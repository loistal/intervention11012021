<?php

# ref http://www.pontikis.net/blog/jquery-ui-autocomplete-step-by-step

if ($_SESSION['ds_userid'] < 1) { exit; }
require('inc/standard.php');
session_write_close(); # do NOT remove this

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) { exit; } # prevent direct access

$term = trim($_GET['term']);
if(preg_match("/[^\040\pL\pN_-]/u", $term)) { exit; }
 
$a_json = array();
$a_json_row = array();

$query = 'select clientid,clientname
          from client
          where deleted=0 and blocked<>1';
if ($_SESSION['ds_allowedclientlist'] != '') { $query .= ' and clientid in ' . $_SESSION['ds_allowedclientlist']; }
$query .= ' and clientname like ? order by clientname';
if ($_SESSION['ds_autocompleteoption'] == 1) { $query_prm = array($term . '%'); }
else { $query_prm = array('%' . $term . '%'); }
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $a_json_row["id"] = $query_result[$i]['clientname'];
  $a_json_row["value"] = $query_result[$i]['clientname'];
  $a_json_row["label"] = d_decode($query_result[$i]['clientname']);
  array_push($a_json, $a_json_row);
};
 
echo json_encode($a_json);

?>