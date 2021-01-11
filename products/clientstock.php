<?php
  switch($currentstep)
  {
    # 
    case 0:
    ?>
    <h2>Stock client:</h2>
    <form method="post" action="products.php"><table>

    <tr><td>Client:</td><td><input type="text" id="myfocus" STYLE="text-align:right" name="clientid" size=5></td><td>Nom: <input type="text" id="clientlist" STYLE="text-align:right" name="clientname" size=20></td></tr>

    <tr><td>Produit:</td><td><input type="text" STYLE="text-align:right" name="productiddirect" size=8></td>
    <td>Nom:
    <select name="productid"><?php
    $query = 'select productid,productname,suppliercode,numberperunit,netweightlabel from product';
    if ($_SESSION['ds_useproductcode'] == 1) { $query = $query . ' order by suppliercode'; }
    else { $query = $query . ' order by productname'; }
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      $productname = $row2['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $productname = $row2['suppliercode']; }
      $productname = $productname . ': ' . $row2['productname'] . ' ';
      if ($_SESSION['ds_useunits'] && $row2['numberperunit'] > 1) { $productname = $productname . $row2['numberperunit'] . ' x '; }
      $productname = $productname . $row2['netweightlabel'];
      echo '<option value="' . $row2['productid'] . '">' . $productname . '</option>';
    }
    ?></select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>"><input type=hidden name="recalcstock" value="1"><input type="submit" value="Valider"></td></tr>

    </table></form><?php
    break;

    # 
    case 1:
    $clientid = $_POST['clientid'];
    $clientname = $_POST['clientname'];
    if ($clientid == "" && $clientname != "")
    {
      $query = 'select clientid,clientname from client where clientname="' . $clientname . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results == 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Client inexistant.</font>'; exit; }
      $row = $query_result[0];
      $clientid = $row['clientid'];
      $clientname = $row['clientname'];
    }
    else
    {
      $query = 'select clientid,clientname from client where clientid="' . $clientid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
      $clientname = $row['clientname'];
    }

    $productid = $_POST['productid']; if ($_POST['productiddirect'] != "") { $productid = $_POST['productiddirect']; }
    $query = 'select productname,suppliercode,product.productid,stockdate,currentstock,currentstockrest,margin,numberperunit,netweightlabel,product.unittypeid as unittypeid,unittypename,productname,weight from product,unittype where product.unittypeid=unittype.unittypeid';
    if ($_SESSION['ds_useproductcode'] == 1 && $_POST['productiddirect'] != "") { $query = $query . ' and suppliercode like "%' . $productid . '%" order by suppliercode limit 1'; }
    else { $query = $query . ' and productid="' . $productid . '"'; }
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0) { echo 'Produit inexistant.'; exit; }
    $row = $query_result[0];
    $productid = $row['productid'];
    $productname = $row['productname'] . ' ';
    $numberperunit = $row['numberperunit'];
    if ($_SESSION['ds_useunits'] && $numberperunit > 1) { $productname = $productname . $numberperunit . ' x '; }
    $productname = $productname . $row['netweightlabel'];
    $unittypename = $row['unittypename'];

    $query = 'select stockmod,csmdate,csmcomment from clientstockmod where productid="' . $productid . '" and clientid="' . $clientid . '" and iscount=1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row = $query_result[0];
      $countedstock = $row['stockmod'];
      $counteddate = $row['csmdate'];
      $countedcomment = $row['csmcomment'];
    }
    else
    {
      $countedstock = 0;
      $counteddate = '1990-01-01';
      $countedcomment = '';
      $query = 'insert into clientstockmod (productid,clientid,iscount,stockmod,csmdate,csmcomment) values ("' . $productid . '","' . $clientid . '",1,"' . $countedstock . '","' . $counteddate . '","' . $countedcomment . '")';
      $query_prm = array();
      require('inc/doquery.php');
    }

    $query = 'select sum(quantity) as sales from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=0 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $sales = $row['sales']+0;
    $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=0 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $sales = $sales + $row['sales'];
