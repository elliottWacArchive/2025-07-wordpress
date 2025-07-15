<?php
/* Template Name: Passer un quiz */
get_header();

global $wpdb;
$table_name = $wpdb->prefix . 'my_quiz_questions';

$question = $wpdb->get_row("SELECT * FROM $table_name ORDER BY RAND() LIMIT 1");

if ($question) {
    $answers = maybe_unserialize($question->answers);
    ?>
    <h1>Question : <?php echo esc_html($question->question); ?></h1>
    <form method="POST">
        <?php foreach ($answers as $answer) : ?>
            <label>
                <input type="radio" name="user_answer" value="<?php echo esc_attr($answer); ?>" required>
                <?php echo esc_html($answer); ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit" name="submit_quiz_answer">Envoyer réponse</button>
    </form>
    <?php

    if (isset($_POST['submit_quiz_answer'])) {
        $user_answer = sanitize_text_field($_POST['user_answer']);
        if ($user_answer === $question->correct_answer) {
            echo "<p>Bonne réponse !</p>";
        } else {
            echo "<p>Mauvaise réponse.</p>";
        }
    }

} else {
    echo "<p>Aucune question disponible.</p>";
}

get_footer();
?>
