<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           WpForms_Firebase_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       WPForms Firebase Integration
 * Plugin URI:        https://github,com/sebastianrothe
 * Description:       Everytime a form is submitted with WPForms, the data is also sent to a firebase datastore.
 * Version:           1.0.0
 * Author:            Mysterium Tremendum GbR
 * Author URI:        https://gruseltour-leipzig.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpforms-firebase-integration
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
define( 'WPFORMS_FIREBASE_INTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpforms-firebase-integration-activator.php
 */
function activate_wpforms_firebase_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpforms-firebase-integration-activator.php';
	WpForms_Firebase_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpforms-firebase-integration-deactivator.php
 */
function deactivate_wpforms_firebase_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpforms-firebase-integration-deactivator.php';
	WpForms_Firebase_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpforms_firebase_integration' );
register_deactivation_hook( __FILE__, 'deactivate_wpforms_firebase_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpforms-firebase-integration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpforms_firebase_integration() {

	$plugin = new WpForms_Firebase_Integration();
	$plugin->run();

}
run_wpforms_firebase_integration();
