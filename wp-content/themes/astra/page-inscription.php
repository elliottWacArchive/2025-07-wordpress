<?php
/*
Template Name: Inscription
*/

get_header();

if (is_user_logged_in()) {
    echo '<p>Vous êtes déjà connecté.</p>';
    get_footer();
    exit;
}

$errors = array();
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['my_register_nonce']) && wp_verify_nonce($_POST['my_register_nonce'], 'my_register_action')) {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = 'Tous les champs sont obligatoires.';
    } elseif (!is_email($email)) {
        $errors[] = 'Email invalide.';
    } elseif (username_exists($username)) {
        $errors[] = 'Ce pseudo existe déjà.';
    } elseif (email_exists($email)) {
        $errors[] = 'Cet email est déjà utilisé.';
    } else {
        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            $errors[] = $user_id->get_error_message();
        } else {
            $success = true;
            wp_redirect(wp_login_url());
            exit;
        }
    }
}
?>
<div class="inscription-form" style="max-width:400px;margin:2em auto;">
    <h1>Créer un compte</h1>
    <?php if ($errors) {
        echo '<div style="color:red;"><ul>';
        foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
        echo '</ul></div>';
    } ?>
    <?php if ($success) {
        echo '<div style="color:green;">Inscription réussie ! Redirection...</div>';
    } else { ?>
    <form method="POST">
        <label>Pseudo<br><input type="text" name="username" required></label><br><br>
        <label>Email<br><input type="email" name="email" required></label><br><br>
        <label>Mot de passe<br><input type="password" name="password" required></label><br><br>
        <?php wp_nonce_field('my_register_action', 'my_register_nonce'); ?>
        <button type="submit">S'inscrire</button>
    </form>
    <?php } ?>
</div>
<?php get_footer(); ?> 