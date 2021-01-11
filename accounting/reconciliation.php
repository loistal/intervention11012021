<?php

$query_list = 'select accountingnumberid,acnumber,acname from accountingnumber where isbank=1 order by acnumber,acname';
$showmenu = 1;
$anid = (int) $_POST['accountingnumberid'];
if (!isset($_POST['accountingnumberid'])) { $anid = (int) $_GET['anid']; }

if ($_POST['reconciliate'] == 1 && $anid > 0)
{
  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and accountingnumberid=? and reconciliationid=0 and deleted=0
  order by adjustmentdate,adjustmentid,linenr';
  $query_prm = array($anid);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i = 0; $i < $num_results_main; $i++)
  {
    if ($_POST[$main_result[$i]['adjustmentid']] == 1)
    {
      if (!isset($reconciliationid))
      {
        $datename = 'reconciliationdate'; require('inc/datepickerresult.php');
        $query = 'insert into reconciliation (userid,reconciliationdate) values (?,?)';
        $query_prm = array($_SESSION['ds_userid'],$reconciliationdate);
        require('inc/doquery.php');
        $reconciliationid = $query_insert_id;
      }
      $query = 'update adjustment set reconciliationid=? where adjustmentid=?';
      $query_prm = array($reconciliationid, $main_result[$i]['adjustmentid']);
      require('inc/doquery.php');
    }
  }
}

