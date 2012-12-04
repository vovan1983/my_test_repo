<?php 

class Validator {

	
	private $_field_data = array();
	private $_error_messages = array();
	private $_error_prefix = '<p>';
	private $_error_suffix = '</p>';
	
	function __construct(){

	}

	function set_rules( $field, $label = '', $rules = '' ) {

		if (sizeof( $_POST ) == 0) {
			return;
		}

		if( is_array( $field ) ) {

			foreach ( $field as $row ) {

				if ( ! isset( $row['field'] ) || ! isset( $row['rules' ]) ) {
					continue;
				}

				$label = ( ! isset( $row['label']) ) ? $row['field'] : $row['label'];
				$this->set_rules( $row['field'], $label, $row['rules'] );
			}
			return;
		}

		if ( ! is_string( $field ) ||  ! is_array( $rules ) OR $field == '' ) {
			return;
		}

		$label = ($label == '') ? $field : $label;
		$this->_field_data[$field] = array(
			'field' => $field, 
			'label' => $label, 
			'rules' => $rules,
			'postdata' => null,
			'error' => ''
			);
	}

	function run() {
		if ( sizeof($_POST) == 0 ) {
			return ;
		}

		if( sizeof( $this->_field_data ) == 0 ) {
			return false;
		}

		foreach ( $this->_field_data as $field => $row ) {
			$this->_field_data[$field]['postdata'] = ( isset( $_POST[$field] ) )? $_POST[$field]: null;

		$this->checkrule($row,$this->_field_data[$field]['postdata']);
		}

		$total_errors = sizeof( $this->_error_messages );
		
		if( $total_errors == 0 ){
			return true;
		}

		return false;
	}

	function checkrule( $field, $postdata ) {

		if( is_array ( $postdata ) ) {

			foreach($postdata as $key => $val ) {
				$this->checkrule($field,$val);
			}

			return;
		}

		foreach( $field['rules'] as $rule => $message ) {
			$param = false;

			if ( preg_match( "/(.*?)\[(.*?)\]/", $rule, $match ) )
			{
				$rule	= $match[1];
				$param	= $match[2];
			}

			if( !method_exists( $this, $rule ) ) {

				if( function_exists( $rule ) ) {
					$result = $rule( $postdata );
					$postdata = ( is_bool( $result ) ) ? $postdata : $result;
					$this->set_field_postdata( $field['field'], $postdata);
					continue;
				}
			} else {
				$result = $this->$rule( $postdata, $param);
			}

			$postdata = ( is_bool( $result ) ) ? $postdata : $result;
			$this->set_field_postdata( $field['field'], $postdata);

			if( $result === false && $message != '' ) {
				$error = sprintf( $message, $field['label']);
				$this->_field_data[$field['field']]['error'] = $error;

				if ( ! isset( $this->_error_messages[$field['field']] ) ) {
					$this->_error_messages[$field['field']] = $error;
				}

			}

			continue;
		}

		return;
	}

	private function set_field_postdata( $field, $postdata ) {

		if( isset( $this->_field_data[$field]['postdata'] ) ) {
			$this->_field_data[$field]['postdata'] = $postdata;
		}
	}

	function postdata( $field ) {

		if( isset( $this->_field_data[$field]['postdata'] ) ){
			return $this->_field_data[$field]['postdata'];
		}
		else return false;
	}

	function reset_postdata() {
		$this->_field_data = array();
	}

	function get_string_errors( $prefix = '', $suffix = '' ) {

		if ( count( $this->_error_messages ) === 0 ) {
			return '';
		}

		if ( $prefix == '' ){
			$prefix = $this->_error_prefix;
		}

		if ($suffix == ''){
			$suffix = $this->_error_suffix;
		}

		$str = '';
		foreach ( $this->_error_messages as $val ){
			if ( $val != '' ){
				$str .= $prefix . $val.$suffix . "\n";
			}
		}

		return $str;
	}

	function get_array_errors() {
		return $this->_error_messages;
	}

	function form_error( $field ) {

		if( isset( $this->_error_messages[$field] ) ) {
			return $this->_error_prefix.$this->_error_messages[$field].$this->_error_suffix;
		}
		else return false;
	}

	function set_error_delimiters( $prefix = '<p>', $suffix = '</p>' ) {
		$this->_error_prefix = $prefix;
		$this->_error_suffix = $suffix;
	}

	function required( $str ) {
		if ( ! is_array( $str ) ){
			return ( trim( $str ) == '') ? false : true;
		} else {
			return ( ! empty( $str ) );
		}
	}
	
	function max_len ( $str , $len = 255) {
		$str_len = strlen($str);
		return $str_len <= $len;
		
	}
	
	function min_len ( $str , $len = 255 ) {
		$str_len = strlen($str);
		return $str_len >= $len;
		
	}
	function integer( $str ) {
		return filter_var( $str, FILTER_VALIDATE_INT );
	}

	function float( $str ) {
		return filter_var( $str, FILTER_VALIDATE_FLOAT );
	}

	function valid_url($str){

		return filter_var( $str, FILTER_VALIDATE_URL );
	}

	function valid_email( $str ) {
		return filter_var( $str, FILTER_VALIDATE_EMAIL );
	}

	function valid_ip( $str ) {
		return filter_var( $str, FILTER_VALIDATE_IP );
	}

	function matches( $str, $field ) {

		if ( ! isset( $_POST[$field] ) ){
			return false;
		}

		$field = $_POST[$field];
		return ( $str !== $field ) ? false : true;
	}

	function alpha( $str) {
		return ( ! preg_match( "/^([a-z])+$/i", $str ) ) ? false : true;
	}

	function valid_captcha( $str, $name ){
		return ( ! empty( $_SESSION[$name] ) && $_SESSION[$name] == $str )? true: false;
	}

	function valid_date( $str ) {
		$stamp = strtotime( $str );

		if ( ! is_numeric( $stamp ) ) {
			return false;
		}

		$month = date( 'm', $stamp );
		$day = date( 'd', $stamp );
		$year = date( 'Y', $stamp );
		return checkdate( $month, $day, $year );
	}

}

?>