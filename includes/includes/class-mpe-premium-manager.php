<?php

namespace XackiGiFF\PremiumManager;

use XackiGiFF\PremiumManager\public\MPEPremiumManagerPublic;
use XackiGiFF\PremiumManager\admin\MPEPremiumManagerAdmin;

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

    /**
     * Проверка, активен ли премиум у пользователя.
     *
     * @param int $user_id ID пользователя.
     * @return bool True, если премиум активен, иначе false.
     */

    public function is_premium_active(int $user_id): bool
    {
        $premium_status = get_user_meta($user_id, 'premium_status', true);
        $premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

        if (!$premium_status || !$premium_end_date) {
            return false;
        }

        $current_date = current_time('Y-m-d');
        return $current_date <= $premium_end_date && $premium_status == 'active';
    }

    /**
     * Отключение премиума, если срок действия истек.
     */
    public function deactivate_expired_premiums(): void
    {
        $users = get_users(array('meta_key' => 'premium_end_date'));

        foreach ($users as $user) {
            if (!$this->is_premium_active($user->ID)) {
                update_user_meta($user->ID, 'premium_status', 'inactive');
            }
        }
    }

    /**
     * Выдача премиума пользователю на заданное количество дней.
     *
     * @param int $user_id ID пользователя.
     * @param int $days Количество дней, на которое выдается премиум.
     */
    public function add_premium(int $user_id, int $days): void
    {
        $current_date = current_time('Y-m-d');
        $expiration_date = date('Y-m-d', strtotime("+$days days", strtotime($current_date)));

        update_user_meta($user_id, 'premium_end_date', $expiration_date);
        update_user_meta($user_id, 'premium_status', 'active');
    }

    public function del_premium($user_id): void
    {
        update_user_meta($user_id, 'premium_end_date', '');
        update_user_meta($user_id, 'premium_status', 'inactive');
    }
}