<?php

class MPEPremiumManagerAdmin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        require_once MPE_PREMIUM_MANAGER_DIR . 'admin/class-mpe-premium-manager-admin-fields.php';
    }

    public function add_admin_menu(): void
    {
        add_menu_page(
            'MPE Premium Manager',
            'MPE Premium Manager',
            'manage_options',
            'mpe-premium-manager',
            array($this, 'admin_page'),
            'dashicons-shield-alt',
            110
        );
        add_submenu_page(
            'mpe-premium-manager',
            'Users | MPE Premium Manager',
            'Users Manager',
            'manage_options',
            'mpe-premium-manager');
        add_submenu_page(
            'mpe-premium-manager',
            'Settings - MPE Premium Manager',
            'Settings',
            'manage_options',
            'mpe-premium-manager-users',
            array($this, 'render_admin_manage_users_page'));
    }

    public function admin_page(): void
    {
        require_once MPE_PREMIUM_MANAGER_DIR . 'admin/view/mpe-premium-manager-admin-display.php';
    }

    public function render_admin_manage_users_page(): void
    {
        require_once MPE_PREMIUM_MANAGER_DIR . 'admin/view/mpe-premium-manager-admin-users-display.php';
    }

    public function enqueue_scripts(): void
    {
       wp_enqueue_style('mpe-premium-manager-admin-css', plugin_dir_url(__FILE__) .'css/mpe-premium-manager-admin.css');
       wp_enqueue_script('mpe-premium-manager-admin-js', plugin_dir_url(__FILE__) .'js/mpe-premium-manager-admin.js', array('jquery'), '', true);
       wp_localize_script('mpe-premium-manager-admin-js', 'mpePremiumData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('get_user_details_nonce')
        ));

    }



}