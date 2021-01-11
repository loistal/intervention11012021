<?php

if (isset($_POST['product']))
{
  $product = $_POST['product'];
  require('inc/findproduct.php');
}
else { $productid = -1; }

if ($productid < 1)
{
  ?><h2>Ajuster stock pour un produit:</h2>
  <form method="post" action="products.php"><table><tr><td>
  <?php require('inc/selectproduct.php'); ?>
  <tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>"><input type="submit" value="Valider"></td></tr>
  </table></form><?php
}
else
{
  require('preload/unittype.php');
  require('preload/modifiedstockreason.php');
  
  if (isset($_POST['specificbatchid'])) { $specificbatchid = (int) $_POST['specificbatchid']; }
  else { $specificbatchid = -1; }

  $query = 'select suppliercode,numberperunit,netweightlabel,product.unittypeid as unittypeid,productname from product where countstock=1 and productid=?';
  $query_prm = array($productid);
  require('inc/doquery.php');
  if ($num_results == 0) { header("refresh:0; url=products.php?productsmenu=modifystock"); exit; }
  $row = $query_result[0];
  $suppliercode = $row['suppliercode'];
  $currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
  $lastyear = $currentyear-1;
  $productname = d_decode($row['productname']) . ' ';
  $numberperunit = $row['numberperunit'];
  $npu = $numberperunit;
  if ($_SESSION['ds_useunits'] && $numberperunit > 1) { $productname = $productname . $numberperunit . ' x '; }
  $productname = $productname . $row['netweightlabel'];
  $unittypeid = $row['unittypeid'];
  $unittypename = $unittypeA[$unittypeid];
  $dmp = $unittype_dmpA[$unittypeid];
  if (isset($_POST['endyearrest'])) { $endyearrest = $_POST['endyearrest']; }
  else { $endyearrest = 0; }
  
  if ($_SESSION['ds_stockperuser'])
  {
    $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
    $query_prm = array();
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    for ($i=0; $i < $num_results_main; $i++)
    {
      $stockperuserA[$main_result[$i]['userid']] = $main_result[$i]['username'];
      $dp_userid = $main_result[$i]['userid'];
      require('inc/calcstock_user.php');
      $stock_userA[$dp_userid] = $userstock;
      ###
      if (isset($_POST['newstock_'.$main_result[$i]['userid']]))
      {
        $newstock = (int) $_POST['newstock_'.$main_result[$i]['userid']];
        # insert modifiedstock_user
        if ($newstock != $stock_userA[$dp_userid])
        {
          $amount = $newstock - $stock_userA[$dp_userid];
          $query = 'insert into modifiedstock_user (productid,netchange,netvalue,changedate,changetime,userid,foruserid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?,?)';
          $query_prm = array($productid, $amount, 0, $_SESSION['ds_userid'], $dp_userid, $_POST['comment'], ($_POST['modifiedstockreasonid']+0));
          require('inc/doquery.php');
          if ($num_results) { $stock_userA[$dp_userid] = $newstock; }
        }
      }
      ###
    }
  }

  if (isset($_POST['doadjust']) && $_POST['doadjust'] == 1)
  {
    if (isset($_POST['addlot']) && $_POST['addlot'] == 1)
    {
      $query = 'insert into purchasebatch (productid,arrivaldate,origamount,amount,cost,totalcost,vat,userid,useby,placementid) values (?,curdate(),0,0,0,0,0,?,curdate(),1)';
      $query_prm = array($productid, $_SESSION['ds_userid']);
      require('inc/doquery.php');
    } 
    ### start update endyear
    $endyear = ($_POST['endyear'] * $numberperunit * $dmp) + $endyearrest;  
    $query = 'select stock from endofyearstock where productid=? and year=?';
    $query_prm = array($productid, $lastyear);
    require('inc/doquery.php');
    if ($num_results) { $stock = $query_result[0]['stock']; }
    else { $stock = 0; }
    if ($endyear != $stock)
    {
      if ($num_results > 0) { $query = 'update endofyearstock set stock=? where productid=? and year=?'; }
      else { $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)'; }
      $query_prm = array($endyear, $productid, $lastyear);
      require('inc/doquery.php');
      echo 'Stock fin année modifié.<br>';
    }
    ### end update endyear
    
    ### adjustment
    if ($_POST['amount'] != "")
    {
      $amount = (int) ($_POST['amount']);
      $amount = d_abs($amount);
      if (isset($_POST['cartonorunit']) && $_POST['cartonorunit'] == 2)
      {
        $showamount = $amount . ' sous-unités';
      }
      else
      {
        $showamount = ($amount/$dmp) . ' ' . d_output($unittypename);
        $amount = $amount * $numberperunit;
      }
      if ($_POST['mytype'] == 2) { $amount = 0 - $amount; $showamount = '-'.$showamount; }
      else { $showamount = '+'.$showamount; }
      $query = 'select prev,origamount,totalcost,vat from purchasebatch where productid=?';
      $query_prm = array($productid);
      if ($specificbatchid > 1) { $query .= ' and purchasebatchid=?'; array_push($query_prm, $specificbatchid); }
      $query .= ' order by ';
      #if ($_SESSION['ds_useemplacement']) { $query .= 'placementrank asc,'; } TODO verify this 
      $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
      require('inc/doquery.php');
      $row = $query_result[0];
      $prev = $row['prev']+0;
      ### backwards compat
      if ($prev == 0)
      {
        if ($row['origamount'] == 0) { $prev = 0; }
        else { $prev = (($row['totalcost']-$row['vat'])*$numberperunit)/$row['origamount']; }
      }
      ###
      $netvalue = $amount * ($prev/$numberperunit) / $dmp;
      if ($amount != 0)
      {
        $query = 'insert into modifiedstock (productid,netchange,netvalue,changedate,changetime,userid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?)';
        $query_prm = array($productid, $amount, $netvalue, $_SESSION['ds_userid'], $_POST['comment'], ($_POST['modifiedstockreasonid']+0));
        require('inc/doquery.php');
        # TODO here convert to unit/subunits (?)
        echo 'Ajustement de ' . $showamount . ' effectué.';
        if ($_SESSION['ds_stockperuser'])
        {
          $foruserid = (int) $_POST['foruserid'];
          if ($foruserid > 0)
          {
            $query = 'insert into modifiedstock_user (productid,netchange,netvalue,changedate,changetime,userid,foruserid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?,?)';
            $query_prm = array($productid, $amount, 0, $_SESSION['ds_userid'], $foruserid, $_POST['comment'], ($_POST['modifiedstockreasonid']+0));
            require('inc/doquery.php');
            $stock_userA[$foruserid] += $amount;
          }
        }
      }
    }
    ###
    
    $wearedone = 0;
    if ($specificbatchid < 1) { $wearedone = 1; }
    
    if ($wearedone == 0)
    {
      $numberperunit = $_POST['numberperunit'];
      $modif = 0;
      if (isset($_POST['changedlv']) && $_POST['changedlv'] != "")
      {
        $useby = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
        
        $query = 'update purchasebatch set useby="' . $useby . '" where purchasebatchid="' . $specificbatchid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['changeplacement']) && $_POST['changeplacement'] != "")
      {
        $query = 'update purchasebatch set placementid="' . $_POST['placementid'] . '" where purchasebatchid="' . $specificbatchid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['changelotsize']) && $_POST['changelotsize'] != "")
      {
        $amount = (int) $_POST['newsize'];
        $amount = $amount * $numberperunit * $dmp;
        $query = 'update purchasebatch set amount="' . $amount . '" where purchasebatchid="' . $specificbatchid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['changebatchname']) && $_POST['changebatchname'] != "")
      {
        $query = 'update purchasebatch set batchname=? where purchasebatchid=?';
        $query_prm = array($_POST['newbatchname'], $specificbatchid);
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['changeprev']) && $_POST['newprev'] != "")
      {
        $query = 'update purchasebatch set prev=? where purchasebatchid=?';
        $query_prm = array($_POST['newprev']+0, $specificbatchid);
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['changesupplierbatchname']) && $_POST['changesupplierbatchname'] != "")
      {
        $query = 'update purchasebatch set supplierbatchname=? where purchasebatchid=?';
        $query_prm = array($_POST['newsupplierbatchname'], $specificbatchid);
        require('inc/doquery.php');
        $modif = 1;
      }
      if (isset($_POST['deletelot']) && $_POST['deletelot'] != "")
      {
        $query = 'update purchasebatch set deleted=1 where purchasebatchid="' . $specificbatchid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $modif = 1;
      }
      if ($modif) { echo '<p>Lot ' . $specificbatchid . ', ' . $_POST['productname'] . ' a été modifié.</p><br>'; } # TODO need better reference than $specificbatchid
    }
    
  }
  
 
  ?><h2>Ajuster stock:</h2>
  <form method="post" action="products.php"><table class="report">
  <tr><td valign=top><font size=-1>Produit:</td><td><font size=+1><b>(
  <?php
  if ($_SESSION['ds_useproductcode'] == 1) { echo $suppliercode; }
  else { echo $productid; }
  echo ') ' . $productname;
  if ($dmp != 1)
  {
    echo '<br><span class=alert>Ce produits est mesuré avec '.substr_count($dmp,0).' décimales.</span>';
  }
  ?>
  </b></font></td></tr>
  <?php

  require ('inc/calcstock.php');

  if ($currentstock < 0 && $unitstock <> 0) { $currentstock = $currentstock + 1; }
  echo '<tr><td><font size=-1>Stock:</td><td><font size=+1><b>' . $currentstock/$dmp;
  if ($unitstock > 0) {  echo ' <font size=-1>' . $unitstock; }
  echo '</font></td></tr>';
  echo '<tr><td><font size=-1>Reservations:</td></td><td>';
  $query = 'select sum(quantity) as reservations from invoice,invoiceitem where invoice.invoiceid=invoiceitem.invoiceid and isreturn=0 and productid=?';
  $query_prm = array($productid);
  require('inc/doquery.php');
  $res = $query_result[0]['reservations'];
  $showres = floor($res/$numberperunit); $showresleft = $res%$numberperunit;
  if ($dmp > 1) { $showres = $showres / $dmp; }
  echo $showres; if ($showresleft) { echo ' <font size=-1>' . $showresleft . '</font>'; }
  echo ' (factures non confirmées)</td></tr></table>';

  echo '<table class="report"><tr><td colspan=2><b>Entré</td><td colspan=2><b>Sorti</td></tr>';
  echo '<tr><td>Fin année</td><td align=right><input type="text" STYLE="text-align:right" name="endyear" value="' . $endyear/$dmp . '" size=8>';
  if ($numberperunit > 1) { echo '<input type="text" STYLE="text-align:right; font-size:70%" name="endyearrest" value="' . $endyearrest . '" size=5>'; }
  echo '</td><td>&nbsp;</td><td align=right>&nbsp;</td></tr>';
  echo '<tr><td>Achats</td><td align=right>' . $purchases/$dmp; if ($purchasesrest) { echo ' <span class="small">' . $purchasesrest . '</span>'; }
  echo '</td><td>Ventes</td><td align=right>' . $sales/$dmp; if ($salesrest) { echo ' <span class="small">' . $salesrest . '</span>'; }
  echo '</td></tr>';
  echo '<tr><td>Retour avoir</td><td align=right>' . $returns/$dmp; if ($returnsrest) { echo ' <span class="small">' . $returnsrest . '</span>'; }
  echo '</td><td>&nbsp;</td><td align=right>&nbsp;</td></tr>';
  if ($posadjust)
  {
    echo '<tr><td>Ajustements</td><td align=right>' . $adjust/$dmp; if ($adjustrest) { echo ' <span class="small">' . $adjustrest . '</span>'; }
    echo '</td><td colspan=2>&nbsp;</td></tr>';
  }
  else
  {
    echo '<tr><td colspan=3>Ajustements</td><td align=right>-' . d_abs($adjust/$dmp); if ($adjustrest) { echo ' <span class="small">' . d_abs($adjustrest) . '</span>'; }
    echo '</td></tr>';
  }
  echo '</table>';
  
  if ($_SESSION['ds_stockperuser'])
  {
    echo '<br><table class="report"><thead><th>';
    foreach ($stockperuserA as $userid => $username)
    {
      echo '<th>',d_output($username);
    }
    echo '<tr><td>Stock par utilisateur :';
    foreach ($stockperuserA as $userid => $username)
    {
      echo '<td><input type=text STYLE="text-align:right" name="newstock_'.$userid.'" value="'.$stock_userA[$userid].'" size=8';
      if (!$_SESSION['ds_systemaccess']) { echo ' disabled'; }
      echo '>';
    }
    echo '</table>';
  }

  echo '<br><table>';
  echo '<tr><td><select name="mytype"><option value=2>Enlever</option><option value=1>Ajouter</option></select>:</td><td><input type="text" STYLE="text-align:right" name="amount" size=10> ';
  if ($_SESSION['ds_useunits'] && $numberperunit>1)
  {
    echo '<select name="cartonorunit"><option value=1>' . $unittypename . '</option><option value=2>Unités</option></select>';
  }
  else
  {
    if ($dmp == 1000 && mb_strtolower($unittypename) == 'kg') { echo 'grammes'; }# 2014 07 09 hack for now, need subunittype field for dmp>1
    else { echo $unittypename; }
  }
  
  if ($_SESSION['ds_stockperuser'])
  {
    echo ' chez <select name="foruserid">';
    if ($_SESSION['ds_systemaccess'])
    {
      echo '<option value=0></option>';
      $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
      $query_prm = array();
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        echo '<option value='.$query_result[$i]['userid'].'>'.d_output($query_result[$i]['username']).'</option>';
      }
    }
    else
    {
      $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 and userid=?';
      $query_prm = array($_SESSION['ds_userid']);
      require('inc/doquery.php');
      echo '<option value='.$query_result[0]['userid'].'>'.d_output($query_result[0]['username']).'</option>';
    }
    echo '</select>';
  }
  
  if (isset($modifiedstockreasonA))
  {
    $dp_itemname = 'modifiedstockreason'; $dp_description = 'Raison'; $dp_noblank = 1;
    require('inc/selectitem.php');
  }
  echo '</table>';

  # list of all purchasebatches
  $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate
  ,origamount,amount,totalcost,vat,useby,purchaseid,purchasebatchgroupid,description';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
  $query = $query . ' from purchasebatch,usertable';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
  $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
  $query = $query . ' and productid="' . $productid . '"';
  $query = $query . ' order by ';
  #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } TODO
  $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
  if ($num_results_main <= 0)
  {
    $query = 'insert into purchasebatch (productid,arrivaldate,origamount,amount,cost,totalcost,vat,userid,useby,placementid)';
    $query = $query . ' values ("' . $productid . '",curdate(),0,0,0,0,0,"' . $_SESSION['ds_userid'] . '",curdate(),1)';
    $query_prm = array();
    require('inc/doquery.php');
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
    $query = $query . ' from purchasebatch,usertable';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
    $query = $query . ' where purchasebatch.userid=usertable.userid';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
    $query = $query . ' and productid="' . $productid . '"';
    $query = $query . ' order by ';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } # TODO
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc'; #echo $query;
    $query_prm = array();
    require('inc/doquery.php');
  }
  echo '<br><table class="report"><tr><td>&nbsp;</td><td><b>ID</b><td><b>Dossier</b></td><td><b>Conteneur<td><b>Batch</b></td>';
  if ($_SESSION['ds_usesofix'] == 1) { echo '<td><b>Pallet ID fourn.'; }
  echo '<td><b>Arrivé le</b></td><td><b>Utilisateur</b></td><td><b>Procuré</b></td><td><b>Taille</b></td><td><b>En stock</b></td><td><b>Prix Rev</b></td>';
  if ($_SESSION['ds_usedlv']) { echo '<td><b>DLV</b></td>'; }
  if ($_SESSION['ds_useemplacement']) { echo '<td><b>Emplacement</b></td></tr>'; }
  $showemptylots = 100;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $spb = '';
    if ($_SESSION['ds_usesofix'] == 1 && $row['purchaseid']>0)
    {
      $query = 'select supplier_pallet_barcode from purchase where purchaseid=?';
      $query_prm = array($row['purchaseid']);
      require('inc/doquery.php');
      if ($num_results)
      {
        $spb = $query_result[0]['supplier_pallet_barcode'];
      }
    }
    if ($showemptylots > -1)
    {
      $shipmentid = $row['shipmentid']; if ($shipmentid < 1) { $shipmentid = '&nbsp;'; }
      $lotsize = $row['amount'];
      $showlotsize = floor($lotsize/$numberperunit); $showlotsizerest = $lotsize%$numberperunit;
      $showlotorigsize = floor($row['origamount']/$numberperunit); $showlotorigsizerest = $lotsize%$numberperunit;
      if ($_SESSION['ds_useunits'] && $showlotsizerest)
      {
        $showlotsize = $showlotsize . ' <font size=-1>' . $showlotsizerest . '</font>';
        $showlotorigsize = $showlotorigsize . ' <font size=-1>' . $showlotorigsizerest . '</font>';
      }
      $stock = $stock - $lotsize;
      $amountleft = $lotsize;
      if ($stock < 0) { $amountleft = $amountleft + $stock; }
      if ($amountleft < 0) { $amountleft = 0; }
      $showamountleft = floor(($amountleft/$numberperunit)/$dmp); $showamountleftrest = $amountleft%$numberperunit;
      if ($_SESSION['ds_useunits'] && $showamountleftrest) { $showamountleft = $showamountleft . ' <font size=-1>' . $showamountleftrest . '</font>'; }
      if ($stock <= 0) { $showemptylots--; }
      $prev = $row['prev']+0;
      ### backwards compat
      if ($prev == 0)
      {
        if ($row['origamount'] == 0) { $prev = 0; }
        else { $prev = (($row['totalcost']-$row['vat'])*$numberperunit)/$row['origamount']; }
      }
      ###
      echo '<tr><td align=right><input type=radio name="specificbatchid" value="' . $row['purchasebatchid'] . '"></td><td align=right>' . $row['purchasebatchid'] . '</td><td align=right>' . $shipmentid . '</td><td align=right>' . d_output($row['batchname']) . '</td>';
      echo '<td align=right>' . d_output($row['supplierbatchname']) . '</td>'; if ($_SESSION['ds_usesofix'] == 1) { echo '<td>'.$spb; }
      echo '<td>' . datefix($row['arrivaldate']) . '</td>
      <td>' . $row['initials'] . '</td>
      <td align=right>' . $showlotorigsize/$dmp . '</td>
      <td align=right>' . $showlotsize/$dmp . '</td>
      <td align=right>' . $showamountleft . '</td>
      <td align=right>' . $prev . '</td>';
      if ($_SESSION['ds_usedlv']) { echo '<td>' . datefix($row['useby']) . '</td>'; }
      if ($_SESSION['ds_useemplacement']) { echo '<td>' . $row['placementname'] . ' (' . $row['warehousename'] . ')</td>'; }
      if ($row['description'] != '')
      {
        echo '<td>',$row['description'];
      }
    }
  }
  echo '<tr><td><input type="checkbox" name="addlot" value="1"></td><td colspan=10>Ajouter nouveau lot</td></tr>';
  echo '</table>';
  
  if ($_SESSION['ds_usedlv'])
  {
    echo '<br><table><tr><td><input type="checkbox" name="changedlv" value="1"></td><td>Ajuster DLV:</td>';
      echo '<td><select name="day">';
      for ($y=1; $y <= 31; $y++)
      { 
        echo '<option value="' . $y . '">' . $y . '</option>';
      }
      echo '</select><select name="month">';
      for ($y=1; $y <= 12; $y++)
      {
        echo '<option value="' . $y . '">' . $y . '</option>';
      }
      echo '</select><select name="year">';
      for ($y=$_SESSION['ds_startyear']; $y <= $_SESSION['ds_endyear']; $y++)
      {
        if ($y == mb_substr($_SESSION['ds_curdate'],0,4)) { echo '<option value="' . $y . '" SELECTED>' . $y . '</option>'; }
        else { echo '<option value="' . $y . '">' . $y . '</option>'; }
      }
      echo '</td>';
    echo '</tr></table>';
  }
  if ($_SESSION['ds_useemplacement'])
  {
    echo '<br><table><tr><td><input type="checkbox" name="changeplacement" value="1"></td><td>Modifier emplacement:</td>';
      echo '<td><select name="placementid">';
      $query = 'select placementid,placementname,warehousename from placement,warehouse where placement.warehouseid=warehouse.warehouseid order by warehousename,placementname';
      $query_prm = array();
      require('inc/doquery.php');
      for ($y=0; $y < $num_results; $y++)
      {
        $row2 = $query_result[$y];
        echo '<option value="' . $row2['placementid'] . '">' . $row2['placementname'] . ' (' . $row2['warehousename'] . ')</option>';
      }
      echo '</select></td>';
    echo '</tr></table>';
  }
  echo '<br><table><tr><td><input type="checkbox" name="changeprev" value="1"></td><td>Modifier Prix Rev: <input type="text" STYLE="text-align:right" name="newprev" size=10></td></tr></table>';
  echo '<br><table><tr><td><input type="checkbox" name="changebatchname" value="1"></td><td>Modifier Conteneur: <input type="text" STYLE="text-align:right" name="newbatchname" size=10></td></tr></table>';
  echo '<br><table><tr><td><input type="checkbox" name="changesupplierbatchname" value="1"></td><td>Modifier Batch: <input type="text" STYLE="text-align:right" name="newsupplierbatchname" size=10></td></tr></table>';
  echo '<br><table><tr><td><input type="checkbox" name="changelotsize" value="1"></td><td>Modifier taille: <input type="text" STYLE="text-align:right" name="newsize" size=10></td></tr></table>';
  echo '<br><table><tr><td><input type="checkbox" name="deletelot" value="1"></td><td>Supprimer lot</td></tr></table>';

  echo '<br><table>';
  echo '<tr><td>Infos:</td><td><input type="text" STYLE="text-align:right" name="comment" size=50></td></tr>';
  echo '<tr><td colspan="2" align="center">&nbsp;<input type=hidden name="productid" value="' . $productid . '"><input type=hidden name="productname" value="' . $productname . '"><input type=hidden name="numberperunit" value="' . $numberperunit . '"></td></tr>';
  $productcodetosend = $productid; if ($_SESSION['ds_useproductcode'] == 1) { $productcodetosend = $product; }
  echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="doadjust" value="1"><input type=hidden name="product" value="' . $productcodetosend . '"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Modifier"></td></tr>';
  
  echo '</table></form>';
}
?>