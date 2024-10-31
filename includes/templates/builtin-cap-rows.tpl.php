<?php
foreach ($builtin_capabilities as $builtin_capability) {
    ?>
    <tr class="<?php print $this->icreon_roles_capabilities_get_tr_class(); ?> icreon-rnc-head-start" >
        <td colspan="<?php print $this->roles_count + 1; ?>"><strong> <?php print $builtin_capability['name']; ?> </strong> </td>
    </tr>
    <?php foreach ($builtin_capability['cap'] as $key => $value) { ?>
        <tr class="<?php print $this->icreon_roles_capabilities_get_tr_class(); ?>">
            <td class="icreon-rnc-wide-cell"> 
                <div class="cap-name-to-filter"><?php print $value['name']; ?> </div>
                <!--<div><?php print $value['desc']; ?> </div> -->
            </td>
            <?php foreach ($this->roles as $rolekey => $role) { ?>
                <td class="icreon-rnc-center-align"><input type="checkbox" name="capability<?php print  '[' . $rolekey . '][' . $key . ']'; ?>" <?php print $this->roles_capabilities_function->icreon_roles_capabilities_get_role_has_capability($this->roles, $rolekey, $key); ?> /></td>
            <?php } ?>
        </tr>
    <?php } ?>
<?php } ?>