<?php

# sum of either debit/credit for each account
# creates SINGLE accounting entry
# keep details if account takes tiers

# 6 and 7 are skipped, summed up in a single line:
# benefits go in 120000 Crédit
# loss goes in 129000 Débit

$nullify_previous_period = 0; # CAGEST uses this to create entry to set prvious period (year) to zero

$showmenu = 1;

if (isset($_POST['closingdate']))
{
  require('preload/accountingnumber.php');
  $showmenu = 0;
  $startdate = $_POST['startdate'];
  $closingdate = $_POST['closingdate'];
  $nextdate = $_POST['nextdate'];
  
  $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 and adjustmentdate=?';
  $query_prm = array($nextdate);
  require('inc/doquery.php');
  if ($num_results > 0)
  {
    exit; #echo 'erreur duplication'; 
  }
  
  $query = 'select benefit_accountinggroupid,loss_accountinggroupid,benefit_accountingnumberid,loss_accountingnumberid from globalvariables_accounting where primaryunique=1';
  $query_prm = array();
  require('inc/doquery.php');
  $benefit_accountinggroupid = $query_result[0]['benefit_accountinggroupid'];
  $loss_accountinggroupid = $query_result[0]['loss_accountinggroupid'];
  $benefit_accountingnumberid = $query_result[0]['benefit_accountingnumberid'];
  $loss_accountingnumberid = $query_result[0]['loss_accountingnumberid'];
  $benefit = 0;
  $loss = 0;
  
  $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,closing,adjustmentcomment) values (?, ?, curdate(), curtime(), 1, "A nouveau")';
  $query_prm = array($_SESSION['ds_userid'], $nextdate);
  require('inc/doquery.php');
  $agid = $query_insert_id; if ($agid < 1) { exit; }

  echo '<h2>Clôture '.datefix($closingdate).'</h2>';
  
  $mainquery = 'select sum(value) as value,adjustment.accountingnumberid,debit,acnumber
  from adjustment,adjustmentgroup,accountingnumber
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.accountingnumberid=accountingnumber.accountingnumberid
  and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=?
  group by adjustment.accountingnumberid,debit
  order by acnumber,adjustment.accountingnumberid,debit desc';
  
  $query = $mainquery;
  $query_prm = array($startdate, $closingdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  
  echo '<table class=report><thead><th>Compte<th>Débit<th>Crédit<th>Infos</thead>';
  $valdebit = $valcredit = 0; $keepdetails = 0; $affects_result = 0; $linenr = 0; $totaldebit = 0; $totalcredit = 0;
  for ($i=0; $i < $num_results_main; $i++)
  {
    if ($main_result[$i]['debit'] == 1)
    {
      $valdebit = $main_result[$i]['value'];
    }
    else
    {
      $valcredit = $main_result[$i]['value'];
    }
    if ($main_result[$i]['accountingnumberid'] != $main_result[($i+1)]['accountingnumberid'])
    {
      if ($accountingnumber_needreferenceA[$main_result[$i]['accountingnumberid']] == 1)
      {
        $keepdetails = 1;
      }
      if ($accountingnumber_accountinggroupidA[$main_result[$i]['accountingnumberid']] == $benefit_accountinggroupid || $accountingnumber_accountinggroupidA[$main_result[$i]['accountingnumberid']] == $loss_accountinggroupid)
      {
        $affects_result = 1;
      }
      if ($valdebit > $valcredit)
      {
        $value = $valdebit - $valcredit; $debit = 1;
      }
      elseif ($valcredit > $valdebit)
      {
        $value = $valcredit - $valdebit; $debit = 0;
      }
      else
      {
        $value = 0; $debit = -1;
      }
      if ($value > 0)
      {
        echo '<tr><td><b>',$accountingnumberA[$main_result[$i]['accountingnumberid']];
        if ($debit == 0) { echo '<td>'; }
        echo '<td align=right>'.myfix($value);
        if ($affects_result == 1)
        {
          if ($debit == 1) { echo '<td>'; }
          echo '<td>Résultat';
          if ($accountingnumber_accountinggroupidA[$main_result[$i]['accountingnumberid']] == $benefit_accountinggroupid)
          {
            if ($debit == 0)
            {
              echo ' (bénéfice)';
              $benefit += $value;
            }
            else
            {
              echo ' (perte)';
              $loss += $value;
            }
          }
          else
          {
            if ($debit == 1)
            {
              echo ' (perte)';
              $loss += $value;
            }
            else
            {
              echo ' (bénéfice)';
              $benefit += $value;
            }
          }
        }
        elseif ($keepdetails == 1)
        {
          # TODO we don't want details, only one line per referenceid
          $subt_deb = 0; $subt_cred = 0;
          echo '<td><td><b>DETAILS:';
          $query = 'select value,debit,referenceid
          from adjustment,adjustmentgroup
          where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
          and deleted=0 and adjustmentdate>=? and adjustmentdate<=?
          and accountingnumberid=?
          order by referenceid'; # group by referenceid does not work for some reason! don't try...
          $query_prm = array($startdate, $closingdate, $main_result[$i]['accountingnumberid']);
          require('inc/doquery.php');
          $line_result = $query_result; $num_results_line = $num_results;
          $ref_debit = 0; $ref_credit = 0;
          for ($y=0; $y < $num_results_line; $y++)
          {
            #echo '<tr><td>';
            if ($line_result[$y]['debit'] == 1)
            {
              #echo '<td>Débit';
              $ref_debit += $line_result[$y]['value'];
            }
            else
            {
              #echo '<td>Crédit';
              $ref_credit += $line_result[$y]['value'];
            }
            if ($line_result[$y]['referenceid'] != $line_result[($y+1)]['referenceid'])
            {
              if ($ref_debit > $ref_credit)
              {
                $ref_debcred = 1;
                $ref_value = $ref_debit - $ref_credit;
              }
              elseif ($ref_credit > $ref_debit)
              {
                $ref_debcred = 0;
                $ref_value = $ref_credit - $ref_debit;
              }
              else { $ref_value = 0; }
              $ref_debit = 0; $ref_credit = 0;
              if ($ref_value > 0)
              {
                echo '<tr><td>';
                if ($ref_debcred == 1) { echo '<td align=right>',myfix($ref_value),'<td><td align=right>',$line_result[$y]['referenceid']; $subt_deb += $ref_value; }
                else { echo '<td><td align=right>',myfix($ref_value),'<td align=right>',$line_result[$y]['referenceid']; $subt_cred += $ref_value; }
                $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr,referenceid,matchingid) values (?,?,?,?,?,?,0)';
                $query_prm = array($agid, $ref_value, $ref_debcred, $main_result[$i]['accountingnumberid'], $linenr, $line_result[$y]['referenceid']);
                require('inc/doquery.php');
                $linenr++;
              }
            }
          }
          echo '<tr><td><td align=right><i>',myfix($subt_deb),'<td align=right><i>',myfix($subt_cred),'<td>';
          $totaldebit += $subt_deb; $totalcredit += $subt_cred;
        }
        else
        {
          echo '<td>';
          if ($debit == 1) { echo '<td>'; $totaldebit += $value; }
          else { $totalcredit += $value; }
          $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr,matchingid) values (?,?,?,?,?,0)';
          $query_prm = array($agid, $value, $debit, $main_result[$i]['accountingnumberid'], $linenr);
          require('inc/doquery.php');
          $linenr++;
        }
      }
      $valdebit = $valcredit = 0; $keepdetails = 0; $affects_result = 0;
    }
  }
  echo '<tr><td><b>TOTAUX<td align=right><b>',myfix($totaldebit),'<td align=right><b>',myfix($totalcredit),'<td>';
  
  if ($benefit > $loss)
  {
    $result = $benefit - $loss; $totalcredit += $result;
    $debit = 0;
    echo '<tr><td><b>BENEFICE<td><td align=right><b>',myfix($result),'<td>';
    $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr,matchingid) values (?,?,?,?,?,0)';
    $query_prm = array($agid, $result, $debit, $benefit_accountingnumberid, $linenr);
    require('inc/doquery.php');
  }
  elseif ($loss > $benefit)
  {
    $result = $loss - $benefit; $totaldebit += $result;
    $debit = 1;
    echo '<tr><td><b>PERTE<td align=right><b>',myfix($result),'<td><td>';
    $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr,matchingid) values (?,?,?,?,?,0)';
    $query_prm = array($agid, $result, $debit, $loss_accountingnumberid, $linenr);
    require('inc/doquery.php');
  }
  echo '<tr><td><b>TOTAUX<td align=right><b>',myfix($totaldebit),'<td align=right><b>',myfix($totalcredit),'<td>';
  echo '</table>';
  
  if ($nullify_previous_period)
  {
    $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,closing,adjustmentcomment) values (?, ?, curdate(), curtime(), 2, "Clôture")';
    $query_prm = array($_SESSION['ds_userid'], $closingdate);
    require('inc/doquery.php');
    $closing_agid = $query_insert_id; if ($closing_agid < 1) { exit; }
    
    $query = $mainquery;
    $query_prm = array($startdate, $closingdate);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    
    $linenr = 0;
    for ($i=0; $i < $num_results_main; $i++)
    {
      # TODO single line instead of one for each debit and credit
      $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr,matchingid) values (?,?,?,?,?,0)';
      $debit = 1; if ($main_result[$i]['debit'] == 1) { $debit = 0; }
      $query_prm = array($closing_agid, $main_result[$i]['value'], $debit, $main_result[$i]['accountingnumberid'], $linenr);
      require('inc/doquery.php');
      $linenr++;
    }
  }
  
}

