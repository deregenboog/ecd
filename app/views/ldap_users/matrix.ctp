<?php
    function getReadableGroup($group) {
        return preg_replace('/^CN=([^,]*).*$/', '$1', $group);
    }
    function getReadableController($controller) {
        return preg_replace('/Bundle\\\Controller/', '', $controller);
    }
?>

<h1>Matrix LDAP Users</h1>
<table>
    <tr>
       <th>Gebruiker / Groep</th>
       <th>ID</th>
       <?php foreach (array_keys($permissions) as $gid): ?>
           <th><?= getReadableGroup($gid) ?></th>
       <?php endforeach; ?>
    </tr>
    <?php foreach ($ldap_users as $uid => $ldap_user): ?>
        <tr>
            <td><?= $ldap_user['cn'] ?> (<?= $uid ?>)</td>
            <td><?= isset($ldap_user['id']) ? $ldap_user['id'] : '' ?></td>
           <?php foreach ($permissions as $gid => $permission): ?>
               <td><?= (array_key_exists($gid, $ldap_user['groups'])) ? 'X' : '' ?></td>
           <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
<table>
    <tr>
       <th>Permissie / Groep</th>
       <?php foreach (array_keys($permissions) as $gid): ?>
           <th><?= getReadableGroup($gid) ?></th>
       <?php endforeach; ?>
    </tr>
    <?php foreach ($all_controllers as $controller): ?>
        <tr>
            <td><?= getReadableController($controller); ?></td>
           <?php foreach ($permissions as $controllers): ?>
               <td><?= (in_array($controller, $controllers)) ? 'X' : '' ?></td>
           <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
