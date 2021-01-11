<?php

$NB_MAX_PRODUCTS = 50;
$currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
$saved = 0;

require('inc/autocomplete_product.php');
require('preload/modifiedstockreason.php');

$PA['save'] = 'uint';
$PA['changedate'] = 'date';
$PA['modifiedstockreasonid'] = 'uint';
$PA['userid'] = 'uint';
$PA['alsoglobal'] = 'uint';
$PA['comment'] = '';
require('inc/readpost.php');
if ($changedate == '') { $changedate = $_SESSION['ds_curdate']; }

for ($i=0; $i < $NB_MAX_PRODUCTS; $i++)
{
  $prodid[$i] = '';
  $productnameA[$i] = '';
  $postamount[$i] = '';
  $utn[$i] = '';
  $stockA[$i] = '';
  $changetext[$i] = '';
  
  if (isset($_POST['productid' . $i]) && $_POST['productid' . $i] != "")
  {
    $product = $_POST['productid' . $i];
    require('inc/findproduct.php');
    if ($productid > 0)
    {
      $query = 'select generic,discontinued,notforsale,productname,brand,supplierid,numberperunit,taxcode,netweightlabel,unittypename,displaymultiplier,suppliercode,product.productid
      from product,taxcode,unittype
      where product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid
      and productid=?';
      $query_prm = array($productid);
      require('inc/doquery.php');
      $row = $query_result[0];
      $dmp[$i] = $row['displaymultiplier'];
      $prodid[$i] = $_POST['productid' . $i]; if ($_SESSION['ds_useproductcode'] == 1) { $prodid[$i] = $row['suppliercode']; }
      $postamount[$i] = $_POST['amount' . $i]*$dmp[$i];

      $discont = '<font color=' . $_SESSION['ds_alertcolor'] . '>';
      if ($row['discontinued']) { $discont = $discont . d_trad('discontinued'); }
      if ($row['discontinued'] && $row['notforsale']) { $discont = $discont . " "; }
      if ($row['notforsale']) { $discont = $discont . d_trad('notforsale'); }
      $discont = $discont . "</font> ";
      if ($row['discontinued'] || $row['notforsale']) { $productnameA[$i] = $discont; }
      else { $productnameA[$i] = ""; }
      $productnameA[$i] = $productnameA[$i] . $row['productname'] . ' ';
      if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productnameA[$i] = $productnameA[$i] . $row['numberperunit'] . ' x '; }
      $productnameA[$i] = $productnameA[$i] . $row['netweightlabel'];
      $utn[$i] = $row['unittypename'];
      
      if ($_SESSION['ds_stockperuser'] && $userid > 0)
      {
        $npu = $row['numberperunit'];
        $dp_userid = $userid;
        require('inc/calcstock_user.php');
        $stockA[$i] = $userstock/$dmp[$i]; if ($$userunitstock != 0) { $stockA[$i] .= ' <font size=-1>'.$userunitstock.'</font>'; }
        $newamount = $postamount[$i] * $npu;
        $netchange = $newamount - $stock;
        if ($netchange != 0)
        {
          $changetext[$i] = floor($netchange / $npu);
          if ($netchange % $npu != 0) { $changetext[$i] .= ' <font size=-1>'.($netchange % $npu).'</font>'; }
          if ($save)
          {
            ############################
            $query = 'select prev,origamount,totalcost,vat from purchasebatch where productid=?';
            $query_prm = array($productid);
            $query .= ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
            require('inc/doquery.php');
            $prev = $query_result[0]['prev']+0;
            $netvalue = $netchange * ($prev/$npu) / $dmp[$i];
            ############################
            $query = 'insert into modifiedstock_user (productid,netchange,netvalue,changedate,changetime,userid,foruserid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?,?)';
            $query_prm = array($productid, $netchange, $netvalue, $_SESSION['ds_userid'], $dp_userid, $comment, $modifiedstockreasonid);
            require('inc/doquery.php');
            if ($num_results) { $saved = 1; }
            if ($alsoglobal)
            {
              $query = 'insert into modifiedstock (productid,netchange,netvalue,changedate,changetime,userid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?)';
              $query_prm = array($productid, $netchange, $netvalue, $_SESSION['ds_userid'], $comment, $modifiedstockreasonid);
              require('inc/doquery.php');
            }
          }
        }
      }
      else
      {
        $numberperunit = $row['numberperunit'];
        require('inc/calcstock.php');
        $stockA[$i] = $currentstock/$dmp[$i]; if ($unitstock != 0) { $stockA[$i] .= ' <font size=-1>'.$unitstock.'</font>'; }
        $newamount = $postamount[$i] * $numberperunit;
        $netchange = $newamount - $stock;
        if ($netchange != 0)
        {
          $changetext[$i] = floor($netchange / $numberperunit);
          if ($netchange % $numberperunit != 0) { $changetext[$i] .= ' <font size=-1>'.($netchange % $numberperunit).'</font>'; }
          if ($save)
          {
            ############################
            $query = 'select prev,origamount,totalcost,vat from purchasebatch where productid=?';
            $query_prm = array($productid);
            $query .= ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
            require('inc/doquery.php');
            $prev = $query_result[0]['prev']+0;
            $netvalue = $netchange * ($prev/$numberperunit) / $dmp[$i];
            ############################
            $query = 'insert into modifiedstock (productid,netchange,netvalue,changedate,changetime,userid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?)';
            $query_prm = array($productid, $netchange, $netvalue, $_SESSION['ds_userid'], $comment, $modifiedstockreasonid);
            require('inc/doquery.php');
            if ($num_results) { $saved = 1; }
          }
        }
      }
      
      if ($postamount[$i] == 0) { $postamount[$i] = ''; }
      else { $postamount[$i] /= $dmp[$i]; }
    }
  }
}
if ($saved) { echo '<p>Comptage effectué.</p><br>'; }

