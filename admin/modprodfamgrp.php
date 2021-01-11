<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Modifier famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Famille de produit:</td>
  <td><select name="productfamilygroupid"><?php

  $query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productfamilygroup,productdepartment where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by productfamilygroupname,productdepartmentname';
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

  # Edit data
  case 1:

  $query = 'select productfamilygroupname,productdepartmentid,familygrouprank from productfamilygroup where productfamilygroupid="' . $_POST['productfamilygroupid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <?php
  echo '<tr><td>Ancien nom:</td><td>' . $row['productfamilygroupname'] . '</td></tr>';
  echo '<tr><td>Nouveau nom:</td><td><input type="text" STYLE="text-align:right" name="productfamilygroupname" value="' . $row['productfamilygroupname'] . '" size=30></td></tr>';
  ?><tr><td>Département de produit:</td><td><select name="productdepartmentid"><?php

  $query = 'select productdepartmentid,productdepartmentname from productdepartment order by productdepartmentname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['productdepartmentid'] == $row['productdepartmentid']) { echo '<option value="' . $row2['productdepartmentid'] . '" SELECTED>' . $row2['productdepartmentname'] . '</option>'; }
    else { echo '<option value="' . $row2['productdepartmentid'] . '">' . $row2['productdepartmentname'] . '</option>'; }
  }
  ?></select></td></tr><?php
  echo '<tr><td>Rank:</td><td><input type="text" STYLE="text-align:right" name="familygrouprank" value="' . $row['familygrouprank'] . '" size=10></td></tr>';
  ?><tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo '<input type=hidden name="productfamilygroupid" value="' . $_POST['productfamilygroupid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $productfamilygroupname = $_POST['productfamilygroupname'];
  $productdepartmentid = $_POST['productdepartmentid'];
  $familygrouprank = $_POST['familygrouprank'];
  $query = 'update productfamilygroup set familygrouprank="' . $familygrouprank . '",productfamilygroupname="' . $productfamilygroupname . '",productdepartmentid="' . $productdepartmentid . '" where productfamilygroupid="' . $_POST['productfamilygroupid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Famille de produit ' . $productfamilygroupname . ' modifiée.</p>';
  break;

}
?>