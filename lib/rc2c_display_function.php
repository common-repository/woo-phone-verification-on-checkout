<?php

/********************************************
* Add the RingCaptcha widget on checkout page
*********************************************/

function rc2c_custom_checkout_field_old() {
    global $rc2c_options;
    $options = get_option('rc2c_settings');

    $accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en';
    
    $locales = explode(',', $accept_language);
    $user_locale = 'en'; 
    if (!empty($locales)) {
        $user_locale = substr($locales[0], 0, 2); 
    }

    $gdpr_implementation = isset($rc2c_options['gdpr_implementation']) ? $rc2c_options['gdpr_implementation'] : false;
    $js_implementation = isset($rc2c_options['js_implementation']) ? $rc2c_options['js_implementation'] : false;
    $app_key = isset($rc2c_options['app_key']) ? sanitize_text_field($rc2c_options['app_key']) : '';
    $gdpr_consent_message_default = "I would like to receive discount updates and promotions in accordance with GDPR standards.";
    $gdpr_consent_message = !empty($rc2c_options['gdpr_consent_message']) ? $rc2c_options['gdpr_consent_message'] : $gdpr_consent_message_default;

    if ($gdpr_implementation == true) {
        echo '<div id="widget-point"></div>';
        echo '<div style="clear:both;"></div>';
        echo '<input id="gdpr_consent" type="hidden" name="gdpr_consent" value="false">';

        if ($js_implementation == true) { 
            echo '<input id="ringcaptcha_verified" type="hidden" name="ringcaptcha_verified" value="false">';
        }

        wp_enqueue_script('ringcaptcha-gdpr', plugin_dir_url(__FILE__) . 'ringcaptcha-gdpr.js', array('jquery'), null, true);
        wp_localize_script('ringcaptcha-gdpr', 'rc_options', array(
            'app_key' => esc_attr($app_key),
            'js_implementation' => $js_implementation,
            'gdpr_consent_message' => $gdpr_consent_message,
        ));
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), null, true);

    } else if ($js_implementation == true) {
        echo '<input id="ringcaptcha_verified" type="hidden" name="ringcaptcha_verified" value="false">';
        echo '<div id="widget-point"></div>';
        echo '<div style="clear:both;"></div>';

        wp_enqueue_script('ringcaptcha-verified', plugin_dir_url(__FILE__) . 'ringcaptcha-verified.js', array('jquery'), null, true);
        wp_localize_script('ringcaptcha-verified', 'rc_options', array('app_key' => esc_attr($app_key)));
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), null, true);
    } else {
        ?>
        <div data-widget data-app="<?php echo esc_attr($app_key); ?>" data-locale="<?php echo esc_attr($user_locale); ?>" data-type="dual"></div>
        <div style="clear:both;"></div>
        <?php
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), null, true);
    }

    wp_nonce_field('rc2c_nonce_action', 'rc2c_nonce');
}

add_action('woocommerce_checkout_billing', 'rc2c_custom_checkout_field_old', 15);

