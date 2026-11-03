<?php get_header(); ?>

<section class="hero">
    <h1><?php bloginfo('name'); ?></h1>
    <p><?php bloginfo('description'); ?></p>
    <a class="button" href="<?php echo esc_url(get_post_type_archive_link('tank')); ?>">Bekijk alle tanks</a>
</section>

<?php
// Uitgelicht: de 4 tanks met de hoogste tier
$featured = new WP_Query([
    'post_type'      => 'tank',
    'posts_per_page' => 4,
    'meta_key'       => 'tank_tier',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
]);
?>
<?php if ($featured->have_posts()) : ?>
    <section>
        <h2>Uitgelichte tanks</h2>
        <div class="tank-grid">
            <?php while ($featured->have_posts()) : $featured->the_post(); ?>
                <?php get_template_part('template-parts/card', 'tank'); ?>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

<?php
// Laatste nieuws: de 3 nieuwste gewone berichten
$news = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
]);
?>
<?php if ($news->have_posts()) : ?>
    <section>
        <h2>Laatste nieuws en guides</h2>
        <div class="card-grid">
            <?php while ($news->have_posts()) : $news->the_post(); ?>
                <?php get_template_part('template-parts/card', 'post'); ?>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>
