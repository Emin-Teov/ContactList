<?php defined('EXEC') or die; ?>
<link rel="stylesheet" href="/<?php echo constant("SERVER"); ?>/resources/css/form.css">
<div class="form-container form-login">
    <form class="form" method="post">
        <?php $_SESSION["set_token"] = md5(uniqid().time());?>
        <input type="hidden" name="get_token" value="<?=$_SESSION["set_token"];?>">
        <input type="hidden" name="ctrl" value="User.login">
        <input class="form-line" min="3" max="45" type="text" name="username" placeholder="username" required="required" aria-required="true">
        <input class="form-line" min="3" max="45" type="password" name="password" placeholder="password" required="required" aria-required="true">
        <input type="submit" class="form-submit" align="center" name="is_submit" value="Login">
    </form>
</div>