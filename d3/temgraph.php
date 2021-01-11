<script type="text/javascript" src="d3/d3.js"></script>
<script type="text/javascript" src="d3/d3.min.js"></script>

<?php
require('inc/func_planning.php');

function getrandomcolor($j)
{
  $mycolorcodeA[0] = '#3182bd';
  $mycolorcodeA[1] = '#6baed6';
  $mycolorcodeA[2] = '#9ecae1';
  $mycolorcodeA[3] = '#c6dbef';
  $mycolorcodeA[4] = '#e6550d';
  $mycolorcodeA[5] = '#fd8d3c';
  $mycolorcodeA[6] = '#fdae6b';
  $mycolorcodeA[7] = '#fdd0a2';
  $mycolorcodeA[8] = '#31a354';
  $mycolorcodeA[9] = '#74c476';
  $mycolorcodeA[10] = '#a1d99b';
  $mycolorcodeA[11] = '#c7e9c0';
  $mycolorcodeA[12] = '#756bb1';
  $mycolorcodeA[13] = '#9e9ac8';
  $mycolorcodeA[14] = '#bcbddc';
  $mycolorcodeA[15] = '#dadaeb';
  $mycolorcodeA[16] = '#636363';
  $mycolorcodeA[17] = '#969696';
  $mycolorcodeA[18] = '#bdbdbd';
  $mycolorcodeA[19] = '#d9d9d9';

  $r = $j;  
  if ($j > 19) 
  { 
    $r = $j%20;
  }
  
  if ($r >= 0)
  {
    $colorcode = $mycolorcodeA[$r];
  }
  else
  {
    $colorcode = '#ceced6';  
  }
  return $colorcode;
}
?>
<style>
.label {
  font: 16px sans-serif;
}

.bar:hover {
  fill: brown;
}

.axis {
  font: 10px sans-serif;
}

.axis path,
.axis line {
  fill: #c1c1c1;
  stroke: #c1c1c1;
  shape-rendering: crispEdges;
}


.slice path {
  stroke: #fff;
}

.slice text {
  font: 10px sans-serif;
  text-anchor: middle;
}   

.sgaxis path {
    fill: none;
    stroke: #777;
    shape-rendering: crispEdges;
}
.sgaxis text {
    font-family: Lato;
    font-size: 13px;
}

</style>

<?php
/******************************************** FORMAT DATA FOR GRAPH************************************************
*******************************************************************************************************************
***IN: $dataNamesA = array of data names 
****************in vertical bar will be X axis 
****************in horizontal bar will be Y axis
****************in pie chart will represent each part of pie
****** $dataValuesA = array of data values
****************in vertical bar will be Y axis 
****************in horizontal bar will be X axis
****************in pie chart will represent the angle of the pie part
***OUT: data string to be used in call of horizontalbar, verticalbar, piechartandlegend, piechart,multilinechart and multiinedatechart
********************************************************************************************************************/
function d_formatdataforgraph($dataNamesA,$dataValuesA)
{
  $testData = '[';
  $i = 0;
  $itotal = count($dataValuesA);
  foreach($dataValuesA as $key=>$value)
  {
    $i++;
    $testData .= '{valueX:\'' . addslashes($dataNamesA[$key]) . '\',valueY:' . $value .'}';
    if ($i < $itotal)
    {
      $testData .= ',';
    }
  }
  $testData .= ']';
  return $testData;
}


/******************************************** FORMAT NAMES FOR MULTILINES GRAPH************************************************
*******************************************************************************************************************
***IN: $nameA = array of names of each line
***OUT: data string to be used in multiline graph
********************************************************************************************************************/
function d_formatnamesformultilinegraph($namesA,$maxlines)
{
  $names = '[';
  $i = 0;
  $itotal = count($namesA);  
  foreach($namesA as $key=>$value)
  {
    $i++;
    if($i <= $maxlines)
    {
      $names .= '{name:\'' . addslashes($value);
      if ($i == $maxlines) { $names .= '....'; }
      $names .= '\'}';
      if ($i < $itotal)
      {
        $names .= ',';
      }
    }
    else
    {
      break;
    }
  }
  $names .= ']';
  return $names;
}

