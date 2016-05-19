
<?php

$form_token = hash('whirlpool', uniqid('auth', true));
$_SESSION['form_token'] = $form_token;

?>
<h2>Confirm Account</h2>
<form action="../validations/ConfirmAccount.php" method="get">
		<p>
			<label for="hash">Confirmation code</label>
			<input type="text" required id="hash" name="hash"/>
		</p>
		<p>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<input type="submit" value="&rarr; Confirm my account" />
		</p>
</form>
