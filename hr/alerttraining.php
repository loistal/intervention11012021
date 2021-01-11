<?php
require('inc/func_planning.php');

$ds_curdate = $_SESSION['ds_curdate'];
$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$curdate = new DateTime();

require('preload/employeecategory.php');

$MAX_LENGTH_DISPLAYED = 60;
$STEP_FORM_ADD = 2;
$STEP_CHOOSE_ADD = 3;

#select all mandatory trainingid
$query = 'select * from training where mandatory = 1 and deleted = 0 order by trainingname';
$query_prm = array();
require('inc/doquery.php');
$num_mandatorytrainings = $num_results;$mandatorytrainingA = $query_result;

$numalerttrainings = 0;$alertidA = array();$alertrefA = array();$alertnameA = array();$alertperiodicA = array();$alertcatempA = array();
$numalertplannings = 0;$alertplanningidA = array();$alertplanningrefA = array();$alertplanningnameA = array();$alertplanningperiodicA = array();
$alertplanningemployeeidA = array();$alertplanningemployeenameA = array();$alertplanningcatempA = array();

if ( $num_mandatorytrainings == 0)
{ 
  #title
  echo '<h2>' . d_trad('alerttraining') . '</h2>';
  echo '<p>' . d_trad('noresult') . '</p>';
}
else
{ 
  for ($t=0;$t<$num_mandatorytrainings;$t++)
  {
    $trainingid = $mandatorytrainingA[$t]['trainingid'];
    $trainingname = $mandatorytrainingA[$t]['trainingname'];
    $trainingref = $mandatorytrainingA[$t]['trainingref'];
    $trainingemployeecategoryid = $mandatorytrainingA[$t]['employeecategoryid'];
    $periodic = $mandatorytrainingA[$t]['periodic'];
    
    #d_debug('trainingname',$trainingname);
    #select employeeplanningtraining for this training
    $query = 'select * from trainingplanning where trainingid=? and deleted=0 and startdate>=? and stopdate<=?';
    $curdate->setDate($currentyear, $currentmonth, $currentday);    
    $startdate = d_getdatesubstmonths($periodic,$curdate);
    #d_debug('periodic',$periodic);
    #d_debug('startdate',$startdate);
    $curdate->setDate($currentyear, $currentmonth, $currentday);    
    $stopdate = d_getdateaddmonths($periodic,$curdate);
    #d_debug('stopdate',$stopdate);    
    $query_prm = array($trainingid,$startdate,$stopdate);
    require('inc/doquery.php');   
    $num_mandatorytrainingplannings = $num_results;$mandatorytrainingplanningA = $query_result;

    if ($num_mandatorytrainingplannings == 0)
    {
      #add it to list of alert
      array_push($alertidA,$trainingid);
      array_push($alertrefA,$trainingref);
      array_push($alertnameA,$trainingname);
      $employeecategoryname = '';
      #d_debug('trainingemployeecategoryid',$trainingemployeecategoryid);
      if ($trainingemployeecategoryid > 0)
      {
        $employeecategoryname = $employeecategoryA[$trainingemployeecategoryid];
      }        
      array_push($alertcatempA,$employeecategoryname);
      array_push($alertperiodicA,$periodic);
      $numalertrainings ++;
    #d_debug('numalertrainings',$numalertrainings);      
    }
    else
    {
      $query_in = ' in (';    
      for ($tp=0;$tp<$num_mandatorytrainingplannings;$tp++)
      {
        $query_in .= $mandatorytrainingplanningA[$tp]['trainingplanningid'];
        if ($tp < ($num_mandatorytrainingplannings -1))
        {
          $query_in .= ',';
        }
        else
        {
          $query_in .= ')';
        }
      }
    
      #select employees concerned by this formation
      $query = 'select * from employee where deleted=0';
      $query_prm = array();
      if ($trainingemployeecategoryid > 0)
      {
        $query .= ' and employeecategoryid=?';
        array_push($query_prm,$trainingemployeecategoryid);      
      }
      $query .= ' order by employeename';

      require('inc/doquery.php');
      $num_employees = $num_results;$alertemployeeA = $query_result;
      
      for ($e=0;$e<$num_employees;$e++)
      {
        $employeeid = $alertemployeeA[$e]['employeeid'];
        $employeename = $alertemployeeA[$e]['employeename'];
        #check if each employee has this training planned
        $query = 'select * from trainingemployeeplanning where deleted=0 and employeeid=? and trainingid=? and trainingplanningid' . $query_in;
        $query_prm =  array($employeeid,$trainingid);
        require('inc/doquery.php');
        $num_trainingemployeeplannings = $num_results;$trainingemployeeplanningA = $query_result;
        if ($num_trainingemployeeplannings == 0)
        {
          #add it to list of alertplanning
          array_push($alertplanningidA,$trainingid);
          array_push($alertplanningrefA,$trainingref);
          array_push($alertplanningnameA,$trainingname);
          array_push($alertplanningperiodicA,$periodic);        
          array_push($alertplanningemployeeidA,$employeeid);
          array_push($alertplanningemployeenameA,$employeename);
          $employeecategoryname = '';
          $categoryemployeeid = $alertemployeeA[$e]['employeecategoryid'];
          if ($categoryemployeeid > 0)
          {
            $employeecategoryname = $employeecategoryA[$categoryemployeeid];
          }        
          array_push($alertplanningcatempA,$employeecategoryname);
          $numalertplannings ++;
        }
      }
    }
  }

  if ($numalertrainings > 0)
  {
    echo '<h2>' . d_trad('mandtrainingsnotplanned') . '</h2>';    ?>
    <table class="report">
    <thead>
      <th><?php echo d_trad('reference'); ?></th>
      <th><?php echo d_trad('trainingname'); ?></th>     
      <th><?php echo d_trad('employeecategory'); ?></th>    
      <th><?php echo d_trad('periodic'); ?></th>        
    </thead><?php 


    for ($ap=0;$ap<$numalertrainings;$ap++)
    {
      $trainingid = $alertidA[$ap];
      $href = 'hr.php?hrmenu=trainingplanning&step=' . $STEP_FORM_ADD . '&trainingid=' . $trainingid;
      echo d_tr();
      $trainingrefdisplayed = $alertrefA[$ap];
      if ( strlen($trainingrefdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingrefdisplayed = substr($trainingrefdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingrefdisplayed. '</a></td>';   
      
      $trainingnamedisplayed = $alertnameA[$ap];
      if ( strlen($trainingnamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingnamedisplayed = substr($trainingnamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingnamedisplayed. '</a></td>';  
      
      echo '<td><a href="' . $href . '">' . $alertcatempA[$ap] . '</a></td>';
      $periodic = $alertperiodicA[$ap];;
      $periodicdisplayed = '';
      if ($periodic == 0)
      {
        $periodicdisplayed = d_trad('punctual');
      }
      else if ($periodic > 0)
      {
        $periodicdisplayed = d_trad('periodicparam',$periodic);
      }
      echo '<td><a href="' . $href . '">' . $periodicdisplayed . '</a></td>';         
      echo '</tr>';        
    }
    echo '</table>';
    echo '<br>';
  }
  
  if ($numalertplannings > 0)
  {
    echo '<h2>' . d_trad('mandtrainingsnotplannedforemployee') . '</h2>';    ?>
    <table class="report">
    <thead>
      <th><?php echo d_trad('reference'); ?></th>
      <th><?php echo d_trad('trainingname'); ?></th>   
      <th><?php echo d_trad('periodic'); ?></th>
      <th><?php echo d_trad('employee'); ?></th>      
      <th><?php echo d_trad('employeecategory'); ?></th>

    </thead><?php 


    for ($ap=0;$ap<$numalertplannings;$ap++)
    {
      $trainingid = $alertplanningidA[$ap];    
      $href = 'hr.php?hrmenu=trainingemployeeplanning&step=' . $STEP_CHOOSE_ADD . '&trainingid=' . $trainingid . '&employeeid=' . $alertplanningemployeeidA[$ap];
      echo d_tr();
      $trainingrefdisplayed = $alertplanningrefA[$ap];
      if ( strlen($trainingrefdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingrefdisplayed = substr($trainingrefdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingrefdisplayed. '</a></td>';   
      
      $trainingnamedisplayed = $alertplanningnameA[$ap];
      if ( strlen($trainingnamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingnamedisplayed = substr($trainingnamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingnamedisplayed. '</a></td>';  
      
      $periodic = $alertplanningperiodicA[$ap];;
      $periodicdisplayed = '';
      if ($periodic == 0)
      {
        $periodicdisplayed = d_trad('punctual');
      }
      else if ($periodic > 0)
      {
        $periodicdisplayed = d_trad('periodicparam',$periodic);
      }
      echo '<td><a href="' . $href . '">' . $periodicdisplayed . '</a></td>';    
      echo '<td><a href="' . $href . '">' . $alertplanningemployeenameA[$ap] . '</a></td>';    
      echo '<td><a href="' . $href . '">' . $alertplanningcatempA[$ap] . '</a></td>';       
      echo '</tr>';       
    }
    echo '</table>';
  }
}
?>
</table>
</form>