<?php
/******************************
* Admin Option for plugin
******************************/

if ( ! defined( 'ABSPATH' ) ) exit;

function rc2c_options_page_old() {
  global $rc2c_options;
  global $rc2c_plugin_name;

  // Retrieve the options and ensure it's an array
  $rc2c_options = get_option('rc2c_settings', array());

  ob_start(); ?>

<div class="wrap">
  <h2>
    <?php echo esc_html($rc2c_plugin_name) . ' ' . esc_html__('Options', 'woo-phone-verification-on-checkout'); ?>
  </h2>
  <form method="post" action="options.php">
    <?php settings_fields('rc2c_settings_group'); ?>
    <?php wp_nonce_field('rc2c_options_nonce_action', 'rc2c_options_nonce'); ?>
    <p>
      <input id="rc2c_settings[enable]" name="rc2c_settings[enable]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable'])) { checked(1, $rc2c_options['enable']); } ?> />
      <label class="description" for="rc2c_settings[enable]">
        <?php echo esc_html('Enable RingCaptcha2wooCommerce', 'woo-phone-verification-on-checkout'); ?>
      </label>
    </p>
    <h3>
      <?php echo esc_html('RingCaptcha Settings', 'woo-phone-verification-on-checkout'); ?>
    </h3>
    <table class="form-table">
      <tr valign="top">
        <?php echo wp_kses_post(__('To get your App Key and Secret Key click <a href="https://my.ringcaptcha.com/register" target="_blank">Here</a>', 'woo-phone-verification-on-checkout')); ?>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"><label class="description" for="rc2c_settings[app_key]">
            <?php echo esc_html('App key', 'woo-phone-verification-on-checkout'); ?>
          </label></th>
        <td class="forminp"><input id="rc2c_settings[app_key]" name="rc2c_settings[app_key]" type="text" value="<?php if(isset($rc2c_options['app_key'])) { echo esc_attr($rc2c_options['app_key']); } ?>"/></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"><label class="description" for="rc2c_settings[secret_key]">
            <?php echo esc_html('Secret key', 'woo-phone-verification-on-checkout'); ?>
          </label></th>
        <td class="forminp"><input id="rc2c_settings[secret_key]" name="rc2c_settings[secret_key]" type="text" value="<?php if(isset($rc2c_options['secret_key'])) { echo esc_attr(sanitize_text_field($rc2c_options['secret_key'])); } ?>"/></td>
      </tr>
    </table>
    <hr />
    <h3>
      <?php echo esc_html('Admin SMS Order Notifications', 'woo-phone-verification-on-checkout'); ?>
    </h3>
    <p style="color:#ff0000;"><?php echo wp_kses_post('*Note: Please contact us at <a href="mailto:support@ringcaptcha.com?subject=Request%20for%20SMS%20Activation&body=Hi%20Ringcaptcha,%20we%20are%20requesting%20to%20activate%20the%20SMS%20notification%20from%20WooCommerce%20Phone%20Verification%20by%20RingCaptcha">support@ringcaptcha.com</a> if you want to use SMS notifications. <br/>&emsp;&emsp;&emsp;&nbsp;You could also reach us through our Intercom (the chat pop-up) <a href="https://ringcaptcha.com" target="_blank">here.</a>', 'woo-phone-verification-on-checkout'); ?></p>
    <table class="form-table">
      <tr valign="top">
        <td class="forminp"><fieldset>
            <input id="rc2c_settings[enable_admin_message]" name="rc2c_settings[enable_admin_message]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_admin_message'])) { checked(1, $rc2c_options['enable_admin_message']); } ?> />
            <label class="description" for="rc2c_settings[enable_admin_message]">
              <?php echo esc_html('Enable SMS notifications', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Admin Mobile Number', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><input id="rc2c_settings[admin_mobile_number]" placeholder="<?php echo esc_html('+18558233280', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[admin_mobile_number]" type="text" value="<?php if(isset($rc2c_options['admin_mobile_number'])) { echo esc_attr(sanitize_text_field($rc2c_options['admin_mobile_number'])); } ?>"/></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {shop_name}, you have a new order ({order_id}) with total amount of ${order_amount}.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[admin_sms_message]"><?php if(isset($rc2c_options['admin_sms_message'])) { echo esc_textarea($rc2c_options['admin_sms_message']); } ?></textarea></td>
      </tr>
    </table>
    <hr />
    <h3>
      <?php echo esc_html('Customer SMS Order Notifications', 'woo-phone-verification-on-checkout'); ?>
    </h3>
    <table class="form-table">
      <tr valign="top">
        <td class="forminp"><fieldset>
            <input id="rc2c_settings[enable_text_notification]" name="rc2c_settings[enable_text_notification]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_text_notification'])) { checked(1, $rc2c_options['enable_text_notification']); } ?> />
            <label class="description" for="rc2c_settings[enable_text_notification]">
              <?php echo esc_html('Enable SMS notifications', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Enable which order statuses you want your customers to be notified', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><fieldset>
            <input id="rc2c_settings[enable_pending]" name="rc2c_settings[enable_pending]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_pending'])) { checked(1, $rc2c_options['enable_pending']); } ?> />
            <label class="description" for="rc2c_settings[enable_pending]">
              <?php echo esc_html('Pending', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_on_hold]" name="rc2c_settings[enable_on_hold]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_on_hold'])) { checked(1, $rc2c_options['enable_on_hold']); } ?> />
            <label class="description" for="rc2c_settings[enable_on_hold]">
              <?php echo esc_html('On-Hold', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_processing]" name="rc2c_settings[enable_processing]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_processing'])) { checked(1, $rc2c_options['enable_processing']); } ?> />
            <label class="description" for="rc2c_settings[enable_processing]">
              <?php echo esc_html('Processing', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_completed]" name="rc2c_settings[enable_completed]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_completed'])) { checked(1, $rc2c_options['enable_completed']); } ?> />
            <label class="description" for="rc2c_settings[enable_completed]">
              <?php echo esc_html('Completed', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_cancelled]" name="rc2c_settings[enable_cancelled]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_cancelled'])) { checked(1, $rc2c_options['enable_cancelled']); } ?> />
            <label class="description" for="rc2c_settings[enable_cancelled]">
              <?php echo esc_html('Cancelled', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_refunded]" name="rc2c_settings[enable_refunded]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_refunded'])) { checked(1, $rc2c_options['enable_refunded']); } ?> />
            <label class="description" for="rc2c_settings[enable_refunded]">
              <?php echo esc_html('Refunded', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset>
          <fieldset>
            <input id="rc2c_settings[enable_failed]" name="rc2c_settings[enable_failed]" type="checkbox" value="1" <?php if(isset($rc2c_options['enable_failed'])) { checked(1, $rc2c_options['enable_failed']); } ?> />
            <label class="description" for="rc2c_settings[enable_failed]">
              <?php echo esc_html('Failed', 'woo-phone-verification-on-checkout'); ?>
            </label>
          </fieldset></td>
      </tr>
            <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Message Variables', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp">

        <?php echo wp_kses_post('<ul>
          <li><code>{name}</code> &ndash; Customer first name</li>
          <li><code>{shop_name}</code> &ndash; Your shop name</li>
          <li><code>{order_id}</code> &ndash; Order ID</li>
          <li><code>{order_amount}</code> &ndash; The total amount of the order</li>
    </ul>', 'woo-phone-verification-on-checkout'); ?>

        </td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Pending SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Pending.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[pending_message]"><?php if(isset($rc2c_options['pending_message'])) { echo esc_textarea($rc2c_options['pending_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('On-Hold SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now On-Hold.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[on_hold_message]"><?php if(isset($rc2c_options['on_hold_message'])) { echo esc_textarea($rc2c_options['on_hold_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Processing SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Processing.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[processing_message]"><?php if(isset($rc2c_options['processing_message'])) { echo esc_textarea($rc2c_options['processing_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Completed SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Completed.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[completed_message]"><?php if(isset($rc2c_options['completed_message'])) { echo esc_textarea($rc2c_options['completed_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Cancelled SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Cancelled.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[cancelled_message]"><?php if(isset($rc2c_options['cancelled_message'])) { echo esc_textarea($rc2c_options['cancelled_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Refunded SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Refunded.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[refunded_message]"><?php if(isset($rc2c_options['refunded_message'])) { echo esc_textarea($rc2c_options['refunded_message']); } ?></textarea></td>
      </tr>
      <tr valign="top">
        <th class="titledesc" scope="row"> <?php echo esc_html('Failed SMS Message', 'woo-phone-verification-on-checkout'); ?>
        </th>
        <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {name}, your order ({order_id}) is now Failed.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[failed_message]"><?php if(isset($rc2c_options['failed_message'])) { echo esc_textarea($rc2c_options['failed_message']); } ?></textarea></td>
      </tr>
    </table>
<hr />
<h3>
  <?php echo esc_html('GDPR', 'woo-phone-verification-on-checkout'); ?>
</h3>
<p>
  <?php echo wp_kses_post(__('<b>*Note:</b> Need to get consent from users in order to be GDPR compliant? No problem!<br/>RingCaptcha now has a GDPR-compliant version of the phone verification widget.', 'woo-phone-verification-on-checkout')); ?></p>
<input id="rc2c_settings[gdpr_implementation]" name="rc2c_settings[gdpr_implementation]" type="checkbox" value="1" <?php if(isset($rc2c_options['gdpr_implementation'])) { checked(1, $rc2c_options['gdpr_implementation']); } ?> />
<label class="description" for="rc2c_settings[gdpr_implementation]">
  <?php echo esc_html('Check if you need to get consent from users for GDPR compliance.', 'woo-phone-verification-on-checkout'); ?>
</label>
<table class="form-table">
  <tr valign="top">
    <th class="titledesc" scope="row"> <?php echo esc_html('GDPR Consent Message', 'woo-phone-verification-on-checkout'); ?>
    </th>
    <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('I would like to receive discount updates and promotions in accordance with GDPR standards.', 'woo-phone-verification-on-checkout'); ?>" name="rc2c_settings[gdpr_consent_message]"><?php if(isset($rc2c_options['gdpr_consent_message'])) { echo esc_textarea($rc2c_options['gdpr_consent_message']); } ?></textarea></td>
  </tr>
</table>
<hr />
<h3>
  <?php echo esc_html('Troubleshooting', 'woo-phone-verification-on-checkout'); ?>
</h3>
<p>
  <?php echo wp_kses_post(__('<b>*Note:</b> Some hosting providers do not support calling HTTP requests making it unable for the plugin to verify through <br/> our API if the phone number has successfully verified. This causes a \'stuck on checkout page\' problem. Check this <br/> fix to use a Javascript workaround.', 'woo-phone-verification-on-checkout')); ?>
  </p>
<input id="rc2c_settings[js_implementation]" name="rc2c_settings[js_implementation]" type="checkbox" value="1" <?php if(isset($rc2c_options['js_implementation']) && $rc2c_options['js_implementation']) { checked(1, $rc2c_options['js_implementation']); } ?> />
<label class="description" for="rc2c_settings[js_implementation]">
  <?php echo esc_html('Stuck on Checkout Fix (Javascript Workaround)', 'woo-phone-verification-on-checkout'); ?>
</label>
<p class="submit">
  <input type="submit" class="button-primary" value="<?php echo esc_html('Save Changes', 'woo-phone-verification-on-checkout'); ?>" />
</p>
</form>
</div>
<hr />
<h3>
  <?php echo esc_html('Send Direct SMS', 'woo-phone-verification-on-checkout'); ?>
</h3>
<?php
// Ensure $order is defined and valid
// $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
// if ($order_id > 0) {
//   $order = wc_get_order($order_id);
// } else {
//   $order = null;
// }
$data = array();
$data['phone'] = isset($_POST['mobile_test']) ? sanitize_text_field($_POST['mobile_test']) : '';
$data['secret_key'] = isset($rc2c_options['secret_key']) ? $rc2c_options['secret_key'] : '';
$data['message'] = isset($_POST['your_message']) ? esc_textarea($_POST['your_message']) : '';

if(isset($_POST['submitted']) && $_POST['submitted'] == 'true') {
  include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
  $warning = __('<em style="color:#00960C;">SMS has been sent to recipient</em><br/>', 'woo-phone-verification-on-checkout');
}

?>
<form name="sendsmsdirect" method="post" action="#submit">
  <table class="form-table">
    <tr valign="top">
      <th class="titledesc" scope="row"> <?php echo esc_html('Mobile Number', 'woo-phone-verification-on-checkout'); ?>
      </th>
      <td class="forminp">
      <?php if(isset($warning)) { echo wp_kses_post($warning); } ?>
      <input id="rc2c_settings[mobile_test]" placeholder="<?php echo esc_html('+18558233280', 'woo-phone-verification-on-checkout'); ?>" name="mobile_test" type="text" required="required" value="<?php if(isset($rc2c_options['mobile_test'])) { echo esc_attr(sanitize_text_field($rc2c_options['mobile_test'])); } ?>"/></td>
    </tr>
    <tr valign="top">
      <th class="titledesc" scope="row"> <?php echo esc_html('SMS Message', 'woo-phone-verification-on-checkout'); ?>
      </th>
      <td class="forminp">
        <textarea style="width:40%; height: 65px;" id="your_message" required="required" maxlength="160" name="your_message"><?php if(isset($rc2c_options['your_message'])) { echo esc_textarea($rc2c_options['your_message']); } ?></textarea><br/>
        <div id="charcount"></div>
      </td>
    </tr>
    <tr valign="top">
      <th class="titledesc" scope="row"> </th>
      <td class="forminp"><input type="hidden" name="submitted" value="true" />
        <input type="submit" id="submit" class="button" value="<?php echo esc_html('Send', 'woo-phone-verification-on-checkout'); ?>" /></td>
    </tr>
  </table>
</form>
<hr />
<p><?php echo wp_kses_post('If you have problems using this plugin, you can reach us through our Intercom <a href="https://ringcaptcha.com" target="_blank">here</a> or send us an email at <a href="mailto:support@ringcaptcha.com">support@ringcaptcha.com</a>', 'woo-phone-verification-on-checkout'); ?></p>
<?php
  echo ob_get_clean();
}

function rc2c_add_options_link_old() {
  global $rc2c_plugin_name;

  add_submenu_page( 'woocommerce', $rc2c_plugin_name.' Options', 'RingCaptcha', 'manage_options', 'rc2c-options', 'rc2c_options_page_old' );
}
add_action('admin_menu', 'rc2c_add_options_link_old');

function rc2c_register_settings_old() {
  // creates our settings in the options table
  register_setting('rc2c_settings_group', 'rc2c_settings');
}
add_action('admin_init', 'rc2c_register_settings_old');