?>
<?php
/******************************************** CALL SIMPLE GRAPH************************************************
*******************************************************************************************************************
***IN: $graphtype = verticalbar OR horizontalbar OR piechart OR piechartandlegend
*******$title = graph title
*******$dataNamesA = array of data names 
****************in verticalbar graph will be X axis 
****************in horizontalbar graph will be Y axis
****************in piechart graph will represent each part of pie
****** $dataValuesA = array of data values
****************in verticalbar graph will be Y axis 
****************in horizontalbar graph will be X axis
****************in piechart graph will represent the angle of the pie part
***OUT : the graph !
********************************************************************************************************************/
function d_callsimplegraph($graphtype,$title,$dataNamesA,$dataValuesA)
{
  $dataset = d_formatdataforgraph($dataNamesA,$dataValuesA);
  $i = rand();
  echo '<div id="' . $graphtype . $i . '"></div>';
  echo '<script type="text/javascript">';
  if ($graphtype == 'piechart' || $graphtype == 'piechartandlegend')
  {
    echo $graphtype . '(' . $dataset . ',"' . addslashes($title) . '");';  
  }
  else
  {
    echo $graphtype . '("#' . $graphtype . $i . '",' . $dataset . ',"' . addslashes($title) . '");';    
  }
  echo '</script>';
}

/******************************************** CALL DOUBLE GRAPH************************************************
*******************************************************************************************************************
***IN: $graphtype = doublehorizontalbar
*******$title = graph title
*******$dataNamesA = array of data names 
****************in verticalbar graph will be X axis 
****************in horizontalbar graph will be Y axis
****************in piechart graph will represent each part of pie
****** $dataValuesA = array of data values
****************in verticalbar graph will be Y axis 
****************in horizontalbar graph will be X axis
****************in piechart graph will represent the angle of the pie part
***OUT : the graph !
********************************************************************************************************************/
function d_calldoublegraph($data)//($graphtype,$title,$dataNamesA,$dataValues1A,$dataValues2A)
{
  /*$dataset1 = d_formatdataforgraph($dataNamesA,$dataValues1A);
  $dataset2 = d_formatdataforgraph($dataNamesA,$dataValues2A);*/
  $i = rand();
  echo '<div id="' . $graphtype . $i . '"></div>';
  echo '<script type="text/javascript">';
  //echo $graphtype . '("#' . $graphtype . $i . '",' . $dataset1 . ',' . $dataset2 . ',"' . addslashes($title) . '");';
  echo 'doubleverticalbar('.$data.')';
  echo '</script>';
}
/******************************************** CALL MULTILINE GRAPH************************************************
*******************************************************************************************************************
***IN: $title = title graph
*******$isdate = 0 if X AXIS is values / $isdate = 1 if X AXIS is dates
*******$interpolate = 1 if the graph have to be interpolated / $interpolate = 0 if not
*******$nameA = array of names of each line (how many names as arrays $dataA)
*******$valuesA = array of values/dates for X axis
*******THIS ONE IS NEEDED*********************************
*******$data1A = array of values for Y axis 
*******THESE ONES ARE OPTIONAL*********************************
*******$data2A = array of values for Y axis
.....
*******$datanA = array of values for Y axis
***OUT : the graph !
********************************************************************************************************************/
function d_callmultilinegraph()
{
  #max lines for multilines graph;
  $MAX_LINES = 20;
  $DATA_ARG_RANK = 5;
  $numargs = func_num_args();
  $title = addslashes(func_get_arg(0));  
  $isdate = func_get_arg(1);
  $interpolate = func_get_arg(2);
  $namesA = func_get_arg(3);
  $names = d_formatnamesformultilinegraph($namesA,$MAX_LINES);
  $valuesA = func_get_arg(4);
  #datum is array()()
  $datum = func_get_arg(5);
  $numdatum = count($datum);
  if($numdatum > $MAX_LINES) { $numdatum = $MAX_LINES; }
  for($n=0;$n<$numdatum;$n++)
  {  
    $dataA = $datum[$n];
    $datumA[$n] = d_formatdataforgraph($valuesA,$dataA);
  }

  $i = rand();
  echo '<div id="' . $graphtype . $i . '"></div>';
  echo '<script type="text/javascript">';
  if ($isdate == 0)
  {
    echo 'multilineschart(' . $names . ',"' . $title . '",' . $interpolate;
  }
  else
  {
    echo 'multilinesdatechart(' . $names . ',"' . $title . '",' . $interpolate;
  }
  for($n=0;$n<$numdatum;$n++)
  {
    echo ',' . $datumA[$n] ;
  }
  echo ');';
  echo '</script>';
}

