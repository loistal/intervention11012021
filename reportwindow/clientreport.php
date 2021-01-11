<?php

# TODO refactor

require('reportwindow/clientreport_cf.php');
require('preload/clientcategory.php');
require('preload/clientcategory2.php');
require('preload/clientcategory3.php');
require('preload/island.php');
require('preload/town.php');
require('preload/employee.php');
require('preload/clientterm.php');
require('preload/clientsector.php');

$PA['regulationzoneid'] = 'int';
$PA['bytype'] = 'int';
require('inc/readpost.php');

$clientcategoryid = $_POST['clientcategoryid'];
$clientcategory2id = $_POST['clientcategory2id']; if (!isset($clientcategory2A)) { $clientcategory2id = -1; }
$clientcategory3id = $_POST['clientcategory3id']; if (!isset($clientcategory3A)) { $clientcategory3id = -1; }
$clientsectorid = (int) $_POST['clientsectorid'];
$islandid = $_POST['islandid'];
$townid = (int) $_POST['townid'];
$employeeid = $_POST['employeeid']; if (!isset($_POST['employeeid'])) { $employeeid = -1; }
$employeeid2 = $_POST['employee2id']; if (!isset($_POST['employee2id'])) { $employeeid2 = -1; }
$clienttermid = $_POST['clienttermid'];
$blocked = $_POST['blocked'];
$showsupplier = $_POST['showsupplier'];
$orderby = $_POST['orderby'];
$email = $_POST['email'];

$title = 'Rapport clients';
if ($_SESSION['ds_purchaseaccess'] == 1)
{
  if ($showsupplier == -1) { $title .= ' / fournisseurs'; }
  if ($showsupplier == 1) { $title = 'Rapport fournisseurs'; }
}
showtitle($title);
echo '<h2>' . $title . '</h2>';

$ourparams = '';
if ($clientcategoryid >= 0) { $ourparams .= '<p><b>' . $_SESSION['ds_term_clientcategory'] . ': ' . d_output($clientcategoryA[$clientcategoryid]) . '</b></p>'; }
if ($clientcategory2id >= 0) { $ourparams .= '<p><b>' . $_SESSION['ds_term_clientcategory2'] . ': ' . d_output($clientcategory2A[$clientcategory2id]) . '</b></p>'; }
if ($clientcategory3id >= 0) { $ourparams .= '<p><b>' . $_SESSION['ds_term_clientcategory3'] . ': ' . d_output($clientcategory3A[$clientcategory3id]) . '</b></p>'; }
if ($clientsectorid >= 0) { $ourparams .= '<p><b>Secteur: ' . d_output($clientsectorA[$clientsectorid]) . '</b></p>'; }
if ($islandid >= 0) { $ourparams .= '<p><b>Île: ' . d_output($islandA[$islandid]) . '</b></p>'; }
if ($townid > 0) { $ourparams .= '<p><b>Ville: ' . d_output($townA[$townid]) . '</b></p>'; }
if ($employeeid >= 0) { $ourparams .= '<p><b>Employé ' . d_output($_SESSION['ds_term_clientemployee1']) . ': ' . d_output($employeeA[$employeeid]). '</b></p>'; }
if ($employeeid2 >= 0) { $ourparams .= '<p><b>Employé ' . d_output($_SESSION['ds_term_clientemployee2']) . ': ' . d_output($employeeA[$employeeid2]) . '</b></p>'; }
if ($clienttermid >= 0) { $ourparams .= '<p><b>Délai de paiement: ' . d_output($clienttermA[$clienttermid]) . '</b></p>'; }
if ($blocked == 1) { $ourparams .= '<p><b>Clients interdits</b></p>'; }
elseif ($blocked == 2) { $ourparams .= '<p><b>Clients non interdits</b></p>'; }
elseif ($blocked == 3) { $ourparams .= '<p><b>Comptes fermés</b></p>'; }
elseif ($blocked == 4) { $ourparams .= '<p><b>Comptes non interdits, non fermés</b></p>'; }
if ($email != '') { $ourparams .= '<p><b>Recherche email: '.d_output($email).'</b></p>'; }
if ($ourparams == '') { $ourparams = '<br>'; }
echo $ourparams; # TODO use include showparams