if($anid > 0)
{
  require('preload/accounting_simplified.php');
  
  ### find before and after
  $query = $query_list;
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $anidA[$i] = $query_result[$i]['accountingnumberid'];
    if ($query_result[$i]['accountingnumberid'] == $anid) { $anid_currentid_index = $i; }
  }
  $anid_before = $anid_currentid_index - 1; if ($anid_before < 0) { $anid_before = $num_results - 1; }
  $anid_after = $anid_currentid_index + 1; if ($anid_after >= $num_results) { $anid_after = 0; }
  $anid_before = $anidA[$anid_before];
  $anid_after = $anidA[$anid_after];
  ###
  $credittotal = 0; $debittotal = 0;
  $query = 'select acnumber,acname from accountingnumber where accountingnumberid=?';
  $query_prm = array($anid);
  require('inc/doquery.php');
  $accountname = $query_result[0]['acnumber'].': '.$query_result[0]['acname'];
  echo '<h2>';
  echo '<a href="accounting.php?accountingmenu=reconciliation&accountingmenu_sa=control&anid=' . $anid_before . '">&#8592;</a> ';
  echo 'Rapprochement bancaire '.d_output($accountname);
  echo ' <a href="accounting.php?accountingmenu=reconciliation&accountingmenu_sa=control&anid=' . $anid_after . '"">&#8594;</a>';
  echo '</h2>';
  echo '<form method="post" action="accounting.php"><table class=report><thead><th>Écriture</th><th>Date<th>'.$_SESSION['ds_term_accounting_comment'].'<th>'.$_SESSION['ds_term_accounting_reference'].'<th>Débit<th>Crédit<th></thead>';
  $query = 'select accounting_simplifiedid,adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid,adjustment.adjustmentgroupid from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and accountingnumberid=? and reconciliationid=0 and deleted=0 and value>0
  order by adjustmentdate,adjustmentid,linenr';
  $query_prm = array($anid);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    echo d_tr();
    if ($query_result[$i]['accounting_simplifiedid'] > 0)
    {
      $link = 'accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$accounting_simplifiedgroupidA[$query_result[$i]['accounting_simplifiedid']].'&asid='.$query_result[$i]['accounting_simplifiedid'].'&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    else
    {
      $link = 'accounting.php?accountingmenu=entry&accountingmenu_sa=simplified&readme=1&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    echo d_td_old($query_result[$i]['adjustmentgroupid'],0,0,0,$link);
    echo d_td_old(datefix($query_result[$i]['adjustmentdate']));
    echo d_td_old($query_result[$i]['adjustmentcomment']);
    echo d_td_old($query_result[$i]['reference']);
    if ($query_result[$i]['debit'] == 1)
    {
      $debittotal += $query_result[$i]['value'];
      echo d_td_old(myfix($query_result[$i]['value']),1);
      echo d_td_old('');
    }
    else
    {
      $credittotal += $query_result[$i]['value'];
      echo d_td_old('');
      echo d_td_old(myfix($query_result[$i]['value']),1);
    }
    echo '<td><input type=checkbox name="'.$query_result[$i]['adjustmentid'].'" value=1>';
    
  }
  $temp = $credittotal-$debittotal;
  if ($temp != 0)
  {
    echo '<tr><td colspan=7 align=center><b>Solde: ';
    if ($temp > 0) { echo '<span class="alert">'; }
    echo myfix(d_abs($temp));
    if ($temp > 0) { echo '</span>'; }
  }
  echo '<tr><td colspan=7 align="center">
  <input type=hidden name="reconciliate" value=1>
  <input type=hidden name="accountingnumberid" value="'. $anid .'">
  <input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
  <input type=hidden name="accountingmenu_sa" value="control">';
  $datename = 'reconciliationdate'; require('inc/datepicker.php');
  echo '<input type="submit" value="Valider"></td></tr></table></form><br>';
  $showmenu = 0;
  # showing ALL, this should be limited by cloture
  $query = 'select reconciliation.reconciliationid,adjustment.adjustmentgroupid,value,debit,reconciliationdate,adjustmentdate,adjustmentcomment,reference,adjustment.adjustmentid
  from reconciliation,adjustment,adjustmentgroup
  where adjustment.reconciliationid=reconciliation.reconciliationid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and reconciliation.deleted=0 and adjustment.accountingnumberid=?
  order by reconciliationdate desc,reconciliation.reconciliationid desc';
  $query_prm = array($anid);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  if($num_results_main)
  {
    $credittotal = 0; $debittotal = 0; $subtotald = 0; $subtotalc = 0;
    echo '<h2>Rapprochements effectué</h2><table class=report><thead><th>Écriture';
    echo '</th><th>Date</th><th>'.$_SESSION['ds_term_accounting_comment'].'<th>'.$_SESSION['ds_term_accounting_reference'].'<th>Débit</th><th>Crédit</th><th>Date de rapprochement</th></thead>';
    for ($i = 0; $i < $num_results_main; $i++)
    {
      $link = '<a href="accounting.php?accountingmenu=undo&accountingmenu_sa=control&adjustmentgroupid='.$main_result[$i]['adjustmentgroupid'].'">' . $main_result[$i]['adjustmentgroupid'] . '</a>';
      echo d_tr(),'<td align=right>',$link;
      echo '<td>' . datefix($main_result[$i]['adjustmentdate'], 'short');
      echo d_td_old($query_result[$i]['adjustmentcomment']);
      echo d_td_old($query_result[$i]['reference']);
      if ($main_result[$i]['debit'] == 1)
      {
        $debittotal += $query_result[$i]['value'];
        $subtotald += $query_result[$i]['value'];
        echo '<td align=right>' . myfix($main_result[$i]['value']);
        echo '<td>';
      }
      else
      {
        $credittotal += $query_result[$i]['value'];
        $subtotalc += $query_result[$i]['value'];
        echo '<td>';
        echo '<td align=right>' . myfix($main_result[$i]['value']);
      }
      echo '<td>' . datefix($main_result[$i]['reconciliationdate'], 'short');
      if ($i != 0 && (mb_substr($main_result[$i]['reconciliationdate'],5,2) != mb_substr($main_result[$i+1]['reconciliationdate'],5,2) || mb_substr($main_result[$i]['reconciliationdate'],0,4) != mb_substr($main_result[$i+1]['reconciliationdate'],0,4)))
      {
        echo '<tr><td colspan=4><td align=right>',myfix($subtotald),'<td align=right>',myfix($subtotalc),'<td>';
        $subtotald = 0; $subtotalc = 0;
      }
    }
    $temp = $credittotal-$debittotal;
    if ($temp != 0)
    {
      echo '<tr><td colspan=7 align=center><b>Solde: ';
      if ($temp > 0) { echo '<span class="alert">'; }
      echo myfix(d_abs($temp));
      if ($temp > 0) { echo '</span>'; }
    }
  }
}

if ($showmenu)
{
  ?><h2>Rapprochement bancaire</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Compte:</td>
  <td><select autofocus name="accountingnumberid"><?php
  $query = $query_list;
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['accountingnumberid'] . '">' . d_output($row['acnumber']) . ': ' . d_output($row['acname']) . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountingmenu_sa" value="control">
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
}

?>