?>
<?php
/*************************************************************************************************************
**********************************************HORIZONTAL BAR*******************************************************
*******************************************************************************************************************
To Call horizontalbar
- insert a div with id 
- format dataset {valueX: XXXX , valueY: YYYY} 
- call horizontalbar(id,dataset, title) 
********************************************************************************************************************/?>
<script type="text/javascript">
function horizontalbar(id,dataset, title)
{
  var outerWidth = 1200;
  var outerHeight = 600;
  var margin = { left: 165, top: 90, right: 90, bottom: 90 };
  var barPadding = 0.2;
  var barPaddingOuter = 0.1;
  color = d3.scale.category20c() ;

  var xAxisLabelOffset = 75;
  var innerWidth  = outerWidth  - margin.left - margin.right;
  var innerHeight = outerHeight - margin.top  - margin.bottom;
  
  //transform y data in time
  /*for(idata=0;idata<dataset.length;idata++)
  {
    dataset[idata].valueY = d3.time.format("%Y-%m-%d").parse(dataset[idata].valueY);     
  }*/
  //set data
  d3.select(id)
    .selectAll("div")
    .data(dataset);
  
  //size of svg
  var svg = d3.select("body").append("svg")
    .attr("width",  outerWidth)
    .attr("height", outerHeight);
    
  var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
  //graph axis
  var xAxisG = g.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + margin.top + ")")
    
  var xAxisLabel = xAxisG.append("text")
    .style("text-anchor", "start")
    .attr("x","-" + margin.left)
    .attr("y", 0)
    .attr("class", "label")
    .text(title);
    
  var yAxisG = g.append("g")
    .attr("class", "y axis");
    
  var xScale = d3.scale.linear().range([0, innerWidth]);
  var yScale = d3.scale.ordinal().rangeRoundBands([0, innerHeight], barPadding, barPaddingOuter);
  
  var xAxis = d3.svg.axis().scale(xScale).orient("top")
    .ticks(10)
    .tickFormat(d3.format("s"))
    .outerTickSize(0);
    
  var yAxis = d3.svg.axis().scale(yScale).orient("left")
    .outerTickSize(0);

  xScale.domain([0, d3.max(dataset, function (d){ return d.valueY; })]);
  yScale.domain( dataset.map( function (d){ return d.valueX; }));
  
  xAxisG.call(xAxis) .attr("transform", "translate(0, 0)");
  yAxisG.call(yAxis);
  
  //bars
  var bars = g.selectAll("rect").data(dataset);
  var legend = g.selectAll("legend").data(dataset);
  
  bars.enter().append("rect")
    .attr("height", yScale.rangeBand());
    
  bars
    .attr("x", 0)
    .attr("y",     function (d){ return yScale(d.valueX); })
    .attr("width", function (d){ return xScale(d.valueY); })
    .attr("fill", function(d, i) { return color(i); })
    .attr("transform", "translate(5,0)");
    
  //legend
  legend.enter().append("text")
    .style("text-anchor", "start")
    .attr("x",  function (d){ return xScale(d.valueY) + 5;})
    .attr("y", function (d){ return yScale(d.valueX) + yScale.rangeBand()/2 + 2; } )         
    .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
    .attr("font-size","10px")
    .text(function (d){ return d.valueY});
 }
</script> 


