<?php

switch($currentstep)
{

  # Which regulationtype?
  case 0:
  ?><h2>Modifier regulationtype:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Regulationtype to edit:</td>
  <td><select name="regulationtypeid"><?php

  $query = 'select regulationtypeid,regulationcode from regulationtype order by regulationcode';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['regulationtypeid'] . '">' . $row['regulationcode'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Edit data
  case 1:
  $query = 'select regulationtypename,regulationcode,regroupnumber,showasterix from regulationtype where regulationtypeid="' . $_POST['regulationtypeid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
   ?><h2>Edit regulation type:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Code:</td><td><input type="text" STYLE="text-align:right" name="regulationcode" value="<?php echo $row['regulationcode'] ?>" size=10></td></tr>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="regulationtypename" value="<?php echo $row['regulationtypename'] ?>" size=30></td></tr>
  <tr><td>Regroup number:</td><td><input type="text" STYLE="text-align:right" name="regroupnumber" value="<?php echo $row['regroupnumber'] ?>" size=5></td></tr>

  <tr><td>Show asterix:</td>
  <td><select name="showasterix"><?php
  if ($row['showasterix'] == "1") { echo '<option value=0>No</option><option value=1 SELECTED>Yes</option>'; }
  else { echo '<option value=0 SELECTED>No</option><option value=1>Yes</option>'; }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>"><input type=hidden name="regulationtypeid" value="<?php echo $_POST['regulationtypeid']; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $regulationcode = $_POST['regulationcode'];
  $regulationtypename = $_POST['regulationtypename'];
  $regroupnumber = $_POST['regroupnumber'];
  #$storeregroupnumber = $_POST['storeregroupnumber'];
  $showasterix = $_POST['showasterix'];
  $query = 'update regulationtype set regulationcode="' . $regulationcode . '",regulationtypename="' . $regulationtypename . '",regroupnumber="' . $regroupnumber . '",showasterix="' . $showasterix . '" where regulationtypeid="' . $_POST['regulationtypeid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p> Regulation ' . $_POST['regulationtypeid'] . ' edité.</p>';
  break;

}

?>