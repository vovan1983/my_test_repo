<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Gift_List_Table extends WP_List_Table {

	public $search_field;
	
	function __construct($field_id = 'id') {
		global $status, $page;
		$this->search_field = $field_id;
		parent::__construct( array(
			'singular'=> 'list_gift', //Singular label
			'plural' => 'lists_gifts', //plural label, also this well be one of the table css class
			'ajax'	=> false //We won't support Ajax for this table
		) );
	}

	function no_items() {
		_e( 'No gifts found.' );
	}

	/*function extra_tablenav( $which ) {

		if ( $which == "top" ) {
			echo"Hello, I'm before the table";
		}
		if ( $which == "bottom" ) {
			echo"Hi, I'm after the table";
		}
	}*/

	function column_default( $item, $column_name ) {

		switch($column_name){			
			case 'GiftName':
			case 'GiftDesc':
			case 'CutoffDate':
			case 'CutoffNumber':
			case 'CurrentCount':
			case 'Active':
			return $item[$column_name];
		default:
			return print_r( $item, true );
		}
	}

	function column_GiftName( $item ) {
		$actions = array(
			'edit'=> sprintf( '<a href="?page=%s&action=%s&gift=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']),
			'delete'=> sprintf('<a onclick = "if (!confirm(\'Do you want delete this gift?\')){return false;}" href="?page=%s&action=%s&gift=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
		);

		return sprintf( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
			/*$1%s*/ $item['GiftName'],
			/*$2%s*/ $item['id'],
			/*$3%s*/ $this->row_actions($actions)
		);
	}
	
	function column_cb( $item ){
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ $this->_args['singular'],
			/*$2%s*/ $item['id']
		);
	}

	function get_columns() {
		return $columns = array(
			'cb' => '<input type="checkbox" />',
			'GiftName' => __( 'Gift Name', 'list_gift' ),
			'GiftDesc' => __( 'Gift Desc', 'list_gift' ),
			'CutoffDate' => __( 'Cutoff Date', 'list_gift' ),
			'CutoffNumber' => __( 'Cutoff Number', 'list_gift' ),
			'CurrentCount' => __( 'Current Count', 'list_gift' ),
			'Active' => __( 'Active', 'list_gift' ),
		);
	}

	function get_sortable_columns() {
		return $sortable = array(
			'GiftName'=>array( 'GiftName', false),
			'GiftDesc'=>array( 'GiftDesc', false)
		);
	}

	function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete'
		);
		return $actions;
	}

	function process_bulk_action() {
		if( 'delete' === $this->current_action() ) {			
			//wp_die( 'Items deleted (or they would be if we had items to delete)!' );
		}
	}

	function prepare_items() {
		global $wpdb; 
		global $tab_gt_gifts;

		$per_page = 7;

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable);
		$this->process_bulk_action();		
		$query = " SELECT * FROM $tab_gt_gifts ";
		$query = (isset($_POST['s'])) ? $query . ' WHERE `' . $this->search_field . '` LIKE "%' . $_POST['s']. '%"' : $query;
		$data = $wpdb->get_results( $query, ARRAY_A );
		function usort_reorder( $a, $b ) {
			$orderby = ( ! empty($_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'id';
			$order = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'desc';
			$result = strcmp( $a[$orderby], $b[$orderby] );
			return ( $order === 'asc' ) ? $result : -$result;
		}

		usort($data, 'usort_reorder');
		$current_page = $this->get_pagenum();
		$total_items = count( $data );
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}
}
?>
