<?php

# ref http://www.pontikis.net/blog/jquery-ui-autocomplete-step-by-step

if ($_SESSION['ds_userid'] < 1) { exit; }
require('inc/standard.php');
session_write_close(); # do NOT remove this

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) { exit; }

$term = trim($_GET['term']);
if(preg_match("/[^\040\pL\pN_-]/u", $term)) { exit; }
 
$a_json = array();
$a_json_row = array();

require('preload/unittype.php');

$query = 'select suppliercode,productid,productname,netweightlabel,currentstock,salesprice,taxcode,countstock,unittypeid
          from product,taxcode
          where product.taxcodeid=taxcode.taxcodeid
          and notforsale=0 and discontinued=0 and (productname like ? or suppliercode like ?)
          order by productname';
if ($_SESSION['ds_autocompleteoption'] == 1) { $query_prm = array($term . '%',$term . '%'); }
else { $query_prm = array('%' . $term . '%','%' . $term . '%'); }
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $currentstock = $query_result[$i]['currentstock'];
  if ($unittype_dmpA[$query_result[$i]['unittypeid']] > 1) { $currentstock /= $unittype_dmpA[$query_result[$i]['unittypeid']]; }
  $a_json_row["id"] = $query_result[$i]['suppliercode'];
  if ($_SESSION['ds_useproductcode']) { $a_json_row["value"] = $query_result[$i]['suppliercode']; }
  else { $a_json_row["value"] = $query_result[$i]['productid']; }
  $price = ($query_result[$i]['salesprice']+($query_result[$i]['salesprice']*$query_result[$i]['taxcode']/100));
  if ($unittype_dmpA[$query_result[$i]['unittypeid']] > 1) { $price *= $unittype_dmpA[$query_result[$i]['unittypeid']]; }
  $price = round($price);
  $a_json_row["label"] = d_decode($query_result[$i]['productname']);
  if ($query_result[$i]['netweightlabel'] != '') { $a_json_row["label"] .= ' '.$query_result[$i]['netweightlabel']; }
  if ($query_result[$i]['countstock'] && $currentstock > 0) { $a_json_row["label"] .=' ['.$currentstock.']'; }
  $a_json_row["label"] .= ' '.$price .' TTC';
  array_push($a_json, $a_json_row);
};
 
echo json_encode($a_json);

?>