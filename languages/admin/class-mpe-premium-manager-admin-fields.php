<?php
// Add new fields to user profile
function mpe_premium_fields($user): void
{
    ?>
    <h3><?php _e("Premium Account Information", "mpe-premium-manager"); ?></h3>

    <table class="form-table">
        <tr>
            <th>
                <label for="premium_status"><?php _e("Premium Status", "mpe-premium-manager"); ?></label>
            </th>
            <td>
                <input type="checkbox" name="premium_status" id="premium_status" <?php checked(get_the_author_meta('premium_status', $user->ID), 'active'); ?>/>
                <span class="description"><?php _e("Check if the user has a premium status.", "mpe-premium-manager"); ?></span>
            </td>
        </tr>
        <tr>
            <th>
                <label for="premium_end_date"><?php _e("End Date of Premium Status", "mpe-premium-manager"); ?></label>
            </th>
            <td>
                <input type="date" name="premium_end_date" id="premium_end_date" value="<?php echo esc_attr(get_the_author_meta('premium_end_date', $user->ID)); ?>" class="regular-text" />
                <span class="description"><?php _e("Enter the end date of the premium status.", "mpe-premium-manager"); ?></span>
            </td>
        </tr>
        <!-- Add a read-only field to display remaining days -->
        <tr>
            <th>
                <label for="premium_days_remaining"><?php _e("Days Remaining", "mpe-premium-manager"); ?></label>
            </th>
            <td>
                <input type="text" readonly id="premium_days_remaining" value="<?php echo esc_attr(mpe_calculate_remaining_days($user->ID)); ?>" class="regular-text" />
                <span class="description"><?php _e("This displays the remaining premium days.", "mpe-premium-manager"); ?></span>
            </td>
        </tr>
    </table>
    <?php
}

// Save new fields values on profile update
function mpe_save_premium_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // Update premium status
    if(isset($_POST['premium_status'])) {
        update_user_meta($user_id, 'premium_status', 'active');
    } else {
        update_user_meta($user_id, 'premium_status', 'inactive');
    }

    // Update end date of premium status
    if(isset($_POST['premium_end_date'])) {
        update_user_meta($user_id, 'premium_end_date', sanitize_text_field($_POST['premium_end_date']));
    }
}

add_action('show_user_profile', 'mpe_premium_fields');
add_action('edit_user_profile', 'mpe_premium_fields');
add_action('personal_options_update', 'mpe_save_premium_fields');
add_action('edit_user_profile_update', 'mpe_save_premium_fields');

// Function to calculate remaining premium days
/**
 * @throws Exception
 */
function mpe_calculate_remaining_days($user_id): bool|int
{
    $end_date_str = get_user_meta($user_id, 'premium_end_date', true);
    if ($end_date_str) {
        $end_date = new DateTime($end_date_str);
        $current_date = new DateTime();
        if($current_date > $end_date) {
            return 0; // Premium status has expired.
        } else {
            return $current_date->diff($end_date)->days;
        }
    } else {
        return 0; // Premium status is not set.
    }
}

