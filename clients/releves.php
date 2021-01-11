<h2>Afficher Relevés:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<table><tr><td>Début:<td>
<?php
$datename = 'startdate'; $selecteddate = mb_substr($_SESSION['ds_curdate'],0,4) . '-01-01'; require('inc/datepicker.php');
echo '<tr><td>Fin:<td>';
$datename = 'stopdate'; require('inc/datepicker.php');
?>
<tr><td><?php
require('inc/selectclient.php');
?>
</td></tr>
<tr><td>Clients:</td><td><input type=text size=30 name=clientlist> (numéros séparés par espaces)</td></tr>
<tr><td align=right><input type="checkbox" name="byclientid" value="1"></td><td>Clients numéros <input type="text" STYLE="text-align:right" name="startid" size=5> à <input type="text" STYLE="text-align:right" name="stopid" size=5></td></tr>
<tr><td align=right><input type="checkbox" name="creditlimit" value="1"></td><td>N'afficher que les clients en depassement de crédit</td></tr>
<tr><td align=right><input type="checkbox" name="onlydebitors" value="1"></td><td>N'afficher que les clients débiteurs</td></tr>
<tr><td>Île:</td>
<td><select name="islandid"><option value=0><?php echo d_trad('selectall'); ?></option><?php
$query = 'select islandid,islandname from island order by islandname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['islandid'] . '">' . $row['islandname'] . '</option>';
}
?></select></td></tr>
<tr><td>Zone:</td>
<td><select name="regulationzoneid"><option value=0><?php echo d_trad('selectall'); ?></option><?php
$query = 'select regulationzonename,regulationzoneid from regulationzone order by regulationzonename';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['regulationzoneid'] . '">' . $row['regulationzonename'] . '</option>';
}
?></select></td></tr>
<tr><td>Secteur:</td>
<td><select name="clientsectorid"><option value=0><?php echo d_trad('selectall'); ?></option><?php
$query = 'select clientsectorid,clientsectorname from clientsector order by clientsectorname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['clientsectorid'] . '">' . $row2['clientsectorname'] . '</option>';
}
?></select></td></tr>
<tr><td>Employé(e) <?php echo $_SESSION['ds_term_clientemployee1']; ?>:</td>
<td><select name="employeeid"><option value="0"><?php echo d_trad('selectall'); ?></option><?php
$query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['employeeid'] == $employeeid) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
  else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
}
?></select></td></tr>
<tr><td>Employé(e) <?php echo $_SESSION['ds_term_clientemployee2']; ?>:</td>
<td><select name="employeeid2"><option value="0"><?php echo d_trad('selectall'); ?></option><?php
$query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['employeeid'] == $employeeid) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
  else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
}
?></select></td></tr>
<tr><td>Catégorie:</td>
<td><select name="clientcategoryid"><option value="0"><?php echo d_trad('selectall'); ?></option><?php
$query = 'select clientcategoryid,clientcategoryname from clientcategory order by clientcategoryname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['clientcategoryid'] == $clientcategoryid) { echo '<option value="' . $row2['clientcategoryid'] . '" SELECTED>' . $row2['clientcategoryname'] . '</option>'; }
  else { echo '<option value="' . $row2['clientcategoryid'] . '">' . $row2['clientcategoryname'] . '</option>'; }
}
?></select></td></tr>
<tr><td>Ranger par:</td><td><select name="myorderby"><option value=1>Numéro</option><option value=2>Nom</option></select></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2><b>Options</b></td></tr>
<tr><td colspan=2>Format: <select name="format"><option value=1>Par Type</option><option value=0>Par Date</option></select></td></tr>
<tr><td colspan=2><input type="checkbox" name="dontshowdebit" value="1"> Ne pas afficher le Débit</td></tr>
<tr><td colspan=2><input type="checkbox" name="dontshowcredit" value="1"> Ne pas afficher le Crédit</td></tr>
<tr><td colspan=2><input type="checkbox" name="dontshownotice" value="1"> Ne pas afficher <?php echo $_SESSION['ds_term_invoicenotice']; ?></td></tr>
<tr><td colspan=2><input type="checkbox" name="dateref" value="1"> Numéro du relevé</td></tr>
<?php
echo '<tr><td colspan=2><input type="checkbox" name="showcomments" value="1"> Afficher '.d_output($_SESSION['ds_term_reference']).'/Commentaires</td></tr>';
echo '<tr><td colspan=2><input type="checkbox" name="showtelephone" value="1"> Afficher numéros de téléphone</td></tr>';
?>
<tr><td colspan=2><input type="checkbox" name="show_paybydate" value="1"> Afficher date écheance</td></tr>
<tr><td colspan=2><input type="checkbox" name="showoperations_public" value="1"> Relevé Marché Public</td></tr>
<tr><td colspan=2><input type="checkbox" name="relevenomatched" value="1"> Ne pas afficher les lettrés</td></tr>
<tr><td colspan=2><input type="checkbox" name="relevenopayment" value="1"> Ne pas afficher les paiements</td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="releves">
<input type=hidden name="usedefaultstyle" value="1"><input type=hidden name="showoperations" value="1">
<input type="submit" value="Valider"></td></tr>
</table></form><?php

?>