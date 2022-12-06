<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->display('cist-eader.tpl');
$smarty->display('cist-index.tpl');
$smarty->display('footer.tpl');
?>
