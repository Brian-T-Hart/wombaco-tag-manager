# **Wombaco Tag Manager Plugin**

This is a WordPress plugin that adds Google Tag Manager container code to the head of your website. The plugin allows you to easily add your Tag Manager container ID without having to manually edit your theme files.

## Installation

1. Download the `wombaco-tag-manager.zip` file.
2. Login to your WordPress Dashboard and go to **Plugins > Add New > Upload Plugin**.
3. Choose the `wombaco-tag-manager.zip` file and click **Install Now**.
4. Once the plugin is installed, click **Activate Plugin**.

## Usage

1. Go to **Settings > Wombaco Tag Manager** in the WordPress dashboard.
2. Enter your Tag Manager container ID (GTM-XXXXXX) in the field provided.
3. Click **Save Changes**.
4. The Tag Manager container code will now be added to the head of your website.

## Development

To modify the plugin, you can edit the `wombaco-tag-manager.php` file. The plugin uses WordPress actions to add the Tag Manager code to the head of your website and to add a settings page to the WordPress dashboard.

The plugin also defines four constants:

- `WOMBACO_TM_PATH`: the directory path of the plugin.
- `WOMBACO_TM_NAME`: the name of the plugin.
- `WOMBACO_TM_SLUG`: the slug of the plugin
- `WOMBACO_TM_SETTING_1`: the option name used to store the Tag Manager container ID in the WordPress database.
