<?php
/**
 * Template Name: Page Admin Quiz
 */

if ( ! current_user_can('administrator') ) {
    wp_die('Accès réservé aux administrateurs.');
}

get_header();

wp_enqueue_style('admin-quiz-style', get_template_directory_uri() . '/css/quiz.css');

global $wpdb;
$table = $wpdb->prefix . 'my_quiz_questions';
$questions = $wpdb->get_results("SELECT * FROM $table");

echo '<div class="admin-quiz-container" style="max-width: 900px; margin: auto; padding: 20px;">';
echo '<h1>Quizz</h1>';

if ($questions) {
    foreach ($questions as $q) {
        $answers = maybe_unserialize($q->answers);
        echo '<article style="background: #1a1a1a; padding: 15px; margin-bottom: 20px; border-radius: 10px; color: #fff;">';
        echo '<h2>' . esc_html($q->question) . '</h2>';
        echo '<ul>';
        if (is_array($answers)) {
            foreach ($answers as $a) {
                echo '<li>' . esc_html($a) . '</li>';
            }
        }
        echo '</ul>';
        echo '<p><strong>Bonne réponse : </strong>' . esc_html($q->correct_answer) . '</p>';
        echo '</article>';
    }
} else {
    echo '<p>Aucune question trouvée.</p>';
}

echo '</div>';

get_footer();
?>
