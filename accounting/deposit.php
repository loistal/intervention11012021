<?php

# TODO remboursements!
# TODO add possibility to cancel
# TODO modify certains fields like depositdate
# and refactor...

require('preload/bankaccount.php');

$showmenu = 1;

$PA['listme'] = 'int';
$PA['createme'] = 'int';
$PA['userid'] = 'int';
require('inc/readpost.php');

if (!isset($bankaccountA))
{
  echo 'Veuillez configurer un compte bancaire (Admin => Ajouter Compte bancaire).';
  $listme = $createme = $showmenu = 0;
}

if($createme)
{
  # TODO check if each line is already deposited (to prevent refresh of deposit page)
  
  $num_create = (int) $_POST['num_create']; if ($num_create > 1000) { $num_create = 1000; }
  $depositid = - 1;
  $datename = 'depositdate'; require('inc/datepickerresult.php'); # TODO BUG why does it come back empty
  if ($depositdate == ''
  || $depositdate == '0000-00-00'
  || $depositdate === NULL
  || $depositdate < '2000-00-00') { $depositdate = $_SESSION['ds_curdate']; }
  $employeeid = (int) $_POST['employeeid'];
  $depositbankaccountid = (int) $_POST['bankaccountid'];
  $counter = 0; $depositbankid = 0;
  $payment_type_valueA = array();
  $totalvalue = 0;
  for ($i=0; $i < $num_create; $i++)
  {
    if (isset($_POST['confirmed'.$i])) { $id = (int) $_POST['confirmed'.$i]; } else { $id = 0; }
    if ($id > 0)
    {
      $counter++;
      if ($depositid < 0)
      {
        $query = 'insert into deposit (depositdate,employeeid,userid,depositcomment,depositbankaccountid) values (?,?,?,?,?)';
        $query_prm = array($depositdate, $employeeid, $_SESSION['ds_userid'], $_POST['depositcomment'], $depositbankaccountid);
        require('inc/doquery.php');
        $depositid = $query_insert_id;
        $query = 'select bankid,accountingnumberid from bankaccount where bankaccountid=?';
        $query_prm = array($depositbankaccountid);
        require('inc/doquery.php');
        $depositbankid = $query_result[0]['bankid'];
        $deposit_accountingnumberid = $query_result[0]['accountingnumberid'];
      }
      ###
      $query = 'select paymenttypeid,value from payment where paymentid=?';
      $query_prm = array($id);
      require('inc/doquery.php');
      $paymenttypeid = (int) $query_result[0]['paymenttypeid'];
      if (!isset($payment_type_valueA[$paymenttypeid])) { $payment_type_valueA[$paymenttypeid] = 0; }
      $payment_type_valueA[$paymenttypeid] += $query_result[0]['value'];
      ###
      $query = 'update payment set depositid=?,depositbankid=?,depositdate=? where paymentid=?'; # depositbankid and depositdate are for backwards compat only
      $query_prm = array($depositid, $depositbankid, $depositdate, $id);
      require('inc/doquery.php');
    }
  }
  if ($depositid > 0)
  {
    if (count($payment_type_valueA) != 1) { $paymenttypeid = 0; }
    $query = 'update deposit set num_payments=?,paymenttypeid=?,toacc=1,value=? where depositid=?';
    $query_prm = array($counter, $paymenttypeid, array_sum($payment_type_valueA), $depositid);
    require('inc/doquery.php');
    echo '<p><a href="printwindow.php?report=showdeposit&depositid='.$depositid.'" target=_blank>Dépôt numéro '.$depositid.' créé.</a></p><br>';
    
    ###
    if ($_SESSION['ds_directtoacc'] == 1)
    {
      ###
      $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
      $query_prm = array();
      require('inc/doquery.php');
      $min_adjustmentdate = $query_result[0]['adjustmentdate'];
      ###
      
      if ($depositdate >= $min_adjustmentdate)
      {
        $comment = 'Dépôt';
        $debit = 0; $total = 0;
        $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated) values (?, ?, curdate(), curtime(), ?, ?, 3)';
        $query_prm = array($_SESSION['ds_userid'], $depositdate, $comment, $depositid);
        require('inc/doquery.php');
        $adjustmentgroupid = $query_insert_id;
        foreach ($payment_type_valueA as $paymenttypeid => $value)
        {
          if ($value > 0)
          {
            $query = 'select accountingnumberid from paymenttype where paymenttypeid=?';
            $query_prm = array($paymenttypeid);
            require('inc/doquery.php');
            $accountingnumberid = $query_result[0]['accountingnumberid'];
            $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
            $query_prm = array($debit, $adjustmentgroupid, $value, $accountingnumberid);
            require('inc/doquery.php');
            $total += $value;
          }
        }
        if ($debit == 1) { $debit = 0; }
        else { $debit = 1; }
        # debit the total to the bankaccount
        $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
        $query_prm = array($debit, $adjustmentgroupid, $total, $deposit_accountingnumberid);
        require('inc/doquery.php');
      }
    }
    ###

  }
}