if ($showmenu)
{
  $query = 'select benefit_accountinggroupid,loss_accountinggroupid,benefit_accountingnumberid,loss_accountingnumberid from globalvariables_accounting where primaryunique=1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($query_result[0]['benefit_accountingnumberid'] == 0 || $query_result[0]['loss_accountingnumberid'] == 0 || $query_result[0]['benefit_accountinggroupid'] == 0 || $query_result[0]['loss_accountinggroupid'] == 0)
  {
    echo 'Veuillez définir les groupes et comptes du bénéfice et perte.';
  }
  elseif ($_SESSION['ds_accounting_closingdate'] == NULL || $_SESSION['ds_accounting_closingdate'] == '0000-00-00')
  {
    echo 'Veuillez définir la date de clôture du premier exercice.';
  }
  else
  {
    $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      $closingdate = $_SESSION['ds_accounting_closingdate'];
      $startdate = d_builddate(1,1,$_SESSION['ds_startyear']); # TODO need parameter for when to start for the first closing
    }
    else
    {
      $startdate = $query_result[0]['adjustmentdate'];
      $closingdate = new DateTime($startdate);
      $closingdate->add(new DateInterval('P1Y'));
      $closingdate->sub(new DateInterval('P1D'));
      $closingdate = $closingdate->format('Y-m-d');
    }
    $nextdate = new DateTime($closingdate);
    $nextdate->add(new DateInterval('P1D'));
    $nextdate = $nextdate->format('Y-m-d');
    
    ?><h2>Clôture</h2>
    <form method="post" action="accounting.php"><table>
    <tr><td>Clôturer votre exercice à la date du :
    <td><?php echo datefix($closingdate); ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="accountingmenu_sa" value="control">
    <input type=hidden name="startdate" value="<?php echo $startdate; ?>">
    <input type=hidden name="closingdate" value="<?php echo $closingdate; ?>">
    <input type=hidden name="nextdate" value="<?php echo $nextdate; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form>
    <?php
  }
}

?>