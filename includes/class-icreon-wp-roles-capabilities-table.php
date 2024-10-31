<?php

class Icreon_Roles_Capabilities_Table {

	private $roles_capabilities_function;
	private $roles;
	private $count = 0;
	private $roles_count;
	private $user;
	private $translation_domain;
	private $cancel_button;
	private $plugin_caps;

	function __construct( $translation_domain, $plugin_caps ) {
		$this->translation_domain			 = $translation_domain;
		$this->plugin_caps					 = $plugin_caps;
		$this->cancel_button				 = __( 'Cancel', $this->translation_domain );
		$this->roles_capabilities_function	 = new Icreon_Roles_Capabilities_Functionality( $translation_domain );
	}

	public function solvese_roles_capabilities_users_roles_caps() {
		wp_enqueue_style( 'icreon-roles-capabilities-custom-css' );
		wp_enqueue_script( 'icreon-roles-capabilities-validator-js' );
		wp_enqueue_script( 'icreon-roles-capabilities-custom-js' );

		$user_id = (int) $_GET[ 'user' ];
		if ( isset( $_GET[ 'icreon_rnc_save_user_rc' ] ) && wp_verify_nonce( $_GET[ 'icreon_rnc_save_user_rc' ], 'icreon_rnc_save_user_rc_' . $_GET[ 'user' ] ) && $user_id > 0 ) {
			$this->user	 = get_userdata( $user_id );
			$message	 = false;
			if ( isset( $_POST[ 'icreon_user_role_cap_action' ] ) && $_POST[ 'icreon_user_role_cap_action' ] == 'user_role_cap' ) {
				$message = true;
				$this->roles_capabilities_function->icreon_roles_capabilities_update_user_role_cap( $user_id );
			}

			$this->user = get_userdata( $user_id );

			$hidden_cap					 = $this->roles_capabilities_function->icreon_roles_capabilities_get_other_custom_caps_for_user( $this->user );
			$user_roles					 = $this->roles_capabilities_function->icreon_roles_capabilities_get_editable_roles();
			$user_capability_from_role	 = $this->roles_capabilities_function->icreon_roles_capabilities_get_user_caps_come_from_roles( $this->user->roles );
			require plugin_dir_path( __FILE__ ) . 'templates/user-caps-role.tpl.php';
		}
	}

	public function solvese_roles_capabilities_get_roles() {
		if ( empty( $this->roles ) ) {
			$this->roles		 = $this->roles_capabilities_function->icreon_roles_capabilities_get_editable_roles( 'administrator' );
			$this->roles_count	 = count( $this->roles );
		}
		return $this->roles;
	}

	/**
	 * actions begin here
	 */
	public function icreon_roles_capabilities_action() {
		$message = array();
		if ( !empty( $_POST ) && isset( $_POST[ 'icreon_role_cap_action' ] ) ) {
			switch ( $_POST[ 'icreon_role_cap_action' ] ) {
				// add role 
				case "add_role":
					if ( current_user_can( $this->plugin_caps[ 'add_new_role' ] ) ) {
						$message = $this->roles_capabilities_function->icreon_roles_capabilities_add_role();
					}
					break;
				case "delete_role":
					if ( current_user_can( $this->plugin_caps[ 'delete_role' ] ) ) {
						$message = $this->roles_capabilities_function->icreon_roles_capabilities_delete_role();
					}
					break;

				case "import_capability":
					if ( current_user_can( $this->plugin_caps[ 'import' ] ) ) {
						$message = $this->roles_capabilities_function->icreon_roles_capabilities_import_capability();
					}
					break;

				case "change_default_role":
					if ( current_user_can( $this->plugin_caps[ 'change_default_role' ] ) ) {
						$message = $this->roles_capabilities_function->icreon_roles_capabilities_change_default_role();
					}
					break;

				case "save_cap_changes":
					$message = $this->roles_capabilities_function->icreon_roles_capabilities_save_capabilities();
					break;

				case "change_role_display_name":
					if ( current_user_can( $this->plugin_caps[ 'rename_role' ] ) ) {
						$message = $this->roles_capabilities_function->icreon_change_role_display_name();
					}
					break;

				default:
					break;
			}
		}

		// adding scripts
		wp_enqueue_script( 'icreon-roles-capabilities-validator-js' );
		wp_enqueue_script( 'icreon-roles-capabilities-custom-js' );
		wp_enqueue_script( 'icreon-roles-capabilities-sticky-js' );
		wp_enqueue_script( 'icreon-roles-capabilities-bootstrap-js' );
		wp_enqueue_script( 'icreon-roles-capabilities-uniform-js' );

		// adding styles
		wp_enqueue_style( 'icreon-roles-capabilities-custom-css' );
		wp_enqueue_style( 'icreon-roles-capabilities-bootstrap-css' );
		wp_enqueue_style( 'icreon-roles-capabilities-font-awesome' );
		wp_enqueue_style( 'icreon-roles-capabilities-uniform' );
		$my_localize_roles = array( 'icreon_wp_roles' => json_encode( (array) $this->roles_capabilities_function->icreon_roles_capabilities_get_roles() ) );
		wp_localize_script( 'icreon-roles-capabilities-custom-js', 'icreon_wp_roles', $my_localize_roles );

		$this->solvese_roles_capabilities_get_roles();
		//print_r($this->roles);
		//exit;
		$this->icreon_roles_capabilities_add_role();
		$this->icreon_roles_capabilities_delete_role();
		$this->icreon_roles_capabilities_change_default_role();
		$this->icreon_roles_capabilities_change_role_name();
		$this->icreon_roles_capabilities_export_caps();
		$this->icreon_roles_capabilities_import_caps();
		$this->icreon_roles_capabilities_table_header( $message );
		$this->icreon_roles_capabilities_table_body();
		$this->icreon_roles_capabilities_table_footer();
	}

