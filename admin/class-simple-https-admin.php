<?php
/**
 * The admin-specific functionality of the plugin
 *
 * @link https://neoslab.com
 * @since 1.0.0
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/admin
*/

/**
 * Class `Simple_HTTPS_Admin`
 * Manages all admin-specific functionality for the Simple HTTPS plugin.
 * Handles enqueuing stylesheets and scripts for the WordPress admin area.
 * Manages .htaccess modifications for HTTPS redirection and security features.
 * Implements plugin settings page and HTTP security headers management.
 * 
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/admin
 * @author NeosLab <support@neoslab.com>
*/
class Simple_HTTPS_Admin
{
	/**
	 * The ID of this plugin
	 * Stores the unique identifier for this plugin throughout the admin area.
	 * Used for hooking WordPress actions, filters, and other plugin functions.
	 * Essential for enqueuing assets and identifying the plugin in WordPress.
	 * Maintains consistent identification across all admin operations.
	 * 
	 * @since 1.0.0
	 * @access private
	 * @var string $pluginName The ID of this plugin
	*/
	private $pluginName;

	/**
	 * The version of this plugin
	 * Stores the current version number for proper asset versioning and caching.
	 * Used for enqueuing stylesheets and scripts with cache busting capabilities.
	 * Helps maintain compatibility across different WordPress installations.
	 * Ensures users receive updated assets when plugin is upgraded.
	 * 
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin
	*/
	private $version;

