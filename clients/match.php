<script language="JavaScript">
function toggle(source) {

    var node_list = document.getElementsByTagName('input');
    var checkboxes = [];
 
    for (var i = 0; i < node_list.length; i++) {
        var node = node_list[i];
 
        if (node.getAttribute('type') == 'checkbox') {
            checkboxes.push(node);
        }
    } 
 
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>

<script type='text/javascript' src='jq/jquery.js'></script>

<script type='text/javascript'>
$(window).load(function(){
    $('.chkclass').click(function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.value').text());
        });
        $('#sum').html('<b>'+sum);
    });
});
$(window).load(function(){
    $('.chkclass2').click(function() {
        var sum = 0;
        $('.chkclass2:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.value2').text());
        });
        $('#sum2').html('<b>'+sum);
    });
});
</script>

<?php

# 2017 05 26 exluded closing entries from matching

$showmatching = 1;
$client = '';
if (isset($_POST['client'])) { $client = $_POST['client']; }
elseif(isset($_GET['clientid']) && (int) $_GET['clientid'] > 0) { $client = (int) $_GET['clientid']; }
$dp_allowdeletedclients = 1; require('inc/findclient.php');
if ($num_clients != 1)
{
  $showmatching = 0;
    ?><h2>Lettrage</h2>
    <form method="post" action="clients.php">
    <table>
    <tr><td>
    <?php
    $dp_allowdeletedclients = 1; require('inc/selectclient.php');
    ?></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1"><input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
    <input type=hidden name="matching" value="no">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
}
else
{
  if ($client_deleted == 1)
  {
    if (isset($_POST['openaccount']) && $_POST['openaccount'] == 1)
    {
      $query = 'update client set deleted=0 where clientid=?';
      $query_prm = array($clientid);
      require ('inc/doquery.php');
      # find all matchingids with pid 7 "Perte" and unmatch everything but the pertes, then set all pertes to zero
      # because: we do not accept any losses from clients whose accounts are reopened
      $query = 'select distinct matchingid from payment where clientid=? and paymenttypeid=7 and value<>0';
      $query_prm = array($clientid);
      require ('inc/doquery.php');
      $unmatch_result = $query_result; $num_results_unmatch = $num_results;
      for ($i=0;$i<$num_results_unmatch;$i++)
      {
        $matchingid = $unmatch_result[$i]['matchingid'];
        $query = 'update invoicehistory set matchingid=0 where matchingid=?';
        $query_prm = array($matchingid);
        require ('inc/doquery.php');
        #$query = 'update invoicehistory_exp set matchingid=0 where matchingid=?';
        #$query_prm = array($matchingid);
        #require ('inc/doquery.php');
        $query = 'update payment set matchingid=0 where paymenttypeid<>7 and matchingid=?';
        $query_prm = array($matchingid);
        require ('inc/doquery.php');
        #$query = 'update payment_exp set matchingid=0 where paymenttypeid<>7 and matchingid=?';
        #$query_prm = array($matchingid);
        require ('inc/doquery.php');
        $query = 'update adjustment set matchingid=0 where matchingid=?';
        $query_prm = array($matchingid);
        require ('inc/doquery.php');
        $query = 'update payment set value=0 where paymenttypeid=7 and matchingid=?';
        $query_prm = array($matchingid);
        require ('inc/doquery.php');
      }
    }
    else
    {
      echo '<p>Le compte client ' . $clientid . ': ' . d_output($clientname) . ' est fermé.</p>'; $showmatching = 0;
      echo '<form method="post" action="clients.php"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '">';
      echo '<input type="hidden" name="client" value="' . $clientid . '"><input type="checkbox" name="openaccount" value="1">&nbsp;Réouvrir&nbsp;compte<input type="submit" value="Valider"></form>';
    }
  }
}
if ($showmatching)
{
  if (!isset($_POST['closeaccount']) || $_POST['closeaccount'] != 1)
  {
    $clientid_currentid_index = array_search($clientid, $_SESSION['ds_match_clientidA']);
    $num_array = count($_SESSION['ds_match_clientidA']);
    $clientid_before = $clientid_currentid_index - 1; if ($clientid_before < 0) { $clientid_before = $num_array - 1; }
    $clientid_after = $clientid_currentid_index + 1; if ($clientid_after >= $num_array) { $clientid_after = 0; }
    $clientid_before = $_SESSION['ds_match_clientidA'][$clientid_before];
    $clientid_after = $_SESSION['ds_match_clientidA'][$clientid_after];
    echo '<h2><a href="clients.php?clientsmenu=match&clientid=' . $clientid_before . '">&#8592;</a> ';
    echo 'Lettrage client ' . $clientid . ': ' . d_output($clientname);
    echo ' <a href="clients.php?clientsmenu=match&clientid=' . $clientid_after . '"">&#8594;</a></h2>';
  }
  if (isset($_POST['matching']) && $_POST['matching'] == "yes")
  {
    $balance = 0; $totalresults = 0; $credit = 0; $debit = 0;
    for ($i=0; $i < $_POST['num_results1']; $i++)
    {
      $checkboxname = 'inv' . $i; $valueref = 'invval' . $i; # 2017 01 24 rounding all values
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance + myround($_POST[$valueref]); $debit = $debit + myround($_POST[$valueref]); $totalresults++; }
    }
    for ($i=0; $i < $_POST['num_results2']; $i++)
    {
      $checkboxname = 'rei' . $i; $valueref = 'reival' . $i;
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance + myround($_POST[$valueref]); $debit = $debit + myround($_POST[$valueref]); $totalresults++; }
    }
    for ($i=0; $i < $_POST['num_results3']; $i++)
    {
      $checkboxname = 'pay' . $i; $valueref = 'payval' . $i;
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance - myround($_POST[$valueref]); $credit = $credit + myround($_POST[$valueref]); $totalresults++; }
    }
    for ($i=0; $i < $_POST['num_results4']; $i++)
    {
      $checkboxname = 'ret' . $i; $valueref = 'retval' . $i;
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance - myround($_POST[$valueref]); $credit = $credit + myround($_POST[$valueref]); $totalresults++; }
    }
    for ($i=0; $i < $_POST['num_results5']; $i++)
    {
      $checkboxname = 'add' . $i; $valueref = 'addval' . $i;
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance + myround($_POST[$valueref]); $debit = $debit + myround($_POST[$valueref]); $totalresults++; }
    }
    for ($i=0; $i < $_POST['num_results6']; $i++)
    {
      $checkboxname = 'adc' . $i; $valueref = 'adcval' . $i;
      if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1) { $balance = $balance - myround($_POST[$valueref]); $credit = $credit + myround($_POST[$valueref]); $totalresults++; }
    }
    if ($totalresults > 0 && $balance != 0)
    {
      echo '<br><b><font color="' . $_SESSION['ds_alertcolor'] . '">Le lettrage n\'a pas reussi:</font></b> &nbsp; Debit= <i>' . myfix($debit) . '</i> &nbsp; Credit= <i>' . myfix($credit) . '</i><br><br>';
    }
    if ($totalresults > 0 && $balance == 0)
    {
      ### MATCH
      $query = 'insert into matching (userid,date,clientid) values (?,CURDATE(),?)';
      $query_prm = array($_SESSION['ds_userid'], $clientid);
      require ('inc/doquery.php');
      $matchingid = $query_insert_id;
      for ($i=0; $i < $_POST['num_results1']; $i++)
      {
        $checkboxname = 'inv' . $i; $ref = 'invref' . $i;
        if (isset($_POST[$checkboxname]) && $_POST[$checkboxname] == 1)
        {
          $query = 'update invoicehistory set matchingid=? where invoiceid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');/*
          $query = 'select adjustmentgroupid from adjustmentgroup where integrated=1 and reference=?';
          $query_prm = array($_POST[$ref]);
          require ('inc/doquery.php');
          $agid = $query_result[0]['adjustmentgroupid'];
          $query = 'update adjustment set matchingid=? where nomatch=1 and adjustmentgroupid=?';
          $query_prm = array($matchingid, $agid);
          require ('inc/doquery.php');*/
        }
      }
      for ($i=0; $i < $_POST['num_results2']; $i++)
      {
        $checkboxname = 'rei' . $i; $ref = 'reiref' . $i;
        if ($_POST[$checkboxname] == 1)
        {
          $query = 'update payment set matchingid=? where paymentid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');/*
          $query = 'select adjustmentgroupid from adjustmentgroup where integrated=2 and reference=?';
          $query_prm = array($_POST[$ref]);
          require ('inc/doquery.php');
          $agid = $query_result[0]['adjustmentgroupid'];
          $query = 'update adjustment set matchingid=? where nomatch=1 and adjustmentgroupid=?';
          $query_prm = array($matchingid, $agid);
          require ('inc/doquery.php');*/
        }
      }
      for ($i=0; $i < $_POST['num_results3']; $i++)
      {
        $checkboxname = 'pay' . $i; $ref = 'payref' . $i;
        if ($_POST[$checkboxname] == 1)
        {
          $query = 'update payment set matchingid=? where paymentid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');/*
          $query = 'select adjustmentgroupid from adjustmentgroup where integrated=2 and reference=?';
          $query_prm = array($_POST[$ref]);
          require ('inc/doquery.php');
          $agid = $query_result[0]['adjustmentgroupid'];
          $query = 'update adjustment set matchingid=? where nomatch=1 and adjustmentgroupid=?';
          $query_prm = array($matchingid, $agid);
          require ('inc/doquery.php');*/
        }
      }
      for ($i=0; $i < $_POST['num_results4']; $i++)
      {
        $checkboxname = 'ret' . $i; $ref = 'retref' . $i;
        if ($_POST[$checkboxname] == 1)
        {
          $query = 'update invoicehistory set matchingid=? where invoiceid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');/*
          $query = 'select adjustmentgroupid from adjustmentgroup where integrated=1 and reference=?';
          $query_prm = array($_POST[$ref]);
          require ('inc/doquery.php');
          $agid = $query_result[0]['adjustmentgroupid'];
          $query = 'update adjustment set matchingid=? where nomatch=1 and adjustmentgroupid=?';
          $query_prm = array($matchingid, $agid);
          require ('inc/doquery.php');*/
        }
      }
      for ($i=0; $i < $_POST['num_results5']; $i++)
      {
        $checkboxname = 'add' . $i; $ref = 'addref' . $i;
        if ($_POST[$checkboxname] == 1)
        {
          $query = 'update adjustment set matchingid=? where adjustmentid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');
        }
      }
      for ($i=0; $i < $_POST['num_results6']; $i++)
      {
        $checkboxname = 'adc' . $i; $ref = 'adcref' . $i;
        if ($_POST[$checkboxname] == 1)
        {
          $query = 'update adjustment set matchingid=? where adjustmentid=?';
          $query_prm = array($matchingid, $_POST[$ref]);
          require ('inc/doquery.php');
        }
      }
      echo '<br><b><font color=blue>Lettrage reussi:</font></b> &nbsp; Debit= <i>' . myfix($debit) . '</i> &nbsp; Credit= <i>' . myfix($credit) . '</i><br><br>';
    }
  }
  
  if (isset($_POST['closeaccount']) && $_POST['closeaccount'] == 1)
  {
    $query = 'update client set deleted=1 where clientid=?';
    $query_prm = array($clientid);
    require('inc/doquery.php');
    echo '<h2 class="alert">Compte client ' . $clientid . ': ' . d_output($clientname) . ' fermé</h2>';
  }
  else
  {
    echo '<form method="post" action="clients.php"><table><tr><td valign=top>';
    
    $totaldebit = 0; $totalcredit = 0; $colspan = 6; if ($_SESSION['ds_matching_extended_info']) { $colspan++; }

    # DEBIT
    echo '<table class="detailinput" style="white-space: normal">';
    echo '<tr><td colspan='.$colspan.'><font size=+1><b>DEBIT</td></tr>';
    $query = 'select accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference,invoicecomment
    from invoicehistory
    where cancelledid<1 and matchingid=0 and isreturn=0 and confirmed=1 and clientid=?
    order by accountingdate asc,invoiceid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results1 = $num_results;
    if ($num_results1 > 0)
    {
      echo '<tr><td><b>Facture<td><b>Date<td><b>Montant<td>
      <td colspan=2><b>'.$_SESSION['ds_term_reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Commentaires'; }
    }
    for ($i=0; $i < $num_results1; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'inv' . $i; $ref = 'invref' . $i; $valueref = 'invval' . $i;
      echo '<tr><td align=right><a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['id'] . '" target=_blank">' . myfix($row['id']) . '</a>';
      echo '<td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass" type="checkbox" name="' . $checkboxname . '" value="1">';
      echo '<td colspan=2>' . $row['reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['invoicecomment']; }
      echo '<td class="sum value" style="display:none;">',($row['totalprice']+0);
      $totaldebit = $totaldebit + $row['totalprice'];
    }
    $query = 'select forinvoiceid,paymentcomment,paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=1 and matchingid=0 and clientid=? order by paymentdate,paymentid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results2 = $num_results;
    if ($num_results2 > 0)
    {
      echo '<tr><td><b>Remb.</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><td><b>Pour facture n<sup>o</sup><td><b>Numéro chèque';
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Info'; }
    }
    for ($i=0; $i < $num_results2; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'rei' . $i; $ref = 'reiref' . $i; $valueref = 'reival' . $i;
      $myid = myfix($row['id']);
      if ($row['paymenttypeid'] == 7) { $myid = '<span class="alert">Perte ' . $myid . '</span>'; }
      echo '<tr><td align=right>' . $myid . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass" type="checkbox" name="' . $checkboxname . '" value="1">';
      echo '<td>'; if ($row['forinvoiceid'] > 0) { echo $row['forinvoiceid']; }
      echo '<td>' . $row['chequeno'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['paymentcomment']; }
      echo '<td class="sum value" style="display:none;">',($row['totalprice']+0);
      $totaldebit = $totaldebit + $row['totalprice'];
    }
    $query = 'select adjustmentdate as date,adjustment.adjustmentgroupid,adjustmentid as id,value as totalprice,adjustmentcomment
    ,reference,adjustmentcomment_line
    from adjustment,adjustmentgroup
    where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
    and debit=1 and matchingid=0 and nomatch=0 and closing=0 and deleted=0 and accountingnumberid=1 and referenceid=? and integrated<4
    order by adjustmentdate,adjustmentid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results5 = $num_results;
    if ($num_results5 > 0)
    {
      echo '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>
      <td><b>'.$_SESSION['ds_term_accounting_comment'].'<td><b>'.$_SESSION['ds_term_accounting_reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Infos'; }
    }
    for ($i=0; $i < $num_results5; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'add' . $i; $ref = 'addref' . $i; $valueref = 'addval' . $i;
      $date = $row['date'];
      echo '<tr><td align=right>' . myfix($row['adjustmentgroupid']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass" type="checkbox" name="' . $checkboxname . '" value="1"></td>
      <td>' . $row['adjustmentcomment'] . '<td>' . $row['reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['adjustmentcomment_line']; }
      echo '<td class="sum value" style="display:none;">',($row['totalprice']+0);
      $totaldebit = $totaldebit + $row['totalprice'];
    }
    echo '<tr><td colspan=2><b>Total coché<td id="sum" align=right><td colspan=4>';
    echo '</table>';

    echo '</td><td width=25></td><td valign=top>';

    # CREDIT
    echo '<table class="detailinput" style="white-space: normal">';
    echo '<tr><td colspan='.$colspan.'><font size=+1><b>CREDIT</td></tr>';
    $query = 'select forinvoiceid,paymentcomment,paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=0 and matchingid=0 and clientid=? order by paymentdate,paymentid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results3 = $num_results;
    if ($num_results3 > 0)
    {
      echo '<tr><td><b>Paiement</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><td><b>Pour facture n<sup>o</sup><td><b>Numéro chèque';
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Info'; }
    }
    for ($i=0; $i < $num_results3; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'pay' . $i; $ref = 'payref' . $i; $valueref = 'payval' . $i;
      $myid = myfix($row['id']);
      if ($row['paymenttypeid'] == 7) { $myid = '<span class="alert">Perte ' . $myid . '</span>'; }
      echo '<tr><td align=right>' . $myid . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass2" type="checkbox" name="' . $checkboxname . '" value="1">';
      echo '<td>'; if ($row['forinvoiceid'] > 0) { echo $row['forinvoiceid']; }
      echo '<td>' . $row['chequeno'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['paymentcomment']; }
      echo '<td class="sum2 value2" style="display:none;">',($row['totalprice']+0);
      $totalcredit = $totalcredit + $row['totalprice'];
    }
    $query = 'select accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference,invoicecomment from invoicehistory where cancelledid<1 and matchingid=0 and isreturn=1 and confirmed=1 and clientid=? order by accountingdate,invoiceid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results4 = $num_results;
    if ($num_results4 > 0)
    {
      echo '<tr><td><b>Avoir<td><b>Date<td><b>Montant<td>
      <td colspan=2><b>'.$_SESSION['ds_term_reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Commentaires'; }
    }
    for ($i=0; $i < $num_results4; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'ret' . $i; $ref = 'retref' . $i; $valueref = 'retval' . $i;
      echo '<tr><td align=right><a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['id'] . '" target=_blank">' . myfix($row['id']) . '</a>';
      echo '<td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass2" type="checkbox" name="' . $checkboxname . '" value="1">';
      echo '<td colspan=2>' . $row['reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['invoicecomment']; }
      echo '<td class="sum2 value2" style="display:none;">',($row['totalprice']+0);
      $totalcredit = $totalcredit + $row['totalprice'];
    }
    $query = 'select adjustmentdate as date,adjustment.adjustmentgroupid,adjustmentid as id,value as totalprice,adjustmentcomment
    ,reference,adjustmentcomment_line
    from adjustment,adjustmentgroup
    where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
    and debit=0 and matchingid=0 and nomatch=0 and closing=0 and deleted=0 and accountingnumberid=1 and referenceid=? and integrated<4
    order by adjustmentdate,adjustmentid';
    $query_prm = array($clientid);
    require ('inc/doquery.php');
    $num_results6 = $num_results;
    if ($num_results6 > 0)
    {
      echo '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>
      <td><b>'.$_SESSION['ds_term_accounting_comment'].'<td><b>'.$_SESSION['ds_term_accounting_reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td><b>Infos'; }
    }
    for ($i=0; $i < $num_results6; $i++)
    {
      $row = $query_result[$i];
      $checkboxname = 'adc' . $i; $ref = 'adcref' . $i; $valueref = 'adcval' . $i;
      $date = $row['date'];
      echo '<tr><td align=right>' . myfix($row['adjustmentgroupid']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><input type=hidden name="' . $ref . '" value="' . $row['id'] . '"><input type=hidden name="' . $valueref . '" value="' . $row['totalprice'] . '"><input class="chkclass2" type="checkbox" name="' . $checkboxname . '" value="1">
      <td>' . $row['adjustmentcomment'] . '<td>' . $row['reference'];
      if ($_SESSION['ds_matching_extended_info']) { echo '<td>' . $row['adjustmentcomment_line']; }
      echo '<td class="sum2 value2" style="display:none;">',($row['totalprice']+0);
      $totalcredit = $totalcredit + $row['totalprice'];
    }
    echo '<tr><td colspan=2><b>Total coché<td id="sum2" align=right><td colspan=4>';
    echo '</table>';

    ?></td></tr>
    <?php
    $totalresults = $num_results1 + $num_results2 + $num_results3 + $num_results4 + $num_results5 + $num_results6;
    $customok = 1;
    
    ### custom test to see if we can close account
    $customfilename = mb_strtolower('custom/' . $_SESSION['ds_customname']) . 'closeaccountok.php';
    if (file_exists($customfilename)) { require($customfilename); }
    ###
    
    if ($totalresults == 0 && $customok == 1)
    {
      echo '<tr><td colspan="5" align="center"><span class="alert"><input type="checkbox" name="closeaccount" value="1">&nbsp;Fermer&nbsp;compte</span>';
    }
    elseif ($totalresults > 0)
    {
      echo '<tr><td colspan="2" align="center"><input type="checkbox" onClick="toggle(this)"> Tout cocher</td></tr>';
    }
    else
    {
      echo '<tr><td colspan="3" align="center">Ce compte ne peut être fermé pour des raisons spécifiques à ' . $_SESSION['ds_customname'] . '.</td></tr>';
    }
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
    <input type=hidden name="matching" value="yes">
    <input type=hidden name="num_results1" value="<?php echo $num_results1; ?>">
    <input type=hidden name="num_results2" value="<?php echo $num_results2; ?>">
    <input type=hidden name="num_results3" value="<?php echo $num_results3; ?>">
    <input type=hidden name="num_results4" value="<?php echo $num_results4; ?>">
    <input type=hidden name="num_results5" value="<?php echo $num_results5; ?>">
    <input type=hidden name="num_results6" value="<?php echo $num_results6; ?>">
    <input type=hidden name="client" value="<?php echo $clientid; ?>">
    <?php
    #echo '<input type=hidden name="clientname" value="' . d_output($clientname) . '">';
    ?>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    if ($totalcredit != $totaldebit)
    {
      $amount = myfix(abs($totalcredit-$totaldebit));
      echo '<br><h2>Total ';
      if ($totalcredit > $totaldebit)
      {
        echo ' crédit: '.$amount.' &nbsp; &nbsp; &nbsp;
        <i>Vous devez '.myfix(abs($totalcredit-$totaldebit)).' francs à ce client.</i>';
      }
      else
      {
        echo ' débit: '.$amount.' &nbsp; &nbsp; &nbsp;
        <i>Ce client vous doit '.myfix(abs($totalcredit-$totaldebit)).' francs.</i>';
      }
      echo '</h2>';
    }
    /*
    echo '<table class="report"><tr><td><b>Total debit</b>:</td><td align=right>' . myfix($totaldebit);
    echo '</td></td><td></tr><tr><td><b>Total credit</b>:</td><td align=right>' . myfix($totalcredit);
    echo '</td></td><td></tr><tr><td><b>Balance</b>:</td><td align=right>' . myfix(abs($totalcredit-$totaldebit));
    if ($totalcredit > $totaldebit) { echo '</td><td>(crédit)'; }
    if ($totaldebit > $totalcredit) { echo '</td><td>(débit)'; }
    echo '</td></tr></table>';*/
  }
}

  

if ($showmatching)
{
  echo '<br><div class="myblock">';
  echo '<p>Pour fermer un compte, tout lettrer d\'abord.</p>';
  echo '<p>Pour réouvrir le compte client: allez dans le menu lettrer, puis taper votre numéro de compte client.</p>';
  echo '<p>Quand un compte est réouvert, les pertes sonts effacées et les operations concernées délettrées.</p>';
  echo '</div>';
}

if (!$showmatching)
{
  unset($_SESSION['ds_match_clientidA']);
  echo '<br><br>';
  $query = 'select distinct invoicehistory.clientid,clientname from invoicehistory,client
  where invoicehistory.clientid=client.clientid
  and confirmed=1 and cancelledid=0 and matchingid=0 and invoiceprice>0
  UNION
  select distinct payment.clientid,clientname from payment,client
  where payment.clientid=client.clientid
  and matchingid=0 and value>0
  UNION
  select distinct adjustment.referenceid as clientid,clientname from adjustment,adjustmentgroup,client
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.referenceid=client.clientid
  and matchingid=0 and nomatch=0 and closing=0 and adjustmentgroup.deleted=0 and accountingnumberid=1
  order by clientname';
  $query_prm = array();
  require ('inc/doquery.php');
  echo d_table("report");
  for ($i=0; $i < $num_results; $i++)
  {
    $_SESSION['ds_match_clientidA'][$i] = $query_result[$i]['clientid'];
    echo d_tr();
    echo d_td_unfiltered('<a href="clients.php?clientsmenu=match&clientid='.$query_result[$i]['clientid'].'">'.d_decode($query_result[$i]['clientname']).'</a>');
  }
  echo d_table_end();
}

?>