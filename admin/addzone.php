<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Ajouter région</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Région:</td><td><input type="text" name="regulationzonename" size=20></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:

  $query = 'insert into regulationzone (regulationzonename) values ("' . $_POST['regulationzonename'] . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Région ajoutée.</p>';
  break;

}
?>