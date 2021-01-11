<?php

# TODO refactor
# TODO add d_output

# input: $i $row $num_rows $subtfield1 $subtfield1_descr $subtfield_count_descr 
# don't forget to modify also showgrandtotal 

if(!isset($subtfield1_descr) && isset($subtfield1)) { $subtfield1_descr = $subtfield1; }
$usesubunits = false;if($_SESSION['ds_useunits']){$usesubunits = true;}
if(!isset($subtfield1_count)){$subtfield1_count = 0;  }
if (!isset($stockfield)) { $stockfield = -1; }

if ($i == ($num_rows - 1)
    || isset($showsubtotal,$subtfield1,$row[$i][$subtfield1],$row[$i+1][$subtfield1])
    && $row[$i][$subtfield1] != $row[$i+1][$subtfield1])
{
  if (isset($subtfield1_descr)) { $fieldname = $subtfield1_descr; } else { $fieldname = ''; }
  if (isset($subtfield1) && isset($row[$i][$subtfield1])) { $showfield = $row[$i][$subtfield1]; } else { $showfield = ''; }
  require('inc/configfield.php');

  echo '<tr class=trtablecolorsub>'; 
  
  $showline_temp = '';
  $iscolspan_temp = true;  
  $colspan_temp = 0; 
  
  for ($y=1;$y <= $fieldnum;$y++)
  {
    if ($y == 1)
    {
      $showline_temp .= '<td colspan=##cs## class=subtotal>';
      $showsubtotal_temp = d_trad('total') . '&nbsp;' . $showfield;
      if (isset($showsubtotal[$y]))
      {
        $showline_temp = mb_ereg_replace("##cs##", 1 , $showline_temp);     
        if(isset($percentagefield) && $y == $percentagefield)
        {
          //to avoid 0.0% and 100.0%
          if(myround($showsubtotal[$y],1) <= 0){$showsubtotal_temp .= ': <0.1%';}          
          elseif($showsubtotal[$y] < 100){$showsubtotal_temp .= ': ' . myfix($showsubtotal[$y], 1) . '%';}
          else{$showsubtotal_temp .= ': ' . myfix($showsubtotal[$y],0) . '%';}          
        }
        elseif($usesubunits && (($y == $stockfield)))
        {
          $showsubtotal_temp .= ': ' . $showsubtotal[$y];
        }
        else
        {
          $showsubtotal_temp .= ': ' . myfix($showsubtotal[$y], 0);        
        }
        //reinit subtotal
        $showsubtotal[$y] = 0;
        $iscolspan_temp = false;
      }

      $showline_temp .= $showsubtotal_temp;
      //optional: number of rows for subtotal and label
      if(isset($subtfield1_count) && isset($subtfield1_count_descr))
      {
        $showline_temp .= ':&nbsp;' . d_trad($subtfield1_count_descr,($subtfield1_count));
        $subtfield1_count = 0;
      }
      $showline_temp .='</td>';
    }
    elseif (isset($showsubtotal[$y]))
    {
      if(isset($percentagefield) && $y == $percentagefield)
      {
        //to avoid 0.0% and 100.0%
        if(myround($showsubtotal[$y],1) <= 0){$showline_temp .= '<td class=subtotal align=right><0.1%';}
        else if($showsubtotal[$y] < 100){$showline_temp .= '<td class=subtotal align=right>' . myfix($showsubtotal[$y], 1) . '%';}  
        else{$showline_temp .= '<td class=subtotal align=right>' . myfix($showsubtotal[$y], 0) . '%';}     
      }
      elseif($usesubunits && (($y == $stockfield)))
      {   
        $showline_temp .= '<td class=subtotal align=right>' . $showsubtotal[$y];
      }
      else
      {
        if (isset($npu_for_total[$y]) && $npu_for_total[$y] > 1)
        {
          $showline_temp .= '<td class=subtotal align=right>' . d_showquantity($showsubtotal[$y], $npu_for_total[$y]);
        }
        else
        {
          $showline_temp .= '<td class=subtotal align=right>' . myfix($showsubtotal[$y], 0);
        }
      }  
      $showline_temp .= '</td>';
      //reinit subtotal
      $showsubtotal[$y] = 0;
      //colspan only for title
      if ($iscolspan_temp && $colspan_temp >= 1)
      {
        $showline_temp = mb_ereg_replace("##cs##", $colspan_temp , $showline_temp);
        $iscolspan_temp = false;
      }      
    }
    else if(!$iscolspan_temp)
    {
      $showline_temp .= '<td class=subtotal>&nbsp;</td>';    
    }
    $colspan_temp ++;
  }
  //no subtotal 
  if($iscolspan_temp){$showline_temp = mb_ereg_replace("##cs##", $colspan_temp , $showline_temp);}
  
  echo $showline_temp . '</tr>';
}
$subtfield1_count ++;

unset ( $showline_temp, $colspan_temp, $iscolspan_temp,$showsubtotal_temp,$usesubunits);

?>