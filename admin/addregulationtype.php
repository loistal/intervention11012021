<?php

switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Add regulation type:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Code:</td><td><input type="text" STYLE="text-align:right" name="regulationcode" size=10></td></tr>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="regulationtypename" size=20></td></tr>
  <tr><td>Regroup number:</td><td><input type="text" STYLE="text-align:right" name="regroupnumber" size=5></td></tr>
  <?php
  #<tr><td>Magasin regroup number:</td><td><input type="text" STYLE="text-align:right" name="storeregroupnumber" size=5></td></tr>
  ?>
  <tr><td>Show asterix:</td>
  <td><select name="showasterix"><option value=0>No</option><option value=1>Yes</option></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $regulationcode = $_POST['regulationcode'];
  $regulationtypename = $_POST['regulationtypename'];
  $regroupnumber = $_POST['regroupnumber'];
  #$storeregroupnumber = $_POST['storeregroupnumber'];
  $showasterix = $_POST['showasterix'];
  $query = 'insert into regulationtype (regulationtypename,regulationcode,regroupnumber,showasterix) values ("' . $regulationtypename . '","' . $regulationcode . '","' . $regroupnumber . '","' . $showasterix . '")';
  $query_prm = array();
  require('inc/doquery.php');
  $regulationtypeid = $query_insert_id;
  echo '<p> Regulation ' . $regulationtypename . ' ajouté.</p>';
  $query = 'select regulationzoneid from regulationzone';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $query = 'insert into regulationmatrix (regulationzoneid,regulationtypeid,freightpriceperkilo,regulationmargin) values ("' . $row['regulationzoneid'] . '","' . $regulationtypeid . '","0","0")';
    $query_prm = array();
    require('inc/doquery.php');
  }
  break;

}
  
?>