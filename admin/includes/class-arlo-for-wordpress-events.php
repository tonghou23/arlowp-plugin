<?php
/**
 * Arlo For Wordpress
 *
 * @package   Arlo_For_Wordpress_Admin
 * @author    Arlo <info@arlo.co>
 * @license   GPL-2.0+
 * @link      http://arlo.co
 * @copyright 2016 Arlo
 */
 
require_once 'class-arlo-for-wordpress-lists.php';
 

class Arlo_For_Wordpress_Events extends Arlo_For_Wordpress_Lists  {

	public function __construct() {		
		$this->singular = __( 'Event', $this->plugin_slug );		
		$this->plural = __( 'Events', $this->plugin_slug );

		parent::__construct();		
	}
	
	public function set_table_name() {
		$this->table_name = $this->wpdb->prefix . 'arlo_events';
	}
	
	public function get_columns() {
		return $columns = [
			'e_code'    => __( 'Code', $this->plugin_slug ),
			'e_name'    => __( 'Name', $this->plugin_slug ),
			'e_startdatetime'    => __( 'Start date', $this->plugin_slug ),
			'e_finishdatetime'    => __( 'Finish date', $this->plugin_slug ),
			'v_name' => __( 'Venue name', $this->plugin_slug ),
			'e_locationname' => __( 'Location name', $this->plugin_slug ),
			'e_roomname' => __( 'Room name', $this->plugin_slug ),
			'e_placesremaining' => __( 'Places remaining', $this->plugin_slug ),
			'e_summary' => __( 'Summary', $this->plugin_slug ),
			'e_sessiondescription' => __( 'Description', $this->plugin_slug ),
			'e_notice' => __( 'Notice', $this->plugin_slug ),
			'e_register' => __( 'Register link', $this->plugin_slug ),
			'e_provider' => __( 'Provider', $this->plugin_slug ),
			//'e_isonline' => __( 'Online', $this->plugin_slug ),
		];
	}	
	
	public function get_hidden_columns() {
        return array();
    }	
	
	public function get_sortable_columns() {
		return array(
			'e_code' => array( 'e_code', true ),
			'e_name' => array( 'e_name', true ),
			'e_startdatetime' => array( 'e_startdatetime', true ),
			'e_finishdatetime' => array( 'e_finishdatetime', true ),
			'v_name' => array( 'v_name', true ),
			'e_locationname' => array( 'e_locationname', true ),
			'e_locationroomname' => array( 'e_locationroomname', true ),
			'e_placesremaining' => array( 'e_placesremaining', true ),
			'e_summary' => array( 'e_summary', true ),
			'e_sessiondescription' => array( 'e_sessiondescription', true ),			
		);
	}
	
	public function column_default($item, $column_name) {
		switch ($column_name) {
			case 'e_code':
			case 'e_name':
			case 'v_name':
			case 'e_locationname':
			case 'e_roomname':
			case 'e_placesremaining':
				return $item->$column_name;
			case 'e_summary':
			case 'e_sessiondescription':
				if (!empty($item->$column_name))
					return '<div class="arlo-list-ellipsis">' . strip_tags($item->$column_name) . '</div>';
				break;
			case 'e_startdatetime':
			case 'e_finishdatetime':
				return $item->$column_name . " " . $item->e_timezone;
			break;
			case 'e_register':
				if (!empty($item->e_registeruri)) 		
					return '<a href="'.$item->e_registeruri.'" target="_blank">' . $item->e_registermessage . '</a>';
				break;
			default:
				return '';
			}
	}
	
	function column_e_code($item) {
		$actions = array(
            'edit' => sprintf('<a href="https://my.arlo.co/%s/Courses/Course.aspx?id=%d">Edit</a>', $this->platform_name, $item->e_arlo_id)
        );
        
		return sprintf('%1$s %2$s', $item->e_code, $this->row_actions($actions) );
	}
	
	protected function get_sql_where() {
		return [
			"e.active = '" . $this->active . "'",
			"e.e_parent_arlo_id = 0"
		];
	}
	
	protected function get_searchable_fields() {
		return [
			'e_code',
			'e_code',
			'v_name',
			'e_locationname',
			'e_locationroomname',
			'e_summary',
			'e_sessiondescription',
			'e_notice',
			'e_registermessage',
			'e_providerorganisation',
		];
	}	
		
	public function get_sql_query() {
		$where = $this->get_sql_where();
		$where = implode(" AND ", $where);
	
		return "
		SELECT
			e.e_arlo_id,
			e.e_code,
			e.e_name,
			e.e_startdatetime,
			e.e_finishdatetime,
			e_timezone,
			v_name,
			e_locationname,
			e_locationroomname,
			e_isfull,
			e_placesremaining,
			e_summary,
			e_sessiondescription,
			e_notice,
			e_registermessage,
			e_registeruri,
			e_providerorganisation,
			e_providerwebsite,
			e_isonline
		FROM
			" . $this->table_name . " AS e
		LEFT JOIN 
			" . $this->wpdb->prefix . "arlo_venues
		ON
			e.v_id = v_arlo_id
		WHERE
			" . $where . "
		";
	}		
}

?>