function rc2c_custom_override_checkout_fields_old($fields) {
    global $rc2c_options;

    if (isset($rc2c_options['enable']) && $rc2c_options['enable'] == true) {
        $fields['billing']['billing_email'] = array(
            'label' => __('Email', 'crc2c_domain'),
            'placeholder' => __('Email', 'placeholder', 'crc2c_domain'),
            'required' => true,
            'clear' => false,
            'type' => 'text',
            'class' => array('form-row-wide')
        );
        unset($fields['billing']['billing_phone']);

        require_once('Ringcaptcha.php');

        $app_key = isset($rc2c_options['app_key']) ? $rc2c_options['app_key'] : '';
        $secret_key = isset($rc2c_options['secret_key']) ? $rc2c_options['secret_key'] : '';
        $lib = new Ringcaptcha($app_key, $secret_key);
        $lib->setSecure(true);

        if (!empty($_POST['billing_first_name']) || !empty($_POST['billing_last_name']) || !empty($_POST['billing_country']) || !empty($_POST['billing_address_1']) || !empty($_POST['billing_city']) || !empty($_POST['billing_state']) || !empty($_POST['billing_postcode']) || !empty($_POST['billing_email'])) {
            if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce($_POST['rc2c_nonce'], 'rc2c_nonce_action')) {
                wc_add_notice(__('Security check failed', 'woo-phone-verification-on-checkout'), 'error');
                return $fields;
            }

            // Sanitize input fields
            $billing_first_name = sanitize_text_field($_POST['billing_first_name']);
            $billing_last_name = sanitize_text_field($_POST['billing_last_name']);
            $billing_country = sanitize_text_field($_POST['billing_country']);
            $billing_address_1 = sanitize_text_field($_POST['billing_address_1']);
            $billing_city = sanitize_text_field($_POST['billing_city']);
            $billing_state = sanitize_text_field($_POST['billing_state']);
            $billing_postcode = sanitize_text_field($_POST['billing_postcode']);
            $billing_email = sanitize_email($_POST['billing_email']);

            if (isset($_POST["ringcaptcha_session_id"]) && isset($_POST["ringcaptcha_pin_code"]) && isset($_POST["ringcaptcha_phone_number"])) {
                $ringcaptcha_session_id = sanitize_text_field($_POST["ringcaptcha_session_id"]);
                $ringcaptcha_pin_code = sanitize_text_field($_POST["ringcaptcha_pin_code"]);
                $ringcaptcha_phone_number = sanitize_text_field($_POST["ringcaptcha_phone_number"]);

                if ($rc2c_options['js_implementation'] == true && isset($_POST['ringcaptcha_verified']) && sanitize_text_field($_POST['ringcaptcha_verified']) == "true") {
                    $user_phone = $ringcaptcha_phone_number;

                    // No need to access order_id here
                } else if ($rc2c_options['js_implementation'] == false && $lib->isValid($ringcaptcha_pin_code, $ringcaptcha_session_id)) {
                    $user_phone = $lib->getPhoneNumber();

                    // No need to access order_id here
                } else {
                    error_log("Phone verification failed or data missing");
                    wc_add_notice(__('<strong>Billing Phone </strong>is a required field (please verify your phone number).'), 'error', 30);
                }
            } else {
                error_log("Phone verification data missing");
                wc_add_notice(__('<strong>Billing Phone </strong>is a required field (please verify your phone number).'), 'error', 30);
            }
        }
    }

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'rc2c_custom_override_checkout_fields_old');

function rc2c_custom_checkout_field_process_old() {
    if ($_POST['billing_phone'] && empty($_POST['billing_phone'])) {
        wc_add_notice(__('Phone is a required field.'), 'error');
    }
}
add_action('woocommerce_checkout_process', 'rc2c_custom_checkout_field_process_old');

function rc2c_phone_verified_old($order_id) {
    global $rc2c_options;

    if (isset($rc2c_options['enable']) && $rc2c_options['enable'] == true) {
        $user_phone = isset($_POST['ringcaptcha_phone_number']) ? sanitize_text_field($_POST['ringcaptcha_phone_number']) : '';

        if (!empty($user_phone)) {
            $order = wc_get_order($order_id);
            if ($order) {
                error_log("Updating order meta for _billing_phone");
                $order->set_billing_phone(sanitize_text_field($user_phone)); // Use setter method
                $order->save();
            }
        }
    }
}

function rc2c_gdpr_meta_old($order_id) {
    $order = wc_get_order($order_id);
    if ($order) {
        $order->update_meta_data(
            'gdpr_consent',
            isset($_POST['gdpr_consent']) ? sanitize_text_field($_POST['gdpr_consent']) : ''
        );
        $order->save();
    }
}

add_action('woocommerce_checkout_update_order_meta', 'rc2c_phone_verified_old'); // Save phone verification after order creation
add_action('woocommerce_checkout_update_order_meta', 'rc2c_gdpr_meta_old'); // Save GDPR consent after order creation