<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Modifier île</h2>
  <form method="post" action="admin.php"><table>
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

  $query = 'select islandname,regulationzoneid from island where islandid="' . $_POST['islandid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier île</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Nom d'île:</td><td><input type="text" name="islandname" value="<?php echo $row['islandname']; ?>" size=20></td></tr>
  <tr><td>Région:</td>
  <td><select name="regulationzoneid"><?php
  $query = 'select regulationzoneid,regulationzonename from regulationzone order by regulationzonename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['regulationzoneid'] == $row['regulationzoneid']) { echo '<option value="' . $row2['regulationzoneid'] . '" SELECTED>' . $row2['regulationzonename'] . '</option>'; }
    else { echo '<option value="' . $row2['regulationzoneid'] . '">' . $row2['regulationzonename'] . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo'<input type=hidden name="islandid" value="' . $_POST['islandid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 2:
  $query = 'update island set islandname="' . $_POST['islandname'] . '",regulationzoneid="' . $_POST['regulationzoneid'] . '" where islandid="' . $_POST['islandid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Île modifiée.</p>';
  break;

}
?>