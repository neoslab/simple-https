<?php
/**
 * Fired during plugin deactivation
 *
 * @link https://neoslab.com
 * @since 1.0.0
 *
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
*/

/**
 * Class `Simple_HTTPS_Deactivator`
 * This class defines all code necessary to run during the plugin's deactivation
 * @since 1.0.0
 * @package Simple_HTTPS
 * @subpackage Simple_HTTPS/includes
 * @author NeosLab <support@neoslab.com>
*/
class Simple_HTTPS_Deactivator
{
	/**
	 * Deactivate plugin
	 * @since 1.0.0
	*/
	public static function deactivate()
	{
		$option = delete_option('_simple_https');
	}
}

?>