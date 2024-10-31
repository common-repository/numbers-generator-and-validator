<?php

/**
 * Plugin Name: Numbers generator and validator
 * Author: Axel Grytt
 * Author URI: https://www.onewayapplications.com/
 * Description: This plugin makes it easy to generate numbers and create a validator for the frontend user on your site.
 * Tested up to: 5.4.2
 * Version: 2.0.8
 */

if (!defined('ABSPATH')) {
    exit;
}

const NGV_PATH = __DIR__;
const NGV_EN = false;
const NGV_VER = "2.0.8";

require_once NGV_PATH . '/func.php';

if (!class_exists('NgvValidator')) {
    require_once NGV_PATH . '/src/classes/class-validator.php';
}

add_action('init', function () {

    $validator = new NgvValidator();

    if (NGV_EN) {

        require_once NGV_PATH . '/src/enterprise/activity-control-options.php';
    }

    require_once NGV_PATH . '/src/endpoints.php';

    if (current_user_can('manage_options')) {

        if (!class_exists('NgvAdmin')) {
            require_once NGV_PATH . '/src/classes/class-admin.php';
        }

        new NgvAdmin($validator);
    }
});
