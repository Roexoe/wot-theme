<?php get_header(); ?>

<h1>Alle tanks</h1>

<?php get_template_part('template-parts/filter-bar'); ?>

<?php if (have_posts()) : ?>
    <div class="tank-grid">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/card', 'tank'); ?>
        <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
<?php else : ?>
    <p>Geen tanks gevonden.</p>
<?php endif; ?>

<?php get_footer(); ?>
