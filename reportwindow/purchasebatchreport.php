<?php

require('preload/unittype.php');

$t_packaging = d_trad('packaging');
$t_arrivaldate = d_trad('arrivaldate');
$t_SBD = d_trad('SBD');
$t_wholesalepricewithouttax = d_trad('wholesalepricewithouttax');
$t_value = d_trad('value');

$PA['product'] = 'product';
$PA['datetype'] = 'uint';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['excludesupplier'] = 'uint';
$PA['client'] = 'supplier';
$PA['temperatureid'] = 'int';
$PA['show_arrivaldate'] = 'uint';
$PA['show_amount'] = 'uint';
$PA['show_useby'] = 'uint';
$PA['show_prev'] = 'uint';
$PA['show_value'] = 'uint';
require('inc/readpost.php');

$t1 = $t2 = 0;

session_write_close();

$query_prm = array($startdate, $stopdate);
$query = 'select supplierid,product.productid,taxcodeid,productname,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname
                 ,suppliercode,prev,arrivaldate,amount,useby,unittypeid,salesprice
                 from product,productfamily,productfamilygroup,productdepartment,purchasebatch
                 where purchasebatch.productid=product.productid and product.productfamilyid=productfamily.productfamilyid
                 and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
if ($datetype == 1) { $query .= ' and useby>=? and useby<=?'; }
else { $query .= ' and arrivaldate>=? and arrivaldate<=?'; }
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productid > 0) { $query .= ' and product.productid=?'; array_push($query_prm, $productid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
if ($temperatureid > 0) { $query .= ' and product.temperatureid=?'; array_push($query_prm, $temperatureid); }
if ($datetype == 1) { $query .= ' order by useby asc,productname'; }
else { $query .= ' order by arrivaldate asc,productname'; }
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

if ($datetype == 1) { $temp = 'DLV '; }
else { $temp = 'arrivé '; }
$title = 'Lots de stock '.$temp.datefix($startdate,'short').' à '.datefix($stopdate,'short');
showtitle_new($title);
require('inc/showparams.php');
echo d_table('report');


echo '<thead><th colspan=2>Produit<th>' . $t_packaging;
if ($show_arrivaldate) { echo '<th>' . $t_arrivaldate; }
if ($show_amount) { echo '<th>Taille'; }
if ($show_useby) { echo '<th>' . $t_SBD; }
if ($show_prev) { echo '<th>Prix Revient'; }
if ($show_value) { echo '<th>' . $t_value; }
echo '</thead>';

for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  echo d_tr();
  echo d_td($row['productid'], 'int');
  echo d_td(d_decode($row['productname']));
  echo d_td($row['numberperunit'] . ' x ' . $row['netweightlabel'], 'right');
  if ($show_arrivaldate) { echo d_td($row['arrivaldate'], 'date'); }
  if ($show_amount)
  {
    echo d_td(floor(($row['amount']/$unittype_dmpA[$row['unittypeid']])/$row['numberperunit']), 'int');
    $t1 += floor(($row['amount']/$unittype_dmpA[$row['unittypeid']])/$row['numberperunit']);
  }
  if ($show_useby) { echo d_td($row['useby'], 'date'); }
  if ($show_prev) { echo d_td($row['prev'], 'currency'); }
  if ($show_value)
  { 
    echo d_td($row['prev'] * (floor(($row['amount']/$unittype_dmpA[$row['unittypeid']])/$row['numberperunit'])), 'currency');
    $t2 += $row['prev'] * (floor(($row['amount']/$unittype_dmpA[$row['unittypeid']])/$row['numberperunit']));
  }
}
echo d_tr(1),'<td colspan=3>';
if ($show_arrivaldate) { echo '<td>'; }
if ($show_amount) { echo '<td align=right>',myfix($t1); }
if ($show_useby) { echo '<td>'; }
if ($show_prev) { echo '<td>'; }
if ($show_value) { echo '<td align=right>',myfix($t2); }
echo d_table_end();

?>