if ($listme || $createme)
{
  # javascript also used in sales/confirm.php - TODO separate in a module
  ?>
  <script type='text/javascript' src='jq/jquery.js'></script>
  <script type='text/javascript'>
  $(document).ready(function(){
  // source http://www.formget.com/checkuncheck-all-checkboxes-using-jquery/
  $("#confirmall").attr("data-type","check");
  $("#confirmall").click(function(){
  if($("#confirmall").attr("data-type")==="check")
  {
  $(".confirm").prop("checked",true);
  $("#confirmall").attr("data-type","uncheck");
  }
  else
  {
  $(".confirm").prop("checked",false);
  $("#confirmall").attr("data-type","check");
  }
  })
  });
  </script>
  <?php
  require('preload/paymenttype.php');
  require('preload/employee.php');
  $showmenu = 0;
  $paymenttypeid = (int) $_POST['paymenttypeid'];
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  
  $query = 'select paymentid,paymenttypeid,initials,paymentdate,value,chequeno,payer,payment.clientid,clientname,payment.employeeid,paymentcomment,forinvoiceid
  from payment,usertable,client
  where payment.userid=usertable.userid and payment.clientid=client.clientid and depositid=0 and paymentdate>=? and paymentdate<=? and reimbursement=0'; # TODO show if reimbursement !!!!
  $query_prm = array($startdate, $stopdate);
  if ($paymenttypeid > 0) { $query .= ' and paymenttypeid=?'; array_push($query_prm, $paymenttypeid); }
  if ($userid > 0) { $query .= ' and payment.userid=?'; array_push($query_prm, $userid); }
  $query .= ' limit 1000';
  require('inc/doquery.php');
  $num_create = $num_results;
  
  echo '<h2>Dépôt</h2>
  <form method="post" action="accounting.php"><table class=report><thead><th>Paiement<th>Date<th>Client<th>Valeur<th>Pour facture<th>Utilisateur<th>Employé<th>Type<th>Cheque<th>Info<th>À déposer</thead>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];

    echo '<tr><td align=right>' . myfix($row['paymentid']) . '<td align=right>' . datefix($row['paymentdate'], 'short') . '</td>
    <td><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $row['clientid'] . '" target=_blank>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</a>
    <td align=right>' . myfix($row['value']) . '</td>
    <td align=right>' . myfix($row['forinvoiceid']) . '</td>
    <td>' . d_output($row['initials']);
    if (isset($employeeA[$row['employeeid']])) { echo '<td>' . d_output($employeeA[$row['employeeid']]); } else { echo '<td>'; }
    echo '<td>' . d_output($paymenttypeA[$row['paymenttypeid']]) . '<td>' . d_output($row['chequeno']) . '<td>' . d_output($row['paymentcomment']) . '</td>
    <td> &nbsp; &nbsp; <input type="checkbox" class="confirm" name="confirmed' . $i . '" value="' . $row['paymentid'] . '">';
  }
  echo '<tr><td colspan=6>Compte de dépôt : '; $dp_itemname = 'bankaccount'; $dp_noblank = 1; $dp_notable = 1; require('inc/selectitem.php');
  echo '<td colspan=4>Date de dépôt : '; $datename = 'depositdate'; $dp_setempty = 1; require('inc/datepicker.php');
  echo '<td> &nbsp; <input type="button" id="confirmall" value="Tous">';
  echo '<tr><td colspan=3>Employé: '; $dp_itemname = 'employee'; $dp_notable = 1; require('inc/selectitem.php');
  echo '<td colspan=11>Infos: <input type=text name="depositcomment" size=80>';
  echo '<tr><td colspan=11 align=center>';
  echo '<tr><td colspan=11 align="center">
  <input type=hidden name=createme value=1><input type=hidden name="paymenttypeid" value="'. $paymenttypeid .'">
  <input type=hidden name="num_create" value="'. $num_create .'"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
  <input type=hidden name="userid" value="'. $userid .'">
  <input type=hidden name="startdate" value="'. $startdate .'">
  <input type=hidden name="stopdate" value="'. $stopdate .'">
  <input type="submit" value="Valider"></table></form>';
}

if ($showmenu)
{
  echo '<h2>Dépôt</h2><form method="post" action="accounting.php"><table>';
  echo '<tr><td>De:<td>'; $datename = 'startdate'; $startdate = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4));
  require('inc/datepicker.php');
  echo '<tr><td>A:<td>'; $datename = 'stopdate'; $stopdate = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4));
  require('inc/datepicker.php');
  echo '<tr><td>Type:';$dp_itemname = 'paymenttype'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = 2;
  require('inc/selectitem.php');
  $dp_itemname = 'user'; $dp_description = d_trad('user'); $dp_allowall = 1; $dp_noblank = 1; #$dp_selectedid = $_SESSION['ds_userid']; 
  require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center">
  <input type=hidden name=listme value=1><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
  <input type="submit" value="Valider"></td></tr></table></form>';
  # TODO !!! filter reimbursements
}

?>