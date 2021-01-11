<?php

echo '<h2>Rapport client';
if ($_SESSION['ds_purchaseaccess'] == 1 || $_SESSION['ds_accountingaccess'] == 1) { echo ' / fournisseur'; }
echo ':</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank><table>';

$dp_description = $_SESSION['ds_term_clientcategory']; $dp_allowall = 1;require('inc/selectitem_clientcategory.php');

$dp_description = $_SESSION['ds_term_clientcategory2']; $dp_allowall = 1;require('inc/selectitem_clientcategory2.php');

$dp_description = $_SESSION['ds_term_clientcategory3']; $dp_allowall = 1;require('inc/selectitem_clientcategory3.php');

$dp_itemname = 'clientsector'; $dp_description = 'Secteur'; $dp_allowall = 1;
require('inc/selectitem.php');

$dp_itemname = 'regulationzone'; $dp_description = 'Zone'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

$dp_itemname = 'island'; $dp_description = 'Île'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td>Ile/Ville:<td><select name="townid"><option value=-1>'. d_trad('selectall') .'</option>';
$query = 'select townid,townname,islandname from town,island where town.islandid=island.islandid order by islandname,townname';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $selected = '';          
  if ($query_result[$i]['townid'] == $rowclient['townid']){$selected = ' SELECTED';}        
  echo '<option value="' . $query_result[$i]['townid'] . '"' . $selected . '>' . d_input($query_result[$i]['islandname']) . '/' . $query_result[$i]['townname'] . '</option>'; 
}
echo '</select>';

$dp_itemname = 'employee'; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee1']; $dp_allowall = 1; $dp_iscashier = 1;
require('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee2']; $dp_allowall = 1; $dp_iscashier = 1; $dp_addtoid = 2;
require('inc/selectitem.php');

$dp_itemname = 'clientterm'; $dp_description = 'Délai de paiement'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td>Interdits:<td>
<select name="blocked">
<option value="0">'. d_trad('selectall') .'</option>
<option value="4">Non interdits, non fermés</option>
<option value="2">Non interdits</option>
<option value="1">Interdits</option>
<option value="3">Comptes fermés</option>
</select>';

if ($_SESSION['ds_purchaseaccess'] == 1 || $_SESSION['ds_accountingaccess'] == 1)
{ 
  echo '<tr><td>Clients/fournisseurs:</td><td><select name=showsupplier><option value=-1>'. d_trad('selectall') .'</option><option value=0>Clients</option><option value=1>Fournisseurs</option></select></td></tr>';
}

echo '<tr><td>Email :<td><input type="text" name="email" size="20">';
?>
<tr><td>Ranger par:</td><td>
<select name="orderby">
<option value="0">Nom</option>
<option value="1">Numéro</option>
<option value="3">Employé <?php echo $_SESSION['ds_term_clientemployee1']; ?>, Client</option></select>
<tr><td align=right><input type=checkbox name="bytype" value=1><td>Ranger d'abord par type de client
<tr><td colspan="2" align="center"><input type=hidden name="report" value="clientreport"><input type="submit" value="Valider"></td></tr>
</table></form>
<?php

require('reportwindow/clientreport_cf.php');

if (isset($_POST['configureme']))
{
  for ($i = 1; $i <= $dp_numfields; $i++)
  {
    $query = 'select showfield from cf_report where userid=? and reportid=? and fieldnum=?';
    $query_prm = array($_SESSION['ds_userid'], $reportid, $i);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      $query = 'insert into cf_report (showfield,showtitle,userid,reportid,fieldnum) values (?,?,?,?,?)';
      $query_prm = array($_POST['field'.$i], $_POST['title'.$i], $_SESSION['ds_userid'], $reportid, $i);
      require('inc/doquery.php');
    }
    else
    {
      $query = 'update cf_report set showfield=?,showtitle=? where userid=? and reportid=? and fieldnum=?';
      $query_prm = array($_POST['field'.$i], $_POST['title'.$i], $_SESSION['ds_userid'], $reportid, $i);
      require('inc/doquery.php');
    }

  }
  echo '<br>Configuration enregistrée.';
}

$query = 'select showfield,showtitle,fieldnum from cf_report where userid=? and reportid=? order by fieldnum';
$query_prm = array($_SESSION['ds_userid'], $reportid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $f = $query_result[$i]['fieldnum'];
  $showfieldA[$f] = $query_result[$i]['showfield'];
  $showtitleA[$f] = $query_result[$i]['showtitle'];
}

?>
<br><br><h2>Configuration:</h2>
<p class=alert>Le champ "<?php echo $dp_fielddescrA[20]; ?>" peut ralentir l'affichage du rapport.</p>
<form method="post" action="clients.php"><table>
<?php

for ($scrap_i = 1; $scrap_i <= $dp_numfields; $scrap_i++)
{
  echo '<tr><td align=right><select name="field' . $scrap_i . '"><option value=0></option>';
  foreach ($dp_fielddescrA as $scrap_y => $fielddescr)
  {
    echo '<option value="' . $scrap_y . '"'; if (isset($showfieldA[$scrap_i]) && $scrap_y == $showfieldA[$scrap_i]) { echo ' selected'; }
    echo '>' . $fielddescr . '</option>';
  }
  echo '</select></td><td><input type=text name="title' . $scrap_i . '" value="' . $showtitleA[$scrap_i] . '" size=20></td></tr>';
}

echo '<tr><td colspan="2" align="center"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name=configureme value=1><input type="submit" value="Valider"></td></tr>';
?></table></form>