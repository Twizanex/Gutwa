<?php
/**
 * Elgg gutwa plugin  
 *
 * @package ElggGutwa
 */
 
 


elgg_register_event_handler('init', 'system', 'gutwa_init');
register_elgg_event_handler('pagesetup','system','gutwa_pagesetup');
/**
 * gutwa plugin initialization functions.
 */
function gutwa_init() {

	global $CONFIG;
	
	global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');

    // register actions here so that the admin can be able to save the settings
       elgg_register_action("gutwa/admin_settings", dirname(__FILE__) . "/actions/admin_settings.php", "admin");

  	// Only continue if the plugin has been set up by Elgg admin
	$adhost = $plugin->red5host;
	$adsred5port = $plugin->red5port;
	$admin_user = $plugin->admin_user;
        $admin_password = $plugin->admin_password;
	
	if (!($adhost && $adsred5port  &&  $admin_user && $admin_password  )) {
	
	if (elgg_is_admin_logged_in()) {
	system_message(elgg_echo('gutwa:warning:creditials:not:set'));
               
          }     
                return NULL;
        }
	
	// Only filter if the paypal gokubwa plugin has been set up by Elgg admin
		
	$admin_paypalid = $plugin->admin_paypalid;
	$admin_notifyid = $plugin->admin_notifyid;
	$paypal_url = $plugin->paypal_url;
	$campaign_return_billing = $plugin->campaign_return_billing;
	$cancel_return_billing = $plugin->cancel_return_billing;
	$return_renew_campaign = $plugin->return_renew_campaign;
	$cancel_return_campaign = $plugin->cancel_return_campaign;
	
// Let us check if the gutwa plugin settings are set or if any is left empty the the plugin will not continue

if (!( $admin_paypalid && $admin_notifyid &&  $paypal_url && $campaign_return_billing && $cancel_return_billing && $return_renew_campaign && $cancel_return_campaign )) {

if (elgg_is_admin_logged_in()) {
	system_message(elgg_echo('gutwa:warning:creditials:check:paypal:set'));
               
            }     

                return NULL;
             }
          	
	// prepare vendor classes and libaries:

        require_once(dirname(__FILE__) . "/vendors/ApiConf/api_conf.php"); // Configuration gutwa
        require_once(dirname(__FILE__) . "/vendors/PearClass/RPC.php"); // Pear Classes
        


	// register a library of helper functions
	elgg_register_library('elgg:gutwa', elgg_get_plugins_path() . 'gutwa/lib/gutwa.php');
	
	

	// Site navigation
	//$item = new ElggMenuItem('Create Ad', elgg_echo('gutwa'), 'gutwa/add');
	$item = new ElggMenuItem('Create Ad', elgg_echo('Create Ad'), 'gutwa/ad');
	elgg_register_menu_item('site', $item);

	// Extend CSS
	elgg_extend_view('css/elgg', 'gutwa/css');
$css_url = 'mod/gutwa/special.css';
elgg_register_css('special', $css_url);
elgg_load_css('special');

$date_css_url = 'mod/gutwa/ui.daterangepicker.css';
elgg_register_css('daterange', $date_css_url);
elgg_load_css('daterange');


$ui_css = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/redmond/jquery-ui.css';
elgg_register_css('dateUi', $ui_css);
elgg_load_css('dateUi');


$js_url = 'mod/gutwa/qTip.js';
elgg_register_js('tooltip', $js_url);
elgg_load_js('tooltip');

$jq_url = 'mod/gutwa/selection.js';
elgg_register_js('inv', $jq_url);
elgg_load_js('inv');

$date_url = 'mod/gutwa/daterangepicker.jQuery.compressed.js';
elgg_register_js('date', $date_url);
elgg_load_js('date');

$picker_url = 'mod/gutwa/daterangepicker.jQuery.js';
elgg_register_js('picker', $picker_url);
elgg_load_js('picker');

$dat_url = 'mod/gutwa/date.js';
elgg_register_js('dat', $dat_url);
elgg_load_js('dat');

$facebook_css = 'mod/gutwa/token-input-facebook.css';
elgg_register_css('fb_css', $facebook_css);
elgg_load_css('fb_css');



$chosen_css = 'mod/gutwa/chosen.css';
elgg_register_css('chosen_css', $chosen_css);
elgg_load_css('chosen_css');

$chosen_js = 'mod/gutwa/chosen.jquery.js';
elgg_register_js('chosen_js', $chosen_js);
elgg_load_js('chosen_js');

$token_url = 'mod/gutwa/jquery.tokeninput.js';
elgg_register_js('token_url', $token_url);
elgg_load_js('token_url');




	// add enclosure to rss item
	elgg_extend_view('extensions/item', 'gutwa/enclosure');

	// extend group main page
	elgg_extend_view('groups/tool_latest', 'gutwa/group_module');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('gutwa', 'gutwa_page_handler');
	

	
	
	 // hooks  : it is not working because new users were being created in on Openx server even the unvalidated ones
	 // so we are using action login to check if a user is validated, not banned and so on
      //	elgg_register_plugin_hook_handler('action', 'register', 'gutwa_register_user_hook', 445);
	
	
	// prevent the engine from logging in users via login(;
	// if you set the priority of your event handler so that it's the last one it the list and gets invoked at the end - 
     //  elgg_register_event_handler('login', 'user', 'gutwa_login_action_user_hook', 1000);
       elgg_register_plugin_hook_handler('action', 'login', 'gutwa_login_action_user_hook', 1000);
	 
	
	//rgister actions was moved from here to the top so that the admin can be able to save the plugin settings
	
	// Add a new gutwa widget
	elgg_register_widget_type('gutwarepo', elgg_echo("gutwa"), elgg_echo("gutwa:widget:description"));

	// Register URL handlers for gutwas
	elgg_register_entity_url_handler('object', 'gutwa', 'gutwa_url_override');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'gutwa_icon_url_override');

	// Register granular notification for this object type
	register_notification_object('object', 'gutwa', elgg_echo('gutwa:newupload'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'gutwa_notify_message');

	// add the group gutwas tool option
	add_group_tool_option('gutwa', elgg_echo('groups:enablegutwas'), true);

	// Register entity type for search
	elgg_register_entity_type('object', 'gutwa');

	// add a gutwa link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'gutwa_owner_block_menu');

	// Register actions
	$action_path = elgg_get_plugins_path() . 'gutwa/actions/gutwa';
	elgg_register_action("gutwa/upload", "$action_path/upload.php");
	
	elgg_register_action("gutwa/delete", "$action_path/delete.php");
	// temporary - see #2010
	elgg_register_action("gutwa/download", "$action_path/download.php");

	// embed support
	$item = ElggMenuItem::factory(array(
		'name' => 'gutwa',
		'text' => elgg_echo('gutwa'),
		'priority' => 10,
		'data' => array(
			'options' => array(
				'type' => 'object',
				'subtype' => 'gutwa',
			),
		),
	));
	elgg_register_menu_item('embed', $item);

	$item = ElggMenuItem::factory(array(
		'name' => 'gutwa_upload',
		'text' => elgg_echo('gutwa:upload'),
		'priority' => 100,
		'data' => array(
			'view' => 'embed/gutwa_upload/content',
		),
	));

	elgg_register_menu_item('embed', $item);
}




