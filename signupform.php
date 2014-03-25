<?php 
    include ("includes/dbconnect.php");

    //status
    // 0 -> Works (Sat on page, show message)
    // 1 -> Error (Stay on page, show message)
    $return = [
        "status" => 1,
        "message" => "Undefined",
    ];

    function f($status, $message) {
        return [
            "status" => $status,
            "message" => $message,
        ];
    }

    function password_hash($email,$pass) {
        $salt = '1Ns3Ch4rtP4dProj3ctM4n4g3m3Nt';
        return hash('SHA512', $email . $pass . $salt );
    }

    function emailcheck() {
        if (!get_magic_quotes_gpc()) {
            $_POST['email'] = addslashes($_POST['email']);
        }
        $emailcheck = $_POST['email'];
        $dbquery = mysql_query("SELECT userEmail FROM INSE_tblUser WHERE userEmail = '$emailcheck'")
        or die(["status"=>1, "message"=>"MySQL Error"]);
        $dbquery2= mysql_num_rows($dbquery);
        if ($dbquery2 != 0) {
            $return = f(1, "This email address has already been registered");
            die(json_encode($return));
        }
    }

    function passwordcheck() {
        if ($_POST['password'] != $_POST['confpassword']) {
            $return = f(1, "Double check password fields. Passwords Must Match");
            die(json_encode($return));
        }
        $_POST['password'] =  password_hash($_POST['email'] , $_POST['password']);
        if (!get_magic_quotes_gpc()) {
            $_POST['password'] = addslashes($_POST['password']);
            $_POST['email'] = addslashes($_POST['email']);
        }
    }

    // Checking to see if the registration form has been submitted and basic validation
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confpassword'])) { 
        if (!$_POST['email'] | !$_POST['password'] | !$_POST['confpassword'] ) {
            $return = f(1, "All fields are required. Double Check Fields");
            die(json_encode($return));
        }
		
		if($_POST['email'] != $_POST['confemail'])
		{
			$return = f(1, "Email addresses do not match");
            die(json_encode($return));
		}
        emailcheck();
        passwordcheck();
        // Inserting the user's registration details to the INSE_tblUser table
        $insert = "INSERT INTO INSE_tblUser (userFName, userLName, userEmail, userPwd, uGrpID) VALUES ('".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['email']."', '".$_POST['password']."', '2')";
        mysql_query($insert);
        $return = f(0, "Account Created. Login to get started");
        die(json_encode($return));
    } else {
    }
?>