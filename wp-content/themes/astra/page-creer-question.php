<?php
/* Template Name: Créer une question */
get_header();
?>
 
<h1>Créer une question</h1>
 
<form method="POST">
    <?php wp_nonce_field('my_quiz_create_question_nonce'); ?>
    <label>Question :
        <input type="text" name="question" required>
    </label>
    <br>
    <label>Réponses (séparées par virgule) :
        <input type="text" name="answers" required>
    </label>
    <br>
    <label>Bonne réponse :
        <input type="text" name="correct_answer" required>
    </label>
    <br>
    <button type="submit" name="my_quiz_create_question">Envoyer</button>
</form>
 
<?php get_footer(); ?>