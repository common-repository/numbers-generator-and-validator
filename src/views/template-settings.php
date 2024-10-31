<?php

function settingsTab($ngvSettings)
{ ?>
    <div id="validator-div">
        <div class="subtabs">
            <ul class="tab">
                <li>
                    <a href="#settings-tab-1/" id="first-settings-tab" class="settings-tab-links" data-index="settings-tab-1" data-target="settings-tab-content">
                        <?php echo __('Create validator', 'ngv') ?></a>
                </li>
                <li>
                    <a href="#settings-tab-2/" id="sec-settings-tab" class="settings-tab-links" data-index="settings-tab-2" data-target="settings-tab-content">
                        <?php echo __('Serial generator', 'ngv') ?></a>
                </li>
            </ul>
            <div class="ngv-30">
                <div id="settings-tab-1" class="settings-tab-content">
                    <form id="validation-creator" name="validation-creator" method="post" action="">
                        <div class="ngv-bottom-30">
                            <h2 class="ngv-top-0"><?php echo __('Tables', 'ngv') ?></h2>
                            <?php $ngvSettings->tablesToUse(); ?>
                        </div>
                        <h2><?php echo __('Frontend', 'ngv') ?></h2>
                        <div class="ngv-row">
                            <div class="ngv-col-6">
                                <div class="ngv-bottom-16">
                                    <div class="ngv-label"><?php echo __('Validator title', 'ngv') ?></div>
                                    <input type="text" name="validatortitle" id="validatortitle" value="<?php echo get_option('NumbersValidator_your_title'); ?>">
                                </div>
                                <div class="ngv-bottom-16">
                                    <div class="ngv-label"><?php echo __('Validator description text', 'ngv') ?></div>
                                    <textarea type="textarea" cols="60" rows="5" name="validatortext" id="validatortext"><?php echo get_option('NumbersValidator_your_text') ?></textarea>
                                </div>
                                <div id="response1">
                                    <div class="ngv-label"><?php echo __('Response if number exist', 'ngv') ?></div>
                                    <input type="text" name="validatorresponse1" id="validatorresponse1" value="<?php echo get_option('NumbersValidator_response1'); ?>">
                                </div>
                                <div id="response2">
                                    <div class="ngv-label"><?php echo __('Response if number doesn\'t exist', 'ngv') ?></div>
                                    <input type="text" name="validatorresponse2" id="validatorresponse2" value="<?php echo get_option('NumbersValidator_response2'); ?>">
                                </div>
                            </div>
                            <div class="ngv-col-6">
                                <div class="ngv-label"><b><?php echo __('Use fetching', 'ngv') ?></b></div>
                                <div class="ngv-row">
                                    <input style="margin-top: 0px" type="checkbox" name="ngv-fetch-setting" <?php if (get_option('NumbersValidator_fetch')) {
                                                                                                                echo "checked";
                                                                                                            } ?>>
                                    <div class="ngv-bottom-8"><?php echo __('Validate without page reload', 'ngv') ?></div>
                                </div>
                            </div>
                        </div>
                        <?php wp_nonce_field('ngv_admin_validation_creator_nonce', 'ngv_admin_validation_creator'); ?>
                        <div class="ngv-top-20">
                            <input type="submit" class="button-primary" name="validatorsave" value="Save">
                        </div>
                    </form>
                </div>
                <div id="settings-tab-2" class="settings-tab-content">
                    <h2 class="ngv-top-0"><?php echo __('Tables', 'ngv') ?></h2>
                    <form id="show-serials-settings" name="show-serials-settings" method="post" action="">
                        <?php $ngvSettings->tablesToUse(true); ?>
                        <?php wp_nonce_field('ngv_admin_show_serial', 'ngv_admin_show_serial_nonce'); ?>
                        <div class="ngv-top-20">
                            <input type="submit" class="button-primary" name="show-serial-save" value="Save Settings">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }

