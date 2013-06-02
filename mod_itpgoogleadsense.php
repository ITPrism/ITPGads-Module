<?php
/**
 * @package      ITPGoogleAdSense
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPGoogleAdSense is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined( '_JEXEC' ) or die;

$app = JFactory::getApplication();
/** @var $app JSite **/

$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx', ""));

// Get remote address
$remoteAddress  = $app->input->server->get("REMOTE_ADDR");

// Get blocked addresses
$ips = explode(",", $params->get('blockedIPs'));
foreach($ips as &$ip) {
    $ip = trim($ip);
}

$html = '<div class="itp-gads-'.$moduleClassSfx.'">';

if (!in_array($remoteAddress, $ips)) {
    
    $customCode     = $params->get('custom_code');
    
    if(!empty($customCode)) { // Display the custom code
        
        $html .= $customCode;
        
    } else {
    
        $publisherId    = $params->get('publisherId');
        $slotId         = $params->get('slot');
        $channelId      = $params->get('channel');
        
        // Get size
        $adFormat   = $params->get('format');
        $format     = explode("-", $adFormat);
        $width      = explode("x", $format[0]);
        $height     = explode("_", $width[1]);
        
        $html .=  '
        <script type="text/javascript"><!--
            google_ad_client = "' . $publisherId . '";
            google_ad_slot = "' . $slotId . '";
            google_ad_width = ' . $width[0] . ';
            google_ad_height = ' . $height[0] . ';
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>';
        
    }
    
} else {
    $html .=  $params->get('altMessage');
}

$html .= "</div>";

echo $html;
