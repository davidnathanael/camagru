<?php

$form_token = hash('whirlpool', uniqid('auth', true));
$_SESSION['form_token'] = $form_token;

?>
<h2>Reset password</h2>
<form action="../validations/ResetPassword.php" method="post">
		<p>
			<label for="password">New password</label>
			<input type="password" id="password" name="password"/>
		</p>
		<p>
			<label for="confirm_password">Confirm password</label>
			<input type="password" id="confirm_password" name="confirm_password"/>
		</p>
		<p>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" />
			<input type="submit" value="&rarr; Submit" />
		</p>
</form>
