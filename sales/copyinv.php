<?php

$PA['client'] = '';
require('inc/readpost.php');
require('inc/findclient.php');
$showmenu = 1;

if ($clientid > 0)
{
  $query = 'select invoiceid,accountingdate,invoiceprice,invoicecomment from invoice where cancelledid=0 and clientid=?
  union
  select invoiceid,accountingdate,invoiceprice,invoicecomment from invoicehistory where cancelledid=0 and clientid=?
  order by invoiceid desc limit 20'; # hard limit 20 for now
  $query_prm = array($clientid, $clientid);
  require('inc/doquery.php');
  if ($num_results > 0)
  {
    $showmenu = 0;
    echo '<h2>Copier facture/avoir pour client: ', $clientname,' (', $clientid,')</h2>
    <form method="post" action="sales.php"><table class=report><thead><th><th>Facture/Avoir<th>Date<th>Montant<th>Infos</thead>';
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<tr><td align=right><input type=radio name="invoiceid" value="',$query_result[$i]['invoiceid'],'"><td align=right>', $query_result[$i]['invoiceid'],
      '<td align=right>', datefix2($query_result[$i]['accountingdate']), '<td align=right>', myfix($query_result[$i]['invoiceprice']),'<td>', d_output($query_result[$i]['invoicecomment']);
    }
    echo '<tr><td colspan=10><input name="modify" type="submit" value="Copier"></td></tr>
    <input type=hidden name="salesmenu" value="invoicing"><input type=hidden name="copyinvoice" value="1">
    </table></form>';
  }
}
if ($showmenu)
{
  ?>
  <h2>Copier facture/avoir</h2>
  <form method="post" action="sales.php">
  <table>
  <tr><td>Copier facture/avoir numéro:</td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=8></td></tr>
  <input name="copyinvoice" type="hidden" value="1">
  <tr><td colspan=2><input name="modify" type="submit" value="Copier"></td></tr>
  <input type=hidden name="salesmenu" value="invoicing">
  </table></form>
  <br>
  <br><h2>Copier facture/avoir par client</h2>
  <form method="post" action="sales.php">
  <table>
  <tr><td><?php
  require('inc/selectclient.php');
  ?>
  <tr><td colspan=2><input name="modify" type="submit" value="Copier"></td></tr>
  <input type=hidden name="salesmenu" value="copyinv">
  </table></form>
  <?php
  }
?>