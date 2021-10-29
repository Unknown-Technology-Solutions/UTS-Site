<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Privacy Policy');
$smarty->display('header.tpl');
$smarty->display('privacy.tpl');
$smarty->display('footer.tpl');
?>