# temp debug check
#if ($clientid == 7383)
#{
#  echo $query;
#}
    $query = 'select sum(quantity) as returns from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=1 and returntostock=1 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $returns = $row['returns']+0;
    $query = 'select sum(quantity) as returns from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=1 and returntostock=1 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $returns = $returns + $row['returns'];
    $query = 'select sum(stockmod) as adjust from clientstockmod where clientid="' . $clientid . '" and productid="' . $productid . '" and csmdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $adjust = $row['adjust'];

    $currentstock = $countedstock + $sales - $returns + $adjust;
    $neg = 0; if ($currentstock < 0) { $neg = 1; }
    $unitstock = abs($currentstock) % $numberperunit;
    $currentstock = floor(abs($currentstock) / $numberperunit);

    $showclientname = d_output(d_decode($clientid . ': ' . $clientname));
    echo '<h2>Stock client ' . $showclientname . '</h2>';
    echo '<form method="post" action="products.php"><table class=report>';
    echo '<tr><td><font size=-1>Produit:</td><td><font size=+1><b>(';
    if ($_SESSION['ds_useproductcode'] == 1) { echo $suppliercode; }
    else { echo $productid; }
    echo ') ' . $productname;
    echo '</b></font></td></tr>';
    echo '<tr><td><font size=-1>Stock:</td><td><font size=+1><b>';
    if ($neg) { echo ' -'; }
    echo $currentstock;
    if ($unitstock > 0) {  echo ' <font size=-1>' . $unitstock; }
    echo '</font></td></tr></table>';

    $countedstockunits = $countedstock % $numberperunit;
    $countedstock = floor($countedstock / $numberperunit);
    if ($_SESSION['ds_useunits'] && $countedstockunits != 0) { $countedstock = $countedstock . ' <font size=-1>' . $countedstockunits . '</font>'; }

    $salesunits = $sales % $numberperunit;
    $sales = floor($sales / $numberperunit);
    if ($_SESSION['ds_useunits'] && $salesunits != 0) { $sales = $sales . ' <font size=-1>' . $salesunits . '</font>'; }

    $returnsunits = $returns % $numberperunit;
    $returns = floor($returns / $numberperunit);
    if ($_SESSION['ds_useunits'] && $returnsunits != 0) { $returns = $returns . ' <font size=-1>' . $returnsunits . '</font>'; }

    $posadjust = 0; if ($adjust >= 0) { $posadjust = 1; }
    $adjustunits = abs($adjust) % $numberperunit;
    $adjust = floor(abs($adjust) / $numberperunit);
    if ($_SESSION['ds_useunits'] && $adjustunits != 0) { $adjust = $adjust . ' <font size=-1>' . $adjustunits . '</font>'; }

    echo '<table class=report><tr><td colspan=2><b>Entré</td><td colspan=2><b>Sorti</td></tr>';
    echo '<tr><td>';
