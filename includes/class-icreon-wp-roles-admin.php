<?php

/*
 * @since      1.0
 * @author     Icreon <user@icreon.com>
 */

class Icreon_Roles_Capabilities_Admin {

	/**
	 * plugin name
	 * @var string
	 */
	private $plugin_name;

	/**
	 * plugin version
	 * @var string
	 */
	private $plugin_version;

	/**
	 * maintain the capabilities
	 * @var object
	 */
	protected $capability_table;

	/**
	 * translation domain
	 * @var string
	 */
	protected $translation_domain;
	protected $plugin_caps;

	/**
	 *  class construction
	 * @param string $plugin_name
	 * @param string $plugin_version
	 * @param string $translation_domain
	 */
	function __construct( $plugin_name, $plugin_version, $translation_domain ) {

		$this->plugin_name			 = $plugin_name;
		$this->plugin_version		 = $plugin_version;
		$this->translation_domain	 = $translation_domain;
		$this->plugin_caps			 = Icreon_Roles_Capabilities_User_Caps::icreon_roles_capabilities_caps();

		// reguster script
		add_action( 'admin_enqueue_scripts', array( $this, 'icreon_roles_capabilities_register_script' ) );

		// regester styles
		add_action( 'admin_enqueue_scripts', array( $this, 'icreon_roles_capabilities_register_styles' ) );


		add_action( 'wp_ajax_export_role_cap', array( $this, 'export_roles_capabilities' ) );

		$this->capability_table = new Icreon_Roles_Capabilities_Table( $translation_domain, $this->plugin_caps );
	}

	private function prepare_export_roles() {
		$prepared_roles	 = array();
		$roles_to_export = $_POST[ 'roles_to_export' ];

		$editable_roles = $this->capability_table->solvese_roles_capabilities_get_roles();

		if ( empty( $roles_to_export ) || empty( $editable_roles ) ) {
			return $prepared_roles;
		}
		foreach ( $roles_to_export as $post_role ) {
			if ( isset( $editable_roles[ $post_role ] ) ) {
				$prepared_roles[ $post_role ] = $editable_roles[ $post_role ];
			}
		}
		return $prepared_roles;
	}

	public function export_roles_capabilities() {

		$prepared_roles = (array) $this->prepare_export_roles();
		header( 'Content-type: application/json' );
		header( "Content-Disposition: attachment; filename=export-roles-capabilities.txt" );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		echo json_encode( $prepared_roles );
		exit();
	}

	private function icreon_roles_capabilities_menu_title() {
		return __( 'Roles & Capabilities', $this->translation_domain );
	}

	/**
	 * generate plugin Menu
	 */
	public function icreon_roles_capabilities_menu() {
		add_submenu_page(
		'users.php', $this->icreon_roles_capabilities_menu_title(), $this->icreon_roles_capabilities_menu_title(), $this->plugin_caps[ 'manage_all_capabilities' ], 'icreon-roles-capablities', array( $this->capability_table, 'icreon_roles_capabilities_action' )
		);

		add_submenu_page(
		null, null, null, $this->plugin_caps[ 'manage_user_capabilities' ], 'icreon-def', array( $this->capability_table, 'solvese_roles_capabilities_users_roles_caps' )
		);
	}

	/**
	 * add new caps to admin
	 */
	public function icreon_roles_add_new_caps_to_admin() {
		$current_version = (int) str_replace( '.', '', $this->plugin_version );
		$prev_version	 = get_site_option( 'icreon_wprc_plugin_version', '111', false );
		if ( $prev_version < $current_version ) {
			if ( $prev_version == 111 ) {
				add_option( 'icreon_wprc_plugin_version', $current_version, '', 'no' );
			}
			update_option( 'icreon_wprc_plugin_version', $current_version );
			$plugin_caps = Icreon_Roles_Capabilities_User_Caps::icreon_roles_capabilities_caps();
			$role		 = get_role( 'administrator' );
			if ( !empty( $role ) ) {
				foreach ( $plugin_caps as $cap ) {
					$role->add_cap( $cap );
				}
			}
		}
	}

	/**
	 * register scripts
	 */
	public function icreon_roles_capabilities_register_script() {

		wp_register_script(
		'icreon-roles-capabilities-validator-js', plugins_url( '/js/jquery.validate.min.js', dirname( __FILE__ ) ), array( 'jquery' )
		);

		wp_register_script(
		'icreon-roles-capabilities-custom-js', plugins_url( '/js/custom_script.js', dirname( __FILE__ ) ), array( 'jquery' )
		);

		wp_register_script(
		'icreon-roles-capabilities-sticky-js', plugins_url( '/js/sticky.js', dirname( __FILE__ ) ), array( 'jquery' )
		);

		wp_register_script(
		'icreon-roles-capabilities-uniform-js', plugins_url( '/js/jquery.uniform.js', dirname( __FILE__ ) ), array( 'jquery' )
		);


		wp_register_script(
		'icreon-roles-capabilities-bootstrap-js', plugins_url( '/js/bootstrap.min.js', dirname( __FILE__ ) ), array( 'jquery' )
		);
	}

	/**
	 * register Stylesheet
	 */
	public function icreon_roles_capabilities_register_styles() {
		wp_register_style( 'icreon-roles-capabilities-bootstrap-css', plugins_url( '/css/bs-modal.css', dirname( __FILE__ ) ) );
		wp_register_style( 'icreon-roles-capabilities-custom-css', plugins_url( '/css/custom.css', dirname( __FILE__ ) ) );

		wp_register_style( 'icreon-roles-capabilities-font-awesome', plugins_url( '/css/font-awesome.css', dirname( __FILE__ ) ) );
		wp_register_style( 'icreon-roles-capabilities-uniform', plugins_url( '/css/uniform.default.css', dirname( __FILE__ ) ) );
	}

}