function settingsSection($ngvSettings)
{

    if (get_option('NumbersValidator_shortcode_name')) {

        $ngvSettings->validator_name = "[" . get_option('NumbersValidator_shortcode_name') . "_shortcode]";
    }

    if (get_option('NumbersValidator_gen_shortcode')) {

        $ngvSettings->serial_name = "[" . get_option('NumbersValidator_gen_shortcode') . "_shortcode]";
    } ?>
    <div class="title-div">
        <h2><?php echo __('Shortcodes & functions', 'ngv') ?></h2>
    </div>
    <div class="subtabs">
        <ul class="tab">
            <li>
                <a href="#short-tab-1/" id="first-short-tab" class="short-tab-links" data-index="short-tab-1" data-target="short-tab-content">
                    <?php echo __('Validator', 'ngv') ?></a>
            </li>
            <li>
                <a href="#short-tab-2/" id="sec-short-tab" class="short-tab-links" data-index="short-tab-2" data-target="short-tab-content">Show
                    <?php echo __('serials', 'ngv') ?></a>
            </li>
        </ul>
        <div class="wrap">
            <div id="short-tab-1" class="short-tab-content">
                <div class="ngv-row">
                    <div class="ngv-col-6">
                        <div class="ngv-bottom-40">
                            <h2 class="ngv-bottom-20"><?php echo __('Your current shortcode', 'ngv') ?></h2>

                            <p><?php echo __('Add this shortcode to you page or template', 'ngv') ?></p>

                            <code>
                                <?php echo $ngvSettings->validator_name; ?></code>
                        </div>
                        <form action="" method="POST">
                            <h2><?php echo __('Custom shortcode name', 'ngv') ?></h2>
                            <p><strong><?php echo __('Optional setting', 'ngv') ?></strong></p>
                            <p><?php echo __('Do not to use space, use underline otherwise the shortcode will not work.', 'ngv') ?></p>
                            <p><?php echo __('Shortcode name example: my_shortcode', 'ngv') ?></p>
                            <?php wp_nonce_field('ngv_admin_custom_shortcode_nonce', 'ngv_admin_custom_shortcode'); ?>
                            <input type="text" name="custom_shortcode" id="custom_shortcode" method="post" value="<?php echo get_option('NumbersValidator_shortcode_name'); ?>">
                            <div class="ngv-top-20">
                                <input type="submit" class="button-primary" name="custom_shortcode_submit" id="custom_shortcode_submit" value="Save">
                            </div>
                        </form>
                    </div>

                    <div class="ngv-col-6">
                        <h2 class="ngv-bottom-20"><?php echo __('Developer functions', 'ngv') ?></h2>
                        <div class="ngv-bottom-40">
                            <code> ngvValidateSerial( $serial );</code>
                            <p><strong><?php echo __('Same as the Shortcode.', 'ngv') ?></strong></p>
                        </div>
                        <div class="ngv-bottom-40">
                            <code>ngvSerialValid( $serial );</code>
                            <p><strong><?php echo __('Returns true or false.', 'ngv') ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="short-tab-2" class="short-tab-content">
                <div class="ngv-row">
                    <div class="ngv-col-6">
                        <h2 class="ngv-bottom-20"><?php echo __('Add this shortcode to you page or template', 'ngv') ?></h2>
                        <p class="ngv-bottom-40"><?php echo __('This will display a serial once then add it to', 'ngv') ?> <strong><?php echo __('used serials', 'ngv') ?></strong></p>

                        <code>
                            <?php echo $ngvSettings->serial_name; ?></code>
                    </div>
                    <div class="ngv-col-6">

                        <h2 class="ngv-bottom-20"><?php echo __('Developer functions', 'ngv') ?></h2>
                        <p class="ngv-bottom-40"><?php echo __('This function will return a serial as string and add it to', 'ngv') ?> <strong><?php echo __('used
                                    serials', 'ngv') ?></strong></p>
                        <code>ngvGenerateSerial();</code>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>