	/**
	 * add role form
	 */
	private function icreon_roles_capabilities_add_role() {
		if ( current_user_can( $this->plugin_caps[ 'add_new_role' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/add-role.tpl.php';
		}
	}

	private function icreon_roles_capabilities_delete_role() {
		if ( current_user_can( $this->plugin_caps[ 'delete_role' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/delete-role.tpl.php';
		}
	}

	private function icreon_roles_capabilities_change_role_name() {
		if ( current_user_can( $this->plugin_caps[ 'rename_role' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/change-role-name.tpl.php';
		}
	}

	private function icreon_roles_capabilities_change_default_role() {
		if ( current_user_can( $this->plugin_caps[ 'change_default_role' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/default-role.tpl.php';
		}
	}

	private function icreon_roles_capabilities_export_caps() {
		if ( current_user_can( $this->plugin_caps[ 'export' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/export-cap.tpl.php';
		}
	}

	private function icreon_roles_capabilities_import_caps() {
		if ( current_user_can( $this->plugin_caps[ 'import' ] ) ) {
			require plugin_dir_path( __FILE__ ) . 'templates/import-cap.tpl.php';
		}
	}

	private function icreon_roles_capabilities_table_header( $message ) {
		require plugin_dir_path( __FILE__ ) . 'templates/head-table.tpl.php';
	}

	private function icreon_roles_capabilities_table_footer() {
		require plugin_dir_path( __FILE__ ) . 'templates/foot-table.tpl.php';
	}

	private function icreon_roles_capabilities_table_body() {
		require plugin_dir_path( __FILE__ ) . 'templates/table-start.tpl.php';
		$this->icreon_roles_capabilities_builtin_cap();
		//$this->icreon_roles_capabilities_custom_cap();
		require plugin_dir_path( __FILE__ ) . 'templates/table-end.tpl.php';
	}

	private function icreon_roles_capabilities_builtin_cap() {
		$builtin_capabilities = $this->roles_capabilities_function->icreon_roles_capabilities_get_built_in_capabilities();
		require plugin_dir_path( __FILE__ ) . 'templates/builtin-cap-rows.tpl.php';
	}

	private function icreon_roles_capabilities_custom_cap() {
		$other_cpas = $this->roles_capabilities_function->icreon_roles_capabilities_get_other_capabilities();
		require plugin_dir_path( __FILE__ ) . 'templates/other-cap-rows.tpl.php';
	}

	private function icreon_roles_capabilities_get_tr_class() {
		$class = '';
		if ( $this->count % 2 == 0 ) {
			$class = 'alternate';
		}
		$this->count++;
		return $class;
	}

}
