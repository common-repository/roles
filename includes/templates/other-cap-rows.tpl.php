<tr class="<?php print $this->icreon_roles_capabilities_get_tr_class(); ?> icreon-rnc-head-start" >
    <td colspan="<?php print $this->roles_count + 1; ?>"><strong> Other Capabilities </strong> </td>
</tr>

<?php foreach ($other_cpas as $other_cap) { ?>
    <tr class="<?php print $this->icreon_roles_capabilities_get_tr_class(); ?>">
        <td class="icreon-rnc-wide-cell"> 
            <div class="cap-name-to-filter"> <?php print $other_cap; ?> </div>
        </td>
        <?php foreach ($this->roles as $rolekey => $role) { ?>
            <td class="icreon-rnc-center-align"><input type="checkbox" name="capability<?php print '[' .$rolekey . '][' . $other_cap . ']'; ?>" <?php print $this->roles_capabilities_function->icreon_roles_capabilities_get_role_has_capability($this->roles, $rolekey, $other_cap); ?> /></td>
            <?php } ?>
    </tr>
<?php } ?>
        