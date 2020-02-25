<!-- salesforce api lead insertion based on form values -->

<?php
$stomach  =  $_POST['yum'];
$firstname = '';
  $content =
    [
      'Phone' => $_POST['yourphone'],
      'Company' => '',
      'Status' => 'New',
      'LastName' => $_POST['yourname'],
      'Email' => $_POST['youremail'],
      'Description' => '',
      'LeadSource' => '',
      'Postal_Code' => '',
      'State' => '',
      'Street' => '',
      'City' => '',
      'Country' => '',
      'Gender' => '',
      'Title' => '',
      'Unable_to_work' => '',
      'yourmessage'=> '',
      'Citizen_of_usa' => '',
      'Worked_paid_taxes' => '',
      'Received_medicare_card' => '',
      'referredfirstname' => '',
      'referredlastname'  => '',
      'referredphone' => '',
      'referredemail' => '',
      'Service_contact_permission' => true,
      'NDA' => ''
    ];

   // conditional values based upon what is entered into the form
   // not all forms contain all the fields so we check if a fields is set but not a empty string or null value
   if(!empty($_POST['zipcode'])){ $content['Postal_Code'] = $_POST['zipcode']; }

   if(!empty($_POST['form'])){ $content['LeadSource'] = $_POST['form']; }

   if(!empty($_POST['yourgender'])){ $content['Gender'] = $_POST['yourgender']; }

   if(!empty($_POST['Title'])){ $content['Title'] = $_POST['Title']; }

   if(!empty($_POST['referredfirstname'])){ $content['referredfirstname'] = $_POST['referredfirstname']; }

   if(!empty($_POST['referredlastname'])){ $content['referredlastname'] = $_POST['referredlastname']; }

   if(!empty($_POST['referredlastname'])){ $content['referredphone'] = $_POST['referredphone']; }

   if(!empty($_POST['referredemail'])){ $content['referredemail'] = $_POST['referredemail']; }
   //medicare
   if(!empty($_POST['received-medicare-card'])){ $content['Received_medicare_card'] = $_POST['received-medicare-card']; }

   if(!empty($_POST['worked-paid-taxes'])){ $content['Worked_paid_taxes'] = $_POST['worked-paid-taxes']; }

   if(!empty($_POST['service-contact-permission'])){ $content['Service_contact_permission'] = $_POST['service-contact-permission']; }

   if(!empty($_POST['unable-to-work'])){ $content['Unable_to_work'] = $_POST['unable-to-work']; }

   if(!empty($_POST['NDA'])){ $content['NDA'] = $_POST['NDA']; }

   if(!empty($_POST['yourage'])){ $content['DateOfBirth'] = $_POST['yourage']; }

   //lead object is created by now so we encode and send
   $json_content = json_encode($content);

   //send via curl
   $curl = curl_init();

   curl_setopt_array($curl, array(
   CURLOPT_URL => "NDA",
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_ENCODING => "",
   CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 30,
   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
   CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => "NDA",
   CURLOPT_HTTPHEADER => array(
     "cache-control: no-cache",
     "content-type: application/x-www-form-urlencoded",
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  $jResponse = json_decode($response);


  //get the auth token and instance
  $auth_token = $jResponse->{'access_token'};
  $instance = $jResponse->{'instance_url'};
  $url = $instance.'/services/apexrest/[endpoint]/';

  // if ($err) {
  //     echo "cURL Error #:" . $err;
  // }

  //if our stomach is full it must be filled with spam and we want to end to script
  if(!empty($stomach)){

    die();

  }else{

  $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_HEADER, false);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_HTTPHEADER,
   array("Authorization: Bearer $auth_token",
    "Content-type: application/json"));
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_POSTFIELDS,  $json_content);

   $json_response = curl_exec($curl);
   $err = curl_error($curl);
   curl_close($curl);

  //array for possible redirects, not all forms are the same so we want to redirect based on the form id
  $redirects = array(
    "Refer a Friend" => "[NDA]/thank-you-refer/",
    "Contact Us" => "[NDA]/[NDA]/thank-you/",
    "medicare" => "[NDA]/[NDA]/medicare-thank-you/"
  );

  if ($err) {
    //do nothing
  } else {

   // log data into a textfile
   $my_file = 'logfile.txt';
   $handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
   $data = $jContent . " - ";
   $date = date("M,d,Y h:i:s A");
   $break = "\n\n";
   //
   fwrite($handle, $data);
   fwrite($handle, $date);
   fwrite($handle, $break);

       if($redirects[$_POST['form']] != null || $redirects[$_POST['form']] != "" ){
         header("Location: " .$redirects[$_POST['form']]);
       }else{
         header("Location: ../thank-you");
       }
    }
  }
}
die();
?>
