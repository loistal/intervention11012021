<?php

$PA['importme'] = 'uint';
$PA['bankaccountid'] = 'uint';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['autodetect'] = 'uint';
$PA['undo_id'] = 'uint';
require('inc/readpost.php');

if ($undo_id)
{
  $query = 'update bankstatement set adjustmentgroupid=0,adjustmentid=0 where bankstatementid=? limit 1';
  $query_prm = array($undo_id);
  require('inc/doquery.php');
}

if ($bankaccountid > 0)
{
  if (isset($_FILES['userfile']['tmp_name']) && is_file($_FILES['userfile']['tmp_name']))
  {
    $query = 'select bankname from bank,bankaccount
    where bankaccount.bankid=bank.bankid and bankaccountid=?';
    $query_prm = array($bankaccountid);
    require('inc/doquery.php');
    $bankname = $query_result[0]['bankname'];
    if ($bankname == 'BP' || $bankname == 'BT')
    {
      $i = 0;
      if (($fp = fopen($_FILES['userfile']['tmp_name'], "r")) !== FALSE)
      {
        while (($dataA = fgetcsv($fp, 10000, ";")) !== FALSE)
        {
          if ($bankname == 'BP' && $dataA[0][2] == '/' && $dataA[0][5] == '/')
          {
            $statementdateA[$i] = d_builddate(substr($dataA[0],0,2),substr($dataA[0],3,2),substr($dataA[0],6,4));
            $statementtextA[$i] = utf8_encode($dataA[1]);
            $validitydateA[$i] = $dataA[2];
            $amountA[$i] = $dataA[3];
            $statementcodeA[$i] = $dataA[4];
            $chequenoA[$i] = '';
            $i++;
          }
          elseif ($bankname == 'BT' && $dataA[5][2] == '/' && $dataA[5][5] == '/')
          {
            $statementdateA[$i] = d_builddate(substr($dataA[5],0,2),substr($dataA[5],3,2),substr($dataA[5],6,4)); # 2021 01 02 changed to 6,4 from 5,4 NOT tested
            $statementtextA[$i] = utf8_encode($dataA[9]);
            $validitydateA[$i] = d_builddate(substr($dataA[7],0,2),substr($dataA[7],3,2),substr($dataA[7],6,4));
            $amountA[$i] = str_replace(',', '.', $dataA[4]);
            $statementcodeA[$i] = $dataA[6];
            $chequenoA[$i] = $dataA[8];
            $i++;
          }
        }
        fclose($fp);
        for ($y=($i-1); $y>=0; $y--)
        {
          $query = 'select bankstatementid from bankstatement
          where bankaccountid=? and statementdate=? and validitydate=? and amount=? and statementtext=? and statementcode=?';
          $query_prm = array($bankaccountid,$statementdateA[$y],$validitydateA[$y],$amountA[$y],$statementtextA[$y]
          ,$statementcodeA[$y]);
          require('inc/doquery.php');
          if ($num_results == 0)
          {
            $query = 'insert into bankstatement (bankaccountid,statementdate,validitydate,amount,statementtext,statementcode
            ,chequeno)
            values (?,?,?,?,?,?,?)';
            $query_prm = array($bankaccountid,$statementdateA[$y],$validitydateA[$y],$amountA[$y],$statementtextA[$y]
            ,$statementcodeA[$y],$chequenoA[$y]);
            require('inc/doquery.php');
          }
        }
      }
    }
    else { echo '<p>Banque non reconnue : '.d_output($bankname).'</p>'; }
  }
  
  require('preload/accountingnumber.php');
  require('preload/bank.php');
  require('preload/bankaccount.php');
  $showmenu = 0;
  
  echo '<h2>Relevé '.d_output($bankA[$bankaccount_bankidA[$bankaccountid]] . ' '
  . $bankaccountA[$bankaccountid]);
  if (isset($accountingnumberA[$bankaccount_accountingnumberidA[$bankaccountid]]))
  {
    echo ' (' . $accountingnumberA[$bankaccount_accountingnumberidA[$bankaccountid]] . ')';
    $anid = $bankaccount_accountingnumberidA[$bankaccountid];
  }
  else { $anid = 0; }
  echo '</h2>';
  echo datefix($startdate,'short'),' à '.datefix($stopdate,'short').' &nbsp; - &nbsp; ';
  echo '<a href="
  reportwindow.php?report=entryreport&journalid=-1&adjustmentgroup_tagid=-1&accounting_simplifiedid=-1&startdate='.$startdate.'&stopdate='.$stopdate;
  if ($anid > 0)
  { echo '&accountingnumberid='.$anid; }
  echo '&integrated=-1" target=_blank>Rapport Écritures</a><br><br>';
  
  $query = 'select * from bankstatement where bankaccountid=? and statementdate>=? and statementdate<=?
  order by statementdate desc,bankstatementid desc';
  $query_prm = array($bankaccountid,$startdate,$stopdate);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  
  for ($i=0; $i<$num_results_main; $i++)
  {
    if (isset($_POST['agid'.$main_result[$i]['bankstatementid']])
    && $_POST['agid'.$main_result[$i]['bankstatementid']] != 0)
    {
      $agid = $_POST['agid'.$main_result[$i]['bankstatementid']];
      $query = 'select value,debit,adjustmentid from adjustment where adjustmentgroupid=? and accountingnumberid=?';
      $query_prm = array($agid,$bankaccount_accountingnumberidA[$bankaccountid]);
      require('inc/doquery.php');
      if ($num_results == 1)
      {
        $ok = 1; $debit = $query_result[0]['debit']; $value = $query_result[0]['value'];
        $adjustmentid = $query_result[0]['adjustmentid'];
        if ($main_result[$i]['amount'] < 0 && $debit == 1) { $ok = 0; }
        if ($main_result[$i]['amount'] > 0 && $debit == 0) { $ok = 0; }
        if ($ok)
        {
          # deduct from $value other lines on same agid
          $query = 'select sum(amount) as amount from bankstatement where adjustmentgroupid=?';
          $query_prm = array($agid);
          require('inc/doquery.php');
          if ($num_results) { $value -= d_abs($query_result[0]['amount']); }
        }
        if (d_abs($main_result[$i]['amount']) > $value) { $ok = 0; }
        if ($ok)
        {
          $query = 'update bankstatement set adjustmentgroupid=?,adjustmentid=? where bankstatementid=?';
          $query_prm = array($agid,$adjustmentid,$main_result[$i]['bankstatementid']);
          require('inc/doquery.php');
          $main_result[$i]['adjustmentgroupid'] = $agid;
          $main_result[$i]['adjustmentid'] = $adjustmentid;
        }# TODO else feedback why we cannot use that adjustmentgroup
      }
    }
  }
  
  if ($autodetect && isset($bankaccount_accountingnumberidA[$bankaccountid]))
  {
    for ($i=0; $i<$num_results_main; $i++)
    {
      if ($main_result[$i]['adjustmentid'] == 0)
      {
        $query = 'select adjustment.adjustmentgroupid,adjustmentid from adjustment,adjustmentgroup
        where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
        and accountingnumberid=? and adjustmentdate=? and value=?';
        if ($main_result[$i]['amount'] > 0) { $query .= ' and debit=1'; }
        else { $query .= ' and debit=0'; }
        $query_prm = array($bankaccount_accountingnumberidA[$bankaccountid],$main_result[$i]['statementdate'],
        d_abs($main_result[$i]['amount']));
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          # try again with adjacent dates
          $query = 'select adjustment.adjustmentgroupid,adjustmentid from adjustment,adjustmentgroup
          where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
          and accountingnumberid=? and (adjustmentdate=?
          or adjustmentdate = DATE_SUB(?,INTERVAL 1 DAY)
          or adjustmentdate = DATE_ADD(?,INTERVAL 1 DAY))
          and value=?';
          if ($main_result[$i]['amount'] > 0) { $query .= ' and debit=1'; }
          else { $query .= ' and debit=0'; }
          $query_prm = array($bankaccount_accountingnumberidA[$bankaccountid],$main_result[$i]['statementdate']
          ,$main_result[$i]['statementdate'],$main_result[$i]['statementdate'],d_abs($main_result[$i]['amount']));
          require('inc/doquery.php');
        }# TODO try again within 5 days
        if ($num_results == 1)
        {
          $temp_adjustmentid = $query_result[0]['adjustmentid'];
          $temp_adjustmentgroupid = $query_result[0]['adjustmentgroupid'];
          $query = 'select bankstatementid from bankstatement where adjustmentid=?';
          $query_prm = array($temp_adjustmentid);
          require('inc/doquery.php');
          if ($num_results == 0)
          {
            $main_result[$i]['adjustmentid'] = $temp_adjustmentid;
            $main_result[$i]['adjustmentgroupid'] = $temp_adjustmentgroupid;
            $query = 'update bankstatement set adjustmentid=?,adjustmentgroupid=?
            where bankstatementid=?';
            $query_prm = array($main_result[$i]['adjustmentid'],$main_result[$i]['adjustmentgroupid']
            ,$main_result[$i]['bankstatementid']);
            require('inc/doquery.php');
          }
        }
      }
    }
  }
  
  if ($num_results_main)
  {
    echo '<form method="post" action="accounting.php">';
    echo d_table("report");
    echo '<thead><th colspan=2>Écriture<th>Date<th colspan=2>Montant<th>Libellé</thead>';
    for ($i=0; $i<$num_results_main; $i++)
    {
      echo d_tr();
      if ($main_result[$i]['adjustmentgroupid'] > 0)
      {
        echo d_td($main_result[$i]['adjustmentgroupid'],'int');
        echo d_td_unfiltered('<a href="accounting.php?accountingmenu='.$accountingmenu
        .'&startdate='.$startdate.'&stopdate='.$stopdate
        .'&bankaccountid='.$bankaccountid.'&undo_id='.$main_result[$i]['bankstatementid'].'">&nbsp;X&nbsp;</a>');
      }
      else
      {
        echo d_td_unfiltered('<input type=number name="agid'.$main_result[$i]['bankstatementid'].'" style="text-align: right">');
        echo d_td();
      }
      echo d_td($main_result[$i]['statementdate'],'date');
      if ($main_result[$i]['amount'] >= 0) { echo d_td($main_result[$i]['amount'],'currency'),d_td(); }
      else
      {
        if ($main_result[$i]['adjustmentgroupid'] == 0)
        {
          echo d_td_unfiltered('&nbsp; <a href="reportwindow.php?report=insert_from_bankstatement&bankstatementid='
          .$main_result[$i]['bankstatementid'].'&anid='.$anid.'" target=_blank>INSÉRER</a> &nbsp;');
        }
        else { echo d_td(); }
        echo d_td($main_result[$i]['amount'],'currency');
      }
      echo d_td($main_result[$i]['statementtext']);
    }
    echo '<input type=hidden name="bankaccountid" value="' . $bankaccountid . '">
    <input type=hidden name="startdate" value="' . $startdate . '">
    <input type=hidden name="stopdate" value="' . $stopdate . '">
    <input type=hidden name="accountingmenu" value="' . $accountingmenu . '">
    <tr><td colspan=10 align=center><input type="submit" value="Valider">';
    echo d_table_end();
    echo '</form>';
  }
}
else { $showmenu = 1; }

if ($showmenu)
{
  echo '<h2>Relevés bancaires</h2>
  <form enctype="multipart/form-data" method="post" action="accounting.php"><table>';
  echo '<tr><td>Début : <td>'; $datename = 'startdate'; $selecteddate = substr($_SESSION['ds_curdate'],0,4).'-01-01';
  require('inc/datepicker.php');
  echo '<tr><td>Fin : <td>'; $datename = 'stopdate'; $selecteddate = substr($_SESSION['ds_curdate'],0,4).'-12-31';
  require('inc/datepicker.php');
  echo '<tr><td>Compte : '; $dp_itemname = 'bankaccount'; $dp_noblank = 1; require('inc/selectitem.php');
  echo '<tr><td>Import : <td><input type="file" name="userfile" size=50><input type="hidden" name="MAX_FILE_SIZE" value="1000000">
  <tr><td align=right><input type=checkbox name="autodetect" value=1 checked><td>Déterminer automatiquement les écritures
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="' . $accountingmenu . '">
  <input type="submit" value="Valider">
  </table></form><font size=+2>
  <br>
  <br><b>Format à télécharger :</b>
  <br>BP : .csv
  <br>BT : CSV_FR';
}

?>