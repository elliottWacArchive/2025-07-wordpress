<?php
/*
Plugin Name: My Stats
Plugin URI: http://localhost
Description: Affiche des statistiques visiteurs & quiz avec ChartJS.
Version: 1.0
Author: Loucman Machababy
*/

// Crée un menu admin
add_action('admin_menu', 'my_stats_menu');

function my_stats_menu() {
  add_menu_page(
    'My Stats',         // Titre de la page
    'My Stats',         // Nom du menu
    'manage_options',   // Capability requise
    'my_stats',         // Slug de la page
    'my_stats_page',    // Fonction callback
    'dashicons-chart-bar', // Icône
    6                   // Position
  );
}

// Contenu de la page admin
function my_stats_page() {
  echo '<div class="wrap">';
  echo '<h1>Tableau de bord - Statistiques Quiz</h1>';
  echo '<canvas id="myChart" width="600" height="400"></canvas>';
  echo '</div>';

  ?>
 
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  