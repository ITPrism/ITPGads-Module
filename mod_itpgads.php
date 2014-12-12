<?php
/**
 * @package      ITPGads
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx', ""));

$app = JFactory::getApplication();
/** @var $app JApplicationSite */

// Get remote address
$remoteAddress = $app->input->server->get("REMOTE_ADDR");

// Get blocked addresses
$ips = explode(",", $params->get('blockedIPs'));
foreach ($ips as &$ip) {
    $ip = trim($ip);
}

$html = '<div class="itp-gads' . $moduleClassSfx . '">';

if (!in_array($remoteAddress, $ips)) {

    $customCode = $params->get('custom_code');

    if (!empty($customCode)) { // Display the custom code

        $html .= $customCode;

    } else {

        $publisherId = $params->get('publisherId');
        $slotId      = $params->get('slot');
        $channelId   = $params->get('channel');

        // Get size
        $adFormat = $params->get('format');
        $format   = explode("-", $adFormat);
        $width    = explode("x", $format[0]);
        $height   = explode("_", $width[1]);

        if ($params->get("ad_type", 1)) { // Asynchronous

            $html .= '
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:inline-block;width:' . $width[0] . 'px;height:' . $height[0] . 'px"
     data-ad-client="' . $publisherId . '"
     data-ad-slot="' . $slotId . '"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

        } else { // Synchronous

            $html .= '
<script type="text/javascript"><!--
    google_ad_client = "' . $publisherId . '";
    google_ad_slot = "' . $slotId . '";
    google_ad_width = ' . $width[0] . ';
    google_ad_height = ' . $height[0] . ';
    //-->
    </script>
    <script type="text/javascript"
    src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
        }
    }

} else {
    $html .= $params->get('altMessage');
}

$html .= "</div>";

echo $html;
