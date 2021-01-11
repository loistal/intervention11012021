<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['bystatus'] = 'uint';
require('inc/readpost.php');

$cst_bracket = array(); $cst_bracket_base = array();
for ($i=0;$i <= 10;$i++) { $cst_bracket[$i] = 0; $cst_bracket_base[$i] = 0; $cst_count[$i] = 0; }
$t1 = $t2 = 0;

$title = 'Rapport CST ' . $month . ' / ' . $year;
showtitle_new($title);
echo d_table('report');

$query = 'select employeeid,payslip.payslipid,bracket0,bracket1,bracket2,bracket3,bracket4,bracket5,bracket6,bracket7,bracket8,bracket9,bracket10,
                 bracket_base0,bracket_base1,bracket_base2,bracket_base3,bracket_base4,bracket_base5,bracket_base6,bracket_base7,bracket_base8,bracket_base9,bracket_base10
from payslip,payslip_tax_bracket
where payslip_tax_bracket.payslipid=payslip.payslipid
and month(payslipdate)=? and year(payslipdate)=?';
if ($bystatus) { $query .= ' and status=1'; }
$query_prm = array($month,$year);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  for ($y = 0; $y <= 10; $y++)
  {
    $cst_bracket[$y] += $query_result[$i]['bracket'.$y];
    $cst_bracket_base[$y] += $query_result[$i]['bracket_base'.$y];
    if ($query_result[$i]['bracket'.$y] > 0) { $cst_count[$y]++; }
  }
}

# temporary display to verify TODO official template
echo '<thead><th><th>Nb<th>Revenus taxables<th>CST due</thead>';
for ($y = 0; $y <= 10; $y++)
{
  echo '<tr><td>3.'.($y+1).'<td align=right>',myfix($cst_count[$y]),'<td align=right>',myfix($cst_bracket_base[$y]),'<td align=right>',myfix($cst_bracket[$y]);
  $t1 += $cst_bracket_base[$y];
  $t2 += $cst_bracket[$y];
}
echo '<tr><td><td><td align=right>',myfix($t1),'<td align=right>',myfix($t2);

# ask Séverine, total is cst_salary or gross_salary??? needed for total non_imposable

echo d_table_end();

?>