<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Operations');
$smarty->display('header.tpl');
?>
    <div class="intro heading" id="1">The Panopticon</div>
    <div class="intro">
        When faced with tomorrow's security challenges today, we decided we needed a new approach to security; enter The&nbsp;Panopticon.<br />
        The&nbsp;Panopticon is our physical and digital security center.
        Here we monitor all network traffic, security cameras, and alarms. With The&nbsp;Panopticon, we are not only able to respond to active threats, but predict and react to threats before they become a reality.
        By analyzing all activity we are able to spot trends and attacks before they even happen saving time and money.<br />
        The&nbsp;Panopticon is our defense, what's yours? 
    </div>
    <div class="intro heading" id="2">Current Security Notices</div>
    <div class="intro">
        No active security alerts for UTS.
    </div>
    <div class="intro heading" id="3">Network Status</div>
    <div class="intro">
        With C1 being retired, C2 has launched! With a higher server and network capacity than ever before, it will allow us to serve more customers, better! 
    </div>
<?php
$smarty->display('footer.tpl');
?>
