<?php

if ( !defined( 'ABSPATH' ) ) {
	// Silence is Golden
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class PPP_Schedule_Table extends WP_List_Table {
	function __construct() {
		global $status, $page;

		parent::__construct( array(
				'singular'  => __( 'Scheduled Share', 'ppp-txt' ),    //singular name of the listed records
				'plural'    => __( 'Scheduled Shares', 'ppp-txt' ),   //plural name of the listed records
				'ajax'      => false                                  //does this table support ajax?
			) );
	}

	public function no_items() {
		printf( __( 'No shares scheduled. Go <a href="%s">write somehing</a>!', 'ppp-txt' ), admin_url( 'post-new.php' ) );
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
		case 'date':
		case 'content':
		case 'day':
		case 'post_title':
			return $item[ $column_name ];
		default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}


	public function get_columns() {
		$columns = array(
			'post_id'        => __( 'Post ID', 'ppp-txt' ),
			'post_title'     => __( 'Post Title', 'ppp-txt' ),
			'day'            => __( 'Day', 'ppp-text' ),
			'date'           => __( 'Scheduled Date', 'ppp-txt' ),
			'content'        => __( 'Share Message', 'ppp-txt' )
		);

		return $columns;
	}

	public function column_post_id( $item ) {
		$actions = array( 'edit'      => sprintf( __( '<a href="%s">Edit</a>', 'ppp-txt' ), admin_url( 'post.php?post=' . $item['post_id'] . '&action=edit#ppp_tweet_schedule_metabox' ) ) );

		return sprintf( '%1$s %2$s', $item['post_id'], $this->row_actions( $actions ) );
	}

	public function column_date( $item ) {
		return date_i18n( get_option('date_format') . ' @ ' . get_option('time_format'), $item['date'] );
	}

	function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$this->_column_headers = array( $columns, $hidden );

		$per_page = 25;
		$current_page = $this->get_pagenum();

		$crons = get_option( 'cron' );

		foreach ( $crons as $timestamp => $cron ) {
			if ( ! isset( $cron['ppp_share_post_event'] ) ) {
				continue;
			}

			$ppp_data   = $cron['ppp_share_post_event'];
			$array_keys = array_keys( $ppp_data );
			$hash_key   = $array_keys[0];
			$event_info = $ppp_data[$hash_key];
			$name_parts = explode( '_', $event_info['args'][1] );
			$day        = $name_parts[1];

			$data[$hash_key] = array(  'post_id'      => $event_info['args'][0],
				                       'post_title'   => get_the_title( $event_info['args'][0] ),
			                           'day'          => $day,
			                           'date'         => $timestamp + ( get_option( 'gmt_offset' ) * 3600 ),
			                           'content'      => ppp_build_share_message( $event_info['args'][0], $event_info['args'][1] ) );
		}

		$total_items = count( $data );

		$offset = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;

		$data = array_slice( $data, ( $offset - 1 ) * $per_page, $per_page, true );
		$this->items = $data;


		$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil( $total_items / $per_page )
			) );

		$this->items = $data;
	}
}

$ppp_schedule_table = new PPP_Schedule_Table();



