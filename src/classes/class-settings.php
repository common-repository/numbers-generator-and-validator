<?php

/**
 * NgvSettings
 * Description: Used at settings tab for admin
 * @package NGV
 * @since   1.17
 */

class NgvSettings
{

    public $validator_name = "[ngv_my_validator_shortcode]";
    public $serial_name = "[ngv_serial_shortcode]";
    private $admin;

    /**
     * @param class $admin Passed by reference
     */
    public function __construct(&$admin)
    {

        $this->admin = $admin;
        $this->ngvStorage = $this->admin->ngvStorage;

        $this->endpoints();
    }

    private function endpoints()
    {
        if (!empty($_POST['validatorsave'])) {

            if (!check_admin_referer('ngv_admin_validation_creator_nonce', 'ngv_admin_validation_creator')) {
                die();
            }

            $table_list = "";

            if (!empty($_POST['tables'])) {

                $table_list = $_POST['tables'];

                if (is_array($_POST['tables'])) {

                    foreach ($table_list as &$table) {

                        $table = sanitize_text_field($table);
                    }
                } else {

                    $table_list = sanitize_text_field($table_list);
                }
            }

            $this->ngvSaveValidator($table_list);
            $this->ngvStorage->getActiveTables();
        }

        if (!empty($_POST['show-serial-save'])) {

            if (!check_admin_referer('ngv_admin_show_serial', 'ngv_admin_show_serial_nonce')) {
                die();
            }

            $table_list = "";

            if (!empty($_POST['tables'])) {

                if (is_array($_POST['tables'])) {

                    $table_list = $_POST['tables'];

                    foreach ($table_list as &$table) {

                        $table = sanitize_text_field($table);
                    }
                } else {

                    $table_list = sanitize_text_field($table_list);
                }
            }

            $this->ngvSaveValidator($table_list, true);
            $this->ngvStorage->getActiveTables();
        }

        // Custom shortcode name call
        if (isset($_POST['custom_shortcode'])) {
            check_admin_referer('ngv_admin_custom_shortcode_nonce', 'ngv_admin_custom_shortcode');
            $custom_shortcode = sanitize_text_field($_POST['custom_shortcode']);
            $this->ngvCustomShortcodeFunction($custom_shortcode);
        }
    }

    private function checkActiveTable($match, $name, $serial = false)
    {

        $list_object = [
            'match' => $match,
            'input_checked' => '',
            'table_name' => $name,
            'if_active' => ''

        ];

        $active_tables = array();

        if (!empty($this->ngvStorage->getactivetables) && !$serial) {
            $active_tables = $this->ngvStorage->getactivetables;
        } else if (!empty($this->ngvStorage->active_serial_tables) && $serial) {

            $active_tables = $this->admin->ngvStorage->active_serial_tables;
        }

        if (!empty($active_tables)) {

            foreach ($active_tables as $active_table) {
                if ($active_table === $list_object['match']) {
                    $list_object['input_checked'] = 'checked';
                    $list_object['if_active'] = '<div class="active-tag-col">active</div>';
                }
            }
        }

        return $list_object;
    }

    private function renderListObject($list_object)
    { ?>
        <div class="active-list-col">
            <input type="checkbox" name="tables[]" value="<?php echo $list_object['match']; ?>" <?php echo $list_object['input_checked']; ?>>
            <?php echo $list_object['table_name']; ?>
        </div>
<?php echo $list_object['if_active'] . "<br>";
    }

    /**
     * @param class $ngv_manager Manager class instance
     * @param boolean $serial If is serial tables
     */

    // Shortcode name function
    private function ngvCustomShortcodeFunction($custom_shortcode)
    {
        update_option('NumbersValidator_shortcode_name', $custom_shortcode);
    }


    /**
     * Save data for validator shortcode
     * @param array $table_list List of tables to save
     * @param boolean $serial If is serial tables
     */
    private function ngvSaveValidator($table_list, $serial = false)
    {

        if ($serial) {

            // Add/update checked tables array option
            update_option('Ngv_checked_serial_tables', $table_list);
        } else {

            // Add/update title and description to database
            if (!empty($_POST['validatortitle'])) {
                update_option('NumbersValidator_your_title', sanitize_text_field($_POST['validatortitle']));
            }

            if (!empty($_POST['validatortext'])) {
                update_option('NumbersValidator_your_text', sanitize_text_field($_POST['validatortext']));
            }

            // Add/update checked tables array option
            if (is_array($table_list)) {
                update_option('NumbersValidator_your_checked_tables', $table_list);
            }

            // Add/update validator responses to database
            if (!empty($_POST['validatorresponse1'])) {
                update_option('NumbersValidator_response1', sanitize_text_field($_POST['validatorresponse1']));
            }

            if (!empty($_POST['validatorresponse2'])) {
                update_option('NumbersValidator_response2', sanitize_text_field($_POST['validatorresponse2']));
            }

            if (!empty($_POST['ngv-fetch-setting'])) {
                if (sanitize_text_field($_POST['ngv-fetch-setting']) === "on") {
                    update_option('NumbersValidator_fetch', 1);
                }
            } else {
                update_option('NumbersValidator_fetch', 0);
            }
        }
    }

    public function tablesToUse($show_serials = false)
    {

        $gettablenamespieces = array();

        if (empty($this->ngvStorage->matches) || !is_array($this->ngvStorage->matches)) {

            echo 'You need to create a list <a class="back-to-storage" href="#">here</a> before you can add lists for the validator';

            return false;
        }

        // Table names to be used
        if (!empty($this->ngvStorage->matches) && is_array($this->ngvStorage->matches)) {

            foreach ($this->ngvStorage->matches as $gettablename) {
                array_push($gettablenamespieces, $this->admin->wpdb->get_var('SELECT name FROM ' . $gettablename));
            }

            array_push($gettablenamespieces, $this->admin->used_serial_table);
        }

        foreach ($this->ngvStorage->matches as $index => $match) {

            $list_object = $this->checkActiveTable($match, 'Please recreate this list.', $show_serials);

            if (!empty($gettablenamespieces[$index])) {

                $list_object['table_name'] = $gettablenamespieces[$index];
            }

            $this->renderListObject($list_object);
        }

        if (!$show_serials) {

            $list_object = $this->checkActiveTable($this->admin->used_serial_table, 'Used serials', $show_serials);

            $this->renderListObject($list_object);
        }
    }
}
