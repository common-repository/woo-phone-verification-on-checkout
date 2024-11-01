<?php
/**
 * Plugin Name: RingCaptcha - Phone Verification on Checkout & SMS Order Notifications
 * Plugin URI: https://wordpress.org/plugins/woo-phone-verification-on-checkout/
 * Description: This plugin replaces the default phone field on your WooCommerce checkout page with RingCaptcha’s phone verification widget. It also adds automated SMS notifications for both sellers and customers. For more information about <a href="https://ringcaptcha.com/" target="_blank">RingCaptcha</a> visit our site
 * Version: 4.0
 * Author: RingCaptcha
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-phone-verification-on-checkout
 */

if (!defined('ABSPATH')) exit;

define('RINGCAPTCHA_VERSION', '4.0');

// Ensure default options are set upon plugin activation
function rc2c_activate() {
    error_log('RingCaptcha: Activating plugin');
    $default_options = array(
        'enable' => 0,
        'app_key' => '',
        'secret_key' => '',
        'admin_mobile_number' => '',
        'admin_sms_message' => '',
        'pending_message' => '',
        'on_hold_message' => '',
        'processing_message' => '',
        'completed_message' => '',
        'cancelled_message' => '',
        'refunded_message' => '',
        'failed_message' => '',
        'gdpr_consent_message' => '',
    );

    // Merge existing options with defaults
    $rc2c_options = get_option('rc2c_settings', array());
    $rc2c_options = array_merge($default_options, $rc2c_options);

    update_option('rc2c_settings', $rc2c_options);
}
register_activation_hook(__FILE__, 'rc2c_activate');

