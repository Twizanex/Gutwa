<?php
/**
 * Elgg gutwa uploader/edit action
 *
 * @package ElggGutwa 
 */

// Get variables

	global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');




$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$destination_url = get_input("destination_url");
$BannerID = get_input("banner_id");

$category = implode(',',get_input("category_targeting"));
$sizes = get_input("banner_specification");
$image_spec = explode('_',$sizes);




$image_height = $sizes[1];
$country_targeting = implode(',',get_input("country_targeting"));
$Keyword_Banner = implode(',',get_input("extra_info"));
$banner_text = get_input("ad_text");
$_SESSION['country_targeting'] = $country_targeting;
$Inv_prod = get_input("inventory_products");
$Details = explode('_',implode('_',$Inv_prod));
$cost = $Details[0];
$Clicks = $Details[1];
$inv_id = $Details[2];
$Imp =$Details[3];

$user = elgg_get_logged_in_user_entity(); // TM
$details = elgg_get_logged_in_user_entity();
	$user_details = array_values((array) $details);
//	$advertiser_id = $user_details[6]['advertiser_account'];



//        $advertiser_id = $user->advertiser_account ;

  // Check is the user has advertiser_account in elgg site if not set the value to zero
     if (!$user->advertiser_account) {

       $user->advertiser_account = 0;

       }
     
       $advertiser_account =  (array)$user->advertiser_account ;

 	if (!empty($advertiser_account)) {
	//$value = trim($advertiser_account);

	$number = 0; // Lets grab the first value array [0] =>'value';
	$myArray = array($user->advertiser_account);
	//echo $myArray[$number];   // For deburging outputs metadata value

	}

 $advertiser_id = $myArray[$number];



if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('gutwa');

//Check if advertiser account exist in openx,if not create new account 
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);

$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($myArray[$number], 'int'));							
$oMessage  = new XML_RPC_Message('ox.getAdvertiser', $aParams);
$oResponse = $oClient->send($oMessage);
$account_details = returnXmlRpcResponseData($oResponse);


// Let use update our elgg metadata with the new Advertiser's account ID // TM

// Now let us update our Elgg metadata with our new advertiser_id
//  $user->advertiser_account = $advertiser_id; // TM



if(empty($account_details)) {
	register_error(elgg_echo('Kindly login to create your advertiser account'));
	forward(REFERER);
}

//End of condition to check advertiser account

//Basic validation
if(empty($title) ) {
	if(!isset($_FILE['reupload']['name']) ) {
	register_error(elgg_echo('Banner name cannot be empty'));
	forward(REFERER);
	}	
}

if(empty($destination_url) ) {
	if(!isset($_FILE['reupload']['name']) ) {
	register_error(elgg_echo('Fill up destination-url'));
	forward(REFERER);
	}	
}

if(empty($banner_text) ) {
	if(!isset($_FILE['reupload']['name']) ) {
	register_error(elgg_echo('Fill up Ad-Text field'));
	forward(REFERER);
	}	
}

//End of Basic validation

// check if upload failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	register_error(elgg_echo('gutwa:cannotload'));
	forward(REFERER);
}
//Deal with Re-upload
if (isset($_FILES['reupload']['name']) ) {
	if (!empty($_FILES['reupload']['name']) && $_FILES['reupload']['error'] != 0) {
	register_error(elgg_echo('gutwa:cannotload'));
	forward(REFERER);
}

if (empty($_FILES['reupload']['name'])) {
		$error = elgg_echo('gutwa:nogutwa');
		register_error($error);
		forward(REFERER);
	}
}	

// check whether this is a new gutwa or an edit
if(isset($_FILES['upload']['name']) ) {
$new_gutwa = true;
if ($guid > 0) {
	$new_gutwa = false;
}

}

 
if(!isset($_SESSION['paid_status'])) {

		$error = elgg_echo('Please make payment to upload your banner');
		register_error($error);
		forward(REFERER);
}
if ($new_gutwa) {
	// must have a gutwa if a new gutwa upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('gutwa:nogutwa');
		register_error($error);
		forward(REFERER);
	}

	$gutwa = new GutwaPluginFile();
	$gutwa->subtype = "gutwa";

	// if no title on new upload, grab gutwaname
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} 


// we have a gutwa upload, so process it


if (isset($_FILES['reupload']['name']) && !empty($_FILES['reupload']['name'] ) ) {


	list($width, $height) = getimagesize($_FILES['reupload']['tmp_name']);


if(!in_array($width,$image_spec)) {
register_error(elgg_echo('Please check your banner width'));
		forward(REFERER); }

if(!in_array($height,$image_spec)) {
register_error(elgg_echo('Please check your banner height'));
		forward(REFERER);
}



$gutwaname = $_FILES['reupload']['name'];
$tmp_path = $_FILES['reupload']['tmp_name'];
$details = elgg_get_logged_in_user_entity();
$user_details = array_values((array) $details);
//$advertiser_id = $user_details[6]['advertiser_account'];
$advertiser_id = $user->advertiser_account; //TM

$debug = true;
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$oClient->setdebug($debug);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value('1', 'int')
            
            );		

