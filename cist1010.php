<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->display('cist-header.tpl');
$smarty->display('cist-index.tpl');
$smarty->display('footer.tpl');
?>