<?php
/*************************************************************************************************************
**********************************************DOUBLE HORIZONTAL BAR*******************************************************
*******************************************************************************************************************
To Call doublehorizontalbar
- insert a div with id 
- format dataset1 {valueX: XXXX , valueY: YYYY} and dataset2 {valueX: XXXX , valueY: YYYY} 
- call doublehorizontalbar(id,dataset, title) 
********************************************************************************************************************/?>
<script type="text/javascript">
/*function doublehorizontalbar(id,dataset1, dataset2, title)
{
  var outerWidth = 1200;
  var outerHeight = 600;
  var margin = { left: 165, top: 90, right: 90, bottom: 90 };
  var barPadding = 0.2;
  var barPaddingOuter = 0.1;
  color = d3.scale.category20c() ;

  var xAxisLabelOffset = 75;
  var innerWidth  = outerWidth  - margin.left - margin.right;
  var innerHeight = outerHeight - margin.top  - margin.bottom;
  
  //FIRST BARS
  //set data
  d3.select(id)
    .selectAll("div")
    .data(dataset1);
  
  //size of svg
  var svg = d3.select("body").append("svg")
    .attr("width",  outerWidth)
    .attr("height", outerHeight);
    
  var g = svg.append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
  //graph axis
  var xAxisG = g.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + margin.top + ")")
    
  var xAxisLabel = xAxisG.append("text")
    .style("text-anchor", "start")
    .attr("x","-" + margin.left)
    .attr("y", 0)
    .attr("class", "label")
    .text(title);
    
  var yAxisG = g.append("g")
    .attr("class", "y axis");
    
  var xScale = d3.scale.linear().range([0, innerWidth]);
  var yScale = d3.scale.ordinal().rangeRoundBands([0, innerHeight], barPadding, barPaddingOuter);
  var y1Scale = d3.scale.ordinal();
  
  var xAxis = d3.svg.axis().scale(xScale).orient("top")
    .ticks(10)
    .tickFormat(d3.format("s"))
    .outerTickSize(0);
    
  var yAxis = d3.svg.axis().scale(yScale).orient("left")
    .outerTickSize(0);

  xScale.domain([0, d3.max(dataset1, function (d){ return d.valueY; })]);
  yScale.domain( dataset1.map( function (d){ return d.valueX; }));
  y1Scale.domain( dataset.map( function (d){ return d.valueX; }));
  x0.domain(data.map(function(d) { return d.State; }));
  x1.domain(ageNames).rangeRoundBands([0, x0.rangeBand()]);  
  
  xAxisG.call(xAxis) .attr("transform", "translate(0, 0)");
  yAxisG.call(yAxis);
  
  //bars
  var bars = g.selectAll("rect").data(dataset1);
  var legend = g.selectAll("legend").data(dataset1);
   
  bars.enter().append("rect")
    .attr("height", yScale.rangeBand());
    
  bars
    .attr("x", 0)
    .attr("y",     function (d){ return yScale(d.valueX); })
    .attr("width", function (d){ return xScale(d.valueY); })
    .attr("fill", function(d, i) { return color(i); })
    .attr("transform", "translate(5,0)");
  
  //SECOND BARS   
  //bars
  var bars = g.selectAll("rect").data(dataset2);
  var legend = g.selectAll("legend").data(dataset2);
  
  bars.enter().append("rect")
    .attr("height", yScale.rangeBand()+20);
    
  bars
    .attr("x", 0)
    .attr("y",     function (d){ return yScale(d.valueX); })
    .attr("width", function (d){ return xScale(d.valueY); })
    .attr("fill", function(d, i) { return color(i); })
    .attr("transform", "translate(5,0)");
    
  //legend
  legend.enter().append("text")
    .style("text-anchor", "start")
    .attr("x",  function (d){ return xScale(d.valueY) + 5;})
    .attr("y", function (d){ return yScale(d.valueX) + yScale.rangeBand()/2 + 2; } )         
    .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
    .attr("font-size","10px")
    .text(function (d){ return d.valueY});
 }*/
</script>
 