function ringcaptcha_load_textdomain() {
    error_log('RingCaptcha: Loading text domain for RingCaptcha plugin.');
    load_plugin_textdomain('woo-phone-verification-on-checkout', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'ringcaptcha_load_textdomain');

// Retrieve our plugin settings from the options table
function get_rc2c_options() {
    // Declare the variable as global to access it
    global $rc2c_options;

    // Initialize the options
    if (!isset($rc2c_options)) {
        $rc2c_options = get_option('rc2c_settings', array());

        // Ensure default values are set if options are not set
        if (!is_array($rc2c_options)) {
            $rc2c_options = array();
        }

        // Set default values if specific keys are not present
        $default_options = array(
            'enable' => 0,
            'app_key' => '',
            'secret_key' => '',
            'admin_mobile_number' => '',
            'admin_sms_message' => '',
            'pending_message' => '',
            'on_hold_message' => '',
            'processing_message' => '',
            'completed_message' => '',
            'cancelled_message' => '',
            'refunded_message' => '',
            'failed_message' => '',
            'gdpr_consent_message' => '',
        );

        // Merge existing options with defaults
        $rc2c_options = array_merge($default_options, $rc2c_options);
    }
    return $rc2c_options;
}

function get_woocommerce_version() {
    if (class_exists('WooCommerce')) {
        return WC()->version;
    }
    return null;
}

function is_using_new_checkout() {
        // Check if the WC_Blocks_Utils class exists
        if (class_exists('WC_Blocks_Utils')) {
            // Check if the checkout block is present on the checkout page
            if (WC_Blocks_Utils::has_block_in_page(wc_get_page_id('checkout'), 'woocommerce/checkout')) {
                return true; // New checkout is being used
            } else {
                return false;
            }
        }
}

function ringcaptcha_init() {
    static $initialized = false;

    if ($initialized) {
        error_log('RingCaptcha: Plugin already initialized. Exiting.');
        return;
    }

    // Get the options
    $rc2c_options = get_rc2c_options();
    error_log('RingCaptcha: Loaded plugin settings: ' . print_r($rc2c_options, true));

    $wc_version = get_woocommerce_version();
    error_log('RingCaptcha: WooCommerce version: ' . $wc_version);

    if (is_using_new_checkout()) {
        error_log('RingCaptcha: WooCommerce version is 8.3 or greater. Loading new implementation.');
        require_once __DIR__ . '/includes/class-ringcaptcha-admin.php';
        require_once __DIR__ . '/includes/class-ringcaptcha-sms-notifications.php';

        error_log('RingCaptcha: settings: ' . print_r($rc2c_options['enable'], true));
        if (!is_admin() && (isset($rc2c_options['enable']) && ($rc2c_options['enable'] == 1 || $rc2c_options['enable'] == '1' || $rc2c_options['enable'] === true))) {
            add_action('woocommerce_blocks_loaded', function() use ($rc2c_options) {
                error_log('RingCaptcha: WooCommerce Blocks loaded.');
                require_once __DIR__ . '/includes/class-ringcaptcha-extend-store-endpoint.php';
                require_once __DIR__ . '/includes/class-ringcaptcha-extend-woo-core.php';
                require_once __DIR__ . '/includes/class-ringcaptcha-blocks-integration.php';

                RingCaptcha_Extend_Store_Endpoint::init();
                error_log('RingCaptcha: Initialized RingCaptcha_Extend_Store_Endpoint.');

                $extend_core = new RingCaptcha_Extend_Woo_Core();
                $extend_core->init();
                error_log('RingCaptcha: Initialized RingCaptcha_Extend_Woo_Core.');

                // Check if the integration is already registered
                if (!has_action('woocommerce_blocks_checkout_block_registration', 'register_ringcaptcha_blocks_integration')) {
                    add_action('woocommerce_blocks_checkout_block_registration', 'register_ringcaptcha_blocks_integration');
                }
            });

            function register_ringcaptcha_blocks_integration($integration_registry) {
                error_log('RingCaptcha: Registering RingCaptcha Blocks Integration.');
                $integration_registry->register(new RingCaptcha_Blocks_Integration());
            }

            function register_ringcaptcha_block_category($categories) {
                error_log('RingCaptcha: Registering RingCaptcha block category.');
                return array_merge(
                    $categories,
                    array(
                        array(
                            'slug'  => 'ringcaptcha',
                            'title' => __('RingCaptcha Blocks', 'woo-phone-verification-on-checkout'),
                        ),
                    )
                );
            }
            add_action('block_categories_all', 'register_ringcaptcha_block_category', 10, 2);

            // Add the filter to override the checkout phone field
            add_filter('woocommerce_checkout_fields', 'override_checkout_phone_field');

            function override_checkout_phone_field($fields) {
                error_log('RingCaptcha: Overriding checkout phone field.');
                // Remove the default phone field
                unset($fields['billing']['billing_phone']);

                // Add the custom phone field
                $fields['billing']['billing_custom_phone'] = array(
                    'type' => 'text',
                    'label' => __('Phone', 'woo-phone-verification-on-checkout'),
                    'placeholder' => _x('Phone', 'placeholder', 'woo-phone-verification-on-checkout'),
                    'required' => true,
                    'class' => array('form-row-wide'),
                    'clear' => true,
                    'priority' => 20,
                );
                error_log('RingCaptcha: Added custom phone field to checkout.');
                return $fields;
            }

            // Add the phone verification validation using WooCommerce Blocks API
            add_action('woocommerce_blocks_checkout_update_order_from_request', 'validate_phone_verification', 10, 2);

            function validate_phone_verification($order, $data) {
                error_log('RingCaptcha: validate_phone_verification called');

                if (empty($data['extensions']['ringcaptcha']['phoneVerification']) || $data['extensions']['ringcaptcha']['phoneVerification'] !== 'verified') {
                    error_log('RingCaptcha: Phone verification failed');
                    throw new Exception(
                        __('Please verify your phone number before placing the order.', 'woo-phone-verification-on-checkout'),
                        400
                    );
                } else {
                    error_log('RingCaptcha: Phone verification succeeded');
                }
            }
        }
    } else {
        error_log('RingCaptcha: WooCommerce version is less than 8.3 or not detected. Loading old implementation.');
        require_once __DIR__ . '/lib/rc2c_admin.php';
        require_once __DIR__ . '/lib/rc2c_admin_function.php';

        if (isset($rc2c_options['enable']) && ($rc2c_options['enable'] == 1 || $rc2c_options['enable'] == '1' || $rc2c_options['enable'] === true)) {
            // Include older version admin classes
            require_once __DIR__ . '/lib/rc2c_display_function.php';

            load_plugin_textdomain('woo-phone-verification-on-checkout', false, basename(dirname(__FILE__)) . '/languages');
            error_log('RingCaptcha: Loaded text domain for older version.');

            define('RC2C_PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');

            add_action('before_woocommerce_init', function() {
                error_log("RingCaptcha: WooCommerce initialized.");
                if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
                    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
                    error_log("RingCaptcha: HPOS compatibility declared.");
                } else {
                    error_log("RingCaptcha: HPOS compatibility class not found.");
                }
            });
        }
    }

    error_log('RingCaptcha: Initializing plugin.');
    $initialized = true;
}

add_action('plugins_loaded', 'ringcaptcha_init');