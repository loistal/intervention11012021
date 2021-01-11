<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Modifier ville</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Ville:</td>
  <td><select name="townid"><?php

  $query = 'select townid,townname from town order by townname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['townid'] . '">' . $row['townname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:

  $query = 'select townname,townrank,islandid from town where townid="' . $_POST['townid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier ville</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Nom de ville:</td><td><input type="text" name="townname" value="<?php echo $row['townname']; ?>" size=20></td></tr>
  <tr><td>Rangement:</td><td><input type="text" STYLE="text-align:right" name="townrank" value="<?php echo $row['townrank']; ?>" size=8></td></tr>
  <tr><td>Île:</td>
  <td><select name="islandid"><?php
  $query = 'select islandid,islandname from island order by islandname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['islandid'] == $row['islandid']) { echo '<option value="' . $row2['islandid'] . '" SELECTED>' . $row2['islandname'] . '</option>'; }
    else { echo '<option value="' . $row2['islandid'] . '">' . $row2['islandname'] . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo '<input type=hidden name="townid" value="' . $_POST['townid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 2:

  $query = 'update town set townname="' . $_POST['townname'] . '",townrank="' . ($_POST['townrank']+0) . '",islandid="' . $_POST['islandid'] . '" where townid="' . $_POST['townid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Ville modifiée.</p>';
  break;

}
?>