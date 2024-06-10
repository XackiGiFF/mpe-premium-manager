global$mpe_premium_manager;
<div style="margin-left: 10%">
    <div style="max-width: 80%; margin: 20px;">
        <h1>MPE Premium Manager: <small>Manage Users</small></h1>
        <p>Система управления премиальными аккаунтами, датой окончания премиума, количество дней до окончания</p>

<?php
use XackiGiFF\PremiumManager\admin\MPEPremiumManagerAdmin;

// Теперь вам нужно будет обновить $current_page и $per_page на основе GET-параметров

$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = isset($_GET['per_page']) ? max(1, intval($_GET['per_page'])) : 20;
// вызываем функцию с параметром сортировки
$sort_order = $_GET['sort'] ?? 'default';

// Для получения текущей страницы из URL, если параметр paged установлен
if(!empty($_GET['paged'])) {
    $current_page = filter_input(INPUT_GET, 'paged', FILTER_VALIDATE_INT);
    MPEPremiumManagerAdmin::display_premium_users_table($current_page, $per_page, $sort_order);
} else {
    MPEPremiumManagerAdmin::display_premium_users_table();
}

// Проверки безопасности
if (isset($_POST['submit'])) {
    if (isset($_POST['mpe_premium_nonce']) && check_admin_referer('mpe_premium_update', 'mpe_premium_nonce')) {

        // Обработка данных формы
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $premium_status = isset($_POST['premium_status']) ? sanitize_text_field($_POST['premium_status']) : false;
        $premium_end_date = isset($_POST['premium_end_date']) ? sanitize_text_field($_POST['premium_end_date']) : '01-01-1970';

        // Проверяем корректность формата даты
        if (DateTime::createFromFormat('Y-m-d', $premium_end_date) !== false) {
            // ОБновление метаданных пользователя
            if ($user_id > 0) {
                update_user_meta($user_id, 'premium_status', $premium_status);
                update_user_meta($user_id, 'premium_end_date', $premium_end_date);
                echo '<div class="updated"><p>Статус и дата окончания премиум-аккаунта обновлены.</p></div>';
            } else {
                echo '<div class="error"><p>Ошибка: Некорректный ID пользователя.</p></div>';
            }
        } else {
            echo '<div class="error"><p>Ошибка: Некорректная дата окончания премиума.</p></div>';
        }
    } else {
        // Неверный или отсутствующий nonce, обработка запроса прекращается
        wp_die('Неверная попытка безопасности');
    }
}

// HTML вашей административной страницы

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
?>
<div class="wrap">
    <h2>Управление статусом премиум-аккаунта</h2>
    <form method="post" action="">
        <?php wp_nonce_field('mpe_premium_update', 'mpe_premium_nonce'); ?>

        <table class="form-table">
            <tr>
                <th><label for="user_id">Пользователь</label></th>
                <td>
                    <select name="user_id" id="user_id">
                        <?php
                        // Получить всех пользователей.
                        $users = get_users();
                        echo '<option value="0">Выберите пользователя</option>';

                        // Проходимся по каждому пользователю и проверяем его с текущим пользователем.
                        foreach ($users as $user) {
                            $current_premium_status = get_user_meta($user->ID, 'premium_status') == 'active' ? 'active' : 'inactive'; // Получить текущий статус премиума
                            echo '<option value="' . esc_attr($user->ID) . '" ' . selected($current_premium_status, 'active', false) . '>' . esc_html($user->display_name) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="premium_status">Статус премиум</label></th>
                <td>
                    <select name="premium_status" id="premium_status">
                        <?php
                        // Проходимся по каждому статусу и проверяем его с текущим статусом пользователя (для выбранного пользователя).
                        $premium_status_options = ['active' => 'Активен', 'inactive' => 'Неактивен'];
                        foreach ($premium_status_options as $value => $label) {
                            echo '<option value="' . esc_attr($value) . '"' . selected($current_premium_status, $value, false) . '>' . esc_html($label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="premium_end_date">Дата окончания премиума</label></th>
                <td>
                    <?php
                    global $mpe_premium_manager;
                    // Предполагается, что мы получаем мета значение для выбранного пользователя. Если у пользователя уже есть дата, используйте её, иначе - используйте текущую дату.
                    $premium_end_date = $user_id ? get_user_meta($user_id,'premium_end_date') : '';
                    $premium_end_date = get_user_meta($user_id, 'premium_end_date', true) ?? date('Y-m-d');
                    $remaining_days = $user_id ? $mpe_premium_manager->get_premium_remaining_days($user_id) : 0;
                    ?>
                    <input name="premium_end_date" id="premium_end_date" type="date" value="<?php echo esc_attr($premium_end_date); ?>" class="regular-text" />
                    <p>Оставшиеся дни до окончания: <span id="premium_remaining_days"><?php echo esc_html($remaining_days); ?></span></p>
                </td>
            </tr>
        </table>

        <?php submit_button('Обновить статус'); ?>
    </form>
</div>

    </div>
</div>