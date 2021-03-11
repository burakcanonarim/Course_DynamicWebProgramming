<?php

    require_once "config.php";

    $admin_email = "";
    $admin_username = "";
    $admin_password = "";
    $admin_confirm_password = "";

    $admin_email_err = "";
    $admin_username_err = "";
    $admin_password_err = "";
    $admin_confirm_password_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!filter_var(trim($_POST["admin_email"]), FILTER_VALIDATE_EMAIL)){
            $admin_email_err = "e-Mail must be standard format";
        } else {
            $sql = "SELECT admin_id FROM admins WHERE admin_email = :admin_email";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["admin_email"]);
                
                if($statement -> execute()){
                    $admin_email = trim($_POST["admin_email"]);
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }
        
        if(empty(trim($_POST["admin_username"]))){
            $admin_username_err = "Username cannot be empty";
        } else {
            $sql = "SELECT admin_id FROM admins WHERE admin_username = :admin_username";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["admin_username"]);
                
                if($statement -> execute()){
                    if($statement -> rowCount() == 1){
                        $admin_username_err = "This username is already taken";
                    } else{
                        $admin_username = trim($_POST["admin_username"]);
                    }
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }
        
        if(empty(trim($_POST["admin_password"]))){
            $admin_password_err = "Password cannot be empty";
        } elseif(strlen(trim($_POST["admin_password"])) < 6){
            $admin_password_err = "Password must have at least <b>'6'</b> characters";
        } else {
            $admin_password = trim($_POST["admin_password"]);
        }
        
        if(empty(trim($_POST["admin_confirm_password"]))){
            $admin_confirm_password_err = "Please confirm password";
        } else {
            $admin_confirm_password = trim($_POST["admin_confirm_password"]);
            if(empty($admin_password_err) && ($admin_password != $admin_confirm_password)){
                $admin_confirm_password_err = "Please check password";
            }
        }
        
        if(empty($admin_email_err) && empty($admin_username_err) && empty($admin_password_err) && empty($admin_confirm_password_err)){
            $sql = "INSERT INTO admins (admin_email, admin_username, admin_password) VALUES (:admin_email, :admin_username, :admin_password)";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_email", $param_email, PDO::PARAM_STR);
                $statement -> bindParam(":admin_username", $param_username, PDO::PARAM_STR);
                $statement -> bindParam(":admin_password", $param_password, PDO::PARAM_STR);
                
                $param_email = $admin_email;
                $param_username = $admin_username;
                $param_password = password_hash($admin_password, PASSWORD_DEFAULT);

                if($statement -> execute()){
                    header("location: admin_welcome.php");
                } else {
                    echo "Something went wrong. Please try again later.";
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
    <title>Create New Admins</title>
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
            <h2>Create New Admins</h2>
            <p>Please fill this form to create an admin.</p>
            <div class="form-group <?php echo (!empty($admin_email_err)) ? 'has-error' : ''; ?>">
                <label>e-Mail</label>
                <input type="text" name="admin_email" class="form-control" value="<?php echo $admin_email; ?>">
                <span class="help-block"><?php echo $admin_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($admin_username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="admin_username" class="form-control" value="<?php echo $admin_username; ?>">
                <span class="help-block"><?php echo $admin_username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($admin_password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="admin_password" class="form-control" value="<?php echo $admin_password; ?>">
                <span class="help-block"><?php echo $admin_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($admin_confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="admin_confirm_password" class="form-control" value="<?php echo $admin_confirm_password; ?>">
                <span class="help-block"><?php echo $admin_confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create">
                <input type="reset" class="btn btn-default" value="Clear">
            </div>
            <p>Do not want to new admins? <a href="admin_welcome.php">Click to site</a></p>
        </form>
    </div>    
</body>
</html>