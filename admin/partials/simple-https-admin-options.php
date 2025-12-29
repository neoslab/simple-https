<?php
if((isset($_GET['output'])) && ($_GET['output'] === 'updated'))
{
    $notice = array('success', __('Your settings have been successfully updated.', 'simple-https'));
}
?>
<div class="wrap">
    <section class="wpdx-wrapper">
        <div class="wpdx-container">
            <div class="wpdx-tabs">
                <?php echo $this->return_plugin_header(); ?>
                <main class="tabs-main">
                    <?php echo $this->return_tabs_menu('tab1'); ?>
                    <section class="tab-section">
                        <?php if(empty(get_option('permalink_structure'))) { ?>
                        <div class="wpdx-notice wrong">
                            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <span><?php echo _e('Your current <b>Permalink Settings</b> structure is set to <b>Plain</b>. In order force the SSL certificate, you must choice another <a href="'.get_admin_url().'options-permalink.php">permalink format</a> here.', 'simple-https'); ?></span>
                        </div>
                        <?php } ?>
                        <?php if(isset($notice)) { ?>
                        <div class="wpdx-notice <?php echo esc_attr($notice[0]); ?>">
                            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <span><?php echo esc_attr($notice[1]); ?></span>
                        </div>
                        <?php } ?>
                        <form method="POST">
                            <input type="hidden" name="shs-update-option" value="true" />
                            <?php wp_nonce_field('shs-referer-form', 'shs-referer-option'); ?>
                            <div class="wpdx-form">
                                <div class="field">
                                    <?php $fieldID = uniqid(); ?>
                                    <span class="label"><?php echo _e('Force HTTPS', 'simple-https'); ?></span>
                                    <div class="onoffswitch">
                                        <input id="<?php echo esc_attr($fieldID); ?>" type="checkbox" name="_simple_https[ssl]" class="onoffswitch-checkbox" <?php if((isset($opts['ssl'])) && ($opts['ssl'] == 'on')) { echo 'checked="checked"';} ?>/>
                                        <label class="onoffswitch-label" for="<?php echo esc_attr($fieldID); ?>">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                    <small><?php echo _e('Do you want to force SSL redirection for this website ?', 'simple-https'); ?></small>
                                </div>
                                <div class="field">
                                    <?php $fieldID = uniqid(); ?>
                                    <span class="label"><?php echo _e('Strict Transport Security', 'simple-https'); ?></span>
                                    <div class="onoffswitch">
                                        <input id="<?php echo esc_attr($fieldID); ?>" type="checkbox" name="_simple_https[sts]" class="onoffswitch-checkbox" <?php if((isset($opts['sts'])) && ($opts['sts'] == 'on')) { echo 'checked="checked"';} ?>/>
                                        <label class="onoffswitch-label" for="<?php echo esc_attr($fieldID); ?>">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                    <small><?php echo _e('Enable HTTP Strict Transport Security for this website ?', 'simple-https'); ?></small>
                                </div>
                                <div class="form-footer">
                                    <input type="submit" class="button button-primary button-theme" style="height:45px;" value="<?php _e('Update Settings', 'simple-https'); ?>">
                                </div>
                            </div>
                        </form>
                    </section>
                </main>
            </div>
        </div>
    </section>
</div>