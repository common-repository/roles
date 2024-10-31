<?php

/*
  Plugin Name: Roles
  Plugin URI: http://www.icreon.com
  Description: Manage user roles and capabilities.
  Version: 1.0.0
  Author: Icreon User
  Author URI: http://www.icreon.com
  License: GPLv2 or later
 */

if ( !defined( 'WPINC' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-icreon-wp-roles-capabilities-caps.php';

function activate_icreon_roles_capabilities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-icreon-wp-roles-capabilities-activator.php';
	Icreon_Roles_Capabilities_Activator::activate();
}

function deactivate_icreon_roles_capabilities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-icreon-wp-roles-capabilities-deactivator.php';
	Icreon_Roles_Capabilities_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_icreon_roles_capabilities' );
register_deactivation_hook( __FILE__, 'deactivate_icreon_roles_capabilities' );


require_once plugin_dir_path( __FILE__ ) . "includes/class-icreon-wp-roles-capabilities.php";

function execute_icreon_roles_capabilities() {
	$icreon_roles_capabilities = new Icreon_Roles_Capabilities();
	$icreon_roles_capabilities->execute();
}

execute_icreon_roles_capabilities();
