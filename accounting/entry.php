<?php

# TODO refactor  2019 10 12 no really, needs refactoring bad, especially POST/GET variables

require('inc/autocomplete_accounting.php');

$accounting_inputsize = 70; #accounting input size, TODO as parameter, simplified.php and entry.php

if ($_SESSION['ds_nbtablecolors'] > 1)
{
  echo '<style>
  input[type="text"].odd {
    background-color : '.$_SESSION['ds_tablecolor1'].'; 
  }
  input[type="text"].even {
    background-color : '.$_SESSION['ds_tablecolor2'].'; 
  }
  </style>';
}

require('preload/accountingnumber.php');
require('preload/journal.php');
require('preload/adjustmentgroup_tag.php');

$numberoflines = $_SESSION['ds_accountinglines'];
if ($numberoflines > 1000) { $numberoflines = 1000; }

if ($_SESSION['ds_accounting_accountbyselect'] == 1)
{
  for ($i=0; $i < $numberoflines; $i++)
  {
    if(isset($_POST['accountingnumber' . $i . 'id']) && $_POST['accountingnumber' . $i . 'id'] > 0) { $_POST['accountingnumber' . $i . 'id'] = $accountingnumberA[$_POST['accountingnumber' . $i . 'id']]; }
    else { $_POST['accountingnumber' . $i . 'id'] = ''; }
  }
}

$datename = 'adjustmentdate';
require('inc/datepickerresult.php');
$selecteddate = $adjustmentdate;
###
$query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results) { $min_adjustmentdate = $query_result[0]['adjustmentdate']; }
else { $min_adjustmentdate = $_SESSION['ds_startyear'].'-00-00'; }
if ($adjustmentdate < $min_adjustmentdate)
{
  $adjustmentdate = $min_adjustmentdate;
  $selecteddate = $adjustmentdate;
}
$dp_datepicker_min = $min_adjustmentdate;
###

$PA['save'] = 'int';
$PA['adjustmentcomment'] = '';
$PA['reference'] = '';
$PA['deleted'] = 'int';
$PA['readme'] = 'int';
$PA['journalid'] = 'int';
$PA['adjustmentgroup_tagid'] = 'int';
$PA['copy'] = 'uint';
$PA['reverse'] = 'uint';
$PA['agid'] = 'int';
$PA['val'] = 'uint'; # GET from insert_from_bankstatement
$PA['anid'] = 'uint'; # GET from insert_from_bankstatement
$PA['com'] = ''; # GET from insert_from_bankstatement
require('inc/readpost.php');
$error = 0;

$query = 'select accountingnumberid,acnumber,acname from accountingnumber where needreference=1 order by accountingnumberid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $accountingnumberidA[$i] = $row['accountingnumberid'];
  $acnumberA[$i] = $row['acnumber'];
  $acnameA[$i] = $row['acname'];
}

if ($agid == 0 && $val > 0 && $anid > 0)
{
  $adjustmentcomment = $com;
  $_POST['cvalue0'] = $_POST['dvalue1'] = $val;
  if ($_SESSION['ds_accounting_accountbyselect'] == 1) { $_POST['accountingnumber0id'] = $anid; }
  else
  {
    $_POST['accountingnumber0id'] = $accountingnumberA[$anid];
  }
}