/**
 * Menu
 *
 */
function gutwa_pagesetup(){
	global $CONFIG;
	
//	global $plugin;
//	$plugin = elgg_get_plugin_from_id('gutwa');
	
	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {

    elgg_register_menu_item('page', array(
                'name' => 'gutwa',
                'href' => $CONFIG->wwwroot . 'admin/settings/gutwa',
                'text' => elgg_echo("admin:settings:gutwa"),
                'context' => 'admin',
                'parent_name' => 'settings',
                'priority' => 100,
                'section' => 'configure',
        ));		
		
	} 


}






/**
*  TM: Start Login User
* Checks if an account is validated then login the user
*
* @params array $credentials The username and password
* @return bool
*/
//var_dump($CONFIG->actions);

function gutwa_login_action_user_hook(){



       // Load the configuration
	global $CONFIG;
	// Load the plugin configuration
	global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');
	
 /// After testing, this might be dropped from here to ->>>>>	
	// set forward url
if (!empty($_SESSION['last_forward_from'])) {
	$forward_url = $_SESSION['last_forward_from'];
	unset($_SESSION['last_forward_from']);
} elseif (get_input('returntoreferer')) {
	$forward_url = REFERER;
} else {
	// forward to main index page
	$forward_url = '';
}

$username = get_input('username');
$password = get_input('password', null, false);
$persistent = (bool) get_input("persistent");
$result = false;

if (empty($username) || empty($password)) {
	register_error(elgg_echo('login:empty'));
	forward();
}

// check if logging in with email address
if (strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

$result = elgg_authenticate($username, $password);
if ($result !== true) {
	register_error($result);
	forward(REFERER);
}

$user = get_user_by_username($username);
if (!$user) {
	register_error(elgg_echo('login:baduser'));
	forward(REFERER);
}

	
	
	
// ->>>>>> to here: to be dropped after testing.....	
      
        // See if the user exists and is  validated and is not banned -- this is fun and works like cham :)
//        if (($user instanceof ElggUser) && ($user->isEnabled()) && (!$user->isBanned()) && ($user->validated)) {

                // do all this good stuff
                
 // Deburging

// Create a metadate for current users to have advertiser account at Elgg site

//Let use set  our ELgg metadata's advertiser_account ID to default Zero.

if (!$user->advertiser_account) {

  $user->advertiser_account = 0;

}

//Check whether the current user has advertiser account
//if advertiser_account is zero,add advertiser account to currernt user through openx API

$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity WHERE username ='$username' ";
$Rset = get_data($query);

//var_dump($advertiser_account);// Deburging

$user_details = (array) $Rset[0];
$advertiser_account =  (array)$user->advertiser_account ;

if (!empty($advertiser_account)) {

$number = 0; // Lets grab the first value array [0] =>'value';
$myArray = array($user->advertiser_account);
//echo $myArray[$number];   // For deburging outputs metadata value

}


//Condition to Check advertiserID exist in openx to create new advertiser account if openx admin deletes it mistakenly

if( $myArray[$number] != 0 || $myArray[$number] == 0) { 


$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string')); // TM: Changed gutwa is not Admin for now
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);

//check advertiser account exist in openx
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($myArray[$number], 'int'));							
$oMessage  = new XML_RPC_Message('ox.getAdvertiser', $aParams);
$oResponse = $oClient->send($oMessage);
$account_details = returnXmlRpcResponseData($oResponse);

//If advertiser account does not exist in openx create a new advertiser account
if(empty($account_details)) {
$guid = $user_details['guid'];
//Add advertiser
$oAdvertiser = new XML_RPC_Value(
                		array('advertiserName' => new XML_RPC_Value($user_details['name'], 'string'),
                        'agencyId' => new XML_RPC_Value('1', 'int'),
						 'contactName' => new XML_RPC_Value($user_details['name'], 'string'),
                       'emailAddress' => new XML_RPC_Value($user_details['email'], 'string'),
                        'username' => new XML_RPC_Value($user_details['username'], 'string'),
                        'password' => new XML_RPC_Value($password,'string')
                ), 
                'struct');

$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            $oAdvertiser
            );	
			
$oMessage  = new XML_RPC_Message('ox.addAdvertiser', $aParams);
$oResponse = $oClient->send($oMessage);
$advertiser_id = returnXmlRpcResponseData($oResponse);


//Get advertiser details
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($advertiser_id, 'int')
            
            );							

