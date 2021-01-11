<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Ajouter département de produit:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="productdepartmentname" size=30></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $productdepartmentname = $_POST['productdepartmentname'];
  $query = 'insert into productdepartment (productdepartmentname) values ("' . $productdepartmentname . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Département de produit ' . $productdepartmentname . ' ajouté.</p>';
  break;

}
?>