<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  / _ \
\_\(_)/_/
 _//o\\_    Delicious new calculator page for direct connect auto transport using the new rateengine ship.cars API
  /   \
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body onload="SizeDown();">
<div class="form">
  <center>
    <h2 class="pageHeading" style="color: white; width: 100%;">DCAT Calculator</h2>
    <label id="label">Pickup Zip:</label><input type="text" name="fZip" class="t">
  <br>
    <label id="label">Delivery Zip:</label><input type="text" name="tZip" class="t">
  <br>
    <label id="label">Vehicle Make:</label>
      <select type="text" name="make" class="t2" id='makes' onchange="popModels()" >
        <option disabled selected value> -- select an option -- </option>
      </select>
  <br>
    <label id="label">Vehicle Model:</label>
      <select type="text" name="model" class="t2" id='models' onchange="originalModel()">
      </select>
  <br>
      <label id="label">Original Model:</label>
      <select type="text" name="OGmodel" class="t2" id='OGmodels' value="Integra"></select>
  <br>
    <label id="label">Year:</label><input type="text" name="year" class="t">
  <br>
    <label id="label">Est. Shipping date:</label><input type="text" id="datepicker" class="t">
  <br>
    <label id="label">Enclosed:</label>
      <input type="radio" name="enclosed" value="true"><label id="label">Yes</label>
      <input type="radio" name="enclosed" value="false" checked><label id="label">No</label>
  <br>
      <label id="label1">Running:</label>
      <input type="radio" name="running" value="true" checked><label id="label">Yes</label>
      <input type="radio" name="running" value="false"><label id="label">No</label>
  <br>
</center>
    <center>
      <button id="submitButton" type="submit" name="submit" onclick="calculate()">Calculate</button>
    </center>
    <label id="label">Price:</label>
      <input type="text" id="price" name="" value="" class="t" readonly>
</div>
</body>
<!--
////////////////////////////////////////////////////////////////////////////////////////
                                        css and styles
///////////////////////////////////////////////////////////////////////////////////////
-->
<style>
body {
    font-family: Roboto Light !important;
}

.form {
  background:#2C92C5;
  padding: 35px;
  max-width:245px !important;
  margin:10px auto;
  border-radius:4px;
  box-shadow:0 2px 8px 2px #888888;
  align-items:left;
  margin-left: 2px !important;
}

#label{
    color:white;
    flex: 1;
    text-align: right;
    margin: 1px 8px 0 0;
    padding: 2px 0;
}

#label1{
    color:white;
    flex: 1;
    text-align: right;
    margin: 1px 8px 0 0;
    padding: 2px 0;
}

.t{
  padding: .6rem 1rem;
  font-size: 1.0rem;
  border: solid 1px #eee;
  background: #fff;
  width: 100%;
  border-radius:4px;
  height: 29px !important;
}
.t2{
  padding: .6rem 1rem;
  font-size: 1.0rem;
  border: solid 1px #eee;
  background: #fff;
  width: 100%;
  border-radius:4px;
  height: 39px !important;
}

#radio{
    color:white;
    flex: 1;
}

#submitButton{
    background-color: white;
    color: #2C92C5;
    border-radius: 4px;
    height: 30px;
    width: 100px;
    margin-top: 3px !important;
}

.pageHeading{
  margin-top: 0px !important
}
</style>


<!--
////////////////////////////////////////////////////////////////////////////////////////
                                     functions and scripts
///////////////////////////////////////////////////////////////////////////////////////
-->
<script TYPE="text/javascript" LANGUAGE="javascript">

  function SizeDown(){
    window.resizeTo(340,700);
    window.focus();
  }

//date picker
$(function() {
  var format = {dateFormat: "yy-mm-dd"};
    $( "#datepicker" ).datepicker(format);
});

//get makes
jQuery(document).ready( function() {
  $makes = $('#makes');
    $.getJSON("./resources/views/makes.json", function(data)
    {
        $.each(data, function(key,value){
        $makes.append("<option>" + value.make + "</option>");
        });
    });
});

//clearing the select boxes
function clearBothModels(){
  document.getElementById('models').options.length = 0;
  document.getElementById('OGmodels').options.length = 0;
}

function clearOriginalModel(){
  document.getElementById('OGmodels').options.length = 0;
}

//gets models asoociated with makes-->
function popModels(){
  clearBothModels();
  $models = $('#models');
  $models.append("<option disabled selected value> -- select an option -- </option>")
  $makes = $('#makes').val();

  $.getJSON("./resources/views/models.json", function(data){
  var uniqueModels = [];
    for(i = 0; i< data.length; i++){
      if(data[i].make == $makes){
        if(uniqueModels.indexOf(data[i].model) === -1){
          uniqueModels.push(data[i].model);
        }
      }
    }

    for(i = 0; i< uniqueModels.length; i++){
      $models.append("<option>" + uniqueModels[i] + "</option>");
    }
  });
}

//get original model associated with model
function originalModel(){
  clearOriginalModel();
  $OGmodels = $('#OGmodels');
  $model = $('#models').val();
  $.getJSON("./resources/views/models.json", function(data){
  var uniqueModels = [];
    for(i = 0; i< data.length; i++){
      if(data[i].model == $model){
        if(uniqueModels.indexOf(data[i].original_model) === -1){
          uniqueModels.push(data[i].original_model);
        }
      }
    }

    for(i = 0; i< uniqueModels.length; i++){
      $OGmodels.append("<option>" + uniqueModels[i] + "</option>");
    }
  });
}

function calculate(){
  //form inputs
  var fZip = $('[name="fZip"]').val();
  var tZip = $('[name="tZip"]').val();
  var make = $('[name="make"]').val();
  var model = $('[name="model"]').val();
  var OGmodel = $('[name="OGmodel"]').val();
  var year = $('[name="year"]').val();
  var date = $('#datepicker').val() + "T00:00:00Z";
  var enclosed = $("input[name='enclosed']:checked").val();
  var running = $("input[name='running']:checked").val();

  var jsonData ={
   "data":{
      "zip": fZip,
      "delivery":tZip,
      "make":make,
      "model":model,
      "OGmodel":OGmodel,
      "year":year,
      "shipping":date,
      "enclosed":enclosed,
      "running":running
      }
    };



  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
  });

  $.ajax({
    type: 'POST',
    url: '/calc',
    dataType: 'json',
    data: jsonData,
    statusCode: {
        500: function (response) {
           $('#price').val('Server Error');
        },
        200: function (response) {
           $('#price').val('$'+ response);
        }
    },
    success: function (html) {
    var response = $.parseJSON(html);
    //displays price
      $('#price').val('$'+ response);
    },
    error: function (html){
    	$('#price').val('no');
    }
  });
}
</script>







