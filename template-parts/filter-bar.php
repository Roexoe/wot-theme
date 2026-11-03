<?php
$nations = get_terms(['taxonomy' => 'nation', 'hide_empty' => true]);
$types   = get_terms(['taxonomy' => 'tank_type', 'hide_empty' => true]);

// Huidige keuzes, zodat het formulier ze onthoudt
$current_nation = isset($_GET['filter_natie']) ? sanitize_title(wp_unslash($_GET['filter_natie'])) : '';
$current_type   = isset($_GET['filter_type']) ? sanitize_title(wp_unslash($_GET['filter_type'])) : '';
$current_tier   = isset($_GET['filter_tier']) ? absint($_GET['filter_tier']) : 0;
$archive_url    = get_post_type_archive_link('tank');
?>
<form class="filter-bar" method="get" action="<?php echo esc_url($archive_url); ?>">
    <label>Natie
        <select name="filter_natie">
            <option value="">Alle naties</option>
            <?php foreach ($nations as $nation) : ?>
                <option value="<?php echo esc_attr($nation->slug); ?>" <?php selected($current_nation, $nation->slug); ?>>
                    <?php echo esc_html($nation->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Type
        <select name="filter_type">
            <option value="">Alle types</option>
            <?php foreach ($types as $type) : ?>
                <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($current_type, $type->slug); ?>>
                    <?php echo esc_html($type->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Tier
        <select name="filter_tier">
            <option value="">Alle tiers</option>
            <?php for ($tier = 1; $tier <= 10; $tier++) : ?>
                <option value="<?php echo $tier; ?>" <?php selected($current_tier, $tier); ?>>Tier <?php echo $tier; ?></option>
            <?php endfor; ?>
        </select>
    </label>

    <button type="submit">Filter</button>
    <a href="<?php echo esc_url($archive_url); ?>">Wissen</a>
</form>
