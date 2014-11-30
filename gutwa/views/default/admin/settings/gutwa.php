<?php

$plugin = elgg_get_plugin_from_id('gutwa');

$action = $vars['url'] . 'action/gutwa/admin_settings';
	
$form_body .= "<div><label>" . elgg_echo('gutwa:settings:red5host') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[red5host]', 'value' => $plugin->red5host)) . "</div>";

$form_body .= "<div><label>" . elgg_echo('gutwa:settings:red5port') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[red5port]', 'value' => $plugin->red5port)) . "<small>" . elgg_echo('gutwa:settings:default_red5port') . "</small></div>";

$form_body .= "<div><label>" . elgg_echo('gutwa:settings:admin_user') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[admin_user]', 'value' => $plugin->admin_user)) . "</div>";

$form_body .= "<div><label>" . elgg_echo('gutwa:settings:admin_password') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[admin_password]', 'value' => $plugin->admin_password)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:admin_email_paypalid') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[admin_paypalid]', 'value' => $plugin->admin_paypalid)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:admin_email_notifyid') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[admin_notifyid]', 'value' => $plugin->admin_notifyid)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:paypal_url') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[paypal_url]', 'value' => $plugin->paypal_url)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:campaign_return_billing') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[campaign_return_billing]', 'value' => $plugin->campaign_return_billing)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:cancel_return_billing') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[cancel_return_billing]', 'value' => $plugin->cancel_return_billing)) . "</div>";

$form_body .= "<div><label>" . elgg_echo('gutwa:settings:return_renew_campaign') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[return_renew_campaign]', 'value' => $plugin->return_renew_campaign)) . "</div>";


$form_body .= "<div><label>" . elgg_echo('gutwa:settings:cancel_return_campaign') . "</label><br />";
$form_body .= elgg_view("input/text",array('name' => 'params[cancel_return_campaign]', 'value' => $plugin->cancel_return_campaign)) . "</div>";


$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
<div>	
<?php
	echo elgg_view('input/form', array('action' => $action, 'body' => $form_body));
?>
</div>

