<?php

require('../temconfig.php');
if (isset($_SESSION['ds_dauphininstance'])) { $dauphin_instancename = $_SESSION['ds_dauphininstance']; }
require('func.php');

$query = 'select curtime() as curtime,curdate() as curdate,date_format(curdate(),"%w") as weekday';
$query_prm = array();
require ('inc/doquery.php');
$_SESSION['ds_curdate'] = $query_result[0]['curdate'];
$_SESSION['ds_curtime'] = $query_result[0]['curtime'];
$_SESSION['ds_weekday'] = $query_result[0]['weekday']; # Monday is 1, same as for planning
if ($_SESSION['ds_weekday'] == 0) { $_SESSION['ds_weekday'] = 7; }

if (isset($_SESSION['ds_checktimes']) && $_SESSION['ds_checktimes'] && isset($_SESSION['ds_userid']))
{
  $timeok = 1;
  $query = 'select monstart,monstop,tuestart,tuestop,wedstart,wedstop,thustart,thustop,fristart,fristop,satstart,satstop,sunstart,sunstop from usertable where userid=?';
  $query_prm = array($_SESSION['ds_userid']);
  require ('inc/doquery.php');
  $curtime = str_replace(':','',$_SESSION['ds_curtime'])+0;
  if ($_SESSION['ds_weekday'] == 1) # Monday
  {
    $start = str_replace(':','',$query_result[0]['monstart'])+0;
    $stop = str_replace(':','',$query_result[0]['monstop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 2)
  {
    $start = str_replace(':','',$query_result[0]['tuestart'])+0;
    $stop = str_replace(':','',$query_result[0]['tuestop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 3)
  {
    $start = str_replace(':','',$query_result[0]['wedstart'])+0;
    $stop = str_replace(':','',$query_result[0]['wedstop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 4)
  {
    $start = str_replace(':','',$query_result[0]['thustart'])+0;
    $stop = str_replace(':','',$query_result[0]['thustop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 5)
  {
    $start = str_replace(':','',$query_result[0]['fristart'])+0;
    $stop = str_replace(':','',$query_result[0]['fristop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 6)
  {
    $start = str_replace(':','',$query_result[0]['satstart'])+0;
    $stop = str_replace(':','',$query_result[0]['satstop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($_SESSION['ds_weekday'] == 7) # Sunday
  {
    $start = str_replace(':','',$query_result[0]['sunstart'])+0;
    $stop = str_replace(':','',$query_result[0]['sunstop'])+0; if ($stop == 0) { $stop = 999999; }
    if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
  }
  if ($timeok != 1) { require('logout.php'); exit; }
}

?>
