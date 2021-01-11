<?php

echo '<h2>Badges QR pour employé(e)s</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>Numéro employé min:<td><input type=number name="min" step=1>';
echo '<tr><td>Numéro employé max:<td><input type=number name="max" step=1>';

echo '<tr><td>Afficher logo:<td><input type=checkbox name="showlogo" value=1 checked>';

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="qrbadges">
<input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';


?>