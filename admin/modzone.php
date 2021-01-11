<?php
switch($currentstep)
{

  # 
  case 0:
  ?><h2>Modifier région</h2>
  <form method="post" action="admin.php"><table>
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
  $query = 'select regulationzonename from regulationzone where regulationzoneid=?';
  $query_prm = array($_POST['regulationzoneid']);
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier région</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Nom:</td><td><input type="text" name="regulationzonename" value="<?php echo $row['regulationzonename']; ?>" size=20></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo'<input type=hidden name="regulationzoneid" value="' . $_POST['regulationzoneid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # 
  case 2:
  $query = 'update regulationzone set regulationzonename=? where regulationzoneid=?';
  $query_prm = array($_POST['regulationzonename'],$_POST['regulationzoneid']);
  require('inc/doquery.php');
  echo '<p>Région modifiée.</p>';
  break;

}
?>