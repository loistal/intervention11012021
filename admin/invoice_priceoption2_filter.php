<?php

$PA['add'] = '';
$PA['invoice_priceoption2_filterid'] = 'uint';
$PA['invoice_priceoption2id'] = 'uint';
require('inc/readpost.php');

echo '<h2>Gestion : Filtre '.d_output($_SESSION['ds_term_invoice_priceoption2']).'</h2><br>';

if ($add == d_trad('add'))
{
  $query = 'insert into invoice_priceoption2_filter (invoice_priceoption2_filtername) values ("Nouveau")';
  $query_prm = array();
  require('inc/doquery.php');
  $invoice_priceoption2_filterid = $query_insert_id;
}

if ($invoice_priceoption2_filterid < 1)
{
  require('preload/invoice_priceoption2_filter.php');
  echo '<form method="post" action="admin.php"><table>';
  if (isset($invoice_priceoption2_filterA))
  { $dp_itemname = 'invoice_priceoption2_filter'; $dp_noblank = 1; require('inc/selectitem.php'); }
  echo '<tr><td colspan="2" align="center"><input type=hidden name="adminmenu" value="' . $adminmenu . '">';
  if (isset($invoice_priceoption2_filterA)) { echo '<input type="submit" value="' . d_trad('modify') . '"><br><br>'; }
  echo '<input type="submit" name="add" value="' . d_trad('add') . '"></table></form>';
}
else
{
  if (isset($_POST['name']))
  {
    $query = 'update invoice_priceoption2_filter set invoice_priceoption2_filtername=? where invoice_priceoption2_filterid=?';
    $query_prm = array($_POST['name'],$invoice_priceoption2_filterid);
    require('inc/doquery.php');
  }
  require('preload/invoice_priceoption2.php');
  require('preload/invoice_priceoption2_filter.php');
  if ($invoice_priceoption2id > 0)
  {
    $query = 'insert into invoice_priceoption2_filter_matrix (invoice_priceoption2_filterid,invoice_priceoption2id) values (?,?)';
    $query_prm = array($invoice_priceoption2_filterid,$invoice_priceoption2id);
    require('inc/doquery.php');
  }
  echo '<form method="post" action="admin.php"><table class="report">
  <tr><td>Nom:<td><input type=text name="name" value="'.d_input($invoice_priceoption2_filterA[$invoice_priceoption2_filterid])
  .'"><tr><td colspan=2>&nbsp;';
  $query = 'select invoice_priceoption2_filter_matrix.invoice_priceoption2id,invoice_priceoption2name
  from invoice_priceoption2_filter_matrix,invoice_priceoption2
  where invoice_priceoption2_filter_matrix.invoice_priceoption2id=invoice_priceoption2.invoice_priceoption2id
  and invoice_priceoption2_filterid=? order by invoice_priceoption2name';
  $query_prm = array($invoice_priceoption2_filterid);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  if ($num_results_main) { echo '<tr><td>'.d_output($_SESSION['ds_term_invoice_priceoption2']).'<td>Supprimer</thead>'; }
  for ($i=0; $i < $num_results_main; $i++)
  {
    $invoice_priceoption2id = $main_result[$i]['invoice_priceoption2id'];
    if (isset($_POST['delete'.$invoice_priceoption2id]))
    {
      $query = 'delete from invoice_priceoption2_filter_matrix where invoice_priceoption2_filterid=? and invoice_priceoption2id=?';
      $query_prm = array($invoice_priceoption2_filterid,$invoice_priceoption2id);
      require('inc/doquery.php');
    }
    else
    {  
      echo d_tr();
      echo d_td($invoice_priceoption2A[$invoice_priceoption2id]);
      echo d_td_unfiltered('<input type=checkbox name="delete'.$invoice_priceoption2id.'" value=1>','center');
    }
  }
  echo d_tr(1);
  echo d_td('Ajouter:');
  $dp_itemname = 'invoice_priceoption2'; require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center"><input type=hidden name="adminmenu" value="' . $adminmenu . '">
  <input type=hidden name="invoice_priceoption2_filterid" value="' . $invoice_priceoption2_filterid . '">
  <input type="submit" value="' . d_trad('save') . '"></table></form>';
}

?>