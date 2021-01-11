<?php
echo '<h2>Stock par Produit </h2>';
echo '<form method="post" action="reportwindow.php" target=_blank>';
echo '<table>';

echo '<tr><td>'; require('inc/selectproduct.php');

echo '<tr><td colspan="2" align="center">';
echo '<input type=hidden name="report" value="foundplacement">';
echo '<input type="submit" value="Valider">';
echo '</table></form>';
?>