<?php

?>

<h2>Afficher connaissement:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Numéro:</td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10></td></tr>
<?php
/*
echo '<tr><td>Lignes / Page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" size=5 value="
. $_SESSION['ds_invoicelines'].
"></td></tr>';
*/
?>
<tr><td>Taille ligne:</td><td><input type="text" STYLE="text-align:right" name="itemfontsize" size=5 value="100">%</td></tr>
<tr><td colspan="2" align="center">
<?php
#if ($_SESSION['ds_userid'] == 1) { echo '<input type=hidden name="report" value="showlocalbolnewtest">'; }
#else { 
echo '<input type=hidden name="report" value="showlocalbolnew">'; 
#}
?>
<input type="submit" value="Valider"></td></tr>
</table></form>

<?php

/*

<br><br><br><br><br><br>


<h2>Afficher connaissement (VIEUX):</h2><?php
$ourfile = "reportwindow.php";
echo '<form method="post" action="' . $ourfile . '" target="_blank"><table>';
echo '<tr><td>Numéro:</td><td><input type="text" STYLE="text-align:right" name="invoiceid" size=10></td></tr>';
echo '<tr><td>Articles / page:</td><td><input type="number" min=1 name="maxlines" value=14 size=5></td></tr>';
echo '<tr><td>Bon de préparation:</td><td><input type="checkbox" value=1 name="deliveryformat"></td></tr>';

#<tr><td>Page:</td><td><input type="text" STYLE="text-align:right" name="pagenumber" size=5 value="1"></td></tr>
#<tr><td>Lignes / Page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" size=5 value="16"></td></tr>
#<tr><td>Taille ligne:</td><td><input type="text" STYLE="text-align:right" name="itemfontsize" size=5 value="100">%</td></tr>
#<tr><td>Cacher remise:</td><td><input type="checkbox" name="hidediscount" value="1"></td></tr>
echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="showlocalbol"><input type="submit" value="Valider"></td></tr>
</table></form>';

*/

?>
