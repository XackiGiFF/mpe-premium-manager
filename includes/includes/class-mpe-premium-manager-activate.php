<?php

namespace XackiGiFF\PremiumManager;

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

        // Check whether we need to setup any new capabilities or roles specifically for Premium Users
        // Here you might want to establish new roles or capabilities if your plugin requires it
        flush_rewrite_rules();
    }
}