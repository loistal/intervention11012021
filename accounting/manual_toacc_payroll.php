<?php

ini_set('max_execution_time', 600*2);
$limit = 300;

############# info toacc
/*
Séverine:
Ecriture salaire :
 
641100                D            Salaires bruts
641200                D            Congés payés
641300                D            Primes et gratifications
641400                D            Avantage en nature
431000                C            Cps part salariale
442000                C            Cst à payer
425000                C            Acomptes versés
421000                C            Salaires nets
648000                C            Avantage en nature
645100                D            Cps part patronale
431000                C            Cps part patronale

Ecriture paiement du salaire :
 
421000                D
512000                C (defined bank account)


Maroussia:
 
DEBIT
CREDIT
641100                D            Salaires bruts
641200                D            Congés payés
641300                D            Primes et gratifications
641400                D            Avantage en nature
645100                D            Cps part patronale
 
431000                C            Cps part salariale et patronale
442000                C            Cst à payer (pour la CST nous on utilise le 447000)
425000                C            Acomptes versés
421000                C            Salaires nets
648000                C            Avantage en nature (je ne pense pas qu’on les comptabilise dans la paie, je vais me renseigner)
 
 
 
 
 Séverine 2019 02 22
 Ecritures

6411  Salaires de base                                   D
6412  Congés payés                                       D
6413  Primes et gratifications                           D
6414 Indemnités et avantages divers                      D
TOTAL DOIT CORRESPONDRE AU BRUT
6451 Cotisations patronales CPS                          D
4210 Salaires nets                                       C
4250 Acomptes salaires                                   C
4310 Cotisations salariales CPS                          C
4310 Cotisations patronales CPS                          C
4420 CST                                                 C
6414 Remboursement de frais                              C
 
 
 
 
*/
#############

$PA['no_referenceid_3'] = 'uint';
$PA['integrate'] = 'uint';
$PA['group'] = 'uint';
$PA['month'] = 'uint';
$PA['year'] = 'uint';
require('inc/readpost.php');

$total = 0; $adjustmentgroupid = -1;

