<?php
switch($currentstep)
{

  # Enter data
  case 0:
  ?><h2>Ajouter entrepôt:</h2>
  <form method="post" action="admin.php">
  <table>
  <tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="warehousename" size=30></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 1:
  $warehousename = $_POST['warehousename'];
  $query = 'insert into warehouse (warehousename) values ("' . $warehousename . '")';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p>Entrepôt ' . $warehousename . ' ajouté.</p>';
  break;

}
?>