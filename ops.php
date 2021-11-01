<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Operations');
$smarty->display('header.tpl');
?>
    <div class="intro heading" id="1">The Panopticon</div>
    <div class="intro">
        When faced with tomorrow's security challenges today, we decided we needed a new approach to security; enter The&nbsp;Panopticon.<br />
        The&nbsp;Panopticon is our network and security center, physical and digital.
        Here we monitor all network traffic and security systems. With The&nbsp;Panopticon active, we are not only able to respond to threats that are active, but threats that are developing.
        By analyzing traffic we are able to spot trends and attacks before they even happen saving time and money.<br />
        The&nbsp;Panopticon is our defense, what's yours? 
    </div>
    <div class="intro heading" id="2">Current Security Notices</div>
    <div class="intro">
        TBA
    </div>
    <div class="intro heading" id="3">Network Status</div>
    <div class="intro">
        TBA
    </div>
<?php
$smarty->display('footer.tpl');
?>
