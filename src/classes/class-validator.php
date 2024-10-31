<?php

/**
 * Validator
 * Description: Used at frontend
 * @package NGV
 * @since   1.13
 */

function findMatch($table_of_serials, $search_value)
{

    foreach ($table_of_serials as $array_of_serials) {

        foreach ($array_of_serials as $serial) {

            if ($serial === $search_value) {
                return true;
            }
        }
    }

    return false;
}

class NgvValidator
{

    private $active_serial_tables = array();
    private $wpdb;
    public $used_serial_table_name = "";

    public function __construct()
    {

        global $wpdb;

        $this->wpdb = $wpdb;

        $this->used_serial_table_name = $this->wpdb->prefix . "ngv_used_serials";

        $ngv_shortcode = get_option('NumbersValidator_shortcode_name');

        if (empty($ngv_shortcode)) {

            add_shortcode("ngv_my_validator_shortcode", array($this, 'ngvValidatorShortcodeFunction'));
        } else {

            $add_shortcode_validator = get_option('NumbersValidator_shortcode_name') . "_shortcode";
            add_shortcode($add_shortcode_validator, array($this, 'ngvValidatorShortcodeFunction'));
        }

        add_shortcode("ngv_serial_shortcode", array($this, 'ngvGenerateSerial'));

        $this->active_serial_tables = get_option('Ngv_checked_serial_tables');

        if (NGV_EN) {
            $this->url = plugins_url() . '/ngv-enterprise';
        } else {
            $this->url = plugins_url() . '/numbers-generator-and-validator';
        }

        wp_enqueue_style('ngv-shortcode-style', $this->url . '/css/shortcode.css');
        wp_enqueue_script('ngv-shortcode-script', $this->url . '/js/shortcode.js');
    }