if ($integrate == 1)
{
  require('preload/bankaccount.php');
  require('preload/paymenttype.php');
  require('preload/net_modif_account.php');
  $payslipdate = d_builddate(1,$month,$year);
  $payslipA = array();
  $employee_by_payslipidA = array();
  $query = 'select payslip.employeeid,payslipid,employee_is_clientid,employeename,employeefirstname
  from payslip,employee
  where payslip.employeeid=employee.employeeid
  and payslipdate=? and status=1 and toacc=0 limit '.$limit;
  $query_prm = array($payslipdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  $num_payslips = 0;
  for ($i=0; $i < $num_results_main; $i++)
  {
    array_push($payslipA,$main_result[$i]['payslipid']);
    if ($main_result[$i]['employee_is_clientid'] > 0)
    {
      $employee_by_payslipidA[$main_result[$i]['payslipid']] = $main_result[$i]['employee_is_clientid'];
    }
    else
    {
      #2019 08 30 insert client
      $employeename_temp = $main_result[$i]['employeename'] . ' ' . $main_result[$i]['employeefirstname'];
      $employeeid_temp = $main_result[$i]['employeeid'];
      $payslipid_temp = $main_result[$i]['payslipid'];
      $query = 'insert into client (clientname,clientcategoryid,clientcategory2id,clienttermid,vatexempt,blocked
      ,employeeid,usedetail,outstandinglimit,townid,clientsectorid,isclient,isemployee)
      values (?,'.$_SESSION['ds_defclientcatid'].','.$_SESSION['ds_defclientcat2id'].',1,0,0,0,0,0,1,1,0,1)';
      $query_prm = array(d_encode($employeename_temp));
      require ('inc/doquery.php');
      $employee_is_clientid = $query_insert_id;
      
      $query = 'update employee set employee_is_clientid=? where employeeid=?';
      $query_prm = array($employee_is_clientid,$employeeid_temp);
      require ('inc/doquery.php');
      
      $employee_by_payslipidA[$payslipid_temp] = $employee_is_clientid;
    }
  }
  
  # load accountingnumberids
  $query = 'select payslip_toaccid,accountingnumberid from payslip_toacc order by payslip_toaccid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $accA[$query_result[$i]['payslip_toaccid']] = $query_result[$i]['accountingnumberid'];
  }
  
  if ($group == 1)
  {
    $debitA = array(); $debit = 0;
    $creditA = array(); $credit = 0;
    for ($i=1;$i<=9;$i++) { $debitA[$i] = 0; $creditA[$i] = 0; }
  }

  foreach ($payslipA as $payslipid)
  {
    if ($group == 0)
    {
      $debitA = array(); $debit = 0;
      $creditA = array(); $credit = 0;
      for ($i=1;$i<=9;$i++) { $debitA[$i] = 0; $creditA[$i] = 0; }
    }
    else
    {
      if ($no_referenceid_3 == 0) { $debitA[3] = 0; $creditA[3] = 0; }
      $debitA[5] = 0; $creditA[5] = 0;
    }

    # 1,	"Salaire brut soumis cotisation (Débit)"
    # 5,	"Salaire net (Crédit)"
    $query = 'select gross_salary,net_salary,payslipcomment,bankaccountid,paymenttypeid,payslip.employeeid,referencenumber
    ,payroll_payment_date
    from payslip,employee
    where payslip.employeeid=employee.employeeid
    and payslipid=?';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    $comment = $query_result[0]['payslipcomment'];
    $employeeid = $query_result[0]['employeeid'];
    $referencenumber = $query_result[0]['referencenumber'];
    $bankaccountid = $query_result[0]['bankaccountid'];
    $payroll_payment_date = $query_result[0]['payroll_payment_date'];
    $paymenttypeid = $query_result[0]['paymenttypeid'];
    $debitA[1] += $query_result[0]['gross_salary']; $debit += $query_result[0]['gross_salary'];
    $creditA[5] += $query_result[0]['net_salary']; $credit += $query_result[0]['net_salary'];
    
    # 9,  "Congés payés (Débit)"
    $query = 'select value from payslip_line_net where `rank`=60 and payslipid=?';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    if ($num_results && $query_result[0]['value'] > 0)
    {
      $debitA[1] -= $query_result[0]['value'];
      $debitA[9] += $query_result[0]['value'];
    }
    
    # 2,	"Remboursement des avances d\'indemnités journalières (Débit)"
    $query = 'select payslip_line_comment from payslip_line_net where `rank`=10050 and payslipid=?';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $debitA[2] += $query_result[0]['payslip_line_comment']; $debit += $query_result[0]['payslip_line_comment'];
    }
    
    # 3,	"Ajout / déduction net (Débit/Crédit)"
    $query = 'select value,payslip_line_comment,override from payslip_line_net where `rank`>=10100 and `rank`<10200 and payslipid=?';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($group && $no_referenceid_3)
      {
        $debitA[3] += $query_result[$i]['value']; $debit += $query_result[$i]['value'];
      }
      else
      {
        if ($query_result[$i]['override'] == 0) { $index = 3; }
        else
        {
          # add 1 to index
          $index = end($accA)+1;
          $debitA[$index] = 0;
          $accA[$index] = $net_modif_account_anidA[($query_result[$i]['override']+0)];
          if ($acc[$index] == 0) { $index = 3; }
        }
        $debitA[$index] += $query_result[$i]['value']; $debit += $query_result[$i]['value'];
        if ($query_result[$i]['payslip_line_comment'] != '')
        {
          if (!isset($debit_commentA[$index])) { $debit_commentA[$index] = ''; }
          else { $debit_commentA[$index] .= ' '; }
          $debit_commentA[$index] .= $query_result[$i]['payslip_line_comment'];
        }
      }
    }
    $query = 'select value,payslip_line_comment,override from payslip_line_net where `rank`>=10200 and `rank`<=11000 and payslipid=?';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($group && $no_referenceid_3)
      {
        $creditA[3] += $query_result[$i]['value']; $credit += $query_result[$i]['value'];
      }
      else
      {
        if ($query_result[$i]['override'] == 0) { $index = 3; }
        else
        {
          # add 1 to index
          $index = end($accA)+1;
          $creditA[$index] = 0;
          $accA[$index] = $net_modif_account_anidA[($query_result[$i]['override']+0)];
          if ($acc[$index] == 0) { $index = 3; }
        }
        $creditA[$index] += $query_result[$i]['value']; $credit += $query_result[$i]['value'];
        if ($query_result[$i]['payslip_line_comment'] != '')
        {
          if (!isset($credit_commentA[$index])) { $credit_commentA[$index] = ''; }
          else { $credit_commentA[$index] .= ' '; }
          $credit_commentA[$index] .= $query_result[$i]['payslip_line_comment'];
        }
      }
    }
    
    # 4,	"CPS part salariale (Crédit)"
    $query = 'select sum(value) as value from payslip_line_net where payslipid=? and `rank`>=100 and `rank`<500';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    $creditA[4] += $query_result[0]['value']; $credit += $query_result[0]['value'];
    
    # 6,	"CPS part patronale (Débit)"
    # 7,	"CPS part patronale (Crédit)"
    $query = 'select sum(value_employer) as value from payslip_line_net where payslipid=? and `rank`>=100 and `rank`<500';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    if ($accA[7] == $accA[4]) { $creditA[4] += $query_result[0]['value']; $credit += $query_result[0]['value']; }
    else { $creditA[7] += $query_result[0]['value']; $credit += $query_result[0]['value']; }
    $debitA[6] += $query_result[0]['value']; $debit += $query_result[0]['value'];
    
    # 8,	"CST (Crédit)"
    $query = 'select sum(value) as value from payslip_line_net where payslipid=? and `rank`=500';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    $creditA[8] += $query_result[0]['value']; $credit += $query_result[0]['value'];
    
    if ($debit != $credit)
    {
      require('preload/employee.php');
      echo '<br>Problème d\'écriture:';
      echo $payslipid.'('.$employeeA[$employeeid].') : '.$debit.' vs '.$credit.'<br>';
      foreach ($debitA as $id => $value)
      {
        if ($value > 0) { echo 'D '.$id.' '.($value+0).'<br>'; }
      }
      foreach ($creditA as $id => $value)
      {
        if ($value > 0) { echo 'C '.$id.' '.($value+0).'<br>'; }
      }
    }
    
    if ($debit == $credit && $debit > 0)
    {
      $save_payslipdate = d_builddate(31,substr($payslipdate,5,2),substr($payslipdate,0,4)) ;
      if ($group == 0 || $adjustmentgroupid < 0)
      {
        $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated)
        values (?,?,curdate(),curtime(),?,?,4)';
        $query_prm = array($_SESSION['ds_userid']
        ,$save_payslipdate
        ,'Fiche paie', $payslipid);
        require('inc/doquery.php');
        $adjustmentgroupid = $query_insert_id;
      }
      foreach ($debitA as $id => $value)
      {
        if ($group == 0 || $id == 5 || ($id == 3 && $no_referenceid_3 == 0) || $id > 9)
        {
          $debcred = 1; if ($value < 0) { $value = d_abs($value); $debcred = 0; }
          if ($value > 0)
          {
            if (isset($debit_commentA[$id])) { $line_comment = $debit_commentA[$id]; }
            else { $line_comment = ''; }
            if ($id == 5 || ($id == 3 && $no_referenceid_3 == 0)) { $referenceid = $employee_by_payslipidA[$payslipid]; }
            else { $referenceid = 0; }
            $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,?,0,1,?)';
            $query_prm = array($line_comment, $debcred, $adjustmentgroupid, $value, $accA[$id], $referenceid);
            require('inc/doquery.php');
          }
        }
      }
      foreach ($creditA as $id => $value)
      {
        if ($group == 0 || $id == 5 || ($id == 3 && $no_referenceid_3 == 0) || $id > 9)
        {
          $debcred = 0; if ($value < 0) { $value = d_abs($value); $debcred = 1; }
          if ($value > 0)
          {
            if (isset($credit_commentA[$id])) { $line_comment = $credit_commentA[$id]; }
            else { $line_comment = ''; }
            if ($id == 5 || ($id == 3 && $no_referenceid_3 == 0)) { $referenceid = $employee_by_payslipidA[$payslipid]; }
            else { $referenceid = 0; }
            $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,?,0,1,?)';
            $query_prm = array($line_comment, $debcred, $adjustmentgroupid, $value, $accA[$id], $referenceid);
            require('inc/doquery.php');
          }
        }
      }
      
      if ($creditA[5] && $bankaccountid && $bankaccount_accountingnumberidA[$bankaccountid])
      {
        # value is $creditA[5]
        # DEBIT       accountid is $accA[5] (to DEBIT)
        # CREDIT      (defined in bankaccount table)
        if ($paymenttypeid) { $line_comment = $paymenttypeA[$paymenttypeid]; }
        else { $line_comment = ''; }
        $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated)
        values (?,?,curdate(),curtime(),?,?,5)';
        if (is_null($payroll_payment_date)) { $payroll_payment_date = $save_payslipdate; }
        $query_prm = array($_SESSION['ds_userid'], $payroll_payment_date, 'Paiement salaire', $payslipid);
        require('inc/doquery.php');
        $p_agi = $query_insert_id;
        $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid)
        values (?,?,?,?,?,0,1,?)';
        $query_prm = array($line_comment, 1, $p_agi, $creditA[5], $accA[5], $employee_by_payslipidA[$payslipid]);
        require('inc/doquery.php');
        $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch)
        values (?,?,?,?,?,0,1)';
        $query_prm = array($line_comment, 0, $p_agi, $creditA[5], $bankaccount_accountingnumberidA[$bankaccountid]);
        require('inc/doquery.php');
      }
      
      $query = 'update payslip set toacc=1 where payslipid=?';
      $query_prm = array($payslipid);
      require('inc/doquery.php');
      $num_payslips++;
    }
  }
  echo '<p>' . $num_payslips . ' fiches intégrées.</p><br>';
  if ($group)
  {
    if ($debit == $credit && $debit > 0)
    {
      foreach ($debitA as $id => $value)
      {
        $ok = 0;
        if ($id != 5 && $id != 3 && $id <= 9) { $ok = 1; }
        if ($id == 3 && $no_referenceid_3) { $ok = 1; }
        if ($ok)
        {
          $debcred = 1; if ($value < 0) { $value = d_abs($value); $debcred = 0; }
          if ($value > 0)
          {
            $line_comment = '';
            $referenceid = 0;
            $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,?,0,1,?)';
            $query_prm = array($line_comment, $debcred, $adjustmentgroupid, $value, $accA[$id], $referenceid);
            require('inc/doquery.php');
          }
        }
      }
      foreach ($creditA as $id => $value)
      {
        $ok = 0;
        if ($id != 5 && $id != 3 && $id <= 9) { $ok = 1; }
        if ($id == 3 && $no_referenceid_3) { $ok = 1; }
        if ($ok)
        {
          $debcred = 0; if ($value < 0) { $value = d_abs($value); $debcred = 1; }
          if ($value > 0)
          {
            $line_comment = '';
            $referenceid = 0;
            $query = 'insert into adjustment (adjustmentcomment_line,debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,?,0,1,?)';
            $query_prm = array($line_comment, $debcred, $adjustmentgroupid, $value, $accA[$id], $referenceid);
            require('inc/doquery.php');
          }
        }
      }
    }
    else
    {
      echo '<br>Problème d\'écriture regroupé: ';
      echo $debit.' vs '.$credit.'<br>';
      foreach ($debitA as $id => $value)
      {
        if ($value > 0) {if ($id == 3 || $id == 5) { echo 'need split'; } echo 'D '.$id.' '.($value+0).'<br>'; }
      }
      foreach ($creditA as $id => $value)
      {
        if ($value > 0) {if ($id == 3 || $id == 5) { echo 'need split'; } echo 'C '.$id.' '.($value+0).'<br>'; }
      }
    }
  }
}


echo '<h2>Intégration manuelle (Paie)</h2>
<p>Il y a une limite de '.$limit.' fiches par intégration.</p>
<form method="post" action="accounting.php"><table class=report>';
if ($month == 0)
{
  $month = mb_substr($_SESSION['ds_curdate'],5,2);
  $year = mb_substr($_SESSION['ds_curdate'],0,4);
}
?><tr><td>Mois:</td><td><select name="month"><?php
for ($i=1; $i <= 12; $i++)
{
  if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><select name="year"><?php
for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
{
  if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select><?php
echo '<tr><td>Regrouper les écritures:<td align=center><input type=checkbox name="group" value=1';
if ($group) { echo ' checked'; }
echo '>
<tr><td>  Aussi regrouper Ajout / Déduction net(te):<td align=center><input type=checkbox name="no_referenceid_3" value=1';
if ($no_referenceid_3) { echo ' checked'; }
echo '></table>
<input type=hidden name="integrate" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';

?>