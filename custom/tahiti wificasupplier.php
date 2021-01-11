<?php

switch($clientaccessmenu)
{

  case 'custom_client':
  default:
      ?><h2>Liste des clients:</h2>
      <form method="post" action="customreportwindow.php" target=_blank><table>
      <tr><td colspan=2>(filtres à ajouter)
      <tr><td colspan="2" align="center">
      <input type=hidden name="report" value="custom_client">
      <input type="submit" value="Valider">
      </table></form><?php  
  break;

break;
  
}

# 

?>