<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link https://neoslab.com
 * @since 1.0.0
 *
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
*/

/**
 * class `Simple_HTTPS`
 * @since 1.0.0
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
 * @author NeosLab <support@neoslab.com>
*/
class Simple_HTTPS
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin
	 * @since 1.0.0
	 * @access protected
	 * @var Simple_HTTPS_Loader $loader maintains and registers all hooks for the plugin
	*/
	protected $loader;

	/**
	 * The unique identifier of this plugin
	 * @since 1.0.0
	 * @access protected
	 * @var string $pluginName the string used to uniquely identify this plugin
	*/
	protected $pluginName;

	/**
	 * The current version of the plugin
	 * @since 1.0.0
	 * @access protected
	 * @var string $version the current version of the plugin
	*/
	protected $version;

	/**
	 * Define the core functionality of the plugin
	 * @since 1.0.0
	*/
	public function __construct()
	{
		if(defined('SIMPLE_HTTPS_VERSION'))
		{
			$this->version = SIMPLE_HTTPS_VERSION;
		}
		else
		{
			$this->version = '1.0.0';
		}

		$this->pluginName = 'simple-https';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin
	 * @since 1.0.0
	 * @access private
	*/
	private function load_dependencies()
	{
		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin
		*/
		require_once plugin_dir_path(dirname(__FILE__)).'includes/class-simple-https-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin
		*/
		require_once plugin_dir_path(dirname(__FILE__)).'includes/class-simple-https-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area
		*/
		require_once plugin_dir_path(dirname(__FILE__)).'admin/class-simple-https-admin.php';

		/**
		 * Loader
		*/
		$this->loader = new Simple_HTTPS_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization
	 * @since 1.0.0
	 * @access private
	*/
	private function set_locale()
	{
		$pluginI18n = new Simple_HTTPS_i18n();
		$this->loader->add_action('plugins_loaded', $pluginI18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin
	 * @since 1.0.0
	 * @access private
	*/
	private function define_admin_hooks()
	{
		$pluginAdmin = new Simple_HTTPS_Admin($this->get_pluginName(), $this->get_version());
		$allowed = array('simple-https-admin');
		if((isset($_GET['page'])) && (in_array($_GET['page'], $allowed)))
		{
			$this->loader->add_action('admin_enqueue_scripts', $pluginAdmin, 'enqueue_styles');
			$this->loader->add_action('admin_enqueue_scripts', $pluginAdmin, 'enqueue_scripts');
		}

		$this->loader->add_action('admin_menu', $pluginAdmin, 'return_admin_menu');
		$this->loader->add_action('init', $pluginAdmin, 'return_update_options');

		if(!is_admin())
		{
			$this->loader->add_action('send_headers', $pluginAdmin, 'return_header_sts');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress
	 * @since 1.0.0
	*/
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 * @since 1.0.0
	 * @return string the name of the plugin
	*/
	public function get_pluginName()
	{
		return $this->pluginName;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin
	 * @since 1.0.0
	 * @return Simple_HTTPS_Loader orchestrates the hooks of the plugin
	*/
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin
	 * @since 1.0.0
	 * @return string the version number of the plugin
	*/
	public function get_version()
	{
		return $this->version;
	}
}

?>