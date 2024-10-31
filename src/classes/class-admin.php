<?php

/**
 * NgvAdmin
 * Description: Main plugin class
 * @package NGV
 * @since   1.13
 */
if (!class_exists('ngvStorage')) {
    require_once NGV_PATH . '/src/classes/class-storage.php';
}

if (!class_exists('NgvSettings')) {
    require_once NGV_PATH . '/src/classes/class-settings.php';
}

if (!class_exists('NgvAC')) {
    require_once NGV_PATH . '/src/classes/class-activity-control.php';
}

class NgvAdmin
{

    private $depricated = "";
    public $ngv_version = "";
    private $plugin_name = "NumbersValidator_version";
    private $plugin_version = NGV_VER;
    private $url = "";
    private $autoload = "yes";
    private $plugin_type = "";
    public $main_email = 'axel@onewayapplications.com';
    public $enterpise_link = 'https://onewayapplications.com/products/ngv-enterprise/';
    public $used_serial_table = '';
    public $validator;
    public $wpdb;
    public $prefix;

    public function __construct(&$validator)
    {

        global $wpdb;

        $this->wpdb = $wpdb;
        $this->validator = $validator;
        $this->prefix = $wpdb->prefix;

        if (NGV_EN) {

            $this->plugin_type = "enterprise";
            $this->url = plugins_url() . '/ngv-enterprise';
        } else {

            $this->plugin_type = "standard";
            $this->url = plugins_url() . '/numbers-generator-and-validator';
        }

        $this->used_serial_table = $this->validator->used_serial_table_name;

        $this->ngv_version = $this->plugin_version . " " . $this->plugin_type;

        update_option($this->plugin_name, $this->plugin_version, $this->depricated, $this->autoload);
        add_action('admin_enqueue_scripts', array($this, 'ngAdminEnqueue'));


        $this->ngvStorage = new NgvStorage($this);
        $this->ngvSettings = new NgvSettings($this);
        $this->ngvAC = new NgvAC($this);


        add_action('admin_menu', array($this, 'adminMenu'));
    }



    public function adminMenu()
    {
        add_management_page('ngv_plugin_options', 'Numbers Validator', 'manage_options', 'my-unique-identifier', array($this, 'adminPage'));
    }

    public function adminPage()
    {
?>
        <div class="ngv">
            <div class="wrap">
                <ul class="tab">
                    <li>
                        <a href="#generator/" id="firsttablink" class="tablinks" data-index="ng-gen" data-target="main-tab-content"><?php echo __('Generator', 'ngv') ?></a>
                    </li>
                    <li>
                        <a href="#manager/" id="sectablink" class="tablinks" data-index="ng-manager" data-target="main-tab-content"><?php echo __('Storage', 'ngv') ?></a>
                    </li>
                    <li>
                        <a href="#validator/" id="thirdtablink" class="tablinks" data-index="ng-val" data-target="main-tab-content"><?php echo __('Settings', 'ngv') ?></a>
                    </li>
                    <li>
                        <a href="#activity/" id="forthtablink" class="tablinks" data-index="ng-ac" data-target="main-tab-content"><?php echo __('Activity Control', 'ngv') ?></a>
                    </li>
                </ul>
                <div id="main-tabs">
                    <!-- Generate tab -->
                    <div id="ng-gen" class="main-tab-content">
                        <div class="ngv-flex-row ngv-top-20">
                            <?php require_once NGV_PATH . '/src/views/template-generator.php'; ?>
                            <?php ngvSidebar($this); ?>
                        </div>
                    </div>
                    <!-- Manager tab -->
                    <div id="ng-manager" class="main-tab-content">
                        <div class="ngv-top-20">
                            <?php require NGV_PATH . '/src/views/template-storage.php'; ?>
                            <?php storageTab($this->ngvStorage); ?>
                        </div>
                    </div>
                    <!-- Validator tab -->
                    <div id="ng-val" class="main-tab-content">
                        <?php require NGV_PATH . '/src/views/template-settings.php'; ?>
                        <div class="ngv-flex-row ngv-top-20">
                            <?php settingsTab($this->ngvSettings); ?>
                            <?php ngvSidebar($this); ?>
                        </div>
                        <?php settingsSection($this->ngvSettings); ?>
                    </div>
                    <div id="ng-ac" class="main-tab-content">
                        <div class="ngv-top-20">
                            <?php if (!NGV_EN) : ?>
                                <p>
                                    <span> <?php echo __('Only available in Entrerpirse edition', 'ngv') ?></span>
                                </p>
                            <?php endif; ?>
                            <?php $this->ngvAC->acTab(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    /**
     *  Admin enqueue scripts and styles
     */
    public function ngAdminEnqueue($hook)
    {

        if ('tools_page_my-unique-identifier' != $hook) {
            return;
        }

        wp_enqueue_script('my-generator-script', $this->url . '/js/dist/generator.js', array('jquery'));
        wp_enqueue_script('my-tabs-script', $this->url . '/js/dist/tabs.js', array('jquery'));
        wp_enqueue_style('ngav-style', $this->url . '/css/style.css');
        wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'we_value' => 1234));
    }
}
