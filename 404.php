<?php get_header(); ?>

<section class="error-404">
    <h1>Doelwit niet gevonden</h1>
    <p>Deze pagina bestaat niet (meer). Zoek verder of ga terug naar het hoofdkwartier.</p>

    <?php get_search_form(); ?>

    <p><a class="button" href="<?php echo esc_url(home_url('/')); ?>">Terug naar home</a></p>
</section>

<?php get_footer(); ?>
