<?php

$form_token = hash('whirlpool', uniqid('auth', true));
$_SESSION['form_token'] = $form_token;

?>
<h2>Sign in</h2>
<form action="../validations/CreateAccount.php" method="post">
		<p>
			<label for="username">Username</label>
			<input type="text" id="username" name="username" maxlength="20" value="<?php echo $_GET['login']; ?>"/>
		</p>
		<p>
			<label for="mail">Email</label>
			<input type="text" id="mail" name="mail" maxlength="50" value="<?php echo $_GET['mail']; ?>"/>
		</p>
		<p>
			<label for="password">Password</label>
			<input type="password" id="password" name="password"/>
		</p>
		<p>
			<label for="confirm_password">Confirm password</label>
			<input type="password" id="confirm_password" name="confirm_password"/>
		</p>
		<p>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<input type="submit" value="&rarr; Sign in" />
		</p>
</form>
