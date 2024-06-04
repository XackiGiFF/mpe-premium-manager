jQuery(document).ready(function($) {
    // Event listener for user selection change
    $('#user_id').change(function() {
        // Get the selected user id
        var userId = $(this).val();

        // AJAX request to WordPress to get the user meta
        $.ajax({
            url: mpePremiumData.ajaxurl, // Use the localized 'ajaxurl'
            method: 'POST',
            data: {
                action: 'get_user_premium_data', // Action hook for the AJAX in functions.php
                user_id: userId, // Passed selected user ID
                nonce: mpePremiumData.nonce // Nonce for security, localized from WordPress
            },
            success: function(response) {
                if(response.success) {
                    // Update the form fields with retrieved data
                    $('#premium_status').val(response.data.premium_status);
                    $('#premium_end_date').val(response.data.premium_end_date);
                    // If you have field to display remaining days, you can update it here as well
                } else {
                    // Handle errors, e.g. show message to the user
                    alert('Не удалось загрузить данные пользователя: ' + response.data.message);
                }
            },
            error: function() {
                alert('Ошибка запроса.');
            }
        });
    });
});
