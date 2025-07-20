<?php
/* Template Name: Passer un quiz */

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url('/page-connexion') ); 
    exit;
}

session_start();
get_header();

global $wpdb;
$table_name = $wpdb->prefix . 'my_quiz_questions';
$questions = $wpdb->get_results("SELECT * FROM $table_name");

if (!isset($_SESSION['quiz_index'])) {
    $_SESSION['quiz_index'] = 0;
    $_SESSION['quiz_score'] = 0;
}

$index = $_SESSION['quiz_index'];

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
?>

<div class="page-wrapper">
  <div class="main-content">
    <?php if ($index < count($questions)) :
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
            <button type="submit" name="submit_quiz_answer">Envoyer réponse</button>
        </form>

    <?php else : ?>
        <h1>Quiz terminé !</h1>
        <p>Score : <?php echo $_SESSION['quiz_score']; ?> / <?php echo count($questions); ?></p>
        <?php session_destroy(); ?>
    <?php endif; ?>
  </div>
  <aside class="sidebar">
    <?php get_sidebar(); ?>
  </aside>
</div>

<?php get_footer(); ?>
