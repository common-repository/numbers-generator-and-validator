<?php

/**
 * NgvManager
 * Description: Used at storage tab for admin
 * @package NGV
 * @since   1.17
 */

class NgvManager
{

    public $getactivetables = array();
    public $active_serial_tables = array();
    public $matches = array();
    private $admin;

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

    public function getTables()
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

    function endPoints()
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

    public function deleteList($tableName)
    {

        if (!check_admin_referer('ngv_admin_drop_table_nonce', 'ngv_admin_drop_table')) {
            die('Not a valid request');
        }

        $tableName = sanitize_text_field($tableName);

        $this->ngvDropSelectedTable($tableName);

        echo ngvSetCookie("ng-manager", true);
    }

    public function createList()
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

    public function updateTransfer()
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

    public function managerTab()
    { ?>
        <div class="ngv-flex-row">
            <div id="create-tables" class="ngv-content-div">
                <h2>Create List</h2>
                <p class="ngv-bottom-24">Create a list in the WordPress database where you can store your numbers. I would not recommend entering
                    more than 10 000 numbers at a time. The reason for that is that your database might refuse more than that.
                    You can add additional numbers to an existing table by entering the existing table name and submit numbers
                    to it.</p>

                <form id="create-table" action="#manager/" method="post">

                    <?php wp_nonce_field('ngv_admin_create_table_nonce', 'ngv_admin_create_table'); ?>

                    <div class="ngv-bottom-24">
                        <div class="ngv-label">Table name</div>
                        <input type="text" name="table_name" id="table-name" required>
                    </div>

                    <p>
                    Remember to add space between each number. Adding space tells the plugin where the value ends
                                and a
                                new begins.</p>
                    <div class="ngv-label">
                        Enter list numbers or text here
                    </div>

                    <textarea type="textarea" name="table_numbers" id="table-numbers" cols="40" rows="5" required></textarea>
                    <div class="ngv-top-10">
                        <input type="submit" class="button-primary" value="Save List" name="create_list">
                    </div>
                </form>
            </div>
            <?php ngvSidebar($this->admin); ?>
        </div>
        <div class="ngv-d-flex">
            <div id="show-tables">
                <h2>Show Lists</h2>
                <?php
                        // Check if no tables exist
                        if (empty($this->matches) && $this->wpdb->get_var("SHOW TABLES LIKE '" . $this->admin->validator->getUsedTable() . "'") != $this->admin->used_serial_table) {

                            echo 'You have not created any lists';
                        } else { ?>
                    <span>Select a list</span>
                    <form name="fromdbtable" id="fromdbtable" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">
                        <?php wp_nonce_field('ngv_admin_get_table_nonce', 'ngv_admin_get_table'); ?>
                        <select id="dbtable" name="dbtable">
                            <option name="dbtable" value="<?php echo $this->admin->used_serial_table; ?>">
                                Used serials
                            </option>
                            <?php foreach ($this->matches as $yourtable) { ?>
                                <option name="dbtable" value="<?php echo $yourtable; ?>">
                                    <?php $yourtables_name = $this->wpdb->get_var("SELECT name FROM $yourtable"); ?>
                                    <?php echo $yourtables_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input class="button-primary" type="submit" name="get_table" value="Show">
                    </form>
                <?php if (isset($_POST['dbtable'])) {
                                check_admin_referer('ngv_admin_get_table_nonce', 'ngv_admin_get_table');
                                $getTable = sanitize_text_field($_POST['dbtable']);
                                echo $this->ngvShowSelectedTable($getTable, $this->wpdb);
                                echo '<script>document.cookie = "ng-manager";</script>';
                            }
                        } ?>
            </div>
            <div id="delete-tables">
                <h2>Delete Lists</h2>
                <p>Used serials cannot be deleted only cleared</p>
                <?php
                        if (empty($this->matches)) {
                            echo 'You have not created any lists';
                        } else { ?>
                    <span>Select a list</span>
                    <form name="fromdbtable-drop" id="fromdbtable-drop" action="" method="post">
                        <?php wp_nonce_field('ngv_admin_drop_table_nonce', 'ngv_admin_drop_table'); ?>
                        <select id="dbtable-drop" name="dbtable-drop">
                            <option name="dbtable" value="<?php echo $this->admin->used_serial_table; ?>">
                                Used serials
                            </option>
                            <?php foreach ($this->matches as $yourtables) { ?>
                                <option name="dbtable-drop" value="<?php echo $yourtables; ?>">
                                    <?php $yourtables_name = $this->admin->wpdb->get_var("SELECT name FROM $yourtables"); ?>
                                    <?php echo $yourtables_name; ?>
                                </option>
                            <?php } ?>
                            <input type="submit" id="get_table_drop" name="get_table_drop" value="Delete" class="button-primary">
                        </select>
                    </form>
                <?php } ?>
            </div>
        </div>
<?php
    }
}
