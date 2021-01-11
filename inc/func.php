<?php

bcscale(4);

if (!isset($_SESSION['ds_exludequerylog']))
{
  $_SESSION['ds_exludequerylog'] = array( # highest probabability hits first
  'insert into log_query',
  'update seq',
  'insert ignore',
  'insert into logtable',
  'update usertable set attempts',
  'update usertable set shadow',
  'update clientaccess set attempts'
  );
}

# all TEM functions should start with d_ (exceptions: myfix myround datefix)
if (!function_exists('d_compare'))
{
  ### the following functions to be used for all currency operations
  
  function d_compare($value, $value2, $precision = 4) # 1=gt 0=eq -1=lt
  {
   return bccomp($value, $value2, $precision);
  }
  
  function d_add($value, $value2, $precision = 4)
  {
   return bcadd($value, $value2, $precision);
  }
  
  function d_subtract($value, $value2, $precision = 4) # $value - $value2
  {
   return bcsub($value, $value2, $precision);
  }
  
  function d_multiply($value, $value2, $precision = 4)
  {
   return bcmul($value, $value2, $precision);
  }
  
  function d_divide($value, $value2, $precision = 4)
  {
    if ($value2 == 0) { return 0; }
   return bcdiv($value, $value2, $precision);
  }

  # returns string of $number at defined precision
  function myround($number, $precision = 0)
  {
    if ($number < 0) { $roundvalue = '-0.'; }
    else { $roundvalue = '0.'; }
    if ($precision > 0)
    {
      if ($precision > 6) { $precision = 6; }
      for ($i=0; $i < $precision; $i++)
      {
        $roundvalue = $roundvalue . '0';
      }
    }
    $roundvalue = $roundvalue . '5';
    return bcadd($number, $roundvalue, $precision);
  }

  # filter text before output in HTML (not values in forms, see d_input)
  function d_output($ourstring, $allowtags = FALSE)
  {
    $ourstring = htmlspecialchars($ourstring, ENT_NOQUOTES, "UTF-8", FALSE);
    if ($allowtags)
    {
      $ourstring = str_replace('&lt;br&gt;','<br>',$ourstring);
      $ourstring = str_replace('&lt;b&gt;','<b>',$ourstring);
      $ourstring = str_replace('&lt;/b&gt;','</b>',$ourstring);
      $ourstring = str_replace('&lt;p&gt;','<p>',$ourstring);
      $ourstring = str_replace('&lt;/p&gt;','</p>',$ourstring);
      $ourstring = str_replace('&lt;h1&gt;','<h1>',$ourstring);
      $ourstring = str_replace('&lt;/h1&gt;','</h1>',$ourstring);
      $ourstring = str_replace('&lt;h2&gt;','<h2>',$ourstring);
      $ourstring = str_replace('&lt;/h2&gt;','</h2>',$ourstring);
      $ourstring = str_replace('&lt;h3&gt;','<h3>',$ourstring);
      $ourstring = str_replace('&lt;/h3&gt;','</h3>',$ourstring);
      $ourstring = str_replace('&lt;ul&gt;','<ul>',$ourstring);
      $ourstring = str_replace('&lt;/ul&gt;','</ul>',$ourstring);
      $ourstring = str_replace('&lt;ol&gt;','<ol>',$ourstring);
      $ourstring = str_replace('&lt;/ol&gt;','</ol>',$ourstring);
      $ourstring = str_replace('&lt;li&gt;','<li>',$ourstring);
      $ourstring = str_replace('&lt;/li&gt;','</li>',$ourstring);
      $ourstring = str_replace('&lt;strong&gt;','<strong>',$ourstring);
      $ourstring = str_replace('&lt;/strong&gt;','</strong>',$ourstring);
    }
    return nl2br($ourstring);
  }

  # format number for display (naming exception, would normally be called d_outputn)
  function myfix($number, $precision = 0)
  {
    if ($number == 0) { $number = '&nbsp;'; }
    else
    {
      $number = myround($number, $precision);
      $number = number_format($number, $precision, ',', $_SESSION['ds_decimalmark']); # TODO hardcoded comma should be parameter , or .
    }
    return $number;
  }

  # filter string before input (values in forms)
  function d_input($ourstring, $type = '')
  {
    if ($type == 'decimal')
    {
      $ourstring = d_add($ourstring, 0);
      $ourstring = rtrim($ourstring, "0");
      $ourstring = rtrim($ourstring, ".");
      if ($ourstring == 0) { $ourstring = ''; }
      return $ourstring;
    }
    else
    {
      return str_replace('"', '&quot;', $ourstring);
    }
  }

  # used to safely include files in a module
  function d_safebasename($ourstring)
  {
    # only allow underscore, numbers and lowercase english letters
    return preg_replace('/[^a-z0-9_ ]/', '', $ourstring);
  }
  
  # create translated text
  function d_trad($string, $optionsA = NULL) # $optionsA = array with possible parameters to place within the string (ex Hello NAME)    TODO add option to capitalize first character
  {
    if (!isset($_SESSION['ds_lang'][$string]))
    {
      global $dauphin_hostname;
      global $dauphin_port;
      global $dauphin_instancename;
      global $dauphin_timezone;
      global $dauphin_login;
      global $dauphin_password;
      $query = 'select tradstring from trad where lang=? and string=?';
      $query_prm = array($_SESSION['ds_language'], $string);
      require('inc/doquery.php');   
      if ($num_results)
      {
        $_SESSION['ds_lang'][$string] = $query_result[0]['tradstring'];       
      }
      else { $_SESSION['ds_lang'][$string] = '##' . $string . '##'; }
    }
    
    $trad_temp = $_SESSION['ds_lang'][$string];
    
    if(!is_null($optionsA))
    {
      $ioption = 1;
      if(is_array($optionsA))
      {
        foreach($optionsA as $options)
        {
          $trad_temp = mb_ereg_replace('##' .$ioption . '##', d_output($options), $trad_temp);    
          $ioption++;
        }
      }
      else
      {
        $trad_temp = mb_ereg_replace('##' .$ioption . '##', d_output($optionsA), $trad_temp);    
      }
    }
    return $trad_temp;
  }

  # build a valid date, format yy-mm-dd, default is current date
  function d_builddate($day,$month,$year) # TODO type int and refactor
  {
    $day = (int) $day; $month = (int) $month; $year = (int) $year;
    if ($year == 0) { $year = (int) substr($_SESSION['ds_curdate'],0,4); }
    if ($month == 0) { $month = (int) substr($_SESSION['ds_curdate'],5,2); }
    if ($day == 0) { $day = (int) substr($_SESSION['ds_curdate'],8,2); }
    switch ($month)
    {
      case 2:
        if ($year%4 == 0) { $maxday = 29; }
        else { $maxday = 28; }
      break;
      case 4:
      case 6:
      case 9:
      case 11:
        $maxday = 30;
      break;
      default:
        $maxday = 31;
      break;
    }
    if ($day > $maxday) { $day = $maxday; }
    if ($day < 10) { $day = '0' . $day; }
    if ($month < 10) { $month = '0' . $month; }
    $date = $year . '-' .  $month . '-' . $day;
    return $date;
  }
  
  # display date
  function datefix($mydate, $format = '')
  {
    if (!isset($mydate) || strlen($mydate) != 10 || $mydate == '0000-00-00') { return NULL; }
    $day = mb_substr($mydate,8,2)+0;
    $month = mb_substr($mydate,5,2)+0;
    $year = mb_substr($mydate,0,4)+0;
    if ($_SESSION['ds_user_date_format'] == 1)
    {
      #2018-12-31
      $mydate = '';
      if (stripos($format, 'noyear') === false) { $mydate .= $year.'-'; }
      if ($month < 10) { $mydate .= '0'; }
      $mydate .= $month;
      if (stripos($format, 'noday') === false)
      {
        $mydate .= '-';
        if ($day < 10) { $mydate .= '0'; }
        $mydate .= $day;
      }
    }
    elseif ($_SESSION['ds_user_date_format'] == 2)
    {
      #31/12/2018
      $mydate = '';
      if (stripos($format, 'noday') === false)
      {
        if ($day < 10) { $mydate .= '0'; }
        $mydate .= $day . '/';
      }
      if ($month < 10) { $mydate .= '0'; }
      $mydate .= $month . '/';
      if (stripos($format, 'noyear') === false) { $mydate .= $year; }
    }
    else
    {
      if (stripos($format, 'short') !== false)
      {
        $mydate = d_trad('month_short_' . $month);
      }
      else
      {
        $mydate = d_trad('month2_' . $month);
      }
      if (stripos($format, 'noday') === false) { $mydate = $day . ' ' . $mydate; }
      if (stripos($format, 'noyear') === false) { $mydate .= ' ' . $year; }
    }
    return $mydate;
  }
  
  # encode to make db field searchable, used for productname + clientname + suppliercode
  function d_encode($ourstring)
  {
    $ourstring = str_replace('&','##26',$ourstring);
    $ourstring = str_replace('"','##22',$ourstring);
    $ourstring = str_replace('<','##3C',$ourstring);
    $ourstring = str_replace('>','##3E',$ourstring);
    return $ourstring;
  }
  
  # decode fields from db
  function d_decode($ourstring)
  {
    $ourstring = str_replace('##26','&',$ourstring);
    $ourstring = str_replace('##22','"',$ourstring);
    $ourstring = str_replace('##3C','<',$ourstring);
    $ourstring = str_replace('##3E','>',$ourstring);
    return $ourstring;
  }

  function d_abs($number)
  {
    if (d_compare($number, 0) == -1)
    {
      $number = d_multiply($number, -1);
    }
    return $number;
  }

  # check if string contains other strings
  function d_strcontains($str, array $arr)
  {
    foreach($arr as $a)
    {
      if (stripos($str,$a) !== false) return true;
    }
    return false;
  }
  
  # sort an array     TODO replace all asort() TODO add options, replace other sorts
  function d_sortarray(array &$tosortA)
  {
    if (extension_loaded('intl') === true)
    {
      collator_asort(collator_create('fr_FR'), $tosortA); # TODO parameter for fr_FR
    }
    else
    {
      asort($tosortA);
    }
  }

  # sorts a query result, fieldname can be an array
  # example : d_sortresults($row, 'percentage', $num_rows);
  function d_sortresults(array &$qA, $fieldname, $num)
  {
    $copyA = $qA;
    for ($i = 0; $i < $num; $i++)
    {
      if (is_array($fieldname))
      {
        $tosortA[$i] = '';
        foreach($fieldname as $part)
        {
          $tosortA[$i] .= $qA[$i][$part];
        }
      }
      else
      {
        $tosortA[$i] = $qA[$i][$fieldname];
      }
    }
    if (is_array($tosortA))
    {
      d_sortarray($tosortA);
      $i = -1;
      foreach($tosortA as $key => $v)
      {
        $i++;
        $qA[$i] = $copyA[$key];
      }
    }
  }
  
  function d_table($class = '')
  {
    if ($class != '') { return '<table class="'.$class.'">'; }
    else { return '<table>'; }
  }
  
  function d_table_end()
  {
    return '</table>';
  }
  
  # TODO make d_thead d_thead_end d_th

  function d_tr($subtotal = 0) # TODO look into using tr:nth-child(odd) and p:nth-child(3n+0)
  {
    static $trcolor = 0;
    if ($subtotal == 1)
    {
      $result = '<tr class="trtablecolorsub">';
    }
    else
    {
      $trcolor++;
      $result = '<tr class="trtablecolor' . $trcolor .'">';
      if($trcolor % $_SESSION['ds_nbtablecolors'] == 0) { $trcolor = 0; }
    }
    return $result;
  }

  function d_td($text = '', $class = '', $colspan = 1, $rowspan = 1) # see style.css for class options
  {
    if ($class == '') { $result = '<td class="default"'; }
    else { $result = '<td class="' . $class . '"'; }
    if ($colspan > 1) { $result .= ' colspan='.$colspan; }
    if ($rowspan > 1) { $result .= ' rowspan='.$rowspan; }
    $result .= '>';
    if ($class == 'int') { $result .= myfix($text); }
    elseif ($class == 'decimal')
    {
      $text = myfix($text, 4);
      $text = rtrim($text, "0");
      $text = rtrim($text, ",");
      $result .= $text;
    }
    elseif ($class == 'currency') # TODO option bold, i.e. composite classes
    {
      $text = myfix($text, $_SESSION['ds_tem_currencyprecision']);
      $result .= $text;
    }
    elseif ($class == 'date') { $result .= datefix2($text); }
    elseif ($class == 'percent')
    {
      $text = myfix($text,2);
      if ($text != '' && $text != 0) { $result .= $text.'&nbsp;%'; }
    }
    else { $result .= d_output($text); }
    return $result;
  }
  
  function d_td_unfiltered($text = '', $class = 'default', $colspan = 1, $rowspan = 1)
  {
    $result = '<td class="' . $class . '"';
    if ($colspan > 1) { $result .= ' colspan='.$colspan; }
    if ($rowspan > 1) { $result .= ' rowspan='.$rowspan; }
    $result .= '>';
    $result .= $text;
    return $result;
  }

  function d_sendemail($emailaddress,$replytoaddress,$subject,$messagetext,$ccaddress='',$bccaddress='')
  {
    $headers  = 'MIME-Version: 1.0' . PHP_EOL;
    $headers .= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;
    $headers .= 'Reply-To: '.$replytoaddress . PHP_EOL; 
    $headers .= 'From: '.$_SESSION['ds_customname'] . ' ' . $_SESSION['ds_name'].' <TEM>' . PHP_EOL;
    $headers .= 'Delivered-to: '.$emailaddress . PHP_EOL;
    if (isset($ccaddress) && ($ccaddress != '')) { $headers .= 'Cc: '.$ccaddress . PHP_EOL; }
    if (isset($bccaddress) && ($bccaddress != '')) { $headers .= 'Bcc: '.$bccaddress . PHP_EOL;}
    #$subject = '=?utf-8?B?'.base64_encode($subject).'?='; somehow this prevents email from actually sending
    $message = '<html><body>' . $messagetext . '</body></html>';
    $headers .= "Content-Transfer-Encoding: base64\r\n\r\n"; # these two lines added because of problems with long emails
    $message = chunk_split(base64_encode($message));
    return mail($emailaddress, $subject, $message, $headers);
  }

  

  
  
  
  
  
  # TODO replace with datefix short
  function datefix2($mydate)
  {
    return datefix($mydate, 'short');
  }
  
  # TODO remove
  function d_td_old($text = '', $align = 0, $class = 0, $colspan = 0, $link = '', $highlight = 0, $allowtags = FALSE)
  {
    $result = '<td';
    if ($class == 1) { $result .= ' class=breakme'; }
    if ($align > 0)
    {
      if ($align == 1) { $result .= ' align=right'; }
      elseif ($align == 3) { $result .= ' align=right'; }
      elseif ($align == 4) { $result .= ' align=center'; }
    }
    $result .= ' valign=top';
    if ($colspan > 1) { $result .= ' colspan='.$colspan; }
    if ($link != '')
    {
      if (substr($link, 0, 2) == '##')
      {
        $link = substr($link, 2);
        $result .= '><a href="' . $link . '">';
      }
      else
      {
        $result .= '><a href="' . $link . '" target=_blank>';
      }
    }
    else
    {
      $result .= '>';
    }
    if ($class == 2) { $result .= '<b>'; }
    $result .= d_output($text, $allowtags);
    if ($link != '') { $result .= '</a>'; }
    return $result;
  }

}


?>