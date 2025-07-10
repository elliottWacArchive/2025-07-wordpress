<?php
/*
Plugin Name: Mon Quiz Plugin
Plugin URI: http://localhost
Description: Plugin pour gérer les quiz.
Version: 1.0
Author: Loucman MACHABABY
*/

register_activation_hook(__FILE__, 'mon_quiz_plugin_install');
register_uninstall_hook(__FILE__, 'mon_quiz_plugin_uninstall');