<?php
/*************************************************************************************************************
**********************************************VERTICAL BAR*********************************************************
*******************************************************************************************************************
To Call verticalbar:
- insert a div with id 
- format dataset {valueX: XXXX , valueY: YYYY}
- call verticalbar(id,dataset, title)
********************************************************************************************************************/?>
<script type="text/javascript">
function verticalbar(id, dataset,title){
  var margin = {top: 40, right: 20, bottom: 30, left: 40},
      width = 1200 - margin.left - margin.right,
      height = 600 - margin.top - margin.bottom
      color = d3.scale.category20c() ;

  //determine scale
  var x = d3.scale.ordinal()
      .rangeRoundBands([0, width], .1);

  var y = d3.scale.linear()
      .range([height, 0]);

  //graph axis
  var xAxis = d3.svg.axis()
      .scale(x)
      .orient("bottom")
      .outerTickSize(0);

  var yAxis = d3.svg.axis()
      .scale(y)
      .orient("left")
      .ticks(10)
      .tickFormat(d3.format("s"))
      .outerTickSize(0);

  //insert chart 
  var chart = d3.select("body").append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  //put dataset
  d3.select(id)
    .selectAll("div")
      .data(dataset);
    
  //determine domains
  x.domain(dataset.map(function(d) { return d.valueX; }));
  y.domain([0, d3.max(dataset, function(d) { return d.valueY; })]);

  chart.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .style("font-size", "8px")      
      .call(xAxis);

  //define legends
  chart.append("g")
      .attr("class", "y axis")
      .call(yAxis)    
    .append("text")
      .attr("class", "label")    
      .attr("x", margin.left -10)
      .attr("y",-15)
      .style("text-anchor", "middle")
      .text( title);

  //define bars
  chart.selectAll(".bar")
      .data(dataset)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.valueX); })
      .attr("width", x.rangeBand())
      .attr("fill", function(d, i) { return color(i); })
      .attr("y", function(d) { return y(d.valueY); })
      .attr("height", function(d) { return height - y(d.valueY); });
     
  chart.selectAll(".legend")
      .data(dataset)
      .enter().append("text")
      .style("text-anchor", "middle")
      .attr("x",   function(d) { return x(d.valueX) + x.rangeBand()/2; })
      .attr("y", function(d) { return y(d.valueY) -5; } )         
      .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
      .attr("font-size","10px")
      .text(function (d){ return d.valueY});

}
</script>

<?php
/*************************************************************************************************************
**********************************************PIE CHART AND LEGEND*************************************************
*******************************************************************************************************************
To Call pie chart:
- format dataset {valueX: XXXX , valueY: YYYY}
- call piechartandlegend(data,title)
********************************************************************************************************************/?>

<script type="text/javascript">
function piechartandlegend(data,title)
{
  var w = 1200,                        //width
  h = 800,                            //height
  r = 300,                            //radius
  color = d3.scale.category20c()     //builtin range of colors
  t = r +100;
  
  var vis = d3.select("body")
      .append("svg:svg")              //create the SVG element inside the <body>
      .data([data])                   //associate our data with the document
          .attr("width", w)           //set the width and height of our visualization (these will be attributes of the <svg> tag
          .attr("height", h)
      .append("svg:g")                //make a group to hold our pie chart
          .attr("transform", "translate(" + t + "," + r + ")") ;   //move the center of the pie chart from 0, 0 to radius, radius
          
  var arc = d3.svg.arc()              //this will create <path> elements for us using arc data
      .outerRadius(r);
      
  var pie = d3.layout.pie()           //this will create arc data for us given a list of values
      .value(function(d) { return d.valueY; });    //we must tell it out to access the value of each element in our data array
      
  var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
      .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
      .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
          .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
              .attr("class", "slice");    //allow us to style things in the slices (like text)
              
  arcs.append("svg:path")
    .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
    .attr("d", arc);                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function
    
  var total = d3.sum(data, function(d) { return  d.valueY});
  var percentageFormat = d3.format("%");
  
  arcs.append("svg:text")                                     //add a label to each slice
    .attr("transform", function(d) {                    //set the label's origin to the center of the arc
    //we have to make sure to set these before calling arc.centroid
    d.innerRadius = 0;
    d.outerRadius = r;
    return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
    })
    .attr("text-anchor", "left")                          //center the text on it's origin
    .text(function(d, i) { return  percentageFormat(data[i].valueY / total) ; });        //get the label from our original data array    
          
  var legendRectSize = 10;
  var legendSpacing = 3;
   
  //title
  vis.append("text")
      .attr("class", "label")    
      .attr("x", -300)
      .attr("y", -250)
      .style("text-anchor", "middle")
      .text( title); 
      
  //legend
  var legend = vis.selectAll('.legend')
    .data(data)
    .enter()
    .append('g')
    .attr('class', 'legend')
    .attr('transform', function(d, i) {
      var height = legendRectSize + legendSpacing;
      var offset =  height * data.length / 2;
      var horz = t + legendRectSize;
      var vert = i * height - offset;
      return 'translate(' + horz + ',' + vert + ')';
    });
    
  legend.append('rect')
    .attr('width', legendRectSize)
    .attr('height', legendRectSize)
    .style('fill', function(d, i) { return color(i); })
    .style('stroke', "#fff");
    
  legend.append('text')
  .attr('x',  legendRectSize + legendSpacing)
  .attr('y', legendRectSize - legendSpacing)
  .attr('fill',function(d, i) { return color(i); })
  .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
  .attr("font-size","10px")
  .text(function(d, i) { return data[i].valueX + " (" + data[i].valueY  + ")"; });
     
}

