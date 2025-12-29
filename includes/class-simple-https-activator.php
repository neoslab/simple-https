<?php
/**
 * Fired during plugin activation
 *
 * @link https://neoslab.com
 * @since 1.0.0
 *
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
*/

/**
 * Class `Simple_HTTPS_Activator`
 * This class defines all code necessary to run during the plugin's activation
 * @since 1.0.0
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
 * @author NeosLab <support@neoslab.com>
*/
class Simple_HTTPS_Activator
{
	/**
	 * Activate plugin
	 * @since 1.0.0
	*/
	public static function activate()
	{
		$option = add_option('_simple_https', false);
	}
}

?>