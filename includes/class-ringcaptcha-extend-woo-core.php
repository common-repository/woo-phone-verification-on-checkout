<?php
class RingCaptcha_Extend_Woo_Core {
    private $name = 'ringcaptcha';

    public function init() {
        $this->save_phone_verification();
        $this->show_phone_verification_in_order();
        $this->show_phone_verification_in_order_confirmation();
        $this->show_phone_verification_in_order_email();
        $this->validate_phone_verification();
        $this->add_phone_number();

        add_action('woocommerce_checkout_create_order', [$this, 'save_phone_number_from_widget'], 10, 2);
    }

    // Method to save the phone number
    function save_phone_number_from_widget($order, $data) {
        if (isset($_POST['billing_phone'])) {
            $order->set_billing_phone(sanitize_text_field($_POST['billing_phone']));
            error_log('Phone number saved: ' . sanitize_text_field($_POST['billing_phone'])); // Log the saved phone number
        } else {
            error_log('No phone number found in checkout data.');
        }
    }

    private function save_phone_verification() {
        add_action('woocommerce_store_api_checkout_update_order_from_request', function($order, $request) {
            $ringcaptcha_request_data = $request['extensions'][$this->name];
            $phone_verification_data = $ringcaptcha_request_data['phoneVerification'] ?? '';

            if (!empty($phone_verification_data)) {
                $order->update_meta_data('ringcaptcha_phone_verification', $phone_verification_data);
                $order->save();
            }
        }, 10, 2);
    }

    private function add_phone_number() {
        add_action('woocommerce_store_api_checkout_update_order_from_request', function($order, $request) {
            $ringcaptcha_request_data = $request['extensions'][$this->name];
            $phone_number = $ringcaptcha_request_data['phoneNumber'] ?? '';

            if (!empty($phone_number)) {
                $order->set_billing_phone(sanitize_text_field($phone_number));
                error_log('Phone number saved: ' . sanitize_text_field($phone_number));
                $order->save();
            } else {
                error_log('No phone number found in extensions data.');
            }
        }, 10, 2);
    }

    private function show_phone_verification_in_order() {
        add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<div><strong>' . esc_html__('Phone Verification', 'woo-phone-verification-on-checkout') . '</strong>';
                echo '<p>' . esc_html($phone_verification_data) . '</p></div>';
            }
        });
    }

    private function show_phone_verification_in_order_confirmation() {
        add_action('woocommerce_thankyou', function($order_id) {
            $order = wc_get_order($order_id);
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<h2>' . esc_html__('Phone Verification', 'woo-phone-verification-on-checkout') . '</h2>';
                echo '<p>' . esc_html($phone_verification_data) . '</p>';
            }
        });
    }

    private function show_phone_verification_in_order_email() {
        add_action('woocommerce_email_after_order_table', function($order, $sent_to_admin, $plain_text, $email) {
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<h2>' . esc_html__('Phone Verification', 'woo-phone-verification-on-checkout') . '</h2>';
                echo '<p>' . esc_html($phone_verification_data) . '</p>';
            }
        }, 10, 4);
    }

    private function validate_phone_verification() {
        add_action('woocommerce_checkout_process', function() {
            if (empty($_POST['extensions']['ringcaptcha']['phoneVerification']) || $_POST['extensions']['ringcaptcha']['phoneVerification'] !== 'verified') {
                wc_add_notice(__('Please verify your phone number before placing the order.', 'woo-phone-verification-on-checkout'), 'error');
            }
        });
    }
}