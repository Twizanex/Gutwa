<?php

/*
*
*  gutwa
*
*/



    global $plugin;
	
        $plugin = elgg_get_plugin_from_id('gutwa');
	
	
	$admin_User_paypalid = $plugin->admin_paypalid;
	$admin_email_notifyid = $plugin->admin_notifyid;
	$testing_paypal_url =  $plugin->paypal_url;
	$campaign_return_billing = $plugin->campaign_return_billing;
	$cancel_return_billing  =  $plugin->cancel_return_billing;
	$return_renew_campaign = $plugin->return_renew_campaign;
	$cancel_return_campaign = $plugin->cancel_return_campaign;	
	
	

if(isset($_GET['details'] )) {
$details = explode('_',$_GET['details']);
$payment_details = array("cost"=>number_format($details[0],2),"Clicks"=>$details[1],"Inv_id"=>$details[2],"Impressions"=>$details[3]);
}





//$advertiser_id = $_GET['adv_id'];
elgg_register_library('elgg:gutwa');
$renewal_details = get_input("inventory_products");
$campaign_id =  get_input("campaign_id");
$banner_id = get_input("baner_id");
elgg_make_sticky_form('gutwa');

$user = elgg_get_logged_in_user_entity(); // TM: to use metadata


$details = elgg_get_logged_in_user_entity();
	$user_details = array_values((array) $details);
	//$advertiser_id = $user_details[6]['advertiser_account'];
	$advertiser_id = $user->advertiser_account;   // TM
	
	
	
	$guid = $user_details[6]['guid'];

require_once('paypal.class.php');  // include the class gutwa
$p = new paypal_class;             // initiate an instance of the class

$p->paypal_url = $testing_paypal_url;   // testing paypal url
//$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

if(is_array($payment_details) )
{
	$type	=	"Campaign";
	$billing_period	=date('Y-m-d H:i:s');
}

if(isset($campaign_id) ) {
$type = "Renew campaign";
$details = explode('_',implode('_',$renewal_details));

$renewal_payment = array("cost"=>number_format($details[0],2),"Clicks"=>$details[1],"Inv_id"=>$details[2],"Impressions"=>$details[3]);
}
//Get publisher email notify ID

if($type == 'Campaign' || $type=='Renew campaign')
{




//Openx admin login
//$debug = true;
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
//$oClient->setdebug($debug);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);




$aParams    = array(
            new XML_RPC_Value($sessionId, 'string')
            
            );				
$oMessage  = new XML_RPC_Message('ox.getAdminPaypal', $aParams);
$oResponse = $oClient->send($oMessage);
$paypal_id = returnXmlRpcResponseData($oResponse);


$admin_paypalid = empty($paypal_id[0]['paypalid'])? $admin_User_paypalid :$admin_paypalid;
$admin_notifyid = empty($paypal_id[0]['notifyid'])? $admin_email_notifyid :$admin_notifyid;
			
}

			
//$aff_paypalid='krishnapriyavit@gmail.com';



// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  

