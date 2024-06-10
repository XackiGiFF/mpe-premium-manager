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

$mpe_premium_manager = null;

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

    if (!wp_next_scheduled('deactivate_expired_premiums_hook')) {
        wp_schedule_event(time(), 'daily', 'deactivate_expired_premiums_hook');
    }

}

// Ensure our activation hook is called when the plugin is activated
register_activation_hook(__FILE__, 'mpe_premium_manager_activate');


// Deactivation hook for cleaning up the plugin environment
function mpe_premium_manager_deactivate(): void
{

    // Include the admin panel file
    require_once(MPE_PREMIUM_MANAGER_DIR . 'includes/class-mpe-premium-manager-activate.php');

    wp_clear_scheduled_hook('deactivate_expired_premiums_hook');
    // Deactivate the plugin
    MPEPremiumManagerDeactivate::deactivate();
    flush_rewrite_rules();
}

// Ensure our deactivation hook is called when the plugin is deactivated
register_deactivation_hook(__FILE__, 'mpe_premium_manager_deactivate');

// Load the plugin
require_once(MPE_PREMIUM_MANAGER_DIR . 'includes/class-mpe-premium-manager.php');

function run_mpe_premium_manager(): MPEPremiumManager
{
    return new MPEPremiumManager();
}

$mpe_premium_manager = run_mpe_premium_manager();

/**
 * Функция для обработки задачи деактивации премиумов
 */
function handle_deactivate_expired_premiums_hook() {
    global $mpe_premium_manager;
    $mpe_premium_manager->deactivate_expired_premiums();
}

// Регистрация обработчика для кастомного хука
add_action('deactivate_expired_premiums_hook', 'handle_deactivate_expired_premiums_hook');

// Регистрация хука для ежедневного отключения истекших премиумов
if (!wp_next_scheduled('deactivate_expired_premiums_hook')) {
    wp_schedule_event(time(), 'daily', 'deactivate_expired_premiums_hook');
}

add_action('deactivate_expired_premiums_hook', function() {
    global $mpe_premium_manager;
    $mpe_premium_manager->deactivate_expired_premiums();
});

function mpe_premium_manager_add_premium($user_id, $days): void
{
    global $mpe_premium_manager;
    $mpe_premium_manager->add_premium($user_id, $days);
}

// Регистрация обработчика для кастомного хука
add_action('mpe_premium_manager_add_premium', 'mpe_premium_manager_add_premium', 10, 2);

function mpe_premium_manager_del_premium($user_id): void
{
    global $mpe_premium_manager;
    $mpe_premium_manager->del_premium($user_id);
}

// Регистрация обработчика для кастомного хука
add_action('mpe_premium_manager_del_premium', 'mpe_premium_manager_del_premium', 10);

function handle_get_user_premium_data()
{
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
        'premium_end_date' => $premium_end_date,
        'premium_remaining_days' => $premium_end_date ? floor((strtotime($premium_end_date) - time()) / 86400) : 0
    ));
}

add_action('wp_ajax_get_user_premium_data', 'handle_get_user_premium_data');


// Вызов кастомного хука добавления премиума
add_action('init', function() {
    $user_id = 1; // ID пользователя
    $days = 150; // Количество дней премиума

    // Вызов кастомного хука
    do_action('mpe_premium_manager_add_premium', $user_id, $days);
});

// Вызов кастомного хука удаления премиума
add_action('init', function() {
    $user_id = 1; // ID пользователя

    // Вызов кастомного хука
    do_action('mpe_premium_manager_del_premium', $user_id);
});