<article <?php post_class('post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
    <?php endif; ?>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="post-meta">
        <?php echo esc_html(get_the_date()); ?> &middot; <?php the_author_posts_link(); ?>
    </p>
    <?php the_excerpt(); ?>
    <p class="post-terms">
        <?php the_category(', '); ?>
        <?php the_tags('&middot; ', ', '); ?>
    </p>
</article>
