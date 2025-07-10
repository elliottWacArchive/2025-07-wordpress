<?php
/*
Plugin Name: My Stats
Plugin URI: http://localhost
Description: Affiche des statistiques visiteurs & quiz avec ChartJS.
Version: 1.0
Author: Loucman Machababy
*/

function my_stats_menu() {
  add_menu_page(
    'My Stats',         // Title
    'My Stats',         // Menu name
    'manage_options',   // Required capability
    'my_stats',         // Page slug
    'my_stats_page',    // Callback function
    'dashicons-chart-bar', // Icon
    6                   // Position
  );
}