</script>

<?php
/*************************************************************************************************************
**********************************************PIE CHART ***********************************************************
*******************************************************************************************************************
To Call pie chart:
- format dataset {valueX: XXXX , valueY: YYYY}
- call piechart(data,title): no lgend but valueX (valueY) in the pie
********************************************************************************************************************/?>
<script type="text/javascript">
function piechart(data,title)
{
  var w = 1200,                        //width
  h = 800,                            //height
  r = 300,                            //radius
  color = d3.scale.category20c()     //builtin range of colors
  t = r +100;
  
  var vis = d3.select("body")
      .append("svg:svg")              //create the SVG element inside the <body>
      .data([data])                   //associate our data with the document
          .attr("width", w)           //set the width and height of our visualization (these will be attributes of the <svg> tag
          .attr("height", h)
      .append("svg:g")                //make a group to hold our pie chart
          .attr("transform", "translate(" + t + "," + r + ")")    //move the center of the pie chart from 0, 0 to radius, radius
  var arc = d3.svg.arc()              //this will create <path> elements for us using arc data
      .outerRadius(r);
  var pie = d3.layout.pie()           //this will create arc data for us given a list of values
      .value(function(d) { return d.valueY; });    //we must tell it out to access the value of each element in our data array
  var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
      .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
      .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
          .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
              .attr("class", "slice");    //allow us to style things in the slices (like text)
  arcs.append("svg:path")
          .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
          .attr("d", arc);                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function
  arcs.append("svg:text")                                     //add a label to each slice
          .attr("transform", function(d) {                    //set the label's origin to the center of the arc
          //we have to make sure to set these before calling arc.centroid
          d.innerRadius = 0;
          d.outerRadius = r;
          return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
      })
      .attr("text-anchor", "left")                          //center the text on it's origin
      .text(function(d, i) { return data[i].valueX + " (" + data[i].valueY  + ")"; });        //get the label from our original data array
          
  vis.append("text")
      .attr("class", "label")    
      .attr("x", -300)
      .attr("y", -250)
      .style("text-anchor", "middle")
      .text( title);          
            
 }
</script>

