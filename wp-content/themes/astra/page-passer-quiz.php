<?php
/* Template Name: Passer un quiz */
session_start();
 
global $wpdb;
$table_name = $wpdb->prefix . 'my_quiz_questions';
 
// Charger toutes les questions
$questions = $wpdb->get_results("SELECT * FROM $table_name");
 
// index + score
if (!isset($_SESSION['quiz_index'])) {
    $_SESSION['quiz_index'] = 0;
    $_SESSION['quiz_score'] = 0;
}
 
$index = $_SESSION['quiz_index'];
 
// TRAITER LA SOUMISSION AVANT TOUT AFFICHAGE
if (isset($_POST['submit_quiz_answer'])) {
    $question = $questions[$index];
    $user_answer = sanitize_text_field($_POST['user_answer']);
 
    if ($user_answer === $question->correct_answer) {
        $_SESSION['quiz_score']++;
    }
 
    $_SESSION['quiz_index']++;
    wp_redirect(site_url('/passer-un-quiz'));
    exit;
}
 
// on affiche
get_header();
 
if ($index < count($questions)) {
    $question = $questions[$index];
    $answers = maybe_unserialize($question->answers);
    ?>
 
    <h1>Question <?php echo ($index + 1); ?> : <?php echo esc_html($question->question); ?></h1>
 
    <form method="POST">
        <?php foreach ($answers as $answer) : ?>
            <label>
                <input type="radio" name="user_answer" value="<?php echo esc_attr($answer); ?>" required>
                <?php echo esc_html($answer); ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <button type="submit" name="submit_quiz_answer">Envoyer réponse</button>
    </form>
 
    <?php
} else {
    echo "<h1>Quiz terminé !</h1>";
    echo "<p>Score : " . $_SESSION['quiz_score'] . " / " . count($questions) . "</p>";
    session_destroy();
}
 
get_footer();
 
 
