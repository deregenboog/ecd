<h1>List LDAP Users</h1> 
<table> 
<tr> 
   <th>uid</th> 
   <th>cn</th> 
   <th>loginshell</th> 
   <th>uidnumber</th> 
   <th>gidnumber</th> 
   <th>homedirectory</th> 
   <th>gecos</th> 
   <th>Actions</th> 
</tr> 
</tr> 
<?php foreach ($ldap_users as $key => $value): ?> 
<tr> 
   <?php
   foreach (array( 'uid', 'cn', 'loginshell', 'uidnumber', 'gidnumber', 'homedirectory', 'gecos') as
	  $par) {
	   if (isset($value['LdapUser'][$par])) {
		   echo " <td>".$value['LdapUser'][$par]."</td>\n";
	   } else {
		   echo " <td></td>\n";
	   }
   }
		   ?>
   <td> 
   <?php echo $html->link('View', '/ldap_users/view/'.$value['LdapUser'][$primaryKey])?> 
	  <?php #php echo $html->link('Edit', '/ldap_users/edit/' . $value['LdapUser'][$primaryKey])?> 
	  <?php #php echo $html->link('Delete', '/ldap_users/delete/' . $value['LdapUser'][$primaryKey])?> 
   </td> 
</tr> 
<?php endforeach; ?> 
</table> 

<ul> 
   <li><?php # echo $html->link('New Ldap User', '/ldap_users/add'); ?></li> 
</ul> 
