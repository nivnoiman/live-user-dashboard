<?php
class Api_Contrller {

	private $db;
	private $uri;
	private $query;
	private $request_method;

	public function __construct() {
		$this->db_gateway     = new Database_Gateway();
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->uri            = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$this->query          = parse_str( $this->uri );

		$uri = explode( '/', $uri );

		if ( 'api!' !== $uri[1] ) {
			header( 'HTTP/1.1 404 Not Found' );
			exit();
		}

	}
	/**
	 * Proccess the request
	 */
	public function process_request() {

		switch ( $this->request_method ) {
			case 'GET':
				if ( $request_data && isset( $this->query['email'] ) && isset( $this->query['password'] ) ) {
					$response = $this->get_user( $this->query['email'], $this->query['password'] );
				} else {
					$response = $this->get_all_users();
				};
				break;
			default:
				$response = $this->not_found_response();
				break;
		}
		header( $response['status_code_header'] );
		if ( $response['body'] ) {
			echo $response['body'];
		}
	}
	/**
	 * Service for retrieve all the users
	 * @return array - response ( header and body )
	 */
	private function get_all_users() {
		$result                         = $this->db->find_all();
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body']               = json_encode( $result );
		return $response;
	}
	/**
	 * Service for retrieve spesifice user ( for login )
	 * @return array - response ( header and body )
	 */
	private function get_user( $email, $password ) {
		$result = $this->db->find( $email, $password );
		if ( ! $result ) {
			return $this->not_found_response();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body']               = json_encode( $result );
		return $response;
	}
	/**
	 * create error api
	 * @return array - response ( header and body )
	 */
	private function not_found_response() {
		$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
		$response['body']               = null;
		return $response;
	}
}
