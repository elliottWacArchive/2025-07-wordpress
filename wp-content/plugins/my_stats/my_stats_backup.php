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
  echo '<div class="wrap">';
  echo '<h1>Tableau de bord - Statistiques Quiz</h1>';
  echo '<canvas id="myChart" width="600" height="400"></canvas>';
  echo '</div>';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['24h', '7 jours', '30 jours', '12 mois'],
        datasets: [{
          label: '# Quiz Passés',
          data: [0, 0, 0, 0],
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
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
