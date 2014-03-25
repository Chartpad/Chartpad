
<?php
	include ("includes/dbconnect.php");

	function rand_string( $length ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.,/?!&*";
		return substr(str_shuffle($chars),0,$length);
	}

	function password_hash($email,$pass)
	{
		$salt = '1Ns3Ch4rtP4dProj3ctM4n4g3m3Nt';
		return hash('SHA512', $email . $pass . $salt );
	}

	if (isset($_POST['email']))
	{
		if (!get_magic_quotes_gpc())
		{
			$_POST['email'] = addslashes($_POST['email']);
		}
		
		$sql= mysql_query("SELECT * FROM INSE_tblUser WHERE userEmail = '".$_POST['email']."'")
		or die(mysql_error());
		$result= mysql_num_rows($sql);
		if ($result == 0) 
		{
			die('Email Address not Recognised.</a>');
		}
		
		while($INSE_account = mysql_fetch_array( $sql )) 	
		{
			$_POST['email'] = stripslashes($_POST['email']); 
			$email = $_POST['email'];
			$namef = $INSE_account['userFName'];
		}
		
		$tmpPass = rand_string(10);
		$tmpPassHash = password_hash($email , $tmpPass);
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*30);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		$query = "UPDATE INSE_tblUser SET userPwd2='$tmpPassHash', userPwd2Expire='$formatDate', uPwd2Chg ='$tmpPassHash' WHERE userEmail='$email'";
		$sql= mysql_query($query)
		or die(mysql_error());
		
		$to = $email;
		$subject = 'Reset Your Chartpad Password';
		$message = '<h1>Forgot your password?</h1>
		<p>Hello ' . $namef . ',</p>
		<p>Chartpad received a request to reset the password for your Chartpad account (' . $email. ').<br />
		If you did not request this password, you can simply ignore this email.</p>

		<p>To reset your password, please login to Chartpad with the password shown below and create a new password. <br />
		This password will automatically expire after 30 Minutes. After this time, if you have not changed your password, you will need to
		return to the <a href="http://www.chartpad.co.uk/forgotpass.php">Forgotten Password</a> page to request a new one.</p>
		
		<p>Email: ' . $email . '<br />
		Password: ' . $tmpPass . '</p>

		<p>Thanks<br />The Chartpad Team</p>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Chartpad Support <noreply@chartpad.co.uk>' . "\r\n";
		mail($to, $subject, $message, $headers, '-fnoreply@chartpad.co.uk');
		echo "<p>We've sent password reset instructions to your email address.</p>
		<p>If you don't receive instructions within a minute or two, check your email's spam and junk filters, or try resending your request.</p>";
	} else {
		die('You must enter the email address you registered with.');
	}
?>