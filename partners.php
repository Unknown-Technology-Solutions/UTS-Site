<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign("title", "Partners");
$smarty->display('header.tpl');
?>
<div class="intro heading" id="1">Our Partners</div>
<div class="intro">
    Unknown Technology Solutions takes pride in many things. One of them is the partnerships we forge.
</div>
<div class="grid-row">
    <div class="grid-column">
        <img src="https://infinitewizardry.com/logo.png" alt="Infinite Wizardy Games logo"> <br />
        Infinite Wizardy Games
    </div>
    <div class="grid-column">
        <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png" alt="Google"> <br />
        Google
    </div>
    <div class="grid-column">
        <img src="https://apex.oracle.com/assets/media/company-logos/oracle-white.png?v=1" alt="Oracle"> <br />
        Oracle
    </div>
</div>
<div class="grid-row">
    <div class="grid-column">
        <img src="https://apex.oracle.com/assets/media/company-logos/oracle-white.png?v=1" alt="Oracle"> <br />
        Oracle
    </div>

    <div class="grid-column">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/2560px-Amazon_logo.svg.png" alt=""> <br />
        Amazon
    </div>

    <div class="grid-column">
        <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png" alt="Google"> <br />
        Google
    </div>
</div>
<br />
<?php
$smarty->display('footer.tpl');
?>