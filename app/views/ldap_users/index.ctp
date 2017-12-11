<?php
    $properties = $active_directory ? ['samaccountname', 'cn'] : ['uid', 'cn', 'loginshell', 'uidnumber', 'gidnumber', 'homedirectory', 'gecos'];
?>

<h1>List LDAP Users</h1>
<table>
    <tr>
        <?php foreach ($properties as $property): ?>
            <th><?= $property; ?></th>
        <?php endforeach; ?>
        <th>&nbsp;</th>
    </tr>
    <?php foreach ($ldap_users as $key => $value): ?>
        <tr>
            <?php foreach ($properties as $property) {
                if (isset($value['LdapUser'][$property])) {
                    echo " <td>".$value['LdapUser'][$property]."</td>\n";
                } else {
                    echo " <td></td>\n";
                }
            } ?>
            <td>
                <?php echo $html->link('View', '/ldap_users/view/'.$value['LdapUser'][$primaryKey])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
