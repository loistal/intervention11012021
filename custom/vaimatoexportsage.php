<?php

#error_reporting(E_ALL);
#ini_set('display_errors', 1);

# $_SESSION['ds_showsqldebug'] = 1; #set to zero at end of file

switch($_SESSION['ds_step'])
{

  # Confirm
  case 0:
  ?><h2>Créer fichier à importer dans SAGE</h2>
 
  <form method="post" action="custom.php"><table>
  <?php
  echo '<tr><td>Date:</td><td>';
  $datename = 'startdate';
  require('inc/datepicker.php');
  echo '</td></tr>';

  ?>
  <tr><td colspan=2>
  <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="tosage3">
  <input type="submit" value="Créer fichier SAGE"></td></tr>
  </table></form>

  <pre>
  
   Procédure d'export:
  1. Facture
  Tag "Location" exclu

  2. Paiement
  Catégorie "Location" exclu
  Commentaire "Prélèvement anticipé" exclu

  3. Ecritures
  Sans exceptions
  
  
  # Account Number definitions
  #les produits se mets dans le compte defini dans "Execption comptable:"
  $anclient = '411000';
  $antva5 = '445711';
  $antva10 = '445713';
  $antva16 = '445712';
  $antva13 = '445713';
  $an_cash = '580000';
  $an_cheque_etc = '580000';
  $an_compensation = '411000';
  $an_compensation_c = '708101';
  $an_perte = '416000';
  $an_perte_c = '701760';
  $an_salaire = '421000';
  
  #Journal definitions
  $VTcomptant = '730';
  $VTother = '700';
  $ENC = 'ENC';
  $OD = '909';
  
  

  </pre>

  <?php
  break;

  # Make file
  case 1:
  ini_set('max_execution_time', 600);
  $sep = chr(9); # tab
  $endline = chr(13) . chr(10);
  
  $limit = 10000;
  $datename = 'startdate'; require('inc/datepickerresult.php');

  echo '<h2>Export SAGE '.datefix($startdate).'</h2>';
  
  $query = 'set SQL_BIG_SELECTS=1;';
  $query_prm = array();
  require('inc/doquery.php');
  
  /*
  $anvente0 = '701300';
  $anvente5 = '701000';
  $anvente10 = '701600';
  $anvente13 = '708200';
  $anvente16 = '701200';
  $anventepid9 = '419101';
  $anventepid10 = '701100';
  $anventepid19 = '419100';
  $anventepid63 = '419102';
  $anventep_fret = '701105'; # Produit N°45+59+60+61
  */

  # Account Number definitions
  #les produits se mets dans le compte defini dans "Execption comptable:"
  $anclient = '411000';
  $antva5 = '445711';
  $antva10 = '445713';
  $antva16 = '445712';
  $antva13 = '445713';
  $an_cash = '580000';
  $an_cheque_etc = '580000';
  $an_compensation = '411000';
  $an_compensation_c = '708101';
  $an_perte = '416000';
  $an_perte_c = '701760';
  $an_salaire = '421000';
  
  #Journal definitions
  $VTother = '700';
  $ENC = 'ENC';
  $OD = '909';

  $filename = 'customfiles/sage' . date("Y_m_d_H_i_s") . '.txt';
  $file = fopen($filename, "w");
  if (!$file) { echo "Cannot create the file!<br>"; exit; }
  
  $writebuffer = '';
  
  ### invoices
  $tva5 = 0; $tva10 = 0; $tva16 = 0; /* $total0 = 0; $total5 = 0; $total10 = 0; $total16 = 0; */
  $tva5c = 0; $tva10c = 0; $tva16c = 0; /*$total0c = 0; $total5c = 0; $total10c = 0; $total16c = 0;*/
  $tva13 = 0; $tva13c = 0; /*$total13 = 0; $total13c = 0;*/
  /*
  $totalpid9 = 0; $totalpid9c = 0;
  $totalpid10 = 0; $totalpid10c = 0;
  $totalpid19 = 0; $totalpid19c = 0;
  $totalpid63 = 0; $totalpid63c = 0;
  $totalpfret = 0; $totalpfretc = 0;
  */
  $p_totalA = array(); $p_total_cA = array();
  
  $p_accountA = array();
  $query = 'select productid,acnumber from product,accountingnumber where product.accountingnumberid=accountingnumber.accountingnumberid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $pid = (int) $query_result[$i]['productid'];
    $p_accountA[$pid] = $query_result[$i]['acnumber'];
  }

  $VT = $VTother;
  $anmain = $anclient;
  $query = 'select isreturn,invoiceitemhistory.productid,lineprice,linevat,linetaxcodeid,reference,invoicehistory.invoiceid,invoicehistory.clientid,date_format(accountingdate,"%d%m%y") as showdate,clientname,date_format(paybydate,"%d%m%y") as paybydate
  from invoiceitemhistory,invoicehistory,client,product
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid and invoiceitemhistory.productid=product.productid
  and cancelledid=0 and isnotice=0 and confirmed=1 and invoicetagid<>2 and invoiceprice>0 and product.accountingnumberid>0
  and accountingdate=?
  and invoicehistory.exported=0
  order by accountingdate,invoiceid';
  $query_prm = array($startdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $isreturn = (int) $main_result[$i]['isreturn']; # invoice debit, avoir credit
    # detailed by client
    $writebuffer .= $VT . $sep;
    $writebuffer .= $row['showdate'] . $sep;
    $writebuffer .= 'FA' . $row['invoiceid'] . $sep;
    $writebuffer .= 'FA' . $row['invoiceid'] . $sep;
    $writebuffer .= $anmain . $sep;
    $writebuffer .= $row['clientid'] . $sep;
    $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($row['clientname']))),0,35) . $sep;
    if ($isreturn)
    {
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['lineprice'] + $row['linevat']) . $sep; # credit
    }
    else
    {
      $writebuffer .= myround($row['lineprice'] + $row['linevat']) . $sep; # debit
      $writebuffer .= $sep; # credit
    }
    $writebuffer .= $row['paybydate'] . $sep;
    $writebuffer .= $endline;
    if ($isreturn)
    {
      $id = (int) $row['productid'];
      $p_totalA[$id] += $row['lineprice'];
      /*
      if ($row['productid'] == 10) { $totalpid10 += $row['lineprice']; $tva5 += $row['linevat']; }
      elseif ($row['productid'] == 9) { $totalpid9 += $row['lineprice']; }
      elseif ($row['productid'] == 19) { $totalpid19 += $row['lineprice']; }
      elseif ($row['productid'] == 63) { $totalpid63 += $row['lineprice']; }
      elseif ($row['productid'] == 45 || $row['productid'] == 59 || $row['productid'] == 60 || $row['productid'] == 61) { $totalpfret += $row['lineprice']; }
      */
      if ($row['linetaxcodeid'] == 2) { $tva5 += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 3) { $tva10 += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 4) { $tva16 += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 5) { $tva13 += $row['linevat']; }
    }
    else
    {
      $id = (int) $row['productid'];
      $p_total_cA[$id] += $row['lineprice'];
      /*
      if ($row['productid'] == 10) { $totalpid10c += $row['lineprice']; $tva5c += $row['linevat']; }
      elseif ($row['productid'] == 9) { $totalpid9c += $row['lineprice']; }
      elseif ($row['productid'] == 19) { $totalpid19c += $row['lineprice']; }
      elseif ($row['productid'] == 63) { $totalpid63c += $row['lineprice']; }
      elseif ($row['productid'] == 45 || $row['productid'] == 59 || $row['productid'] == 60 || $row['productid'] == 61) { $totalpfretc += $row['lineprice']; }
      */
      if ($row['linetaxcodeid'] == 2) { $tva5c += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 3) { $tva10c += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 4) { $tva16c += $row['linevat']; }
      elseif ($row['linetaxcodeid'] == 5) { $tva13c += $row['linevat']; }
    }
  }
  $lastshowdate = $row['showdate'];
  
  #subtotal by day
  $totaltext = 'total';
  if ($tva5 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva5 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($tva5) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva5c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva5 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($tva5c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva10 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($tva10) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva10c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($tva10c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva16 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva16 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($tva16) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva16c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva16 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($tva16c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva13 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva13 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($tva13) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($tva13c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $antva13 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($tva13c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  /*
  if ($total0 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente0 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($total0) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total0c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente0 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($total0c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total5 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente5 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($total5) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total5c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente5 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($total5c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total10 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($total10) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total10c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($total10c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total16 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente16 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($total16) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total16c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente16 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($total16c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total13 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente13 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($total13) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($total13c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anvente13 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($total13c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  */
  foreach ($p_totalA as $id => $total)
  {
    if ($total > 0)
    {
      $writebuffer .= $VT . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= $p_accountA[$id] . $sep;
      $writebuffer .= $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= myround($total) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
  }
  foreach ($p_total_cA as $id => $total)
  {
    if ($total > 0)
    {
      $writebuffer .= $VT . $sep;
      $writebuffer .= $lastshowdate . $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= $p_accountA[$id] . $sep;
      $writebuffer .= $sep;
      $writebuffer .= $totaltext . $lastshowdate . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($total) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= $endline;
    }
  }
  /*
  if ($totalpid10 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($totalpid10) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid10c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid10 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($totalpid10c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid9 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid9 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($totalpid9) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid9c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid9 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($totalpid9c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid19 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid19 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($totalpid19) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid19c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid19 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($totalpid19c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid63 > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid63 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($totalpid63) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpid63c > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventepid63 . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($totalpid63c) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpfret > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventep_fret . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= myround($totalpfret) . $sep; # debit
    $writebuffer .= $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  if ($totalpfretc > 0)
  {
    $writebuffer .= $VT . $sep;
    $writebuffer .= $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $anventep_fret . $sep;
    $writebuffer .= $sep;
    $writebuffer .= $totaltext . $lastshowdate . $sep;
    $writebuffer .= $sep; # debit
    $writebuffer .= myround($totalpfretc) . $sep; # credit
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  */
  
  # payments
  $query = 'select reimbursement,paymentcomment as reference,paymentid,payment.clientid,clientname,date_format(paymentdate,"%d%m%y") as showdate,value,bankid,depositbankid,paymenttypeid,forinvoiceid,chequeno
  from payment,client
  where payment.clientid=client.clientid and paymentdate=?
  and payment.exported=0 and paymentcategoryid<>2 and paymentcomment not like "%Prélèvement anticipé%"
  order by paymentdate,paymentid';
  $query_prm = array($startdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $reimbursement = $row['reimbursement'];
    $journalcode = $ENC;
    
    $annumber = $an_cheque_etc;
    $annumber_client = $anclient;
    
    if ($row['paymenttypeid'] == 1) { $annumber = $an_cash; }
    elseif ($row['paymenttypeid'] == 8) { $annumber = $an_compensation; $annumber_client = $an_compensation_c; }
    elseif ($row['paymenttypeid'] == 7) { $annumber = $an_perte; $annumber_client = $an_perte_c; }
    elseif ($row['paymenttypeid'] == 7) { $annumber = $an_salaire; }
    
    if ($reimbursement)
    {
      # payment itself, debit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      if ($row['forinvoiceid'] > 0) { $writebuffer .= 'FA' . $row['forinvoiceid'] . $sep; }
      else { $writebuffer .= $sep; }
      $writebuffer .= $annumber_client . $sep;
      $writebuffer .= $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($row['clientname']))),0,35) . $sep;
      $writebuffer .= myround($row['value']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
      # counterpart, credit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      $writebuffer .= $sep; # 'P' . $row['paymentid'] . 
      $writebuffer .= $annumber . $sep;
      $writebuffer .= $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($row['clientname']))),0,35) . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['value']) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
    }
    else
    {
      # payment itself, credit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      if ($row['forinvoiceid'] > 0) { $writebuffer .= 'FA' . $row['forinvoiceid'] . $sep; }
      else { $writebuffer .= $sep; }
      $writebuffer .= $annumber_client . $sep;
      $writebuffer .= $row['clientid'] . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($row['clientname']))),0,35) . $sep;
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['value']) . $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
      
      # counterpart, debit
      $writebuffer .= $journalcode . $sep;
      $writebuffer .= $row['showdate'] . $sep;
      $writebuffer .= 'P' . $row['paymentid'] . $sep;
      $writebuffer .= $sep; # 'P' . $row['paymentid'] . 
      $writebuffer .= $annumber . $sep;
      $writebuffer .= $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($row['clientname']))),0,35) . $sep;
      $writebuffer .= myround($row['value']) . $sep; # debit
      $writebuffer .= $sep; # credit
      $writebuffer .= $sep;
      $writebuffer .= substr($row['chequeno'],0,17) . $endline;
    }
  }
  
  # adjustment (écriture)
  $query = 'select adjustment.adjustmentgroupid,adjustment.adjustmentid,date_format(adjustmentdate,"%d%m%y") as showdate,value,debit,acnumber,referenceid,adjustmentcomment
  from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and
  adjustmentdate=? and exported=0
  order by adjustmentdate,adjustment.adjustmentgroupid,adjustment.adjustmentid';
  $query_prm = array($startdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    # one adjustment, debit or credit
    $writebuffer .= $OD . $sep;
    $writebuffer .= $row['showdate'] . $sep;
    $writebuffer .= 'OL' . $row['adjustmentid'] . $sep;
    $writebuffer .= 'OD' . $row['adjustmentgroupid'] . $sep;
    $writebuffer .= $row['acnumber'] . $sep;
    if ($row['acnumber'] == '411000' || $row['acnumber'] == '401000') { $writebuffer .= $row['referenceid'] . $sep; } #'C' . 
    else { $writebuffer .= $sep; }
    $writebuffer .= substr(str_replace ($sep, ' ', trim($row['adjustmentcomment'])),0,35) . $sep;
    if ($row['debit'] == 1)
    {
      $writebuffer .= myround($row['value']) . $sep; # debit
      $writebuffer .= $sep; # credit
    }
    else
    {
      $writebuffer .= $sep; # debit
      $writebuffer .= myround($row['value']) . $sep; # credit
    }
    $writebuffer .= $sep;
    $writebuffer .= $endline;
  }
  
  $writebuffer = str_replace ('é', 'e', $writebuffer);
  $writebuffer = str_replace ('è', 'e', $writebuffer);
  $writebuffer = str_replace ('à', 'a', $writebuffer);
  $writebuffer = str_replace ('ç', 'c', $writebuffer);
  $writebuffer = str_replace ('ï', 'i', $writebuffer);
  $writebuffer = str_replace (chr(195), 'e', $writebuffer);
  $writebuffer = str_replace (chr(169), '', $writebuffer);
  fwrite($file, $writebuffer);
  fclose($file);

  echo '<br><br><p>Fichier <a href="customfiles/' . basename($filename) . '"><font color=blue><i>' . basename($filename) . '</i></font></a> créé.</p>';
  ?><p>- Cliquer sur le bouton droit de la souris</p>
  <p>- Enregistrer la cible sous...</p><?php
  
  $query = 'update invoicehistory set exported=1 where accountingdate=?';
  $query_prm = array($startdate);
  require('inc/doquery.php');
  
  $query = 'update payment set exported=1 where paymentdate=?';
  $query_prm = array($startdate);
  require('inc/doquery.php');
  
  $query = 'update adjustmentgroup set exported=1 where adjustmentdate=?';
  $query_prm = array($startdate);
  require('inc/doquery.php');

  break;

}

# $_SESSION['ds_showsqldebug'] = 0;

?>