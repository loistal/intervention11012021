<?php 
    if (isset($_GET['intervention'])) {
        $intervention_id = $_GET['intervention'];
    }
    else 
    {
        echo '<p>Veuillez fournir un numéro d\'intervention.</p>';
        exit();
    }

    if($intervention_id === '')
    {
        echo '<p>Veuillez fournir un numéro d\'intervention.</p>';
        exit();
    }

    $query = 'select * from intervention where interventionid=?';
    $query_prm = array($intervention_id);
    require('inc/doquery.php');

    if($num_results === 0)
    {
        echo '<p>Pas de résultats pour l\'intervention numéro ' . $intervention_id;
        exit();
    }
    else 
    {
        $my_intervention = $query_result[0];
    }

    $full_title = 'Intervention ' . $my_intervention['interventionid'];
    if($my_intervention['interventiondate'] !== '0000-00-00')
    {
        $full_title .= ' - ' . datefix($my_intervention['interventiondate']);
    }

    showtitle_new($full_title);

    echo '<div style="max-width: 50%;">';

    echo d_table('report');
    echo d_tr(); 
    echo d_td_unfiltered('<strong>Client</strong>'); 
    
    $query = 'select clientname from client where clientid=?';
    $query_prm = array($my_intervention['interventionclientid']);
    require('inc/doquery.php');

    if($num_results === 1) { $clientname = $query_result[0]['clientname']; }
    else { $clientname = ''; }

    echo d_td($clientname);

    echo d_tr();
    echo d_td_unfiltered('<strong>Employé</strong>'); 

    $query = 'select employeename, employeefirstname from employee where employeeid=?';
    $query_prm = array($my_intervention['interventionemployeeid']);
    require('inc/doquery.php');

    if($num_results === 1) 
    { 
        $fullname = $query_result[0]['employeename'];
        $fullname .= ', ';
        $fullname .= $query_result[0]['employeefirstname'];
    }
    else { $fullname = ''; }
    echo d_td($fullname); 

    echo d_tr();
    echo d_td_unfiltered('<strong>Date</strong>'); 
    echo d_td($my_intervention['interventiondate'], 'date');

    echo d_tr();
    echo d_td_unfiltered('<strong>Titre</strong>'); 
    echo d_td($my_intervention['interventiontitle']);

    echo d_tr();
    echo d_td_unfiltered('<strong>Commentaire</strong>'); 
    echo d_td($my_intervention['interventioncomment']);

    echo d_tr();
    echo d_td_unfiltered('<strong>Nombre de lignes</strong>'); 

    $query = 'select * from interventionitem where interventionid=?';
    $query_prm = array($intervention_id);
    require('inc/doquery.php');
    $intervention_lines = $query_result;
    $num_lines = $num_results;
    echo d_td_unfiltered($num_lines, 'int');

    echo d_tr();
    echo d_td_unfiltered('<strong>Total heures</strong>'); 

    $total_hours = 0;
    $total_minutes = 0;
    $total_elapsed = new DateInterval('PT0H');
    for($i = 0; $i < $num_lines; $i++)
    {
        $time_start = $intervention_lines[$i]['timestart'];
        $time_end = $intervention_lines[$i]['timeend'];

        # Skip the line if information is missing
        if($time_start === '00:00:00' || $time_end === '00:00:00') { continue; }

        $start_datetime = new DateTime($time_start);
        $end_datetime = new DateTime($time_end);

        $hour_start = $start_datetime->format('H');
        $hour_end = $end_datetime->format('H');
        $is_ended_next_day = $hour_end < $hour_start;

        # Special calculations if the intervention was performed across midnight
        if($is_ended_next_day)
        {
            $hours_to_midnight = 24 - $hour_start;
            $minute_start =$start_datetime->format('i');
            if($minute_start !== 0)
            {
                $hours_to_midnight = 24 - $hour_start - 1;
                $minutes_to_midnight = 60 - $minute_start;
            }
            else 
            {
                $hours_to_midnight = 24 - $hour_start;
                $minutes_to_midnight = 0;
            }
            $total_elapsed->h += $hours_to_midnight;
            $total_elapsed->i += $minutes_to_midnight;

            $total_elapsed->h += $end_datetime->format('H');
            $total_elapsed->i += $end_datetime->format('i');
        }
        else 
        {
            $line_elapsed = $start_datetime->diff($end_datetime);
            $total_elapsed->h += $line_elapsed->format('%h');
            $total_elapsed->i += $line_elapsed->format('%i');
        }

        # Fix because DateIntervals don't work properly
        $total_hours = $total_elapsed->h + floor($total_elapsed->i / 60);
        $total_minutes = $total_elapsed->i % 60;
    }

    $show_total_time = '';
    if($num_lines !== 0) 
    {
        $show_total_time = $total_hours . ' h ' . $total_minutes . ' min';
    }
    echo d_td($show_total_time);

    if ($_SESSION['ds_term_intervention_value1']) 
    { 
        echo d_tr();
        $v1_text = '<strong>Total ' . $_SESSION['ds_term_intervention_value1'] . '</strong>';
        echo d_td_unfiltered($v1_text);  
        
        $total_value1 = 0;
        for($i = 0; $i < $num_lines; $i++)
        {
            $total_value1 += $intervention_lines[$i]['value1'];
        }
        echo d_td($total_value1, 'int');
    }

    if ($_SESSION['ds_term_intervention_value2']) 
    { 
        echo d_tr();
        $v2_text = '<strong>Total ' . $_SESSION['ds_term_intervention_value2'] . '</strong>';
        echo d_td_unfiltered($v2_text);  
        
        $total_value2 = 0;
        for($i = 0; $i < $num_lines; $i++)
        {
            $total_value2 += $intervention_lines[$i]['value2'];
        }
        echo d_td($total_value2, 'int');
    } 

    if ($_SESSION['ds_term_intervention_value3']) 
    { 
        echo d_tr();
        $v3_text = '<strong>Total ' . $_SESSION['ds_term_intervention_value3'] . '</strong>';
        echo d_td_unfiltered($v3_text);  
        
        $total_value3 = 0;
        for($i = 0; $i < $num_lines; $i++)
        {
            $total_value3 += $intervention_lines[$i]['value3'];
        }
        echo d_td($total_value3, 'int');
    } 

    if ($_SESSION['ds_term_intervention_value4']) 
    { 
        echo d_tr();
        $v4_text = '<strong>Total ' . $_SESSION['ds_term_intervention_value4'] . '</strong>';
        echo d_td_unfiltered($v4_text);  
        
        $total_value4 = 0;
        for($i = 0; $i < $num_lines; $i++)
        {
            $total_value4 += $intervention_lines[$i]['value4'];
        }
        echo d_td($total_value4, 'int');
    } 
    echo d_table_end(); 
    echo '</div>';
    echo '<br>';

    if(count($intervention_lines) === 0) 
    {
        echo '<h3>Pas de lignes</h3>';
    } 
    else 
    { 
        echo '<h3>Lignes</h3>';
        echo d_table('report'); 
        echo d_tr();    
        echo d_th('Produit'); 
        echo d_th('Employé'); 
        echo d_th('Heure début'); 
        echo d_th('Heure fin');

        if($_SESSION['ds_term_interventionfield1']) 
        { 
            echo d_th($_SESSION['ds_term_interventionfield1']); 
        }
        if($_SESSION['ds_term_interventionfield2']) { 
            echo d_th($_SESSION['ds_term_interventionfield2']); 
        } 
        if($_SESSION['ds_term_interventionfield3']) { 
            echo d_th($_SESSION['ds_term_interventionfield3']);
        } 
        if($_SESSION['ds_term_interventionfield4']) 
        { 
            echo d_th($_SESSION['ds_term_interventionfield4']);
        } 
        if($_SESSION['ds_term_intervention_tag1']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_tag1']);
        } 
        if($_SESSION['ds_term_intervention_tag2']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_tag2']);
        } 
        if($_SESSION['ds_term_intervention_value1']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_value1']);
        } 
        if($_SESSION['ds_term_intervention_value2']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_value2']);
        } 
        if($_SESSION['ds_term_intervention_value3']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_value3']);
        } 
        if($_SESSION['ds_term_intervention_value4']) 
        { 
            echo d_th($_SESSION['ds_term_intervention_value4']);
        } 
        
        for ($i = 0; $i < $num_lines; $i++) 
        { 
            echo d_tr();

            $product_name = '';
            $query = 'select productname from product where productid=?';
            $query_prm = array($intervention_lines[$i]['productid']); 
            require('inc/doquery.php');
            if($num_results === 1) { $product_name = $query_result[0]['productname']; }
            echo d_td($product_name);

            $full_name = '';
            $query = 'select employeename, employeefirstname from employee where employeeid=?';
            $query_prm = array($intervention_lines[$i]['employeeid']); 
            require('inc/doquery.php');
            if($num_results === 1) 
            { 
                $full_name = $query_result[0]['employeename'];
                $full_name .= ', ';
                $full_name .= $query_result[0]['employeefirstname']; 
            }
            echo d_td($full_name);

            $time_start = '';
            if($intervention_lines[$i]['timestart'] !== '00:00:00')
            {
                $time_start = substr($intervention_lines[$i]['timestart'], 0, 5);
            }
            echo d_td($time_start);

            $time_end = '';
            if($intervention_lines[$i]['timeend'] !== '00:00:00')
            {
                $time_end = substr($intervention_lines[$i]['timeend'], 0, 5);
            }
            echo d_td($time_end);

            $field1 = '';
            if($_SESSION['ds_term_interventionfield1']) 
            {
                $field1 = $intervention_lines[$i]['field1'];
            }
            echo d_td($field1);

            $field2 = '';
            if($_SESSION['ds_term_interventionfield2']) 
            {
                $field2 = $intervention_lines[$i]['field2'];
            }
            echo d_td($field2);

            $field3 = '';
            if($_SESSION['ds_term_interventionfield3']) 
            {
                $field3 = $intervention_lines[$i]['field3'];
            }
            echo d_td($field3);

            $field4 = '';
            if($_SESSION['ds_term_interventionfield4']) 
            {
                $field4 = $intervention_lines[$i]['field4'];
            }
            echo d_td($field4);

            $tag1 = '';
            if($_SESSION['ds_term_intervention_tag1']) 
            {
                $query = 'select interventionitemtag1name from interventionitemtag1 where 
                interventionitemtag1id=?';
                $query_prm = array($intervention_lines[$i]['interventiontagid']);
                require('inc/doquery.php');

                if($num_results === 1) { $tag1 = $query_result[0]['interventionitemtag1name']; }
            }
            echo d_td($tag1);

            $tag2 = '';
            if($_SESSION['ds_term_intervention_tag2']) 
            {
                $query = 'select interventionitemtag2name from interventionitemtag2 where 
                interventionitemtag2id=?';
                $query_prm = array($intervention_lines[$i]['interventiontagid2']);
                require('inc/doquery.php');

                if($num_results === 1) { $tag2 = $query_result[0]['interventionitemtag2name']; }
            }
            echo d_td($tag2);

            $value1 = 0;
            if($_SESSION['ds_term_intervention_value1']) 
            {
                $value1 = $intervention_lines[$i]['value1'];
            }
            echo d_td_unfiltered($value1, 'int');

            $value2 = 0;
            if($_SESSION['ds_term_intervention_value2']) 
            {
                $value2 = $intervention_lines[$i]['value2'];
            }
            echo d_td_unfiltered($value2, 'int');

            $value3 = 0;
            if($_SESSION['ds_term_intervention_value3']) 
            {
                $value3 = $intervention_lines[$i]['value3'];
            }
            echo d_td_unfiltered($value3, 'int');

            $value4 = 0;
            if($_SESSION['ds_term_intervention_value4']) 
            {
                $value4 = $intervention_lines[$i]['value4'];
            }
            echo d_td_unfiltered($value4, 'int');
        }
        echo d_table_end();
    } 
?>