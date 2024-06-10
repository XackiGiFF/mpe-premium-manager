<?php

if (!current_user_can('manage_options')) {
wp_die('You are not allowed to access this page.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = [
        'mpe_premium_manager_days',
        'mpe_premium_manager_success_message',
        'mpe_premium_manager_link_on_premium',
        'mpe_premium_manager_link_on_premium_text',
        'mpe_premium_manager_template_standart',
        'mpe_premium_manager_template_premium'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === 'mpe_premium_manager_days') {
                $sanitized_value = intval($_POST[$field]);
            } elseif (in_array($field, [
                    'mpe_premium_manager_template_standart',
                    'mpe_premium_manager_template_premium'])) {
                $sanitized_value = wp_kses_post(wp_unslash($_POST[$field])); // обработка HTML-полей
            } else {
                $sanitized_value = sanitize_text_field($_POST[$field]);
            }
            update_option($field, $sanitized_value);
        }
    }

    echo '<div class="updated"><p>Настройки сохранены</p></div>';
}
$days = intval(get_option('mpe_premium_manager_days', 0));
$success_message = get_option('mpe_premium_manager_success_message', 'Your premium status has been updated for {days} days.');

$mpe_premium_manager_link_on_priemium = get_option('mpe_premium_manager_link_on_premium', '');
$mpe_premium_manager_link_on_premium_text = get_option('mpe_premium_manager_link_on_premium_text', '');

$mpe_premium_manager_template_standart = get_option('mpe_premium_manager_template_standart', '');
$mpe_premium_manager_template_premium = get_option('mpe_premium_manager_template_premium', '');

?>

<div style="margin-left: 10%">
    <div style="max-width: 80%; margin: 20px;">
        <h1>MPE Premium Manager: <small>Settings</small></h1>
        <p>Система управления премиальными аккаунтами, датой окончания премиума, количество дней до окончания</p>
        <div class="wrap">
            <h2>Настройки премиум менеджера:</h2>
            <form method="post" action="">
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_days">Количество дней премиума</label>
                            <p>Используется для расчета даты окончания премиума для всех пользователей</p>
                        </th>
                        <td><input name="mpe_premium_manager_days" type="number" id="mpe_premium_manager_days" value="<?php echo esc_attr($days); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_success_message">Сообщение об успешном обновлении (HMTL)</label>
                            <p>Используйте плейсхолдеры для подстановки значений:</p>
                            <ul>
                                <li><code>{days}</code> - количество дней до окончания премиума</li>
                            </ul>
                        </th>
                        <td>
                            <?php
                            wp_editor(
                                $success_message,
                                'mpe_premium_manager_success_message',
                                array(
                                    'textarea_name' => 'mpe_premium_manager_success_message',
                                    'textarea_rows' => 10,
                                    'media_buttons' => false
                                )
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h2>Настройка отображения премиума в личном кабинете пользователя по шорткоду:</h2>
                            <p><code>[mpe_premium_manager_user_details]</code></p>
                        </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_link_on_premium">Ссылка на премиум</label>
                            <p>Ссылка на премиум, которая будет отображаться в личном кабинете пользователя</p>
                        </th>
                        <td><input name="mpe_premium_manager_link_on_premium" type="text" id="mpe_premium_manager_link_on_premium" value="<?php echo esc_attr(get_option('mpe_premium_manager_link_on_premium', '/lk/')); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_link_on_premium_text">Текст на премиум</label>
                            <p>Текст, который будет отображаться в личном кабинете пользователя</p>
                        </th>
                        <td><input name="mpe_premium_manager_link_on_premium_text" type="text" id="mpe_premium_manager_link_on_premium_text" value="<?php echo esc_attr(get_option('mpe_premium_manager_link_on_premium_text', 'Premium')); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_template_standart">Шаблон стандартного пользователя</label>
                            <p>Шаблон для отображения стандартного пользователя в личном кабинете пользователя</p>
                            <p>Используйте плейсхолдеры для подстановки значений:</p>
                            <ul>
                                <li><code>{link}</code> - ссылка на премиум</li>
                                <li><code>{link_text}</code> - текст ссылки на премиум</li>
                            </ul>
                        </th>
                        <td>
                            <?php
                            wp_editor(
                                $mpe_premium_manager_template_standart,
                                'mpe_premium_manager_template_standart',
                                array(
                                    'textarea_name' => 'mpe_premium_manager_template_standart',
                                    'textarea_rows' => 10,
                                    'media_buttons' => false
                                )
                            );
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="mpe_premium_manager_template_premium">Шаблон премиума</label>
                            <p>Шаблон для отображения премиума в личном кабинете пользователя</p>
                            <p>Используйте плейсхолдеры для подстановки значений:</p>
                            <ul>
                                <li><code>{date}</code> - дата окончания премиума</li>
                                <li><code>{remaining_days}</code> - оставшееся количество дней до окончания премиума</li>
                            </ul>
                        </th>
                        <td>
                            <?php
                            wp_editor(
                                $mpe_premium_manager_template_premium,
                                'mpe_premium_manager_template_premium',
                                array(
                                    'textarea_name' => 'mpe_premium_manager_template_premium',
                                    'textarea_rows' => 10,
                                    'media_buttons' => false
                                )
                            );
                            ?>
                        </td>
                    </tr>

                </table>
                <p class="submit"><button type="submit" class="button button-primary">Сохранить настройки</button></p>
            </form>

            <h2>Шорткоды премиум менеджера</h2>
            <p>Используйте шорткоды для размещения на страницах:</p>
            <ul>
                <li><code>[mpe_premium_manager_add_premium]</code> на странице подтверждения платежа для вставки автоматизированного кода выдачи премиума.</li>
                <li><code>[mpe_premium_manager_user_details]</code> на странице пользователя для вывода информации о статусе премиума.</li>
            </ul>


            <?php /** Блок ниже не требуется, но возможны изменения в будущих версиях */?>
            <!-- Настройка интеграции платежной системы Stripe -->

            <!-- Тут название платежной системы и поле для ввода ключа для проверки статуса платежа -->

            <!-- Настройка интеграции платежной системы PayPal -->

            <!-- Тут название платежной системы и поле для ввода ключа для проверки статуса платежа -->

            <!-- Настройка интеграции платежной системы Яндекс.Деньги -->

            <!-- Тут название платежной системы и поле для ввода ключа для проверки статуса платежа -->

            <!-- Тут настройка поля сниппета для вставки в код страницы сайта -->
        </div>
    </div>
</div>
