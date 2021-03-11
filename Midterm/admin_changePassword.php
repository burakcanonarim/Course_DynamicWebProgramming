<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: admin_login.php");
        exit;
    }
    
    require_once "config.php";

    $admin_new_password = "";
    $admin_confirm_password = "";
    $admin_new_password_err = "";
    $admin_confirm_password_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["admin_new_password"]))){
            $admin_new_password_err = "Please enter the new password.";
        } elseif(strlen(trim($_POST["admin_new_password"])) < 6){
            $admin_new_password_err = "Password must have atleast 6 characters.";
        } else{
            $admin_new_password = trim($_POST["admin_new_password"]);
        }
        
        if(empty(trim($_POST["admin_confirm_password"]))){
            $admin_confirm_password_err = "Please confirm the password.";
        } else{
            $admin_confirm_password = trim($_POST["admin_confirm_password"]);
            if(empty($admin_new_password_err) && ($admin_new_password != $admin_confirm_password)){
                $admin_confirm_password_err = "Password did not match.";
            }
        }
        
        if(empty($admin_new_password_err) && empty($admin_confirm_password_err)){
            $sql = "UPDATE admins SET admin_password = :admin_password WHERE admin_id = :admin_id";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_password", $param_password, PDO::PARAM_STR);
                $statement -> bindParam(":admin_id", $param_id, PDO::PARAM_INT);

                $param_password = password_hash($admin_new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["admin_id"];
                
                if($statement -> execute()){
                    session_destroy();
                    header("location: admin_logout.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                unset($statement);
            }
        }
        unset($connection);
    }
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            background: #c0deea;
        }
        form, .content {
            width: 30%;
            margin: 30px auto;
            padding: 20px;
            border: 5px solid #B0C4DE;
            background: white;
            background-image: linear-gradient(white, #f9d423);
            border-radius: 0px 0px 10px 10px;
            }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <h2>Change Password</h2><br>
            <div class="form-group <?php echo (!empty($admin_new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="admin_new_password" class="form-control" value="<?php echo $admin_new_password; ?>">
                <span class="help-block"><?php echo $admin_new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($admin_confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="admin_confirm_password" class="form-control">
                <span class="help-block"><?php echo $admin_confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change">
                <a class="btn btn-link" href="admin_profile.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>