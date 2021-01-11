<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Tere Uta TEST</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      if ($_SESSION['ds_systemaccess'])
      {
        echo '&nbsp; <a href="custom.php?custommenu=import">Import fact de TEREVAU</a><br>';
      }
      ?>
      <br>
    </div>
  </div>

<?php
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
  $_SESSION['ds_showsqldebug'] = 1;
  
  echo 'test simple, une copie de facture 10 de Terevau va etre créé:';
  # TODO
  # need to load to temporary (empty) table, then change invoiceid/invoiceitemid
  # create tere_uta_invoice and tere_uta_invoiceitem
  /*
  create table if not exists tere_uta_invoice like invoice;
  alter table tere_uta_invoice modify column invoiceid int unsigned default null;
  create table if not exists tere_uta_invoiceitem like invoiceitem;
  alter table tere_uta_invoiceitem modify column invoiceitemid int unsigned default null;
  */
  $invoiceid = 10;
  
  $query = 'truncate table tere_uta_invoice;truncate table tere_uta_invoiceitem;';
  $query_prm = array();
  require('inc/doquery.php');
  
  $query = 'insert into tere_uta_invoice select * from temsaas_terevau.invoicehistory where invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  
  $query = 'select invoiceitemid from temsaas_terevau.invoiceitemhistory where invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  $item_result = $query_result; $num_results_item = $num_results; unset($query_result, $num_results);

  for ($y=0; $y < $num_results_item; $y++)
  {
    $row2 = $item_result[$y];
    $query = 'insert into tere_uta_invoiceitem select * from temsaas_terevau.invoiceitemhistory where invoiceitemid=?';
    $query_prm = array($row2['invoiceitemid']);
    require('inc/doquery.php');
  }
  $_SESSION['ds_showsqldebug'] = 0;
  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>