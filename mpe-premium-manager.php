<?php
/*
Plugin Name: Mpe Premium Manager
Plugin URI: http://github.com/XackiGiFF/mpe-premium-manager
Description: Manage premium status, expiration date, and remaining days for users.
Version: 1.0.2
Author: XackiGiFF
Author URI: http://github.com/XackiGiFF
License: A "Slug" license name e.g. GPL2
Requires at least: 6.5.2
Requires PHP: 8.0
Tested up to: 6.5.2
Stability: Stable
Text Domain: mpe-premium-manager
Domain Path: /languages

*/

use XackiGiFF\PremiumManager\MPEPremiumManager;
use XackiGiFF\PremiumManager\MPEPremiumManagerActivate;
use XackiGiFF\PremiumManager\MPEPremiumManagerDeactivate;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('MPE_PREMIUM_MANAGER_DIR', plugin_dir_path(__FILE__));
define('MPE_PREMIUM_MANAGER_URL', plugin_dir_url(__FILE__));

/* Debugging */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/* End Debugging */


// Activation hook for setting up the plugin environment
function mpe_premium_manager_activate(): void
{
    // Ensure backward compatibility with earlier WordPress versions
    if ( ! function_exists( 'add_action' ) ) {
        require_once ABSPATH . 'wp-includes/plugin.php';
    }

    // Include the admin panel file
    require_once(MPE_PREMIUM_MANAGER_DIR . 'includes/class-mpe-premium-manager-activate.php');

    // Activate the plugin
    MPEPremiumManagerActivate::activate();
}

// Ensure our activation hook is called when the plugin is activated
register_activation_hook(__FILE__, 'mpe_premium_manager_activate');


// Deactivation hook for cleaning up the plugin environment
function mpe_premium_manager_deactivate(): void
{

    // Include the admin panel file
    require_once(MPE_PREMIUM_MANAGER_DIR . 'includes/class-mpe-premium-manager-activate.php');

    // Deactivate the plugin
    MPEPremiumManagerDeactivate::deactivate();
    flush_rewrite_rules();
}

// Ensure our deactivation hook is called when the plugin is deactivated
register_deactivation_hook(__FILE__, 'mpe_premium_manager_deactivate');

// Load the plugin
require_once(MPE_PREMIUM_MANAGER_DIR . 'includes/class-mpe-premium-manager.php');

function run_mpe_premium_manager(): void
{
    $plugin = new MPEPremiumManager();
}

run_mpe_premium_manager();


// Function to add days to premium status
/**
 * @throws Exception
 */
function mpe_add_days_to_premium($user_id, $days_to_add): void
{
    $end_date_str = get_user_meta($user_id, 'premium_end_date', true);
    $end_date = $end_date_str ? new DateTime($end_date_str) : new DateTime();
    $end_date->add(new DateInterval('P' . $days_to_add . 'D')); // Adds specified number of days.

    update_user_meta($user_id, 'premium_end_date', $end_date->format('Y-m-d'));
}

add_action('wp_ajax_get_user_premium_data', 'handle_get_user_premium_data');

function handle_get_user_premium_data() {
    // Check the nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_user_details_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed'));
        wp_die();
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    // Fetch user premium status and end date
    $premium_status = get_user_meta($user_id, 'premium_status', true);
    $premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

    // Send a json response back
    wp_send_json_success(array(
        'premium_status' => $premium_status,
        'premium_end_date' => $premium_end_date
    ));
}
