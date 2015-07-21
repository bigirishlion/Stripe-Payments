  <?php
require 'lib/Stripe.php';
 
if ($_POST) {
  Stripe::setApiKey("xxxxxxxxx");
  $error = '';
  $success = '';

  try {
    /*if (empty($_POST['street']) || empty($_POST['city']) || empty($_POST['zip']) || empty($_POST['cafe_member']))
      throw new Exception("Fill out all required fields.");
    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");


    if($subscription = true){
    // Create customer and assign customer to plan
        $customer = Stripe_Customer::create(array("source" => $_POST['stripeToken'],
            //"plan" => "Plan1",
            "plan"=>"testplan",
            "description" => "Subscription for ". $_POST['cafe_member'],
            "email" => $_POST['email']
        ));
    } else {
        // Donate
        $charge = Stripe_Charge::create(array(
            "amount" => 99,
            "currency" => "usd",
            "source" => $_POST['stripeToken'],
            "description" => $_POST['email']."Subsribed to ".$_POST['cafe_member']
        ));
    }*/

    // Send information to salesforce
    //http://sim.plified.com/2009/02/13/pushing-leads-to-salesforce-with-php/
    /*$url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
    $fields = array(
      'last_name'=>urlencode($_POST['first_name']),
      'first_name'=>urlencode($_POST['last_name']),
      'email'=>urlencode($_POST['email']),
      'street'=>urlencode($_POST['street']),
      'city'=>urlencode($_POST['city']),
      'state'=>urlencode($_POST['state']),
      'zip'=>urlencode($_POST['zip']),
      'oid' => '11111111', // insert with your id
      '11111111'=>urlencode($_POST['cafe_member']),
      //'retURL' => urlencode('http://lafleurcafe.aaron.local/subscribe/thanks/'),
      //'debug' => '1',
      //'debugEmail' => urlencode("aaron@guavajellyco.com"),
    );
    foreach($fields as $key=>$value) { $query_string .= $key.'='.$value.'&'; }
    rtrim($query_string,'&');
    // Test cUrl connection
    if (!function_exists('curl_init')){
      die('Sorry cURL is not installed!');
    }
    //Open cURL connection
    $ch = curl_init();
    //Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
     $result = curl_exec($ch);
    //close cURL connection
    curl_close($ch);*/

    // Send information to MailChimp
    $apiKey = "11111";
    $listId = "4af95dc7a1";

    /*$groupings = array(
      'name'=> 'Cafe Member',
      'groups'=> $_POST['cafe_member'],
    );

    $merge_vars = array(
      'FNAME' => ucwords(trim($_POST['first_name'])),
      'LNAME' => ucwords(trim($_POST['last_name'])),
      'address1'=>array('addr1'=>$_POST['street'], 'city'=>$_POST['city'], 'state'=>$_POST['state'], 'zip'=>$_POST['zip']),
      'GROUPINGS' => array($groupings),
    );*/

    $send_data=array(
      "email_address"=>$_POST['email'],
      'merge_vars'=>$merge_vars,
      'email'=>array($email_array),
      'apikey'=>$apiKey, // Your Key
      'id'=>$listId, // Your proper List ID
      'double_optin'=>false,
      'update_existing'=>true,
      'replace_interests'=>true,
      'send_welcome'=>false,
      'email_type'=>"html",
    );

    $payload=json_encode($send_data);

    $submit_url="https://us11.api.mailchimp.com/3.0/lists/" . $listId . "/members";

    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$submit_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_POST,TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS,urlencode($payload));
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $result=curl_exec($ch);
    curl_close($ch);
    $mcdata=json_decode($result);

    //if ($mcdata->error) echo "Mailchimp Error: ".$mcdata->error;
    echo("JSON: " . $payload);
    echo("Result = " . $result);

    //echo '<div class="alert alert-success"><strong>Success!</strong> Your payment was successful.</div>';
  }
  catch (Exception $e) {
    echo '<div class="alert alert-danger"><strong>Error!</strong> '.$e->getMessage().'</div>';
  }
} else {
  echo "<h1>Nothing to see here...</h1>";
}
?>