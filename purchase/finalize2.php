<?php

#ini_set('display_errors', 'On');
#error_reporting(E_ALL);

# 2016 10 03 beginning refactor
# remove/fix: old mysql, $currentstep, SESSION variables

function str_contains($haystack, $needle, $ignoreCase = false)
{
  if ($ignoreCase)
  {
    $haystack = mb_strtolower($haystack);
    $needle = mb_strtolower($needle);
  }
  $needlePos = mb_strpos($haystack, $needle);
  return ($needlePos === false ? false : ($needlePos));
}

$transitmargin = 0.02; # TODO should be global parameter

$PA['saveme'] = 'int';
$PA['verifyme'] = 'int';
$PA['shipmentid'] = 'int';
require('inc/readpost.php');

if ($saveme == 1)
{
  $lines = $_POST['lines'];
  for ($i=1; $i <= $lines; $i++)
  {
    $purchaseid[$i] = $_POST['purchaseid'.$i];
    $amount = $_POST['amount' . $i];
    
    $query = 'select numberperunit,unittypename,discontinued,displaymultiplier from product,unittype where product.unittypeid=unittype.unittypeid and productid=?';
    $query_prm = array($_POST['productid' . $i]);
    require('inc/doquery.php');
    $row = $query_result[0];
    $unittypename = $row['unittypename'];
    $numberperunit = $row['numberperunit'];
    $discontinued = $row['discontinued'];
    $dmp = $row['displaymultiplier'];
    $amount *= $dmp;
    
    $query = 'insert into purchasebatch (purchaseid,batchname,supplierbatchname,useby,userid,productid,arrivaldate,origamount,amount,cost,portfees,shipmentid) values (?,?,?,?,?,?,?,?,?,?,?,?)';
    $query_prm = array($purchaseid[$i], $_POST['batchname'.$i], $_POST['supplierbatchname'.$i], $_POST['useby'.$i], $_SESSION['ds_userid'],
    $_POST['productid' . $i], $_POST['arrivaldate'], $amount, $amount, $_POST['pru' . $i], $_POST['portfees' . $i], $shipmentid);
    require('inc/doquery.php');
    echo 'Produit no: ' . $_POST['productid' . $i] . ' arrivé avec ' . ($amount / $numberperunit) . ' ' . $unittypename . ' a ' . ($_POST['pru' . $i]) . ' XPF.<br>';

  }
  $query = 'update shipment set coldstorage=?, sanitaryfees=?, freightcostexchangerate=?, insuranceexchangerate=?, exchangerate=?, shipmentstatus="Fini", vattaxes=? where shipmentid=?';
  $query_prm = array(($_POST['coldstorage']+0), ($_POST['sanitaryfees']+0), ($_POST['freightcostexchangerate']+0), ($_POST['insuranceexchangerate']+0), ($_POST['exchangerate']+0), ($_POST['vattaxes']+0), $shipmentid);
  require('inc/doquery.php');
}
elseif($verifyme == 1)
{
  $lines = $_POST['lines'];
  $exchangerate = $_POST['exchangerate'];
  $insuranceexchangerate = $_POST['insuranceexchangerate'];
  $freightcostexchangerate = $_POST['freightcostexchangerate'];
  $totalsanitaryfees = $_POST['sanitaryfees'];
  $totalcoldstorage = $_POST['coldstorage'];

  for ($i=1; $i <= $lines; $i++)
  {
    $purchaseid[$i] = $_POST['purchaseid'.$i];
    $sanitaryfees[$i] = ($_POST['weight' . $i] / $_POST['calctotalweight']) * $totalsanitaryfees;
    $coldstorage[$i] = ($_POST['weight' . $i] / $_POST['calctotalweight']) * $totalcoldstorage;
    $cif[$i] = floor((($_POST['purchaseprice' . $i] * $exchangerate) + ($_POST['freightcost' . $i] * $freightcostexchangerate) + ($_POST['insurance' . $i] * $insuranceexchangerate)));
  }

  ?><h2>Cost calculation</h2>
  <table><form method="post" action="purchase.php"><tr><td>Order number: </td><td><b><font color=blue size=+2><?php echo $shipmentid; ?></font></b></td></tr>
  <tr><td>Exchange rate: </td><td> &nbsp; <?php echo $_POST['currencyacronym']; ?> = <input type="text" STYLE="text-align:right" name="exchangerate" value="<?php echo $exchangerate; ?>" size=15 readonly></td></tr>
  <?php
  if ($_POST['incotermid'] != 3)
  {
    echo '<tr><td>Insurance cost: </td><td> &nbsp; ' . $_POST['totalinsurance'] . ' ' . $_POST['insurancecurrencyacronym'] . ' &nbsp; Exchange rate=<input type="text" STYLE="text-align:right" name="insuranceexchangerate" value="' . $insuranceexchangerate . '"  size=15 readonly></td></tr>';
    echo '<tr><td>Freightcost: </td><td> &nbsp; ' . $_POST['totalfreightcost'] . ' ' . $_POST['freightcostcurrencyacronym'] . ' &nbsp; Exchange rate=<input type="text" STYLE="text-align:right" name="freightcostexchangerate" value="' . $freightcostexchangerate . '"  size=15 readonly></td></tr>';
  }
  echo '<tr><td>Laissez Passer: </td><td><input type="text" STYLE="text-align:right" name="sanitaryfees" value="' . $_POST['sanitaryfees'] . '" size=15 readonly> XPF</td></tr>';
  echo '<tr><td>Frigo/Autre: </td><td><input type="text" STYLE="text-align:right" name="coldstorage" value="' . $_POST['coldstorage'] . '" size=15 readonly> XPF</td></tr>';
  echo '</table><br><table class="detailinput">';
  echo '<tr><td><b>ID</b></td><td><b>Produit</b></td><td><b>Q.</b></td><td><b>CIF</b></td><td><b>Transit</b></td><td><b>Debarquement</b></td><td><b>Transport</b></td><td><b>Laissez Passer</b></td><td><b>Frigo/Autre</b></td><td><b>Prix Total Entrepot</b></td><td><b>Douane</b></td><td><b>Prix Total Rev</b></td></tr>';
  $subtquan = 0; $subtcif = 0; $subttran = 0; $subtunl = 0; $subttrs = 0;
  $subt1 = 0; $subt2 = 0; $subt3 = 0; $subt4 = 0; $subt5 = 0;
  for ($i=1; $i <= $lines; $i++)
  {
    $total = ($cif[$i] * (1 + $transitmargin)) + $_POST['unloadingcost' . $i] + $_POST['portfees' . $i] + $_POST['transportcost' . $i] + $sanitaryfees[$i] + $coldstorage[$i];
    echo '<tr><td>' . $_POST['productid' . $i] . '</td><td>' . $_POST['productname' . $i] . '</td><td align=right>'.$_POST['showamount' . $i].'</td><td align=right>' . $cif[$i] . '</td>
    <td align=right>' . ($cif[$i] * $transitmargin) . '</td><td align=right>' . ($_POST['unloadingcost' . $i]) . '</td><td align=right>' . $_POST['transportcost' . $i] . '</td>
    <td>' . ($sanitaryfees[$i]) . '</td><td>' . ($coldstorage[$i]) . '</td><td align=right>' . ($total - ($_POST['portfees' . $i])) . '</td><td align=right>' . $_POST['portfees' . $i] . '</td><td align=right>' . ($total) . '</td></tr>';
    $subtquan += $_POST['showamount' . $i];
    $subtcif += $cif[$i];
    $subttran += ($cif[$i] * $transitmargin);
    $subtunl += ($_POST['unloadingcost' . $i]);
    $subttrs += $_POST['transportcost' . $i];
    $subt1 += ($sanitaryfees[$i]);
    $subt2 += ($coldstorage[$i]);
    $subt3 += $total - $_POST['portfees' . $i];
    $subt4 += $_POST['portfees' . $i];
    $subt5 += $total;
    echo '<input type=hidden name=supplierbatchname'.$i.' value="'.$_POST['supplierbatchname'.$i].'">';
    echo '<input type=hidden name=purchaseid'.$i.' value="'.$purchaseid[$i].'">';
    echo '<input type=hidden name=productid'.$i.' value="'.$_POST['productid' . $i].'">';
    echo '<input type=hidden name=amount'.$i.' value="'.$_POST['amount' . $i].'">';
    echo '<input type=hidden name=batchname'.$i.' value="'.$_POST['batchname' . $i].'">';
    echo '<input type=hidden name=useby'.$i.' value='.$_POST['useby' . $i].'>';
    echo '<input type=hidden name=pru'.$i.' value="'.($total/$_POST['amount' . $i]).'">';
    echo '<input type=hidden name=portfees'.$i.' value="'.$_POST['portfees' . $i].'">';
  }
  echo '<tr><td colspan=2><b></b></td><td align=right>'.($subtquan).'</td><td align=right>'.($subtcif).'</td><td align=right>'.($subttran).'</td><td align=right>'.($subtunl).'</td>
  <td align=right>'.($subttrs).'</td><td align=right>'.($subt1).'</td><td align=right>'.($subt2).'</td><td align=right>'.($subt3).'</td><td align=right>'.($subt4).'</td><td align=right>'.($subt5).'</td></tr>';
  ?>
  </table><br><table>
  <tr><td align="center">
  <INPUT TYPE="hidden" NAME="saveme" value="1">
  <input type=hidden name="verifyme" value=1>
  <input type=hidden name="lines" value="<?php echo $lines; ?>">
  <input type=hidden name="vattaxes" value="<?php echo $vattaxes; ?>">
  <input type=hidden name="shipmentid" value="<?php echo $shipmentid; ?>">
  <input type=hidden name="arrivaldate" value="<?php echo $_POST['arrivaldate']; ?>">
  <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>"><BR>
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
}
elseif($shipmentid > 0)
{
  ##############################################################################################################
  
  $PA['nosofix'] = 'int';
  require('inc/readpost.php');

  $query = 'select shipmentstatus from shipment where shipmentid=?';
  $query_prm = array($shipmentid);
  require('inc/doquery.php');
  
  if (!isset($query_result[0]['shipmentstatus']))
  {
    echo '<p>Erreur. Commande numéro ' . $shipmentid . ' n\'existe pas.</p>';
  }
  elseif ($query_result[0]['shipmentstatus'] == 'Fini')
  {
    echo '<p>Commande numéro ' . $shipmentid . ' a déja été FINALIZED.</p>';
  }
  elseif ($query_result[0]['shipmentstatus'] == 'Commandé' || $query_result[0]['shipmentstatus'] == 'Arrivé au port')
  {
    $totalprice = 0;
    $calctotalweight = 0;
    $mytotalpricexpf = 0;
    $vattaxes = 0;
    $separator = chr(13);
    
    $query = 'select arrivaldate,currencyrate,currencyacronym,unloadingcost,freightcost,insurance,incotermid from shipment,currency where shipment.currencyid=currency.currencyid and shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $row = $query_result[0];
    $exchangerate = $row['currencyrate'];
    $currencyacronym = $row['currencyacronym'];
    $totalfreightcost = $row['freightcost'];
    $totalinsurance = $row['insurance'];
    $totalunloadingcost = $row['unloadingcost'];
    $arrivaldate = $row['arrivaldate'];
    $incotermid = $row['incotermid'];
    
    $query = 'select currencyrate,currencyacronym from shipment,currency where shipment.freightcostcurrencyid=currency.currencyid and shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $row = $query_result[0];
    $freightcostexchangerate = $row['currencyrate'];
    $freightcostcurrencyacronym = $row['currencyacronym'];
    
    $query = 'select currencyrate,currencyacronym from shipment,currency where shipment.insurancecurrencyid=currency.currencyid and shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $row = $query_result[0];
    $insuranceexchangerate = $row['currencyrate'];
    $insurancecurrencyacronym = $row['currencyacronym'];
    
    $query = 'select purchaseid,batchname,supplierbatchname,useby,avantage,purchase.productid as productid,productname,countryid,numberperunit,transportpricepercarton
    ,amount,amountcartons,purchaseprice,netweight,sih,p_transportpricepercarton
    from purchase,product
    where purchase.productid=product.productid and purchase.shipmentid=? order by purchaseid';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $lines = $num_results;
    $main_result = $query_result;
    
    for ($x=0; $x < $lines; $x++) # 2017 06 16 update 3x p_ values at the moment of finalize
    {
      $temp_purchaseid = $main_result[$x]['purchaseid'];
      $temp_productid = $main_result[$x]['productid'];
      $query = 'select palletpricepercarton,transportpricepercarton,stickerpricepercarton
      from product where productid=?';
      $query_prm = array($temp_productid);
      require('inc/doquery.php');
      $t_p_palletpricepercarton = $query_result[0]['palletpricepercarton'];
      $t_p_transportpricepercarton = $query_result[0]['transportpricepercarton']; $main_result[$x]['p_transportpricepercarton'] = $query_result[0]['transportpricepercarton'];;
      $t_p_stickerpricepercarton = $query_result[0]['stickerpricepercarton'];
      $query = 'update purchase set p_palletpricepercarton=?,p_transportpricepercarton=?,p_stickerpricepercarton=?
      where purchaseid=?';
      $query_prm = array($t_p_palletpricepercarton, $t_p_transportpricepercarton, $t_p_stickerpricepercarton, $temp_purchaseid);
      require('inc/doquery.php');
    }
    
    for ($x=0; $x < $lines; $x++)
    {
      $i = $x+1; # old index starting at 1
      $row = $main_result[$x];
      
      $purchaseid[$i] = $row['purchaseid'];
      $useby[$i] = $row['useby'];
      $batchname[$i] = $row['batchname'];
      $supplierbatchname[$i] = $row['supplierbatchname'];
      $productid[$i] = $row['productid'];
      $productname[$i] = $row['productname'];
      $purchaseprice[$i] = $row['purchaseprice'];
      $mypurchasepricexpf[$i] = $row['purchaseprice'] * $exchangerate;
      $amount[$i] = $row['amount'];
      $amountcartons[$i] = $row['amountcartons'];
      $numberperunit[$i] = $row['numberperunit'];
      $transportpricepercarton[$i] = $row['transportpricepercarton']; if ($row['p_transportpricepercarton'] != 0) { $transportpricepercarton[$i] = $row['p_transportpricepercarton']; }
      $weight[$i] = ($row['netweight'] * $amount[$i]) / 1000;
      $countryid[$i] = $row['countryid'];
      $sih[$i] = $row['sih'] . $row['countryid'] . $row['avantage'];
      
      $mytotalpricexpf += $mypurchasepricexpf[$i];
      $totalprice += $purchaseprice[$i];
      $calctotalweight += $weight[$i];
    }
    
    for ($i=1; $i <= $lines; $i++)
    {
      $transportcost[$i] = ($amount[$i] / $numberperunit[$i]) * $transportpricepercarton[$i];
      if ($amountcartons[$i] > 0) { $transportcost[$i] = $amountcartons[$i] * $transportpricepercarton[$i]; }
      $unloadingcost[$i] = ($weight[$i] / $calctotalweight) * $totalunloadingcost;
      $freightcost[$i] = ($weight[$i] / $calctotalweight) * $totalfreightcost;
      $insurance[$i] = (($mypurchasepricexpf[$i] + $freightcost[$i]) / ($totalfreightcost + $mytotalpricexpf)) * $totalinsurance;
      $cif[$i] = floor((($purchaseprice[$i] * $exchangerate) + ($freightcost[$i] * $freightcostexchangerate) + ($insurance[$i] * $insuranceexchangerate)));
    }

    if ($nosofix)
    {
      $customscode = '';
      $file = '';
      $transitmargin = 0;
    }
    else
    {
      $customscode = basename($_FILES['userfile']['name'], ".ddt");
      $file = file_get_contents($_FILES['userfile']['tmp_name']);
      if (!$file) { echo "Cannot read the file!<br>"; }
    }
    
    $testfile = strstr($file,"DOSSIER=");
    $position1 = str_contains($testfile, $separator) - 8;
    if ($position1 < 1)
    {
      $position1 = mb_strpos($testfile, chr(10)) - 8;
    }
    if ($nosofix != 1 && substr($testfile,8,$position1) != $shipmentid)
    {
      exit('Wrong Dossier number in import file.');
    }

    ################## save total tax amount, Date de déclaration, Navire (IDENTITE_MOYEN) ###

    if ($nosofix) { $taxfile = ''; }
    else { $taxfile = file_get_contents($_FILES['userfile']['tmp_name']); }
    $taxfile = strstr($taxfile,"[LIQ_DDT]");
    $taxfile = strstr($taxfile,"CODE_TAXE=011@TYPE_TAXE=P");
    $taxfile = strstr($taxfile,"MONTANT_TAXE=");
    $taxposition = str_contains($taxfile,'@DATE_ECHEANCE=')-13;
    $taxfile = mb_substr($taxfile,13,$taxposition);
    if ($taxfile == "") { $taxfile = "0"; }

    if ($nosofix) { $decl_date = NULL; }
    else { $decl_date = $file; }
    $decl_date = strstr($decl_date,"[DDT]");
    $decl_date = strstr($decl_date,"DATE_DEPOT=");
    $decl_date = mb_substr($decl_date, 11, 10);
    $decl_date = substr ($decl_date, 6, 4) . '-' . substr ($decl_date, 3, 2) . '-' . substr ($decl_date, 0, 2);

    if ($nosofix) { $navire = ''; }
    else { $navire = $file; }
    $navire = strstr($navire,"[DDT_BUL]");
    $navire = strstr($navire,"IDENTITE_MOYEN=");
    $pos = str_contains($navire,'NATIONALITE')-17;
    $navire = mb_substr($navire, 15, $pos);

    if ($nosofix) { $sofixadvantage = ''; }
    else { $sofixadvantage = $file; }
    $sofixadvantage = strstr($sofixadvantage,"[ART]");
    $sofixadvantage = str_contains($sofixadvantage,'@AVANTAGE=390');
    if ($sofixadvantage > 0) { $sofixadvantage = '390'; }

    $query = 'update shipment set customscode=?,savedtaxes=?,sofixvessel=?,sofixadvantage=?';
    $query_prm = array($customscode, $taxfile, $navire, $sofixadvantage);
    if ($decl_date != '--') { $query .= ',sofixdate=?'; array_push($query_prm, $decl_date); }
    $query .= ' where shipmentid=?';
    array_push($query_prm, $shipmentid);
    require('inc/doquery.php');
    echo '<font size=-1>Tax(011) enregistré: ' . d_output($taxfile) . '</font><br>';

    ##################################

    $prefile = strstr($file,"[ART]");
    $prefile = strstr($prefile,"1=NUMERO");
    $position1 = str_contains($prefile, '[SF_ART]');
    $prefile = mb_substr($prefile,0,$position1);

    $file = strstr($file,"[LIQ_ART]");
    $file = strstr($file,"1=ARTICLE");
    $position1 = str_contains($file, '[LIQ_DDT]');
    $file = mb_substr($file,0,$position1);

    $prefilebyline = explode($separator, $prefile);
    $filebyline = explode($separator, $file);

    for ($i=1; $i <= $lines; $i++)
    {
      $sihportfees[$i] = 0;
      $calcsihportfees[$i] = 0;
    }

    $i = 1;
    $num_lines = 0;
    foreach ($prefilebyline as $importline)
    {
      if (strpos($importline, 'NUMERO_ARTICLE'))
      {
        $position1 = str_contains($importline, 'POSITION_TARIFAIRE=') + 19;
        $readsihcode[$i] = mb_substr($importline,$position1,11);

        # WE NEED TO READ COUNTRY CODE AND TRANSLATE TO COUNTRYID
        $position1 = str_contains($importline, 'PAYS_ORIGINE=') + 13;
        $sofixcountry = mb_substr($importline,$position1,3);
        $query = 'select countryid from country where sofixcode=?';
        $query_prm = array($sofixcountry);
        require('inc/doquery.php');
        $row23 = $query_result[0];

        $avantage = "";
        if (strpos($importline, 'AVANTAGE='))
        {
          $kladd1 = str_contains($importline, 'AVANTAGE=') + 9;
          $kladd2 = str_contains($importline, '@BENEFICIAIRE_AVANTAGE');
          $kladd2 = $kladd2 - $kladd1;
          $avantage = mb_substr($importline,$kladd1,$kladd2);
        }

        $readsihcode[$i] = $readsihcode[$i] . $row23['countryid'] . $avantage;

        if ($readsihcode[$i] != "") { $num_lines++; }
        $i = $i + 1;
      }
    }

    foreach ($filebyline as $importline)
    {
      if (strpos($importline, 'ARTICLE='))
      {
        $position1 = str_contains($importline, 'ARTICLE=') + 8;
        $i = mb_substr($importline,$position1,4) + 0;
        if ($i != 0)
        {
          $position1 = str_contains($importline, 'CODE_TAXE=0') + 11;
          if (substr($importline,$position1,2) != '11' )
          {
            $position1 = str_contains($importline, 'TYPE_TAXE=') + 10;
            $position2 = $position1 + 1;
            if (substr($importline,$position1,($position2-$position1)) != 'E')
            {
              $position1 = str_contains($importline, 'MONTANT_TAXE=') + 13;
              $position2 = str_contains($importline, '@BASE_TAXE=');
              $calcsihportfees[$i] = $calcsihportfees[$i] + mb_substr($importline,$position1,($position2-$position1));
            }
          }
          else
          {
            $position1 = str_contains($importline, 'TYPE_TAXE=') + 10;
            $position2 = $position1 + 1;
            if (substr($importline,$position1,($position2-$position1)) != 'E')
            {
              $position1 = str_contains($importline, 'MONTANT_TAXE=') + 13;
              $position2 = str_contains($importline, '@BASE_TAXE=');
              $vattaxes = $vattaxes + mb_substr($importline,$position1,($position2-$position1));
            }
          }
        }
      }
    }

    ### Order products by SIH
    
    # create sorttable
    for ($i=1; $i <= $lines; $i++) { $sorttableeligible[$i] = 1; }
    $currentindex = 1;
    $sorttable[1] = 1;
    $sorttableeligible[1] = 0;
    $sorttableindex = 2;
    $startsort = 1;
    while ($currentindex <= $lines && $startsort <= $lines)
    {
      for ($i=$startsort; $i <= $lines; $i++)
      {
        if ($sih[$i] == $sih[$currentindex] && $sorttableeligible[$i] == 1)
        {
          $sorttable[$sorttableindex] = $i;
          $sorttableeligible[$i] = 0;
          $sorttableindex++;
        }
      }
      while ($sorttableeligible[$startsort] == 0 && $startsort <= $lines) { $startsort++; }
      $currentindex = $startsort;
      $sorttable[$sorttableindex] = $currentindex;
      $sorttableeligible[$currentindex] = 0;
      $sorttableindex++;
      $startsort++;
    }

    # sort
    for ($i=1; $i <= $lines; $i++)
    {
      $y = $sorttable[$i];
      $newproductid[$i] = $productid[$y];
      $newproductname[$i] = $productname[$y];
      $newpurchaseprice[$i] = $purchaseprice[$y];
      $newamount[$i] = $amount[$y];
      $newamountcartons[$i] = $amountcartons[$y];
      $newnumberperunit[$i] = $numberperunit[$y];
      $newtransportpricepercarton[$i] = $transportpricepercarton[$y];
      $newweight[$i] = $weight[$y];
      $newsih[$i] = $sih[$y];
      $newcountryid[$i] = $countryid[$y];
      $newtransportcost[$i] = $transportcost[$y];
      $newunloadingcost[$i] = $unloadingcost[$y];
      $newfreightcost[$i] = $freightcost[$y];
      $newfreightcost[$i] = $freightcost[$y];
      $newinsurance[$i] = $insurance[$y];
      $newcif[$i] = $cif[$y];
      $newpurchaseid[$i] = $purchaseid[$y];
      $newuseby[$i] = $useby[$y];
      $newbatchname[$i] = $batchname[$y];
      $newsupplierbatchname[$i] = $supplierbatchname[$y];
    }
    for ($i=1; $i <= $lines; $i++)
    {
      $productid[$i] = $newproductid[$i];
      $productname[$i] = $newproductname[$i];
      $purchaseprice[$i] = $newpurchaseprice[$i];
      $amount[$i] = $newamount[$i];
      $amountcartons[$i] = $newamountcartons[$i];
      $numberperunit[$i] = $newnumberperunit[$i];
      $transportpricepercarton[$i] = $newtransportpricepercarton[$i];
      $weight[$i] = $newweight[$i];
      $sih[$i] = $newsih[$i];
      $countryid[$i] = $newcountryid[$i]; # new fix SIH/country
      $transportcost[$i] = $newtransportcost[$i];
      $unloadingcost[$i] = $newunloadingcost[$i];
      $freightcost[$i] = $newfreightcost[$i];
      $insurance[$i] = $newinsurance[$i];
      $cif[$i] = $newcif[$i];
      $purchaseid[$i] = $newpurchaseid[$i];
      $useby[$i] = $newuseby[$i];
      $batchname[$i] = $newbatchname[$i];
      $supplierbatchname[$i] = $newsupplierbatchname[$i];
    }

    $sihcounter = 1;
    $subtotalcif = 0;
    for ($i=1; $i <= $lines; $i++)
    {
       $subtotalcif = $subtotalcif + $cif[$i];
       if ($sih[$i] != $sih[$i+1])
       {
         $calcsihcode[$sihcounter] = $sih[$i];
         $cifbysih[$sihcounter] = $subtotalcif;
         $subtotalcif = 0;
         $sihcounter = $sihcounter + 1;
       }
    }
    $num_sih = $sihcounter - 1;

    for ($i=1; $i <= $num_sih; $i++)
    {
      $sihportfees[$i] = 0;
      for ($y=1; $y <= $num_lines; $y++)
      {
        if ($calcsihcode[$i] == $readsihcode[$y])
        {
          $sihportfees[$i] = $sihportfees[$i] + $calcsihportfees[$y];
        }
      }
    }

    # Distribute port fees for each SIH
    for ($i=1; $i <= $lines; $i++)
    {
      $portfees[$i] = 0;
      for ($y=1; $y <= $num_sih; $y++)
      {
        # find out which SIH code matches
        if ($sih[$i] == $calcsihcode[$y])
        {
          $portfees[$i] = ($cif[$i] / $cifbysih[$y]) * $sihportfees[$y];
        }
      }
    }

    ?><h2>Cost calculation</h2>
    <table><form method="post" action="purchase.php"><tr><td>Order number: </td><td><b><font color=blue size=+2><?php echo $shipmentid; ?></font></b></td></tr>
    <tr><td>Exchange rate: </td><td> &nbsp; <?php echo $currencyacronym; ?> = <input type="text" STYLE="text-align:right" name="exchangerate" value="<?php echo $exchangerate; ?>" size=15></td></tr>
    <?php
    if ($incotermid != 3)
    {
      echo '<tr><td>Insurance cost: </td><td> &nbsp; ' . $totalinsurance . ' ' . $insurancecurrencyacronym . ' &nbsp; Exchange rate=<input type="text" STYLE="text-align:right" name="insuranceexchangerate" value="' . $insuranceexchangerate . '"  size=15></td></tr>';
      echo '<tr><td>Freightcost: </td><td> &nbsp; ' . $totalfreightcost . ' ' . $freightcostcurrencyacronym . ' &nbsp; Exchange rate=<input type="text" STYLE="text-align:right" name="freightcostexchangerate" value="' . $freightcostexchangerate . '"  size=15></td></tr>';
    }
    echo '<tr><td>Laissez Passer: </td><td><input type="text" STYLE="text-align:right" name="sanitaryfees" size=15> XPF</td></tr>';
    echo '<tr><td>Frigo/autre: </td><td><input type="text" STYLE="text-align:right" name="coldstorage" size=15> XPF</td></tr>';
    echo '</table><br><table class="detailinput">';
    echo '<tr><td><b>ID</b></td><td><b>Produit</b></td><td><b>Q.</b></td><td><b>CIF</b></td><td><b>Transit</b></td><td><b>Debarquement</b></td><td><b>Transport</b></td>
    <td><b>Laissez Passer</b></td><td><b>Frigo/Autre</b></td><td><b>Douane</b></td><td><b>Total</b></td></tr>';
    for ($i=1; $i <= $lines; $i++)
    {
      $total = ($cif[$i] * (1 + $transitmargin)) + $unloadingcost[$i] + $portfees[$i] + $transportcost[$i];
      if ($amountcartons[$i] == 0) { $showamount = $amount[$i]; }
      else { $showamount = $amountcartons[$i]; }
      $showamount = $amount[$i]/$numberperunit[$i] . ' (' . $amountcartons[$i] . ')';
      echo '<tr><td>' . $productid[$i] . ' ['.$supplierbatchname[$i].']</td><td>' . $productname[$i] . '</td><td align=right>' . $showamount . '</td><td align=right>' . ($cif[$i]) . '</td>
      <td align=right>' . ($cif[$i] * $transitmargin) . '</td><td align=right>' . ($unloadingcost[$i]) . '</td><td align=right>' . $transportcost[$i] . '</td><td>A calculer</td>
      <td>A calculer</td><td align=right>' . $portfees[$i] . '</td><td align=right>' . ($total) . '</td></tr>';
      echo '<input type=hidden name=supplierbatchname'.$i.' value="'.$supplierbatchname[$i].'">';
      echo '<input type=hidden name=purchaseid'.$i.' value="'.$purchaseid[$i].'">';
      echo '<input type=hidden name=weight'.$i.' value="'.$weight[$i].'">';
      echo '<input type=hidden name=purchaseprice'.$i.' value="'.$purchaseprice[$i].'">';
      echo '<input type=hidden name=freightcost'.$i.' value="'.$freightcost[$i].'">';
      echo '<input type=hidden name=insurance'.$i.' value="'.$insurance[$i].'">';
      echo '<input type=hidden name=amount'.$i.' value="'.$amount[$i].'">';
      echo '<input type=hidden name=unloadingcost'.$i.' value="'.$unloadingcost[$i].'">';
      echo '<input type=hidden name=portfees'.$i.' value="'.$portfees[$i].'">';
      echo '<input type=hidden name=transportcost'.$i.' value="'.$transportcost[$i].'">';
      echo '<input type=hidden name=productid'.$i.' value="'.$productid[$i].'">';
      echo '<input type=hidden name=productname'.$i.' value="'.$productname[$i].'">';
      echo '<input type=hidden name=showamount'.$i.' value="'.$showamount.'">';
      echo '<input type=hidden name=batchname'.$i.' value="'.$batchname[$i].'">';
      echo '<input type=hidden name=useby'.$i.' value="'.$useby[$i].'">';
    }
    ?>
    </table><br><table>
    <tr><td align="center">
    <input type=hidden name="verifyme" value=1>
    <input type=hidden name="lines" value="<?php echo $lines; ?>">
    <input type=hidden name="currencyacronym" value="<?php echo $currencyacronym; ?>">
    <input type=hidden name="incotermid" value="<?php echo $incotermid; ?>">
    <input type=hidden name="totalinsurance" value="<?php echo $totalinsurance; ?>">
    <input type=hidden name="insurancecurrencyacronym" value="<?php echo $insurancecurrencyacronym; ?>">
    <input type=hidden name="totalfreightcost" value="<?php echo $totalfreightcost; ?>">
    <input type=hidden name="freightcostcurrencyacronym" value="<?php echo $freightcostcurrencyacronym; ?>">
    <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>">
    <input type=hidden name="calctotalweight" value="<?php echo $calctotalweight; ?>">
    <input type=hidden name="arrivaldate" value="<?php echo $arrivaldate; ?>">
    <input type=hidden name="shipmentid" value="<?php echo $shipmentid; ?>">
    <input type=hidden name="vattaxes" value="<?php echo $vattaxes; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form>
    <?php
  }

  
  
  ##############################################################################################################
}
else
{
  ?><h2>Finalize</h2>
  <form enctype="multipart/form-data" method="post" action="purchase.php"><table><tr><td>Order number: </td><td><input autofocus type="text" STYLE="text-align:right" name="shipmentid" size=6></td></tr>
  <tr><td>Fichier: </td><td><input type="hidden" name="MAX_FILE_SIZE" value="100000"><input type="file" name="userfile" size=80></td></tr>
  <tr><td>&nbsp;</td><td><input type="checkbox" name="nosofix" value="1"> Procéder sans fichier SOFIX</td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>"><input type="submit" value="Continuer"></td></tr></table></form>
  <?php
}


?>