    /**
     * @param array $table_names Array of used tables
     * @return array $array_to_check Array of serials
     */
    private function getArrayOfSerials($table_names)
    {

        $array_to_check = array();

        foreach ($table_names as $active_table) {

            $table_name = $this->wpdb->_escape($active_table);

            if ($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {

                $res_table = $this->wpdb->get_results("SELECT * FROM $table_name");

                $array_to_check[$active_table] = array();

                foreach ($res_table as $element) {

                    if (isset($element->text)) {

                        $array_to_check[$active_table] = array_merge($array_to_check[$active_table], explode(" ", $element->text));
                    } else if (isset($element->serial_added)) {

                        $array_to_check[$active_table] = array_merge($array_to_check[$active_table], explode(" ", $element->serial_added));
                    }
                }
            }
        }

        return $array_to_check;
    }

    /**
     * @param string $table_to_edit Table name
     * @param int $index Index to remove/move
     */
    private function setSerialAsUsed($table_to_edit, $index)
    {

        // If used serials table does not exist create it
        if ($this->wpdb->get_var("SHOW TABLES LIKE '" . $this->used_serial_table_name . "'") != $this->used_serial_table_name) {

            createSerialTable($this->used_serial_table_name);
        }

        $array_of_values = $this->getArrayOfSerials($this->active_serial_tables);

        $serial_to_move = $array_of_values[$table_to_edit][$index];

        unset($array_of_values[$table_to_edit][$index]);

        $string_with_spaces = implode(" ", $array_of_values[$table_to_edit]);

        $result = $this->wpdb->query("UPDATE $table_to_edit SET text='$string_with_spaces'");

        if ($result > 0) {
            $date = date("F j, Y, g:i a");
            $result = $this->wpdb->query("INSERT INTO $this->used_serial_table_name(date_added, serial_added) VALUES ('$date', '$serial_to_move')");

            if ($result < 1) {

                echo "Something went wrong! Please conact the admin. Error code: 212";
            }
        } else {

            echo "Something went wrong! Please contact admin. Error code: 211";
        }
    }

    public function getUsedTable()
    {
        return $this->used_serial_table_name;
    }
    // Start user shortcode
    public function ngvValidatorShortcodeFunction()
    {

        $title = get_option('NumbersValidator_your_title');
        $text = get_option('NumbersValidator_your_text');

        $html = '<div class="ngv-wrapper">';
        $html .= '<form id="ngv-validation-form" name="form1" method="post" action="">';
        ob_start();
        wp_nonce_field('ngv_validator_form_nonce');
        $html .= ob_get_clean();
        $html .= '<h2>' . $title . '</h2>';
        $html .= '<p>' . $text . '</p>';
        $html .= '<div class="ngv-flex"><input type="text" name="ngv-your-serial" id="ngv-validator-input" min="1" pattern=".{1,}" required title="1 characters minimum">';
        $html .= '<input type="submit" name="call_validator" class="ngv-call-validator" value="Check"></div>';
        $html .= '</form>';

        if (isset($_POST['call_validator']) && !empty($_POST['ngv-your-serial'])) {

            $your_serial = (string) sanitize_text_field($_POST['ngv-your-serial']);
            $response = $this->ngvValidateSerials($your_serial, false);

            if (NGV_EN) {

                $validation_limit_response = ngv_activity_report_checker($your_serial);

                if (is_string($validation_limit_response)) {

                    $response = $validation_limit_response;
                }
            }

            $html .= '<div id="validator-response" style="background-color:#3c3c3c;padding:10px;margin-top:10px;display:inline-block;border-radius:2px;color:#fff;">' . $response . '</div>';
        }

        $html .= '</div>';

        $fetch = "false";

        if (get_option('NumbersValidator_fetch') == 1) {
            $fetch = "true";
        }

        $html .= '<script>var ngv_settings = {api_url:"' . get_rest_url() . 'ngv/validate' . '", use_fetch: "' . $fetch . '"}</script>';
        return $html;
    }

    public function ngvGenerateSerial()
    {

        $response = 'No serials available contact the site admin.';

        if (empty($this->active_serial_tables) || count($this->active_serial_tables) < 1) {

            return $response;
        }

        $array_to_check = $this->getArrayOfSerials($this->active_serial_tables);
        $number_of_tables = count($array_to_check);

        if ($number_of_tables < 1) {

            return $response;
        }

        // Random table index
        $random_table = random_int(0, ($number_of_tables - 1));

        // Store key
        $table_to_edit = "";
        $count = 0;
        foreach ($array_to_check as $key => $value) {

            if ($random_table === $count) {
                $table_to_edit = $key;
            }

            $count++;
        }

        // Convert to numbered indexes
        $array_to_check = array_values($array_to_check);

        $number_of_serials = count($array_to_check[$random_table]);

        if ($number_of_serials < 1 || empty($array_to_check[$random_table][0])) {

            // $this->removeWhiteSpace( $table_to_edit );
            return $response;
        }

        // Random serial index
        $random_serial = random_int(0, ($number_of_serials - 1));

        $this->setSerialAsUsed($table_to_edit, $random_serial);

        return $array_to_check[$random_table][$random_serial];
    }

    /**
     * Validation function
     * @param string $search_value Serial to check
     * @param bool $bool_value If should return bool value
     */

    public function ngvValidateSerials($search_value, $bool_value = false)
    {

        $checked_tables = get_option('NumbersValidator_your_checked_tables');
        $array_of_serials = array();
        $response = "Your serial number is not valid!";

        // Check if there are no active tables
        if (empty($checked_tables) || empty($search_value)) {

            $validated = get_option('NumbersValidator_response2');

            if (is_string($validated) && !empty($validated)) {

                $response = $validated;
            }

            return $response;
        }

        // Find serial match in checked tables
        $found_match = findMatch($this->getArrayOfSerials($checked_tables), $search_value);

        // Output response
        if ($found_match) {

            $validated = get_option('NumbersValidator_response1');

            // Standard response
            if (!is_string($validated) || empty($validated)) {

                $validated = "Your serial number is valid!";
            }
        } else {

            $validated = get_option('NumbersValidator_response2');

            // Standard response
            if (empty($validated)) {

                $validated = "Your serial number is not valid!";
            }
        }

        // return bool true/false
        if ($bool_value) {

            return $found_match;
        }

        if (is_string($validated) && !empty($validated)) {

            $response = $validated;
        }

        return $response;
    }
}
