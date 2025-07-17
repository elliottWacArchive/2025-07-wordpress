<?php
/*
Plugin Name: My Quiz Plugin
Plugin URI: http://localhost/W-CMS-502-LYN-2-1-wordpress-safiya.jazouli
Description: Plugin personnalisé pour gérer les questionnaires et les quiz.
Version: 1.0
Author: Safiya Jazouli
*/

// bloque l’accès direct
if (!defined('ABSPATH')) exit;

/** créer la table lors de l'activation
 */
register_activation_hook(__FILE__, 'my_quiz_install');
function my_quiz_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_quiz_questions';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        question text NOT NULL,
        answers text NOT NULL,
        correct_answer varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/** supprimer la table lors de la désinstallation
 */
register_uninstall_hook(__FILE__, 'my_quiz_uninstall');
function my_quiz_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_quiz_questions';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/** gérer le formulaire sur toutes les pages 
 * exemple: Soumission d'une nouvelle question
 */
add_action('wp_loaded', 'my_quiz_handle_form');
function my_quiz_handle_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['my_quiz_create_question'])) {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'my_quiz_create_question_nonce')) {
                wp_die('Erreur de sécurité.');
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'my_quiz_questions';

            $question = sanitize_text_field($_POST['question']);

            $answers = explode(',', $_POST['answers']);
            $answers = array_map('trim', $answers);
            $answers = maybe_serialize($answers);
 
            $correct = sanitize_text_field($_POST['correct_answer']);

            $wpdb->insert(
                $table_name,
                array(
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct
                )
            );

            wp_redirect(site_url('/passer-un-quiz')); 
            exit;
        }

    } 
}

class QuizChoiceWidget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'quiz_choice_widget',
            'Choix du Quiz',
            array('description' => 'Permet de choisir entre poser des questions ou y répondre')
        );

        add_action('init', array($this, 'handle_post_redirect'));
    }

    public function handle_post_redirect() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_action'])) {
            $choice = sanitize_text_field($_POST['quiz_action']);
            if ($choice === 'ask') {
                wp_redirect(site_url('/creer-une-question'));
                exit;
            } elseif ($choice === 'answer') {
                wp_redirect(site_url('/passer-un-quiz'));
                exit;
            }
        }
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Que voulez-vous faire ?' . $args['after_title'];
        ?>
        <form method="POST" action="">
            <button type="submit" name="quiz_action" value="ask">Créer une question</button>
            <button type="submit" name="quiz_action" value="answer">Passer un quiz</button>
        </form>
        <?php
        echo $args['after_widget'];
    }
}

// enregistrer le widget
function register_quiz_choice_widget() {
    register_widget('QuizChoiceWidget');
}
add_action('widgets_init', 'register_quiz_choice_widget');   
