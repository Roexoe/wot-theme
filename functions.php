<?php
/**
 * Themasetup: ondersteuning voor functies en menulocaties
 */
function wot_setup() {
    add_theme_support('title-tag');        // WordPress zet zelf de <title>
    add_theme_support('post-thumbnails');  // uitgelichte afbeeldingen
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);

    register_nav_menus([
        'primary' => 'Hoofdmenu',
        'footer'  => 'Footermenu',
    ]);
}
add_action('after_setup_theme', 'wot_setup');

/**
 * CSS laden
 */
function wot_enqueue_assets() {
    wp_enqueue_style('wot-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'wot_enqueue_assets');

/**
 * Content type: tank
 */
function wot_register_tank_post_type() {
    register_post_type('tank', [
        'labels' => [
            'name'          => 'Tanks',
            'singular_name' => 'Tank',
            'add_new_item'  => 'Nieuwe tank toevoegen',
            'edit_item'     => 'Tank bewerken',
        ],
        'public'       => true,
        'has_archive'  => true,              // geeft /tanks/ als archief
        'rewrite'      => ['slug' => 'tanks'],
        'menu_icon'    => 'dashicons-shield',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'wot_register_tank_post_type');

/**
 * Taxonomieën: natie en tanktype
 */
function wot_register_taxonomies() {
    register_taxonomy('nation', 'tank', [
        'labels'            => ['name' => 'Naties', 'singular_name' => 'Natie'],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'natie'],
    ]);

    register_taxonomy('tank_type', 'tank', [
        'labels'            => ['name' => 'Tanktypes', 'singular_name' => 'Tanktype'],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'type'],
    ]);
}
add_action('init', 'wot_register_taxonomies');

/**
 * Extra velden voor een tank: meta box
 */
function wot_tank_fields() {
    return [
        'tier'   => 'Tier (1-10)',
        'damage' => 'Damage per schot',
        'armor'  => 'Pantser voorkant (mm)',
        'speed'  => 'Topsnelheid (km/u)',
    ];
}

function wot_add_tank_meta_box() {
    add_meta_box('wot_tank_stats', 'Tank stats', 'wot_render_tank_meta_box', 'tank');
}
add_action('add_meta_boxes', 'wot_add_tank_meta_box');

function wot_render_tank_meta_box($post) {
    wp_nonce_field('wot_save_tank_stats', 'wot_tank_nonce');

    foreach (wot_tank_fields() as $key => $label) {
        $value = get_post_meta($post->ID, 'tank_' . $key, true);
        echo '<p><label>' . esc_html($label) . '<br>';
        echo '<input type="number" name="tank_' . esc_attr($key) . '" value="' . esc_attr($value) . '"></label></p>';
    }
}

function wot_save_tank_stats($post_id) {
    // Beveiliging: alleen opslaan als het formulier echt van onze meta box komt
    if (!isset($_POST['wot_tank_nonce']) || !wp_verify_nonce($_POST['wot_tank_nonce'], 'wot_save_tank_stats')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (array_keys(wot_tank_fields()) as $key) {
        if (isset($_POST['tank_' . $key])) {
            update_post_meta($post_id, 'tank_' . $key, absint($_POST['tank_' . $key]));
        }
    }
}
add_action('save_post_tank', 'wot_save_tank_stats');

/**
 * Filter op het tank-archief via de URL
 */
function wot_filter_tank_archive($query) {
    // Alleen de hoofdquery op de voorkant van het tank-archief
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive('tank')) {
        return;
    }

    // Filter op natie en/of tanktype (taxonomieën)
    $tax_query = [];
    $params = [
        'nation'    => 'filter_natie',
        'tank_type' => 'filter_type',
    ];
    foreach ($params as $taxonomy => $param) {
        if (!empty($_GET[$param])) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => sanitize_title(wp_unslash($_GET[$param])),
            ];
        }
    }
    if (!empty($tax_query)) {
        $query->set('tax_query', $tax_query);
    }

    // Filter op tier (meta-veld)
    if (!empty($_GET['filter_tier'])) {
        $query->set('meta_query', [[
            'key'   => 'tank_tier',
            'value' => absint($_GET['filter_tier']),
            'type'  => 'NUMERIC',
        ]]);
    }
}
add_action('pre_get_posts', 'wot_filter_tank_archive');
