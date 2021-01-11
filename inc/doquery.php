<?php

# mandatory input: $query $query_prm[]
# output:
unset($query_result);    # result array
unset($num_results);     # number of rows
unset($query_insert_id); # insert id

# example:
/*
$query = 'select name from usertable where username=?';
$query_prm = array('dauphin');
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  echo $i . ' ' . $main_result[$i]['name'] . '<br>';
}
*/

try
{
  if (!isset($dbh_doquery))
  {
    if (isset($dauphin_port) && $dauphin_port > 0) { $connectstring_doquery = 'mysql:host=' . $dauphin_hostname . ';port=' . $dauphin_port . ';dbname=' . $dauphin_instancename . ';charset=utf8mb4'; }
    else { $connectstring_doquery = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename . ';charset=utf8mb4'; }
    $setstring_doquery = 'SET NAMES utf8mb4, time_zone = "' . $dauphin_timezone . '", sql_mode = ""'; # can take off SET NAMES when all php versions >= 5.3.6
    $paramA_doquery = array(PDO::MYSQL_ATTR_INIT_COMMAND => $setstring_doquery, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $dbh_doquery = new PDO($connectstring_doquery, $dauphin_login, $dauphin_password, $paramA_doquery);
  }
  $sth_doquery = $dbh_doquery->prepare($query);
  $sth_doquery->execute($query_prm);
  $querytype_doquery  = mb_strtolower(substr($query,0,6));
  if ($querytype_doquery  == 'select') { $query_result = $sth_doquery->fetchAll(PDO::FETCH_ASSOC); }
  if ($querytype_doquery  == 'insert' || $querytype_doquery  == 'update' || $querytype_doquery  == 'replac') { $query_insert_id = $dbh_doquery->lastInsertId(); }
  $num_results = $sth_doquery->rowCount();
}
catch(PDOException $e_temp)
{
  if (!empty($query_prm)) # TODO important, this is copied 3 times, optimize
  {
    $indexed_temp = $query_prm == array_values($query_prm);
    foreach($query_prm as $k=>$v)
    {
      if (is_string($v)) $v="'$v'";
      elseif ($v === null) $v='NULL';
      elseif (is_array($v)) $v = implode(',', $v);
      if ($indexed_temp) { $query = preg_replace('/\?/', $v, $query, 1); }
      else { $query = str_replace(":$k",$v,$query); }
    }
  }
  $sqlerrormessage_temp = $e_temp->getMessage();
  if (isset($_SESSION['ds_showsqldebug']) && $_SESSION['ds_showsqldebug'] == 1)
  {
    echo '<p class=alert>Problème de base de données:<br>' . $sqlerrormessage_temp . '<br>' . d_output($query) . '</p>';
  }
  else
  {
    echo '<p class=alert>Problème de base de données.</p>';
  }
  if (isset($dbh_doquery))
  {
    $query_temp = 'insert into log_sqlerror (logdate, logtime, userid, querystring, errorstring) values (curdate(), curtime(), ?, ?, ?)';
    $query_prm_temp = array($_SESSION['ds_userid']+0, $query, $sqlerrormessage_temp);
    $sth_doquery = $dbh_doquery->prepare($query_temp);
    $sth_doquery->execute($query_prm_temp);
  }
  else { $_SESSION['ds_showsqldebug'] = 1; }
  if (!isset($_SESSION['last_sqlerror_time']) || time() > ($_SESSION['last_sqlerror_time']+60))
  {
    if (d_sendemail('svein.tjonndal@gmail.com','svein.tjonndal@gmail.com',
    'SQL ERROR : '.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'],$sqlerrormessage_temp.'<br>'.$query))
    { echo '<p class=alert>Un e-mail a été envoyé au service technique.</p>'; }
    else { echo '<p class=alert>Veuillez contacter le service technique.</p>'; }
    $_SESSION['last_sqlerror_time'] = time();
  }
}
if (isset($_SESSION['ds_showsqldebug']) && $_SESSION['ds_showsqldebug'] == 1)
{
  if (!empty($query_prm))
  {
    $indexed_temp = $query_prm == array_values($query_prm);
    foreach($query_prm as $k=>$v)
    {
      if (is_string($v)) $v="'$v'";
      elseif ($v === null) $v='NULL';
      elseif (is_array($v)) $v = implode(',', $v);
      if ($indexed_temp) { $query = preg_replace('/\?/', $v, $query, 1); }
      else { $query = str_replace(":$k",$v,$query); }
    }
  }
  echo '<p>' . $dauphin_instancename . ': (' . $num_results . ') ' . d_output($query) . '</p>';
  if ($querytype_doquery  == 'insert' || $querytype_doquery  == 'update' || $querytype_doquery  == 'replac') { echo 'query_insert_id=' . $query_insert_id; }
  #echo '<p>' . var_dump($query_result) . '</p>';
}
if ($querytype_doquery  == 'insert' || $querytype_doquery  == 'update')
{
  if (!d_strcontains(substr($query,0,50), $_SESSION['ds_exludequerylog'])) # checking 50 first chars
  {
    if (!empty($query_prm))
    {
      $indexed_temp = $query_prm == array_values($query_prm);
      foreach($query_prm as $k=>$v)
      {
        if (is_string($v)) $v="'$v'";
        elseif ($v === null) $v='NULL';
        elseif (is_array($v)) $v = implode(',', $v);
        if ($indexed_temp) { $query = preg_replace('/\?/', $v, $query, 1); }
        else { $query = str_replace(":$k",$v,$query); }
      }
    }
    #echo '<p>' . d_output($query) . '</p>';
    $query_temp = 'insert into log_query (logdate, logtime, userid, querystring) values (curdate(), curtime(), ?, ?)';
    $query_prm_temp = array($_SESSION['ds_userid']+0, $query);
    $sth_doquery = $dbh_doquery->prepare($query_temp);
    $sth_doquery->execute($query_prm_temp);

  }
}
#$sth_doquery = null; DO NOT do this
# TODO unset temp variables
?>