$oMessage  = new XML_RPC_Message('ox.getAdvertiser', $aParams);
$oResponse = $oClient->send($oMessage);
$account_details = returnXmlRpcResponseData($oResponse);

$account_id = $account_details['accountId'];

//Create user	
//$rand = rand(0,3);
$oUser = new XML_RPC_Value(
                		array('userName' => new XML_RPC_Value($user_details['username'], 'string'),
                        'agencyId' => new XML_RPC_Value('1', 'int'),
						'defaultAccountId' => new XML_RPC_Value($account_id,'int'),
						 'contactName' => new XML_RPC_Value($user_details['name'], 'string'),
                       'emailAddress' => new XML_RPC_Value($user_details['email'], 'string'),
                        'username' => new XML_RPC_Value($user_details['username'], 'string'),
                        'password' => new XML_RPC_Value($password,'string')
                ), 
                'struct');
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            $oUser
            );							

$oMessage  = new XML_RPC_Message('ox.addUser', $aParams);
$oResponse = $oClient->send($oMessage);
$User_id = returnXmlRpcResponseData($oResponse);


//Link user to advertiser                
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($User_id, 'int'),new XML_RPC_Value($account_id, 'int')            );		
$oMessage  = new XML_RPC_Message('ox.linkUserToAdvertiserAccount', $aParams);
$oResponse = $oClient->send($oMessage);
$linkUser_id = returnXmlRpcResponseData($oResponse);

	
// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);