	/**
	 * Initialize the class and set its properties
	 * Constructor method that initializes the admin handler for the plugin.
	 * Stores the plugin name and version for use throughout the class.
	 * Sets up the basic properties needed for WordPress integration.
	 * Prepares the class for proper admin area functionality.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param string $pluginName The name of this plugin used for identification
	 * @param string $version The version of this plugin for asset management
	 * @return void No return value
	*/
	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area
	 * Handles registration and enqueuing of all CSS styles for admin interface.
	 * Loads Font Awesome icons for enhanced visual elements and navigation.
	 * Enqueues the main dashboard stylesheet for plugin layout and design.
	 * Ensures proper styling across all plugin admin pages consistently.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void No return value, outputs CSS links in admin head
	*/
	public function enqueue_styles()
	{
		wp_register_style($this->pluginName.'-fontawesome', plugin_dir_url(__FILE__).'assets/fonts/fontawesome/css/all.min.css', array(), $this->version, 'all');
		wp_register_style($this->pluginName.'-dashboard', plugin_dir_url(__FILE__).'assets/styles/simple-https-admin.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->pluginName.'-fontawesome');
		wp_enqueue_style($this->pluginName.'-dashboard');
	}

	/**
	 * Register the JavaScript for the admin area
	 * Manages the registration and enqueuing of JavaScript files for admin.
	 * Loads the minified JavaScript file for optimal performance and speed.
	 * Handles client-side interactions and form submissions on settings page.
	 * Depends on jQuery for proper DOM manipulation and event handling.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void No return value, outputs JavaScript in admin footer
	*/
	public function enqueue_scripts()
	{
		wp_register_script($this->pluginName.'-script', plugin_dir_url(__FILE__).'assets/javascripts/simple-https-admin.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->pluginName.'-script');
	}

	/**
	 * Return the header
	 * Generates the HTML header markup for the plugin's admin pages.
	 * Displays the plugin name alongside a settings icon for branding.
	 * Uses internationalization functions for multilingual text support.
	 * Provides consistent header appearance across all admin interfaces.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return string HTML markup for plugin header with icon and text
	*/
	public function return_plugin_header()
	{
		$html = '<div class="wpdx-header"><span class="header-icon"><i class="fas fa-sliders-h"></i></span> <span class="header-text">'.__('Simple HTTPS', 'simple-https').'</span></div>';
		return $html;
	}

	/**
	 * Return the tabs menu
	 * Generates navigation tabs for the plugin's settings page interface.
	 * Allows users to switch between different configuration sections easily.
	 * Highlights the active tab based on current page parameter selection.
	 * Uses Font Awesome icons for enhanced visual navigation experience.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param string $tab The currently active tab identifier to highlight
	 * @return void Outputs HTML navigation menu directly
	*/
	public function return_tabs_menu($tab)
	{
		$link = admin_url('options-general.php');
		$list = array
		(
			array('tab1', 'simple-https-admin', 'fa-cogs', __('Settings', 'simple-https'))
		);

		$menu = null;
		foreach($list as $item => $value)
		{
			$html = array('div' => array('class' => array()), 'a' => array('href' => array()), 'i' => array('class' => array()), 'p' => array(), 'span' => array());
			$menu ='<div class="tab-label '.$value[0].' '.(($tab === $value[0]) ? 'active' : '').'"><a href="'.$link.'?page='.$value[1].'"><p><i class="fas '.$value[2].'"></i><span>'.$value[3].'</span></p></a></div>';
			echo wp_kses($menu, $html);
		}
	}

	/**
	 * Return .htaccess string
	 * Generates Apache .htaccess configuration rules for HTTPS enforcement.
	 * Creates rewrite rules that redirect all HTTP traffic to HTTPS protocol.
	 * Adds security headers for Content-Security-Policy implementation.
	 * Returns properly formatted string with correct PHP_EOL line endings.
	 * 
	 * @since 1.0.0
	 * @access private
	 * @return string Formatted .htaccess rules with proper line endings
	*/
	private function return_htaccess_string()
	{
		$string = 'RewriteEngine On'.PHP_EOL;
		$string.= 'RewriteCond %{HTTPS} !=on'.PHP_EOL;
		$string.= 'RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]'.PHP_EOL;
		$string.= 'Header always set Content-Security-Policy "upgrade-insecure-requests;"';
		return $string;
	}

	/**
	 * Return header strict transport security
	 * Implements HTTP Strict Transport Security (HSTS) headers for security.
	 * Reads saved plugin options to determine appropriate HSTS policy status.
	 * Sets max-age=600 for enabling or max-age=0 for immediate expiration.
	 * Forces browsers to always use HTTPS connections when policy is active.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void Sets HTTP headers or does nothing if STS is not configured
	*/
	public function return_header_sts()
	{
		$simple_https = get_option('_simple_https');
		if((isset($simple_https['sts'])) && ($simple_https['sts'] === 'on'))
		{
			header("strict-transport-security: max-age=600");
		}
		elseif((isset($simple_https['sts'])) && ($simple_https['sts'] === 'off'))
		{
			header("strict-transport-security: max-age=0");
		}
	}

	/**
	 * Remove empty lines from a string
	 * Cleans up content by removing all lines that are completely empty.
	 * Splits input string by line breaks into an array for processing.
	 * Filters out lines containing only whitespace or no characters at all.
	 * Rebuilds content by imploding remaining lines with proper line endings.
	 * 
	 * @since 1.0.0
	 * @access private
	 * @param string $content The content string to clean and remove empty lines from
	 * @return string Cleaned content with all empty lines removed
	*/
	private function remove_empty_lines($content)
	{
		$lines = explode(PHP_EOL, $content);
		$non_empty_lines = array_filter($lines, function($line)
		{
			return trim($line) !== '';
		});
		
		return implode(PHP_EOL, $non_empty_lines);
	}

	/**
	 * Update `Options` on form submit
	 * Processes form submissions for plugin settings with security validation.
	 * Handles SSL option enabling or disabling with .htaccess file modifications.
	 * Manages STS option updates for HTTP Strict Transport Security headers.
	 * Removes empty lines from .htaccess file before saving changes to disk.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void Redirects to settings page or exits after processing
	*/
	public function return_update_options()
	{
		if((isset($_POST['shs-update-option'])) && ($_POST['shs-update-option'] === 'true') 
		&& check_admin_referer('shs-referer-form', 'shs-referer-option'))
		{
			$opts = array('ssl' => 'off', 'sts' => 'off');
			$filepath = ABSPATH . '/.htaccess';

			if(isset($_POST['_simple_https']['ssl']))
			{
				$opts['ssl'] = 'on';
				if(file_exists($filepath) && is_writable($filepath))
				{
					$filetext = file_get_contents($filepath);
					$htaccess = str_replace('RewriteEngine On', $this->return_htaccess_string(), $filetext);
					$htaccess = $this->remove_empty_lines($htaccess);

					$writefile = fopen($filepath, 'w');
					if($writefile)
					{
						fwrite($writefile, $htaccess);
						fclose($writefile);
						chmod($filepath, 0444);
					}
				}
			}
			else
			{
				if(file_exists($filepath) && is_writable($filepath))
				{
					$filetext = file_get_contents($filepath);
					$findtext = $this->return_htaccess_string();
					$htaccess = str_replace($findtext, 'RewriteEngine On', $filetext);
					$htaccess = $this->remove_empty_lines($htaccess);

					$writefile = fopen($filepath, 'w');
					if($writefile)
					{
						fwrite($writefile, $htaccess);
						fclose($writefile);
						chmod($filepath, 0444);
					}
				}
			}

			if(isset($_POST['_simple_https']['sts']))
			{
				$opts['sts'] = 'on';
			}

			update_option('_simple_https', $opts);
			wp_redirect(admin_url('options-general.php?page=simple-https-admin&output=updated'));
			exit;
		}
	}

	/**
	 * Return the `Options` page
	 * Loads and displays the plugin's main options page template for administrators.
	 * Retrieves saved plugin options from WordPress database for current settings.
	 * Includes the partial template file that renders the settings interface.
	 * Provides the visual interface for managing HTTPS and security options.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void Includes and renders the options page template
	*/
	public function return_options_page()
	{
		$opts = get_option('_simple_https');
		require_once plugin_dir_path(__FILE__).'partials/simple-https-admin-options.php';
	}

	/**
	 * Return Backend Menu
	 * Registers the plugin's admin menu items in WordPress dashboard interface.
	 * Adds a settings page under the Settings menu for easy administrator access.
	 * Removes unnecessary submenu pages created automatically during registration.
	 * Sets proper capability requirements for accessing plugin settings page.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return void Adds menu items to WordPress admin interface
	*/
	public function return_admin_menu()
	{
		add_options_page('Simple HTTPS', 'Simple HTTPS', 'manage_options', 'simple-https-admin', array($this, 'return_options_page'));
		remove_submenu_page('options-general.php', 'simple-https-about');
	}
}

?>