<?php

if (isset($_POST['sqldebug']))
{
  $_SESSION['ds_showsqldebug'] = $_POST['sqldebug'];
}

?>

<h2>Debug mode</h2>
<form method="post" action="system.php"><table>

<tr><td>SQL debug:</td><td>
<select name="sqldebug">
  <option value="0" <?php if($_SESSION['ds_showsqldebug'] == 0){echo 'SELECTED';}?>>Off</option>';
  <option value="1" <?php if($_SESSION['ds_showsqldebug']  == 1){echo 'SELECTED';}?>>On</option>';
</select></td></tr>

<tr><td colspan="2" align="center"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Valider"></td></tr>
</table></form>