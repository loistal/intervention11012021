<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Modifier département de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Départment de produit:</td>
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

  # Edit data
  case 1:

  $query = 'select productdepartmentname,departmentrank from productdepartment where productdepartmentid="' . $_POST['productdepartmentid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier département de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <?php
  echo '<tr><td>Ancien nom:</td><td>' . $row['productdepartmentname'] . '</td></tr>';
  echo '<tr><td>Nouveau nom:</td><td><input type="text" STYLE="text-align:right" name="productdepartmentname" value="' . $row['productdepartmentname'] . '" size=30></td></tr>';
  echo '<tr><td>Rank:</td><td><input type="text" STYLE="text-align:right" name="departmentrank" value="' . $row['departmentrank'] . '" size=10></td></tr>';
  ?><tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo '<input type=hidden name="productdepartmentid" value="' . $_POST['productdepartmentid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $productdepartmentname = $_POST['productdepartmentname'];
  $departmentrank = $_POST['departmentrank'];
  $query = 'update productdepartment set departmentrank="' . $departmentrank . '",productdepartmentname="' . $productdepartmentname . '" where productdepartmentid="' . $_POST['productdepartmentid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Département de produit ' . $productdepartmentname . ' modifié.</p>';
  break;

}
?>