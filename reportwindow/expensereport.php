<?php

exit;
#not used

require('preload/expensecategory.php');
require('preload/taxcode.php');
  
if ($_POST['client'] == "") { $clientid = ""; }
else
{
  $client = $_POST['client'];
  if (!isset($client)) { $client = $_GET['client']; }
  require ('inc/findclient.php');
}

if ($clientid < 1 && $_POST['client'] != "")
{
  echo '<form method="post" action="reportwindow.php"><table><tr><td>';
  require ('inc/selectclient.php');
  echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="step" value="1">';
  echo '<input type=hidden name="expensedateday" value="' . $_POST['expensedateday'] . '">';
  echo '<input type=hidden name="expensedatemonth" value="' . $_POST['expensedatemonth'] . '">';
  echo '<input type=hidden name="expensedateyear" value="' . $_POST['expensedateyear'] . '">';
  echo '<input type=hidden name="expensedate" value="' . $_POST['expensedate'] . '">'; # datepicker
  echo '<input type=hidden name="expensedatestopday" value="' . $_POST['expensedatestopday'] . '">';
  echo '<input type=hidden name="expensedatestopmonth" value="' . $_POST['expensedatestopmonth'] . '">';
  echo '<input type=hidden name="expensedatestopyear" value="' . $_POST['expensedatestopyear'] . '">';
  echo '<input type=hidden name="expensedatestop" value="' . $_POST['expensedatestop'] . '">'; # datepicker
  echo '<input type=hidden name="expensecategoryid" value="' . $_POST['expensecategoryid'] . '">';
  echo '<input type=hidden name="expensecomment" value="' . $_POST['expensecomment'] . '">';
  echo '<input type=hidden name="report" value="expensereport"><input type="submit" value="Valider"></td></tr></table></form>';
}

else
{
  $datename = 'expensedate';
  require ('inc/datepickerresult.php');
  $ourdate = $expensedate;

  $datename = 'expensedatestop';
  require ('inc/datepickerresult.php');
  $ourdatestop = $expensedatestop;
  
  $expensecategoryid = $_POST['expensecategoryid'] + 0;
  $expensecomment = $_POST['expensecomment'];

  $ourtitle = 'Dépenses '.$_SESSION['ds_customname'].' ';
  $ourtitle = $ourtitle . datefix($ourdate) . ' à ' . datefix($ourdatestop);
  showtitle($ourtitle);
  echo '<h2>' . d_output($ourtitle) . '</h2>';
  
  if ($clientid != "")
  {
    $showclientname = $clientid . ': ' . d_output($clientname);
    echo '<p><b>Client:</b> ' . $showclientname . '</p>';
  }
  
  if ($expensecategoryid > 0)
  {
    echo '<p><b>Catégorie:</b> ' . $expensecategoryA[$expensecategoryid] . '</p>';
  }
  
  if ($expensecomment != "")
  {
    echo '<p><b>Description:</b> ' . d_output($expensecomment) . '</p>';
  }
  
  echo '<table border=1 cellspacing=2 cellpadding=2>';
  echo '<tr><td><b>N<sup>o</sup> dépense</td><td><b>Date</td><td><b>Quantité</td><td><b>Montant</td><td><b>TVA</td><td><b>Total TTC</td>';
  if ($clientid == "") { echo '<td><b>Client</td>'; }
  if ($expensecategoryid == 0) { echo '<td><b>Catégorie</td>'; }
  echo '<td><b>Description</td></tr>';
  
  $ourtotal = 0;

  $query = 'select expenseid,expensedate,quantity,amount,taxcodeid,expensecatid,expensecomment,clientid from expense where expensedate>=? and expensedate<=?';
  $query_prm = array($ourdate,$ourdatestop);
  if ($clientid > 0) { $query = $query . ' and expense.clientid=?'; array_push($query_prm, $clientid); }
  if ($expensecategoryid > 0) { $query = $query . ' and expense.expensecatid=?'; array_push($query_prm, $expensecategoryid); }
  if ($expensecomment != "") { $query = $query . ' and expense.expensecomment like ?'; array_push($query_prm, '%' . $expensecomment . '%'); }

#  echo $query . '<br>';
#  var_dump($query_prm);
#  echo '<br>';

  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  $ourtotal = 0; $qtotal = 0;
  for ($i=0;$i<$num_results_main;$i++)
  {
    echo '<tr><td align=right>' . $main_result[$i]['expenseid'] . '</td><td align=right>' . datefix2($main_result[$i]['expensedate']) . '</td><td align=right>' . $main_result[$i]['quantity'] . '</td><td align=right>' . myfix($main_result[$i]['amount']) . '</td>';
    $taxcodeid = $main_result[$i]['taxcodeid'];
    echo '<td align=right>' . ($taxcodeA[$taxcodeid]+0) . '%</td>';
    $linetotal = $main_result[$i]['quantity'] * $main_result[$i]['amount'];
    $linetotal = myround($linetotal + ($linetotal * $taxcodeA[$taxcodeid] / 100));
    echo '<td align=right>' . myfix($linetotal) . '</td>';
    $ourtotal = $ourtotal + $linetotal;
    $qtotal = $qtotal + $main_result[$i]['quantity'];
    if ($clientid == "")
    {
      if ($main_result[$i]['clientid'] > 0)
      {
        echo '<td align=right>' . $main_result[$i]['clientid'] . '</td>';
      }
      else
      {
        echo '<td>&nbsp;</td>';
      }
    }
    if ($expensecategoryid == 0)
    {
      $currentexpensecategoryid = $main_result[$i]['expensecatid'];
      echo '<td align=right>' . $expensecategoryA[$currentexpensecategoryid] . '</td>';
    }
    echo '<td align=right>' . $main_result[$i]['expensecomment'] . '</td>';
    echo '</tr>';
  }
  echo '<tr><td><b>Total (' . $i . ')</td><td>&nbsp;</td><td align=right><b>' . myfix($qtotal) . '</td><td colspan=2>&nbsp;</td><td align=right><b>' . myfix($ourtotal) . '</td><td colspan=5>&nbsp;</td></tr>';
  echo '</table>';

}
?>