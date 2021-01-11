<?php

# TODO remove and replace by doquery

if (!function_exists('dbconnect'))
{

# old connect function, still widely used  TODO remove
  function dbconnect()
  {
    global $dauphin_hostname;
    global $dauphin_login;
    global $dauphin_password;
    global $dauphin_instancename;
    $usethisinstance = $dauphin_instancename;
    if (isset($_SESSION['ds_dauphininstance'])) { $usethisinstance = $_SESSION['ds_dauphininstance']; }
    $db_conn = mysql_connect ($dauphin_hostname, $dauphin_login, $dauphin_password, TRUE);   # define database connection
    mysql_select_db ($usethisinstance);
    mysql_set_charset('utf8', $db_conn);
    return $db_conn;
  }


# no longer to be used, see doquery.php
  function querycheck($resulttest)
  {
    if (!$resulttest)
    {
      echo '<br><br><b>Erreur base de donné</b>: ' . mysql_error() . $query;
      exit;
    }
    
    ### logging
    global $query; # only $query is logged! all others like $queryX or $query123 need to be changed ASAP
    if ($query != '')
    {
      $querytype_doquery  = mb_strtolower(substr($query,0,6));
      
      if ($querytype_doquery  == 'insert' || $querytype_doquery  == 'update') # if logging queries
      {
        if (!d_strcontains(substr($query,0,50), $_SESSION['ds_exludequerylog'])) # checking 50 first chars
        {
          #echo '<p>' . d_output($query) . '</p>';
          ### copy from doquery
            global $dauphin_hostname; # TODO move these from standard and into separate file we can include (important!)
            global $dauphin_port;
            global $dauphin_instancename;
            global $dauphin_timezone;
            global $dauphin_login;
            global $dauphin_password;
            if (!isset($dbh_doquery))
            {
              if (isset($dauphin_port) && $dauphin_port > 0) { $connectstring_doquery = 'mysql:host=' . $dauphin_hostname . ';port=' . $dauphin_port . ';dbname=' . $dauphin_instancename; }
              else { $connectstring_doquery = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename; }
              $setstring_doquery = 'SET NAMES utf8mb4, time_zone = "' . $dauphin_timezone . '", sql_mode = ""'; # utf8
              $paramA_doquery = array(PDO::MYSQL_ATTR_INIT_COMMAND => $setstring_doquery, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
              $dbh_doquery = new PDO($connectstring_doquery, $dauphin_login, $dauphin_password, $paramA_doquery);
            }
            $query_temp = 'insert into log_query (logdate, logtime, userid, querystring) values (curdate(), curtime(), ?, ?)';
            $query_prm = array($_SESSION['ds_userid']+0, $query);
            $sth_doquery = $dbh_doquery->prepare($query_temp);
            $sth_doquery->execute($query_prm);
        }
      }
    }
    ###
  }
}