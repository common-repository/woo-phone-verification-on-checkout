<?php

/******************************
* Send SMS notifications
******************************/

if ( ! defined( 'ABSPATH' ) ) exit;

if(isset($rc2c_options['enable_text_notification']) && $rc2c_options['enable_text_notification'] == true) {
    if(isset($rc2c_options['enable_pending']) && $rc2c_options['enable_pending'] == true) {
        function rc2c_status_pending_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            // SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['pending_message']));

            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['pending_message'])) : '';
            // Sends SMS using RingCaptcha API
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_pending', 'rc2c_status_pending_old');
    }

    if(isset($rc2c_options['enable_failed']) && $rc2c_options['enable_failed'] == true) {
        function rc2c_status_failed_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            // SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['failed_message']));
            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['failed_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('failed status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_failed', 'rc2c_status_failed_old');
    }

    if(isset($rc2c_options['enable_on_hold']) && $rc2c_options['enable_on_hold'] == true) {
        function rc2c_status_hold_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            // SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['on_hold_message']));
            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['on_hold_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('on-hold status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_on-hold', 'rc2c_status_hold_old');
    }

    if(isset($rc2c_options['enable_processing']) && $rc2c_options['enable_processing'] == true) {
        function rc2c_status_processing_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            // SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['processing_message']));

            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['processing_message']));

            // Sends SMS using RingCaptcha API
            error_log('processing status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_processing', 'rc2c_status_processing_old');
    }

    if(isset($rc2c_options['enable_completed']) && $rc2c_options['enable_completed'] == true) {
        function rc2c_status_completed_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            // SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();		
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['completed_message']));

            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['completed_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('completed status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_completed', 'rc2c_status_completed_old');
    }

    if(isset($rc2c_options['enable_refunded']) && $rc2c_options['enable_refunded'] == true) {
        function rc2c_status_refunded_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            //SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['refunded_message']));

            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['refunded_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('refunded status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_refunded', 'rc2c_status_refunded_old');
    }

    if(isset($rc2c_options['enable_cancelled']) && $rc2c_options['enable_cancelled'] == true) {
        function rc2c_status_cancelled_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            //SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['cancelled_message']));

            $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['cancelled_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('cancelled status');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_order_status_cancelled', 'rc2c_status_cancelled_old');
    }

    if(isset($rc2c_options['enable_admin_message']) && $rc2c_options['enable_admin_message'] == true) {
        function rc2c_send_admin_notification_old($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return;
            }

            global $rc2c_options;
            //SMS Replacements Variables
            include(plugin_dir_path(__FILE__) . 'rc2c_message_variable.php');
            $data = array();
            // $data['phone'] = sanitize_text_field($rc2c_options['admin_mobile_number']);
            // $data['secret_key'] = sanitize_text_field($rc2c_options['secret_key']);
            // $data['message'] = sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['admin_sms_message']));

            $data['phone'] = isset($rc2c_options['admin_mobile_number']) ? sanitize_text_field($rc2c_options['admin_mobile_number']) : '';
            $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
            $data['message'] = is_array($rc2c_options) && isset($rc2c_options['pending_message']) ? sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options['admin_sms_message'])) : '';

            // Sends SMS using RingCaptcha API
            error_log('admin sms');
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
        }
        add_action('woocommerce_new_order', 'rc2c_send_admin_notification_old');
    }
}