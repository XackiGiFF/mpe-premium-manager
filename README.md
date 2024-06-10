# MPE Premium Manager EN

**MPE Premium Manager** is a WordPress plugin designed to manage the premium status of users on your site. This plugin allows administrators to add or remove the premium user role and set display templates for premium and standard users. Additionally, it includes automatic checks for the expiration of premium status, ensuring that user roles are up to date.

## Features

- Adds a new user role: `MPE Premium User`
- Manage user metadata to store premium status information
- Create and configure templates for premium and standard users
- Shortcodes to display user premium status, remaining days, and end date
- Automatic checks for premium status expiration

## Installation

1. **Download**:
    - Clone the repository using the following command:
      ```sh
      git clone https://github.com/XackiGiFF/mpe-premium-manager.git
      ```
    - Alternatively, download the source code as a ZIP file from GitHub and unzip it.

2. **Placement**:
    - Place the plugin folder into your site's `wp-content/plugins` directory.

3. **Activation**:
    - Go to the WordPress admin panel, find the "MPE Premium Manager" plugin in the "Plugins" section, and activate it.

## Shortcodes

### Inserting Automated Premium Grant Code

[mpe_premium_manager_add_premium]

Add this to the payment confirmation page to insert the automated premium grant code.

### Displaying Premium Status Information

[mpe_premium_manager_user_details]

Add this to the user page to display information about the user's premium status.

### Display Premium Status (in Future)

```shortcode
[mpe_premium_status]
```
Displays whether the current user is a premium member.

### Display Remaining Days

```shortcode
[mpe_remaining_days]
```
Displays the number of days remaining in the user's premium status.

### Display Premium End Date

```shortcode
[mpe_premium_end_date]
```
Displays the end date of the user's premium status.

## Usage

### Adding Premium Status to a User

```php
add_user_meta($user_id, 'premium_status', 'on', true);
add_user_meta($user_id, 'premium_end_date', '2024-06-10', true);
```

### Checking a User's Premium Status

```php
$premium_status = get_user_meta($user_id, 'premium_status', true);
$premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

if ($premium_status === 'on' && strtotime($premium_end_date) > time()) {
    echo 'The user is a premium member!';
} else {
    echo 'The user is not a premium member.';
}
```

### Methods of the MPEPremiumManager Class

### Adding Premium Status
```php
/**
 * Issue premium status to the user for a specified number of days.
 * @param int $user_id
 * @param int $days
 * @return void
 */
MPEPremiumManager::add_premium(int $user_id, int $days): void;
```
Issue premium status to the user for a specified number of days.

### Deleting Premium Status
```php
/**
 * Remove premium status from the user.
 * @param int $user_id
 * @return void
 */
MPEPremiumManager::del_premium(int $user_id): void;
```
Remove premium status from the user.

### Checking Premium Status Activity
```php
/**
 * Check if the user's premium status is active.
 * @param int $user_id
 * @return bool
 */
MPEPremiumManager::is_premium_active(int $user_id): bool;
```
Check if the user's premium status is active.

### Getting Remaining Premium Days
```php
/**
 * Return the number of remaining premium days for the user.
 * @param int $user_id
 * @return bool|int
 */
MPEPremiumManager::get_premium_remaining_days(int $user_id): bool|int;
```
Return the number of remaining premium days for the user.

### Getting Premium Expiry Date
```php
/**
 * Return the expiry date of the user's premium status.
 * @param int $user_id
 * @return string
 */
MPEPremiumManager::get_premium_date(int $user_id): string;
```
Return the expiry date of the user's premium status.

## Automatic Premium Status Check

The plugin automatically checks for the expiration of premium status. If a user's premium status has expired, it updates their role and metadata accordingly.

## Settings

The plugin provides the following default settings, which can be modified via code or the admin panel:

- Premium status duration (`mpe_premium_manager_days`) – 30 days
- Success message for updated premium status (`mpe_premium_manager_success_message`)
- Link to the premium page (`mpe_premium_manager_link_on_premium`)
- Text for the premium page link (`mpe_premium_manager_link_on_premium_text`)
- Template for standard users (`mpe_premium_manager_template_standart`)
- Template for premium users (`mpe_premium_manager_template_premium`)

## Contributions

If you would like to contribute to this project, please:

1. Fork the repository (`git clone https://github.com/XackiGiFF/mpe-premium-manager.git`)
2. Create a new branch (`git checkout -b feature/feature_name`)
3. Make your changes and commit them (`git commit -am 'Added new feature'`)
4. Push the branch (`git push origin feature/feature_name`)
5. Create a Pull Request on GitHub

## License

This project is licensed under the MIT License. For more details, please refer to the LICENSE file in the root directory of the project.

---

