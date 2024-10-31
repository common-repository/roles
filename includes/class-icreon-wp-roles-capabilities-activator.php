<?php

class Icreon_Roles_Capabilities_Activator {

	public static function activate() {
		$caps = array();
		add_option( 'icreon_wp_rc_caps', serialize( $caps ) );
		Icreon_Roles_Capabilities_Activator::icreon_roles_capabilities_add_plugin_cap();
	}

	private static function icreon_roles_capabilities_add_plugin_cap() {
		$plugin_caps = Icreon_Roles_Capabilities_User_Caps::icreon_roles_capabilities_caps();
		global $wp_roles;
		$all_roles	 = $wp_roles->roles;
		if ( isset( $all_roles[ 'administrator' ] ) ) {
			$role = get_role( 'administrator' );
			foreach ( $plugin_caps as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}

}
