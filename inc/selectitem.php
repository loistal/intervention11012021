<?php

# TODO need refactor everything, especially all the different options ( of $dp_notr $dp_notddescr)

### input box for selecting various items

# 2016 10 18 what a horrible mess, refactor all the various selectitem.php to one file and merge/remove useless parameters

# input: $dp_itemname $dp_addtoid $dp_description $dp_selectedid $dp_allowall $dp_nonempty $dp_noblank $dp_short $dp_long $dp_readonly $dp_showdeleted $_POST[$dp_itemname . $dp_addtoid . 'id']
# input: $dp_ultimate (ultimate = id to signify a group of ids to be listed last, see products/regionprice.php for an example) $dp_colspan $dp_notr $dp_notddescr $dp_notable
# input for employee: $dp_issales $dp_iscashier $dp_isdelivery $dp_ishrsuperuser $dp_scheduleid
# input for various: $dp_groupid (for employee this is teamid)
# to buffer input: $dp_buffername
# unused input: $dp_maxstrlength
# output: $_POST[$dp_itemname . $dp_addtoid . 'id']

$temp_buffer = '';

if (!isset($dp_addtoid)) { $dp_addtoid = ''; }
if (!isset($dp_selectedid)) { $dp_selectedid = ''; }
if (!isset($dp_notr)) { $dp_notr = 0; }
if (!isset($dp_notable)) { $dp_notable = 0; }
if (!isset($dp_notddescr)) { $dp_notddescr = 0; }
if (!isset($dp_long)) { $dp_long = 0; }
if (!isset($dp_autofocus)) { $dp_autofocus = 0; }
if (!isset($dp_noblank)) { $dp_noblank = 0; }
if (!isset($dp_nonempty)) { $dp_nonempty = 0; }
if (!isset($dp_showdeleted)) { $dp_showdeleted = 0; }
if (!isset($dp_issales)) { $dp_issales = 0; }
if (!isset($dp_iscashier)) { $dp_iscashier = 0; }
if (!isset($dp_scheduleid)) { $dp_scheduleid = 0; }
if (!isset($dp_ishrsuperuser)) { $dp_ishrsuperuser = 0; }
if (!isset($dp_buffername)) { $dp_buffername = ''; }

