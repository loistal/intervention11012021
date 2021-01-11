<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Ajouter ville</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Nom:</td><td><input type="text" name="townname" size=20></td></tr>
  <tr><td>Île:</td>
  <td><select name="islandid"><?php
  $query = 'select islandid,islandname from island order by islandname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:
  $query = 'insert into town (townname,islandid) values ("' . $_POST['townname'] . '","' . $_POST['islandid'] . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Ville ajouté.</p>';
  break;

}
?>