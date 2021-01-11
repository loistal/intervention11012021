<?php
require('preload/planningteamvalue.php');
if (!isset($colorA)) { require('preload/color.php');}

switch($currentstep)
{

  # form to choose wich plannningteamvalue
  case 0:
    echo '<h2>' . d_trad('modifyplanningteamvalue') . '</h2>'; ?>
    <form method="post" action="hr.php"><table>
    <tr>
      <td><?php echo d_trad('name:');?></td>
      <td><select name="planningteamvalueid">
        <?php
        foreach($planningteamvalueA as $planningteamvalueid => $planningteamvaluename)
        {
          echo '<option value="' . $planningteamvalueid . '">' . d_output($planningteamvaluename) . '</option>';
        }
        ?>
      </select></td>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
    <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
    </table></form><?php
    break;

  # form to modify plannningteamvalue
  case 1:
    $planningteamvalueid = $_POST['planningteamvalueid'];
    if (!isset($_POST['planningteamvalueid'])) { $planningteamvalueid = $_GET['planningteamvalueid']; }
    $query = 'select * from planningteamvalue where planningteamvalueid=?';
    if(!$ds_showdeleteditems) {  $query .= 'and deleted=0'; }      
    $query_prm = array($planningteamvalueid);      
    require('inc/doquery.php');
    $row = $query_result[0];     
    $symbol = $row['planningteamvaluesymbol']; 
    if ($symbol == NULL ){ $symbol = ''; }
    $name = $row['planningteamvaluename'];
    if ($name == NULL) { $name = ''; } 
    $abschecked = '';if ($row['absence']){ $abschecked = ' CHECKED'; }
    $restchecked = '';if ($row['rest']){ $restchecked = ' CHECKED'; }
    $paidleavechecked = '';if ($row['ispaidleave']){ $paidleavechecked = ' CHECKED'; }
    $bankholidaychecked = '';if ($row['isbankholiday']){ $bankholidaychecked = ' CHECKED'; }
    $trainingchecked = '';if ($row['istraining']){ $trainingchecked = ' CHECKED'; }
    if($num_results > 0)
    {
      echo '<h2>' . d_trad('modifyplanningteamvalue') . '</h2>'; ?>
      <form method="post" action="hr.php"><table>
      <tr><td><?php echo d_trad('symbol:');?></td><td><input type="text" name="symbol" size=20 value="<?php echo d_input($symbol); ?>"></td></tr>
      <tr><td><?php echo d_trad('name:');?></td><td><input type="text" name="name" size=20 value="<?php echo d_input($name); ?>"></td></tr>
      <tr><td><?php echo d_trad('color:');?></td><td><select name="colorid"><?php
        $selected = '';if($row['colorid'] == -1) { $selected = ' SELECTED'; }
        echo '<option value="-1" style="BACKGROUND-COLOR: #000000; color: #ffffff"' . $selected . '>&nbsp;</option>';      
        foreach ($colorA as $colorid => $colorname)
        {
          $showcc = "#" . $color_codeA[$colorid]; if ($showcc == "#000000") { $showcc = "#ffffff"; }
          $selected = '';if($row['colorid'] == $colorid) { $selected = ' SELECTED'; }
          echo '<option value="' . $colorid . '" style="BACKGROUND-COLOR: #000000; color: ' . $showcc . '"' . $selected . '>' . $colorname . '</option>';
        } ?>
      </select></td></tr> 
      <tr><td><?php echo d_trad('rank:');?></td><td><input type=number name="rank" size=2 value=<?php echo $row['rank'];?>></td></tr>         
      <tr><td><?php echo d_trad('absence:');?></td><td><input type=checkbox value=1 name="absence" <?php echo $abschecked;?>></td></tr>     
      <tr><td><?php echo d_trad('rest:');?></td><td><input type=checkbox value=1 name="rest" <?php echo $restchecked;?>></td></tr>     
      <tr><td><?php echo d_trad('ispaidleave:');?></td><td><input type=checkbox value=1 name="ispaidleave" <?php echo $paidleavechecked;?>></td></tr>  
      <tr><td><?php echo d_trad('isbankholiday:');?></td><td><input type=checkbox value=1 name="isbankholiday" <?php echo $bankholidaychecked;?>></td></tr>  
      <tr><td><?php echo d_trad('training:');?></td><td><input type=checkbox value=1 name="istraining" <?php echo $trainingchecked;?>></td></tr>  
      <tr><td><?php echo d_trad('deleted:');?></td><td><input type="checkbox" name="deleted" value="1" <?php if( $row['deleted'] ) {echo ' CHECKED';}?>></td></tr>
      <tr><td colspan="2" align="center">
      <input type=hidden name="step" value="2">
      <input type=hidden name="planningteamvalueid" value="<?php echo $row['planningteamvalueid']; ?>">      
      <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
      <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
      </table></form><?php
    }
    else
    {
      echo d_trad('noresult');
    }
    break;

  #update planningteamvalue
  case 2:
    $planningteamvalueid = $_POST['planningteamvalueid'];
    $planningteamvaluesymbol = $_POST['symbol'];
    $planningteamvaluename = $_POST['name'];
    $absence = $_POST['absence']+0;
    $rest = $_POST['rest']+0;
    $rank = $_POST['rank'];
    $colorid = $_POST['colorid'];
    $ispaidleave = $_POST['ispaidleave'] +0;
    $isbankholiday = $_POST['isbankholiday'] +0;    
    $istraining = $_POST['istraining'] +0;    
    $deleted = $_POST['deleted'] +0;
     #calculated value
    $presence = 0;
    if ($absence == 0 && $rest == 0 && $ispaidleave == 0 && $isbankholiday == 0) { $presence = 1;}

      
    $query_update = 'update planningteamvalue set planningteamvaluename=?,presence=?,absence=?,rest=?,rank=?,colorid=?,ispaidleave=?,isbankholiday=?,istraining=?,deleted=? '; 
    $query_update_prm = array($planningteamvaluename,$presence,$absence,$rest,$rank,$colorid,$ispaidleave,$isbankholiday,$istraining,$deleted);    
    if($planningteamvaluename == '')
    {
      echo '<p class="alert">' . d_trad('planningteamvaluenamemustnotbeempty') . '<p>';      
    }
    else
    {
      #check if planningteamvalue already exist with this symbol    
      $query = 'select * from planningteamvalue where planningteamvaluename=? and planningteamvalueid<>? and deleted=0';
      $query_prm = array($planningteamvaluename,$planningteamvalueid);
      require('inc/doquery.php');

      if ($num_results > 0)
      {
        echo '<p class="alert">' . d_trad('planningteamvaluenamealreadyexists',d_output($planningteamvaluename)) . '<p>';
      }
      else
      {
        $query_update .= ',planningteamvaluesymbol=?';
        array_push($query_update_prm,$planningteamvaluesymbol);
        $query_update .= ' where planningteamvalueid=?';
        array_push($query_update_prm,$planningteamvalueid);
        $query = $query_update;
        $query_prm = $query_update_prm;
        require('inc/doquery.php');
        echo '<p>' . d_trad('planningteamvaluemodified',$planningteamvaluename) . '</p><br>';    
      }        
    }
    require('hr/listplanningteamvalue.php');
    break;
}//switch

?>