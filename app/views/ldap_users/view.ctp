<h1>View LDAP User</h1>

<p>
    <?php if (isset($ldap_user['LdapUser'])): ?>
        <?= $ldap_user['LdapUser']['cn'] ?>
        (<?= $ldap_user['LdapUser']['uidnumber'] ?>)
    <?php endif; ?>
</p>

<table>
    <?php if (isset($ldap_user['LdapUser'])): ?>
        <?php foreach ($ldap_user['LdapUser'] as $key => $value): ?>
            <?php if (is_array($value)) continue; ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $value ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<h2>Groups</h2>
<table>
    <tr>
        <th>gidnumber</th>
        <th>cn</th>
    </tr>
    <?php foreach ($ldap_groups as $value): ?>
        <tr>
            <td><?= $value['gidnumber'] ?></td>
            <td><?= $value['cn'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
