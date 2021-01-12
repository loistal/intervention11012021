<?php
    $PA['client'] = 'client';
    $PA['employeemainid'] = 'uint';
    $PA['startdate'] = 'date';
    $PA['enddate'] = 'date';
    $PA['title'] = '';
    $PA['comment'] = '';
    require('inc/readpost.php');

    $build_query = 'select * from intervention';
    $query_prm = array();

    $query_info = array();
    if($startdate !== '') 
    { 
        array_push($query_info, array('interventiondate', $startdate, '>=')); 
    }
    if($enddate !== '') 
    { 
        array_push($query_info, array('interventiondate', $enddate, '<=')); 
    }
    if($clientid !== '') 
    { 
        array_push($query_info, array('interventionclientid', $clientid, '=')); 
    }
    if($employeemainid !== 0) 
    { 
        array_push($query_info, array('interventionemployeeid', $employeemainid, '=')); 
    }
    if($title !== '') 
    { 
        array_push($query_info, array('interventiontitle', $title, '=')); 
    }
    if($comment !== '') 
    { 
        array_push($query_info, array('interventioncomment', $comment, '=')); 
    }

    if(count($query_info) >= 1) { $build_query .= ' where'; }

    $num_fields = count($query_info);
    for($i = 0; $i < $num_fields; $i++)
    {
        $field_name = $query_info[$i][0];
        $field_value = $query_info[$i][1];
        $field_operator = $query_info[$i][2];

        $build_query .= ' ' . $field_name . $field_operator . '?';
        if($i !== ($num_fields - 1)) { $build_query .= ' and'; }
        array_push($query_prm, $field_value);
    }

    $query = $build_query . ' order by interventionid desc';
    require('inc/doquery.php');

    $interventions = $query_result;
    $num_interventions = $num_results;
    for ($i=0; $i < $num_interventions; $i++) 
    { 
        if ($interventions[$i]['interventionemployeeid'] === '0') 
        {
            $interventions[$i]['interventionemployeeid'] = '';
            $interventions[$i]['interventionemployeename'] = '';
        }
        else 
        {
            $query = 'select employeename, employeefirstname from employee where employeeid=?';
            $query_prm = array($interventions[$i]['interventionemployeeid']);
            require('inc/doquery.php');

            if($num_results === 1)
            {
                $interventions[$i]['interventionemployeename'] = $query_result[0]['employeename'];
                $interventions[$i]['interventionemployeename'] .= ', ';
                $interventions[$i]['interventionemployeename'] .= $query_result[0]['employeefirstname'];
            }
        }
        if ($interventions[$i]['interventionclientid'] === '0') 
        {
            $interventions[$i]['interventionclientid'] = '';
            $interventions[$i]['interventionclientname'] = '';
        }
        else 
        {
            $query = 'select clientname from client where clientid=?';
            $query_prm = array($interventions[$i]['interventionclientid']);
            require('inc/doquery.php');

            if($num_results === 1)
            {
                $interventions[$i]['interventionclientname'] = $query_result[0]['clientname'];
            }
        }
    }

    showtitle_new('Interventions');

    if(count($interventions) === 0) 
    {
        echo '<p>Pas de résultats.</p>';
        exit();
    }

    if($startdate !== '') { echo 'Du ' . datefix($startdate); }
    if($startdate !== '' && $enddate !== '') { echo ' jusqu\'au ' . datefix($enddate); }
    elseif($startdate === '' && $enddate !== '') { echo 'Jusqu\'au ' . datefix($enddate); }
    elseif($startdate === '' && $enddate === '') { echo 'Toutes les dates incluses.'; }
    echo '<br><br>';

    echo d_table('report'); 
    echo d_tr();
    echo d_thead();
    echo d_th('Numéro');
    echo d_th('Date');
    echo d_th('Titre');
    echo d_th('Client');
    echo d_th('Employé');
    echo d_th('Commentaire');
    echo d_thead_end();
    
    echo d_tbody();

    for($i = 0; $i < $num_interventions; $i++)
    {
        echo d_tr();
        $intervention_id = $interventions[$i]['interventionid'];
        $link_report = '<a href="reportwindow.php?report=showintervention&amp;';
        $link_report .= 'intervention=' . $intervention_id;
        $link_report .= '" target="_blank">' . $intervention_id . '</a></td>';
        echo d_td_unfiltered($link_report);

        echo d_td($interventions[$i]['interventiondate'], 'date');
        echo d_td($interventions[$i]['interventiontitle']);
        echo d_td($interventions[$i]['interventionclientname']);
        echo d_td($interventions[$i]['interventionemployeename']);

        $comment = $interventions[$i]['interventioncomment'];
        $comment_length = strlen($comment);
        if($comment_length > 50 )
        {
            $comment = substr($comment, 0, 50) . ' ...';
        }
        echo d_td($comment);
    }

    echo d_tbody_end();
    echo d_table_end(); 
?>
