<?php

function Miku_supports() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('widgets');
}

function Miku_register_assets() {
    wp_register_style('miku', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('miku');
    wp_enqueue_script('miku-sidebar', get_template_directory_uri() . '/assets/js/sidebar.js', array(), '1.0', true);
}

function Miku_widgets_init() {
    register_sidebar(array(
        'name'          => 'Sidebar Miku',
        'id'            => 'miku-sidebar',
        'description'   => 'Zone de widgets pour la sidebar du thème Miku',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4><div class="widget-content">',
    ));
}

function miku_create_pages() {
    $pages = array(
        'creer-question' => array(
            'title' => 'Créer une Question',
            'content' => '[miku_question_form]'
        ),
        'quiz' => array(
            'title' => 'Quiz',
            'content' => '[miku_quiz_list]'
        ),
        'admin-articles' => array(
            'title' => 'Articles Administration',
            'content' => '[miku_admin_articles]'
        )
    );
    
    foreach ($pages as $slug => $page_data) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));
        }
    }
}

function miku_add_question_meta_box() {
    add_meta_box(
        'miku_post_type',
        'Type de contenu Miku',
        'miku_post_type_callback',
        'post',
        'side',
        'default'
    );
    
    add_meta_box(
        'miku_question_reponses',
        'Réponses de la question',
        'miku_question_reponses_callback',
        'post',
        'normal',
        'default'
    );
}

function miku_post_type_callback($post) {
    wp_nonce_field(basename(__FILE__), 'miku_post_type_nonce');
    $post_type_miku = get_post_meta($post->ID, 'post_type_miku', true);
    
    echo '<p><label for="post_type_miku">Type de contenu :</label></p>';
    echo '<select name="post_type_miku" id="post_type_miku">';
    echo '<option value="">Article normal</option>';
    echo '<option value="question"' . selected($post_type_miku, 'question', false) . '>Question</option>';
    echo '<option value="reponse"' . selected($post_type_miku, 'reponse', false) . '>Réponse</option>';
    echo '</select>';
}

function miku_question_reponses_callback($post) {
    wp_nonce_field(basename(__FILE__), 'miku_question_reponses_nonce');
    $reponse_1 = get_post_meta($post->ID, 'miku_reponse_1', true);
    $reponse_2 = get_post_meta($post->ID, 'miku_reponse_2', true);
    $reponse_3 = get_post_meta($post->ID, 'miku_reponse_3', true);
    $reponse_4 = get_post_meta($post->ID, 'miku_reponse_4', true);
    $bonne_reponse = get_post_meta($post->ID, 'miku_bonne_reponse', true);
    
    echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">';
    echo '<div>';
    echo '<label for="miku_reponse_1"><strong>Réponse A:</strong></label>';
    echo '<input type="text" name="miku_reponse_1" id="miku_reponse_1" value="' . esc_attr($reponse_1) . '" style="width: 100%;">';
    echo '</div>';
    echo '<div>';
    echo '<label for="miku_reponse_2"><strong>Réponse B:</strong></label>';
    echo '<input type="text" name="miku_reponse_2" id="miku_reponse_2" value="' . esc_attr($reponse_2) . '" style="width: 100%;">';
    echo '</div>';
    echo '<div>';
    echo '<label for="miku_reponse_3"><strong>Réponse C:</strong></label>';
    echo '<input type="text" name="miku_reponse_3" id="miku_reponse_3" value="' . esc_attr($reponse_3) . '" style="width: 100%;">';
    echo '</div>';
    echo '<div>';
    echo '<label for="miku_reponse_4"><strong>Réponse D:</strong></label>';
    echo '<input type="text" name="miku_reponse_4" id="miku_reponse_4" value="' . esc_attr($reponse_4) . '" style="width: 100%;">';
    echo '</div>';
    echo '</div>';
    
    echo '<div style="margin-top: 15px;">';
    echo '<label><strong>Bonne réponse:</strong></label><br>';
    echo '<label style="margin-right: 15px;"><input type="radio" name="miku_bonne_reponse" value="1"' . checked($bonne_reponse, '1', false) . '> Réponse A</label>';
    echo '<label style="margin-right: 15px;"><input type="radio" name="miku_bonne_reponse" value="2"' . checked($bonne_reponse, '2', false) . '> Réponse B</label>';
    echo '<label style="margin-right: 15px;"><input type="radio" name="miku_bonne_reponse" value="3"' . checked($bonne_reponse, '3', false) . '> Réponse C</label>';
    echo '<label style="margin-right: 15px;"><input type="radio" name="miku_bonne_reponse" value="4"' . checked($bonne_reponse, '4', false) . '> Réponse D</label>';
    echo '</div>';
}