if ($agid > 0 && $readme)
{
  $query = 'select integrated,adjustmentdate,adjustmentcomment,reference,deleted,accounting_simplifiedid
  from adjustmentgroup
  where closing=0 and adjustmentgroupid=?';
  $query .= ' and adjustmentdate>=?';
  $query_prm = array($agid, $min_adjustmentdate);
  require('inc/doquery.php');
  if ($num_results != 1)
  {
    $agid = -1;
  }
  elseif ($query_result[0]['integrated'] > 0)
  {
    echo '<p class=alert>Ne peut pas modifier écriture '.$agid.', car elle est integrée: '.d_output($query_result[0]['adjustmentcomment']).' '.d_output($query_result[0]['reference']).'</p>';
    $agid = -1;
  }
  elseif ($query_result[0]['accounting_simplifiedid'] > 0)
  {
    $query = 'select accounting_simplifiedname as n,accounting_simplifiedgroupname as gn from accounting_simplified,accounting_simplifiedgroup
    where accounting_simplified.accounting_simplifiedgroupid=accounting_simplifiedgroup.accounting_simplifiedgroupid and accounting_simplifiedid=?';
    $query_prm = array($query_result[0]['accounting_simplifiedid']);
    require('inc/doquery.php');
    echo '<p class=alert>Cette écriture vient d\'un modèle simplifié: '. d_output($query_result[0]['gn']) .' => '.d_output($query_result[0]['n']).'</p>';
  }
  if ($agid > 0)
  {
    $query = 'select bankstatementid from bankstatement where adjustmentgroupid=? limit 1';
    $query_prm = array($agid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p class=alert>Modification impossible car l\'écriture '.$agid.' figure sur un relevé bancaire.</p>';
      $agid = -1;
    }
  }
  if ($agid > 0)
  {
    $query = 'select integrated,adjustmentdate,adjustmentcomment,reference,deleted,accounting_simplifiedid,journalid,adjustmentgroup_tagid
    from adjustmentgroup where adjustmentgroupid=?';
    $query_prm = array($agid);
    require('inc/doquery.php');
    $journalid = $query_result[0]['journalid'];
    $adjustmentgroup_tagid = $query_result[0]['adjustmentgroup_tagid'];
    $adjustmentcomment = $query_result[0]['adjustmentcomment'];
    $reference = $query_result[0]['reference'];
    $selecteddate = $query_result[0]['adjustmentdate'];
    $deleted = $query_result[0]['deleted'];
    $integrated = $query_result[0]['integrated'];
    $matchingerror = 0; $matchingerrorlist = array(); $reconcileerrorlist = array();
    $query = 'select adjustmentcomment_line,matchingid,value,referenceid,debit,accountingnumberid,reconciliationid,linenr
    from adjustment where adjustmentgroupid=?';
    $query_prm = array($agid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($query_result[$i]['matchingid'] > 0)
      {
        $matchingerror = 1;
        array_push($matchingerrorlist,$query_result[$i]['matchingid']);
      }
      if ($query_result[$i]['reconciliationid'] > 0)
      {
        $matchingerror = 2;
        array_push($reconcileerrorlist,$query_result[$i]['reconciliationid']);
      }
      if ($query_result[$i]['linenr'] >= $numberoflines)
      {
        $numberoflines = $query_result[$i]['linenr']+1;
      }
    }
    if ($matchingerror == 1 && $copy == 0)
    {
      $adjustmentcomment = '';
      $reference = '';
      $selecteddate = '';
      echo '<p class=alert>L\'écriture '.$agid.' ne peut être modifiée, car elle est lettrée.<br>Numéros lettrage :';
      $matchingerrorlist = array_unique($matchingerrorlist);
      foreach ($matchingerrorlist as $kladd) { echo ' ' . $kladd; }
      echo '</p>';
    }
    elseif ($matchingerror == 2 && $copy == 0)
    {
      $adjustmentcomment = '';
      $reference = '';
      $selecteddate = '';
      echo '<p class=alert>L\'écriture '.$agid.' ne peut être modifiée, car elle est rapprochée.<br>Numéros rapprochement :';
      $reconcileerrorlist = array_unique($reconcileerrorlist);
      foreach ($reconcileerrorlist as $kladd) { echo ' ' . $kladd; }
      echo '</p>';
    }
    else
    {
      $d_i = 0;
      for ($i=0; $i < $num_results; $i++)
      {
        if ($integrated) { $d_i = $i; }
        else { $d_i = $query_result[$i]['linenr']; }
        $_POST['comment' . $d_i] = $query_result[$i]['adjustmentcomment_line'];
        $_POST['accountingnumber' . $d_i . 'id'] = $accountingnumberA[$query_result[$i]['accountingnumberid']];
        $didP[$d_i] = $query_result[$i]['referenceid']; if ($didP[$d_i] == 0) { $didP[$d_i] = ''; }
        if ($reverse)
        {
          if ($query_result[$i]['debit'] == 1) { $query_result[$i]['debit'] = 0; }
          else { $query_result[$i]['debit'] = 1; }
        }
        if ($query_result[$i]['debit'] == 1)
        {
          $_POST['dvalue' . $d_i] = $query_result[$i]['value']+0;
        }
        else
        {
          $_POST['cvalue' . $d_i] = $query_result[$i]['value']+0;
        }
      }
    }
    if ($copy)
    {
      $agid = -1;
    }
    elseif ($matchingerror)
    {
      function showtitle($title)
      {
        echo '<TITLE>TEM ' . $title . '</TITLE>';
      }
      $notitle = 1;
      $startdate = '2000-01-01';
      $stopdate = '3000-01-01';
      $_POST['id'] = $agid;
      $_POST['accounting_simplifiedid'] = -1;
      $_POST['journalid'] = -1;
      $_POST['adjustmentgroup_tagid'] = -1;
      unset($id);
      require('reportwindow/entryreport.php');
      echo '<br>';
      $agid = -1;
    }
  }
}

