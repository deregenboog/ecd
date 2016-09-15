<?php
	if (isset($ldap_user['LdapUser'])) {
		echo($ldap_user['LdapUser']['displayname']);
		echo " (".($ldap_user['LdapUser']['uidnumber']).")";
		echo "<h1>View LDAP User</h1>\n";
	}
?>

<table>
<?php 
if (isset($ldap_user['LdapUser'])) {
	foreach ($ldap_user['LdapUser'] as $k => $val) {
		echo "
		<tr>
		<td>$k</td>
		<td>$val</td>
		</tr>
		";
	}
}
?>
</table>
<h1>Groups</h1>
<table>
<?php
	echo "
		<tr>
		<td>gidnumber</td>
		<td>cn</td>
		</tr>
		";
foreach ($ldap_groups as $k => $val) {
	echo "
		<tr>
		<td>".$val['gidnumber']."</td>
		<td>".$val['cn']."</td>
		</tr>
		";
}
?>
</table>
