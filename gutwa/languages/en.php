<?php
/**
 * Elgg gutwa plugin language pack
 *
 * @package ElggGutwa  
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'gutwa' => "gutwas",
	'gutwa:user' => "%s's gutwas",
	'gutwa:friends' => "Friends' gutwas",
	'gutwa:all' => "All site gutwas",
	'gutwa:edit' => "Edit gutwa",
	'gutwa:more' => "More gutwas",
	'gutwa:list' => "list view",
	'gutwa:group' => "Group gutwas",
	'gutwa:gallery' => "gallery view",
	'gutwa:gallery_list' => "Gallery or list view",
	'gutwa:num_gutwas' => "Number of gutwas to display",
	'gutwa:user:gallery'=>'View %s gallery',
	'gutwa:upload' => "Upload a gutwa",
	'gutwa:replace' => 'Replace gutwa content (leave blank to not change gutwa)',
	'gutwa:list:title' => "%s's %s %s",
	'gutwa:title:friends' => "Friends'",

	'gutwa:add' => 'Upload a gutwa',

	'gutwa:gutwa' => "gutwa",
	'gutwa:title' => "Title",
	'gutwa:desc' => "Description",
	'gutwa:tags' => "Tags",

	'gutwa:list:list' => 'Switch to the list view',
	'gutwa:list:gallery' => 'Switch to the gallery view',

	'gutwa:types' => "Uploaded gutwa types",

	'gutwa:type:' => 'gutwas',
	'gutwa:type:all' => "All gutwas",
	'gutwa:type:video' => "Videos",
	'gutwa:type:document' => "Documents",
	'gutwa:type:audio' => "Audio",
	'gutwa:type:image' => "Pictures",
	'gutwa:type:general' => "General",

	'gutwa:user:type:video' => "%s's videos",
	'gutwa:user:type:document' => "%s's documents",
	'gutwa:user:type:audio' => "%s's audio",
	'gutwa:user:type:image' => "%s's pictures",
	'gutwa:user:type:general' => "%s's general gutwas",

	'gutwa:friends:type:video' => "Your friends' videos",
	'gutwa:friends:type:document' => "Your friends' documents",
	'gutwa:friends:type:audio' => "Your friends' audio",
	'gutwa:friends:type:image' => "Your friends' pictures",
	'gutwa:friends:type:general' => "Your friends' general gutwas",

	'gutwa:widget' => "gutwa widget",
	'gutwa:widget:description' => "Showcase your latest gutwas",

	'groups:enablegutwas' => 'Enable group gutwas',

	'gutwa:download' => "Download this",

	'gutwa:delete:confirm' => "Are you sure you want to delete this gutwa?",

	'gutwa:tagcloud' => "Tag cloud",

	'gutwa:display:number' => "Number of gutwas to display",

	'river:create:object:gutwa' => '%s uploaded the gutwa %s',
	'river:comment:object:gutwa' => '%s commented on the gutwa %s',

	'item:object:gutwa' => 'gutwas',

	'gutwa:newupload' => 'A new gutwa has been uploaded',
	'gutwa:notification' =>
'%s uploaded a new gutwa:

%s
%s

View and comment on the new gutwa:
%s
',

	/**
	 * Embed media
	 **/

		'gutwa:embed' => "Embed media",
		'gutwa:embedall' => "All",

	/**
	 * Status messages
	 */

		'gutwa:saved' => "Your gutwa was successfully saved.",
		'gutwa:deleted' => "Your gutwa was successfully deleted.",

	/**
	 * Error messages
	 */

		'gutwa:none' => "No gutwas.",
		'gutwa:uploadfailed' => "Sorry; we could not save your gutwa.",
		'gutwa:downloadfailed' => "Sorry; this gutwa is not available at this time.",
		'gutwa:deletefailed' => "Your gutwa could not be deleted at this time.",
		'gutwa:noaccess' => "You do not have permissions to change this gutwa",
		'gutwa:cannotload' => "There was an error uploading the gutwa",
		'gutwa:nogutwa' => "You must select a gutwa",
		'gutwa:noapikey' => 'Check that all fields are defined properly, please configure gutwa correctly in your admin tools or setting',
		
		
		
	/***********
	* Gutwa admin section  gutwa:warning:creditials:check:paypal:set
	**************/	
		
  'gutwa:warning:creditials:check:paypal:set' => 'Site Admin, Check gutwa plugin settings and fill e.g your email, website address and other paypal credentials',		
  'gutwa:warning:creditials:not:set' => 'Site Admin, Check that all Elgg Advertisement system fields are filled properly, please configure gutwa plugin correctly in your admin tools or setting for Openx and ELgg Ads system to work smoothly',
  'gutwa:settings:admin_email_paypalid' => 'This is your Paypal ID E-mail for example:   goodemail_biz@gmail.com',
  'gutwa:settings:admin_email_notifyid' => 'This is your Paypal E-mail to notify you e.g:  notify_me_please_biz@gmail.com',
  'gutwa:settings:paypal_url' => 'This is the full paypal url e.g :  https://www.sandbox.paypal.com/cgi-bin/webscr',
  'gutwa:settings:campaign_return_billing' => 'This is the your site campaign return billing e.g :  http://www.yoursite.com',
  'gutwa:settings:cancel_return_billing' => 'This is the your site Cancel return billing e.g :  http://www.yoursite.com',
  'gutwa:settings:return_renew_campaign' => 'This is the your site return renew Campaign e.g :  http://www.yoursite.com',
  'gutwa:settings:cancel_return_campaign' => 'This is the your site Cancel return renew Campaign e.g :  http://www.yoursite.com',
  		
		
		
		
		
	/*
	*  TM: modified gutwa start
	*
	*/	
		
	'gutwa' => 'gutwa',
	'item:object:gutwa_room' => 'gutwa rooms',
    'gutwa:owner' => "%s's rooms",
	'gutwa:friends' => "Friends\' Rooms",
	'gutwa:none' => 'No rooms',

	'admin:settings:gutwa' => 'gutwa Settings',
	'gutwa:settings:red5host' => 'This is the Hostname or domain where Openx is installed: Example:  YourOpenxsite.com',
	'gutwa:settings:red5port' => 'The second part of the Openx web address Server example : /Openx/www/api/v2/xmlrpc/',
	'gutwa:settings:default_red5port' => 'This is the openx API v2 xmlrpc gutwa location',
	'gutwa:settings:admin_user' => 'Openx Admin User',
	'gutwa:settings:admin_password' => 'Openx Admin User Password',
	'gutwa:settings:module_key' => 'gutwa Module key (vary for multiple instances using same gutwa Server)',
	'gutwa:settings:save:ok' => 'Successfully saved the gutwa settings',

	'gutwa:rooms' => 'gutwa Rooms',
	'gutwa:add' => 'Create a new room',
	'gutwa:rooms:edit' => 'Edit room',

	'gutwa:room:name' => 'Room name',
	'gutwa:room:type' => 'Room type',
	'gutwa:room:type:conference' => 'Conference Room',
	'gutwa:room:type:audience' => 'Audience Room',
	'gutwa:room:type:restricted' => 'Restricted Room',
	'gutwa:room:type:show_recording' => 'Show Recording',
	'gutwa:room:recording_id' => 'Available Recordings',
	'gutwa:room:max_users' => 'Max users',
	'gutwa:room:is_moderated' => 'Moderation modus',
	'gutwa:room:is_moderated_1' => 'Participants need to wait till the teacher enters the room',
	'gutwa:room:is_moderated_2' => 'Participants can already start (first User in Room becomes Moderator)',
	'gutwa:room:language' => 'Language',
	'gutwa:room:comment' => 'Comment',
	'gutwa:room:group' => 'Group',
	'gutwa:room:save:ok' => 'Successfully saved the gutwa room',
	'gutwa:room:save:fail' => 'Error saving the gutwa room',
	'gutwa:room:error:required_name' => 'Please fill in a name for the room',

	'gutwa:room:delete:ok' => 'Successfully deleted the gutwa room',
	'gutwa:room:delete:fail' => 'Error deleting the gutwa room',

	'gutwa:group_rooms' => 'gutwa Group Rooms',
	'gutwa:all_rooms' => 'All gutwa Rooms',
	'gutwa:not_found' => 'No rooms found.',	
	
	
	
	
	
	
	
	
		
		
);

add_translation("en", $english);