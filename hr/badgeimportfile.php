<?php

ini_set('auto_detect_line_endings',true);
ini_set('max_execution_time', 300);

/*

examples extract of file created by BioStar:

Date,Device ID,devicename,Event,T&A Event,User ID,User,Status
2015-07-16 16:23:55,546309667,546309667[192.168.110.101],Tamper Switch On,,,,
2015-07-16 16:43:46,546309667,546309667[192.168.110.101],Verify Fail(Unregistered Card),,229617292,,
2015-07-16 16:43:49,546309667,546309667[192.168.110.101],Verify Success(Card Only),,1,Charles,
2015-07-16 16:44:39,546309667,546309667[192.168.110.101],Verify Success(Card Only),,2,Svein,
2015-07-16 16:44:59,546309667,546309667[192.168.110.101],Verify Success(Card Only),,1,Charles,
2015-07-22 18:57:37,546309667,546309667[192.168.110.101],System Reset,,,,

Date,Device ID,Device,Event,T&A Event,User ID,User,Status
2015-11-05 16:14:26,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:14:29,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:14:32,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:14:38,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:14:44,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:14:48,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:31:26,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-05 16:31:33,546309667,,Admin Menu Entered,,1,Wong Charles,
2015-11-05 16:32:25,546309667,,Socket Connected,,0,,
2015-11-05 16:32:25,546309667,,Socket Disconnected,,0,,
2015-11-05 16:32:26,546309667,,Socket Connected,,0,,
2015-11-05 16:32:27,546309667,,Socket Disconnected,,0,,
2015-11-06 11:18:12,546309649,Entrée Thierry,Time Change,,0,,
2015-11-06 11:18:42,546309649,Entrée Thierry,Time Change,,0,,
2015-11-06 11:18:46,546309665,Entrée Monique,Time Change,,0,,
2015-11-06 11:18:47,546309665,Entrée Monique,Time Change,,0,,
2015-11-06 11:18:49,546309677,Entrée Vaimato,Time Change,,0,,
2015-11-06 11:18:51,546309677,Entrée Vaimato,Time Change,,0,,
2015-11-06 11:26:32,546309649,Entrée Thierry,Verify Success(Card Only),,1,Wong Charles,
2015-11-06 11:26:35,546309649,Entrée Thierry,Verify Success(Card Only),,1,Wong Charles,
2015-11-06 11:26:40,546309649,Entrée Thierry,Admin Menu Entered,,1,Wong Charles,
2015-11-06 14:48:36,546309667,,Socket Connected,,0,,
2015-11-06 14:48:37,546309667,,Socket Disconnected,,0,,
2015-11-06 14:49:04,546309667,,Socket Connected,,0,,
2015-11-06 14:49:08,546309667,,Time Change,,0,,
2015-11-06 14:49:25,546309667,,Time Change,,0,,
2015-11-06 14:56:06,546309667,,Socket Disconnected,,0,,
2015-11-06 15:43:34,546309667,,System Reset,,,,
2015-11-06 15:43:35,546309667,,Tamper Switch Off,,,,
2015-11-06 16:14:55,546309667,,Socket Connected,,0,,
2015-11-06 16:14:56,546309667,,Socket Disconnected,,0,,
2015-11-06 16:17:14,546309667,,Admin Menu Entered,,1,Wong Charles,
2015-11-06 16:18:22,546309667,,Verify Success(Card Only),,1,Wong Charles,
2015-11-06 16:18:44,546309667,,Admin Menu Entered,,1,Wong Charles,
2015-11-06 16:23:56,546309667,,Socket Connected,,0,,
2015-11-06 16:23:56,546309667,,Socket Disconnected,,0,,
2015-11-06 16:23:58,546309667,,Socket Connected,,0,,
2015-11-06 16:23:58,546309667,,Socket Disconnected,,0,,
2015-11-06 16:24:02,546309667,,Socket Connected,,0,,
2015-11-06 16:27:42,546309667,,Socket Disconnected,,0,,
2015-11-06 16:28:34,546309665,Entrée Monique,Socket Disconnected,,0,,
2015-11-06 16:33:26,546309667,,Socket Connected,,0,,
2015-11-08 11:00:07,546309665,Entrée Monique,Verify Success(Card Only),,1,Wong Charles,
2015-11-09 20:06:24,546309649,Entrée Thierry,Device Disconnected,,,,
2015-11-09 20:06:28,546309665,Entrée Monique,Device Disconnected,,,,

*/

