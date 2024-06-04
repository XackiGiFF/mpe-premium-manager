<?php

echo '<h1>MPE Premium Manager</h1>';
echo '<p>Manage Users</p>';

$user_id = get_current_user_id();
$status = get_user_meta($user_id, 'premium_status', true);
print_r($status);