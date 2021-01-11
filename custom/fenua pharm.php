<?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Fenua Pharm</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      echo '<b>Import:</b><br>';
      echo '&nbsp; <a href="custom.php?custommenu=import">Déclarant</a><br>';
      echo '<br>&nbsp; <a href="custom.php?custommenu=importproduct">Produits</a><br>';
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{

  case 'import':
  # config
  $separator = ';';
  $proceed = (int) $_POST['proceed'];
  $min_info = (int) $_POST['min_info'];

  echo '<h2>Import Déclarant'; #if ($proceed != 1) { echo ' [affichage seulement]'; }
  echo '</h2>';
  if ($_POST['importme'] == 1)
  {
    $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
    $i = 0; $totaleuro = 0; $shipmentid = 0;
    echo '<table class=report>';
    if ($min_info == 1) { echo '<thead><th>CIP 7<th>QTE<th>PRIX REVIENT UNITAIRE<th>Dést</thead>'; }
    else
    {
      echo '<thead><th>CIP(EAN 13)<th>DESIGNATION<th>ORIGINE<th>QTE<th>PRIX UNITAIRE ACHAT<th>REFERENCE<th>PRIX REVIENT UNITAIRE<th>CODE DOUANIER<th>COEF<th>TAUX TVA<th>Fact No<th>Dést<th>Total EUR<th>CIP (7 chiffres)<th></thead>';
    }
    while ($line=fgets($fp))
    {
      $i++; $productid = -1;
      $lineA = explode($separator, $line);
      $productlineA[$i] = $lineA;
      if ($i == 1)
      {
        $clientname = $lineA[11]; $field1 = $lineA[10];
        #$clientname = $lineA[3]; $field1 = '';
      }
      if ($i == 2) { $ref = $lineA[14]; }
      
      if (1 == 1) # all lines
      {
        echo '<tr><td>' . $lineA[0];
        echo '<td>' . $lineA[1];
        echo '<td>' . $lineA[2];
        echo '<td>' . $lineA[3];
        if ($min_info != 1)
        {
          echo '<td>' . $lineA[4];
          echo '<td>' . $lineA[5];
          echo '<td>' . $lineA[6];
          echo '<td>' . $lineA[7];
          echo '<td>' . $lineA[8];
          echo '<td>' . $lineA[9];
          echo '<td>' . $lineA[10];
          echo '<td>' . $lineA[11];
          echo '<td>' . $lineA[12];
          echo '<td>' . $lineA[13];
        }
        
        $query = 'select productid from product where eancode=?';
        $query_prm = array($lineA[0]);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'select productid from product where suppliercode=?';
          $query_prm = array($lineA[5]);
          require('inc/doquery.php');
        }
        if ($num_results == 0 && $min_info != 1)
        {
          # product insert
          $query = 'select countryid from country where sofixcode=?';
          $query_prm = array($lineA[2]);
          require('inc/doquery.php');
          $countryid = $query_result[0]['countryid'];
          $query = 'select taxcodeid from taxcode where taxcode=?';
          $query_prm = array($lineA[9]);echo '"',$lineA[9],'"';
          require('inc/doquery.php');
          $taxcodeid = $query_result[0]['taxcodeid']+0;
          $sih = $lineA[7];
          $unittypeid = 1;
          $supplierunittypeid = 1;
          $salesprice = ceil(str_replace(',','.',$lineA[6]));
          $productfamilyid = 1;
          $suppliercode2 = '';
          $suppliercode = $lineA[5];
          $productname = $lineA[1];
          $eancode = d_safebasename($lineA[0]);
          $netweightlabel = '';
          
          if ($proceed == 1)
          {
            # product insert
            $query = 'insert into product (netweightlabel, eancode, productname, suppliercode, suppliercode2, productfamilyid, salesprice, supplierunittypeid, unittypeid, taxcodeid, countryid, sih, numberperunit) values (?,?,?,?,?,?,?,?,?,?,?,?,1)';
            $query_prm = array($netweightlabel,$eancode,$productname,$suppliercode,$suppliercode2,$productfamilyid,$salesprice,$supplierunittypeid,$unittypeid,$taxcodeid,$countryid,$sih);
            require('inc/doquery.php');
            $productid = $query_insert_id;
            echo '<td>Produit inséré';
          }
        }
        else
        {
          # TODO update price!
          $productid = $query_result[0]['productid']; echo '<td>';
        }
        $productidA[$i] = $productid;
        $invoice_result_text = '';
        if ($min_info == 1) { $totaleuro += (str_replace(',','.',$lineA[2]) * $lineA[1]); }
        else { $totaleuro += (str_replace(',','.',$lineA[12]) * $lineA[3]); }
        if ($proceed == 1)
        {
          if ($shipmentid == 0)
          {
            $kladd = 'total euro='.$totaleuro;
            $query = 'insert into shipment (shipmentstatus,shipmentcomment) values (?,?)';
            $query_prm = array('Fini',$kladd);
            require('inc/doquery.php');
            $shipmentid = $query_insert_id;
          }
          # purchasebatch insert
          # line 10 is supplier invoiceid
          # line 6 is cost & price
          if ($min_info == 1) { $prev = str_replace(',','.',$lineA[2]); }
          else { $prev = str_replace(',','.',$lineA[6]); }
          # quantity line 3
          $query = 'insert into purchasebatch (productid,prev,amount,userid,supplierbatchname,placementid,shipmentid,arrivaldate,origamount) values (?,?,?,?,?,1,?,curdate(),?)';
          if ($min_info == 1) 
          { 
            $lineA[1] += 0;
            $query_prm = array($productid,$prev,$lineA[1],$_SESSION['ds_userid'],'',$shipmentid,$lineA[1]);
          }
          else
          {
            $lineA[3] += 0;
            $query_prm = array($productid,$prev,$lineA[3],$_SESSION['ds_userid'],$lineA[10],$shipmentid,$lineA[3]);
          }
          require('inc/doquery.php');
        }
      }
    }
    if ($shipmentid > 0)
    {
      $kladd = 'total euro='.$totaleuro;
      $query = 'update shipment set shipmentcomment=? where shipmentid=?';
      $query_prm = array($kladd, $shipmentid);
      require('inc/doquery.php');
    }
    echo '</table>';
    echo '<p>Total en euro: '.$totaleuro.'</p>';
    if ($proceed == 1 && $i > 0)
    {
      # insert into invoice and invoiceitem
      # clientname line 11
      $query = 'select clientid,clientname from client where clientname like ?';
      $clientname = str_replace(' DE ','',$clientname);
      $clientname = str_replace('PHARMACIE','',$clientname);
      $query_prm = array('%'.trim($clientname).'%');echo $clientname;
      require('inc/doquery.php');
      $clientid = $query_result[0]['clientid'];
      if ($clientid > 0)
      {
        $clientname = $query_result[0]['clientname'];
        
        ###
        $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
        $query_prm = array();
        require('inc/doquery.php');

        $invoiceid = $query_insert_id;
        if ($invoiceid < 1) { echo '<p class=alert>critical error attributing invoiceid</p>'; exit; }
        
        $query = 'insert into invoice (invoiceid,matchingid,cancelledid,invoicegroupid,confirmed) values (?,0,0,0,0)';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        ###
        
        $totalprice = 0; $invoicevat = 0;
        for ($y = 1; $y <= $i; $y++)
        {
          if ($min_info == 1)
          {
            #$productidA[$y]
            $query = 'select taxcode,product.taxcodeid from taxcode,product where product.taxcodeid=taxcode.taxcodeid and productid=?';
            $query_prm = array($productidA[$y]);
            require('inc/doquery.php');
            $taxcodeid = $query_result[0]['taxcodeid'];
            $taxcode = $query_result[0]['taxcode'];
            
            $basecartonprice = ceil(str_replace(',','.',$productlineA[$y][2]));
            $lineprice = myround($basecartonprice * $productlineA[$y][1]);
            $linevat = myround($lineprice * $taxcode/100);
            echo '<br>TVA ligne '.$y.'= round('.$lineprice.' * '.$taxcode.'/100)= '.$linevat;
          }
          else
          {
            $basecartonprice = ceil(str_replace(',','.',$productlineA[$y][6]));
            $lineprice = myround($basecartonprice * $productlineA[$y][3]);
            $linevat = myround($lineprice * $productlineA[$y][9]/100);
            echo '<br>TVA ligne '.$y.'= round('.$lineprice.' * '.$productlineA[$y][9].'/100)= '.$linevat;
          }
          $totalprice += $lineprice;
          $invoicevat += $linevat;
          
          if ($min_info != 1)
          {
            $query = 'select taxcodeid from taxcode where taxcode=?';
            $query_prm = array($productlineA[$y][9]);
            require('inc/doquery.php');
            $taxcodeid = $query_result[0]['taxcodeid'];
          }
          
          $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
          $query_prm = array();
          require('inc/doquery.php');
          $invoiceitemid = $query_insert_id;
          $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat,itemcomment,linetaxcodeid) values (?,?,?,?,?,?,?,?,?,?)';
          if ($min_info == 1) { $query_prm = array($invoiceitemid, $invoiceid, $productidA[$y], $productlineA[$y][1], 0, $basecartonprice, $lineprice, $linevat, '', $taxcodeid); }
          else { $query_prm = array($invoiceitemid, $invoiceid, $productidA[$y], $productlineA[$y][3], 0, $basecartonprice, $lineprice, $linevat, '', $taxcodeid); }
          require('inc/doquery.php');
        }
        $ourdate = $_SESSION['ds_curdate'];
        $totalprice += $invoicevat;
        $datename = 'accountingdate'; require('inc/datepickerresult.php');
        $datename = 'paybydate'; require('inc/datepickerresult.php');
        $query = 'update invoice set reference="'.$ref.'",field1="' . $field1 . '",paybydate="' . $paybydate . '",accountingdate="' . $accountingdate . '",deliverydate="' . $ourdate . '",invoicedate=curdate(),invoicetime=curtime(),clientid=' . $clientid . ',userid=' . $_SESSION['ds_userid'] . ',invoiceprice=' . $totalprice . ',invoicevat=' . $invoicevat . ' where invoiceid="' . $invoiceid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        
        $invoice_result_text = '<br><p>Facture <a href="printwindow.php?report=showinvoice&invoiceid=' . $invoiceid . '&linesperpage=100" target=_blank>' .$invoiceid. '</a> insérée.</p>';
      }
      else
      {
        $invoice_result_text = '<br><p class=alert>N\'A PAS PU IDENTIFIER LE CLIENT, facture pas créée</p>';
      }
    }
    ### test VAT
    /*
    $totalprice = 0; $invoicevat = 0;
    for ($y = 1; $y <= $i; $y++)
    {
      $basecartonprice = ceil(str_replace(',','.',$productlineA[$y][6]));
      $lineprice = $basecartonprice * $productlineA[$y][3];
      $linevat = myround($lineprice * $productlineA[$y][9]/100);
      echo '<br>$linevat = myround($lineprice * $productlineA[$y][9]/100);';
      echo '<br>TVA ligne '.$y.'= round('.$lineprice.' * '.$productlineA[$y][9].'/100)= '.$linevat;
    }
    */
    ###
    echo $invoice_result_text;
  }
  else
  {
    ?>
    <form enctype="multipart/form-data" method="post" action="custom.php">
    <table>
    <?php
    echo '<tr><td>Date facture:<td>'; $datename = 'accountingdate'; require('inc/datepicker.php');
    echo '<tr><td>Date échéance<td>'; $datename = 'paybydate'; require('inc/datepicker.php');
    ?>
    <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
    <tr><td colspan=2><select name="proceed"><option value=0>Afficher (et ne pas insérer)</option><option value=1>Afficher et insérer</option></select></td></tr>
    <tr><td colspan=2><input type=checkbox name=min_info value=1> Fichier à 4 lignes (CIP 13; quantité; prix revient; Dést)</td></tr>
    <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
  }
  break;
  
  case 'importproduct':
    # config
    $separator = ';';

    echo '<h2>Product import</h2>';

    if ($_POST['importme'] == 1)
    {
      $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
      $i = 0;
      echo '<table class=report>';
      while ($line=fgets($fp))
      {
        $i++;
        $lineA = explode($separator, $line);
        
        if ($i >= 1)
        {
          echo '<tr>';
          for ($x=0; $x < 4; $x++)
          {
            echo '<td>' . $lineA[$x];
          }

          # taxcodeid
          $taxcodeid = 4; # all 16%
          if ($lineA[4] == '5') { $taxcodeid = 4; }
          
          $unittypeid = 1;
          
          $supplierunittypeid = 1;
          
          # salesprice
          $salesprice = 0;
          
          # margin
          $margin = 0;
          
          # prev
          $prev = 0;
          
          $productfamilyid = 1;
        
          # suppliercode2
          $suppliercode2 = '';
          
          # suppliercode
          $suppliercode = $lineA[1];
          
          # productname
          $productname = d_encode($lineA[3]);
          
          # eancode
          $eancode = $lineA[2];
          
          #netweightlabel
          $netweightlabel = '';
          
          $supplierid = 0;
          
          $query = 'select productid from product where eancode=? or suppliercode=?';
          $query_prm = array($eancode, $suppliercode);
          require('inc/doquery.php');
          if ($num_results > 0) { $productname = ''; }
          
          if ($productname != '')
          {
            # product insert
            $query = 'insert into product (supplierid,netweightlabel,eancode,productname,suppliercode,suppliercode2,productfamilyid,margin,salesprice,supplierunittypeid,unittypeid,taxcodeid,numberperunit,countryid) values (?,?,?,?,?,?,?,?,?,?,?,?,1,156)';
            $query_prm = array($supplierid,$netweightlabel,$eancode,$productname,$suppliercode,$suppliercode2,$productfamilyid,$margin,$salesprice,$supplierunittypeid,$unittypeid,$taxcodeid);
            require('inc/doquery.php');
            echo '<td>Product inserted';
            #echo '<br>',$query,'<br>';var_dump($query_prm);
          }
          else { echo '<td>'; }
        }
        
      }
      echo '</table>';
    }
    else
    {
      ?><p>Fichier CSV: &nbsp; &nbsp; &nbsp; [vide]; Code; EAN; Nom du produit; Taux TVA</p>
      <form enctype="multipart/form-data" method="post" action="custom.php">
      <table>
      <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
      <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
    }
  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>