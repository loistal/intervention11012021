<?php

if ($_SESSION['ds_can_send_emails'] != 1) { exit; }

require ('inc/standard.php');
require ('inc/top.php');

$PA['add_attachments'] = 'uint';
$PA['email_bodyid'] = 'int';
$PA['confirm1'] = 'uint';
$PA['confirm2'] = 'uint';
$PA['invoice_list'] = '';
require('inc/readpost.php');

$invoice_list = unserialize(base64_decode($invoice_list));

$query = 'select infoemail from companyinfo';
$query_prm = array();
require('inc/doquery.php');
$replytoaddress = $query_result[0]['infoemail'];
if (trim($replytoaddress) == "")
{
  echo '<p>L\'adresse email de votre entreprise n\'est pas définie.</p>';
  $confirm1 = 0;
}

if ($confirm1 && $confirm2 && $email_bodyid > 0 && is_array($invoice_list))
{
  $query = 'select subject,email_body from email_body where email_bodyid=?';
  $query_prm = array($email_bodyid);
  require('inc/doquery.php');
  $subject = $query_result[0]['subject'];
  $messagetext = $query_result[0]['email_body'];
  
  $query = 'select useremail from usertable where userid=?';
  $query_prm = array($_SESSION['ds_userid']);
  require('inc/doquery.php');
  $ccaddress = $query_result[0]['useremail'];
  
  echo '<h2>Email(s) envoyé(s)</h2><table class="report"><thead><th>Facture<th>Resultat<th>Partage</thead>';
  foreach($invoice_list as $invoice_client_list)
  {
    $link = ''; $email = '';
    echo d_tr(),d_td($invoice_client_list);
    $invoiceidA = explode("|", $invoice_client_list);
    foreach($invoiceidA as $invoiceid)
    {
      $invoiceid = (int) $invoiceid;
      if ($invoiceid>0)
      {
        if ($email == '')
        {
          $query = 'select batchemail,confirmed,isreturn,isnotice,proforma from invoicehistory,client
          where invoicehistory.clientid=client.clientid
          and invoiceid=?
          UNION
          select batchemail,confirmed,isreturn,isnotice,proforma from invoice,client
          where invoice.clientid=client.clientid
          and invoiceid=?';
          $query_prm = array($invoiceid,$invoiceid);
          require('inc/doquery.php');
          $email = $query_result[0]['batchemail'];
        }
        $template = $_SESSION['ds_invoicetemplate'];
        if ($_SESSION['ds_custominvoiceisdefault']) { $template = 0; }
        $ds_customname = strtolower($_SESSION['ds_customname']);
        $showcustom = 0;
        if ($template == 0)
        {
          if ($_SESSION['ds_custominvoiceisdefault'] && file_exists('custom/' . $ds_customname . 'showinvoice.php'))
          { $showcustom = 1; $template = 99; }
        }
        elseif ($template >= 99) { $showcustom = 1; }
        $token = md5(uniqid(mt_rand(), TRUE));
        $query = 'SELECT token FROM invoiceshare WHERE invoiceid = ?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        if ($num_results)
        {
          $query = 'UPDATE invoiceshare SET token = ?, instancename = ?, userid = ?, showcustom = ?, template = ? WHERE invoiceid = ?';
        }
        else
        {
          $query = 'INSERT INTO invoiceshare (token, instancename, userid, showcustom, template, invoiceid) values (?, ?, ?, ?, ?, ?)';
        }
        $query_prm = array($token,
          $dauphin_instancename,
          $_SESSION['ds_userid'],
          $showcustom,
          $template,
          $invoiceid
        );
        require('inc/doquery.php');

        $servername = $_SERVER['SERVER_NAME'];
        if ($_SESSION['ds_customname'] == 'Wing Chong') { $servername = 'https://'.$_SERVER['SERVER_NAME'].':4431'; }
        $baselink = $servername.'/printwindow.php';
        $baselink .= '?report=showinvoice&invoiceid='.$invoiceid.'&instancename='.$dauphin_instancename.'&token='.$token;
        $link .= ' <a href="'.$baselink.'">'.$invoiceid.'</a>';
      }
    }

    if (trim($email) != "")
    {
      if (strpos($messagetext, '%%%'))
      {
        $body = str_replace('%%%', $link, $messagetext);
      }
      else
      {
        $kladd = 'Votre document:';
        if (count($invoiceidA) > 1) { $kladd = 'Vos documents:'; }
        $body = $messagetext.'<br><br>'.$kladd.$link;
      }
      if ($add_attachments)
      {
        $query = 'select imageid,imagetype,image from image where invoiceid=? order by imageorder,imageid';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        if ($num_results)
        {
          $separator = md5(time());
          
          $headers  = 'MIME-Version: 1.0' . PHP_EOL;
          $headers .= 'Content-Type: multipart/mixed; boundary="' . $separator . '"' . PHP_EOL;
          $headers .= 'Content-Transfer-Encoding: 7bit' . PHP_EOL;
          $headers .= 'Reply-To: '.$replytoaddress . PHP_EOL; 
          $headers .= 'From: '.$_SESSION['ds_customname'] . ' ' . $_SESSION['ds_name'].' <TEM>' . PHP_EOL;
          $headers .= 'Delivered-to: '.$email . PHP_EOL;
          if (isset($ccaddress) && ($ccaddress != '')) { $headers .= 'Cc: '.$ccaddress . PHP_EOL; }
          if (isset($bccaddress) && ($bccaddress != '')) { $headers .= 'Bcc: '.$bccaddress . PHP_EOL;}
          #$subject = '=?utf-8?B?'.base64_encode($subject).'?='; somehow this prevents email from actually sending
          $message = '--' . $separator . PHP_EOL;
          $message .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
          $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL;
          $message .= PHP_EOL . '<html><body>' . nl2br($body) . '</body></html>' . PHP_EOL . PHP_EOL;
          
          for ($i=0; $i < $num_results; $i++)
          {
            $message .= '--' . $separator . PHP_EOL;
            $message .= 'Content-Type: application/octet-stream; name="image'
            . $query_result[$i]['imageid'] . '.'.$query_result[$i]['imagetype'].'"' . PHP_EOL;
            $message .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
            $message .= 'Content-Disposition: attachment' . PHP_EOL;
            $message .= PHP_EOL . chunk_split(base64_encode($query_result[$i]['image'])) . PHP_EOL . PHP_EOL;
          }
          $message .= '--' . $separator . '--';
          $result = mail($email, $subject, $message, $headers);
        }
        else { $add_attachments = 0; }
      }
      if ($add_attachments == 0)
      {
        $result = d_sendemail($email,$replytoaddress,$subject,nl2br($body),$ccaddress);
      }
      if ($result) { echo d_td('Envoyé'); }
      else { echo d_td('Échec envoi'); }
    }
    else { echo d_td('Adresse email manquante'); }
    echo d_td($baselink);
  }
  echo '</table>';
}

require ('inc/bottom.php');

?>