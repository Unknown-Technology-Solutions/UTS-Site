<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->display('header.tpl');
$smarty->display('index.tpl');
$smarty->display('footer.tpl');
?>
