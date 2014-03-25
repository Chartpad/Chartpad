<?php
	session_start();
	if(isset($_SESSION['INSE_Email'])) {
		header("Location: ./site/index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="description" content="Chartpad is a web-based project management solution. Create customised charts from common data. 
		Completely free to use. Export as a number of different formats. Enter data using easy to use and well designed interfaces. 
		Uses web technologies to render the charts in real-time, on your browser. Get started now by logging in or signing up" />
		<meta name="keywords" content="INSE, Chartpad, Chart, GANTT, PERT, WBT, Project, Management, Planning, Task" />
		<meta name="author" content="Brian Brewer, Michael Goodwin, Jill Pomares, Michael Sharp, Scott Walton" />
		<meta charset="UTF-8" /> 
		<title>Chartpad - Web-based Project Management Solution</title>
		<link href="includes/style-index.css" rel="stylesheet">
		<script src="scripts/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
		<script src="scripts/script-index.js"></script>
	</head>
	<!-- Use this body tag to enable message display lightbox upon page load -->
	<!-- <body  onload="$('#message-click').trigger('click');">  -->
	<body>
		<div class="header">
			<img src="images/logo_sm.png" alt="Chartpad">
			<span id="shareToggle">Share</span>
			<span id="contact-btn">Contact</span>
			<span id="faq-btn">FAQ</span>
		</div>
		<div class="content">
			<div class="info">
				<div class="main">
					<span id="main_1"><img src="/images/main1.png" alt="Chartpad is a web-based project management solution"></span>
					<span id="main_2"><img src="/images/main2.png" alt="Create customised charts from a common set of data"></span>
					<span id="main_3"><img src="images/main3.png" alt="Absolutely no cost to you"></span>
					<span id="main_4"><img src="/images/main4.png" alt="Export as a number of different formats" /></span>
					<span id="main_5"><img src="/images/main5.png" alt="" /></span>
					<span id="main_6"><img src="/images/main6.png" alt="Uses web technologies to render the charts in real-time, on your browser"></span>
					<span id="main_7"><img src="/images/main7.png" alt="Get started now by logging in or signing up"></span>
				</div>
				<ul class="option">
					<li id="option_1" class="option1"></li>
					<li id="option_2" class="option2"></li>
					<li id="option_3" class="option3"></li>
					<li id="option_4" class="option4"></li>
					<li id="option_5" class="option5"></li>
					<li id="option_6" class="option6"></li>
					<li id="option_7" class="option7"></li>
				</ul>
			</div>
			<div class="signup">
				<span>Sign-up for a free account</span>
				<button id="get-started">Get Started</button>
			</div>
			<div class="login" id="login-form">
			
			<div id="loginMessage"></div>
				<form class="form" action="site/login.php" method="post" name="login-form">
					<ul>
						<li>
							<input type="email" name="email" placeholder="Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
						</li>
						<li>
							<input type="password" name="password" placeholder="Password" />
						</li>
						<li>
							<a href="" id="forgot-pass-btn">Forgot Password?</a>
							<button class="submit" type="submit" name="login">Login</button>
							<button class="hidden" id="message-click" type="submit" name="login">#</button>
						</li>
					</ul>
				</form>
				
			</div>
		</div>
		<div id="sign-up">
			<form class="form-inputs" action="signupform.php" method="post" name="signup-form">
				<ul>
					<li>
						 <h2>Sign Up for your FREE Chartpad account</h2>
					</li>
					<li>
						<label for="sign-up-firstname">First Name:</label>
						<input id="sign-up-firstname" type="text"  name="firstname" placeholder="Enter First Name" required="required" />
					</li>
					<li>
						<label for="sign-up-lastname">Last Name:</label>
						<input id="sign-up-lastname" type="text"  name="lastname" placeholder="Enter Last Name" required="required" />
					</li>
					<li>
						<label for="sign-up-email">Email:</label>
						<input id="sign-up-email" type="email" name="email" placeholder="Enter Email Address" required="required" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"/>
					</li>
					<li>
						<label for="sign-up-confemail">Confirm Email:</label>
						<input id="sign-up-confemail" type="email" name="confemail" placeholder="Confirm Email Address" required="required" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"/>
					</li>
					<li>
						<label for="sign-up-password">Password:</label>
						<input id="sign-up-password" type="password" name="password" placeholder="Create Password" required="required" />
					</li>
					<li>
						<label for="sign-up-confpassword">Confirm Password:</label>
						<input id="sign-up-confpassword" type="password" name="confpassword" placeholder="Confirm Password" required="required" />
					</li>
					<li>
						<button class="submit" type="submit" name="Register">Submit Form</button>
					</li>
				</ul>
			</form>
			<div id="sign-up-output"></div>
			<button class="button-sm close" type="submit" name="close">Close</button>
		</div>
		<div id="forgot-pass">
			<form class="form-inputs" action="forgotpass.php" method="post" name="forgot-password">
				<ul>
					<li>
						 <h2>Forgotten Password</h2>
					</li>
					<li>
						<label for="forgot-pass-email">Email:</label>
						<input id="forgot-pass-email" type="email" name="email" placeholder="Enter Email Address" required="required" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"/>
					</li>
					<li>
						<button class="submit" type="submit" name="Forgot-Pass">Submit Form</button>
					</li>
				</ul>
			</form>
				<div id="forgot-pass-output"></div>
			<button class="button-sm close" type="submit" name="close">Close</button>
		</div>
		<div id="contact">
				<form class = "form-inputs" action="site/contact.php" method="POST" name="contact">
				<ul>
					<li>
						<label for="name">Name:</label>
						<input type="text" name="name" required="required"/>
					</li>
					<li>
						<label for="email">Email:</label>
						<input type="email" name="email" required="required" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"/>
					</li>
					<li>
						<label for="message">Message:</label>
						<textarea name="message" cols="40" rows="6" required="required"></textarea>
					</li>
					<li>
						<button class="submit" type="submit">Submit Form</button>
					</li>
				</ul>
				</form>
			<button class="button-sm close" type="submit" name="close">Close</button>
		</div>
		<div id="message">
				<?php
					include ("message.php");
				?>
			<button class="button-sm close" type="submit" name="close">Close</button>
		</div>
		<div id="faq">
			<div id="faq-content">
				<?php
					include ("site/faq.php");
				?>
			</div>
			<button class="button-sm close" type="submit" name="close">Close</button>
		</div>
		<div class="shareBox">
			<ul>
				<li><img id="shareFacebook" src="images/facebook.png" /></li>
				<li><img id="shareTwitter" src="images/twitter.png" /></li>
				<li><img id="shareGooglePlus" src="images/googleplus.png" /></li>
				<li><img id="shareLinkedIn" src="images/linkedin.png" /></li>
			</ul>
		</div>
	</body>
</html>