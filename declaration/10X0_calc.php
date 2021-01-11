<?php

if ($explain)
{
  require('preload/vatindex.php');
}

$f = array();
$query = 'select vatindexid from vatindex order by vatindexid';
$query_prm = array();
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $index = $query_result[$i]['vatindexid'];
  $f[$index] = 0;
}

$query = 'select value,debit,acnumber,vatindexid,vatnegative,adjustment.adjustmentgroupid,adjustmentdate,adjustmentcomment,reference
from adjustment,accountingnumber,adjustmentgroup
where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=? and vatindexid>0 and value>0';
if ($_SESSION['ds_tva_encaissement'] > 0) { $query .= ' and vatindexid<>6'; }
$query .= '
order by vatindexid,acnumber,adjustment.adjustmentgroupid';
$query_prm = array($startdate, $stopdate);
require('inc/doquery.php');
if ($explain)
{
  echo '<h2>Débit - selon date comptable</h2>';
  if (!empty($query_prm))
  {
    $query_show = $query;
    $indexed_temp = $query_prm == array_values($query_prm);
    foreach($query_prm as $k=>$v)
    {
      if (is_string($v)) $v="'$v'";
      elseif ($v === null) $v='NULL';
      elseif (is_array($v)) $v = implode(',', $v);
      if ($indexed_temp) { $query_show = preg_replace('/\?/', $v, $query_show, 1); }
      else { $query_show = str_replace(":$k",$v,$query_show); }
    }
  }
  echo '<p style="font-size: medium">'.d_output($query_show).'</p>';
  echo '<table border=1 cellspacing=2 cellpadding=2>
  <thead><th>Écriture<th>Date<th>Compte<th>Index TVA<th>Libellé<th>Réference<th>Débit<th>Crédit</thead>';
}
for ($i = 0; $i < $num_results; $i++)
{
  if ($explain)
  {
    echo d_tr(),d_td($query_result[$i]['adjustmentgroupid'])
    ,d_td($query_result[$i]['adjustmentdate'],'date')
    ,d_td($query_result[$i]['acnumber'])
    ,d_td($vatindexA[$query_result[$i]['vatindexid']])
    ,d_td($query_result[$i]['adjustmentcomment'])
    ,d_td($query_result[$i]['reference']);
    if ($query_result[$i]['debit'] == 1)
    {
      echo d_td($query_result[$i]['value'],'currency'),d_td();
    }
    else
    {
      echo d_td(),d_td($query_result[$i]['value'],'currency');
    }
  }
  $index = $query_result[$i]['vatindexid'];
  if ($query_result[$i]['debit'] == 1)
  {
    if ($query_result[$i]['vatnegative'] == 1)
    {
      $f[$index] = d_add($f[$index], $query_result[$i]['value']);
    }
    else
    {
      $f[$index] = d_subtract($f[$index], $query_result[$i]['value']);
    }
  }
  else
  {
    if ($query_result[$i]['vatnegative'] == 1)
    {
      $f[$index] = d_subtract($f[$index], $query_result[$i]['value']);
    }
    else
    {
      $f[$index] = d_add($f[$index], $query_result[$i]['value']);
    }
  }
}
if ($explain) { echo d_table_end(); }

$f[1] = d_add($f[5], $f[7]);
$f[2] = d_add($f[6], $f[105]);
$f[5] = d_add($f[5], $f[105]);

if ($_SESSION['ds_tva_encaissement'] > 0)
{
  # 2020 09 17 using matchingdate, users MUST lettrer dans la periode
  $f[2] = 0;

  $query = 'select value,debit,acnumber,vatindexid,vatnegative,adjustment.adjustmentgroupid,adjustmentdate,date,adjustmentcomment,reference
  from adjustment,accountingnumber,adjustmentgroup,matching
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.matchingid=matching.matchingid
  and adjustmentgroup.deleted=0 and date>=? and date<=? and vatindexid=6 and value>0
  order by vatindexid,acnumber,adjustment.adjustmentgroupid';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  if ($explain)
  {
    echo '<h2>Encaissements - selon date lettrage</h2>';
    if (!empty($query_prm))
    {
      $query_show = $query;
      $indexed_temp = $query_prm == array_values($query_prm);
      foreach($query_prm as $k=>$v)
      {
        if (is_string($v)) $v="'$v'";
        elseif ($v === null) $v='NULL';
        elseif (is_array($v)) $v = implode(',', $v);
        if ($indexed_temp) { $query_show = preg_replace('/\?/', $v, $query_show, 1); }
        else { $query_show = str_replace(":$k",$v,$query_show); }
      }
    }
    echo '<p style="font-size: medium">'.d_output($query_show).'</p>';
    echo '<table border=1 cellspacing=2 cellpadding=2>
    <thead><th>Écriture<th>Date<th>Date lettré<th>Compte<th>Libellé<th>Réference<th>Débit<th>Crédit</thead>';
  }
  for ($i = 0; $i < $num_results; $i++)
  {
    if ($explain)
    {
      echo d_tr(),d_td($query_result[$i]['adjustmentgroupid'])
      ,d_td($query_result[$i]['adjustmentdate'],'date')
      ,d_td($query_result[$i]['date'],'date')
      ,d_td($query_result[$i]['acnumber'])
      ,d_td($query_result[$i]['adjustmentcomment'])
      ,d_td($query_result[$i]['reference']);
    }
    if ($query_result[$i]['debit'] == 1)
    {
      if ($explain) { echo d_td($query_result[$i]['value'],'currency'),d_td(); }
      $f[2] = d_subtract($f[2],$query_result[$i]['value']);
    }
    else
    {
      if ($explain) { echo d_td(),d_td($query_result[$i]['value'],'currency'); }
      $f[2] = d_add($f[2],$query_result[$i]['value']);
    }
  }
  if ($explain) { echo d_table_end(); }

  $f[6] = $f[2];
}
?>