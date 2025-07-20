<?php
/*
Plugin Name: My Stats
Plugin URI: http://localhost
Description: Affiche des statistiques visiteurs & quiz avec ChartJS.
Version: 1.0
Author: Loucman Machababy
*/

//Create admin menu
add_action('admin_menu', 'my_stats_menu');

function my_stats_menu() {
  add_menu_page(
    'My Stats',         
    'My Stats',         
    'manage_options',   
    'my_stats',         
    'my_stats_page',    
    'dashicons-chart-bar',
    6                   
  );
}

//Content page admin
function my_stats_page() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'my_stats_logs';

  // Récupérer le nombre de quiz passés sur différentes périodes
  $now = current_time('mysql');
  $count_24h = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(%s, INTERVAL 1 DAY)", $now));
  $count_7d = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(%s, INTERVAL 7 DAY)", $now));
  $count_30d = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(%s, INTERVAL 30 DAY)", $now));
  $count_12m = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE created_at >= DATE_SUB(%s, INTERVAL 12 MONTH)", $now));

  // Passer les données au JS
  $data = [$count_24h, $count_7d, $count_30d, $count_12m];

  echo '<div class="wrap">';
  echo '<h1>Tableau de bord - Statistiques Quiz</h1>';
  echo '<canvas id="myChart" width="600" height="400"></canvas>';

  // Statistiques détaillées par quiz
  $quiz_stats = $wpdb->get_results("SELECT quiz_id, COUNT(*) as total FROM $table_name GROUP BY quiz_id");
  echo '<h2>Stats par Quiz</h2>';
  echo '<table class="widefat"><thead><tr><th>ID Quiz</th><th>Nombre de passages</th></tr></thead><tbody>';
  foreach ($quiz_stats as $row) {
    echo '<tr><td>' . esc_html($row->quiz_id) . '</td><td>' . esc_html($row->total) . '</td></tr>';
  }
  echo '</tbody></table>';

  // Statistiques détaillées par utilisateur
  $user_stats = $wpdb->get_results("SELECT user_id, COUNT(*) as total FROM $table_name GROUP BY user_id");
  echo '<h2>Stats par Utilisateur</h2>';
  echo '<table class="widefat"><thead><tr><th>ID Utilisateur</th><th>Nombre de quiz passés</th></tr></thead><tbody>';
  foreach ($user_stats as $row) {
    echo '<tr><td>' . esc_html($row->user_id) . '</td><td>' . esc_html($row->total) . '</td></tr>';
  }
  echo '</tbody></table>';

  // Top utilisateurs (ceux qui ont passé le plus de quiz)
  $top_users = $wpdb->get_results("SELECT user_id, COUNT(*) as total FROM $table_name GROUP BY user_id ORDER BY total DESC LIMIT 5");
  echo '<h2>Top 5 Utilisateurs</h2>';
  echo '<table class="widefat"><thead><tr><th>ID Utilisateur</th><th>Quiz passés</th></tr></thead><tbody>';
  foreach ($top_users as $row) {
    echo '<tr><td>' . esc_html($row->user_id) . '</td><td>' . esc_html($row->total) . '</td></tr>';
  }
  echo '</tbody></table>';

  // Visiteurs uniques (user_id ou IP si user_id null)
  $unique_users = $wpdb->get_var("SELECT COUNT(DISTINCT IFNULL(user_id, ip_address)) FROM $table_name");
  echo '<h2>Visiteurs uniques</h2>';
  echo '<p><strong>' . esc_html($unique_users) . '</strong> visiteurs uniques ont passé au moins un quiz.</p>';

  echo '</div>';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const statsData = <?php echo json_encode($data); ?>;
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['24h', '7 jours', '30 jours', '12 mois'],
        datasets: [{
          label: '# Quiz Passés',
          data: statsData,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
  <?php
}

/**
 * Enregistre un log de passage de quiz dans la table my_stats_logs
 * @param int|null $user_id
 * @param int|null $quiz_id
 */
function my_stats_log_quiz($user_id = null, $quiz_id = null) {
  global $wpdb;
  $table_name = $wpdb->prefix . 'my_stats_logs';

  $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
  $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

  $wpdb->insert(
    $table_name,
    [
      'user_id' => $user_id,
      'quiz_id' => $quiz_id,
      'ip_address' => $ip_address,
      'user_agent' => $user_agent,
      'created_at' => current_time('mysql')
    ]
  );
}

register_activation_hook(__FILE__, 'my_stats_install');

function my_stats_install() {
  global $wpdb;

  $table_name = $wpdb->prefix . 'my_stats_logs';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) DEFAULT NULL,
    quiz_id bigint(20) DEFAULT NULL,
    ip_address varchar(100) DEFAULT NULL,
    user_agent text DEFAULT NULL,
    score float DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
