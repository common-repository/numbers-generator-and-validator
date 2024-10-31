<?php

/**
 * Activity control template
 *
 * @package NGV\src\classes
 * @since   1.17
 */

class NgvAC
{

    private $admin;
    private $wpdb;

    public function __construct(&$admin)
    {
        $this->admin = $admin;
        $this->validator = $this->admin->validator;
        $this->wpdb = $this->admin->wpdb;
    }

    private function ngvUsedSerialsStatus()
    {

        $found = false;

        // If used serials table does not exist create it
        if (!checkTableExists($this->admin->used_serial_table)) {

            createSerialTable($this->admin->used_serial_table);
        } else {

            $html = "";

            $data_from_db = $this->wpdb->get_results("SELECT * FROM " . $this->admin->used_serial_table);

            if (is_array($data_from_db) && count($data_from_db) > 0) {

                foreach ($data_from_db as $value) {

                    $html .= "<tr><td>$value->date_added</td><td>$value->serial_added</td></tr>";
                }

                $found = true;
            } else {
                $text = __('No serials added yet!', 'ngv');
                $html = "<tr><td>$text</td></tr>";
            }
        }

        if (!$found) {

            echo $html;

            return false;
        }

?>
        <div class="activity-row2">
            <h3><?php echo __('Status', 'ngv') ?></h3>
            <div class="status-screen">
                <table class="status-table">
                    <tr>
                        <th><?php echo __('Date added', 'ngv') ?></th>
                        <th><?php echo __('Serial added', 'ngv') ?></th>
                    </tr>
                    <?php echo $html; ?>
                </table>
            </div>
        </div>
    <?php
    }
    public function acTab()
    { ?>
        <div class="ngv-flex-row">
            <?php if (!NGV_EN) { ?>
                <div class="ngv-content-div">
                    <div id="custom_message"></div>
                    <h3><?php echo __('Entrerpirse editon includes:', 'ngv') ?></h3>
                    <ul style="list-style-type:square; padding:0 30px;">
                        <li><?php echo __('Activity control section where you can set how many times a number can be validated.', 'ngv') ?></li>
                        <li><?php echo __('Track how many times a number has been validated and show the ip-address of users that have validated a
                            number.', 'ngv') ?></li>
                        <li><?php echo __('Use unlimited lists instead of four.', 'ngv') ?></li>
                    </ul>
                    <h3><?php echo __('How to buy it', 'ngv') ?></h3>
                    <p><?php echo __('Go to', 'ngv') ?> <a href="http://www.onewayapplications.com/ngv-enterprise">onewayapplications.com/ngv-enterprise</a>.
                        <?php echo __('Enter your email and make the payment with PayPal. A key and a link will be sent your email. Click the link and enter the key to start the download.', 'ngv') ?></p>
                </div>
            <?php } else { ?>
                <?php $activityOption = get_option('ngv_enterprise_activity_report'); ?>
                <div class="activity-row1">
                    <p class="ngv-top-0"><?php echo __('Here can you check how many times a number has been validated and who validated it. You can also control how
                        many times a number can be validated before it gets invalid. More features will come in the future.', 'ngv') ?></p>
                    <form id="activity-form" action="" method="post">
                        <?php wp_nonce_field('ngv_admin_activity_form_nonce'); ?>
                        <div class="ngv-bottom-24">
                            <h2 class="ngv-top-0"><?php echo __('Activate or Deactivate', 'ngv') ?></h2>
                            <input type="radio" id="activity-checkbox_on" name="activity-checkbox_on" <?php if (
                                                                                                            $activityOption[0] == 1
                                                                                                        ) {
                                                                                                            echo 'checked';
                                                                                                        } ?> onclick="document.getElementById('activity-checkbox_off').checked = false;">on
                            <br>
                            <input type="radio" id="activity-checkbox_off" name="activity-checkbox_off" <?php if (
                                                                                                            $activityOption[0] == 0
                                                                                                        ) {
                                                                                                            echo 'checked';
                                                                                                        } ?> onclick="document.getElementById('activity-checkbox_on').checked = false;">off
                        </div>
                        <?php if ($activityOption[0] == 1 && $activityOption[0] != null) { ?>
                            <div class="ngv-bottom-24">
                                <h2 class="ngv-top-0"><?php echo __('Validation limit', 'ngv') ?></h2>
                                <div class="ngv-bottom-8"><?php echo __('Select how many times a number should be validated.', 'ngv') ?></div>
                                <input type="number" name="activity_number" value="<?php echo $activityOption[1]; ?>">
                            </div>
                            <div class="ngv-bottom-24">
                                <h2 class="ngv-top-0"><?php echo __('Response message', 'ngv') ?></h2>
                                <div class="ngv-bottom-8"><?php echo __('The message the frontend user sees if the number has exceeded the validation limit.', 'ngv') ?></div>
                                <textarea class="activity-response" type="text" name="activity_response"><?php echo $activityOption[2]; ?></textarea>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="submit" class="button-primary" name="activity-submit" value="save settings">
                            <input type="submit" class="button-secondary" name="activity-submit2" value="delete" onclick="return confirm('Are you sure? All your information will be permanently deleted');">
                        </div>
                        <div class="ngv-top-8"><b><?php echo __('Delete removes all activity data.', 'ngv') ?></b></div>
                    </form>
                </div>
            <?php } ?>
            <?php ngvSidebar($this->admin); ?>
        </div>
        <?php if (NGV_EN) { ?>
            <?php ngv_activity_report_status(); ?>
            <div class="ngv-flex-row">
                <div class="ngv-content-div">
                    <h2 class="ngv-top-0"><?php echo __('Used serials', 'ngv') ?></h2>
                    <?php $this->ngvUsedSerialsStatus(); ?>
                </div>
            </div>
        <?php } ?>
<?php }
}
