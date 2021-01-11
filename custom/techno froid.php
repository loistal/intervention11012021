<?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');
echo '<br>';

if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
else { $custommenu = $_GET['custommenu']; }

# table
?>
<div id="selectactionbar">
<div class="selectaction">
&nbsp; <a href="custom.php?custommenu=tosage">Export SAGE</a><br>
</div><br>
<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

# Go to the menuitem
switch($custommenu)
{

  case 'tosage':
  $step = (int) $_POST['step'];
  switch($step)
  {

    # Confirm
    case 0:
    ?><h2>Créer fichier à importer dans SAGE</h2>
   
    <form method="post" action="custom.php"><table>
    <?php
    echo '<tr><td>Date:</td><td>'; echo 'test facture 5 uniqement';
    #$datename = 'startdate';
    #require('inc/datepicker.php');
    echo '</td></tr>';
    ?>
    <tr><td colspan=2>
    <input type=hidden name="step" value="1"><input type=hidden name="custommenu" value="tosage">
    <input type="submit" value="Créer fichier SAGE"></td></tr>
    </table></form>
    <pre>
    # Assumptions
    1. "Montant travaux suivant devis" only appears on invoices with a single product line
    
    # Account Number definitions
    $anclient = '411000';
    $antravaux = '611000'; # total on 'travaux suivant devis'
    $ancreditmont = '701100';
    $anvente13 = '701101'; # vs 706800 on model 4    vs 706100 on model 4 and 5
    $antva13 = '445713'; # vs 665713 on model 5
    $anvente16 = '707100';
    $anavance = '419100'; # avance de démarrage (vat NOT calculated, see model 1), also deductions "avance de démarrage"
    $anasTRC = '616400'; # deduction: l’assurance TRC à enregistrer TTC en compte
    $anprorata = '604200'; # le compte prorata à enregistrer en HT
    $anproratatva = '445613'; # le compte prorata à enregistrer en TVA
    # also see model 2
    # Chacun des comptes de produits (commençant par 7) et des comptes de charges (commençant par 6) doit être rattaché à un code analytique (indispensable pour importer les données dans SAGE).
    
    #Journal definitions
    $VT = 'VTE';
    </pre>
<!--
    <pre>
     Procédure d'export:
    1. Facture "Comptants"
    Factures avec paiement meme date que facture et "pour facture numéro"
    Tag "Location" exclu
    Tva 13% exlu

    2. Facture autres
    Factures de point "1. Facture comptant" exclu
    Tag "Location" exclu
    Tva 13% exlu

    3. Paiement
    Paiements pour point "1. Facture comptant" exclu
    Catégorie "Location" exclu
    Commentaire "Prélèvement anticipé" exclu

    4. Ecritures
    Sans exceptions
    
    
    # Account Number definitions
    $ancaisse = '531000';
    $anclient = '411000';
    $anvente0 = '701300';
    $anvente5 = '701000';
    $anventepid10 = '701100';
    $anvente10 = '701600';
    $anvente16 = '701200';
    $antva5 = '445711';
    $antva10 = '445713';
    $antva16 = '445712';
    $anpaiement = '531000';
    
    #Journal definitions
    $VTcomptant = '730';
    $VTother = '700';
    $ENC = 'ENC';
    $OD = '909';
    
    

    </pre>
-->
    <?php
    break;

    # Make file
    case 1:
    ini_set('max_execution_time', 600);
    $sep = chr(9); # tab chr(9)
    $endline = chr(13) . chr(10);
    
    function sage_line($VT, $showdate, $invoiceid, $an, $clientid, $clientname, $value, $dc, $paybydate, $tag, $f1)
    {
      $sep = chr(9); # tab chr(9)
      $endline = chr(13) . chr(10);
      ### one line
      $writebuffer = $VT . $sep;
      $writebuffer .= $showdate . $sep;
      $writebuffer .= $paybydate . $sep;
      $writebuffer .= $invoiceid . $sep;
      $writebuffer .= $an . $sep;
      $writebuffer .= $clientid . $sep;
      $writebuffer .= 'G' . $sep;
      $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($clientname))),0,35) . $sep;
      $writebuffer .= myround($value) . $sep; # debit
      $writebuffer .= $dc . $sep; # credit
      $writebuffer .= $f1 . $sep;
      $writebuffer .= $sep;
      $writebuffer .= $endline;
      ###
      if (substr($an,0,1) > 5)
      {
        $writebuffer .= $VT . $sep;
        $writebuffer .= $showdate . $sep;
        $writebuffer .= $paybydate . $sep;
        $writebuffer .= $invoiceid . $sep;
        $writebuffer .= $an . $sep;
        $writebuffer .= $clientid . $sep;
        $writebuffer .= 'A' . $sep;
        $writebuffer .= substr(str_replace ($sep, ' ', trim(d_decode($clientname))),0,35) . $sep;
        $writebuffer .= myround($value) . $sep; # debit
        $writebuffer .= $dc . $sep; # credit
        $writebuffer .= $f1 . $sep;
        $writebuffer .= $tag . $sep;
        $writebuffer .= $endline;
      }
      return $writebuffer;
    }
    
    $limit = 10000;
    $datename = 'startdate'; require('inc/datepickerresult.php');
    $stopdate = $startdate; #not used

    echo '<h2>Export SAGE '.datefix($startdate).'</h2>';
    
    $query = 'set SQL_BIG_SELECTS=1;';
    $query_prm = array();
    require('inc/doquery.php');

    # Account Number definitions
    $anclient = '411000';
    $antravaux = '611000'; # total on 'travaux suivant devis'
    $ancreditmont = '701100';
    $anvente13 = '701101'; # vs 706800 on model 4    vs 706100 on model 4 and 5
    $antva13 = '445713'; # vs 665713 on model 5
    $anvente16 = '707100';
    $anavance = '419100'; # avance de démarrage (vat NOT calculated, see model 1), also deductions "avance de démarrage"
    $anasTRC = '616400'; # deduction: l’assurance TRC à enregistrer TTC en compte
    $anprorata = '604200'; # le compte prorata à enregistrer en HT
    $anproratatva = '445613'; # le compte prorata à enregistrer en TVA
    # also see model 2
    # Chacun des comptes de produits (commençant par 7) et des comptes de charges (commençant par 6) doit être rattaché à un code analytique (indispensable pour importer les données dans SAGE).
    
    /*
    $ancaisse = '531000';
    $anvente0 = '701300';
    $anvente5 = '701000';
    $anventepid10 = '701100';
    $anvente10 = '701600';
    $anvente16 = '701200';
    $antva5 = '445711';
    $antva10 = '445713';
    $antva16 = '445712';
    $anpaiement = '531000';
    */
    
    #Journal definitions
    $VT = 'VTE';

    $filename = 'customfiles/sage' . date("Y_m_d_H_i_s") . '.txt';
    $file = fopen($filename, "w");
    if (!$file) { echo "Cannot create the file!<br>"; exit; }
    
    $writebuffer = '';
    
    ### invoices

    require('preload/invoicetag.php');
    
    $query = 'select productid,lineprice,linevat,linetaxcodeid,reference,invoicehistory.invoiceid,invoicehistory.clientid,isreturn,clientname,invoicetagid,clientcode,field1,
    date_format(accountingdate,"%d%m%y") as showdate,date_format(paybydate,"%d%m%y") as paybydate
    from invoiceitemhistory,invoicehistory,client
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid and cancelledid=0 and isnotice=0 and confirmed=1 and invoiceprice>0';
    #and accountingdate=? and paymentdate=?
    $query .= ' and invoicehistory.invoiceid=5
    and invoicehistory.exported=0
    order by accountingdate,invoiceid';
    $query_prm = array($startdate);

    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      $isreturn = (int) $row['isreturn']; # invoice debit, avoir credit
      $invoiceid = $row['invoiceid'];
      $showinvoiceid = substr($row['accountingdate'],0,4) . 'F';
      if (mb_strlen($invoiceid) == 1) { $showinvoiceid .= '000' .  $invoiceid; }
      elseif (mb_strlen($invoiceid) == 2) { $showinvoiceid .= '00' .  $invoiceid; }
      elseif (mb_strlen($invoiceid) == 3) { $showinvoiceid .= '0' .  $invoiceid; }
      else { $showinvoiceid .= substr($invoiceid, -4); }
      
      if ($row['productid'] == 647)
      {
        $deduct_prevat = 0; $deduct_aftervat = 0;
        $query = 'select * from invoicededuction where invoiceid=? group by deduction_prevat order by deduction_prevat';
        $query_prm = array($row['invoiceid']);
        require('inc/doquery.php');
        for ($y=0; $y < $num_results; $y++)
        {
          $row2 = $query_result[$y];
          if ($row2['deduction_prevat'] == 1) { $deduct_prevat += $row2['deduction']; }
          else { $deduct_aftervat += $row2['deduction']; }
        }
        
        $debit1 = $row['lineprice'] + $row['linevat'] - $deduct_prevat - $deduct_aftervat;
        $debit2 = $deduct_aftervat;
        $credit1 = $row['lineprice'] - $deduct_prevat;
        $credit2 = $row['linevat'];
        
        $dc = 'D'; if ($isreturn) { $dc = 'C'; }
        if ($debit1 > 0) { $writebuffer .= sage_line($VT, $row['showdate'], $showinvoiceid, $anclient, $row['clientcode'], $row['clientname'], $debit1, $dc, $row['paybydate'], $invoicetagA[$row['invoicetagid']], $row['field1']); }
        if ($debit2 > 0) { $writebuffer .= sage_line($VT, $row['showdate'], $showinvoiceid, $anavance, $row['clientcode'], $row['clientname'], $debit2, $dc, $row['paybydate'], $invoicetagA[$row['invoicetagid']], $row['field1']); }
        $dc = 'C'; if ($isreturn) { $dc = 'D'; }
        if ($credit1 > 0) { $writebuffer .= sage_line($VT, $row['showdate'], $showinvoiceid, $ancreditmont, $row['clientcode'], $row['clientname'], $credit1, $dc, $row['paybydate'], $invoicetagA[$row['invoicetagid']], $row['field1']); }
        if ($credit2 > 0) { $writebuffer .= sage_line($VT, $row['showdate'], $showinvoiceid, $antva13, $row['clientcode'], $row['clientname'], $credit2, $dc, $row['paybydate'], $invoicetagA[$row['invoicetagid']], $row['field1']); }
      }
      else
      {
        #
      }

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
    break;

  }
  break;

}


require ('inc/bottom.php');
?>