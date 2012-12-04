<?php

global $wpdb;

if ( isset( $_POST['submit'] ) ) {
//wtfddddd
//ttttttt
//гшуршгурагу

//branch update
	$valid = new Validator;
	$rules = array(
		array(
			'field' => 'GiftName',
			'label' => 'Gift Name',
			'rules' => array(
				'required' => 'Field %s is required',
				'alpha_numeric' => 'The %s must contain only letters and numbers',
				'max_len[150]' => 'Maximum lenght 150 characters.'
				
		)) ,
		array(
			'field' => 'GiftDesc',
			'label' => 'Gift Description',
			'rules' => array(
				'required' => 'Field %s is required',
				'alpha_numeric' => 'The %s must contain only letters and numbers',
				'max_len[255]' => 'Maximum lenght 255 characters.'
		)) ,
		array(
			'field' => 'CutoffDate',
			'label' => 'CutOff Date',
			'rules' => array(
				'required' => 'Field %s is required',
				'valid_date' => 'The %s must contain date'
		)) ,
		array(
			'field' => 'CutoffNumber',
			'label' => 'Cutoff Number',
			'rules' => array(
				'required' => 'Field %s is required',
				'integer' => 'The %s must contain numbers'
		))

	);

	$valid->set_rules($rules);
}

if( isset( $_POST['submit'] ) && $_POST['submit'] == 'Add new Gift' ) {

	if($valid->run()){
			$data = array();

				foreach( $_POST as $field => $value ) {

					if($field == 'submit' ) {
						continue;
					}

					$data[$field] = mysql_real_escape_string( $value );
				}

				$wpdb->insert( "$this->tbl_gifts", $data );
				unset($_POST);
				//wp_redirect( 'admin.php?page=admin_edit_users_gifts' );


	} else {

		$errors = $valid->get_array_errors();
	}
}

if ( isset ( $_POST['action'] ) && ($_POST['action']=='delete' || $_POST['action2']=='delete')) {

		if ( isset($_POST['list_gift']) || is_array($_POST['list_gift']) ) {
			$sql = " DELETE FROM $this->tbl_gifts WHERE ";

			foreach( $_POST['list_gift'] as $value ) {
				$str_[] = '`id` = ' . $value;
			}

			$where = implode ( $str_ , ' OR ');
			$sql = $sql .' '. $where;
			$wpdb->query ( $sql );
			unset($_POST);
			//wp_redirect( 'admin.php?page=admin_edit_users_gifts' );
		}

}


if ( isset ( $_GET['action'] ) && $_GET['action'] == 'delete') {

	if ( isset ( $_GET['gift'] ) ){
		$gift = (int) $_GET['gift'];
		$wpdb->query ( "DELETE FROM `" . $this->tbl_gifts . "` WHERE `id` = '" . (int) $gift . "'" );
		//wp_redirect( 'admin.php?page=admin_edit_users_gifts' );
	}

}

if ( isset ( $_GET['action'] ) && $_GET['action'] == 'edit') {

	if ( isset ( $_GET['gift'] ) ) {
		$id = (int) $_GET['gift'];
		$edit_mode = $wpdb->get_row( "SELECT * FROM $this->tbl_gifts WHERE id = $id", ARRAY_A );
		//wp_redirect( 'admin.php?page=admin_edit_users_gifts' );
		//var_dump($edit_mode);
	}

}

if( isset( $_POST['id'] ) && (int)$_POST['id'] ){
	
	if($valid->run()){
		$data = array();

		foreach( $_POST as $field => $value ) {
			if( $field == 'submit' ) {
				continue;
			}
			$data[$field] = mysql_real_escape_string( $value );
		}
			if (!isset($_POST['Active'])){
				$data['Active'] = '0';
			}
		$wpdb->update( "$this->tbl_gifts", $data, array('id' => $data['id']));
		//wp_redirect( 'admin.php?page=admin_edit_users_gifts' );
		unset($_POST);
	}else {

		$id = $_POST['id'];
		$edit_mode = $wpdb->get_row( "SELECT * FROM $this->tbl_gifts WHERE id = $id", ARRAY_A );
		$errors = $valid->get_array_errors();

	}
}
?>