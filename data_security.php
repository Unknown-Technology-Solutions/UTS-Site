<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Data Security');
$smarty->display('header.tpl');
$smarty->display('data_security.tpl');
$smarty->display('footer.tpl');
?>
