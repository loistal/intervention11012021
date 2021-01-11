<?php

if ($_SESSION['ds_can_send_emails'] != 1) { exit; }

$PA['email_bodyid'] = 'uint';
$PA['email_body'] = '';
$PA['subject'] = '';
$PA['deleted'] = 'uint';
require('inc/readpost.php');

if ($subject != '' || ($deleted == 1 && $email_bodyid > 0))
{
  if ($email_bodyid > 0)
  {
    $query = 'update email_body set subject=?,email_body=?,deleted=? where email_bodyid=?';
    $query_prm = array($subject, $email_body, $deleted, $email_bodyid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>E-mail modifié.</p>';
    }
  }
  else
  {
    $query = 'insert into email_body (subject,email_body) values (?,?)';
    $query_prm = array($subject,$email_body);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>E-mail ajouté.</p>';
    }
  }
}

if ($email_bodyid > 0)
{
  $query = 'select * from email_body where email_bodyid=?';
  $query_prm = array($email_bodyid);
  require('inc/doquery.php');
  $subject = $query_result[0]['subject'];
  $email_body = $query_result[0]['email_body'];
  $deleted = $query_result[0]['deleted'];
  echo '<h2>Modifier e-mail</h2>';
}
else
{
  echo '<h2>Ajouter e-mail</h2>';
}
echo '<p>Utiliser ce code pour inserer le lien vers la facture: %%%</p>';
echo '<form method="post" action="clients.php"><table>';
echo '<tr><td>Sujet: <input autofocus type=text name="subject" value="'.$subject.'" size=60>';
echo '<tr><td><textarea type="textarea" name="email_body" cols=160 rows=35>' . d_input($email_body) . '</textarea>';
echo '<tr><td>';
if ($deleted) { echo 'Supprimé:'; }
else { echo 'Supprimer:'; }
echo ' <input type=checkbox name="deleted"'; if ($deleted == 1) { echo ' checked'; } echo ' value=1>';
echo '<tr><td colspan="2" align="center">
<input type=hidden name="clientsmenu" value="' . $clientsmenu . '">
<input type=hidden name="email_bodyid" value="' . $email_bodyid . '">
<input type="submit" value="Valider"></td></tr>
</table></form>';

$query = 'select email_bodyid,subject from email_body';
if ($_SESSION['ds_showdeleteditems'] != 1) { $query .= ' where deleted=0'; }
$query .= ' order by subject';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<br><br><h2>Modifier e-mail</h2><form method="post" action="clients.php"><table class=report>';
  echo '<thead><th><th>Sujet</thead>';
  for ($i=0; $i < $num_results; $i++)
  {
    echo d_tr();
    echo d_td_unfiltered('<input type=radio name="email_bodyid" value="'.d_input($query_result[$i]['email_bodyid']).'">','right');
    echo d_td($query_result[$i]['subject']);
  }
  echo '<tr><td colspan=10 align="center"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '">
  <input type="submit" value="Modifier"></td></tr>
  </table></form>';
}

?>