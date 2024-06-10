# MPE Premium Manager EN

**MPE Premium Manager** is a WordPress plugin designed to manage the premium status of users on your site. This plugin allows administrators to add or remove the premium user role and set display templates for premium and standard users.

## Features

- Adds a new user role: `MPE Premium User`
- Manage user metadata to store premium status information
- Create and configure templates for premium and standard users

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

# MPE Premium Manager RU

**MPE Premium Manager** — это плагин WordPress, предназначенный для управления статусом премиум пользователей на вашем сайте. Этот плагин позволяет администраторам добавлять или удалять роль премиум пользователя и устанавливать шаблоны отображения для премиум и стандартных пользователей.

## Функции и особенности

- Добавление новой роли пользователя: `MPE Premium User`
- Управление метаданными пользователей для хранения информации о премиум статусе
- Создание и настройка шаблонов для премиум и стандартных пользователей

## Установка

1. **Скачивание**:
    - Используйте команду для клонирования репозитория:
      ```sh
      git clone https://github.com/XackiGiFF/mpe-premium-manager.git
      ```
    - Или скачайте архив с исходным кодом напрямую с GitHub и распакуйте его.

2. **Размещение**:
    - Поместите папку с плагином в директорию `wp-content/plugins` на вашем сайте WordPress.

3. **Активация**:
    - Перейдите в админ-панель WordPress, найдите плагин "MPE Premium Manager" в разделе "Плагины" и активируйте его.

## Использование

### Добавление премиум статуса пользователю

```php
add_user_meta($user_id, 'premium_status', 'on', true);
add_user_meta($user_id, 'premium_end_date', '2024-06-10', true);
```

### Проверка премиум статуса пользователя

```php
$premium_status = get_user_meta($user_id, 'premium_status', true);
$premium_end_date = get_user_meta($user_id, 'premium_end_date', true);

if ($premium_status === 'on' && strtotime($premium_end_date) > time()) {
    echo 'Пользователь является премиум!';
} else {
    echo 'Пользователь не является премиум.';
}
```

## Настройки

Плагин предоставляет следующие настройки по умолчанию, которые можно изменить через код или админ-панель:

- Длительность премиум статуса (`mpe_premium_manager_days`) – 30 дней
- Сообщение об успешном обновлении премиум статуса (`mpe_premium_manager_success_message`)
- Ссылка на страницу премиум (`mpe_premium_manager_link_on_premium`)
- Текст ссылки на страницу премиум (`mpe_premium_manager_link_on_premium_text`)
- Шаблон для стандартных пользователей (`mpe_premium_manager_template_standart`)
- Шаблон для премиум пользователей (`mpe_premium_manager_template_premium`)

## Внесение вкладов

Если вы хотите внести свой вклад в этот проект, пожалуйста:

1. Склонируйте репозиторий (`git clone https://github.com/XackiGiFF/mpe-premium-manager.git`)
2. Создайте новую ветку (`git checkout -b feature/название_фичи`)
3. Внесите изменения и сделайте коммит (`git commit -am 'Добавлена новая фича'`)
4. Запушьте ветку (`git push origin feature/название_фичи`)
5. Создайте Pull Request на GitHub

## Лицензия

Этот проект распространяется под лицензией MIT. Подробности можно найти в файле LICENSE в корне проекта.

---

Автор: [XackiGiFF](https://github.com/XackiGiFF)