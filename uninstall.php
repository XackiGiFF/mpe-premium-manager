<?php

// Add a new user meta field for premium status if not already available
$users = get_users();
foreach ( $users as $user ) {
    delete_metadata('user', $user->ID,'premium_status');
    delete_metadata('user', $user->ID,'premium_end_date');
}

$options_to_delete = [
    'mpe_premium_days',
    'mpe_premium_success_message',
    'mpe_premium_link_on_premium',
    'mpe_premium_link_on_premium_text',
    'mpe_premium_template_standart',
    'mpe_premium_template_premium',
    'mpe_premium_manager_days',
    'mpe_premium_manager_success_message',
    'mpe_premium_manager_ssylka_na_priemium',
    'mpe_premium_manager_ssylka_na_priemium_text',
    'mpe_premium_manager_premium_template_standart',
    'mpe_premium_manager_premium_template_premium'
];

foreach ($options_to_delete as $option_name) {
    delete_option($option_name);
}

// Удаляем зарегистрированные шорткоды
remove_shortcode('mpe_premium_manager_user_details');
remove_shortcode('mpe_premium_manager_add_premium');