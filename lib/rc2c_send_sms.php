<?php
/******************************
*  Ringcaptcha Direct SMS (Notifications)
******************************/

if ( ! defined( 'ABSPATH' ) ) exit;

global $rc2c_options;

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

$url = 'https://api.ringcaptcha.com/'.$rc2c_options['app_key'].'/sms';
error_log('SEND SMS: ' . print_r($args, true));
$response = wp_remote_post( $url, $args );

// Log the response for debugging
if ( is_wp_error( $response ) ) {
    // Log the error message
    error_log('SMS Error: ' . $response->get_error_message());
} else {
    // Log the full response
    error_log('SMS Response: ' . print_r($response, true));
    
    // Optionally, log the response body and status code separately
    error_log('Response Body: ' . wp_remote_retrieve_body($response));
    error_log('Response Code: ' . wp_remote_retrieve_response_code($response));
}