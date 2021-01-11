<?php

###### testing barcodes
/*
$ourtype = 'ean13'; $ourtext = '7999888777728';
#$ourtype = 'code128'; $ourtext = 'hkcgheggrjk';
echo 'test barcode: <img src="showbarcode.php?barcode=' . $ourtext . '&ourtype=' . $ourtype . '" alt="Code bar ' . $ourtype . ' invalide.">';
*/
######

echo '<h2>Afficher 100 codes barre</h2>
<form method="post" action="reportwindow.php" target=_blank><table border=0 cellpadding=1 cellspacing=1>
<tr><td>Première chiffre:</td><td><input autofocus type="text" STYLE="text-align:right" name="ourtext" size=15> ex: 0000000001</td></tr>
<tr><td>Type:</td><td>Code 128</td></tr>
<tr><td>Taille:</td><td><input type="text" STYLE="text-align:right" name="height" value="60" size=5></td></tr>
<tr><td>Largeur:</td><td><input type="text" STYLE="text-align:right" name="width" value="1" size=5></td></tr>
<tr><td colspan=2><input type=hidden name="report" value="makebarcodes">
<input type="submit" value="Valider"></td></tr>
</table></form>';

?>