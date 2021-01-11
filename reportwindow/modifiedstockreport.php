<?php
require('preload/modifiedstockreason.php'); $modifiedstockreasonA[0] = '';
require('preload/unittype.php');

$total = $total2 = 0;
$tablename = 'modifiedstock';

$PA['foruserid'] = 'int';
require('inc/readpost.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$date = $startdate;
if ($stopdate < $date) { $stopdate = $date; }
$product = $_POST['product']; require('inc/findproduct.php'); if (!isset($productid)) { $productid = 0; }
$userid = (int) $_POST['userid'];
$productfamilygroupid = (int) $_POST['productfamilygroupid'];
if ($foruserid > 0) { $tablename = 'modifiedstock_user'; require('preload/user.php'); }

showtitle('Rapport des ajustements stock');

echo '<h2>Ajustements ';
echo datefix($date) . ' à ' . datefix($stopdate) . '</h2>';

if ($foruserid)
{
  echo 'Stock pour utilisateur : '.$userA[$foruserid];
}

echo '<table class="report" border=1 cellspacing=2 cellpadding=2>';
$query = 'select modifiedstockreasonid,netvalue,'.$tablename.'.productid,productname,numberperunit,netweightlabel,netchange,changedate,changetime,initials,modifiedstockcomment,unittypeid
from '.$tablename.',product,usertable';
if ($productfamilygroupid > 0) { $query .= ',productfamily'; }
$query .= ' where '.$tablename.'.productid=product.productid and '.$tablename.'.userid=usertable.userid and changedate>=? and changedate<=?';
$query_prm = array($date,$stopdate);
if ($userid > 0) { $query = $query . ' and usertable.userid=?'; array_push($query_prm,$userid);  }
if ($foruserid > 0) { $query .= ' and foruserid=?'; array_push($query_prm,$foruserid);  }
if ($productid > 0) { $query .= ' and '.$tablename.'.productid=?'; array_push($query_prm,$productid); }
if ($productfamilygroupid > 0) { $query .= ' and product.productfamilyid=productfamily.productfamilyid and productfamilygroupid=?'; array_push($query_prm,$productfamilygroupid); }
$orderby = ' order by initials,changedate,modifiedstockid';
if ($_POST['myorder'] == 1) { $orderby = ' order by changedate,initials,modifiedstockid'; }
if ($_POST['myorder'] == 2) { $orderby = ' order by '.$tablename.'.productid,modifiedstockid'; }
$query .= $orderby;
require('inc/doquery.php');
echo '<tr><td><b>Utilisateur</td><td><b>Date</td><td><b>Heure<td><b>Produit</td><td><b>Ajustement</td><td><b>Valeur</td></td><td><b>Raison</td><td><b>Infos</td></tr>';#<td><b>Dépôt-vente
for ($y=0; $y < $num_results; $y++)
{
  $row2 = $query_result[$y];
  $dmp = $unittype_dmpA[$row2['unittypeid']];
  $productname = $row2['productid'] . ': ' . d_decode($row2['productname']) . ' ';
  $numberperunit = $row2['numberperunit']; if ($numberperunit < 1) { $numberperunit = 1; }
  if ($_SESSION['ds_useunits'] && $numberperunit > 1) { $productname = $productname . $numberperunit . ' x '; }
  $productname = $productname . $row2['netweightlabel'];
  $modif = floor(d_abs($row2['netchange']/$numberperunit))/$dmp; if ($row2['netchange'] < 0) { $modif = 0 - $modif; }
  $modifrest = $row2['netchange']%$numberperunit;
  if ($modifrest) { $modif = $modif . ' <font size=-1>' . $modifrest . '</font>'; }
  echo '<tr><td>' . d_output($row2['initials']) . '</td><td>' . datefix($row2['changedate']) . '</td>
  <td>' . substr($row2['changetime'],0,5) . '</td><td>' . d_output($productname) . '</td>
  <td class="right">' . $modif . '</td><td class="right">' . myfix($row2['netvalue']) . '</td>';
  echo '<td>' . $modifiedstockreasonA[$row2['modifiedstockreasonid']] . '</td><td>' . $row2['modifiedstockcomment'] . '</td></tr>';
  $total += ($row2['netchange']/$numberperunit)/$dmp;
  $total2 += $row2['netvalue'];
}
if ($num_results)
{
  /*
  $showtotal = floor(d_abs($total/$numberperunit))/$dmp; if ($total < 0) { $showtotal = '-' . $showtotal; }
  $totalrest = abs($total%$numberperunit);
  if ($totalrest) { $showtotal = $showtotal . ' <font size=-1>' . $totalrest . '</font>'; }
  */
  echo '<tr><td colspan=4><b>Total</td>
  <td align=right>' . $total . '</td>
  <td align=right>' . myfix($total2) . '</td>
  <td colspan=10></td></tr>';
}
echo '</table>';
?>