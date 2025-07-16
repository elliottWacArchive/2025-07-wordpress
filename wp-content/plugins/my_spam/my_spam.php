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
    echo '<div class="wrap">';
    echo '<h1>Envoi d\'emails ciblés</h1>';

    // Traitement de l'envoi d'email
    if (isset($_POST['my_spam_send_mail'])) {
        $subject = sanitize_text_field($_POST['my_spam_subject']);
        $message = wp_kses_post($_POST['my_spam_message']);
        $users = get_users(['fields' => ['user_email']]);
        $emails = array_map(function($u) { return $u->user_email; }, $users);
        $sent = 0;
        foreach ($emails as $email) {
            if (wp_mail($email, $subject, $message)) {
                $sent++;
            }
        }
        echo '<div class="notice notice-success"><p>Email envoyé à ' . esc_html($sent) . ' utilisateurs.</p></div>';
    }

    // Formulaire d'envoi
    echo '<form method="POST">';
    echo '<p><label>Sujet :<br><input type="text" name="my_spam_subject" required style="width: 400px;"></label></p>';
    echo '<p><label>Message :<br><textarea name="my_spam_message" rows="6" cols="60" required></textarea></label></p>';
    echo '<p><button type="submit" name="my_spam_send_mail" class="button button-primary">Envoyer à tous les utilisateurs</button></p>';
    echo '</form>';
    echo '</div>';
} 