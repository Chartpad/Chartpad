<?php
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) 
{
		$inputName = $_POST['name'];
		$inputEmail = $_POST['email'];
		$inputMessage = $_POST['message'];
		$to = 'chart@chartpad.co.uk';
		$subject = 'Message from Chartpad Website';
		$message = '<h1>New message received from Chartpad website</h1>
		<p>Hello, <br /><br />' . $inputName . ' (' . $inputEmail . ') has sent a message from the Chartpad website.</p>
		<p>Message: ' . $inputMessage. '.</p>

		<p>To reply to ' . $inputName . ', just reply to this email.</p>
		
		<p>Thanks<br />The Chartpad Team</p><p>P.S. Mikey Broke It</p>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $inputName . ' <' . $inputEmail . '>' . "\r\n";
		mail($to, $subject, $message, $headers);
		echo"form submitted";
}
?>
