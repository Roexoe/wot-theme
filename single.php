<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('post-single'); ?>>
        <h1><?php the_title(); ?></h1>
        <p class="post-meta">
            <?php echo esc_html(get_the_date()); ?> &middot; door <?php the_author_posts_link(); ?>
        </p>

        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>

        <div class="post-content"><?php the_content(); ?></div>

        <p class="post-terms">
            <?php the_category(', '); ?>
            <?php the_tags('&middot; ', ', '); ?>
        </p>
    </article>

    <?php the_post_navigation(); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
