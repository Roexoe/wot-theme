<?php get_header(); ?>

<?php $author = get_queried_object(); ?>

<header class="author-header">
    <?php echo get_avatar($author->ID, 96); ?>
    <h1><?php echo esc_html($author->display_name); ?></h1>
    <p><?php echo esc_html(get_the_author_meta('description', $author->ID)); ?></p>
    <p>Aantal berichten: <?php echo (int) count_user_posts($author->ID); ?></p>
</header>

<?php if (have_posts()) : ?>
    <div class="card-grid">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/card', 'post'); ?>
        <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
<?php endif; ?>

<?php get_footer(); ?>
