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
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/admin
 * @author NeosLab <support@neoslab.com>
*/
class Simple_HTTPS_Admin
{
	/**
	 * The ID of this plugin
	 * @since 1.0.0
	 * @access private
	 * @var string $pluginName the ID of this plugin
	*/
	private $pluginName;

	/**
	 * The version of this plugin
	 * @since 1.0.0
	 * @access private
	 * @var string $version the current version of this plugin
	*/
	private $version;

	/**
	 * Initialize the class and set its properties
	 * @since 1.0.0
	 * @param string $pluginName the name of this plugin
	 * @param string $version the version of this plugin
	*/
	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area
	 * @since 1.0.0
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
	 * @since 1.0.0
	*/
	public function enqueue_scripts()
	{
		wp_register_script($this->pluginName.'-script', plugin_dir_url(__FILE__).'assets/javascripts/simple-https-admin.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->pluginName.'-script');
	}

	/**
	 * Return the header
	*/
	public function return_plugin_header()
	{
		$html = '<div class="wpdx-header"><span class="header-icon"><i class="fas fa-sliders-h"></i></span> <span class="header-text">'.__('Simple HTTPS', 'simple-https').'</span></div>';
		return $html;
	}

	/**
	 * Return the tabs menu
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
	 * Update `Options` on form submit
	*/
	public function return_update_options()
	{
		if((isset($_POST['shs-update-option'])) && ($_POST['shs-update-option'] === 'true')
		&& check_admin_referer('shs-referer-form', 'shs-referer-option'))
		{
			$opts = array('ssl' => 'off', 'sts' => 'off');
			$filepath = ABSPATH."/.htaccess";
			if(isset($_POST['_simple_https']['ssl']))
			{
				$opts['ssl'] = 'on';
				if(file_exists($filepath))
				{
					$filetext = file_get_contents($filepath);
					$htaccess = str_replace('RewriteEngine On', $this->return_htaccess_string(), $filetext);
					$writefile = fopen($filepath, "w") or die("Unable to open file!");
					fwrite($writefile, $htaccess);
					fclose($writefile);
				}
			}
			else
			{
				if(file_exists($filepath))
				{
					$filetext = file_get_contents($filepath);
					$findtext = $this->return_htaccess_string();
					$htaccess = str_replace($findtext, 'RewriteEngine On', $filetext);
					$writefile = fopen($filepath, "w") or die("Unable to open file!");
					fwrite($writefile, $htaccess);
					fclose($writefile);
				}
			}

			if(isset($_POST['_simple_https']['sts']))
			{
				$opts['sts'] = 'on';
			}

			$data = update_option('_simple_https', $opts);
			header('location:'.admin_url('options-general.php?page=simple-https-admin').'&output=updated');
			die();
		}
	}

	/**
	 * Return the `Options` page
	*/
	public function return_options_page()
	{
		$opts = get_option('_simple_https');
		require_once plugin_dir_path(__FILE__).'partials/simple-https-admin-options.php';
	}

	/**
	 * Return Backend Menu
	*/
	public function return_admin_menu()
	{
		add_options_page('Simple HTTPS', 'Simple HTTPS', 'manage_options', 'simple-https-admin', array($this, 'return_options_page'));
		remove_submenu_page('options-general.php', 'simple-https-about');
	}
}

?>