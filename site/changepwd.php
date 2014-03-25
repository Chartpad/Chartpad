<?php
function password_hash($email,$pass)
{
	$salt = '1Ns3Ch4rtP4dProj3ctM4n4g3m3Nt';
	return hash('SHA512', $email . $pass . $salt );
}
include ('includes/dbconnect.php');
$error=0;
if (isset($_POST['save'])) 
	{ 
	/*$message_currpass = "";
	$message_newpass = "";
	$message_confpwd = "";*/
		if (empty($_POST['currpass']))
		{
			$message_currpwd = 'Enter Current Password';
			$error=1;
		}
		else
		{
			$currpass = $_POST['currpass'];
		}
		if (empty($_POST['password']))
		{
			$message_newpwd = 'New Password Cannot Be Blank';
			$error=1;
		}
		else
		{
			if (!$_POST['password2'] == $_POST['password'])
			{
			
				$message_confpwd = 'Passwords do not Match';
				$error=1;
			}
			else
			{
				$newpass = $_POST['password2'];
			}
		}
		
		$email = $_SESSION['INSE_Email'];
		$currpass = password_hash($email , $currpass);
		$sql= mysql_query("SELECT * FROM INSE_tblUser WHERE userEmail = '".$email."'")
		or die(mysql_error());
		$result= mysql_num_rows($sql);
		if ($result == 0) 
		{
		die('<div class="error">User account not found.</div>');
		}
		while($INSE_account = mysql_fetch_array( $sql )) 	
		{
		if ($currpass != $INSE_account['userPwd'])
			{
				if ($currpass != $INSE_account['uPwd2Chg'])
				{
				echo('<div class="error">Incorrect password, please try again.<br />'.$message_currpwd.'</div>');
				}
				else 
				{
				$newpass = password_hash($email , $newpass);
				
				
				if($error == '1')
				{
					echo('<div class="error">Please Check Passwords and Try Again<br />'.$message_currpwd.$message_newpwd.$message_confpwd.'</div>');
				}
				if($error == '0')
				{
					mysql_query("UPDATE INSE_tblUser SET userPwd = '$newpass', uPwd2Chg = null WHERE userEmail = '$email'")
					or die(mysql_error());
					echo ("<div class='success'>Password Changed Successfully!</div>");
				}
				}
			}
			else
			{
				$newpass = password_hash($email , $newpass);
				
				
				if($error == '1')
				{
					echo('<div class="error">Please Check Passwords and Try Again<br />'.$message_currpwd.$message_newpwd.$message_confpwd.'</div>');
				}
				if($error == '0')
				{
					mysql_query("UPDATE INSE_tblUser SET userPwd = '$newpass' WHERE userEmail = '$email'")
					or die(mysql_error());
					echo ("<div class='success'>Password Changed Successfully!</div>");
				}
			}
		}
	}	
	else
	{
	}
	
 	
?>
<div id="changepwd">
<form class="form-inputs" method="post" name="changepwd-form">
    <div id="changepwd-output">
	</div>
	<ul>
        <li>
             <h1 class="title">Change Password</h1>
             
        </li>
        <li>
            <label for="currpass">Current Password:</label>
            <input type="password" name="currpass" placeholder="Enter Current Password"/>
            
        </li>
		<li>
            <label for="password">New Password:</label>
            <input type="password" name="password" placeholder="Enter New Password" />
			
        </li>
		<li>
            <label for="password2">Confirm Password:</label>
            <input type="password" name="password2" placeholder="Confirm New Password" />
			
        </li>
		<li>
        	<button class="submit" type="submit" name="save">Save</button>
        </li>
    </ul>
</form>
</div>
<p>&nbsp;</p>
