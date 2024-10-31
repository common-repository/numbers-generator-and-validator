<?php
class ngv_settings {
    public $plugin_name = "NumbersValidator_version";
    public $plugin_version = "1.12";
    public $plugin_edition = "standard";
    public $ngv_version = "";
    public $depricated = "";
    public $autoload = "yes";
}

$ngv_settings = new ngv_settings();

$ngv_settings->ngv_version = $ngv_settings->plugin_version . " " . $ngv_settings->plugin_edition;

update_option($ngv_settings->plugin_name, $ngv_settings->plugin_version, $ngv_settings->depricated, $ngv_settings->autoload);
?>