<?php

### keep this
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
require ('inc/top.php');
set_time_limit(600);
###

echo 'disabled'; exit;

# to exclude certain databases, see below
########################################################### paste MySQL update here ############################################
$mainquery = <<<ENDSQL

insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_1', 'Jan', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_2', 'Fév', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_3', 'Mars', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_4', 'Avr', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_5', 'Mai', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_6', 'Juin', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_7', 'Juil', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_8', 'Août', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_9', 'Sept', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_10', 'Oct', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_11', 'Nov', 1);
insert into trad (lang, string, tradstring, important) values ('fr', 'month_short_12', 'Déc', 1);

ENDSQL;
################################################################################################################################

echo '<h2>Updating all databases</h2>';

$num_db = 0; $dbA = array();
if ($_GET['key'] == 'dauphin')
{
  $query = 'SHOW DATABASES';
  $query_prm = array();
  require('inc/doquery.php');
  while( ( $db = $sth_doquery->fetchColumn( 0 ) ) !== false )
  {
    $ok = 0;
    if ($db != 'information_schema' && $db != 'performance_schema' && $db != 'mysql') { $ok = 1; }
    
    ### exclusion list
    /*
    if ($db == 'solcag_15') { $ok = 0; }
    if ($db == 'solcag_16') { $ok = 0; }
    if ($db == 'solcag_19') { $ok = 0; }
    */
    ###
    
    if ($ok == 1)
    {
      $num_db++;
      $dbA[$num_db] = $db;
    }
  }

  for ($i=1; $i <= $num_db; $i++)
  {
    echo '<br><b>' . $dbA[$i] .'</b><br>';
    $dauphin_instancename = $dbA[$i];
    unset($dbh_doquery);
    $query = $mainquery;
    $query_prm = array();
    require('inc/doquery.php');
  }

}
else { echo 'missing key'; }

?>