<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Careers');
$smarty->display('header.tpl');
?>
    <div class="intro heading">Coming Soon!</div>
    <div class="intro">
      This page is a work in progress
    </div>
<?php
$smarty->display('footer.tpl');
?>
