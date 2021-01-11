<?php
if (!isset($colorA)) { require('preload/color.php'); }
// $ds_ismanagervalidationplanning = $_SESSION['ds_ismanagervalidationplanning'];  

switch($currentstep)
{
  # form
  case 0:
  
    #determine rank
    $query = 'select rank from planningteamvalue where deleted=0 order by rank desc limit 1';
    $query_prm = array();
    require('inc/doquery.php');
    $rank = 1;
    if ($num_results > 0) { $rank = $query_result[0]['rank'] + 1;}
    echo '<h2>' . d_trad('addplanningteamvalue') . '</h2>'; 
    echo '<form method="post" action="hr.php"><table>';
    echo '<tr><td>' . d_trad('name:') . '</td><td><input type="text" name="name" size=20></td></tr>';    
    echo '<tr><td>' . d_trad('symbol:') . '</td><td><input type="text" name="symbol" size=20></td></tr>';
    echo '<tr><td>' . d_trad('absence:') . '</td><td><input type="checkbox" name="absence" value="1" CHECKED></td></tr>';
    echo '<tr><td>' . d_trad('rest:') . '</td><td><input type="checkbox" name="rest" value="1"></td></tr>';
    echo '<tr><td>' . d_trad('rank:') . '</td><td><input type="number" name="rank" value=' . $rank . ' size=2></td></tr>';
    echo '<tr><td>' . d_trad('color:') . '</td><td><select name="colorid">';
    echo '<option value="-1" style="BACKGROUND-COLOR: #000000; color: #ffffff">&nbsp;</option>';
    foreach ($colorA as $colorid => $colorname)
    {
      $showcc = "#" . $color_codeA[$colorid]; if ($showcc == "#000000") { $showcc = "#ffffff"; }
      echo '<option value="' . $colorid. '" style="BACKGROUND-COLOR: #000000; color: ' . $showcc . '">' . d_output($colorname). '</option>';
    }

    echo '</select></td></tr>';
    // if ($ds_ismanagervalidationplanning)
    // {
      echo '<tr><td>' . d_trad('validatedcolor:') . '</td><td><select name="colorid1">';
      echo '<option value="-1" style="BACKGROUND-COLOR: #000000; color: #ffffff">&nbsp;</option>';
      foreach ($colorA as $colorid => $colorname)
      {
        $showcc = "#" . $color_codeA[$colorid]; if ($showcc == "#000000") { $showcc = "#ffffff"; }
        echo '<option value="' . $colorid. '" style="BACKGROUND-COLOR: #000000; color: ' . $showcc . '">' . d_output($colorname). '</option>';
      }

      echo '</select></td></tr>';
      echo '<tr><td>' . d_trad('refusedcolor:') . '</td><td><select name="colorid2">';
      echo '<option value="-1" style="BACKGROUND-COLOR: #000000; color: #ffffff">&nbsp;</option>';
      foreach ($colorA as $colorid => $colorname)
      {
        $showcc = "#" . $color_codeA[$colorid]; if ($showcc == "#000000") { $showcc = "#ffffff"; }
        echo '<option value="' . $colorid. '" style="BACKGROUND-COLOR: #000000; color: ' . $showcc . '">' . d_output($colorname). '</option>';
      }
    // }

    echo '</select></td></tr>';    
    echo '<tr><td colspan="2" align="center">';
    echo '<input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="' . $hrmenu . '">';
    echo '<input type="submit" value="' . d_trad('validate') . '"></td></tr>';
    echo '</table></form>';

    break;

  # action
  case 1:
    $name = d_input($_POST['name']);  
    $symbol = d_input($_POST['symbol']);
    $absence = $_POST['absence'];if ($absence == "") { $absence = 0; }
    $rest = $_POST['rest'];if ($rest == "") { $rest = 0; }
    $rank = $_POST['rank'] +0;
    $colorid = $_POST['colorid'];
    $colorid1 = $_POST['colorid1'];
    $colorid2 = $_POST['colorid2'];

    if($name == '')
    {
      echo '<p class="alert">' . d_trad('planningteamvaluenamemustnotbeempty') . '</p>'; exit;
    }
    else
    {
      #check if symbol name exists
      $query = 'select * from planningteamvalue where planningteamvaluename=? and deleted=0';
      $query_prm = array($name);
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        echo '<p class="alert">' . d_trad('planningteamvaluenamealreadyexists',$name) . '<p>';
      }
      else
      {
        $query = 'insert into planningteamvalue (planningteamvaluesymbol,planningteamvaluename,absence,rest,colorid,colorid1,colorid2,rank) values (?,?,?,?,?,?,?,?)';
        $query_prm = array($symbol,$name,$absence,$rest,$colorid,$colorid1,$colorid2,$rank);   
        require('inc/doquery.php');
        if($num_results > 0)
        {
          echo '<p>' . d_trad('planningteamvalueadded',$symbol) .'</p>';
        }
      }
    }
    
    break;
}
?>