<?php
/*
Template Name: Connexion
*/

if (is_user_logged_in()) {
    wp_redirect(site_url('/passer-un-quiz'));
    exit;
}

$errors = array();
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['my_login_nonce']) && wp_verify_nonce($_POST['my_login_nonce'], 'my_login_action')) {
    $login = sanitize_user($_POST['login']);
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $errors[] = 'Tous les champs sont obligatoires.';
    } else {
        $creds = array(
            'user_login'    => $login,
            'user_password' => $password,
            'remember'      => true
        );
        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            $errors[] = 'Identifiants incorrects.';
        } else {
            $success = true;
            wp_redirect(site_url('/passer-un-quiz'));
            exit;
        }
    }
}

get_header();
?>

<div class="connexion-form" style="max-width:400px;margin:2em auto;">
    <h1>Connexion</h1>
    <?php if ($errors) {
        echo '<div style="color:red;"><ul>';
        foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
        echo '</ul></div>';
    } ?>
    <?php if ($success) {
        echo '<div style="color:green;">Connexion réussie ! Redirection...</div>';
    } else { ?>
    <form method="POST">
        <label>Pseudo ou Email<br><input type="text" name="login" required></label><br><br>
        <label>Mot de passe<br><input type="password" name="password" required></label><br><br>
        <?php wp_nonce_field('my_login_action', 'my_login_nonce'); ?>
        <button type="submit">Se connecter</button>
    </form>
    <p style="margin-top:1em;">
        Pas encore inscrit ? <a href="<?php echo site_url('/inscription'); ?>">Créer un compte</a>
    </p>
    <?php } ?>
</div>

<?php get_footer(); ?>
