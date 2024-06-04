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

    public static function sort_users($sort_order) {
        switch ($sort_order) {
            case 'active':
                return array('orderby' => 'premium_status', 'order' => 'DESC');
            case 'inactive':
                return array('orderby' => 'premium_status', 'order' => 'ASC');
            case 'users_newest':
                return array('orderby' => 'user_registered', 'order' => 'DESC');
            case 'users_oldest':
                return array('orderby' => 'user_registered', 'order' => 'ASC');
            default:
                return array('orderby' => 'ID', 'order' => 'DESC');
        }
    }

    private static function display_sort_buttons($current_page) {
        // Ссылки для сортировки
        echo '<div class="sort-buttons" style="margin-bottom: 10px;">';

        // Определяем текущую сортировку для выделения активной кнопки
        $current_sort = isset($_GET['sort']) ? $_GET['sort'] : '';

        $sort_buttons = [
            'active' => 'Сначала активные',
            'inactive' => 'Сначала неактивные',
            'users_newest' => 'По пользователям (новые)',
            'users_oldest' => 'По пользователям (старые)',
        ];

        foreach ($sort_buttons as $sort_value => $sort_label) {
            // Добавляем текущий номер страницы и параметр сортировки к URL
            $link = add_query_arg([
                'paged' => $current_page,
                'sort' => $sort_value,
            ]);

            $class = $sort_value === $current_sort ? 'button active' : 'button';

            echo "<a href='{$link}' class='{$class}'>{$sort_label}</a>";
        }

        echo '</div>';
    }

    public static function display_premium_users_table($current_page = 1, $per_page = 20, $sort_order = 'default') {
        // Получаем общее количество пользователей
        $total_users = count_users();
        $total_users = $total_users['total_users'];

        // Вычисляем общее количество страниц
        $total_pages = ceil($total_users / $per_page);

        // Удостоверяемся, что текущая страница не выходит за пределы возможных значений
        $current_page = max(1, min($current_page, $total_pages));


        $sort_params = self::sort_users($sort_order);

        // Получаем пользователей для текущей страницы с учетом параметров сортировки
        $users = get_users(array(
            'number'   => $per_page,
            'offset'   => ($current_page - 1) * $per_page,
            'orderby'  => $sort_params['orderby'],
            'order'    => $sort_params['order'],
            // Возможно, вам потребуется добавить дополнительные аргументы для фильтрации активных/неактивных пользователей
        ));

        // Начало таблицы
        $table_html = '<table class="wp-list-table widefat fixed striped">';
        $table_html .= '<thead>';
        $table_html .= '<tr>';
        $table_html .= '<th>User ID</th>';
        $table_html .= '<th>Full Name</th>';
        $table_html .= '<th>Premium Status</th>';
        $table_html .= '<th>Expiration Date</th>';
        $table_html .= '<th>Days Left</th>';
        $table_html .= '</tr>';
        $table_html .= '</thead>';
        $table_html .= '<tbody>';

        // Перебираем всех пользователей и заполняем таблицу данными
        foreach ($users as $user) {
            $user_id = $user->ID;
            $premium_status = get_user_meta($user_id, 'premium_status', true);
            $premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

            // Вычисляем оставшиеся дни до истечения премиум статуса
            $expiration_date = ($premium_end_date) ? new DateTime($premium_end_date) : null;
            $current_date = new DateTime();
            $days_left = ($expiration_date) ? $current_date->diff($expiration_date)->days : 'N/A';

            // Если премиум статус истек, указываем это
            $days_left_text = ($days_left < 0 || !$premium_end_date) ? 'Expired' : $days_left;
            $premium_status_text = ($days_left < 0 || !$premium_end_date) ? 'Inactive' : $premium_status;

            // Добавляем строку в таблицу
            $table_html .= '<tr>';
            $table_html .= '<td>' . esc_html($user_id) . '</td>';
            $table_html .= '<td>' . esc_html($user->display_name) . '</td>';
            $table_html .= '<td>' . esc_html($premium_status_text) . '</td>';
            $table_html .= '<td>' . esc_html($premium_end_date) . '</td>';
            $table_html .= '<td>' . esc_html($days_left_text) . '</td>';
            $table_html .= '</tr>';
        }

        // Закрываем tbody и саму таблицу
        $table_html .= '</tbody>';
        $table_html .= '</table>';

        // Кнопки пагинации
        $pagination_html = '<div class="tablenav">';
        $pagination_html .= '<div class="tablenav-pages">';
        $pagination_html .= '<span class="displaying-num">' . $total_users . ' users</span>';

        // Кнопка "Предыдущая страница", если это не первая страница
        if ($current_page > 1) {
            $pagination_html .= '<a class="prev-page" href="' . add_query_arg('paged', ($current_page - 1)) . '"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">‹</span></a>';
        }

        // Кнопки страниц
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination_html .= ($i === $current_page) ? '<span class="current-page">' . $i . '</span>' : '<a class="page-num" href="' . add_query_arg('paged', $i) . '">' . $i . '</a>';
        }

        // Кнопка "Следующая страница", если это не последняя страница
        if ($current_page < $total_pages) {
            $pagination_html .= '<a class="next-page" href="' . add_query_arg('paged', ($current_page + 1)) . '"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>';
        }

        $pagination_html .= '</div>';
        $pagination_html .= '</div>';

        // Проверяем переданный параметр per_page и обновляем $per_page
        if (isset($_GET['per_page']) && intval($_GET['per_page'])) {
            $per_page = intval($_GET['per_page']);
        }

        // Выводим таблицу
        echo $table_html;

        // Начало строки для пагинации и выбора количества строк
        echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">';

        // Форма для выбора количества строк на странице
        echo '<form action="" method="GET" style="margin-right: auto;">';

        // Если есть другие параметры запроса, передаем их в скрытых полях
        foreach ($_GET as $key => $value) {
            if ($key !== 'per_page') {
                echo '<input type="hidden" name="'. htmlspecialchars($key) .'" value="'. htmlspecialchars($value) .'">';
            }
        }

        // Выпадающий список для выбора числа строк
        echo '<select name="per_page" onchange="this.form.submit()">';
        foreach ([10, 20, 30, 50, 100] as $option) {
            $selected = $per_page == $option ? ' selected' : '';
            echo "<option value=\"$option\"$selected>$option</option>";
        }
        echo '</select>';

        echo '<noscript><input type="submit" value="Применить"></noscript>';
        echo '</form>';

        // Выводим кнопки сортировки
        self::display_sort_buttons($current_page);

        // Выводим кнопки пагинации
        echo $pagination_html;

        // Закрываем строку для пагинации и выбора количества строк
        echo '</div>';
    }


}