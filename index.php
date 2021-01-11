<?php

require('inc/password.php'); # TODO remove once Wing Chong Server at PHP5.5+

require('inc/standard.php');

if(isset($_POST['instancecounter']))
{
  $instancecounter = $_POST['instancecounter']+0;
  setcookie('instancecounter', $instancecounter, time() + 60*60*24*30, '/');
}

if (isset($_POST['username']) && isset($enterprisename))
{
  $instancecounter = $_POST['instancecounter']+0;
  $dauphin_instancename = $enterpriseinstance[$instancecounter];
  $ourenterprisename = $enterprisename[$instancecounter];

  if ($instancecounter > 0 && $dauphin_instancename != '')
  {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = 'select userid,shadow,salt,password,password_hash,access_clientid from usertable where deleted=0 and username=?';
    $query_prm = array($username);
    unset ($dbh_doquery); # IMPORTANT for shared servers
    require ('inc/doquery.php');
    if ($num_results > 0 && $username != "")
    {
      $userid = (int) $query_result[0]['userid'];
      $password_hash = $query_result[0]['password_hash'];
      $shadow = $query_result[0]['shadow']; # needed for backwards compat
      $salt = $query_result[0]['salt']; # needed for backwards compat
      $access_clientid = $query_result[0]['access_clientid'];
  
      # check allowed times
      $timeok = 1;
      $query = 'select curdate() as curdate,expiredate,attempts,maxattempts,curtime() as curtime,date_format(curdate(),"%w") as weekday,
      monstart,monstop,tuestart,tuestop,wedstart,wedstop,thustart,thustop,fristart,fristop,satstart,satstop,sunstart,sunstop
      from usertable where deleted=0 and username=?';
      $query_prm = array($username);
      require ('inc/doquery.php');
      $expiredate = $query_result[0]['expiredate'];
      if ($expiredate > "2000-01-01")
      {
        if ($expiredate < $query_result[0]['curdate']) { $timeok = 0; }
      }
      $attempts = $query_result[0]['attempts'];
      if ($attempts >= $query_result[0]['maxattempts']) { $timeok = 0; }
      $weekday = $query_result[0]['weekday'];
      $curtime = str_replace(':','',$query_result[0]['curtime'])+0;
      if ($weekday == 1) # Monday
      {
        $start = str_replace(':','',$query_result[0]['monstart'])+0;
        $stop = str_replace(':','',$query_result[0]['monstop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 2)
      {
        $start = str_replace(':','',$query_result[0]['tuestart'])+0;
        $stop = str_replace(':','',$query_result[0]['tuestop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 3)
      {
        $start = str_replace(':','',$query_result[0]['wedstart'])+0;
        $stop = str_replace(':','',$query_result[0]['wedstop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 4)
      {
        $start = str_replace(':','',$query_result[0]['thustart'])+0;
        $stop = str_replace(':','',$query_result[0]['thustop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 5)
      {
        $start = str_replace(':','',$query_result[0]['fristart'])+0;
        $stop = str_replace(':','',$query_result[0]['fristop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 6)
      {
        $start = str_replace(':','',$query_result[0]['satstart'])+0;
        $stop = str_replace(':','',$query_result[0]['satstop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($weekday == 0) # Sunday
      {
        $start = str_replace(':','',$query_result[0]['sunstart'])+0;
        $stop = str_replace(':','',$query_result[0]['sunstop'])+0; if ($stop == 0) { $stop = 999999; }
        if ($start > $curtime || $stop < $curtime) { $timeok = 0; }
      }
      if ($timeok)
      {
        if (password_verify($password, $password_hash)
        || $password_hash == '' && hash('sha512',$password . $salt) == $shadow) # backwards compat
        {
          $query = 'update usertable set attempts=0 where userid=?';
          $query_prm = array($userid);
          require ('inc/doquery.php');
          $query = 'insert into logtable (logtype,userid,logdate,logtime,loginfo) values (1,"' . $userid . '",CURDATE(),CURTIME(),"' . $_SERVER['REMOTE_ADDR'] . '")';
          $query_prm = array();
          require ('inc/doquery.php');
          $_SESSION['ds_username'] = $username;
          $_SESSION['ds_userid'] = $userid;
          if ($access_clientid == 0)
          {
            $_SESSION['ds_clientaccess'] = 0;
          }
          else
          {
            # 2019 11 29 NEW clientaccess
            $_SESSION['ds_clientaccess'] = 1;
            $_SESSION['ds_clientaccess_clientid'] = $access_clientid;
            $query = 'select clientname,issupplier from client where clientid=?';
            $query_prm = array($access_clientid);
            require('inc/doquery.php');
            $_SESSION['ds_clientname'] = $query_result[0]['clientname'];
            $_SESSION['ds_issupplier'] = $query_result[0]['issupplier'];
          }
          require ('inc/setaccess.php');
        }
      }
    }
    if (!isset($_SESSION['ds_userid']))
    {
      # login failed
      if (isset($attempts)) { $attempts++; }
      else { $attempts = 1; }
      $query = 'update usertable set attempts=? where username=?';
      $query_prm = array($attempts,$username);
      require ('inc/doquery.php');
      $query = 'insert into logtable (logtype,userid,logdate,logtime,loginfo) values (0,0,CURDATE(),CURTIME(),?)';
      $query_prm = array($_SERVER['REMOTE_ADDR'] . ' ' . $username);
      require ('inc/doquery.php');
    }
    unset ($_POST['username']);
  }
}

if (isset($_SESSION['ds_userid']) && $_SESSION['ds_clientaccess'] == 0)
{
  require ('inc/top.php');
  $dauphin_currentmenu = '';
  if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
  require ('inc/menu.php');
  require ('inc/loggedin.php');
  require ('inc/bottom.php');
}
else
{
  require('inc/frontpage.php');
}

if (isset($_SESSION['ds_userid']) && $_SESSION['ds_clientaccess'] == 1)
{
  echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=clientaccess.php">';
}

?>