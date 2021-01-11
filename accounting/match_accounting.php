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

<script>
$(window).load(function(){

    var sum = 0;
    var sum2 = 0;

    $('.chkclass').click(function() {
        sum = 0;
        $('.chkclass:checked').each(function() {
           console.log(parseFloat($(this).closest('tr').find('.value').text()))
            sum += parseFloat($(this).closest('tr').find('.value').text());
        });
        $('#sum').html('<b>'+sum);
        updateDiff(sum, sum2);
    });

    $('.chkclass2').click(function() {
        sum2 = 0;
        $('.chkclass2:checked').each(function() {
            sum2 += parseFloat($(this).closest('tr').find('.value2').text());
        });
        $('#sum2').html('<b>'+sum2);
        updateDiff(sum, sum2);
    });

});

function updateDiff(sum, sum2) {
    var diff = sum - sum2;
    $('#sum3').html('<b>'+diff);
}
</script>

<?php

if (isset($_POST['set_accounting_matchempty']))
{
  if (!isset($_POST['accounting_matchempty'])) { $_POST['accounting_matchempty'] = 0; }
  $_SESSION['ds_accounting_matchempty'] = $_POST['accounting_matchempty']+0;
  $query = 'update usertable set accounting_matchempty=? where userid=?';
  $query_prm = array((int)$_POST['accounting_matchempty'],$_SESSION['ds_userid']);
  require('inc/doquery.php');
}

### find only accounts with something to match
$query_list_party = 'select clientid,clientname,issupplier,isclient,isemployee,isother
from client,adjustment,adjustmentgroup
where adjustment.referenceid=client.clientid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and client.deleted=0 and adjustmentgroup.deleted=0 and value>0 and adjustment.matchingid=0 and issupplier=1 and nomatch=0
group by clientname
order by clientname';
if ($_SESSION['ds_accounting_matchempty'] == 1)
{
  $query_list_party = 'select clientid,clientname,issupplier,isclient,isemployee,isother from client
  where issupplier=1 and deleted=0 order by clientname';
}
$query_list_party1 = $query_list_party;

$query_list_party2 = 'select clientid,clientname,issupplier,isclient,isemployee,isother
from client,adjustment,adjustmentgroup
where adjustment.referenceid=client.clientid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and client.deleted=0 and adjustmentgroup.deleted=0 and value>0 and adjustment.matchingid=0 and isemployee=1 and nomatch=0
group by clientname
order by clientname';
if ($_SESSION['ds_accounting_matchempty'] == 1)
{
  $query_list_party2 = 'select clientid,clientname,issupplier,isclient,isemployee,isother from client
  where isemployee=1 and deleted=0 order by clientname';
}

$query_list_party3 = 'select clientid,clientname,issupplier,isclient,isemployee,isother
from client,adjustment,adjustmentgroup
where adjustment.referenceid=client.clientid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and client.deleted=0 and adjustmentgroup.deleted=0 and value>0 and adjustment.matchingid=0 and isother=1 and nomatch=0
group by clientname
order by clientname';
if ($_SESSION['ds_accounting_matchempty'] == 1)
{
  $query_list_party3 = 'select clientid,clientname,issupplier,isclient,isemployee,isother from client
  where isother=1 and deleted=0 order by clientname';
}

$query_list_account = 'select accountingnumber.accountingnumberid,acnumber,acname,debit
from accountingnumber,adjustment,adjustmentgroup
where adjustment.accountingnumberid=accountingnumber.accountingnumberid
and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and matchable=1 and needreference=0 and adjustmentgroup.deleted=0 and value>0 and adjustment.matchingid=0
group by acnumber,acname
order by acnumber,acname';
if ($_SESSION['ds_accounting_matchempty'] == 1)
{
  $query_list_account = 'select accountingnumberid,acnumber,acname from accountingnumber
  where matchable=1 and needreference=0 order by acnumber,acname';
}
###


$PA['anid'] = 'uint';
$PA['matchme'] = 'uint';
$PA['clientid'] = 'uint';
$PA['difflist'] = 'uint';
require('inc/readpost.php');
if ($clientid > 0) { $anid = -1; }
if ($difflist == 2) { $query_list_party = $query_list_party2; }
elseif ($difflist == 3) { $query_list_party = $query_list_party3; }

$showmenu = 1; $debittext = 'DÉBIT'; $credittext = 'CRÉDIT';