for ($i=0; $i < $numberoflines; $i++)
{
  if (!isset($_POST['dvalue' . $i])) { $_POST['dvalue' . $i] = ''; }
  if (!isset($_POST['cvalue' . $i])) { $_POST['cvalue' . $i] = ''; }
  if (!isset($_POST['comment' . $i])) { $_POST['comment' . $i] = ''; }
  if (isset($_POST['accountingnumber' . $i . 'id']) && $_POST['accountingnumber' . $i . 'id'] != "")
  {
    $dacnumberP[$i] = array_search($_POST['accountingnumber' . $i . 'id'], $accountingnumberA);
    if ($accountingnumber_deletedA[$dacnumberP[$i]] == 1) { $error = 8; }
    if (isset($acnumberA) && in_array($_POST['accountingnumber' . $i . 'id'], $acnumberA))
    {
      if (isset($didP[$i]) && $didP[$i] > 0) { $client = $didP[$i]; }
      else { $client = $_POST['client' . $i]; }
      require('inc/findclient.php'); $didP[$i] = $clientid;
      if ($didP[$i] < 1) { $error = 6; }
    }
    elseif (isset($_POST['client' . $i]) && $_POST['client' . $i] != '') { $error = 7; }
  }
  elseif ((isset($_POST['dvalue' . $i]) && $_POST['dvalue' . $i] > 0) || (isset($_POST['cvalue' . $i]) && $_POST['cvalue' . $i] > 0)) { $error = 8; }
}

$totaldebit = 0; $totalcredit = 0;
for ($i=0; $i < $numberoflines; $i++)
{
  if (isset($_POST['accountingnumber' . $i . 'id']) && $_POST['accountingnumber' . $i . 'id'] == ""
  || isset($dacnumberP[$i]) && $dacnumberP[$i]) { }
  else { $error = 8; }
  if (!isset($_POST['did' . $i]) || $_POST['did' . $i] == "" || $didP[$i]) { }
  else { $error = 1; }
  if (!isset($_POST['dvalue' . $i]) || $_POST['dvalue' . $i] == "" || (is_numeric($_POST['dvalue' . $i]) && $_POST['dvalue' . $i] > 0)) { }
  else { $error = 1; }
  if (!isset($_POST['cvalue' . $i]) || $_POST['cvalue' . $i] == "" || (is_numeric($_POST['cvalue' . $i]) && $_POST['cvalue' . $i] > 0)) { }
  else { $error = 1; }
  if (isset($_POST['dvalue' . $i]) && $_POST['dvalue' . $i] > 0 && isset($_POST['cvalue' . $i]) && $_POST['cvalue' . $i] > 0) { $error = 2; }

  if (isset($_POST['dvalue' . $i]) && $_POST['dvalue' . $i] != '') { $totaldebit = $totaldebit + $_POST['dvalue' . $i]; }
  if (isset($_POST['cvalue' . $i]) && $_POST['cvalue' . $i] != '') { $totalcredit = $totalcredit + $_POST['cvalue' . $i]; }
}
if ($totaldebit != $totalcredit) { $error = 3; }
if ($totaldebit == 0) { $error = 4; }
if ($adjustmentcomment == '') { $error = 5; }

