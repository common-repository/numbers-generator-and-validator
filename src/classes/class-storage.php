<?php

/**
 * ngvStorage
 * Description: Used at storage tab for admin
 * @package NGV
 * @since   1.17
 */


class NgvStorage
{

    public $getactivetables = array();
    public $active_serial_tables = array();
    public $matches = array();
    public $admin;

    public function __construct(&$admin)
    {

        $this->admin = $admin;

        // Update and transfer
        $check = get_option('new_validator_table');
        if (!empty($check)) {
            $this->updateTransfer();
        }

        $this->getActiveTables();
        $this->getTables();
        $this->endPoints();
    }

    public function getActiveTables()
    {
        $this->getactivetables = get_option('NumbersValidator_your_checked_tables');
        $this->active_serial_tables = get_option('Ngv_checked_serial_tables');

        // Check if you have any tables and if they are active
        $this->wpdb = $this->admin->wpdb;

        $check_table_name = array($this->wpdb->prefix . "number_validator", $this->wpdb->prefix . "ngv_used_serials");

        $found = false;

        if (is_array($this->getactivetables)) {

            for ($i = 0; $i < count($this->getactivetables); $i++) {

                foreach ($check_table_name as $table_name) {

                    if (strpos($this->getactivetables[$i], $table_name) !== false) {

                        $found = true;
                    }
                }
            }
        }

        if (!$found) {

            update_option('NumbersValidator_your_checked_tables', '');
        }
    }

    private function getTables()
    {
        $dbname = $this->wpdb->dbname;
        $tab = "Tables_in_" . $dbname;
        $sql_show_all_tab = "SHOW TABLES";
        $res_tables = $this->wpdb->get_results($sql_show_all_tab);
        $addtotablevar = "";

        // Sort out other tables and loop though all tables
        foreach ($res_tables as $alltables) {
            //add space between tables
            $addtotablevar .= $alltables->$tab . "|≤|";
        }

        // Explode variable in to pieces
        $tablepieces = explode("|≤|", $addtotablevar);

        // Crate a new array with the right tables
        $this->matches = preg_grep("/number_validator_/", $tablepieces);

        // Order array keys
        $this->matches = array_values($this->matches);
    }

    private function endPoints()
    {
        // Delete list
        if (isset($_POST['get_table_drop']) && !empty($_POST['dbtable-drop'])) {
            $this->deleteList($_POST['dbtable-drop']);
            $this->getTables();
        }

        // Create list
        if (isset($_POST['create_list'])) {
            $this->createList();
            $this->getTables();
        }
    }

    // Table dropdown function
    public function ngvShowSelectedTable($getTable)
    {

        $table_name = $this->wpdb->_escape($getTable);
        $sql_get_table_data = "SELECT * FROM $table_name";
        $sql_get_table_column_names = "SHOW COLUMNS FROM $table_name";
        $res_table_column_name = $this->admin->wpdb->get_results($sql_get_table_column_names);
        $res_table = $this->wpdb->get_results($sql_get_table_data);
        $html = "";

        $html .= "<table id='my-selected-table' border='1'><tr>";
        foreach ($res_table_column_name as $column) {
            $html .= "<td>" . $column->Field . "</td>";
        }
        $html .= "</tr>";
        foreach ($res_table as $tab_element => $element) {
            $html .= "<tr>";
            foreach ($element as $val => $value) {
                $html .= "<td>" . $value . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";

        return $html;
    }

    // create table
    private function ngvTableInstall()
    {

        if (empty($_POST['table_numbers'])) {

            return false;
        }

        if (empty($_POST['table_name'])) {

            return false;
        }

        $ngv_your_table_name = sanitize_text_field($_POST['table_name']);
        $realtable = 'number_validator_' . $ngv_your_table_name;
        $table_name = $this->wpdb->prefix . $realtable;
        $charset_collate = $this->wpdb->get_charset_collate();
        $t = time();
        $creationdate = date("Y-m-d", $t);

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            date date DEFAULT '$creationdate' NOT NULL,
            name varchar(255) DEFAULT '$ngv_your_table_name' NOT NULL,
            text text NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        $numbers = sanitize_text_field($_POST['table_numbers']);

        $this->wpdb->insert($table_name, array('text' => $numbers));

        return true;
    }

    // Drop table function
    private function ngvDropSelectedTable($ngv_drop_table)
    {
        $this->wpdb->query("DROP TABLE IF EXISTS $ngv_drop_table");
    }

    private function deleteList($tableName)
    {

        if (!check_admin_referer('ngv_admin_drop_table_nonce', 'ngv_admin_drop_table')) {
            die('Not a valid request');
        }

        $tableName = sanitize_text_field($tableName);

        $this->ngvDropSelectedTable($tableName);

        echo ngvSetCookie("ng-manager");
    }

    private function createList()
    {

        if (!check_admin_referer('ngv_admin_create_table_nonce', 'ngv_admin_create_table')) {
            die('Not a valid request');
        }

        if (!NGV_EN) {

            if (count($this->matches) <= 3) {

                return $this->ngvTableInstall();
            }
        } else if (NGV_EN) {

            return $this->ngvTableInstall();
        }
    }

    private function updateTransfer()
    {

        $ngv_check_version = get_option('number_validator');

        if ($ngv_check_version === "1.0") {

            $ngv_transfer = array(
                "1" => get_option('your_title'),
                "2" => get_option('your_text'),
                "3" => get_option('your_checked_tables'),
                "4" => get_option('number_validator_shortcode_name'),
                "5" => get_option('number_validator'),
                "6" => get_option('new_validator_table'),
            );

            $checked_tables_temp = get_option('your_checked_tables');
            $checked_tables_temp = explode(" ", $checked_tables_temp);
            $key = array_search('', $checked_tables_temp);
            unset($checked_tables_temp[$key]);
            $$checked_tables_temp = array_values($checked_tables_temp);
            add_option('NumbersValidator_your_checked_tables', $checked_tables_temp);
            add_option('NumbersValidator_your_title', $ngv_transfer[1]);
            add_option('NumbersValidator_your_text', $ngv_transfer[2]);
            add_option('NumbersValidator_shortcode_name', $ngv_transfer[4]);
            add_option('NumbersValidator_version', $ngv_transfer[6]);

            foreach ($this->matches as $old_table_values) {

                $this->wpdb->query("UPDATE $old_table_values SET name='$old_table_values'");
            }

            delete_option('your_title');
            delete_option('your_text');
            delete_option('your_checked_tables');
            delete_option('number_validator_shortcode_name');
            delete_option('number_validator');
            delete_option('new_validator_table');
            header("Refresh:0");
        }
    }
}