// Modify Banner


$oBanner = new XML_RPC_Value(
                		array(
						'bannerId' => new XML_RPC_Value($BannerID, 'int'),
			    		'status' => new XML_RPC_Value(2,'int'),
						'storageType' => new XML_RPC_Value('web','string'),
						'append' => new XML_RPC_Value($_FILES['reupload']['tmp_name'],'string'), 
						'prepend' => new XML_RPC_Value($_FILES['reupload']['name'],'string'),
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

//forward('gutwa/elgg_payment?adv_id='.$advertiser_id.'&Banner_id='.$Banner_id.'&cost='.$cost.'&camp_id='.$Camp_id.'');


register_error(elgg_echo("Uploaded Banner Awaits For Admin Approval"));


}



if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
	

	list($width, $height) = getimagesize($_FILES['upload']['tmp_name']);
	
if(!in_array($width,$image_spec)) {
register_error(elgg_echo('Please check your banner width'));
		forward(REFERER); }

if(!in_array($height,$image_spec)) {
register_error(elgg_echo('Please check your banner height'));
		forward(REFERER);
}



	$gutwaname = $_FILES['upload']['name'];
	$tmp_path = $_FILES['upload']['tmp_name'];

	$details = elgg_get_logged_in_user_entity();
	$user_details = array_values((array) $details);
	
//	$advertiser_id = $user_details[6]['advertiser_account'];

//        $advertiser_id = $user->advertiser_account ;  // TM
        
        


//$debug=true;
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
//$oClient->setdebug($debug);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value('1', 'int')
            
            );		

$pricing_model = ($Clicks==0)?1:2;

$Imp = ($Imp==0)?-1:$Imp;
$Clicks = ($Clicks==0)?-1:$Clicks;

//Add Campaign
$oCamp = new XML_RPC_Value(
                		array('advertiserId' => new XML_RPC_Value($myArray[$number], 'int'),
                        'campaignName' => new XML_RPC_Value($title.'_Campaign', 'string'),
//						 'description' => new XML_RPC_Value('My Banner Test', 'string'),
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
$oMessage  = new XML_RPC_Message('ox.addCampaign', $aParams);
$oResponse = $oClient->send($oMessage);
$Camp_id = returnXmlRpcResponseData($oResponse);


// Add Banner


$oBanner = new XML_RPC_Value(
                		array(
						'campaignId' => new XML_RPC_Value($Camp_id, 'int'),
			            		'bannerName' => new XML_RPC_Value($title, 'string'),
						'status' => new XML_RPC_Value(2,'int'),
						'storageType' => new XML_RPC_Value('web','string'),
						'alt' => new XML_RPC_Value($category,'string'),
						'url' => new XML_RPC_Value($destination_url,'string'),
						'append' => new XML_RPC_Value($_FILES['upload']['tmp_name'],'string'), 
						'prepend' => new XML_RPC_Value($_FILES['upload']['name'],'string'),
						'adserver' => new XML_RPC_Value($Keyword_Banner,'string'),
						'comments' => new XML_RPC_Value($banner_text,'string')	

			                       
                        
                ), 
                'struct');

$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            $oBanner
            );				



$oMessage  = new XML_RPC_Message('ox.addBanner', $aParams);
$oResponse = $oClient->send($oMessage);
$Banner_id = returnXmlRpcResponseData($oResponse);


//Set Country Targeting
if(!empty($country_targeting) ) {
$Targeting = array(
                    0 => array(
                                'logical' => 'or',
                                'type' => 'deliveryLimitations:Geo:Country',
                                'comparison' => '==',
                                'data' => $country_targeting
                               )
                   );

$aParams = array(
                 new XML_RPC_Value($sessionId, 'string'),
                 new XML_RPC_Value($Banner_id, 'int'),
                 XML_RPC_encode($Targeting)
                );

}
$oMessage = New XML_RPC_Message('ox.setBannerTargeting', $aParams);
$oResponse = $oClient->send($oMessage);
returnXmlRpcResponseData($oResponse);


// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);

unset($_SESSION['paid_status']);
unset($_SESSION['banner_text']);
unset($_SESSION['destination_url']);
unset($_SESSION['title']);
//forward('gutwa/elgg_payment?adv_id='.$advertiser_id.'&Banner_id='.$Banner_id.'&cost='.$cost.'&camp_id='.$Camp_id.'');


register_error(elgg_echo("Uploaded Banner Awaits For Admin Approval"));


}

// gutwa saved so clear sticky form
elgg_clear_sticky_form('gutwa');


// handle results differently for new gutwas and gutwa updates

	forward('gutwa/ad');