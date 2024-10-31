<?php

class Icreon_Roles_Capabilities_User_Caps
{

    public static function icreon_roles_capabilities_caps()
    {
        $caps = array(
            'manage_all_capabilities' => 'wprc_manage_all_capabilities',
            'manage_user_capabilities' => 'wprc_manage_user_capabilities',
            'add_new_role' => 'wprc_add_new_role',
            'delete_role' => 'wprc_delete_role',
            'change_default_role' => 'wprc_change_default_role'

        );
        return $caps;
    }
}
