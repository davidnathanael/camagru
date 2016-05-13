<?php

$form_token = hash('whirlpool', uniqid('auth', true));
$_SESSION['form_token'] = $form_token;

?>
<h2>Reset password</h2>
<form action="../validations/ForgotPassword.php" method="post">
		<p>
			<label for="mail">Email</label>
			<input type="text" id="mail" name="mail"/>
		</p>
		<p>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<input type="submit" value="&rarr; Log in" />
		</p>
</form>
