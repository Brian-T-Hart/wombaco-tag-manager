<?php
/*
Plugin Name: Wombaco Tag Manager
Description: Allows you to add a Tag Manager script to head code
Version: 1.2
Author: Brian Hart
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Define the directory of this plugin
define('WOMBACO_TM_PATH', plugin_dir_path(__FILE__));
define('WOMBACO_TM_NAME', 'WC Tag Manager');
define('WOMBACO_TM_SLUG', 'wombaco-tag-manager');
define('WOMBACO_TM_CONTAINER_ID', 'wombaco-tm-container-id');
define('WOMBACO_TM_ACTIVE', 'wombaco-tm-active');
define('WOMBACO_TM_CONTAINER_ID_VALUE', sanitize_text_field(get_option(WOMBACO_TM_CONTAINER_ID)));

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

// Add a settings page to the admin menu
function wombaco_tm_register_settings_page()
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
add_action('admin_menu', 'wombaco_tm_register_settings_page');

// Register the setting
function wombaco_tm_register_settings()
{
    register_setting('wombaco_tm_settings_group', WOMBACO_TM_CONTAINER_ID, 'sanitize_text_field');
    register_setting('wombaco_tm_settings_group', WOMBACO_TM_ACTIVE, 'sanitize_Key');
}
add_action('admin_init', 'wombaco_tm_register_settings');

// Display settings page
function wombaco_tm_settings_page()
{
?>
    <div class="wrap">
        <h1><?php echo WOMBACO_TM_NAME; ?> Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wombaco_tm_settings_group');
            do_settings_sections('wombaco_tm_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Active</th>
                    <td>
                        <input type="checkbox" name="<?php echo WOMBACO_TM_ACTIVE; ?>" value="1" <?php checked(1, get_option(WOMBACO_TM_ACTIVE), true); ?> />
                        <label for="<?php echo WOMBACO_TM_ACTIVE; ?>">Check to activate the plugin</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">GTM Container ID</th>
                    <td>
                        <input type="text" name="<?php echo WOMBACO_TM_CONTAINER_ID; ?>" value="<?php echo esc_attr(get_option(WOMBACO_TM_CONTAINER_ID)); ?>" placeholder="GTM-XXXXXX" style="width: 300px;" />
                        <label for="<?php echo WOMBACO_TM_CONTAINER_ID; ?>" style="display: block; margin-top:5px;">Enter your Tag Manager container id (GTM-XXXXXX)</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

if (get_option(WOMBACO_TM_ACTIVE) && !empty(WOMBACO_TM_CONTAINER_ID_VALUE)) {
    // Add GTM code to the head
    add_action('wp_body_open', 'wombaco_tm_add_gtm_noscript');

    // Inject GTM code in the head
    function wombaco_tm_add_to_head()
    {
        if (!empty(WOMBACO_TM_CONTAINER_ID_VALUE)) {
            echo "
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','" . esc_js(WOMBACO_TM_CONTAINER_ID_VALUE) . "');</script>
            <!-- End Google Tag Manager -->\n
            ";
        }
    }
    add_action('wp_head', 'wombaco_tm_add_to_head');

    // Add GTM noscript code to the body
    function wombaco_tm_add_gtm_noscript()
    {
        if (!empty(WOMBACO_TM_CONTAINER_ID_VALUE)) {
            echo "
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=" . esc_attr($gtm_id) . "'
            height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->\n
            ";
        }
    }
    add_action('wp_footer', 'wombaco_tm_add_gtm_noscript');
}// if active