// Now let us update our Elgg metadata with our new advertiser_id
  $user->advertiser_account = $advertiser_id;
  


}
}

 //  } //TM: clossing -- // See if the user exists and is  validated and is not banned -- this is fun and works like cham :)

}





/**
 * Dispatches gutwa pages.
 * URLs take the form of
 *  All gutwas:       gutwa/all
 *  User's gutwas:    gutwa/owner/<username>
 *  Friends' gutwas:  gutwa/friends/<username>
 *  View gutwa:       gutwa/view/<guid>/<title>
 *  New gutwa:        gutwa/add/<guid>
 *  Edit gutwa:       gutwa/edit/<guid>
 *  Group gutwas:     gutwa/group/<guid>/all
 *  Download:        gutwa/download/<guid>
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function gutwa_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$gutwa_dir = elgg_get_plugins_path() . 'gutwa/pages/gutwa';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			gutwa_register_toggle();
			include "$gutwa_dir/owner.php";
			break;
		case 'friends':
			gutwa_register_toggle();
			include "$gutwa_dir/friends.php";
			break;
		case 'read': // Elgg 1.7 compatibility
			register_error(elgg_echo("changebookmark"));
			forward("gutwa/view/{$page[1]}");
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$gutwa_dir/view.php";
			break;
		case 'add':
			include "$gutwa_dir/upload.php";
			break;
		case 'addBanner':
			include "$gutwa_dir/upload_banner.php";
			break;
	       case 'elgg_payment':
			include "$gutwa_dir/elgg_payment.php";
			break;

		 case 'renew':
			include "$gutwa_dir/banner_renew.php";
			break;
	
		 case 'process':
			include "$gutwa_dir/process.php";
			break;	

		case 'ad':
			include "$gutwa_dir/banner.php";
			break;
		case 'stat':
			include "$gutwa_dir/banner_statistics.php";
			break;

		case 'country':
			include "$gutwa_dir/input.php";
			break;

		case 'category':
			include "$gutwa_dir/category.php";
			break;		

		case 'delete':
			include "$gutwa_dir/delete_banner.php";
			break;

		case 'keyword':
			include "$gutwa_dir/keyword.php";
			break;

		case 'edit':
			set_input('guid', $page[1]);
			include "$gutwa_dir/edit.php";
			break;


		
		case 'banner_edit':
			
			include "$gutwa_dir/banner_edit.php";
			break;

		case 'search':
			gutwa_register_toggle();
			include "$gutwa_dir/search.php";
			break;
		case 'group':
			gutwa_register_toggle();
			include "$gutwa_dir/owner.php";
			break;
		case 'all':
			gutwa_register_toggle();
			include "$gutwa_dir/world.php";
			break;
		case 'download':
			set_input('guid', $page[1]);
			include "$gutwa_dir/download.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function gutwa_register_toggle() {
	$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

	if (get_input('list_type', 'list') == 'list') {
		$list_type = "gallery";
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = "list";
		$icon = elgg_view_icon('list');
	}

	if (substr_count($url, '?')) {
		$url .= "&list_type=" . $list_type;
	} else {
		$url .= "?list_type=" . $list_type;
	}


	elgg_register_menu_item('extras', array(
		'name' => 'gutwa_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("gutwa:list:$list_type"),
		'priority' => 1000,
	));
}


/**
 * Creates the notification message body
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function gutwa_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'gutwa')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('gutwa:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Add a menu item to the user ownerblock
 */
