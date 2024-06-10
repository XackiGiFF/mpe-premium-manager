<?php

namespace XackiGiFF\PremiumManager;

class MPEPremiumManagerDeactivate
{
    public static function deactivate(): void
    {

        global $wp_roles;
        $wp_roles->remove_role('mpe_premium_user');

        flush_rewrite_rules();
    }
}