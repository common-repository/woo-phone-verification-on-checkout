<?php

if (!defined('ABSPATH')) exit;

class RingCaptcha_SMS_Notifications {
    private $options;

    public function __construct() {
        // $this->options = get_option('rc2c_settings');
        // add_action('woocommerce_order_status_changed', [$this, 'log_order_status_change'], 10, 4);
        // $this->register_hooks();
        $this->options = get_option('rc2c_settings');
        
        if (isset($this->options['enable_text_notification']) && $this->options['enable_text_notification']) {
            add_action('woocommerce_order_status_pending', [$this, 'send_sms_pending']);
            add_action('woocommerce_order_status_failed', [$this, 'send_sms_failed']);
            add_action('woocommerce_order_status_on-hold', [$this, 'send_sms_on_hold']);
            add_action('woocommerce_order_status_processing', [$this, 'send_sms_processing']);
            add_action('woocommerce_order_status_completed', [$this, 'send_sms_completed']);
            add_action('woocommerce_order_status_refunded', [$this, 'send_sms_refunded']);
            add_action('woocommerce_order_status_cancelled', [$this, 'send_sms_cancelled']);
        }    

        add_action('woocommerce_order_status_changed', [$this, 'log_order_status_change'], 10, 4);

        // For admin notifications
        if (isset($this->options['enable_admin_message']) && $this->options['enable_admin_message']) {
            error_log('Admin SMS notification is enabled'); // Log if admin message is enabled
            add_action('woocommerce_thankyou', [$this, 'send_admin_notification']);
        }
    }

    public function log_order_status_change($order_id, $old_status, $new_status, $order) {
        // Prepare the log message
        $log_message = sprintf(
            'Order ID: %d changed status from %s to %s',
            $order_id,
            $old_status,
            $new_status
        );

        // Log the message to the error log
        error_log($log_message);
    }

    private function send_sms($order_id, $message_key) {
        $order = wc_get_order($order_id);
        if (!$order) {
            error_log("Order not found for ID: $order_id"); // Log if the order is not found
            return;
        }

        // Define replacements here
        $replacements = [
            '{name}'         => ucfirst( sanitize_text_field( $order->get_billing_first_name() ) ),
            '{shop_name}'    => get_bloginfo('name'),
            '{order_id}'     => $order_id,
            '{order_amount}' => $order->get_total(),
            // Add any other replacements you need
        ];

        include_once(plugin_dir_path(__FILE__) . '../lib/rc2c_message_variable.php');
        
        $data = [
            'phone' => sanitize_text_field($order->get_billing_phone()), // Use the getter method
            'secret_key' => sanitize_text_field($this->options['secret_key']),
            'message' => sanitize_text_field(str_replace(array_keys($replacements), $replacements, $this->options[$message_key])),
        ];

        include(plugin_dir_path(__FILE__) . '../lib/rc2c_send_sms.php');
    }

       // SMS sending functions for each status
       public function send_sms_pending($order_id) {
        error_log("send_sms_pending called for Order ID: $order_id");
        $this->send_sms($order_id, 'pending_message');
    }

    public function send_sms_failed($order_id) {
        error_log("send_sms_failed called for Order ID: $order_id");
        $this->send_sms($order_id, 'failed_message');
    }

    public function send_sms_on_hold($order_id) {
        error_log("send_sms_on_hold called for Order ID: $order_id");
        $this->send_sms($order_id, 'on_hold_message');
    }

    public function send_sms_processing($order_id) {
        error_log("send_sms_processing called for Order ID: $order_id");
        $this->send_sms($order_id, 'processing_message');
    }

    public function send_sms_completed($order_id) {
        error_log("send_sms_completed called for Order ID: $order_id");
        $this->send_sms($order_id, 'completed_message');
    }

    public function send_sms_refunded($order_id) {
        error_log("send_sms_refunded called for Order ID: $order_id");
        $this->send_sms($order_id, 'refunded_message');
    }

    public function send_sms_cancelled($order_id) {
        error_log("send_sms_cancelled called for Order ID: $order_id");
        $this->send_sms($order_id, 'cancelled_message');
    }

    private function send_sms_request($data) {
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $data,
            'cookies' => array()
        );

        $url = 'https://api.ringcaptcha.com/' . $this->options['app_key'] . '/sms';
        error_log('admin notification: ' . print_r($args, true));
        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            error_log('Error sending SMS: ' . $response->get_error_message());
        } else {
            $status_code = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);
            error_log('SMS sent response: ' . print_r($response, true));
            error_log('Status Code: ' . $status_code);
            error_log('Response Body: ' . $body);
        }
    }

    public function send_admin_notification($order_id) {
        error_log('send_admin_notification called for order ID: ' . $order_id); // Log the order ID

        $order = wc_get_order($order_id);
        if (!$order) {
            error_log("Order not found for ID: $order_id"); // Log if the order is not found
            return;
        }

        include_once(plugin_dir_path(__FILE__) . '../lib/rc2c_message_variable.php');

        $data = [
            'phone' => sanitize_text_field($this->options['admin_mobile_number']),
            'secret_key' => sanitize_text_field($this->options['secret_key']),
            'message' => sanitize_text_field(str_replace(array_keys($replacements), $replacements, $this->options['admin_sms_message'])),
        ];

        $this->send_sms_request($data);
    }
}

new RingCaptcha_SMS_Notifications();