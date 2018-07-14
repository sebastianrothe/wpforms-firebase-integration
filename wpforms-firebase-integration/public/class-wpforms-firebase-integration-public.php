<?php

require __DIR__.'/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WpForms_Firebase_Integration
 * @subpackage WpForms_Firebase_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WpForms_Firebase_Integration
 * @subpackage WpForms_Firebase_Integration/public
 * @author     Your Name <email@example.com>
 */
class WpForms_Firebase_Integration_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private static $CONFIG_FILE = 'firebase-account.json';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->firebase = $this->initDatabase(self::$CONFIG_FILE);
	}

	private function initDatabase($configFile) {
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../config/'.$configFile);
		return $this->firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->create();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WpForms_Firebase_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WpForms_Firebase_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpforms-firebase-integration-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WpForms_Firebase_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WpForms_Firebase_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpforms-firebase-integration-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Integrate WPForms with Firebase
	 * 
	 * @param array $fields - Sanitized entry field values/properties.
	 * @param array $entry - Original $_POST global.
	 * @param array $form_data - Form settings/data.
	 * @param int $entry_id - Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
	 */
	public function send_registration_to_firebase($fields, $entry, $form_data, $entry_id) {
		if (!$fields || count($fields) < 5) {
			return;
		}

		$databaseError = null;
		[10 => $date, 9 => $name, 11 => $people, 13 => $email, 12 => $phone, 14 => $coupon] = $fields;
		$values = [
			'name' => $name['value'],
			'email' => $email['value'],
			'phone' => $phone['value'],
			'date' => $date['value'],
			'people' => $people['value'],
			'registeredAt' => date('Y-m-d H:i:s'),
			'coupon' => $coupon['value']
		];

		try {
			$db = $this->firebase->getDatabase();
			$path = 'berlin/gruseltour/registrations';
			$newRegistration = $db->getReference($path)
				->push($values);
		} catch (Exception $e) {
			$databaseError = new WP_Error( 'firebaseConnection', $e->getMessage() );
		}

		// Simple error handling
		if ( is_wp_error( $databaseError ) ) {
			$msg  = "There was an error trying to push a registration to the Firebase.\n";
			$msg .= 'Error returned: ' . $error = $databaseError->get_error_message() . "\n\n";
			$msg .= "The registration below may need to be added to the Firebase manually.\n";
			$msg .= json_encode($values);
			
			wp_mail( get_bloginfo( 'admin_email' ), 'Firebase Connector Error', $msg );
		}	
	}
}