Author: [XackiGiFF](https://github.com/XackiGiFF)

# MPE Premium Manager

**MPE Premium Manager** — это плагин WordPress, предназначенный для управления премиум-статусом пользователей на вашем сайте. Этот плагин позволяет администраторам добавлять или удалять премиум-роль пользователя и настраивать шаблоны отображения для премиум и стандартных пользователей. Кроме того, он включает автоматическую проверку срока действия премиум-статуса, что позволяет поддерживать актуальность ролей пользователей.

## Особенности

- Добавляет новую роль пользователя: `Премиум пользователь MPE`
- Управление метаданными пользователей для хранения информации о премиум-статусе
- Создание и настройка шаблонов для премиум и стандартных пользователей
- Шорткоды для отображения премиум-статуса пользователя, оставшихся дней и даты окончания
- Автоматические проверки срока действия премиум-статуса

## Установка

1. **Скачать**:
    - Клонируйте репозиторий с помощью следующей команды:
      ```sh
      git clone https://github.com/XackiGiFF/mpe-premium-manager.git
      ```
    - Также вы можете скачать исходный код в виде ZIP-файла с GitHub и распаковать его.

2. **Размещение**:
    - Поместите папку с плагином в директорию `wp-content/plugins` вашего сайта.

3. **Активация**:
    - Перейдите в панель администратора WordPress, найдите плагин "MPE Premium Manager" в разделе "Плагины" и активируйте его.

## Шорткоды

### Вставка автоматизированного кода выдачи премиума

```shortcode
[mpe_premium_manager_add_premium]
```
Добавляется на страницу подтверждения платежа для вставки автоматизированного кода выдачи премиума.

### Вывод информации о статусе премиума

```shortcode
[mpe_premium_manager_user_details]
```
Добавляется на страницу пользователя для вывода информации о статусе премиума.

## Шорткоды (В будущем)

### Отображение премиум-статуса

```shortcode
[mpe_premium_status]
```
Отображает, является ли текущий пользователь премиум-участником.

### Отображение оставшихся дней

```shortcode
[mpe_remaining_days]
```
Отображает количество оставшихся дней премиум-статуса пользователя.

### Отображение даты окончания премиум-статуса

```shortcode
[mpe_premium_end_date]
```
Отображает дату окончания премиум-статуса пользователя.

## Использование

### Добавление премиум-статуса пользователю

```php
add_user_meta($user_id, 'premium_status', 'on', true);
add_user_meta($user_id, 'premium_end_date', '2024-06-10', true);
```

### Проверка премиум-статуса пользователя

```php
$premium_status = get_user_meta($user_id, 'premium_status', true);
$premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

if ($premium_status === 'on' && strtotime($premium_end_date) > time()) {
    echo 'Пользователь является премиум-участником!';
} else {
    echo 'Пользователь не является премиум-участником.';
}
```

## Методы класса `MPEPremiumManager`

### Добавление премиум-статуса

```php
/**
 * Выдача премиума пользователю на заданное количество дней.
 * @param int $user_id
 * @param int $days
 * @return void
 */
MPEPremiumManager::add_premium(int $user_id, int $days): void;
```
Выдача премиума пользователю на заданное количество дней.

### Удаление премиум-статуса

```php
/**
 * Удаление премиума у пользователя.
 * @param int $user_id
 * @return void
 */
MPEPremiumManager::del_premium(int $user_id): void;
```
Удаление премиума у пользователя.

### Проверка активности премиум-статуса

```php
/**
 * Проверка, активен ли премиум у пользователя.
 * @param int $user_id
 * @return bool
 */
MPEPremiumManager::is_premium_active(int $user_id): bool;
```
Проверка, активен ли премиум у пользователя.

### Получение оставшихся дней премиум-статуса

```php
/**
 * Возвращает количество оставшихся дней премиум-статуса пользователя.
 * @param int $user_id
 * @return bool|int
 */
MPEPremiumManager::get_premium_remaining_days(int $user_id): bool|int;
```
Возвращает количество оставшихся дней премиум-статуса пользователя.

### Получение даты окончания премиума

```php
/**
 * Возвращает дату окончания премиума у пользователя.
 * @param int $user_id
 * @return string
 */
MPEPremiumManager::get_premium_date(int $user_id): string;
```
Возвращает дату окончания премиума у пользователя.

## Автоматическая проверка премиум-статуса

Плагин автоматически проверяет срок действия премиум-статуса. Если премиум-статус пользователя истек, он обновляет его роль и метаданные соответственно.

## Настройки

Плагин предоставляет следующие настройки по умолчанию, которые можно изменить через код или панель администратора:

- Длительность премиум-статуса (`mpe_premium_manager_days`) – 30 дней
- Сообщение об успешном обновлении премиум-статуса (`mpe_premium_manager_success_message`)
- Ссылка на страницу премиум-статуса (`mpe_premium_manager_link_on_premium`)
- Текст для ссылки на страницу премиум-статуса (`mpe_premium_manager_link_on_premium_text`)
- Шаблон для стандартных пользователей (`mpe_premium_manager_template_standart`)
- Шаблон для премиум-пользователей (`mpe_premium_manager_template_premium`)

## Вклад

Если вы хотите внести вклад в этот проект, пожалуйста:

1. Форкайте репозиторий (`git clone https://github.com/XackiGiFF/mpe-premium-manager.git`)
2. Создайте новую ветку (`git checkout -b feature/feature_name`)
3. Внесите свои изменения и закоммитьте их (`git commit -am 'Добавлена новая функция'`)
4. Запушьте ветку (`git push origin feature/название_фичи`)
5. Создайте Pull Request на GitHub

## Лицензия

Этот проект распространяется под лицензией MIT. Подробности можно найти в файле LICENSE в корне проекта.

---

Автор: [XackiGiFF](https://github.com/XackiGiFF)