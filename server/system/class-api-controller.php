<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Api_Controller {
	/**
	 * Database Gateway
	 */
	private $db;
	/**
	 * An associative array containing any of the various components of the URL
	 */
	private $uri;
	/**
	 * Api Requested query ( Api Params )
	 */
	private $query;
	/**
	 * Api Request Method - allow GET & PATCH
	 */
	private $request_method;

	public function __construct() {
		$this->db             = array( 'users' => new Users_Database() );
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->uri            = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

		parse_str( $_SERVER['QUERY_STRING'], $this->query );

		$uri = explode( '/', $this->uri );

		if ( 'server' !== $uri[1] || empty( $this->query ) ) {
			$this->send_response_json(
				array(
					'success' => false,
					'msg'     => 'Ops... something wrong',
				),
				404
			);
		}
	}
	/**
	 * Send header for the rqeust page
	 * @param int $status - response status (200|404) ( 200 default )
	 */
	private function send_header( $status ) {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Content-Type: application/json; charset=UTF-8' );
		header( 'Access-Control-Allow-Methods: GET,PATCH' );
		header( 'Access-Control-Max-Age: 3600' );
		header( 'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With' );
		header( 'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With' );

		if ( 200 === $status ) {
			header( 'HTTP/1.1 200 OK' );
		} else {
			header( 'HTTP/1.1 404 Not Found' );
		}

	}
	/**
	 * Send json response to the requested page
	 * - Including header
	 */
	private function send_response_json( $data, $status = 200 ) {
		$this->send_header( $status );
		$response = array(
			'success' => isset( $data['success'] ) ? $data['success'] : 200 === $status ? true : false,
			'data'    => $data,
		);
		echo json_encode( $response );
		exit();
	}
	/**
	 * Retreive the requested service
	 * @return string service to perform
	 */
	private function request_service() {
		return array_keys( $this->query )[0];
	}
	/**
	 * Proccess the request
	 */
	public function process_request() {
		if ( $this->is_allow_mothod_functions() ) {
			$response = call_user_func( array( $this, $this->request_service() ) );
			if ( $response ) {
				$this->send_response_json( $response );
			} else {
				$this->send_not_found_response( 200, array( 'success' => false ) );
			}
		}
		$this->send_not_found_response();
	}
	/**
	 * Check if the current request is one of the allow function by method
	 * @return boolean true if yes false if not
	 */
	private function is_allow_mothod_functions() {

		if ( ! method_exists( $this, $this->request_service() ) ) {
			return false;
		}

		$allow_funcs = array();
		switch ( $this->request_method ) {
			case 'GET':
				$allow_funcs = array( 'user_login', 'get_all_users', 'ping_online_user' );
				break;
			case 'PATCH':
				$allow_funcs = array( 'ping_online_user' );
				break;
		}
		return in_array( $this->request_service(), $allow_funcs, true );
	}
	/**
	 * Service for retrieve all the users ( remove token and password )
	 * @return array of all the users
	 */
	private function get_all_users() {
		return array_map(
			function( $item ) {
				return $this->prepared_user( $item );
			},
			$this->db['users']->get_db()
		);
	}
	/**
	 * Service for retrieve spesifice user ( for login )
	 * @return mixed (boolean|array) array of user info or false on failure
	 */
	private function user_login() {
		$password     = isset( $this->query['password'] ) ? $this->query['password'] : '';
		$email        = isset( $this->query['email'] ) ? $this->query['email'] : '';
		$current_time = time();

		$result = $this->db['users']->update(
			array( 'ip', 'last_ping', 'login_time', 'login_counter', 'agent' ),
			array( $this->get_client_ip(), $current_time, $current_time, '++', $this->get_client_agent() ),
			array(
				'email'    => $email,
				'password' => md5( $password ),
			),
			'array' // for retrieve the data as array
		);
		if ( ! $result ) {
			return false;
		}
		$user = $this->prepared_user( $result );
		unset( $user['agent'] );

		return array_merge(
			$user,
			array( 'token' => $this->generate_login_token( $email, $password ) ),
		);
	}
	/**
	 * Service for update spesifice user online datetime and retrieve the list online users
	 * @return mixed (boolean|array) array of online users info or false on failure
	 */
	private function ping_online_user() {
		$token = isset( $this->query['token'] ) ? $this->query['token'] : '';

		list( $email, $password ) = $this->decoe_login_token( $token );

		$online_users = array();

		$result = $this->db['users']->update(
			'last_ping',
			time(),
			array(
				'email'    => $email,
				'password' => md5( $password ),
			)
		);
		if ( ! $result ) {
			return false;
		}
		foreach ( $this->db['users']->get_db() as $user ) {
			if ( $user['last_ping'] >= strtotime( '-30 seconds' ) ) {
				$online_users[] = $this->prepared_user( $user );
			}
		}
		return $online_users;
	}
	/**
	 * Create token for update user
	 * @param string $email login email
	 * @param string $password login password
	 * @return string token
	 */
	private function generate_login_token( $email, $password ) {
		return base64_encode( $email . 'NivNoiman' . $password );
	}
	/**
	 * Decode token for update user
	 * @param string $token
	 * @return array email and password
	 */
	private function decoe_login_token( $token ) {
		$decode_token = base64_decode( $token );
		return explode( 'NivNoiman', $decode_token );
	}
	/**
	 * create error api
	 * @param int $status the status of the respoonse - default 404 ( not found )
	 * @param array $data extra data to the response
	 */
	private function send_not_found_response( $status = 404, $data = array() ) {
		$this->send_response_json(
			array_merge(
				$data,
				array( 'msg' => 'Not Found' )
			),
			$status
		);
	}
	/**
	 * Retrieve the ip of the client
	 * @return string - ip
	 */
	private function get_client_ip() {
		$ip = ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
		// fix localhost ip version 6 ::1 to 127.0.0.1
		if ( '::1' === $ip ) {
			$ip = '127.0.0.1';
		}
		return $ip;
	}
	/**
	 * Retrieve the agent of the client
	 * @return string - client's agent
	 */
	private function get_client_agent() {
		return str_replace( '|', '-', $_SERVER['HTTP_USER_AGENT'] );
	}
	/**
	 * Preapre user item
	 * - Change the unixtime to date format
	 * @param array $user - user info ( from db )
	 * @param boolean $protect - remove the password param ( default is true )
	 * @return array prepared user
	 */
	private function prepared_user( $user, $protect = true ) {
		if ( $protect ) {
			unset( $user['password'] );
		}
		$format = 'd/m/Y H:i:s';

		$user['last_ping']    = gmdate( $format, $user['last_ping'] );
		$user['login_time']   = gmdate( $format, $user['login_time'] );
		$user['update_time']  = gmdate( $format, $user['update_time'] );
		$user['created_time'] = gmdate( $format, $user['created_time'] );
		return $user;
	}
}
