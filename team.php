<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'Meet the Team');
$smarty->display('header.tpl');
?>
    <div class="intro heading" id="1">TBA</div>
    <div class="intro">
        TBA
    </div>
    <div class="intro heading" id="2">TBA</div>
    <div class="intro">
        TBA
    </div>
    <div class="intro heading" id="3">TBA</div>
    <div class="intro">
        TBA
    </div>
<?php
$smarty->display('footer.tpl');
?>
