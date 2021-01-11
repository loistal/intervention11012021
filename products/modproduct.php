<?php
#product/modproduct
echo '
<form method="post" action="products.php">
<h2>Modifier Produit</h2>
<table>
<tr><td>';
require('inc/selectproduct.php');
echo '<tr><td colspan="2" align="center"><input name="Valider" type="submit" value="Valider">
      <input type=hidden name="modify" value="1"><input type=hidden name="productsmenu" value="addproduct">
</form></table>';
?>