<?php
/*
Plugin Name: Wombaco Tag Manager
Description: Allows you to add a Tag Manager script to head code
Version: 1.1
Author: Brian Hart
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Define the directory of this plugin
define('WOMBACO_TM_PATH', plugin_dir_path(__FILE__));
define('WOMBACO_TM_NAME', 'WC Tag Manager');
define('WOMBACO_TM_SLUG', 'wombaco-tag-manager');
define('WOMBACO_TM_SETTING_1', 'wombaco_tm_setting_1');

if (!empty(get_option(WOMBACO_TM_SETTING_1))) {

    // Add the content to the head tags
    function wombaco_tm_add_to_head()
    {
        // Get the value from the tag_manager_setting option
        $container_id = get_option(WOMBACO_TM_SETTING_1);

        $tag = "
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','$container_id');</script>
        <!-- End Google Tag Manager -->\n
    ";

        // Output the content to the head tags
        echo $tag;
    }
    add_action('wp_head', 'wombaco_tm_add_to_head');
} // if


// Add a settings page to the admin menu
function wombaco_tm_settings()
{
    // Add the settings page to the "Settings" menu
    add_options_page(
        WOMBACO_TM_NAME,
        WOMBACO_TM_NAME,
        'manage_options',
        WOMBACO_TM_SLUG,
        'wombaco_tm_settings_page'
    );
}
add_action('admin_menu', 'wombaco_tm_settings');


// Display the settings page
function wombaco_tm_settings_page()
{
    // Check if the user is allowed to manage options
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        // Update the value of the tag_manager_setting option
        $new_setting_1 = sanitize_text_field($_POST[WOMBACO_TM_SETTING_1]);
        update_option(WOMBACO_TM_SETTING_1, $new_setting_1);

        // Display a success message
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    // Get the current value of the tag_manager_setting option
    $current_setting_1 = get_option(WOMBACO_TM_SETTING_1);

?>
    <div class="wrap">
        <h1><?php echo WOMBACO_TM_NAME; ?> Settings</h1>
        <br>
        <form method="post">
            <label for="<?php echo WOMBACO_TM_SETTING_1; ?>" style="display:block;font-size: 1.5em;font-weight:600;margin-bottom: 5px;">Container ID</label>
            <input type="text" name="<?php echo WOMBACO_TM_SETTING_1; ?>" id="<?php echo WOMBACO_TM_SETTING_1; ?>" value="<?php echo esc_attr($current_setting_1); ?>" class="regular-text">
            <p style="margin-top:5px;">Enter your Tag Manager container id (GTM-XXXXXX)</p>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Add Settings link to plugin
function wombaco_tm_add_settings_link($links)
{
    $settings_link = sprintf(
        '<a href="%1$s" %2$s>%3$s</a>',
        admin_url('options-general.php?page=' . WOMBACO_TM_SLUG),
        'aria-label="' . __('Settings for ' . WOMBACO_TM_NAME, WOMBACO_TM_SLUG) . '"',
        __('Settings', WOMBACO_TM_SLUG),
    );

    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wombaco_tm_add_settings_link');