switch ($_GET['action']) {
    
   case 'process':      // Process and order...

      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      if($type=='Campaign')
	  {
		  $p->add_field('business', $admin_paypalid);
	      $p->add_field('return',  $campaign_return_billing .'/gutwa/elgg_payment?action=success&advertiser_id='.$advertiser_id.'&cost='.$payment_details['cost'].'&from=sucess&type=Campaign&inv_id='.$payment_details['Inv_id'].'&user_id='.$guid.''); 
	      $p->add_field('cancel_return',  $cancel_return_billing .'/gutwa/addBanner?action=cancel&email='.$admin_notifyid.'&advertiser_id='.$advertiser_id.'');

		  $p->add_field('notify_url', $this_script.'?action=ipn&email='.$admin_notifyid.'&advertiser_id='.$advertiser_id.'&billing_period='.$billing_period.'&type=Campaign&inv_id='.$payment_details['Inv_id'].'&user_id='.$guid.'');
		  $p->add_field('item_name', 'Campaign Billing');
		  $p->add_field('amount', 2);
	   }
	   
	   if($type=='Renew campaign'){
	   
		  $p->add_field('business', $admin_paypalid);
	      $p->add_field('return',  $return_renew_campaign . '/gutwa/elgg_payment?action=success&advertiser_id='.$advertiser_id.'&cost='.$renewal_payment['cost'].'&type=Renewal&banner_id='.$banner_id.'&campaign_id='.$campaign_id.'&inv_id='.$renewal_payment['Inv_id'].'&user_id='.$guid.'&clicks='.$renewal_payment['Clicks'].'&impressions='.$renewal_payment['Impressions'].'');
	      $p->add_field('cancel_return', $cancel_return_campaign . '/gutwa/ad?action=cancel&email='.$admin_notifyid.'&advertiser_id='.$advertiser_id.'');
		  $p->add_field('notify_url', $this_script.'?action=ipn&email='.$admin_notifyid.'&advertiser_id='.$advertiser_id.'&billing_period='.$billing_period.'&type=Campaign&inv_id='.$renewal_payment['Inv_id'].'&user_id='.$guid.'');
		  $p->add_field('item_name', 'Renewal Payment');
		  $p->add_field('amount', 2);
	   }
	  	
	  	  $p->submit_paypal_post();
	 
	   // submit the fields to paypal
      //$p->dump_fields();      // for debugging, output a table of all the fields
      break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
 	
$type = $_GET['type'];	 

	     if($type=='Campaign')
	     {

$curr_time = date('Y-m-d H:i:s');
			$guid = $_GET['user_id'];
			$advertiser_id = $_GET['advertiser_id'];
			$cost = $_GET['cost'];	
			$Inv_id = $_GET['inv_id'];
$query = "INSERT INTO elgg_user_payment_details VALUES ('',$Inv_id,$advertiser_id,$guid,'".$curr_time."',1,$cost)";
insert_data($query);
$payment_success_details = array("inv_id"=>$Inv_id,"time"=>$curr_time,"status"=>1,"advertiser_id"=>$advertiser_id);
$_SESSION['paid_status'] = $payment_success_details; 

//$_SESSION['paid_status']='';

elgg_make_sticky_form('gutwa');
register_error(elgg_echo("your payment has been received,Kindly upload your banner"));
forward("gutwa/addBanner");

     		 

	      } 
$type = $_GET['type'];	
		  if($type=='Renewal') {
		
		
		  
		  
		    $curr_time = date('Y-m-d H:i:s');
			$guid = $_GET['user_id'];
			$BannerID = $_GET['banner_id'];
			$CampaignID = $_GET['campaign_id'];
			$advertiser_id = $_GET['advertiser_id'];
			$cost = $_GET['cost'];	
			$Inv_id = $_GET['inv_id'];
			$Clicks = $_GET['clicks'];
			$Imp = $_GET['impressions'];	
			$query = "INSERT INTO elgg_user_payment_details VALUES ('',$Inv_id,$advertiser_id,$guid,'".$curr_time."',1,$cost)";
			insert_data($query);  

//Openx admin login
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
//$oClient->setdebug($debug);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);

//Update campaign

$pricing_model = ($Clicks==0)?1:2;
$Imp = ($Imp==0)?-1:$Imp;
$Clicks = ($Clicks==0)?-1:$Clicks;
$oCamp = new XML_RPC_Value(
                		array('campaignId' => new XML_RPC_Value($CampaignID, 'int'),
                        			'impressions' => new XML_RPC_Value($Imp, 'int'),
						'clicks' =>  new XML_RPC_Value($Clicks, 'int'),
                       'revenueType' => new XML_RPC_Value($pricing_model, 'int'),
                        'revenue' => new XML_RPC_Value($cost, 'double'),

                        
                ), 
                'struct');
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            $oCamp
            );				
$oMessage  = new XML_RPC_Message('ox.modifyCampaign', $aParams);
$oResponse = $oClient->send($oMessage);
$Camp_id = returnXmlRpcResponseData($oResponse);


// Modify Banner


$oBanner = new XML_RPC_Value(
                		array(
						'bannerId' => new XML_RPC_Value($BannerID, 'int'),
			    		'status' => new XML_RPC_Value(0,'int'),
				), 
                'struct');

$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            $oBanner
            );				



$oMessage  = new XML_RPC_Message('ox.modifyBanner', $aParams);
$oResponse = $oClient->send($oMessage);
$Banner_id = returnXmlRpcResponseData($oResponse);





// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);

   		  
			$_SESSION['paid_status'] = 'paid';
			register_error(elgg_echo("Your payment has been received"));
			forward("gutwa/ad");
}
 
		// You could also simply re-direct them to another page, or your own 
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code 
      // below).
      
      break;
      
   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.
 	     
	     





if($type=='Campaign')
	     {
     		 
			   forward("gutwa/addBanner");
     		   exit();

	      }
      
      break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text gutwa.
     // mail('chetanpatel@dreamajax.com','from paypal','asokapapers payapal');
      if ($p->validate_ipn()) {
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
         $subject = 'Instant Payment Notification - Recieved Payment';

	$to = $_GET['email'];    //  your email

         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         
         foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; }
         mail($to, $subject, $body);

		$curr_time = date('Y-m-d H:i:s');

		 //$bill_period = $_GET['billing_period'];

 		//$clientid = $_GET['clientid'];
		
		//$guid = $_GET['user_id'];

 		//$advertiser_id = $_GET['advertiser_id'];

		//$cost = $_GET['cost'];	
		//$Inv_id = $_GET['inv_id'];		




		 

	    	
      }
	  
      break;
 }     



?>