if ($save)
{
  if ($error != 0)
  {
    echo '<p><font color="' . $_SESSION['ds_alertcolor'] . '">';
    switch ($error)
    {
      case 2:
      echo 'Débit et crédit sur la même ligne.';
      break;
      
      case 3:
      echo 'Débit et crédit différents.';
      break;
      
      case 4:
      echo 'Écriture sans valeur.';
      break;
      
      case 5:
      echo $_SESSION['ds_term_accounting_comment'].' est obligatoire.';
      break;
      
      case 6:
      echo 'Tiers manquant.';
      break;
      
      case 7:
      echo 'Tiers pour compte hors tiers.';
      break;
      
      case 8:
      echo 'Erreur de compte.';
      break;
      
      default:
      echo 'N\'a pas pu être enregistré';
      break;
    }
    echo '</font></p><br>';
  }
  else
  {
    if ($agid > 0)
    {
      $query = 'update adjustmentgroup set userid=?,adjustmentdate=?,adjustmentcomment=?,reference=?,deleted=?
      ,originaladjustmentdate=curdate(),adjustmenttime=curtime(),accounting_simplifiedid=0,journalid=?,adjustmentgroup_tagid=?
      where adjustmentgroupid=?';
      $query_prm = array($_SESSION['ds_userid'],$adjustmentdate,$adjustmentcomment,$reference,$deleted,$journalid
      ,$adjustmentgroup_tagid,$agid);
      require('inc/doquery.php');
      $wasinsert = 0;
      $query = 'update adjustment set adjustmentcomment_line="",value=0,referenceid=0,accountingnumberid=0 where adjustmentgroupid=?';
      $query_prm = array($agid);
      require('inc/doquery.php');
      for ($i=0; $i < $numberoflines; $i++)
      {
        $line_existsA[$i] = 0;
      }
      $query = 'select linenr from adjustment where adjustmentgroupid=?';
      $query_prm = array($agid);
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        $linenr = $query_result[$i]['linenr'];
        $line_existsA[$linenr] = 1;
      }
      for ($i=0; $i < $numberoflines; $i++)
      {
        if ($_POST['accountingnumber' . $i . 'id'] != "" && $_POST['dvalue' . $i] > 0)
        {
          if (isset($didP[$i])) { $kladd = $didP[$i]; }
          else { $kladd = 0; }
          if ($line_existsA[$i] == 1)
          {
            $query = 'update adjustment set adjustmentcomment_line=?,value=?,referenceid=?,accountingnumberid=?,debit=1
            where adjustmentgroupid=? and linenr=?';
            $query_prm = array($_POST['comment' . $i], $_POST['dvalue' . $i], $kladd, $dacnumberP[$i], $agid, $i);
          }
          else
          {
            $query = 'insert into adjustment (adjustmentcomment_line,adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid,linenr)
            values (?,?,?,1,?,?,0,?)';
            $query_prm = array($_POST['comment' . $i], $agid, $_POST['dvalue' . $i], $kladd, $dacnumberP[$i], $i);
          }
          require('inc/doquery.php');
        }
        if ($_POST['accountingnumber' . $i . 'id'] != "" && $_POST['cvalue' . $i] > 0)
        {
          if (isset($didP[$i])) { $kladd = $didP[$i]; }
          else { $kladd = 0; }
          if ($line_existsA[$i] == 1)
          {
            $query = 'update adjustment set adjustmentcomment_line=?,value=?,referenceid=?,accountingnumberid=?,debit=0 where adjustmentgroupid=? and linenr=?';
            $query_prm = array($_POST['comment' . $i], $_POST['cvalue' . $i], $kladd, $dacnumberP[$i], $agid, $i);
          }
          else
          {
            $query = 'insert into adjustment (adjustmentcomment_line,adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid,linenr) values (?,?,?,0,?,?,0,?)';
            $query_prm = array($_POST['comment' . $i], $agid, $_POST['cvalue' . $i], $kladd, $dacnumberP[$i], $i);
          }
          require('inc/doquery.php');
        }
      }
    }
    else
    {
      $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment, reference,
      accounting_simplifiedid, journalid, adjustmentgroup_tagid) values (?, ?, curdate(), curtime(), ?, ?, 0, ?, ?)';
      $query_prm = array($_SESSION['ds_userid'], $adjustmentdate, $adjustmentcomment, $reference, $journalid, $adjustmentgroup_tagid);
      require('inc/doquery.php');
      $agid = $query_insert_id;
      $wasinsert = 1;
      for ($i=0; $i < $numberoflines; $i++)
      {
        if ($_POST['accountingnumber' . $i . 'id'] != "" && $_POST['dvalue' . $i] > 0)
        {
          if (isset($didP[$i])) { $kladd = $didP[$i]; }
          else { $kladd = 0; }
          $query = 'insert into adjustment (adjustmentcomment_line,adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid,linenr) values (?,?,?,1,?,?,0,?)';
          $query_prm = array($_POST['comment' . $i], $agid, $_POST['dvalue' . $i], $kladd, $dacnumberP[$i], $i);
          require('inc/doquery.php');
        }
        if ($_POST['accountingnumber' . $i . 'id'] != "" && $_POST['cvalue' . $i] > 0)
        {
          if (isset($didP[$i])) { $kladd = $didP[$i]; }
          else { $kladd = 0; }
          $query = 'insert into adjustment (adjustmentcomment_line,adjustmentgroupid,value,debit,referenceid,accountingnumberid,matchingid,linenr) values (?,?,?,0,?,?,0,?)';
          $query_prm = array($_POST['comment' . $i], $agid, $_POST['cvalue' . $i], $kladd, $dacnumberP[$i], $i);
          require('inc/doquery.php');
        }
      }
    }
  }
}

