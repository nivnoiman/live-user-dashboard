<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Database_Gateway {
	/**
	 * Contain all the data
	 */
	protected $raw_data;
	/**
	 * array separate by new line
	 */
	protected $rows;
	/**
	 * database rows
	 */
	protected $row_count;
	/**
	 * Database name
	 */
	protected $db_name;
	/**
	 * Database ABSPATH
	 */
	protected $db_path;
	/**
	 * Assign db name to the current instance
	 */
	public function __construct( $db_name ) {
		$this->db_name = $db_name;
		$this->db_path = ABSPATH . '/database/' . $this->db_name . '.txt';
		$this->read_data();
	}
	/**
	 * Read the file database and save to the "raw_data" prop
	 * # Use this for refresh the data instance
	 */
	public function read_data() {
		$this->raw_data  = file_get_contents( $this->db_path );
		$this->rows      = explode( "\n", $this->raw_data );
		$this->rows      = array_map( 'trim', $this->rows );
		$this->row_count = count( $this->rows );
	}
	/**
	 * Retrieve the number of the columns ( data information that available )
	 * @return int columns counter
	 */
	public function get_col_counter() {
		$col = explode( '|', $this->raw_data );
		return count( $col );
	}
	/**
	 * Retrieve the db schema
	 * @return array of the db schema
	 */
	protected function get_db_schema() {
		return array( 'id' );
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
				foreach ( $this->get_db_schema() as $column_index => $key ) {
					$data[ $i ][ $key ] = $column[ $column_index ];
				}
			}
		}
		return $data;
	}
	/**
	 * Retreive all the data
	 * @return array - all the db
	 */
	public function get_all() {
		return get_db();
	}
	/**
	 * Retreive spesific row index ( item )
	 * @param array search - meta to search for the spesific item
	 * @return int index of the first item data that match the search | -1 if not found
	 */
	protected function find_index( $search ) {
		foreach ( $this->get_db() as $index => $item ) {
			$search_matches = 0;
			foreach ( $search as $key => $value ) {
				if ( $item[ $key ] == $value ) {
					$search_matches ++;
				}
			}
			if ( count( $search ) === $search_matches ) {
				return $index;
			}
		}
		return -1;
	}
	/**
	 * Retreive spesific row ( item )
	 * @param array search - meta to search for the spesific item
	 * @return mixed (boolean|array) array of the first item data that match the search | false if not found
	 */
	public function find( $search ) {
		$index = $this->find_index( $search );
		if ( $index > -1 ) {
			return $this->get_db()[ $index ];
		}
		return false;
	}
	/**
	 * Update spesific row ( item )
	 * @param string $key - what to update
	 * @param mixed (string|array) $value - new value
	 * @param mixed (string|array) - meta to search for the spesific item
	 * @param string $return - boolean or array item data ( boolean | array )
	 * @return mixed (boolean|item) ture Or item on success match the search | false if not found
	 */
	public function update( $key, $value, $where, $return = 'boolean' ) {
		$index = $this->find_index( $where );
		if ( $index > -1 ) {
			$new_data = '';
			$data     = $this->get_db();

			list( $keys, $values ) = $this->standard_key_and_value( $key, $value );

			foreach ( $keys as $i => $k ) {
				if ( isset( $data[ $index ][ $k ] ) ) {
					if ( '++' === $values[ $i ] ) {
						$values[ $i ] = (int) $data[ $index ][ $k ] + 1;
					}
					$data[ $index ][ $k ] = (string) $values[ $i ];
				}
			}

			foreach ( $data as $item ) {
				$new_data .= implode( '|', $item ) . "\n";
			}

			if ( file_put_contents( $this->db_path, $new_data ) ) {
				return 'boolean' === $return ? true : $data[ $index ];
			}
		}
		return false;
	}
	/**
	 * Create array standard from string key and value
	 * @param mixed (string|array) $key
	 * @param mixed (string|array) $value
	 * @return array of key and vaoue as array - ready to destruction
	 */
	protected function standard_key_and_value( $key, $value ) {
		if ( ! is_array( $key ) ) {
			$key = array( $key );
		}
		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}
		return array(
			$key,
			$value,
		);
	}
}
