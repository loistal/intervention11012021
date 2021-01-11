<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Ajouter île</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Nom d'île:</td><td><input type="text" name="islandname" size=20></td></tr>
  <tr><td>Région:</td>
  <td><select name="regulationzoneid"><?php

  $query = 'select regulationzoneid,regulationzonename from regulationzone order by regulationzonename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['regulationzoneid'] . '">' . $row['regulationzonename'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:
  $query = 'insert into island (islandname,regulationzoneid) values ("' . $_POST['islandname'] . '","' . $_POST['regulationzoneid'] . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Île ajouté.</p>';
  break;

}
?>