# config
$separator = ','; if ($_POST['separator'] == ';') { $separator = ';'; }
$importme = $_POST['importme'] +0;
echo '<h2>Import file from Biostar</h2>';

if ($importme == 1)
{
 
  $tmp_name = $_FILES['userfile']['tmp_name'];
  if (!isset($tmp_name) || ($tmp_name == '')) 
  { 
    echo '<p class=alert>' . d_trad('pleasechooseafile') .'</p>';
    $importme = 0;
  }
  else
  {
    $lineA = file($_FILES['userfile']['tmp_name']);
    $i = 0; $x = 0;
    #echo '<table class=report>';
    foreach ($lineA as $line)
    {
      $i++;
      $line = explode($separator, $line);
      
      if ($i == 1)
      {
        $index = -1;
        $index_date = -1;
        $index_deviceid = -1;
        $index_device = -1;
        $index_userid = -1;
        $index_user = - 1;
        foreach ($line as $value)
        {
          $index++;
          #echo '<td>', $index, ' ', $value;
          if ($i == 1)
          {
            if ($value == 'Date') { $index_date = $index; }
            if ($value == 'Device ID') { $index_deviceid = $index; }
            if ($value == 'Device') { $index_device = $index; }
            if ($value == 'Event') { $index_event = $index; }
            if ($value == 'User ID') { $index_userid = $index; }
            if ($value == 'User') { $index_user = $index; }
          }
        }
      
        if ($index_date < 0 || $index_deviceid < 0 || $index_device < 0 || $index_event < 0 || $index_userid < 0 || $index_user < 0)
        {
          echo '<br>Champs manquante(s)';
          exit;
        }
      }
      else
      {
        $badgedate = mb_substr($line[$index_date],0,10);
        $badgetime = mb_substr($line[$index_date],11,8);
        $deviceid = $line[$index_deviceid];
        $devicename = $line[$index_device];
        $event = $line[$index_event];
        $badgeuserid = (int) $line[$index_userid];
        $badgeusername = $line[$index_user];

        if ($event == 'Verify Success(Card Only)')
        {
          $query = 'select badgelogid from badgelog where badgedate=? and badgetime=? and deviceid=? and devicename=? and event=? and badgeuserid=? and badgeusername=?';
          $query_prm = array($badgedate,$badgetime,$deviceid,$devicename,$event,$badgeuserid,$badgeusername);
          require('inc/doquery.php');
          if ($num_results == 0)
          {
            ### 2017 01 16 find employeeid
            $query = 'select employeeid from employee where badgenumber=?';
            $query_prm = array($badgeuserid);
            require('inc/doquery.php');
            $employeeid = (int) $query_result[0]['employeeid'];
            
            $query = 'insert into badgelog (badgedate, badgetime, deviceid, devicename, event, badgeuserid, badgeusername, employeeid) values (?,?,?,?,?,?,?,?)';
            $query_prm = array($badgedate,$badgetime,$deviceid,$devicename,$event,$badgeuserid,$badgeusername,$employeeid);
            require('inc/doquery.php');
            $x++;
          }
        }
      }
    }
    echo '<p>Fichier importé: ', $x, ' lignes.</p>';
  }
}

if ($importme == 0)
{
  ?>
  <form enctype="multipart/form-data" method="post" action="hr.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td>Separateur:</td><td><select name="separator"><option value=",">,</option><option value=";">;</option></select></td></tr>
  <tr><td colspan=2><span class="alert">Assurez vous que le codage est UTF-8</span></td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="importme" value="1"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" value="Import"></form></td></tr></table><?php
}

?>