<?php
/*************************************************************************************************************
**********************************************MULTILINES CHART*****************************************************
*******************************************************************************************************************
To Call simplegraph:
- format dataset [{valueX: A , valueY: a},{valueX: B , valueY: b},{valueX: C , valueY: c}} 
with as much {} as needed and valueX format and valueY format as you want
- call multilineschart(names,title,interpolate,data,data1,data2,data3,...) interpolate: 0/1
********************************************************************************************************************/?>
<script type="text/javascript">
function multilineschart()
{
  var   width = 1000,
        height = 500,
        margin = { top: 20, right: 200, bottom: 20, left: 50 },
        NUM_ARGUMENTS_BEFORE_DATA = 3;
        
  var color = d3.scale.category10();
  var numarguments = arguments.length ; 
  var numdata = numarguments - NUM_ARGUMENTS_BEFORE_DATA;
  var names = arguments[0];
  var title = arguments[1];  
  var interpolate = arguments[2]; 

  //take 1rst dataset to evaluate X domain
  var dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA];
  
  //search min and max of all datalines to determine Y domain
  var min = d3.min(dataline, function(d) { return d.valueY; });
  var max = d3.max(dataline, function(d) { return d.valueY; });
  //graph axis
  for(iline=1;iline<numdata;iline++)
  {  
    dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA+iline]; 
    
    var mintemp = d3.min(dataline, function(d) { return d.valueY; });
    if (mintemp < min) { min = mintemp; }
    var maxtemp = d3.max(dataline, function(d) { return d.valueY; });   
    if (maxtemp > max) { max = maxtemp; }    
  }
  
  //graph axis
  xScale = d3.scale.linear().range([margin.left, width - margin.right]).domain(d3.extent(dataline, function(d) { return d.valueX; }));
  yScale = d3.scale.linear().range([height - margin.top, margin.bottom]).domain([min,max]),
  xAxis = d3.svg.axis()
    .scale(xScale)
    .ticks(10)
    .tickFormat(d3.format("f")),
  yAxis = d3.svg.axis()
    .scale(yScale)
    .orient("left")
    .ticks(10)
    .tickFormat(d3.format("f"));
  
  //insert chart 
  var vis = d3.select("body").append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
      
  vis.append("svg:g")
      .attr("class", "x sgaxis")
      .attr("transform", "translate(0," + (height - margin.bottom) + ")")
      .call(xAxis);
  vis.append("svg:g")
      .attr("class", "y sgaxis")
      .attr("transform", "translate(" + (margin.left) + ",0)")
      .call(yAxis);
    
  //generate lines: interpolated or not
  if (interpolate == 1)
  {
    var lineGen = d3.svg.line()
      .x(function(d) {
          return xScale(d.valueX);
      })
      .y(function(d) {
          return yScale(d.valueY);
      })
      .interpolate("monotone");
  }
  else
  {
    var lineGen = d3.svg.line()
      .x(function(d) {
          return xScale(d.valueX);
      })
      .y(function(d) {
          return yScale(d.valueY);
      });
  }
   
  for(iline=0;iline<numdata;iline++)
  {
    dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA+iline];
    vis.append('svg:path')
        .attr('d', lineGen(dataline))
        .attr('stroke', color(iline) )
        .attr('stroke-width', 2)
        .attr('fill', 'none');
  }
        
  //title
  vis.append("text")
      .attr("class", "label")    
      .attr("x", 0)
      .attr("y", 0)
      .style("text-anchor", "start")
      .text( title); 

  //legend
  var legendRectWidth = 30;
  var legendRectHeight = 5;
  var legendSpacing = 10;
        
  var legend = vis.selectAll('.legend')
    .data(names)
    .enter()
    .append('g')
    .attr('class', 'legend')
    .attr('transform', function(d, i) {
      var h = legendRectHeight + legendSpacing;
      var horz =  width - 80;
      var vert = 20 + i * h;
      return 'translate(' + horz + ',' + vert + ')';
    });
    
  legend.append('rect')
    .attr('width', legendRectWidth)
    .attr('height', legendRectHeight)
    .style('fill', function(d, i) { return color(i); })
    .style('stroke', "#fff");
    
  legend.append('text')
  .attr('x', legendRectWidth + legendSpacing)
  .attr('y', legendRectHeight)
  .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
  .attr("font-size","<?php echo $_SESSION['ds_user_font_size'];?>")
  .style('fill', function(d, i) { return color(i); })
  .text(function(d) { return d.name; });
           
}