if ($save && $error == 0)
{
  if ($wasinsert == 0) { echo '<h2>L\'écriture '.$agid.' a été modifiée</h2>'; }
  else { echo '<h2>L\'écriture '.$agid.' a été créée</h2>'; }
}
else
{
  if ($agid > 0) { echo '<h2>Modifier l\'écriture '.$agid.'</h2>'; }
  else { echo '<h2>Écriture</h2>'; }
}
?>
<form method="post" action="accounting.php"><table>
<?php
echo '<tr><td>'.$_SESSION['ds_term_accounting_comment'].':</td><td colspan=7><input autofocus type="text" name="adjustmentcomment" value="' . d_input($adjustmentcomment) . '" size='.$accounting_inputsize.'></td></tr>';
echo '<tr><td>'.$_SESSION['ds_term_accounting_reference'].':</td><td colspan=7><input type="text" name="reference" value="' . d_input($reference) . '" size='.$accounting_inputsize.'></td></tr>';
if (isset($journalA))
{
  $dp_itemname = 'journal'; $dp_description = 'Journal'; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $journalid;
  require('inc/selectitem.php');
}
if (isset($adjustmentgroup_tagA))
{
  echo ' &nbsp; ';
  $dp_itemname = 'adjustmentgroup_tag'; $dp_description = $_SESSION['ds_term_accounting_tag'];
  $dp_notable = 1; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $adjustmentgroup_tagid;
  require('inc/selectitem.php');
}
echo '<tr><td>Date:</td><td colspan=7>';
$$datename = $selecteddate;
require('inc/datepicker.php');
echo '</td></tr>';
if ($agid > 0)
{
  if ($deleted == 1) { echo '<tr><td>Supprimé:</td><td colspan=7><input type="checkbox" name="deleted" checked value=1>'; }
  else { echo '<tr><td>Supprimer:</td><td colspan=7><input type="checkbox" name="deleted" value=1>'; }
}
echo '<tr><td colspan=10>&nbsp;</td></tr>';
?>
<tr><td><b>Compte</td><td><b>Tiers</td><td><b>Débit</td><td><b>Crédit<td><b>Infos<?php
$odd_even = 'even';
for ($i=0; $i < $numberoflines; $i++)
{
  if ($odd_even == 'odd') { $odd_even = 'even'; $dp_style = 'background-color : '.$_SESSION['ds_tablecolor2']; }
  else { $odd_even = 'odd'; $dp_style = 'background-color : '.$_SESSION['ds_tablecolor1']; }

  if (!isset($_POST['accountingnumber' . $i . 'id']) || $_POST['accountingnumber' . $i . 'id'] == ""
  || $dacnumberP[$i]) { $ourcolor = $_SESSION['ds_fgcolor']; }
  else { $ourcolor = $_SESSION['ds_alertcolor']; $error = 1; }
  if ($_SESSION['ds_accounting_accountbyselect'] == 1)
  {
    echo '<tr>';
    $dp_itemname = 'accountingnumber'; $dp_addtoid = $i; $dp_long = 1;
    $dp_selectedid = array_search($_POST['accountingnumber' . $i . 'id'],$accountingnumberA);
    require('inc/selectitem.php');
  }
  else
  {
    $kladd = ''; if (isset($_POST['accountingnumber' . $i . 'id'])) { $kladd = $_POST['accountingnumber' . $i . 'id']; }
    echo '<tr><td><input class="' . $odd_even . '" type="text" STYLE="color: ' . $ourcolor . '; text-align:right" name="accountingnumber' . $i . 'id"
    value="' . $kladd . '" id="accounting_autocomplete' . $i . '" autocomplete="off" size=10></td>';
  }

  echo '<td>'; $dp_nodescription = 1; $dp_addtoid = $i; if (isset($didP[$i])) { $client = $didP[$i]; } else { $client = ''; }
  require('inc/selectclient.php');

  if ($_POST['dvalue' . $i] == "" || (is_numeric($_POST['dvalue' . $i]) && $_POST['dvalue' . $i] > 0)) { $ourcolor = $_SESSION['ds_fgcolor']; }
  else { $ourcolor = $_SESSION['ds_alertcolor']; $error = 1; }
  if ($_POST['dvalue' . $i] == 0) { $_POST['dvalue' . $i] = ''; }
  echo '<td><input class="' . $odd_even . '" type="text" STYLE="color: ' . $ourcolor . '; text-align:right" name="dvalue' . $i . '" value="' . $_POST['dvalue' . $i] . '" size=15>';

  if ($_POST['cvalue' . $i] == "" || (is_numeric($_POST['cvalue' . $i]) && $_POST['cvalue' . $i] > 0)) { $ourcolor = $_SESSION['ds_fgcolor']; }
  else { $ourcolor = $_SESSION['ds_alertcolor']; $error = 1; }
  if ($_POST['cvalue' . $i] == 0) { $_POST['cvalue' . $i] = ''; }
  echo '<td><input class="' . $odd_even . '" type="text" STYLE="color: ' . $ourcolor . '; text-align:right" name="cvalue' . $i . '" value="' . $_POST['cvalue' . $i] . '" size=15>';
  echo '<td><input class="' . $odd_even . '" type="text" STYLE="color: ' . $ourcolor . '; text-align:right" name="comment' . $i . '" value="' . $_POST['comment' . $i] . '" size=15>';
}
echo '<tr><td colspan=2><td align=center><b>' . myfix($totaldebit) . '</b></td><td align=center><b>' . myfix($totalcredit) . '</b></td></tr>';
#if (!$save || ($save && $error != 0))
#{
  echo '<tr><td align="center" colspan=7><input type="submit" value="Valider"></td></tr>'; 