if ($matchme && ($clientid > 0 || $anid > 0))
{
  $ok = 0; $debittotal = 0; $credittotal = 0; $aidA = array();
  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and matchingid=0 and value>0';
  if ($clientid > 0) { $query .= ' and referenceid=?'; $query_prm = array($clientid); }
  else { $query .= ' and accountingnumberid=?'; $query_prm = array($anid); }
  $query .= 'order by adjustmentdate,adjustmentid,linenr';
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    if (isset($_POST[$query_result[$i]['adjustmentid']]) && $_POST[$query_result[$i]['adjustmentid']] == 1)
    {
      if ($query_result[$i]['debit'] == 1) { $debittotal += $query_result[$i]['value']; }
      else { $credittotal += $query_result[$i]['value']; }
      array_push($aidA, $query_result[$i]['adjustmentid']);
    }
  }
  if ($debittotal == $credittotal && $credittotal > 0)
  {
    $query = 'insert into matching (userid,date,clientid, accountingnumberid) values (?,CURDATE(),?,?)';
    $query_prm = array($_SESSION['ds_userid'], $clientid, $anid);
    require ('inc/doquery.php');
    $matchingid = $query_insert_id;
    $aidA = array_filter(array_unique($aidA));
    sort($aidA);
    $aid_list = '(';
    foreach ($aidA as $kladd)
    {
      $aid_list .= $kladd . ',';
    }
    $aid_list = rtrim($aid_list,',') . ')';
    if ($aid_list == '()') { $aid_list = '(-1)'; }
    $query = 'update adjustment set matchingid=? where adjustmentid in '. $aid_list;
    $query_prm = array($matchingid);
    require ('inc/doquery.php');
    echo '<p>Compte lettré ('.$debittotal.' XPF).</p><br>';
  }
}

