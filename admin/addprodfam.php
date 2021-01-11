<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Ajouter sous-famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="productfamilyname" size=30></td></tr>
  <tr><td>Famille de produit:</td>
  <td><select name="productfamilygroupid"><?php

  $query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by productfamilygroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['productfamilygroupid'] . '">' . $row['productfamilygroupname'] . ' (' . $row['productdepartmentname'] . ')</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $productfamilyname = $_POST['productfamilyname'];
  $productfamilygroupid = $_POST['productfamilygroupid'];
  $query = 'insert into productfamily (productfamilyname,productfamilygroupid) values ("' . $productfamilyname . '","' . $productfamilygroupid . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Sous-famille de produit ' . $productfamilyname . ' ajouté.</p>';
  break;

}
?>