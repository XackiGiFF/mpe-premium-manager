<?php

namespace XackiGiFF\PremiumManager\public;

class MPEPremiumManagerPublic
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    public function enqueue_scripts(): void
    {
        wp_enqueue_style('mpe-premium-manager-public-css', plugin_dir_url(__FILE__) .'css/mpe-premium-manager-public.css');
        wp_enqueue_script('mpe-premium-manager-public-js', plugin_dir_url(__FILE__) .'js/mpe-premium-manager-public.js', array('jquery'));
    }
    // TODO: Add your code here
}