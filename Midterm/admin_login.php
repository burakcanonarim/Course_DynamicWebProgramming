<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: admin_welcome.php");
        exit;
    }
    
    require_once "config.php";

    $admin_username = "";
    $admin_password = "";
    $admin_email = "";
    $admin_username_err = "";
    $admin_password_err = "";
    $admin_email_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["admin_username"]))){
            $admin_username_err = "Username cannot be empty";
        } else {
            $admin_username = trim($_POST["admin_username"]);
        }
        
        if(empty(trim($_POST["admin_password"]))){
            $admin_password_err = "Password cannot be empty";
        } else {
            $admin_password = trim($_POST["admin_password"]);
        }
        
        if(empty($admin_username_err) && empty($admin_password_err)){
            $sql = "SELECT admin_id, admin_email, admin_username, admin_password FROM admins WHERE admin_username = :admin_username";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["admin_username"]);
                
                if($statement -> execute()){
                    if($statement -> rowCount() == 1){
                        if($row = $statement -> fetch()){
                            $admin_id = $row["admin_id"];
                            $admin_email = $row["admin_email"];
                            $admin_username = $row["admin_username"];

                            if($admin_username == "admin") {
                                $admin_hashed_password = password_hash($row["admin_password"], PASSWORD_DEFAULT);
                            } else {
                                $admin_hashed_password = $row["admin_password"];
                            }
                            
                            if(password_verify($admin_password, $admin_hashed_password)){
                                header("Refresh: 1;");
                                $_SESSION["loggedin"] = true;
                                $_SESSION["admin_id"] = $admin_id;
                                $_SESSION["admin_email"] = $admin_email;
                                $_SESSION["admin_username"] = $admin_username;
                            } else {
                                $admin_password_err = "The password is not valid.";
                            }
                        }
                    } else {
                        $admin_username_err = "The username is not valid.";
                    }
                } else {
                    echo "Oops! Something went wrong";
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
    <title>Login</title>
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
            <h2>Login</h2>
            <div class="form-group <?php echo (!empty($admin_username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="admin_username" class="form-control" value="<?php echo $admin_username; ?>">
                <span class="help-block"><?php echo $admin_username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($admin_password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="admin_password" class="form-control">
                <span class="help-block"><?php echo $admin_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>
</html>