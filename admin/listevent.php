<?php
$ds_showdeleteditems = $_SESSION['ds_showdeleteditems'];

$title = d_trad('listevent:');
echo '<h2>' . $title . '</h2>';

require('preload/calendar.php');
if(isset($calendarA))
{
  echo '<table class="report">';
  echo '<thead><th>' . d_trad('date') . '</th>';
  echo '<th>' . d_trad('event') . '</th>';
  echo '<th>' . d_trad('bankholiday') . '</th>'; 
  if($ds_showdeleteditems)
  {
    echo '<th>' . d_trad('deleted') . '</th>';
  }
  echo '</thead>';
  foreach ($calendarA as $id => $name)
  {
    echo d_tr() .'<td>' . d_output(datefix2($calendar_dateA[$id])) . '</td>';
    echo '<td><a href="system.php?systemmenu=modevent&step=1&date=' . $calendar_dateA[$id] . '">' . d_output($name) . '</td>';
    echo '<td align=center>';
    if ($calendar_isbankholidayA[$id]) { echo '&radic;'; }
    echo '</td>';
    if($ds_showdeleteditems)
    {
      echo '<td align=center>';
      if ($calendar_deletedA[$id]) { echo '&radic;'; }
      echo '</td>';
    }
    echo '</tr>';
  }
  echo '</table>';
}

?>