<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Modifier sous-famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Sous-famille de produit:</td>
  <td><select name="productfamilyid"><?php

  $query = 'select productfamilyid,productfamilyname,productfamilygroupname from productfamily,productfamilygroup where productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid order by productfamilyname,productfamilygroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['productfamilyid'] . '">' . $row['productfamilyname'] . ' (' . $row['productfamilygroupname'] . ')</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Edit data
  case 1:

  $query = 'select productfamilyname,productfamilygroupid,familyrank from productfamily where productfamilyid="' . $_POST['productfamilyid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  ?><h2>Modifier sous-famille de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <?php
  echo '<tr><td>Ancien Nom:</td><td>' . $row['productfamilyname'] . '</td></tr>';
  echo '<tr><td>Nouveau Nom:</td><td><input type="text" STYLE="text-align:right" name="productfamilyname" value="' . $row['productfamilyname'] . '" size=30></td></tr>';
  ?><tr><td>Famille de produit:</td><td><select name="productfamilygroupid"><?php

  $query = 'select productfamilygroupid,productfamilygroupname from productfamilygroup order by productfamilygroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['productfamilygroupid'] == $row['productfamilygroupid']) { echo '<option value="' . $row2['productfamilygroupid'] . '" SELECTED>' . $row2['productfamilygroupname'] . '</option>'; }
    else { echo '<option value="' . $row2['productfamilygroupid'] . '">' . $row2['productfamilygroupname'] . '</option>'; }
  }
  ?></select></td></tr><?php
  echo '<tr><td>Rank:</td><td><input type="text" STYLE="text-align:right" name="familyrank" value="' . $row['familyrank'] . '" size=10></td></tr>';
  ?><tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <?php echo '<input type=hidden name="productfamilyid" value="' . $_POST['productfamilyid'] . '">'; ?>
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $productfamilyname = $_POST['productfamilyname'];
  $productfamilygroupid = $_POST['productfamilygroupid'];
  $familyrank = $_POST['familyrank'];
  $query = 'update productfamily set familyrank="' . $familyrank . '",productfamilyname="' . $productfamilyname . '",productfamilygroupid="' . $productfamilygroupid . '" where productfamilyid="' . $_POST['productfamilyid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Sous-famille de produit ' . $productfamilyname . ' modifiée.</p>';
  break;

}
?>