$dp_itemA = $dp_itemname . 'A';
if(!isset($$dp_itemA )){require('preload/' . $dp_itemname . '.php');}
if ($dp_itemname == 'bankaccount') { require('preload/bank.php'); }
if ($dp_itemname == 'user' && isset($dp_short) && $dp_short) { $dp_itemA = 'user_initialsA'; }
if ($dp_itemname == 'accountingnumber' && $dp_long) { $dp_itemA = 'accountingnumber_longA'; }
if (isset($$dp_itemA)) { $dp_itemA = $$dp_itemA; } else { $dp_itemA =  array(); }
if (isset($dp_itemA) || 1==1) # 2016 12 21 testing showing empty selector to show possibility, should probably be in else {}
{
  $dp_item_deletedA = $dp_itemname . '_deletedA';
  if(isset($$dp_item_deletedA)) { $dp_item_deletedA = $$dp_item_deletedA; }
  else { $dp_item_deletedA = array(); }

  if (isset($dp_description) && $dp_description != '')
  { 
    if($dp_notr != 1 && $dp_notable != 1) { $temp_buffer .= '<tr>'; }
    if($dp_notddescr != 1 && $dp_notable != 1) { $temp_buffer .= '<td>'; }
		$temp_buffer .= $dp_description . ': '; 
  }
  
  if(isset($dp_colspan)) { $temp_buffer .= '<td colspan=' . $dp_colspan . '>';}
  elseif (!isset($dp_notable) || $dp_notable != 1) { $temp_buffer .= '<td>'; }
  if (isset($dp_readonly) && $dp_readonly == 1) { $temp_buffer .= '<input type=hidden name="' . $dp_itemname . $dp_addtoid . 'id" value="' . $dp_selectedid . '">'; }
  $temp_buffer .= '<select ';
  if ($dp_autofocus) { $temp_buffer .= 'autofocus '; }
  $temp_buffer .= 'name="' . $dp_itemname . $dp_addtoid . 'id"';
  if (isset($dp_readonly) && $dp_readonly == 1) { $temp_buffer .= ' disabled'; }
  $temp_buffer .= '>';
  if (isset($dp_allowall) && $dp_allowall == 1) { $temp_buffer .= '<option value=-1>'. d_trad('selectall') .'</option>'; }
  if ($dp_noblank != 1)
  {
    $temp_buffer .= '<option value=0';
    if ($dp_selectedid === 0) { $temp_buffer .= ' selected'; }
    $temp_buffer .= '></option>';
  }
  if ($dp_nonempty == 1)
  {
    $temp_buffer .= '<option value=-2';
    if ($dp_selectedid == -2) { $temp_buffer .= ' selected'; }
    $temp_buffer .= '>'. d_trad('nonempty') .'</option>';
  }
  if (isset($dp_itemA))
  {
    foreach ($dp_itemA as $itemidS => $itemname)
    {
      $dp_ok = 1;
      if (isset($dp_item_deletedA[$itemidS]) && $dp_item_deletedA[$itemidS])
      {
        if ($dp_showdeleted != 1 && $_SESSION['ds_showdeleteditems'] != 1 && $dp_selectedid != $itemidS) { $dp_ok = 0; }
        else { $itemname = d_trad('deletedsquarebrackets',$itemname); } 
      }
      if ($dp_itemname == 'employee')
      {
        if ($dp_issales == 1 && $employee_issalesA[$itemidS] != 1) { $dp_ok = 0; }
        if (isset($dp_isdelivery) && $dp_isdelivery == 1 && $employee_isdeliveryA[$itemidS] != 1) { $dp_ok = 0; }
        if (isset($dp_ispicking) && $dp_ispicking == 1 && $employee_ispickingA[$itemidS] != 1) { $dp_ok = 0; }
        if ($dp_iscashier == 1 && $employee_iscashierA[$itemidS] != 1) { $dp_ok = 0; }
        if ($dp_scheduleid == 1 && $employee_scheduleidA[$itemidS] != 1) { $dp_ok = 0; }
        if ($dp_ishrsuperuser == 1 && $employee_ishrsuperuserA[$itemidS] != 1) { $dp_ok = 0; }
        if (isset($dp_groupid) && $employee_teamidA[$itemidS] != $dp_groupid && $itemidS != $_SESSION['ds_myemployeeid']) { $dp_ok = 0; } # myself plus my team is allowed
      }
      if ($dp_ok)
      {
        if ($dp_itemname == 'bankaccount')
        {
          $dp_bankid = $bankaccount_bankidA[$itemidS];
          $itemname = $bankA[$dp_bankid] . ': ' . $itemname;
        }
        if ($dp_itemname == 'training')
        {   
          $itemnametemp = $itemname;
          if ($training_refA[$itemidS] != '') 
          { 
            $itemname = $training_refA[$itemidS];
            if ($itemnametemp != '')
            {
              $itemname .=  ': ' . $itemnametemp;
            }
          }
        }        
        if ($dp_itemname == 'returnreason' && $returnreason_returntostockA[$itemidS] == 1)
        {
          $itemname = d_trad('returnsquarebrackets',$itemname);
        }
        $temp_buffer .= '<option value="' . d_input($itemidS) . '"';
        if ($dp_selectedid == $itemidS) { $temp_buffer .= ' selected'; }
        $temp_buffer .= '>' . mb_substr(d_output($itemname),0,$_SESSION['ds_selectitem_length']) . '</option>';
      }
    }
  }
  if ($dp_itemname == 'regulationzone' && isset($dp_ultimate) && $dp_ultimate > 0)
  {
    $temp_buffer .= '<option value=9999>' . d_trad('otherislands') . '</option>';
  }
  $temp_buffer .= '</select>';
}

if ($dp_buffername != '') { $$dp_buffername .= $temp_buffer; }
else { echo $temp_buffer; }

unset($dp_buffername,$dp_itemname,$dp_addtoid,$dp_issales,$dp_isdelivery,$dp_iscashier,$dp_description,$dp_allowall,$dp_nonempty,$dp_selectedid,$dp_noblank,$dp_short,$dp_ishrsuperuser,$dp_scheduleid,$dp_colspan,$dp_notr,$dp_notddescr,$dp_showdeleted, $dp_long, $dp_readonly, $dp_notable,$dp_groupid);

?>