function miku_save_post_type_meta($post_id) {
    if ((!isset($_POST['miku_post_type_nonce']) || !wp_verify_nonce($_POST['miku_post_type_nonce'], basename(__FILE__))) && 
        (!isset($_POST['miku_question_reponses_nonce']) || !wp_verify_nonce($_POST['miku_question_reponses_nonce'], basename(__FILE__)))) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['post_type_miku'])) {
        update_post_meta($post_id, 'post_type_miku', sanitize_text_field($_POST['post_type_miku']));
    }
    if (isset($_POST['miku_reponse_1'])) {
        update_post_meta($post_id, 'miku_reponse_1', sanitize_text_field($_POST['miku_reponse_1']));
    }
    if (isset($_POST['miku_reponse_2'])) {
        update_post_meta($post_id, 'miku_reponse_2', sanitize_text_field($_POST['miku_reponse_2']));
    }
    if (isset($_POST['miku_reponse_3'])) {
        update_post_meta($post_id, 'miku_reponse_3', sanitize_text_field($_POST['miku_reponse_3']));
    }
    if (isset($_POST['miku_reponse_4'])) {
        update_post_meta($post_id, 'miku_reponse_4', sanitize_text_field($_POST['miku_reponse_4']));
    }
    if (isset($_POST['miku_bonne_reponse'])) {
        update_post_meta($post_id, 'miku_bonne_reponse', sanitize_text_field($_POST['miku_bonne_reponse']));
    }
}
function miku_question_form_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour créer une question.</p>';
    }
    
    if (isset($_POST['submit_question'])) {
        $title = sanitize_text_field($_POST['question_title']);
        $content = sanitize_textarea_field($_POST['question_content']);
        $reponse_1 = sanitize_text_field($_POST['reponse_1']);
        $reponse_2 = sanitize_text_field($_POST['reponse_2']);
        $reponse_3 = sanitize_text_field($_POST['reponse_3']);
        $reponse_4 = sanitize_text_field($_POST['reponse_4']);
        $bonne_reponse = sanitize_text_field($_POST['bonne_reponse']);
        
        if (!empty($title) && !empty($content) && !empty($reponse_1) && !empty($reponse_2) && !empty($reponse_3) && !empty($reponse_4) && !empty($bonne_reponse)) {
            $post_id = wp_insert_post(array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_author' => get_current_user_id()
            ));
            
            if ($post_id) {
                update_post_meta($post_id, 'post_type_miku', 'question');
                update_post_meta($post_id, 'miku_reponse_1', $reponse_1);
                update_post_meta($post_id, 'miku_reponse_2', $reponse_2);
                update_post_meta($post_id, 'miku_reponse_3', $reponse_3);
                update_post_meta($post_id, 'miku_reponse_4', $reponse_4);
                update_post_meta($post_id, 'miku_bonne_reponse', $bonne_reponse);
                echo '<div class="success-message">Question créée avec succès !</div>';
            }
        } else {
            echo '<div class="error-message">Veuillez remplir tous les champs.</div>';
        }
    }
    
    ob_start();
    ?>
