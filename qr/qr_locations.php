<?php

echo '<h2>Sites QR</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>Filtrer sur le nom du lieu:<td><input autofocus type=text name="filter">';

echo '<tr><td>'; require('inc/selectclient.php');

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="qr_locations">
<input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

?>