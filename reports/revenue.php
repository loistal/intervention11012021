<?php

$BY_TOTAL = 1;
$BY_DETAILEDCLIENTCATEGORY = 5;
$BY_DETAILEDCLIENTCATEGORYGROUP = 6;
$BY_EMPLOYEE = 3;
$BY_PRODUCT = 4;

$PRODUCTBY_NOORDER = 0;
$PRODUCTBY_FAMILY = 1;
$PRODUCTBY_DEPARTMENT = 2;
$PRODUCTBY_TYPE = 3;

?>
<h2>Chiffre d'Affaire:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Début:</td><td colspan=4><?php
$datename = 'start'; $selecteddate = $_SESSION['ds_curdate'];
require('inc/datepicker.php');
?></td></tr>
<tr><td>Fin:</td><td colspan=4><?php
$datename = 'stop'; $selecteddate = $_SESSION['ds_curdate'];
require('inc/datepicker.php');
?></td></tr>
<tr><td><?php $dp_colspan=4;require('inc/selectclient.php'); ?></td></tr>
<?php
$dp_description = d_output($_SESSION['ds_term_clientcategory']); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; $dp_colspan = 4;
$dp_itemname = 'clientcategory'; require('inc/selectitem.php');

$dp_description = d_output($_SESSION['ds_term_clientcategory2']); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; $dp_colspan = 4;
$dp_itemname = 'clientcategory2'; require('inc/selectitem.php');
?>
<?php $dp_itemname = 'clientcategorygroup2'; $dp_description = d_trad('clientcategorygroup2'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;$dp_colspan=4;
require('inc/selectitem.php');?>
<?php
if ($_SESSION['ds_usenotice'])
{
  echo '<tr><td>Exclure ' . $_SESSION['ds_term_invoicenotice'] . ':</td><td colspan=4><input type=checkbox value=1 name="excludenotice"></td></td></tr>';
}
?>
<tr><td>Exclure référence:</td><td colspan=4><input type=text STYLE="text-align:right" size=20 name="reference"></td></tr>
<?php $dp_itemname = 'town'; $dp_description = 'Commune'; $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;$dp_colspan=4;
require('inc/selectitem.php');
$dp_itemname = 'island'; $dp_description = d_trad('island'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;$dp_colspan=4;
require('inc/selectitem.php');
$dp_itemname = 'user'; $dp_description = 'Utilisateur'; $dp_allowall = 1; $dp_noblank = 1; $dp_colspan=4;
require('inc/selectitem.php');
?>
<tr><td colspan=5><input type=checkbox value=1 name="annual"> Rapport annuel par mois</td></tr>
<tr><td colspan=5><input type=checkbox value=1 name="totalonleft"> Total à gauche</td></tr>
<!-- TODO how does showclientemployee work? remove? -->
<tr><td colspan=5><input type=checkbox value=1 name="showclientemployee"> Afficher l'employé qui s'occupe de chaque client</td></tr>

<tr><td colspan=5>&nbsp;</td></tr>

<tr><td valign=top><b>Par:</b>
<td><input type=radio name="reporttype" value='<?php echo $BY_TOTAL;?>' checked>Total<td colspan=4><input type=checkbox value=1 name="byhour"> Par heure de saisie
<tr><td></td><td colspan=2><input type=radio name="reporttype" value='<?php echo $BY_DETAILEDCLIENTCATEGORY;?>'>
<?php echo d_output($_SESSION['ds_term_clientcategory']); ?>
<tr><td></td><td><input type=radio name="reporttype" value='<?php echo $BY_DETAILEDCLIENTCATEGORYGROUP;?>'>
<?php $dp_itemname = 'clientcategorygroup'; $dp_description = d_trad('clientcategorygroup'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;$dp_colspan=3;$dp_notr=1;$dp_notddescr=1;
require('inc/selectitem.php');?>
</tr>
<tr><td></td><td colspan=4><input type=radio name="reporttype" value='<?php echo $BY_EMPLOYEE;?>'> Employé (facture)</td></tr>
<tr><td></td><td><input type=radio name="reporttype" value='<?php echo $BY_PRODUCT;?>'> Produit</td><td colspan=3><input type=checkbox value=1 name="byquantity"> Quantités</td></tr>
<tr><td colspan=2></td><td>Ranger par:</td><td colspan=2>
	<select name="porderby">
		<option value=<?php echo $PRODUCTBY_NOORDER;?>></option>
		<option value=<?php echo $PRODUCTBY_FAMILY;?>>Famille</option>
		<option value=<?php echo $PRODUCTBY_TYPE;?>>Type de produit</option>
</td></tr><?php
echo '<tr><td colspan=2><td>Fournisseur:<td><select name="supplierid"><option value=-1>'.d_trad("selectall").'</option>';
    $query = 'select clientid,clientname from client where deleted=0 and issupplier=1 order by clientname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<option value="' . $query_result[$i]['clientid'] . '"';
      echo '>' . d_output(d_decode($query_result[$i]['clientname'])) . '</option>';
    }
    echo '</select>';?> <input type=checkbox value=1 name="excludesupplier"> Exclure</td></tr>
<tr><td colspan=2></td><td>Marque:</td><td colspan=2><input type=text STYLE="text-align:right" size=20 name="brand">
<tr><td colspan=2></td><?php $dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 0; $dp_noblank=0; $dp_notr=1; $dp_colspan =2; require('inc/selectitem.php');?></td></tr>
<tr><td colspan=2></td><?php $dp_itemname='productfamilygroup';$dp_description = d_trad('family'); $dp_allowall = 0; $dp_noblank=0; $dp_notr=1; $dp_colspan =2; require('inc/selectitem_productfamilygroup.php');?></td></tr>
<tr><td colspan=2></td><?php $dp_itemname='productfamily';$dp_description = d_trad('subfamily'); $dp_allowall = 0; $dp_noblank=0; $dp_notr=1; $dp_colspan =2; require('inc/selectitem_productfamily.php');?></td></tr>

<?php
$query = 'select productid,productname from product where exludefromvatreport=1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<tr><td colspan=2></td><td colspan=3><input type=checkbox value=1 name="exludefromvatreport"> Exclure produits';
  $testvar = $num_results - 1;
  for ($i=0;$i<$num_results;$i++)
  {
    echo ' ' . $query_result[$i]['productid'] . ' ' . d_output(d_decode($query_result[$i]['productname']));
    if ($i < $testvar) { echo ','; }
  }
	echo '</td></tr>';
}
?>
<tr><td colspan=5>&nbsp;</td></tr>
<tr><td>Graphique:<td colspan=10>
 <select name=graph>
  <option value=0>Aucun</option>
  <option value=1>Multilignes (seulement pour rapport annuel)</option>
  <option value=2>Histogrammes verticaux</option>
  <option value=3>Histogrammes horizontaux</option>
  <option value=4>Diagramme circulaire</option>
 </select>
<span class="alert">Non disponible pour "Total"</span>
<tr><td colspan=5>&nbsp;</td></tr>
<tr><td colspan="5" align="center">
<input type=hidden name="report" value="revenue">
<input type="submit" value="Valider"></td></tr></table></form>
<br>
<p>Ce rapport est hors TVA, par contre les remises et promotions sonts INCLUS</p>