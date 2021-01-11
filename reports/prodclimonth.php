<h2><?php echo d_trad('productbyclientbymonth:');?></h2>

<form method="post" action="reportwindow.php" target="_blank"><table>

<tr><td><?php require('inc/selectproduct.php');?></td></tr>

<?php $year = substr($_SESSION['ds_curdate'],0,4);?>
<tr>
  <td><?php echo d_trad('year:');?></td>
  <td colspan=2><select name="year"><?php
      for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
      {
        if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select> <input type=checkbox name="compare3years" value=1> Comparer 3 ans

<tr>
  <td><?php echo d_trad('invoiceemployee:');?></td>
  <?php $dp_itemname = 'employee'; $dp_issales = 1; $dp_allowall = 1; require('inc/selectitem.php');?></td>
  <td><input type=checkbox name=withplanningclients value=1>&nbsp;<?php echo d_trad('displayallclientsinplanning');?></td>
</tr>
<tr><td><td><select name="quantitytype">
<option value=0>Quantité</option>
<option value=1>CA HT</option>
</select>
<tr><td>Ranger par :<td><select name="orderby">
<option value=1>Géo</option>
<option value=0>Quantité</option>
</select>
<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan="3" align="center"><input type=hidden name="report" value="prodclimonthreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>


</table></form>
<?php
if ($_SESSION['ds_useunits'])
{
  echo '<p class=alert>Ce rapport n\'affiche pas les sous-unités.</p>';
}
?>