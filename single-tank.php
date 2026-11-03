<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('tank-single'); ?>>
        <h1><?php the_title(); ?></h1>

        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>

        <ul class="tank-stats">
            <?php foreach (wot_tank_fields() as $key => $label) : ?>
                <?php $value = get_post_meta(get_the_ID(), 'tank_' . $key, true); ?>
                <?php if ($value === '') { continue; } ?>
                <li><strong><?php echo esc_html($label); ?>:</strong> <?php echo esc_html($value); ?></li>
            <?php endforeach; ?>
        </ul>

        <p>
            Natie: <?php echo get_the_term_list(get_the_ID(), 'nation', '', ', '); ?><br>
            Type: <?php echo get_the_term_list(get_the_ID(), 'tank_type', '', ', '); ?>
        </p>

        <div class="tank-content"><?php the_content(); ?></div>
    </article>

    <?php
    // Gerelateerde tanks: tweede query, zelfde natie, behalve de huidige tank
    $nations = wp_get_post_terms(get_the_ID(), 'nation', ['fields' => 'ids']);
    if (!empty($nations)) :
        $related = new WP_Query([
            'post_type'      => 'tank',
            'posts_per_page' => 3,
            'post__not_in'   => [get_the_ID()],
            'tax_query'      => [[
                'taxonomy' => 'nation',
                'field'    => 'term_id',
                'terms'    => $nations,
            ]],
        ]);
    ?>
        <?php if ($related->have_posts()) : ?>
            <section class="related-tanks">
                <h2>Meer uit dezelfde natie</h2>
                <div class="tank-grid">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <?php get_template_part('template-parts/card', 'tank'); ?>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
