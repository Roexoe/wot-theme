<?php get_header(); ?>

<header class="archive-header">
    <h1><?php the_archive_title(); ?></h1>
    <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
</header>

<?php if (have_posts()) : ?>
    <div class="card-grid">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            // Kies de kaart op basis van het content type
            $card = (get_post_type() === 'tank') ? 'tank' : 'post';
            get_template_part('template-parts/card', $card);
            ?>
        <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
<?php else : ?>
    <p>Niets gevonden in dit archief.</p>
<?php endif; ?>

<?php get_footer(); ?>