$fieldnum = 0;
$query = 'select fieldnum,showfield,showtitle from cf_report where reportid=? and userid=? order by fieldnum';
$query_prm = array($reportid, $_SESSION['ds_userid']);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  if ($query_result[$i]['showfield'] > 0)
  {
    $fieldnum++;
    $showfieldA[$fieldnum] = $query_result[$i]['showfield'];
    $showtitleA[$fieldnum] = $query_result[$i]['showtitle'];
    if ($showtitleA[$fieldnum] == '') { $showtitleA[$fieldnum] = $dp_fielddescrA[$showfieldA[$fieldnum]]; }
  }
}
if ($fieldnum == 0)
{
  echo '<p>Veuillez configurer les champs que vous voulez afficher.</p>';
  exit;
}

$query = 'select clientfield1,postalcode,contact2,contact3,countryid,client.townid,townname,islandname,client.clientid
,clientname,address,postaladdress,quarter,telephone,cellphone,fax,contact,clientcategoryid,clientcategory2id,clientcategory3id
,blocked,employeeid,employeeid2,clienttermid,email,concat(isclient,issupplier,isemployee,isother) as clienttype
from client,town,island
where client.townid=town.townid and town.islandid=island.islandid';
$query_prm = array();
if ($clientcategoryid >= 0) { $query .= ' and client.clientcategoryid=?'; array_push($query_prm, $clientcategoryid); }
if ($clientcategory2id >= 0) { $query .= ' and client.clientcategory2id=?'; array_push($query_prm, $clientcategory2id); }
if ($clientcategory3id >= 0) { $query .= ' and client.clientcategory3id=?'; array_push($query_prm, $clientcategory3id); }
if ($clientsectorid >= 0) { $query .= ' and client.clientsectorid=?'; array_push($query_prm, $clientsectorid); }
if ($regulationzoneid > 0) { $query .= ' and island.regulationzoneid=?'; array_push($query_prm, $regulationzoneid); }
if ($islandid > 0) { $query .= ' and town.islandid=?'; array_push($query_prm, $islandid); }
if ($townid > 0) { $query .= ' and client.townid=?'; array_push($query_prm, $townid); }
if ($employeeid >= 0) { $query .= ' and client.employeeid=?'; array_push($query_prm, $employeeid); }
if ($employeeid2 >= 0) { $query .= ' and client.employeeid2=?'; array_push($query_prm, $employeeid2); }
if ($clienttermid >= 0) { $query .= ' and client.clienttermid=?'; array_push($query_prm, $clienttermid); }
if ($blocked == 1) { $query .= ' and client.blocked>0'; }
if ($blocked == 2) { $query .= ' and client.blocked<1'; }
if ($blocked == 3) { $query .= ' and client.deleted=1'; }
else { $query .= ' and client.deleted=0'; }
if ($blocked == 4) { $query .= ' and client.blocked=0 and client.deleted=0'; }
if ($email != '') { $query .= ' and email like ?'; array_push($query_prm, '%'.$email.'%'); }
if ($_SESSION['ds_allowedclientlist'] != '') { $query .= ' and client.clientid in ' . $_SESSION['ds_allowedclientlist']; }
if ($_SESSION['ds_purchaseaccess'] != 1 && $_SESSION['ds_accountingaccess'] != 1) { $query .= ' and (client.isclient=1 or client.isemployee=1)'; }
else
{
  if ($showsupplier > -1) { $query .= ' and client.issupplier=?'; array_push($query_prm, $showsupplier); }
}
$query .= ' order by ';
if ($bytype) { $query .= 'isclient,issupplier,isemployee,isother,'; }
if ($orderby == 0) { $query .= 'clientname'; }
if ($orderby == 1) { $query .= 'clientid'; }
if ($orderby == 3)
{
  $query .= 'employeeid,clientname';
  $subtfield1 = 'employeeid';
}
require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

echo '<table class=report><thead>';
for ($i = 1; $i <= $fieldnum; $i++)
{
  echo '<th>' . $showtitleA[$i] . '</th>';
}
echo '</thead>';

for ($i = 0; $i < $num_rows; $i++)
{
  # TODO subheader here
  #require('inc/showsubheader.php');

  echo d_tr();
  for ($y = 1; $y <= $fieldnum; $y++)
  {
    $fieldname = $dp_fieldnameA[$showfieldA[$y]];
    $showfield = $row[$i][$fieldname];
    require('inc/configfield.php');
    echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp);
  }
  echo '</tr>';
  
  #subtotal here
  require('inc/showsubtotal.php');
}
//after last line: Grand total
$i = ($num_rows -1);
require('inc/showgrandtotal.php');

echo '</table>';

?>