#echo 'Comptage';
echo datefix2($counteddate);
    echo '</td><td align=right>' . $countedstock . '</td><td align=right>&nbsp;</td><td align=right>&nbsp;</td></tr>';
    echo '<tr><td>Ventes</td><td align=right>' . $sales . '</td><td>Retour avoir</td><td align=right>' . $returns . '</td></tr>';
    if ($posadjust) { echo '<tr><td>Ajustements</td><td align=right>' . $adjust . '</td><td colspan=2>&nbsp;</td></tr>'; }
    else { echo '<tr><td colspan=3>Ajustements</td><td align=right>' . $adjust  . '</td></tr>'; }
    echo '</table>';

  echo '<br><table>';
  echo '<tr><td><input type="checkbox" name="iscount" value="1"> Comptage ';

  # csmdate
    $day = mb_substr($_SESSION['ds_curdate'],8,2);
    $month = mb_substr($_SESSION['ds_curdate'],5,2);
    $year = mb_substr($_SESSION['ds_curdate'],0,4);
    ?><select name="day"><?php
    for ($i=1; $i <= 31; $i++)
    { 
      if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><?php

  echo ' &nbsp; &nbsp; </td><td align=right><input type="text" STYLE="text-align:right" name="endyear" value="' . $endyear . '" size=5> ' . $unittypename;
  if ($_SESSION['ds_useunits'] == 1) { echo ' &nbsp; <input type="text" STYLE="text-align:right; font-size:70%" name="endyearrest" value="' . $endyearrest . '" size=5> unités'; }
  echo '</td><td>&nbsp;</td><td align=right>&nbsp;</td></tr></table>';

    echo '<br><table>';
    echo '<tr><td><select name="mytype"><option value=2>Enlever</option><option value=1>Ajouter</option></select>:</td><td><input type="text" STYLE="text-align:right" name="amount" size=10> ';
    if ($_SESSION['ds_useunits'] && $numberperunit>1) { echo '<select name="cartonorunit"><option value=1>' . $unittypename .'</option><option value=2>Unités</option></select>'; }
    else { echo $unittypename; }
    echo '</tr></table>';
    echo '<br><table>';
    echo '<tr><td>Cause de l\'ajustement:</td><td><input type="text" STYLE="text-align:right" name="comment" size=50></td></tr>';
    echo '<tr><td colspan="2" align="center">&nbsp;<input type=hidden name="productid" value="' . $productid . '"><input type=hidden name="clientid" value="' . $clientid . '"><input type=hidden name="productname" value="' . $productname . '"><input type=hidden name="numberperunit" value="' . $numberperunit . '"></td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="2"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Ajuster"></td></tr>';
    echo '</table></form>';

    echo '<br><br><table cellspacing=1 cellpadding=1 border=1>';
    echo '<tr><td colspan=4><b>Dernières modifications</b></td></tr>';
    echo '<tr><td><b>Type</b></td><td><b>Date</b></td><td><b>Stock</b></td><td><b>Commentaire</b></td></tr>';
    $query = 'select csmdate,csmcomment,stockmod,iscount from clientstockmod where clientid="' . $clientid . '" and productid="' . $productid . '"';
    $query = $query . ' order by csmdate desc LIMIT 10';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $stockmod = $row['stockmod'];
      if ($_SESSION['ds_useunits'] && $numberperunit > 1)
      {
        $negstockmod = 0; if ($row['stockmod'] < 0) { $negstockmod = 1; }
        $stockmodunits = abs($row['stockmod']) % $numberperunit;
        $stockmod = floor(abs($row['stockmod']) / $numberperunit);
        $stockmod = $stockmod . ' <font size=-1>' . $stockmodunits . '</font>';
        if ($negstockmod == 1) { $stockmod = '-' . $stockmod; }
      }
      $type = 'Mod'; if ($row['iscount'] ==1) { $type = 'Comptage'; }
      echo '<tr><td>' . $type . '</td><td>' . datefix2($row['csmdate']) . '</td><td align=right>' . $stockmod . '</td><td>' . $row['csmcomment'] . '</tr>';
    }
    echo '</table>';
    
    # last invoices
    echo '<br><br><table cellspacing=1 cellpadding=1 border=1>';
    echo '<tr><td colspan=4><b>Dernières factures</b></td></tr>';
    echo '<tr><td><b>Type</b></td><td><b>Date</b></td><td><b>Quantité</b></td><td><b>Commentaire</b></td></tr>';
    $query = 'select accountingdate,invoicecomment,quantity,isreturn from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and cancelledid=0';
    $query = $query . ' UNION ';
    $query = $query . 'select accountingdate,invoicecomment,quantity,isreturn from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and cancelledid=0';
    $query = $query . ' order by accountingdate desc LIMIT 10'; # HERE add history
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $ourtype = 'Vente'; if (($row['isreturn']+0) == 1) { $ourtype = 'Retour'; }
      echo '<tr><td>' . $ourtype . '</td><td>' . datefix2($row['accountingdate']) . '</td><td align=right>' . $row['quantity'] . '</td><td>' . $row['invoicecomment'] . '</tr>';
    }
    echo '</table>';

    break;

    case 2:
    $clientid = $_POST['clientid'];
    $productid = $_POST['productid'];
    $amount = $_POST['amount'];
    $numberperunit = $_POST['numberperunit'];

    if ($amount != "" && $_POST['iscount'] != 1)
    {
      $amount = (int) $amount;
      if ($amount > 0)
      {
        if ($_POST['cartonorunit'] != 2) { $amount = $amount * $numberperunit; }
        if ($_POST['mytype'] == 2) { $amount = 0 - $amount; }
        $query = 'insert into clientstockmod (productid,clientid,stockmod,csmdate,csmcomment,iscount) values("' . $productid . '","' . $clientid . '","' . $amount . '",CURDATE(),"' . $_POST['comment'] . '",0)';
        $query_prm = array();
        require('inc/doquery.php');
        echo '<p>Stock client ajusté.</p>';
      }
    }

    if ($_POST['iscount'] == 1)
    {
      
      $csmdate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
      $amount = $_POST['endyear'] * $numberperunit + $_POST['endyearrest'];
      $query = 'update clientstockmod set csmdate="' . $csmdate . '",stockmod="' . $amount . '",csmcomment="' . $_POST['comment'] . '" where clientid="' . $clientid . '" and productid="' . $productid . '" and iscount=1';
      $query_prm = array();
      require('inc/doquery.php');
      echo '<p>Comptage effectué.</p>';
    }

    break;

  }
?>