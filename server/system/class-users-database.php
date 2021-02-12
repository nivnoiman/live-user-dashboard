<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Users_Database extends Database_Gateway {
	/**
	 * Register the db name
	 */
	public function __construct() {
		parent::__construct( 'users' );
	}
	/**
	 * Retrieve the db schema
	 * @return array of the db schema
	 */
	protected function get_db_schema() {
		return array( 'id', 'full_name', 'username', 'password', 'email', 'created_time', 'update_time', 'login_time', 'last_ping', 'ip', 'agent', 'login_counter' );
	}
}
