<?php
function storageTab($ngvStorage)
{ ?>
    <div class="ngv-flex-row">
        <div id="create-tables" class="ngv-content-div">
            <h2 class="ngv-top-0"><?php echo __('Create List', 'ngv') ?></h2>
            <p class="ngv-bottom-24"><?php echo __('Create a list in the WordPress database where you can store your numbers. I would not recommend entering
                    more than 10 000 numbers at a time. The reason for that is that your database might refuse more than that.
                    You can add additional numbers to an existing table by entering the existing table name and submit numbers
                    to it.', 'ngv') ?></p>

            <form id="create-table" action="#manager/" method="post">

                <?php wp_nonce_field('ngv_admin_create_table_nonce', 'ngv_admin_create_table'); ?>

                <div class="ngv-bottom-24">
                    <div class="ngv-label"><?php echo __('Table name', 'ngv') ?></div>
                    <input type="text" name="table_name" id="table-name" required>
                </div>

                <p class="ngv-bottom-24"><b>
                        <?php echo __('Remember to add space between each number. Adding space tells the plugin where the value ends and a new begins.', 'ngv') ?></b>
                </p>
                <div class="ngv-label">
                    <?php echo __('Enter serials here:', 'ngv') ?>
                </div>

                <textarea type="textarea" name="table_numbers" id="table-numbers" cols="40" rows="5" required></textarea>
                <div class="ngv-top-10">
                    <input type="submit" class="button-primary" value="Save List" name="create_list">
                </div>
            </form>
        </div>
        <?php ngvSidebar($ngvStorage->admin); ?>
    </div>
    <div class="ngv-row">
        <div id="show-tables">
            <h2><?php echo __('Show Lists', 'ngv') ?></h2>
            <?php
            // Check if no tables exist
            if (empty($ngvStorage->matches) && $ngvStorage->admin->wpdb->get_var("SHOW TABLES LIKE '" . $ngvStorage->admin->validator->getUsedTable() . "'") != $ngvStorage->admin->used_serial_table) {

                echo 'You have not created any lists';
            } else { ?>
                <span><?php echo __('Select a list', 'ngv') ?></span>
                <form name="fromdbtable" id="fromdbtable" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">
                    <?php wp_nonce_field('ngv_admin_get_table_nonce', 'ngv_admin_get_table'); ?>
                    <select id="dbtable" name="dbtable">
                        <option name="dbtable" value="<?php echo $ngvStorage->admin->used_serial_table; ?>">
                            <?php echo __('Used serials', 'ngv') ?>
                        </option>
                        <?php foreach ($ngvStorage->matches as $yourtable) { ?>
                            <option name="dbtable" value="<?php echo $yourtable; ?>">
                                <?php $yourtables_name = $ngvStorage->wpdb->get_var("SELECT name FROM $yourtable"); ?>
                                <?php echo $yourtables_name; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input class="button-primary" type="submit" name="get_table" value="Show">
                </form>
            <?php if (isset($_POST['dbtable'])) {
                    check_admin_referer('ngv_admin_get_table_nonce', 'ngv_admin_get_table');
                    $getTable = sanitize_text_field($_POST['dbtable']);
                    echo $ngvStorage->ngvShowSelectedTable($getTable, $ngvStorage->wpdb);
                    echo '<script>document.cookie = "ng-manager";</script>';
                }
            } ?>
        </div>
        <div id="delete-tables">
            <h2><?php echo __('Delete Lists', 'ngv') ?></h2>
            <p><?php echo __('Used serials cannot be deleted only cleared', 'ngv') ?></p>
            <?php
            if (empty($ngvStorage->matches)) {
                echo 'You have not created any lists';
            } else { ?>
                <span><?php echo __('Select a list', 'ngv') ?></span>
                <form name="fromdbtable-drop" id="fromdbtable-drop" action="" method="post">
                    <?php wp_nonce_field('ngv_admin_drop_table_nonce', 'ngv_admin_drop_table'); ?>
                    <select id="dbtable-drop" name="dbtable-drop">
                        <option name="dbtable" value="<?php echo $ngvStorage->admin->used_serial_table; ?>">
                            <?php echo __('Used serials', 'ngv') ?>
                        </option>
                        <?php foreach ($ngvStorage->matches as $yourtables) { ?>
                            <option name="dbtable-drop" value="<?php echo $yourtables; ?>">
                                <?php $yourtables_name = $ngvStorage->admin->wpdb->get_var("SELECT name FROM $yourtables"); ?>
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
?>