<?php
/*
Plugin Name: My Spam
Description: Envoi d'emails ciblés aux utilisateurs selon différents critères.
Version: 1.0
Author: Loucman
*/

// Ajouter un menu dans l'admin
add_action('admin_menu', 'my_spam_menu');
function my_spam_menu() {
    add_menu_page(
        'My Spam',
        'My Spam',
        'manage_options',
        'my_spam',
        'my_spam_page',
        'dashicons-email-alt',
        7
    );
}

// Page d'admin de base
function my_spam_page() {
    global $wpdb;
    echo '<div class="wrap">';
    echo '<h1>Envoi d\'emails ciblés</h1>';

    // Récupérer la liste des quiz existants
    $quiz_table = $wpdb->prefix . 'my_quiz_questions';
    $quiz_list = $wpdb->get_results("SELECT DISTINCT id FROM $quiz_table");

    // Traitement de l'envoi d'email ciblé
    if (isset($_POST['my_spam_send_mail_quiz'])) {
        $subject = sanitize_text_field($_POST['my_spam_subject']);
        $message = wp_kses_post($_POST['my_spam_message']);
        $quiz_id = intval($_POST['my_spam_quiz_id']);
        $mode = sanitize_text_field($_POST['my_spam_mode']);

        // Récupérer les utilisateurs selon le critère
        $logs_table = $wpdb->prefix . 'my_stats_logs';
        if ($mode === 'has_passed') {
            // Utilisateurs ayant passé ce quiz
            $user_ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM $logs_table WHERE quiz_id = %d AND user_id IS NOT NULL", $quiz_id));
        } else {
            // Utilisateurs n'ayant PAS passé ce quiz
            $all_users = get_users(['fields' => ['ID', 'user_email']]);
            $passed_users = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT user_id FROM $logs_table WHERE quiz_id = %d AND user_id IS NOT NULL", $quiz_id));
            $user_ids = array_diff(wp_list_pluck($all_users, 'ID'), $passed_users);
        }
        $emails = [];
        foreach ($user_ids as $uid) {
            $user = get_userdata($uid);
            if ($user && $user->user_email) {
                $emails[] = $user->user_email;
            }
        }
        $sent = 0;
        foreach ($emails as $email) {
            if (wp_mail($email, $subject, $message)) {
                $sent++;
            }
        }
        echo '<div class="notice notice-success"><p>Email envoyé à ' . esc_html($sent) . ' utilisateurs.</p></div>';
    }

    // Formulaire d'envoi ciblé
    echo '<form method="POST">';
    echo '<p><label>Quiz ciblé : <select name="my_spam_quiz_id" required>';
    foreach ($quiz_list as $quiz) {
        echo '<option value="' . esc_attr($quiz->id) . '">Quiz ID ' . esc_html($quiz->id) . '</option>';
    }
    echo '</select></label></p>';
    echo '<p><label>Sujet :<br><input type="text" name="my_spam_subject" required style="width: 400px;"></label></p>';
    echo '<p><label>Message :<br><textarea name="my_spam_message" rows="6" cols="60" required></textarea></label></p>';
    echo '<p>';
    echo '<button type="submit" name="my_spam_send_mail_quiz" value="1" class="button button-primary" onclick="this.form.my_spam_mode.value=\'has_passed\'">Envoyer à ceux qui ont passé ce quiz</button> ';
    echo '<button type="submit" name="my_spam_send_mail_quiz" value="1" class="button" onclick="this.form.my_spam_mode.value=\'not_passed\'">Envoyer à ceux qui n\'ont PAS passé ce quiz</button>';
    echo '<input type="hidden" name="my_spam_mode" value="has_passed">';
    echo '</p>';
    echo '</form>';
    echo '</div>';
} 