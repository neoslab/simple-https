<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link https://neoslab.com
 * @since 1.0.0
 * @package Simple_HTTPS
 *
 * @wordpress-plugin
 * Plugin Name: Simple HTTPS
 * Plugin URI: https://wordpress.org/plugins/simple-https/
 * Description: Correct your SSL/HTTPS issue within few clicks and enable HTTP Strict Transport Security for your website.
 * Version: 2.3.2
 * Author: NeosLab
 * Author URI: https://neoslab.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: simple-https
 * Domain Path: /languages
*/

/**
 * If this file is called directly, then abort
*/
if(!defined('WPINC'))
{
	die;
}

/**
 * Currently plugin version
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions
*/
define('SIMPLE_HTTPS_VERSION', '2.3.2');

/**
 * The code that runs during plugin activation
 * This action is documented in includes/class-simple-https-activator.php
*/
function activate_simple_https()
{
	require_once plugin_dir_path(__FILE__).'includes/class-simple-https-activator.php';
	Simple_HTTPS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation
 * This action is documented in includes/class-simple-https-deactivator.php
*/
function deactivate_simple_https()
{
	require_once plugin_dir_path(__FILE__).'includes/class-simple-https-deactivator.php';
	Simple_HTTPS_Deactivator::deactivate();
}

/**
 * Activation/deactivation hook
*/
register_activation_hook(__FILE__, 'activate_simple_https');
register_deactivation_hook(__FILE__, 'deactivate_simple_https');

/**
 * The core plugin class that is used to define internationalization
 * admin-specific hooks, and public-facing site hooks
*/
require plugin_dir_path(__FILE__).'includes/class-simple-https-core.php';

/**
 * Begins execution of the plugin
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle
 * @since 1.0.0
*/
function run_simple_https()
{
	$plugin = new Simple_HTTPS();
	$plugin->run();
}

/**
 * Run plugin
*/
run_simple_https();

?>