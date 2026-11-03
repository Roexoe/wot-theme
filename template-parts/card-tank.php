<article <?php post_class('tank-card'); ?>>
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium'); ?>
        <?php endif; ?>
        <h3><?php the_title(); ?></h3>
    </a>
    <p class="tank-meta">
        Tier <?php echo esc_html(get_post_meta(get_the_ID(), 'tank_tier', true)); ?>
        &middot; <?php echo get_the_term_list(get_the_ID(), 'nation', '', ', '); ?>
    </p>
</article>
