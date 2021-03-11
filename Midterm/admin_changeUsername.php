<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: admin_login.php");
        exit;
    }
    
    require_once "config.php";
    
    $admin_new_username = "";
    $admin_confirm_username = "";
    $admin_new_username_err = "";
    $admin_confirm_username_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["admin_new_username"]))){
            $admin_new_username_err = "Username cannot be empty";
        } else {
            $sql = "SELECT admin_id FROM admins WHERE admin_username = :admin_username";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["admin_new_username"]);
                
                if($statement -> execute()){
                    if($statement -> rowCount() == 1){
                        $admin_new_username_err = "This username is already taken";
                    } else{
                        $admin_new_username = trim($_POST["admin_new_username"]);
                    }
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }

        if(empty(trim($_POST["admin_confirm_username"]))){
            $admin_confirm_username_err = "Please confirm the Username.";
        } else{
            $admin_confirm_username = trim($_POST["admin_confirm_username"]);
            if(empty($admin_new_username_err) && ($admin_new_username != $admin_confirm_username)){
                $admin_confirm_username_err = "Username did not match.";
            }
        }
        
        if(empty($admin_new_username_err) && empty($admin_confirm_username_err)){
            $sql = "UPDATE admins SET admin_username = :admin_username WHERE admin_id = :admin_id";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_username", $param_username, PDO::PARAM_STR);
                $statement -> bindParam(":admin_id", $param_id, PDO::PARAM_INT);
                
                $param_username = $admin_new_username;
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
    <title>Change Username</title>
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
            <h2>Change Username</h2><br>
            <div class="form-group <?php echo (!empty($admin_new_username_err)) ? 'has-error' : ''; ?>">
                <label>New Username</label>
                <input type="text" name="admin_new_username" class="form-control" value="<?php echo $admin_new_username; ?>">
                <span class="help-block"><?php echo $admin_new_username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($admin_confirm_username_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Username</label>
                <input type="text" name="admin_confirm_username" class="form-control" value="<?php echo $admin_confirm_username; ?>">
                <span class="help-block"><?php echo $admin_confirm_username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change">
                <a class="btn btn-link" href="customer_profile.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>