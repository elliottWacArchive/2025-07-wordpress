<?php
/* Template Name: Créer une question */

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url('/page-connexion') );
    exit;
}
get_header();
?>

<div class="page-wrapper">
  <div class="main-content">
    <h1>Créer une question</h1>
    <form method="POST">
        <?php wp_nonce_field('my_quiz_create_question_nonce'); ?>
        <label>Question :
            <input type="text" name="question" required>
        </label>
        <label>Réponses (séparées par virgule) :
            <input type="text" name="answers" required>
        </label>
        <label>Bonne réponse :
            <input type="text" name="correct_answer" required>
        </label>
        <button type="submit" name="my_quiz_create_question">Envoyer</button>
    </form>
  </div>
  <aside class="sidebar">
    <?php get_sidebar(); ?>
  </aside>
</div>

<?php get_footer(); ?>
