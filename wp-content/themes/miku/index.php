<?php get_header(); ?>

<div class="main-container">
    <div class="content-area">
        <div class="site-content">
            <h1>Bienvenue sur le Quiz Miku !</h1>
            <p>Page de quiz autour de la culture japonaise!</p>

            <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
            <article class="post">
                <h2><?php the_title(); ?></h2>
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php endwhile; ?>
            <?php else : ?>
            <p>Aucun contenu trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>