#}
echo '<input type=hidden name="save" value="1"><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type=hidden name="accountingmenu_sa" value="simplified">';
if ($agid > 0) { echo '<input type=hidden name=agid value='.$agid.'>'; }
echo '</table></form>';

/*
$listdetails = 1; # show amounts etc (query in loop)
$query = 'select adjustmentgroup.adjustmentgroupid,adjustmentdate,adjustmentcomment,reference,deleted,journalid from adjustmentgroup
where adjustmentgroup.accounting_simplifiedid=0 and deleted=0 and integrated=0
order by adjustmentdate desc,adjustmentgroupid desc limit 20'; # hardcode last 20
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
if ($num_results_main)
{
  echo '<br><table class=report STYLE="min-width: 800px"><thead><th>Écriture<th>Date<th>'.$_SESSION['ds_term_accounting_comment'].'<th>'.$_SESSION['ds_term_accounting_reference'].'<th>Comptes Débit<th>Comptes Crédit<th>Valeur<th>Journal</thead>';
  for ($i = 0; $i < $num_results_main; $i++)
  {
    echo d_tr();
    echo d_td_old($main_result[$i]['adjustmentgroupid'],0,0,0,'##accounting.php?accountingmenu=entry&accountingmenu_sa=simplified&readme=1&agid='.$agid.'&agid='.$main_result[$i]['adjustmentgroupid']);
    echo d_td_old(datefix($main_result[$i]['adjustmentdate'], 'short'));
    echo d_td_old($main_result[$i]['adjustmentcomment']);
    echo d_td_old($main_result[$i]['reference']);
    if ($listdetails == 1)
    {
      $kladd = ''; $valueD = 0;
      $query = 'select accountingnumberid,value from adjustment where adjustmentgroupid=? and debit=1';
      $query_prm = array($main_result[$i]['adjustmentgroupid']);
      require('inc/doquery.php');
      for ($y = 0; $y < $num_results; $y++)
      {
        $kladd .= $accountingnumberA[$query_result[$y]['accountingnumberid']] . ' ';
        $valueD += $query_result[$y]['value'];
      }
      echo d_td_old(trim($kladd));
      $kladd = ''; $valueC = 0;
      $query = 'select accountingnumberid,value from adjustment where adjustmentgroupid=? and debit=0';
      $query_prm = array($main_result[$i]['adjustmentgroupid']);
      require('inc/doquery.php');
      for ($y = 0; $y < $num_results; $y++)
      {
        $kladd .= $accountingnumberA[$query_result[$y]['accountingnumberid']] . ' ';
        $valueC += $query_result[$y]['value'];
      }
      echo d_td_old(trim($kladd));
      if ($valueD == $valueC) { echo d_td_old(myfix($valueD),1); }
      else { echo d_td_old('Erreur'); }
    }
    if (isset($journalA[$main_result[$i]['journalid']])) { echo d_td_old($journalA[$main_result[$i]['journalid']]); }
    else { echo d_td_old(); }
  }
}
*/
?>