<form method="post" class="miku-question-form">
    <div class="form-section">
        <h4>📝 Question</h4>
        <p>
            <label for="question_title">Titre de la question :</label>
            <input type="text" name="question_title" id="question_title" required>
        </p>
        <p>
            <label for="question_content">Contenu de la question :</label>
            <textarea name="question_content" id="question_content" rows="4" required></textarea>
        </p>
    </div>

    <div class="form-section">
        <h4>Réponses proposées</h4>
        <div class="reponses-grid">
            <div class="reponse-item">
                <label for="reponse_1">Réponse A :</label>
                <input type="text" name="reponse_1" id="reponse_1" required>
            </div>
            <div class="reponse-item">
                <label for="reponse_2">Réponse B :</label>
                <input type="text" name="reponse_2" id="reponse_2" required>
            </div>
            <div class="reponse-item">
                <label for="reponse_3">Réponse C :</label>
                <input type="text" name="reponse_3" id="reponse_3" required>
            </div>
            <div class="reponse-item">
                <label for="reponse_4">Réponse D :</label>
                <input type="text" name="reponse_4" id="reponse_4" required>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h4>Bonne réponse</h4>
        <div class="bonne-reponse-selector">
            <label>
                <input type="radio" name="bonne_reponse" value="1" required>
                <span class="radio-label">Réponse A</span>
            </label>
            <label>
                <input type="radio" name="bonne_reponse" value="2" required>
                <span class="radio-label">Réponse B</span>
            </label>
            <label>
                <input type="radio" name="bonne_reponse" value="3" required>
                <span class="radio-label">Réponse C</span>
            </label>
            <label>
                <input type="radio" name="bonne_reponse" value="4" required>
                <span class="radio-label">Réponse D</span>
            </label>
        </div>
    </div>

    <p class="submit-section">
        <input type="submit" name="submit_question" value="Créer la question" class="button">
    </p>
</form>
<?php
    return ob_get_clean();
}

function miku_display_question_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
        'show_answer' => false
    ), $atts);
    
    $post_id = intval($atts['id']);
    $show_answer = filter_var($atts['show_answer'], FILTER_VALIDATE_BOOLEAN);
    
    if (!$post_id) {
        return '<p>ID de question non spécifié.</p>';
    }
    
    $post = get_post($post_id);
    if (!$post || get_post_meta($post_id, 'post_type_miku', true) !== 'question') {
        return '<p>Question non trouvée.</p>';
    }
    
    $reponse_1 = get_post_meta($post_id, 'miku_reponse_1', true);
    $reponse_2 = get_post_meta($post_id, 'miku_reponse_2', true);
    $reponse_3 = get_post_meta($post_id, 'miku_reponse_3', true);
    $reponse_4 = get_post_meta($post_id, 'miku_reponse_4', true);
    $bonne_reponse = get_post_meta($post_id, 'miku_bonne_reponse', true);
    
    ob_start();
    ?>
