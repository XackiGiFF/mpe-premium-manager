<?php

namespace XackiGiFF\PremiumManager;

class MPEPremiumManagerDeactivate
{
    public static function deactivate(): void
    {

        global $wp_roles;
        $wp_roles->remove_role('mpe_premium_user');

        // Add a new user meta field for premium status if not already available
        $users = get_users();
        foreach ( $users as $user ) {
            delete_metadata('user', $user->ID,'premium_status');
            delete_metadata('user', $user->ID,'premium_end_date');
        }

        // Check whether we need to setup any new capabilities or roles specifically for Premium Users
        // Here you might want to establish new roles or capabilities if your plugin requires it
        flush_rewrite_rules();
    }
}