echo '<h2>Comptage / Inventaire</h2>';
echo '<form method="post" action="products.php"><table><tr><td>Date:<td>';
$datename = 'changedate'; require('inc/datepicker.php');
if (isset($modifiedstockreasonA))
{
  echo '<tr><td>';
  $dp_itemname = 'modifiedstockreason'; $dp_description = 'Raison'; $dp_noblank = 1; $dp_selectedid = $modifiedstockreasonid;
  require('inc/selectitem.php');
}

if ($_SESSION['ds_stockperuser'])
{
  echo '<tr><td>Stock pour:<td><select name="userid"><option value=0></option>';
  $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo '<option value='.$query_result[$i]['userid']; if ($userid == $query_result[$i]['userid']) { echo ' selected'; }
    echo '>'.$query_result[$i]['username'].'</option>';
    $stockperuserA[$query_result[$i]['userid']] = $query_result[$i]['username'];
  }
  echo '</select> &nbsp; <input type=checkbox name="alsoglobal" value=1';
  if ($alsoglobal) { echo ' checked'; }
  echo '> Aussi modifier stock global';
}

echo '<tr><td>Infos:<td><input type=text name="comment" value="'.$comment.'" size=80>
</table><div class="center"><button type="submit" name="refresh" value="1">' . d_trad('update') . '</button> &nbsp; &nbsp; &nbsp;
<button type="submit" name="save" value="1">' . d_trad('validate') . '</button>
<input type=hidden name="productsmenu" value="' . $productsmenu . '"></div>';

echo d_table('report'),'<thead><th colspan=3>Produit<th>Stock<th>Comptage<th>Ajustement net</thead>';

for ($i=0; $i < $NB_MAX_PRODUCTS; $i++)
{
  echo d_tr().'<td>'.($i+1).'
  <td><input type="text" STYLE="text-align:right" name="productid' . $i . '" id="product_autocomplete' . $i . '" autocomplete="off" value="' . $prodid[$i] . '" size=10>
  <td>' . $productnameA[$i] . '<td align=right>' . $stockA[$i] . '
  <td><input type="text" STYLE="text-align:right" name="amount' . $i . '" value="' . $postamount[$i] . '" size=5> '.$utn[$i].'<td align=right>' . $changetext[$i];
}

echo d_table_end(),'<div class="center"><button type="submit" name="refresh" value="1">' . d_trad('update') . '</button> &nbsp; &nbsp; &nbsp;
<button type="submit" name="save" value="1">' . d_trad('validate') . '</button></div>';

?>