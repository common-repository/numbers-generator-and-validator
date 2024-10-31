<?php
function ngv_activity_report_status()
{ ?>
    <div class="activity-row2">
        <h3>Status</h3>
        <div class="status-screen">
            <table class="status-table">
                <tr>
                    <th>Number</th>
                    <th>Date</th>
                    <th>Times Validated</th>
                    <th>User Ip Address</th>
                    <th>User System</th>
                </tr>
                <?php
                global $wpdb;
                $ngv_actvity_report_table_name = $wpdb->prefix . 'ngv_enterprise_activity_report';
                //search if activity report table exist if not create it
                if ($wpdb->get_var("SHOW TABLES LIKE '$ngv_actvity_report_table_name'") != $ngv_actvity_report_table_name) {
                    echo "<td><b>Please activate to start using activity control</b></td>";
                } else {
                    $ngv_actvity_report_search_table = $wpdb->get_results("SELECT * FROM $ngv_actvity_report_table_name");
                    foreach ($ngv_actvity_report_search_table as $index) {
                        echo "<tr><td>$index->number</td><td>$index->date</td><td>$index->times_validated</td><td>$index->ip_address</td><td>$index->user_system</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
<?php
}

// Create table function
function ngvActivityReportCreateTableFunction()
{
    global $wpdb;
    $ngv_activity_report_table_name = "ngv_enterprise_activity_report";
    $table_name = $wpdb->prefix . $ngv_activity_report_table_name;
    $charset_collate = $wpdb->get_charset_collate();
    $t = time();
    $creationdate = date("Y-m-d", $t);

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        date date DEFAULT '$creationdate' NOT NULL,
        number varchar(255) DEFAULT '$ngv_activity_report_table_name' NOT NULL,
        times_validated text NOT NULL,
        ip_address text NOT NULL,
        user_system text NOT NULL,
        UNIQUE KEY id (id)

    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function ngvARUpdateSettings()
{

    global $wpdb;
    $activityOption = get_option('ngv_enterprise_activity_report');

    // On
    if (!empty($_POST['activity-checkbox_on']) && empty($_POST['activity-submit2'])) {

        // Sets activity report option to 1
        $activityOption = array(1, $activityOption[1], $activityOption[2]);
        $ngv_actvity_report_option_on = update_option('ngv_enterprise_activity_report', $activityOption);

        // Activity report table
        $ngv_actvity_report_table_name = $wpdb->prefix . 'ngv_enterprise_activity_report';

        if (!checkTableExists($ngv_actvity_report_table_name)) {

            ngvActivityReportCreateTableFunction();
        }
    } else if (!empty($_POST['activity-checkbox_off'])) { // Off

        $activityOption = array(0, $activityOption[1], $activityOption[2]);

        update_option('ngv_enterprise_activity_report', $activityOption);
    }

    // Delete
    if (isset($_POST['activity-submit2'])) {
        $droprealtable = $wpdb->prefix . 'ngv_enterprise_activity_report';
        $wpdb->query("DROP TABLE IF EXISTS $droprealtable");
        $activityOption = array(0, $activityOption[1], $activityOption[2]);
        update_option('ngv_enterprise_activity_report', $activityOption);
    }

    // Number
    if (!empty($_POST['activity_number'])) {

        $number = sanitize_text_field($_POST['activity_number']);
        $activityOption = array($activityOption[0], $number, $activityOption[2]);
        update_option('ngv_enterprise_activity_report', $activityOption);
    }

    if (!empty($_POST['activity_response'])) {

        $response = sanitize_text_field($_POST['activity_response']);
        $activityOption = array($activityOption[0], $activityOption[1], $response);
        update_option('ngv_enterprise_activity_report', $activityOption);
    } else {

        $activityOption = array($activityOption[0], $activityOption[1], false);
        update_option('ngv_enterprise_activity_report', $activityOption);
    }
}

function ngv_activity_report_checker($serial_number)
{

    $activity_option = get_option('ngv_enterprise_activity_report');

    if (!$activity_option) {
        return false;
    }

    if (!ngvSerialValid($serial_number)) {
        return False;
    }

    global $wpdb;

    $ngv_actvity_report_table_name = $wpdb->prefix . 'ngv_enterprise_activity_report';

    if (!checkTableExists($ngv_actvity_report_table_name)) {
        ngvActivityReportCreateTableFunction();
    }

    $ngv_used_serial_search = '"' . $serial_number . '"';
    $ip_address = getenv('REMOTE_ADDR');
    $user_system = getenv('HTTP_USER_AGENT');
    $date = date("Y-m-d");

    // Check if number has been validated
    $times_validated = $wpdb->get_var('SELECT times_validated FROM ' . $wpdb->prefix . 'ngv_enterprise_activity_report WHERE number = ' . $ngv_used_serial_search . '');

    // Check if number has been validated over limit set
    if ($times_validated >= $activity_option[1]) {

        $times_validated = $times_validated + "1";

        // Return message
        return $activity_option[2];
    } else {

        $times_validated = $times_validated + "1";
    }

    // If number has not been validated add it to the table
    if ($times_validated == 1) {

        $wpdb->insert($wpdb->prefix . 'ngv_enterprise_activity_report', array('date' => 123, 'number' => $serial_number, 'times_validated' => 1));
    }
    // Update validation column
    $wpdb->query("UPDATE " . $wpdb->prefix . "ngv_enterprise_activity_report SET times_validated='$times_validated', date='$date', ip_address='$ip_address', user_system='$user_system' WHERE number='$serial_number'");

    return false;
}

if (isset($_POST['activity-submit']) || isset($_POST['activity-submit2'])) {
    check_admin_referer('ngv_admin_activity_form_nonce');

    if (!current_user_can('manage_options')) {
        exit;
    }

    ngvARUpdateSettings();
}
?>