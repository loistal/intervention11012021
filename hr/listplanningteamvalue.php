<?php
require('preload/color.php'); 

$title = d_trad('planningteamvalues');
echo '<h2>' . $title . '</h2>';

$query = 'select * from planningteamvalue';
if(!$ds_showdeleteditems)
{
  $query .= ' where deleted = 0';
}
$query .= '  order by rank';
$query_prm = array();
require('inc/doquery.php');

if($num_results > 0)
{
  echo '<form method="post" action="hr.php?hrmenu=addplanningteamvalue">';
  echo '<table class="report">';  
  echo '<thead>';
  echo '<th>' . d_trad('name') . '</th>';
  echo '<th>' . d_trad('symbol') . '</th>';
  echo '<th>' . d_trad('rank') . '</th>';  
  echo '<th>' . d_trad('absence') . '</th>';
  echo '<th>' . d_trad('rest') . '</th>';
  echo '<th>' . d_trad('paidleave') . '</th>';    
  echo '<th>' . d_trad('bankholiday') . '</th>';  
  echo '<th>' . d_trad('training') . '</th>';  

  if($ds_showdeleteditems)
  {
    echo '<th>' . d_trad('deleted') . '</th>';
  }
  echo '</thead>';
  for($i=0;$i<$num_results;$i++)
  {
    $row = $query_result[$i];
    echo d_tr();
    $planningteamvalueid = $row['planningteamvalueid'];
    $planningteamvaluesymbol = $row['planningteamvaluesymbol'];
    
    echo '<td><a href="hr.php?hrmenu=modplanningteamvalue&step=1&planningteamvalueid=' . $planningteamvalueid . '">' . d_output($row['planningteamvaluename']) . '</td>'; 
    echo '<td style="text-align:center; BACKGROUND-COLOR:#' . $color_codeA[$row['colorid']] . ';"><a href="hr.php?hrmenu=modplanningteamvalue&step=1&planningteamvalueid=' . $planningteamvalueid . '">' . d_output($planningteamvaluesymbol) . '</td>';       
    echo '<td>' . $row['rank'] .'</td>';        
    echo '<td align=center>';
    if ($row['absence']) { echo '&radic;'; }
    echo '</td>';
    echo '<td align=center>';
    if ($row['rest']) { echo '&radic;'; }
    echo '</td>'; 
    echo '<td align=center>';
    if ($row['ispaidleave']) { echo '&radic;'; }
    echo '</td>';
    echo '<td align=center>';
    if ($row['isbankholiday']) { echo '&radic;'; }
    echo '</td>';
    echo '<td align=center>';
    if ($row['istraining']) { echo '&radic;'; }
    echo '</td>';    
    
    if($ds_showdeleteditems)
    {
      echo '<td align=center>';
      if ($row['deleted']) { echo '&radic;'; }
      echo '</td>';
    }
    echo '</tr>';
  }
  echo '</table>';  
  echo '<br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';    
  echo '</form>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}

?>