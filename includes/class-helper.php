<?php
/**
 * Elementor test Helper Class
 *
 * PHP version 7
 *
 */

/**
 * App Helper
 *
 * @package helpers
 */
class Helper {
	/**
	 * Holds a refrence to the class instance (singleton).
	 *
	 * @var [object]
	 */
	protected static $instance = null;
	/**
	 * Instance - Start the class as static when called
	 *
	 * @method get_instance
	 * @return self Instance
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Retrieve exist cookie by name
	 * @param string $name - the unique name of the cookie
	 * @return string the value of the cookie
	 */
	private function get_cookie( $name ) {
		return isset( $_COOKIE[ $name ] ) ? htmlspecialchars( $_COOKIE[ $name ] ) : '';
	}

	public function is_user_login() {
		return $this->get_cookie( 'user' ) ? true : false;
	}
}
/**
 * Get Helper
 *
 * @return Helper Object
 */
function helper() {
	return Helper::get_instance();
}
