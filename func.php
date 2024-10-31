<?php

function ngvSetCookie($target)
{
    $html = '';

    $html .= '<script>';
    $html .= 'document.cookie = "' . $target . '";';

    $html .= '</script>';

    return $html;
}

function checkTableExists($table_name)
{
    global $wpdb;

    $bool_flag = false;

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") === $table_name) {
        $bool_flag = true;
    }

    return $bool_flag;
}

function createSerialTable($table_name)
{
    global $wpdb;
    //table not in database. Create new table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        date_added text NOT NULL,
        serial_added text NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function ngvValidateSerial($value)
{

    $validator = new NgvValidator();
    $response = $validator->ngvValidateSerials((string) sanitize_text_field($value));

    if (NGV_EN) {

        $validation_limit_response = ngv_activity_report_checker($value);

        if (is_string($validation_limit_response)) {

            $response = $validation_limit_response;
        }
    }

    return $response;
}

function ngvSerialValid($value)
{
    $validator = new NgvValidator();
    return $validator->ngvValidateSerials((string) sanitize_text_field($value), true);
}

function ngvGenerateSerial()
{
    $validator = new NgvValidator();
    return $validator->ngvGenerateSerial();
}


function ngvSidebar($ngvAdmin)
{ ?>
    <div class="ngv-sidebar">
        <h2 class="ngv-top-0"><?php echo __('Version', 'ngv'); ?></h2>
        <p><?php echo $ngvAdmin->ngv_version; ?></p>
        <?php if (!NGV_EN) { ?>
            <p><?php echo __('Get Enterprise verion', 'ngv'); ?> <a href="<?php echo $ngvAdmin->enterpise_link; ?>"><?php echo __('here', 'ngv'); ?></a></p>
        <?php } ?>
        <hr>
        <p>
            <strong><?php echo __('For support and info contact:', 'ngv'); ?></strong>
            <br>
            <a href="mailto:<?php echo $ngvAdmin->main_email; ?>"><?php echo $ngvAdmin->main_email; ?></a>
        </p>
    </div>
<?php
}
