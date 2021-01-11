<?php
switch($currentstep)
{

  case 0:
  ?><h2>Modifier délai de paiement:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td>
  <td><select name="clienttermid"><?php

  $query = 'select clienttermid,clienttermname from clientterm order by clienttermname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    echo '<option value="' . $row2['clienttermid'] . '">' . $row2['clienttermname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1">
<?php echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Read/enter data
  case 1:
  $clienttermid = $_POST['clienttermid'];
  $query = 'select clienttermname,daystopay,special from clientterm where clienttermid=\'' . $clienttermid . '\'';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier délai paiement:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td>
  <?php
  echo '<td><input type="text" name="name" value="' . $row['clienttermname'] . '" size=50></td></tr>';
  echo '<tr><td>Jours:</td><td><input type="text" name="days" value="' . $row['daystopay'] . '" size=50></td></tr>';
  echo '<tr><td>Spécial:<td><select name="special"><option value=0> </option><option value=1';
  if ($row['special'] == 1) { echo ' selected'; }
  echo '>Fin du mois</option></select>';
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2">
<?php echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '">'; ?>
  <?php echo '<input type=hidden name="clienttermid" value="' . $clienttermid . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $clienttermid = $_POST['clienttermid'];
  $clienttermname = $_POST['name'];
  $daystopay = (int) $_POST['days'];
  $special = (int) $_POST['special']; #if ($special == 1) { $daystopay = 1; }
  $query = 'update clientterm set clienttermname=?,daystopay=?,special=? where clienttermid=?';
  $query_prm = array($clienttermname, $daystopay, $special, $clienttermid);
  require('inc/doquery.php');
  echo '<p>Délai de paiement ' . $clienttermname . ' modifié.</p>';
  break;

}
?>