function gutwa_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "gutwa/ad";
		$item = new ElggMenuItem('gutwa', elgg_echo('Create Ad'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->gutwa_enable != "no") {
			$url = "gutwa/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('gutwa', elgg_echo('gutwa:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Returns an overall gutwa type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 */
function gutwa_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
		case "application/ogg":
			return "audio";
			break;
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

// deprecated and will be removed
function get_general_gutwa_type($mimetype) {
	elgg_deprecated_notice('Use gutwa_get_simple_type() instead of get_general_gutwa_type()', 1.8);
	return gutwa_get_simple_type($mimetype);
}

/**
 * Returns a list of gutwatypes
 *
 * @param int       $container_guid The GUID of the container of the gutwas
 * @param bool      $friends        Whether we're looking at the container or the container's friends
 * @return string The typecloud
 */
function gutwa_get_type_cloud($container_guid = "", $friends = false) {

	$container_guids = $container_guid;

	if ($friends) {
		// tags interface does not support pulling tags on friends' content so
		// we need to grab all friends
		$friend_entities = get_user_friends($container_guid, "", 999999, 0);
		if ($friend_entities) {
			$friend_guids = array();
			foreach ($friend_entities as $friend) {
				$friend_guids[] = $friend->getGUID();
			}
		}
		$container_guids = $friend_guids;
	}

	elgg_register_tag_metadata_name('simpletype');
	$options = array(
		'type' => 'object',
		'subtype' => 'gutwa',
		'container_guids' => $container_guids,
		'threshold' => 0,
		'limit' => 10,
		'tag_names' => array('simpletype')
	);
	$types = elgg_get_tags($options);

	$params = array(
		'friends' => $friends,
		'types' => $types,
	);

	return elgg_view('gutwa/typecloud', $params);
}

function get_gutwatype_cloud($owner_guid = "", $friends = false) {
	elgg_deprecated_notice('Use gutwa_get_type_cloud instead of get_gutwatype_cloud', 1.8);
	return gutwa_get_type_cloud($owner_guid, $friends);
}

/**
 * Populates the ->getUrl() method for gutwa objects
 *
 * @param ElggEntity $entity gutwa entity
 * @return string gutwa URL
 */
function gutwa_url_override($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "gutwa/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Override the default entity icon for gutwas
 *
 * Plugins can override or extend the icons using the plugin hook: 'gutwa:icon:url', 'override'
 *
 * @return string Relative URL
 */
function gutwa_icon_url_override($hook, $type, $returnvalue, $params) {
	$gutwa = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($gutwa, 'object', 'gutwa')) {

		// thumbnails get first priority
		if ($gutwa->thumbnail) {
			$ts = (int)$gutwa->icontime;
			return "mod/gutwa/thumbnail.php?gutwa_guid=$gutwa->guid&size=$size&icontime=$ts";
		}

		$mapping = array(
			'application/excel' => 'excel',
			'application/msword' => 'word',
			'application/ogg' => 'music',
			'application/pdf' => 'pdf',
			'application/powerpoint' => 'ppt',
			'application/vnd.ms-excel' => 'excel',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'openoffice',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
			'application/x-gzip' => 'archive',
			'application/x-rar-compressed' => 'archive',
			'application/x-stuffit' => 'archive',
			'application/zip' => 'archive',

			'text/directory' => 'vcard',
			'text/v-card' => 'vcard',

			'application' => 'application',
			'audio' => 'music',
			'text' => 'text',
			'video' => 'video',
		);

		$mime = $gutwa->mimetype;
		if ($mime) {
			$base_type = substr($mime, 0, strpos($mime, '/'));
		} else {
			$mime = 'none';
			$base_type = 'none';
		}

		if (isset($mapping[$mime])) {
			$type = $mapping[$mime];
		} elseif (isset($mapping[$base_type])) {
			$type = $mapping[$base_type];
		} else {
			$type = 'general';
		}

		if ($size == 'large') {
			$ext = '_lrg';
		} else {
			$ext = '';
		}
		
		$url = "mod/gutwa/graphics/icons/{$type}{$ext}.gif";
		$url = elgg_trigger_plugin_hook('gutwa:icon:url', 'override', $params, $url);
		return $url;
	}
}

