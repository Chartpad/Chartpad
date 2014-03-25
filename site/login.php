<?php
    include("includes/dbconnect.php");
    session_start();
    error_reporting(0);

    //status
    // 0 -> Works (Move along, no need for message) <-- Still use for testing
    // 1 -> Error (Stay on page, show message)
    // 2 -> redirect (move to chosen page, doesn't give user the privaledge to explicitly)
    $return = [
        "status" => 1,
        "message" => "Undefined",
    ];
    
    function password_hash($email, $pass) {
        $salt = '1Ns3Ch4rtP4dProj3ctM4n4g3m3Nt';
        return hash('SHA512', $email . $pass . $salt );
    }

    function f($status, $message) {
        return [
            "status" => $status,
            "message" => $message,
        ];
    }

    //Check Session
    if(isset($_SESSION['INSE_Email'])) { 
        $email = $_SESSION['INSE_Email']; 
        $password = $_SESSION['INSE_Account'];
        $accountcheck = mysql_query("SELECT * FROM INSE_tblUser WHERE userEmail = '$email'")
        or die(["status"=>1, "message"=>"MySQL Error"]);
        while($INSE_account = mysql_fetch_array( $accountcheck )) {
            if ($password != $INSE_account['password']) {
                $return = f(2, "index.php?changepwd");
                die(json_encode($return));
            } else {
                if ($INSE_account['userDisabled'] == 1) {
                    $return = f(1, "Account disabled, contact Chartpad for more information");
                    die(json_encode($return));
                } else {
                    $return = f(0, "Login Successful");
                    die(json_encode($return));
                }
            }
        }
    }

    //Check to see if the login form has been submitted 
    if (isset($_POST['email']) && isset($_POST['password'])) {
        if($_POST['email'] == "" || $_POST['password'] == "") {
            $return = f(1, "Username or Password Missing");
            die(json_encode($return));
        }

        //Check Account Details in database - if correct assign cookie
        if (!get_magic_quotes_gpc()) {
            $_POST['email'] = addslashes($_POST['email']);
        }
        
        $sql= mysql_query("SELECT * FROM INSE_tblUser WHERE userEmail = '".$_POST['email']."'")
        or die (["status"=>1, "message"=>"MySQL Error"]);

        $result = mysql_num_rows($sql);
        if ($result == 0) {
            $return = f(1, "No user account found, get one using the Signup form");
        }
        
        while($INSE_account = mysql_fetch_array($sql)) {
            $_POST['password'] = stripslashes($_POST['password']);
            $INSE_account['userPwd'] = stripslashes($INSE_account['userPwd']);
            $_POST['password'] = password_hash($_POST['email'] , $_POST['password']);
            if ($_POST['password'] != $INSE_account['userPwd']) {
                if ($_POST['password'] != $INSE_account['userPwd2']) {
                    $return = f(1, "Incorrect Password");
                } else {
                    $date = date('Y-m-d H:i:s');
                    $currentDate = strtotime($date);
                    $formatDate = date("Y-m-d H:i:s", $currentDate);

                    if($INSE_account['userPwd2Expire'] < $formatDate) {
                        $return = f(1, "Password Expired");
                        die(json_encode($return));
                    } else {
                        $usremail = $INSE_account['userEmail'];
                        $query = "UPDATE INSE_tblUser SET userPwd2=NULL, userPwd2Expire=NULL WHERE userEmail='$usremail'";
                        $sql= mysql_query($query)
                        or die (["status"=>1, "message"=>"MySQL Error"]);
                        
                        $_POST['email'] = stripslashes($_POST['email']); 
                         
                        $_SESSION['INSE_Email'] = $_POST['email'];
                        $_SESSION['INSE_Account'] = $_POST['password'];
                        $_SESSION['INSE_userID'] = $INSE_account['userID'];
                        $_SESSION['INSE_NameF'] = $INSE_account['userFName'];
                        $_SESSION['INSE_NameL'] = $INSE_account['userLName'];
                        $uGrpID = $INSE_account['uGrpID'];
                        $_SESSION['INSE_uGrpID'] = $uGrpID;
                        session_write_close();
                        if (isset($_SESSION['INSE_url'])) {
                            $return = f(2, $_SESSION['INSE_url']);
                            die(json_encode($return));
                        } else {
                            if ($INSE_account['userDisabled'] == 1) {
                                $return = f(1, "Account disabled, contact Chartpad for more information");
                                die(json_encode($return));
                            } else {
                                $return = f(2, "index.php?changepwd");
                                die(json_encode($return));
                            }
                        }
                    }
                }
            } else {
                $_POST['email'] = stripslashes($_POST['email']); 
                 
                $_SESSION['INSE_Email'] = $_POST['email'];
                $_SESSION['INSE_Account'] = $_POST['password'];
                $_SESSION['INSE_userID'] = $INSE_account['userID']; 
                $_SESSION['INSE_NameF'] = $INSE_account['userFName'];
                $_SESSION['INSE_NameL'] = $INSE_account['userLName'];
                $uGrpID = $INSE_account['uGrpID'];
                $_SESSION['INSE_uGrpID'] = $uGrpID;
                session_write_close();

                    if (isset($_SESSION['INSE_url'])) {
                        $return = f(2, $_SESSION['INSE_url']);
                        die(json_encode($return));
                    } else {
                    if ($INSE_account['userDisabled'] == 1) {
                        $return = f(1, "Account disabled, contact Chartpad for more information");
                        die(json_encode($return));
                    } else {
                        $return = f(2, "site/index.php");
                        die(json_encode($return));
                    }
                }
            }
        }
    } else {
        echo "Error: Nothing Sent";
    }
    echo json_encode($return);
?>