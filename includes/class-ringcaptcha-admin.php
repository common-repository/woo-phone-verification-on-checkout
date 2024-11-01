<?php

if (!defined('ABSPATH')) exit;

class RingCaptcha_Admin {
    private $options;

    public function __construct() {
        error_log('RingCaptcha_Admin: __construct called');
        $this->options = get_option('rc2c_settings');
        error_log('RingCaptcha_Admin: Loaded options: ' . print_r($this->options, true));
        
        add_action('admin_menu', [$this, 'add_options_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_options_page() {
        error_log('RingCaptcha_Admin: add_options_page called');
        add_submenu_page('woocommerce', 'RingCaptcha Options', 'RingCaptcha', 'manage_options', 'rc2c-options', [$this, 'render_options_page']);
    }

    public function register_settings() {
        error_log('RingCaptcha_Admin: register_settings called');
        register_setting('rc2c_settings_group', 'rc2c_settings');
    }

    public function render_options_page() {
        error_log('RingCaptcha_Admin: render_options_page called');
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__('RingCaptcha Options', 'woo-phone-verification-on-checkout'); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('rc2c_settings_group');
                wp_nonce_field('rc2c_options_nonce_action', 'rc2c_options_nonce');
                ?>
                <h3><?php echo esc_html__('RingCaptcha Settings', 'woo-phone-verification-on-checkout'); ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Enable RingCaptcha2wooCommerce', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><input type="checkbox" name="rc2c_settings[enable]" value="1" <?php checked(1, isset($this->options['enable']) ? $this->options['enable'] : 0); ?> /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('App Key', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><input type="text" name="rc2c_settings[app_key]" value="<?php echo esc_attr($this->options['app_key'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Secret Key', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><input type="text" name="rc2c_settings[secret_key]" value="<?php echo esc_attr($this->options['secret_key'] ?? ''); ?>" /></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Admin SMS Order Notifications', 'woo-phone-verification-on-checkout'); ?></h3>
                <p style="color:#ff0000;">
                    <?php echo wp_kses_post('*Note: Please contact us at <a href="mailto:support@ringcaptcha.com?subject=Request%20for%20SMS%20Activation&body=Hi%20Ringcaptcha,%20we%20are%20requesting%20to%20activate%20the%20SMS%20notification%20from%20WooCommerce%20Phone%20Verification%20by%20RingCaptcha">support@ringcaptcha.com</a> if you want to use SMS notifications. <br/>&emsp;&emsp;&emsp;&nbsp;You could also reach us through our Intercom (the chat pop-up) <a href="https://ringcaptcha.com" target="_blank">here.</a>', 'woo-phone-verification-on-checkout'); ?>
                </p>
                <table class="form-table">
                    <tr valign="top">
                        <td class="forminp">
                            <fieldset>
                                <input type="checkbox" name="rc2c_settings[enable_admin_message]" value="1" <?php checked(1, isset($this->options['enable_admin_message']) ? $this->options['enable_admin_message'] : 0); ?> />
                                <label class="description" for="rc2c_settings[enable_admin_message]"><?php esc_html_e('Enable SMS notifications', 'woo-phone-verification-on-checkout'); ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Admin Mobile Number', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><input type="text" name="rc2c_settings[admin_mobile_number]" placeholder="<?php esc_attr_e('+18558233280', 'woo-phone-verification-on-checkout'); ?>" value="<?php echo esc_attr($this->options['admin_mobile_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMS Message', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><textarea name="rc2c_settings[admin_sms_message]" style="width:40%; height: 65px;" placeholder="<?php esc_attr_e('Hi {shop_name}, you have a new order ({order_id}) with total amount of ${order_amount}.', 'woo-phone-verification-on-checkout'); ?>"><?php echo esc_textarea($this->options['admin_sms_message'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Customer SMS Order Notifications', 'woo-phone-verification-on-checkout'); ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <td class="forminp">
                            <fieldset>
                                <input type="checkbox" name="rc2c_settings[enable_text_notification]" value="1" <?php checked(1, isset($this->options['enable_text_notification']) ? $this->options['enable_text_notification'] : 0); ?> />
                                <label class="description" for="rc2c_settings[enable_text_notification]"><?php esc_html_e('Enable SMS notifications', 'woo-phone-verification-on-checkout'); ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Enable which order statuses you want your customers to be notified', 'woo-phone-verification-on-checkout'); ?></th>
                        <td class="forminp">
                            <?php
                            $statuses = [
                                'pending' => 'Pending',
                                'on_hold' => 'On-Hold',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                                'failed' => 'Failed'
                            ];
                            foreach ($statuses as $status => $label) {
                                ?>
                                <fieldset>
                                    <input type="checkbox" name="rc2c_settings[enable_<?php echo $status; ?>]" value="1" <?php checked(1, isset($this->options["enable_$status"]) ? $this->options["enable_$status"] : 0); ?> />
                                    <label class="description" for="rc2c_settings[enable_<?php echo $status; ?>]"><?php esc_html_e($label, 'woo-phone-verification-on-checkout'); ?></label>
                                </fieldset>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Message Variables', 'woo-phone-verification-on-checkout'); ?></th>
                        <td class="forminp">
                            <?php echo wp_kses_post('<ul>
                                <li><code>{name}</code> &ndash; Customer first name</li>
                                <li><code>{shop_name}</code> &ndash; Your shop name</li>
                                <li><code>{order_id}</code> &ndash; Order ID</li>
                                <li><code>{order_amount}</code> &ndash; The total amount of the order</li>
                            </ul>'); ?>
                        </td>
                    </tr>
                    <?php
                    foreach ($statuses as $status => $label) {
                        ?>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e("$label SMS Message", 'woo-phone-verification-on-checkout'); ?></th>
                            <td><textarea name="rc2c_settings[<?php echo $status; ?>_message]" style="width:40%; height: 65px;" placeholder="<?php esc_attr_e("Hi {name}, your order ({order_id}) is now $label.", 'woo-phone-verification-on-checkout'); ?>"><?php echo esc_textarea($this->options["{$status}_message"] ?? ''); ?></textarea></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <hr />
                <h3><?php echo esc_html__('GDPR', 'woo-phone-verification-on-checkout'); ?></h3>
                <p><?php echo wp_kses_post(__('<b>*Note:</b> Need to get consent from users in order to be GDPR compliant? No problem!<br/>RingCaptcha now has a GDPR-compliant version of the phone verification widget.', 'woo-phone-verification-on-checkout')); ?></p>
                <input type="checkbox" name="rc2c_settings[gdpr_implementation]" value="1" <?php checked(1, isset($this->options['gdpr_implementation']) ? $this->options['gdpr_implementation'] : 0); ?> />
                <label class="description" for="rc2c_settings[gdpr_implementation]"><?php esc_html_e('Check if you need to get consent from users for GDPR compliance.', 'woo-phone-verification-on-checkout'); ?></label>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('GDPR Consent Message', 'woo-phone-verification-on-checkout'); ?></th>
                        <td><textarea name="rc2c_settings[gdpr_consent_message]" style="width:40%; height: 65px;" placeholder="<?php esc_attr_e('I would like to receive discount updates and promotions in accordance with GDPR standards.', 'woo-phone-verification-on-checkout'); ?>"><?php echo esc_textarea($this->options['gdpr_consent_message'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Troubleshooting', 'woo-phone-verification-on-checkout'); ?></h3>
                <p><?php echo wp_kses_post(__('<b>*Note:</b> Some hosting providers do not support calling HTTP requests making it unable for the plugin to verify through <br/> our API if the phone number has successfully verified. This causes a \'stuck on checkout page\' problem. Check this <br/> fix to use a Javascript workaround.', 'woo-phone-verification-on-checkout')); ?></p>
                <input type="checkbox" name="rc2c_settings[js_implementation]" value="1" <?php checked(1, isset($this->options['js_implementation']) ? $this->options['js_implementation'] : 0); ?> />
                <label class="description" for="rc2c_settings[js_implementation]"><?php esc_html_e('Stuck on Checkout Fix (Javascript Workaround)', 'woo-phone-verification-on-checkout'); ?></label>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'woo-phone-verification-on-checkout'); ?>" />
                </p>
            </form>
        </div>
        <hr />
        <h3><?php echo esc_html__('Send Direct SMS', 'woo-phone-verification-on-checkout'); ?></h3>
        <?php
        $data = [
            'phone' => isset($_POST['mobile_test']) ? sanitize_text_field($_POST['mobile_test']) : '',
            'secret_key' => $this->options['secret_key'] ?? '',
            'message' => isset($_POST['your_message']) ? sanitize_textarea_field($_POST['your_message']) : ''
        ];

        if (isset($_POST['submitted']) && sanitize_text_field($_POST['submitted']) === 'true') {
            include(plugin_dir_path(__FILE__) . '../lib/rc2c_send_sms.php');
            $warning = __('<em style="color:#00960C;">SMS has been sent to recipient</em><br/>', 'woo-phone-verification-on-checkout');
            error_log('RingCaptcha_Admin: SMS sent to ' . $data['phone']);
        }
        ?>
        <form name="sendsmsdirect" method="post" action="#submit">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Mobile Number', 'woo-phone-verification-on-checkout'); ?></th>
                    <td>
                        <?php if (isset($warning)) {
                            echo wp_kses_post($warning);
                        } ?>
                        <input type="text" name="mobile_test" placeholder="<?php esc_attr_e('+18558233280', 'woo-phone-verification-on-checkout'); ?>" required="required" value="<?php echo esc_attr($data['phone']); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('SMS Message', 'woo-phone-verification-on-checkout'); ?></th>
                    <td>
                        <textarea name="your_message" style="width:40%; height: 65px;" required="required" maxlength="160"><?php echo esc_textarea($data['message']); ?></textarea><br/>
                        <div id="charcount"></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"></th>
                    <td>
                        <input type="hidden" name="submitted" value="true" />
                        <input type="submit" id="submit" class="button" value="<?php esc_attr_e('Send', 'woo-phone-verification-on-checkout'); ?>" />
                    </td>
                </tr>
            </table>
        </form>
        <hr />
        <p><?php echo wp_kses_post('If you have problems using this plugin, you can reach us through our Intercom <a href="https://ringcaptcha.com" target="_blank">here</a> or send us an email at <a href="mailto:support@ringcaptcha.com">support@ringcaptcha.com</a>', 'woo-phone-verification-on-checkout'); ?></p>
        <?php
    }
}

new RingCaptcha_Admin();