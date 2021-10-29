<?php
include_once('./functions.php');
$smarty = create_smarty();
$smarty->assign('title', 'UTS Software');
$smarty->display('header.tpl');
?>
    <div class="intro heading">Software</a>
      <div class="intro">
        Since we here at UTS develop software, we thought we should share it. Along with the following software being free, we also provide support! You can find our contact info <a href="index.html#contact">here</a>.
        <ul>
          <li>
            <a href="#"></a>Network Monitor and Reporter (NMR) | V0.60 <br />
          </li>
          <li>
            <a href="#"></a>Power-on Notifier | V0.1 <br />
            <a href="downloads/PowerOnNotifier.exe">Windows Download</a> <br />
            <!--<a href="/downloads/PowerOnNotifier" download>Linux Download</a>-->
          </li>
        </ul>
        For any of the above programs, feel free to <a href="mailto://support@unknownts.com">email us</a> if you need support or have questions.
      </div>
    </div>
<?php
$smarty->display('footer.tpl');
?>