</script><?php
/**********************************************************************************************************************
**********************************************MULTILINES DATE CHART******************************************************************
*************************************************************************************************************************************
To Call multilinedatechart:
- format dataset [{valueX: 2001-12-01 , valueY: a},{valueX: 2002-01-01 , valueY: b},{valueX: 2002-02-01 , valueY: c}} 
with as much {} as needed and valueX format = YYYY-mm-dd and valueY format as you want
- call multilinesdatechart(names,title,interpolate,data,data1,data2,data3,...) interpolate: 0/1 
***********************************************************************************************************************************/?>
<script type="text/javascript">
function multilinesdatechart()
{
  var   width = 1000,
        height = 500,
        margin = { top: 20, right: 200, bottom: 20, left: 50 },
        NUM_ARGUMENTS_BEFORE_DATA = 3;
        
  var color = d3.scale.category10();
  var numarguments = arguments.length ; 
  var numdata = numarguments - NUM_ARGUMENTS_BEFORE_DATA;

  var names = arguments[0];
  var title = arguments[1];  
  var interpolate = arguments[2]; 

  //take 1rst dataset to evaluate X domain
  var dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA];
  
  //transform each valueX in date
  for(iline=0;iline<numdata;iline++)
  {
    dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA+iline]; 
    
    for(idata=0;idata<dataline.length;idata++)
    {
      dataline[idata].valueX = d3.time.format("%Y-%m-%d").parse(dataline[idata].valueX);     
    }
  }
    
  //search min and max of all datalines to determine Y domain
  dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA]; 
  var min = d3.min(dataline, function(d) { return d.valueY; });
  var max = d3.max(dataline, function(d) { return d.valueY; });
  //graph axis
  for(iline=1;iline<numdata;iline++)
  {  
    dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA+iline]; 
    
    var mintemp = d3.min(dataline, function(d) { return d.valueY; });
    if (mintemp < min) { min = mintemp; }
    var maxtemp = d3.max(dataline, function(d) { return d.valueY; });   
    if (maxtemp > max) { max = maxtemp; }    
  }
  xScale = d3.time.scale().range([margin.left, width - margin.right]).domain(d3.extent(dataline, function(d) { return d.valueX; }));  
  yScale = d3.scale.linear().range([height - margin.top, margin.bottom]).domain([min,max]),
  xAxis = d3.svg.axis()
    .scale(xScale)
    .ticks(10)
    //.tickFormat(d3.format("f")),
  yAxis = d3.svg.axis()
    .scale(yScale)
    .orient("left")
    .ticks(10)
    .tickFormat(d3.format("f"));
  
  //insert chart 
  var vis = d3.select("body").append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
     
  vis.append("svg:g")
      .attr("class", "x sgaxis")
      .attr("transform", "translate(0," + (height - margin.bottom) + ")")
      .call(xAxis);
  vis.append("svg:g")
      .attr("class", "y sgaxis")
      .attr("transform", "translate(" + (margin.left) + ",0)")
      .call(yAxis);
    
  //generate lines: interpolated or not
  if (interpolate == 1)
  {
    var lineGen = d3.svg.line()
      .x(function(d) {
          return xScale(d.valueX);
      })
      .y(function(d) {
          return yScale(d.valueY);
      })
      .interpolate("monotone");
  }
  else
  {
    var lineGen = d3.svg.line()
      .x(function(d) {
          return xScale(d.valueX);
      })
      .y(function(d) {
          return yScale(d.valueY);
      });
  }

  for(iline=0;iline<numdata;iline++)
  {  
    dataline = arguments[NUM_ARGUMENTS_BEFORE_DATA+iline]; 
    
    vis.append('svg:path')
        .attr('d', lineGen(dataline))
        .attr('stroke', color(iline) )
        .attr('stroke-width', 2)
        .attr('fill', 'none');      
  }
        
  //title
  vis.append("text")
      .attr("class", "label")    
      .attr("x", 0)
      .attr("y", 0)
      .style("text-anchor", "start")
      .text( title); 

  //legend
  var legendRectWidth = 30;
  var legendRectHeight = 5;
  var legendSpacing = 10;
        
  var legend = vis.selectAll('.legend')
    .data(names)
    .enter()
    .append('g')
    .attr('class', 'legend')
    .attr('transform', function(d, i) {
      var h = legendRectHeight + legendSpacing;
      var horz =  width - 80;
      var vert = 20 + i * h;
      return 'translate(' + horz + ',' + vert + ')';
    });
    
  legend.append('rect')
    .attr('width', legendRectWidth)
    .attr('height', legendRectHeight)
    .style('fill', function(d, i) { return color(i); })
    .style('stroke', "#fff");
    
  legend.append('text')
  .attr('x', legendRectWidth + legendSpacing)
  .attr('y', legendRectHeight)
  .attr("font-family","<?php echo $_SESSION['ds_user_font'];?>")   
  .attr("font-size","<?php echo $_SESSION['ds_user_font_size'];?>")    
  .style('fill', function(d, i) { return color(i); })
  .text(function(d) { return d.name; });
           
}

</script>
