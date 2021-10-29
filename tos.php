<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Terms Of Service');
$smarty->display('header.tpl');
$smarty->display('tos.tpl');
$smarty->display('footer.tpl');
?>
