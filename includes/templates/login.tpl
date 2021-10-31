        <div class="intro" style="margin-top:25px;margin-bottom:25px;">
            <div class="login">
                <div class="bigger_heading center">UTS Portal</div> <br />
                <form action="./uts_login.php" method="POST" class="login-form center">
                    <input type="text" id="username" name="username" placeholder="Username" class="login-text" /> <br />
                    <input type="password" name="password" placeholder="Password" class="login-text" /> <br />
                    <!--<input type="checkbox" name="remember" class="login-text" />Remember me<br />-->
                    <button type="submit" name="submit" class="button-login"><span>Sign in</span></button> <br />
                </form>
            </div>
        </div>
<script>
window.addEventListener('load', (event) => {
  document.getElementById('username').click();
});
</script>
