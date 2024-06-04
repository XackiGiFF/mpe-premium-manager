<?php

namespace XackiGiFF\PremiumManager;
use MPEPremiumManagerAdmin;
use MPEPremiumManagerPublic;

class MPEPremiumManager {

    public function __construct()
    {
        $this->load_dependencies();

        $this->define_admin_hooks();
        $this->define_public_hooks();
    }


    private function load_dependencies(): void
    {
        // Include the admin panel file
        require_once(MPE_PREMIUM_MANAGER_DIR . 'admin/class-mpe-premium-manager-admin.php');

        // Include the public file
        require_once(MPE_PREMIUM_MANAGER_DIR . 'public/class-mpe-premium-manager-public.php');
    }

    private function define_admin_hooks(): void
    {
        $plugin_admin = new MPEPremiumManagerAdmin();
    }

    private function define_public_hooks(): void
    {
        $plugin_public = new MPEPremiumManagerPublic();
    }
}