if($clientid > 0 || $anid > 0)
{
  require('preload/accounting_simplified.php');
  
  $datename = 'startdate'; $dp_allowempty = 1;
  require('inc/datepickerresult.php');
  $datename = 'stopdate'; $dp_allowempty = 1;
  require('inc/datepickerresult.php');
  
  $showmenu = 0; $totalresults = 0; $debittotal = 0; $credittotal = 0;
  if ($clientid > 0)
  {
    ### find before and after
    $query = $query_list_party;
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $clientidA[$i] = $query_result[$i]['clientid'];
      if ($query_result[$i]['clientid'] == $clientid)
      {
        $clientid_currentid_index = $i; $clientname = d_decode($query_result[$i]['clientname']);
        $issupplier = $query_result[$i]['issupplier'];
        $isemployee = $query_result[$i]['isemployee'];
        $isother = $query_result[$i]['isother'];
      }
    }
    $clientid_before = $clientid_currentid_index - 1; if ($clientid_before < 0) { $clientid_before = $num_results - 1; }
    $clientid_after = $clientid_currentid_index + 1; if ($clientid_after >= $num_results) { $clientid_after = 0; }
    $clientid_before = $clientidA[$clientid_before];
    $clientid_after = $clientidA[$clientid_after];
    ###
    echo '<h2>';
    echo '<a href="accounting.php?accountingmenu=match_accounting&difflist='.$difflist.'&clientid='.$clientid_before.'">&#8592;</a> ';
    $description = 'Fournisseur';
    if ($difflist == 2) { $description = 'Employé(e)'; }
    elseif ($difflist == 3) { $description = 'Autre'; }
    echo 'Lettrage ', $description,' ', d_output($clientname);
    echo ' <a href="accounting.php?accountingmenu=match_accounting&difflist='.$difflist.'&clientid='.$clientid_after.'"">&#8594;</a>';
    echo '</h2>';
  }
  else
  {
    ### find before and after
    $query = $query_list_account;
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $anidA[$i] = $query_result[$i]['accountingnumberid'];
      if ($query_result[$i]['accountingnumberid'] == $anid) { $anid_currentid_index = $i; }
    }
    if (!isset($anid_currentid_index)) { $anid_currentid_index = 0; }
    $anid_before = $anid_currentid_index - 1; if ($anid_before < 0) { $anid_before = $num_results - 1; }
    $anid_after = $anid_currentid_index + 1; if ($anid_after >= $num_results) { $anid_after = 0; }
    $anid_before = $anidA[$anid_before];
    $anid_after = $anidA[$anid_after];
    ###
    $query = 'select acnumber,acname from accountingnumber where accountingnumberid=?';
    $query_prm = array($anid);
    require('inc/doquery.php');
    echo '<h2>';
    echo '<a href="accounting.php?accountingmenu=match_accounting&accountingmenu_sa=control&anid=' . $anid_before . '">&#8592;</a> ';
    echo 'Lettrage compte '.d_output($query_result[0]['acnumber']).': '.d_output($query_result[0]['acname']);
    echo ' <a href="accounting.php?accountingmenu=match_accounting&accountingmenu_sa=control&anid=' . $anid_after . '"">&#8594;</a>';
    echo '</h2>';
  }
  echo '<form method="post" action="accounting.php"><table><tr><td valign=top>';
  echo '<table class="detailinput"><tr><td colspan=7><font size=+1><b>',$debittext;
  echo '<tr><td><b>Écriture<td><b>Date<td><b>'.$_SESSION['ds_term_accounting_comment'].'<td><b>'
  .$_SESSION['ds_term_accounting_reference'].'<td><b>Infos<td><b>Montant<td></tr>';

  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid,adjustment.adjustmentgroupid
  ,accounting_simplifiedid,adjustmentcomment_line as infos
  from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and matchingid=0 and deleted=0 and closing=0 and debit=1 and value>0 and integrated<4';
  if ($clientid > 0) { $query .= ' and referenceid=?'; $query_prm = array($clientid); }
  else { $query .= ' and accountingnumberid=?'; $query_prm = array($anid); }
  if (isset($startdate)) { $query .= ' and adjustmentdate>=?'; array_push($query_prm,$startdate); }
  if (isset($stopdate)) { $query .= ' and adjustmentdate<=?'; array_push($query_prm,$stopdate); }
  $query .= ' order by adjustmentdate,adjustmentid,linenr';
  require('inc/doquery.php');
  $totalresults += $num_results;
  for ($i = 0; $i < $num_results; $i++)
  {
    $debittotal += $query_result[$i]['value'];
    echo d_tr();
    if ($query_result[$i]['accounting_simplifiedid'] > 0)
    {
      $link = 'accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$accounting_simplifiedgroupidA[$query_result[$i]['accounting_simplifiedid']].'&asid='.$query_result[$i]['accounting_simplifiedid'].'&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    else
    {
      $link = 'accounting.php?accountingmenu=entry&accountingmenu_sa=simplified&readme=1&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    echo d_td_old($query_result[$i]['adjustmentgroupid'],1,0,0,$link);
    echo d_td_old(datefix($query_result[$i]['adjustmentdate']));
    echo d_td_old($query_result[$i]['adjustmentcomment']);
    echo d_td_old($query_result[$i]['reference']);
    echo d_td_old($query_result[$i]['infos']);
    echo d_td_old(myfix($query_result[$i]['value']),1);
    echo '<td class="sum value" style="display:none;">',($query_result[$i]['value']+0);
    echo '<td><input class="chkclass" type=checkbox name="'.$query_result[$i]['adjustmentid'].'" value=1>';
  }
  echo d_tr(),d_td_old('Total',0,2,4),d_td(),d_td_old(myfix($debittotal),1,2); # ,d_td_old('')
  echo '<td id="sum" align=right></td>';
  
  echo '</table></td><td width=25></td><td valign=top>';
  echo '<table class="detailinput"><tr><td colspan=7><font size=+1><b>',$credittext;
  echo '<tr><td><b>Écriture<td><b>Date<td><b>'.$_SESSION['ds_term_accounting_comment'].'<td><b>'
  .$_SESSION['ds_term_accounting_reference'].'<td><b>Infos<td><b>Montant<td>';
  
  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid,adjustment.adjustmentgroupid,accounting_simplifiedid
  ,adjustmentcomment_line as infos
  from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and matchingid=0 and deleted=0 and closing=0 and debit=0 and value>0 and integrated<4';
  if ($clientid > 0) { $query .= ' and referenceid=?'; $query_prm = array($clientid); }
  else { $query .= ' and accountingnumberid=?'; $query_prm = array($anid); }
  if (isset($startdate)) { $query .= ' and adjustmentdate>=?'; array_push($query_prm,$startdate); }
  if (isset($stopdate)) { $query .= ' and adjustmentdate<=?'; array_push($query_prm,$stopdate); }
  $query .= ' order by adjustmentdate,adjustmentid,linenr';
  require('inc/doquery.php');
  $totalresults += $num_results;
  for ($i = 0; $i < $num_results; $i++)
  {
    $credittotal += $query_result[$i]['value'];
    echo d_tr();
    if ($query_result[$i]['accounting_simplifiedid'] > 0)
    {
      $link = 'accounting.php?accountingmenu=simplified&modify=1&accountingmenu_sa=simplified&simplified_currentid='.$accounting_simplifiedgroupidA[$query_result[$i]['accounting_simplifiedid']].'&asid='.$query_result[$i]['accounting_simplifiedid'].'&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    else
    {
      $link = 'accounting.php?accountingmenu=entry&accountingmenu_sa=simplified&readme=1&agid='.$query_result[$i]['adjustmentgroupid'];
    }
    echo d_td_old($query_result[$i]['adjustmentgroupid'],1,0,0,$link);
    echo d_td_old(datefix($query_result[$i]['adjustmentdate']));
    echo d_td_old($query_result[$i]['adjustmentcomment']);
    echo d_td_old($query_result[$i]['reference']);
    echo d_td_old($query_result[$i]['infos']);
    echo d_td_old(myfix($query_result[$i]['value']),1);
    echo '<td class="sum2 value2" style="display:none;">',($query_result[$i]['value']+0);
    echo '<td><input class="chkclass2" type=checkbox name="'.$query_result[$i]['adjustmentid'].'" value=1>';
  }
  echo d_tr(),d_td_old('Total',0,2,4),d_td(),d_td_old(myfix($credittotal),1,2); # ,d_td_old('')
  echo '<td id="sum2" align=right></td>';

  echo '</table>';
  echo '<tr><td colspan=3 align=center><p id="sum3"></p></tr>';
  if ($totalresults > 0) { echo '<tr><td colspan=3 align=center><input type="checkbox" onClick="toggle(this)"> Tout cocher</td></tr>'; }
  echo '<tr><td colspan=3 align=center><b>Balance: ' . myfix(d_abs($credittotal-$debittotal));
  if ($credittotal > $debittotal) { echo ' (crédit)'; }
  if ($debittotal > $credittotal) { echo ' (débit)'; }
  echo '</td></tr>';
  echo '<tr><td colspan=3 align="center"><input type="submit" value="Valider"></td></tr>';
  echo '<input type=hidden name="accountingmenu" value="' . $accountingmenu . '">
  <input type=hidden name="matchme" value=1><input type=hidden name="accountingmenu_sa" value="control">';
  if ($clientid > 0) { echo '<input type=hidden name="clientid" value="' . $clientid . '">'; }
  else { echo '<input type=hidden name="anid" value="' . $anid . '">'; }
  ###
  echo '<tr><td colspan=100 align=center>';
  $datename = 'startdate'; if (!isset($startdate)) { $selecteddate = '0000-00-00'; }
  require('inc/datepicker.php');
  echo ' à ';
  $datename = 'stopdate'; if (!isset($stopdate)) { $selecteddate = '0000-00-00'; }
  require('inc/datepicker.php');
  ###
  echo '</table></form>';
  
  $debittotal = 0; $credittotal = 0;
  
  echo '<h2>Lettrés</h2>';
  echo '<table><tr><td valign=top>';
  echo '<table class="detailinput"><tr><td colspan=7><font size=+1><b>',$debittext,'</td></tr>';
  echo '<tr><td><b>Lettrage<td><b>Écriture<td><b>Date<td><b>'.$_SESSION['ds_term_accounting_comment']
  .'<td><b>'.$_SESSION['ds_term_accounting_reference'].'<td><b>Infos<td><b>Montant</tr>';

  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid,adjustment.adjustmentgroupid,matchingid
  ,adjustmentcomment_line as infos
  from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and matchingid>0 and deleted=0 and debit=1 and value>0';
  if ($clientid > 0) { $query .= ' and referenceid=?'; $query_prm = array($clientid); }
  else { $query .= ' and accountingnumberid=?'; $query_prm = array($anid); }
  $query .= ' order by adjustmentdate,adjustmentid,linenr';
  require('inc/doquery.php');
  $totalresults += $num_results;
  for ($i = 0; $i < $num_results; $i++)
  {
    $debittotal += $query_result[$i]['value'];
    echo d_tr();
    $link = '##accounting.php?accountingmenu=undo&accountingmenu_sa=control&matchingid='.$query_result[$i]['matchingid'];
    echo d_td_old(myfix($query_result[$i]['matchingid']),1,0,0,$link);
    echo d_td_old(myfix($query_result[$i]['adjustmentgroupid']),1,0,0,$link);
    echo d_td_old(datefix($query_result[$i]['adjustmentdate']));
    echo d_td_old($query_result[$i]['adjustmentcomment']);
    echo d_td_old($query_result[$i]['reference']);
    echo d_td_old($query_result[$i]['infos']);
    echo d_td_old(myfix($query_result[$i]['value']),1);
  }
  echo d_tr(),d_td_old('Total',0,2,6),d_td_old(myfix($debittotal),1,2);
  
  echo '</table></td><td width=25></td><td valign=top>';
  echo '<table class="detailinput"><tr><td colspan=7><font size=+1><b>',$credittext;
  echo '<tr><td><b>Lettrage<td><b>Écriture<td><b>Date<td><b>'.$_SESSION['ds_term_accounting_comment']
  .'<td><b>'.$_SESSION['ds_term_accounting_reference'].'<td><b>Infos<td><b>Montant</tr>';
  
  $query = 'select adjustmentdate,adjustmentcomment,reference,value,debit,adjustmentid,adjustment.adjustmentgroupid,matchingid
  ,adjustmentcomment_line as infos
  from adjustmentgroup,adjustment
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and matchingid>0 and deleted=0 and debit=0 and value>0';
  if ($clientid > 0) { $query .= ' and referenceid=?'; $query_prm = array($clientid); }
  else { $query .= ' and accountingnumberid=?'; $query_prm = array($anid); }
  $query .= ' order by adjustmentdate,adjustmentid,linenr';
  require('inc/doquery.php');
  $totalresults += $num_results;
  for ($i = 0; $i < $num_results; $i++)
  {
    $credittotal += $query_result[$i]['value'];
    echo d_tr();
    $link = '##accounting.php?accountingmenu=undo&accountingmenu_sa=control&matchingid='.$query_result[$i]['matchingid'];
    echo d_td_old(myfix($query_result[$i]['matchingid']),1,0,0,$link);
    echo d_td_old(myfix($query_result[$i]['adjustmentgroupid']),1);
    echo d_td_old(datefix($query_result[$i]['adjustmentdate']));
    echo d_td_old($query_result[$i]['adjustmentcomment']);
    echo d_td_old($query_result[$i]['reference']);
    echo d_td_old($query_result[$i]['infos']);
    echo d_td_old(myfix($query_result[$i]['value']),1);
  }
  echo d_tr(),d_td_old('Total',0,2,6),d_td_old(myfix($credittotal),1,2);
  echo '</table>';

}

if ($showmenu)
{
  ?><table class="transparent"><tr><td valign=top>
  
  <?php
  $query = $query_list_account;
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results)
  {
    ?><h2>Lettrage Compte</h2>
    <form method="post" action="accounting.php"><table>
    <tr><td>Compte:</td><td><select name="anid"><?php
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['accountingnumberid'] . '">' . d_output($row['acnumber']) . ': ' . d_output($row['acname']) . '</option>';
    }
    echo '</select></td></tr>';
    ?><tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="accountingmenu_sa" value="control">
    <input type="submit" value="Valider"></td></tr>
    </table></form>
    <?php
  }
  ?>
  
  <td width=50>&nbsp;&nbsp;<td valign=top>

  <h2>Lettrage Fournisseur</h2>
  <?php
  $query = $query_list_party1;
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results)
  {
    ?><form method="post" action="accounting.php"><table><tr><td>Compte:</td><td><select name="clientid"><?php
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['clientid'] . '">' . d_output(d_decode($row['clientname'])) . '</option>';
    }
    echo '</select>';
    ?><tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="accountingmenu_sa" value="control">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  }
  
  ?><tr><td colspan=3>&nbsp;
  <tr><td valign=top>
  
  <h2>Lettrage Employé(e)</h2>
  <?php
  $query = $query_list_party2;
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results)
  {
    ?><form method="post" action="accounting.php"><table><tr><td>Compte:</td><td><select name="clientid"><?php
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['clientid'] . '">' . d_output(d_decode($row['clientname'])) . '</option>';
    }
    echo '</select>';
    ?><tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="difflist" value="2">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  }
  
  ?><td width=50>&nbsp;&nbsp;<td valign=top>
  
  <h2>Lettrage Autres Tiers</h2>
  <?php
  $query = $query_list_party3;
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results)
  {
    ?><form method="post" action="accounting.php"><table><tr><td>Compte:</td><td><select name="clientid"><?php
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<option value="' . $row['clientid'] . '">' . d_output(d_decode($row['clientname'])) . '</option>';
    }
    echo '</select>';
    ?><tr><td colspan="2" align="center">
    <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="difflist" value="3">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  }
  
  ?></table>
  
  <?php
  echo '<br><br><h2>Mes Options</h2><form method="post" action="accounting.php"><table>';
  echo '<tr><td>Lettrage: Afficher comptes vides</td><td><input type="checkbox" name="accounting_matchempty" value="1"'; if ($_SESSION['ds_accounting_matchempty']) echo ' CHECKED'; echo '></td></tr>';
  ?><tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountingmenu_sa" value="control">
  <input type=hidden name="set_accounting_matchempty" value="1">
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <br>
  <br><p class="alert">Vous pouvez lettrer les clients dans la gestion commerciale.</p>
  <?php
}

?>