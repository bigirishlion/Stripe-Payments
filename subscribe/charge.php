  <?php
require 'lib/Stripe.php';
 
if ($_POST) {
  Stripe::setApiKey("xxxxxxxxxxxx");
  $error = '';
  $success = '';

  try {
    if (empty($_POST['street']) || empty($_POST['city']) || empty($_POST['zip']) || empty($_POST['cafe_member']))
      throw new Exception("Fill out all required fields.");
    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");

    $subscription = true;

    if($subscription){
        // Create customer and assign customer to plan
        $customer = Stripe_Customer::create(array("source" => $_POST['stripeToken'],
            "plan" => "Plan1",
            //"plan"=>"testplan",
            "description" => "Subscription for ". $_POST['cafe_member'],
            "email" => $_POST['email']
        ));
    } else {
        // Donate
        $charge = Stripe_Charge::create(array(
            "amount" => $_POST['donation_amount'],
            "currency" => "usd",
            "source" => $_POST['stripeToken'],
            "description" => $_POST['email']."Subsribed to ".$_POST['cafe_member']
        ));
    }

    // Send information to MailChimp
    $listId = $_POST['list_id'];
    $username = "anything";
    $password = "xxxxxxxxxxxx";

    $merge_vars = array(
      'FNAME' => ucwords(trim($_POST['first_name'])),
      'LNAME' => ucwords(trim($_POST['last_name'])),
    );

    $send_data=array(      
      "email_address"=>$_POST['email'],
      'status'=>'subscribed',
      'merge_fields'=>$merge_vars,
      'id'=>$listId, // Your proper List ID
      'double_optin'=>false,
      'update_existing'=>true,
      'replace_interests'=>true,
      'send_welcome'=>false,
      'email_type'=>"html",
    );

    $payload=json_encode($send_data);

    $submit_url="https://us7.api.mailchimp.com/3.0/lists/" . $listId . "/members";

    $ch=curl_init();
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch,CURLOPT_URL,$submit_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_POST,TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $result=curl_exec($ch);
    if(curl_errno($ch)){
      echo 'error:' . curl_error($ch);
    }
    curl_close($ch);

    echo '<div class="alert alert-success"><strong>Success!</strong> Your payment was successful.</div>';
  }
  catch (Exception $e) {
    echo '<div class="alert alert-danger"><strong>Error!</strong> '.$e->getMessage().'</div>';
  }
} else {
  echo "<h1>Nothing to see here...</h1>";
}
?>