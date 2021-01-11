<?php

echo '<h2>Edition Code Barre</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank>';
echo '<table>';

echo '<tr><td>Du numéro: <td><input autofocus type=text STYLE="text-align:right" name="startbarcode" size=8>';
echo '<tr><td>Au numéro: <td><input type=text STYLE="text-align:right" name="stopbarcode" size=8>';
echo '<tr><td colspan=2 align="center">(Limite 100)';

echo '<tr><td colspan=2 align="center"><select name="format">
<option value="A5">A5</option>
<option value="A4">A4</option>
</select>';

echo '<tr><td>Quantité: <td><select name="orig">
<option value=1>Actuelle</option>
<option value=0>Originale</option>
</select>';

echo '<tr><td colspan="4" align="center">';
echo '<input type=hidden name="report" value="displaypalletbarcode">';
echo '<input type="submit" value="Valider">';
echo '</table></form>';

?>

 