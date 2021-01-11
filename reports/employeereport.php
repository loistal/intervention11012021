<?php

echo '<h2>Rapport employé(e)s</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

$dp_description = 'Qualification'; $dp_itemname = 'qualification'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="employeereport"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

require('reportwindow/employeereport_cf.php');
require('inc/configreport.php');

?>