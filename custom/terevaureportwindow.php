<?php

# Build web page

$reportwindow = 1;
require ('inc/top.php');

$report = $_POST['report'];
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }
switch($report)
{
  case 'export':
  
  # code journal "VT" etc
  # date
  # num piece
  # réf
  # num compte
  # num tiers
  # libelle
  # déb
  # créd
  
  $sep = ";";
  $eol = "\r\n";

  $PA['startdate'] = 'date';
  $PA['stopdate'] = 'date';
  require('inc/readpost.php');

  echo '<pre>';
  
  $query = 'select adjustmentgroup.accounting_simplifiedid,adjustmentgroup.adjustmentgroupid,userid,adjustmentdate,adjustmentcomment
  ,reference,debit,value,referenceid,adjustment.accountingnumberid,reconciliationid,matchingid,acnumber,reconciliationid,adjustmentid
  from adjustmentgroup,adjustment,accountingnumber
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.accountingnumberid=accountingnumber.accountingnumberid';
  $query .= ' and adjustmentdate>=? and adjustmentdate<=? and adjustmentgroup.deleted=0 and value>0';
  $query_prm = array($startdate, $stopdate);
  $query .= ' order by adjustmentdate,adjustmentgroupid,debit desc,acnumber';
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    # code journal "VT" etc
    if ($query_result[$i]['adjustmentcomment'] == 'Facture' || $query_result[$i]['adjustmentcomment'] == 'Avoir') { echo 'Vente',$sep; }
    elseif ($query_result[$i]['adjustmentcomment'] == 'Paiement') { echo 'ENC',$sep; }
    else { echo 'Autre',$sep; }
    # date
    echo str_replace("-", "/", $query_result[$i]['adjustmentdate']),$sep;
    # num piece
    echo $query_result[$i]['adjustmentid'],$sep;
    # réf
    echo $query_result[$i]['reference'],$sep;
    # num compte
    echo $query_result[$i]['acnumber'].'0',$sep;
    # num tiers
    echo $query_result[$i]['referenceid'],$sep;
    # libelle
    echo $query_result[$i]['adjustmentcomment'],$sep;
    # déb # cred
    if ($query_result[$i]['debit'])
    {
      echo (int)$query_result[$i]['value'],$sep;
    }
    else
    {
      echo $sep,(int)$query_result[$i]['value'];
    }
    echo $eol;
  }
  
  echo '</pre>';
  
  break;
  
  default:

  break;
}

require ('inc/bottom.php');

?>


