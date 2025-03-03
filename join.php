<?php
$show_form = true;
$error_msg = NULL;
require_once __DIR__.'/contactform/vendor/autoload.php';
require_once __DIR__.'/contactform/config.php';

if (isset($_POST['submit'])) {
	require_once('prefs.php');
	if (isset($captcha) && $captcha == "yes") {
		session_start();
		if (isset($_SESSION['key'])) {
			if(md5($_POST['captcha']) != $_SESSION['key']) {
				setcookie(session_name(), '', time()-36000, '/');
				$_SESSION = array();
				session_destroy();

				echo "<p>The text you entered didn't match the image, please <a href='join.php'>try again</a>.</p>";
				include('footer.php');
				exit;
			}
			if (isset($_SESSION['key']) && isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-36000, '/');
				$_SESSION = array();
				session_destroy();
			}
		} else {
			echo "<p>The text you entered didn't match the image, please <a href='join.php'>try again</a>.</p>";
			include('footer.php');
			exit;
		}
	}
	include_once('header.php');

	if (isBot() !== false)
		$error_msg .= "No bots please! UA reported as: ".$_SERVER['HTTP_USER_AGENT'] . "\r\n";

	if (
		substr_count($_POST['comments'], 'https://') > 1 ||
		substr_count($_POST['comments'], 'http://') > 1
	)
		$error_msg .= "Too many URLs; we've assumed you're spam and 'lost' your application. Please try again without any extra URLs if you're a geniune person :)\r\n";

	$exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
	if (filesize(SPAMWDS) > 0) $spamlist = file(SPAMWDS);

	foreach ($_POST as $key => $val) {
		if (isset($spamlist)) {
			foreach ($spamlist as $spamword) {
				if (preg_match("/(".trim($spamword).")/i", $val)) {
					$error_msg .= "Your join application contains words in the spam list, please go back and remove references to obvious 'spam' material.\r\n";
				}
			}
		}
		if (preg_match($exploits, $val))
			$error_msg .= "No meta injection, please.\r\n";

		if (preg_match("/(<.*>)/i", $val))
			$error_msg .= "No HTML, please.\r\n";

		$clean[$key] = cleanUp($val);
	}

	// set default values for empty/unset fields
	if (empty($clean['dispemail']))
		$clean['dispemail'] = "no";

	if (!isset($favefield) || $favefield == "no" || !isset($clean['fave']))
		$clean['fave'] = NULL;

	// let's do some security and spam checks
	if (empty($clean['name']) || empty($clean['email']) || empty($clean['country']))
		$error_msg .= "Name, e-mail and country are required fields. \r\n";
	if (!preg_match("/^[a-zA-Z-'\s]*$/", $clean['name']))
		$error_msg .= "That name is not valid. Your name must contain letters only, and must be less than 15 characters. \r\n";
	if ($clean['dispemail'] != "yes" && $clean['dispemail'] != "no")
		$error_msg .= "You didn't choose whether or not you'd like to show your e-mail address on the member list. \r\n";
	if ($clean['fave'] != "" && (!preg_match("/^[a-zA-Z0-9-'\s]*$/", $clean['fave']) || strlen($clean['fave']) > 20))
		$error_msg .= "Your chosen \"favourite\" is not valid. It must contain letters and numbers only, and must be less than 20 characters. \r\n";
	if (!preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($clean['email'])))
		$error_msg .= "The email address you have used is not valid. \r\n";
	if (!empty($clean['url']) && !preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $clean['url']))
		$error_msg .= "The website url you provided is not valid. Please remove and try again or fix the URL.\r\n";
	if ($clean['country'] == "null" || (filesize(COUNTRIES) > 0 && checkTXTfile(COUNTRIES, $clean['country'], "country") === false))
		$error_msg .= "Please select a valid country. \r\n";

	if (filesize(IPBLOCKLST) > 0 && checkTXTfile(IPBLOCKLST, $_SERVER['REMOTE_ADDR'], "ip") === true) {
		echo "<p>Your IP is in the block list, that means you're not allowed to join at this time. \r\n</p>";
		exit(include('footer.php'));
	}
	if (filesize(NEWBIES) > 0 && checkTXTfile(NEWBIES, breakEmail($clean['email']), "email") === true) {
		echo "<p>You're already in the pending queue, you can't join twice!</p> \n";
		exit(include('footer.php'));
	}
	if (filesize(MEMBERS) > 0 && checkTXTfile(MEMBERS, breakEmail($clean['email']), "email") === true) {
		echo "<p>You're already a member of the fanlisting, you can't join twice!</p> \n";
		exit(include('footer.php'));
	}

	if ($error_msg == NULL) {
		$show_form = false;

		// attempt to break email to piss off spammers :p
		$clean['email'] = breakEmail(strtolower($clean['email']));

		// send off some emails
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		// Server settings
		$mail->setLanguage(CONTACTFORM_LANGUAGE);
		$mail->SMTPDebug = CONTACTFORM_PHPMAILER_DEBUG_LEVEL;
		$mail->isSMTP();
		$mail->Host = CONTACTFORM_SMTP_HOSTNAME;
		$mail->SMTPAuth = true;
		$mail->Username = CONTACTFORM_SMTP_USERNAME;
		$mail->Password = CONTACTFORM_SMTP_PASSWORD;
		$mail->SMTPSecure = CONTACTFORM_SMTP_ENCRYPTION;
		$mail->Port = CONTACTFORM_SMTP_PORT;
		$mail->CharSet = CONTACTFORM_MAIL_CHARSET;
		$mail->Encoding = CONTACTFORM_MAIL_ENCODING;

		if ($emailnewbies == "yes") {
			// Recipients
			$mail->setFrom($admin_email, $title);
			$mail->addAddress(fixEmail($clean['email']), $clean['name']);
			$mail->addReplyTo($admin_email);

			// Content
			$mail->Subject = "Thank you for joining $title";
			$mail->Body  = $thanksjoinMsg . "\n\n";
			$mail->Body .= "== Your Details == \n";
			$mail->Body .= "Name: {$clean['name']} \n";
			$mail->Body .= "Email: " . fixEmail($clean['email']) . " \n";
			$mail->Body .= "URL: {$clean['url']} \n";
			$mail->Body .= "Country: {$clean['country']} \n";
			if (isset($favefield) && $favefield == "yes") {
				$mail->Body .= "$favetext: {$clean['fave']} \n";
			}
			$mail->Body .= "Comments: {$clean['comments']} \n\n";

			$mail->send();
		}

		if ($emailadmin == "yes") {
			// Remove previous recipients
			$mail->ClearAllRecipients();

			$mail->setFrom($admin_email, $title);
			$mail->addAddress($admin_email, $admin_name);

			$mail->Subject = "New member at $title";

			$mail->Body  = "There's a new member at your $FLsubject fanlisting with the following details: \n\n";

			$mail->Body .= "Name: {$clean['name']} \n";
			$mail->Body .= "Email: " . fixEmail($clean['email']) . " \n";
			$mail->Body .= "URL: {$clean['url']} \n";
			$mail->Body .= "Country: {$clean['country']} \n";
			if (isset($favefield) && $favefield == "yes") {
				$mail->Body .= "$favetext: {$clean['fave']} \n";
			}
			$mail->Body .= "Comments: {$clean['comments']} \n";
			$mail->Body .= "IP: {$_SERVER['REMOTE_ADDR']} \n\n";

			$mail->Body .= "Manage members: {$FLurl}/admin.php?ap=manage_members&s=newbies";

			$mail->send();
		}

		// add the member to the newbies txt file
		if (addmember("$clean[name],$clean[email],$clean[dispemail],$clean[url],$clean[country],$clean[fave]\n")) {
			echo "<h1>Thank You</h1> \n <p>Thank you for joining $title, the fanlisting for $FLsubject!</p>";
		} else {
			echo "<h1>Oh Dear!</h1> \n <p>Your application could not be added at this time. Please contact the owner of the fanlisting for help.</p>";
		}
	}
}
if (!isset($_POST['submit']) || $show_form == true) {
	require_once('prefs.php');
	include_once('header.php');

?>
<h1>Join the Fanlisting</h1>
<p>To join the fanlisting, fill in your details below. Please do not use this form to update; use the <a href="update.php">update form</a> instead.</p>

<p>(Name, E-mail and Country are required fields.)</p>

<?php
	if ($error_msg != NULL) {
		echo "<p><strong style='color: red;'>ERROR:</strong><br />";
		echo nl2br($error_msg) . "</p>";
	}
	if (isset($_POST['country'])) $country = $_POST['country']; else $country = NULL;
?>

<form action="join.php" method="post"><p>
	<label>Name *</label><br /> <input type="text" id="name" name="name" value="<?php get_data("name"); ?>" required /> <br />
	<label>E-mail *</label><br /> <input type="email" id="email" name="email" value="<?php get_data("email"); ?>" required /> <br />
	<label>Display E-mail?</label><br />
	<input type="radio" id="dispemailyes" name="dispemail" value="yes" checked="checked" /> Yes
	<input type="radio" id="dispemailno" name="dispemail" value="no" /> No<br />
	<label>Website</label><br /> <input type="url" id="url" name="url" placeholder="https://" value="<?php get_data("url"); ?>" /> <br />
	<label>Country *</label><br /> <select name="country" id="country" required><option value="null">Please select a country:</option><?php get_countries($country); ?></select> <br />
<?php
	if (isset($favefield) && $favefield == "yes") {
?>
	<label><?php echo $favetext; ?></label><br /> <input type="text" id="fave" name="fave"  value="<?php get_data("fave"); ?>" /> <br />
<?php
	}
	if (isset($captcha) && $captcha == "yes") {
?>
	<img src="captcha.php" alt="" /><br />
	<label>Captcha</label><br /> <input type="text" name="captcha" id="captcha" /> <br />
<?php
	}
?>
	<label>Comments</label><br /> <textarea id="comments" name="comments" rows="3" cols="25"><?php get_data("comments"); ?></textarea><br />
	<input type="submit" name="submit" id="submit" value="Join" />
</p></form>

<?php
}
include('footer.php');
?>