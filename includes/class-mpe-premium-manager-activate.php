<?php

namespace XackiGiFF\PremiumManager;

require_once(MPE_PREMIUM_MANAGER_DIR . 'public/view/class-mpe-premium-manager-public-display.php');
use XackiGiFF\PremiumManager\public\view\MPEPremiumManagerPublicDisplay;

class MPEPremiumManagerActivate
{
    public static function activate(): void
    {

        global $wp_roles;
        $wp_roles->add_role('mpe_premium_user', 'MPE Premium User', array(
            'read' => true,
            'level' => 10
        ));

        // Add a new user meta field for premium status if not already available
        $users = get_users();
        foreach ( $users as $user ) {
            add_user_meta( $user->ID, 'premium_status', 'off', true );
            add_user_meta( $user->ID, 'premium_end_date', '', true );
        }

        new MPEPremiumManagerPublicDisplay();

        $template_standart = MPEPremiumManagerPublicDisplay::get_template('standart') ?? '';

        $template_premium = MPEPremiumManagerPublicDisplay::get_template('premium') ?? '';

        $default_options = [
            'mpe_premium_manager_days' => 30,
            'mpe_premium_manager_success_message' => 'Your premium status has been updated for {days} days.',
            'mpe_premium_manager_link_on_premium' => '/premium/',
            'mpe_premium_manager_link_on_premium_text' => 'Go to Premium',
            'mpe_premium_manager_template_standart' => $template_standart,
            'mpe_premium_manager_template_premium' => $template_premium
        ];

        foreach ($default_options as $option_name => $default_value) {
            // Проверяем, существует ли уже такая опция
            if (get_option($option_name) === false) {
                add_option($option_name, $default_value);
            }
        }

        // Check whether we need to setup any new capabilities or roles specifically for Premium Users
        // Here you might want to establish new roles or capabilities if your plugin requires it
        flush_rewrite_rules();
    }
}