<div class="miku-question-display" data-question-id="<?php echo $post_id; ?>">
    <div class="question-header">
        <h3 class="question-title"><?php echo esc_html($post->post_title); ?></h3>
        <div class="question-content">
            <?php echo wpautop(esc_html($post->post_content)); ?>
        </div>
    </div>

    <div class="question-answers">
        <div class="answer-option" data-answer="1">
            <label>
                <input type="radio" name="question_<?php echo $post_id; ?>" value="1">
                <span class="answer-label">A</span>
                <span class="answer-text"><?php echo esc_html($reponse_1); ?></span>
            </label>
        </div>
        <div class="answer-option" data-answer="2">
            <label>
                <input type="radio" name="question_<?php echo $post_id; ?>" value="2">
                <span class="answer-label">B</span>
                <span class="answer-text"><?php echo esc_html($reponse_2); ?></span>
            </label>
        </div>
        <div class="answer-option" data-answer="3">
            <label>
                <input type="radio" name="question_<?php echo $post_id; ?>" value="3">
                <span class="answer-label">C</span>
                <span class="answer-text"><?php echo esc_html($reponse_3); ?></span>
            </label>
        </div>
        <div class="answer-option" data-answer="4">
            <label>
                <input type="radio" name="question_<?php echo $post_id; ?>" value="4">
                <span class="answer-label">D</span>
                <span class="answer-text"><?php echo esc_html($reponse_4); ?></span>
            </label>
        </div>
    </div>

    <?php if ($show_answer) : ?>
    <div class="correct-answer">
        <p><strong>Bonne réponse :</strong> <?php echo chr(64 + $bonne_reponse); ?> -
            <?php 
                $correct_answer_text = get_post_meta($post_id, 'miku_reponse_' . $bonne_reponse, true);
                echo esc_html($correct_answer_text);
                ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="question-meta">
        <p>Par <?php echo get_the_author_meta('display_name', $post->post_author); ?> le
            <?php echo get_the_date('j F Y', $post_id); ?></p>
    </div>
</div>
<?php
    return ob_get_clean();
}

function miku_quiz_list_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour accéder aux quiz.</p>';
    }
    
    $questions = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'meta_query' => array(
            array(
                'key' => 'post_type_miku',
                'value' => 'question',
                'compare' => '='
            )
        )
    ));
    
    ob_start();
    ?>
<div class="miku-quiz-list">
    <h3>🎓 Quiz disponibles</h3>
    <?php if ($questions->have_posts()) : ?>
    <div class="quiz-questions">
        <?php while ($questions->have_posts()) : $questions->the_post(); ?>
        <div class="quiz-question-item">
            <h4><?php the_title(); ?></h4>
            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <div class="question-actions">
                <a href="<?php the_permalink(); ?>" class="view-question">Voir la question</a>
                <span class="question-difficulty">🌟 Difficulté: Normale</span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
    <p>Aucune question disponible pour le moment.</p>
    <?php endif; wp_reset_postdata(); ?>
</div>
<?php
    return ob_get_clean();
}

function miku_admin_articles_shortcode() {
    if (!current_user_can('administrator')) {
        return '<p>Accès réservé aux administrateurs.</p>';
    }
    
    $questions = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'post_type_miku',
                'value' => array('question', 'reponse'),
                'compare' => 'IN'
            )
        )
    ));
    
    ob_start();
    ?>
<div class="miku-admin-articles">
    <h3>Gestion des Questions et Réponses</h3>
    <?php if ($questions->have_posts()) : ?>
    <table class="admin-articles-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($questions->have_posts()) : $questions->the_post(); ?>
            <tr>
                <td><?php the_title(); ?></td>
                <td><?php echo get_post_meta(get_the_ID(), 'post_type_miku', true); ?></td>
                <td><?php the_author(); ?></td>
                <td><?php the_date(); ?></td>
                <td>
                    <a href="<?php echo get_edit_post_link(); ?>">Éditer</a>
                    <a href="<?php the_permalink(); ?>">Voir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p>Aucune question ou réponse trouvée.</p>
    <?php endif; wp_reset_postdata(); ?>
</div>
<?php
    return ob_get_clean();
}

add_action('after_setup_theme', 'Miku_supports');
add_action('wp_enqueue_scripts', 'Miku_register_assets');
add_action('widgets_init', 'Miku_widgets_init');
add_action('wp_loaded', 'miku_create_pages');
add_action('add_meta_boxes', 'miku_add_question_meta_box');
add_action('save_post', 'miku_save_post_type_meta');
add_shortcode('miku_question_form', 'miku_question_form_shortcode');
add_shortcode('miku_quiz_list', 'miku_quiz_list_shortcode');
add_shortcode('miku_admin_articles', 'miku_admin_articles_shortcode');
add_shortcode('miku_display_question', 'miku_display_question_shortcode');