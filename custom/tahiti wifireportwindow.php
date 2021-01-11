<?php

$reportwindow = 1;
require ('inc/top.php');

$report = $_POST['report'];
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }
switch($report)
{
  case 'custom_client':
  
  require('preload/country.php');
  
  echo '<h2>Liste des clients</h2>';
  echo d_table('report');
  #echo '<thead><th>Operation<th>Date<th>Quantité<th>Produit<th>PAMP<th>Prix<th>Marge</thead>';
  
  $query = 'select * from client where issupplier=0 and email<>"" order by client_customdate1';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    echo d_tr();
    echo d_td($main_result[$i]['clientcode']);
    echo d_td($main_result[$i]['clientfirstname']);
    echo d_td(d_decode($main_result[$i]['clientname']));
    echo d_td($main_result[$i]['email']);
    echo d_td($countryA[$main_result[$i]['countryid']]);
    echo d_td($main_result[$i]['address']);
    echo d_td($main_result[$i]['town_name']);
    echo d_td($main_result[$i]['postalcode']);
    echo d_td($main_result[$i]['postaladdress']);
    echo d_td($main_result[$i]['telephone']);
    echo d_td($main_result[$i]['cellphone']);
    echo d_td($main_result[$i]['clientcomment']);
    echo d_td($main_result[$i]['clientfield1']);
    echo d_td($main_result[$i]['client_customdate1']);
  }
  
  break;
  

  default:

  break;
}

require ('inc/bottom.php');

?>


