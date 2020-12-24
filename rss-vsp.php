<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://itjustdoes.co.uk
 * @since             1.0.0
 * @package           Rss_Vsp
 *
 * @wordpress-plugin
 * Plugin Name:       rss-vsp
 * Plugin URI:        https://github.com/iamrssweb/rss-vsp
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            It Just Does
 * Author URI:        http://itjustdoes.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rss-vsp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RSS_VSP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rss-vsp-activator.php
 */
function activate_rss_vsp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rss-vsp-activator.php';
	Rss_Vsp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rss-vsp-deactivator.php
 */
function deactivate_rss_vsp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rss-vsp-deactivator.php';
	Rss_Vsp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rss_vsp' );
register_deactivation_hook( __FILE__, 'deactivate_rss_vsp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rss-vsp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rss_vsp() {

	$plugin = new Rss_Vsp();
	$plugin->run();

}
run_rss_vsp();
