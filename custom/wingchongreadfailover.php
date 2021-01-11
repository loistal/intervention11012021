<?php

# do NOT upload this file to the master installation
# this file should be in 'custom' folder
# this script is meant to be executed on the command line or as a cronjob
# its sole argument is the table to be downloaded
# careful this may fail for larger tables (invoices, payments)
# IMPORTANT clocks must be synced

set_time_limit(600); # for040hosting

$_SESSION['debug'] = 1; # log actual SQL errors

$url = 'https://www.tem-wico2.com/custom_available/wingchongtofailover.php';

#$_SESSION['ds_nonstrictsql'] = 1; # allow all insertions 2013 04 08 parameter removed
chdir(__DIR__);
chdir('..');
require('inc/standard.php');
$eol = "§#!";
#$eol = '<br>'; # testing

#$query = 'select timestamp(now()) as newtimestamp,lastreadmasterts from lastreadmaster where lastreadmasterid=1';
# substracting one minute in case clocks are out of sync
$query = 'select timestamp(SUBTIME(now(),"0 0:1:0")) as newtimestamp,lastreadmasterts from lastreadmaster where lastreadmasterid=1';
$query_prm = array();
require('inc/doquery.php');
$timestamp = $query_result[0]['lastreadmasterts'];
$newtimestamp = $query_result[0]['newtimestamp'];
#echo $newtimestamp;

$postdata = http_build_query(
  array(
    'key' => '4j1rTjR9jkKXRu2G9GsV',
    'timestamp' => $timestamp
  )
);
$opts = array('http' =>
  array(
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => $postdata
  )
);
$queries = file_get_contents($url, false, stream_context_create($opts));
#echo 'raw result:<br>'.$queries.'<br><br>';

$queryA = explode($eol, $queries);
$num_queries = count($queryA) - 1;
#if ($queryA[$num_queries] != 'CONFIRMEDENDOFILE') { echo $newtimestamp . ': FAILED to update\n'; exit; }
if ($queryA[$num_queries] != 'CONFIRMEDENDOFILE') { echo $queries . '\n\n'; exit; }
$query_prm = array();

for ($i=0;$i<$num_queries;$i++)
{
  #echo $queryA[$i];
  #echo "\n";
  $query = $queryA[$i];
  require('inc/doquery.php');
}

$query = 'update lastreadmaster set lastreadmasterts=? where lastreadmasterid=1';
$query_prm = array($newtimestamp);
require('inc/doquery.php');


#echo 'Failover updated ' . $newtimestamp . ' (' . $num_queries . ' queries)';
#echo "\n";





### for php <5.3
/*
if (!function_exists('str_getcsv')) { 
    function str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\") { 
        $fiveMBs = 5 * 1024 * 1024; 
        $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+'); 
        fputs($fp, $input); 
        rewind($fp); 

        $data = fgetcsv($fp, 1000, $delimiter, $enclosure); //  $escape only got added in 5.3.0 

        fclose($fp); 
        return $data;
    } 
}
*/
###
/*  STARTING OVER
#$tablename = $argv[1];
$tablename = $_GET['tablename']; #for testing
if (!ctype_alnum($tablename)) { echo 'Non Alpha'; exit; }

$sep = "#!NC!#";
$eol = "\r\n";

if ($debug) { echo 'Starting...<br>'; }

# set these parameters to point to master
#$url = 'https://www.dauphin-wico.com/custom_available/wingchongtofailover.php?tablename=' . $tablename;
$url = 'http://localhost/custom_available/wingchongtofailover.php?tablename=' . $tablename;
$param = array
(  
  "key" => "4j1rTjR9jkKXRu2G9GsV"
);
chdir(__DIR__);
chdir('..');
require('inc/standard.php');

$f = fopen('php://temp', 'w+');
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
#curl_setopt($curl, CURLOPT_FILE, $f);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
$output = curl_exec($curl);
curl_close($curl);

/* OLD
rewind($f);
$i = -1;
while ($lineread = fgetcsv($f))
{
  if ($lineread[0] != '')
  {
    $i++;
    $line[$i] = $lineread[0];
  }
}
$num_lines = $i;
fclose($f);
*/
/*
### new
$i = -1;
$lineread = explode($eol, $output);
foreach ($lineread as $item)
{
  #echo $item .'<br>';
  $line[$i] = $item;
  $i++;
}
$num_lines = $i+1;

###

if ($debug) { echo 'num_lines='.$num_lines.'<br>'; }
if ($line[$num_lines] != 'CONFIRMEDENDOFILE') { exit; }
echo 'yup';exit;
$columnsnext = 0; $createnext = 0; $readyfordata = 0; $firstline = 0; $firstentry = 0; $query = '';
for ($i = 0; $i < $num_lines; $i++)
{
  if (substr($line[$i],0,3) == '###')
  {
    if ($query != '')
    {
      if ($debug) { echo '<br><br>x' . $query; var_dump($query_prm); }
      #require('inc/doquery.php');
    }
    $tablename = substr($line[$i],3);
    if (!ctype_alnum($tablename)) { echo 'non alpha'; exit; }
    $query = 'drop table if exists ' .  $tablename;
    $query_prm = array();
    #require('inc/doquery.php');
    $createnext = 1;
  }
  elseif ($createnext)
  {
    if ($debug) { echo $line[$i]; }
    $query = $line[$i];
    $query_prm = array();
    #require('inc/doquery.php');
    $createnext = 0;
    $columnsnext = 1;
  }
  elseif ($columnsnext)
  {
    $query_start = 'insert into ' . $tablename . ' (' . str_replace(';',',',$line[$i]) . ') values ';
    $query = $query_start;
    $query_prm = array();
    $columnsnext = 0;
    $readyfordata = 1;
    $firstentry = 1;
  }
  elseif ($readyfordata)
  {
    #$entry = str_getcsv($line[$i], ';');
    $entry = substr($line[$i], 0, 0-strlen($sep));
    if ($debug) { echo '<br><br>Data: ' . $line[$i]; }
    $firstline = 1;
    if ($firstentry)
    {
      $firstentry = 0;
    }
    else
    {
      if ($debug) { echo '<br><br>' . $query; var_dump($query_prm); }
      #require('inc/doquery.php');
      $query = $query_start;
      $query_prm = array();
    }
    $query .= '(';
    $entryA = explode($sep, $entry);
    foreach ($entryA as $item)
    {
      if ($firstline)
      {
        $firstline = 0;
      }
      else
      {
        $query .= ',';
      }
      $query .= '?';
      array_push($query_prm,$item);
    }
    $query .= ') ';
  }
}
if ($query != '')
{
  if ($debug) { echo '<br><br>' . $query; var_dump($query_prm); }
  #require('inc/doquery.php');
}
*/
?>