<?php

echo '<h2>Afficher facture '.$_SESSION['ds_customname'].':</h2>';

#$ourfile = "customprintwindow.php";
$ourfile = 'printwindow.php';
echo '<form method="post" action="' . $ourfile . '" target="_blank"><table>';
?><tr><td>Numéro:</td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10></td></tr>
<tr><td>Page:</td><td><input type="text" STYLE="text-align:right" name="pagenumber" size=5 value="1"></td></tr>
<tr><td>Lignes / Page:</td><td><input type="text" STYLE="text-align:right" name="linesperpage" size=5 value="
<?php
echo $_SESSION['ds_invoicelines'];
?>
"></td></tr>
<tr><td>Taille ligne:</td><td><input type="text" STYLE="text-align:right" name="itemfontsize" size=5 value="100">%</td></tr>
<tr><td>Cacher remise:</td><td><input type="checkbox" name="hidediscount" value="1"></td></tr>
<!-- <tr><td>Taille ligne:</td><td><select name="itemfontsize"><option value=100>100</option><option value=75>75</option><option value=70>70</option><option value=60>60</option><option value=50>50</option><option value=45>45</option><option value=40>40</option><option value=25>25</option><option value=125>125</option><option value=90>90</option><option value=80>80</option></select></td></tr> -->
<?php
echo '<tr><td>Décaler:</td><td><select name="offset"><option value=0></option>';
$testvar = $_SESSION['ds_vaimato_decaler'];
for ($i=-10;$i>=-100;$i-=10)
{
  echo '<option value="'.$i.'"';
  if ($i == $testvar) { echo ' selected'; }
  echo '>'.$i.'</option>';
}
echo '</select></td></tr>';
if ($_SESSION['ds_allowinvoiceshare'] == 1)
{
 echo '<tr><td>Partager:</td><td><input type=checkbox name="shareinvoice" value=1></td></tr>';
}
?>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="showinvoice">
<input type=hidden name="showcustom" value="1">
<input type="submit" value="Valider"></td></tr>
</table></form><?php

?>