<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Ajouter famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="productfamilygroupname" size=30></td></tr>
  <tr><td>Département:</td>
  <td><select name="productdepartmentid"><?php

  $query = 'select productdepartmentid,productdepartmentname from productdepartment order by productdepartmentname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['productdepartmentid'] . '">' . $row['productdepartmentname'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $productfamilygroupname = $_POST['productfamilygroupname'];
  $productdepartmentid = $_POST['productdepartmentid'];
  $query = 'insert into productfamilygroup (productfamilygroupname,productdepartmentid) values ("' . $productfamilygroupname . '","' . $productdepartmentid . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Famille de produit ' . $productfamilygroupname . ' ajoutée.</p>';
  break;

}
?>