<?php
class Database_Gateway {
	/**
	 * Contain all the data
	 */
	private $data;
	/**
	 * array separate by new line
	 */
	private $rows;
	/**
	 * database rows
	 */
	private $row_count;

	public function __construct() {
		$this->data      = file_get_contents( 'database.txt' );
		$this->rows      = explode( "\n", $this->data );
		$this->rows      = array_map( 'trim', $this->rows );
		$this->row_count = count( $rows );

	}
	/**
	 * Retrieve the number of the columns ( data information that available )
	 * @return int columns counter
	 */
	public function get_col_counter() {
		$col = explode( '|', $this->data );
		return count( $col );
	}
	/**
	 *  Retrieve all the database
	 * @return array of all the users
	 */
	public function get_db() {
		// Creating our array database
		$data = array();
		for ( $i = 0; $i < $this->row_count - 1; $i++ ) {
			for ( $j = 0; $j < $this->get_col_counter( $this->rows[ $i ] ); $j++ ) {
				$column = explode( '|', $this->rows[ $i ] );
				$data[] = array(
					'id'           => $column[0],
					'full_name'    => $column[1],
					'username'     => $column[2],
					'password'     => $column[3], // md5
					'email'        => $column[4],
					'created_date' => $column[5],
					'update_date'  => $column[6],
				);
			}
		}
		return $data;
	}
	/**
	 * Retreive all the users
	 */
	public function get_all_users() {
		return get_db();
	}
	/**
	 * Retreive spesific User
	 * @param string $email - user email
	 * @param string $password - user password
	 */
	public function find( $email, $password ) {
		$hashed_password = md5( $password );
		foreach ( $this->get_db() as $item ) {
			if ( $item['email'] === $email && $hashed_password === $password ) {
				return $item;
			}
		}
		return false;
	}
}
