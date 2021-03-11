<?php

    require_once "config.php";

    $email = "";
    $username = "";
    $password = "";
    $confirm_password = "";

    $email_err = "";
    $username_err = "";
    $password_err = "";
    $confirm_password_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
            $email_err = "e-Mail must be standard format";
        } else {
            $sql = "SELECT id FROM customers WHERE email = :email";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["email"]);
                
                if($statement -> execute()){
                    $email = trim($_POST["email"]);
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }
        
        if(empty(trim($_POST["username"]))){
            $username_err = "Username cannot be empty";
        } else {
            $sql = "SELECT id FROM customers WHERE username = :username";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["username"]);
                
                if($statement -> execute()){
                    if($statement -> rowCount() == 1){
                        $username_err = "This username is already taken";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Password cannot be empty";
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have at least <b>'6'</b> characters";
        } else {
            $password = trim($_POST["password"]);
        }
        
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Please check password";
            }
        }
        
        if(empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            $sql = "INSERT INTO customers (email, username, password) VALUES (:email, :username, :password)";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":email", $param_email, PDO::PARAM_STR);
                $statement -> bindParam(":username", $param_username, PDO::PARAM_STR);
                $statement -> bindParam(":password", $param_password, PDO::PARAM_STR);
                
                $param_email = $email;
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                if($statement -> execute()){
                    header("location: customer_login.php");
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
    <title>Register</title>
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
            <h2>Register for Customers</h2>
            <p>Please fill this form to create an account.</p>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>e-Mail</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register">
                <input type="reset" class="btn btn-default" value="Clear">
            </div>
            <p>Already have an account? <a href="customer_login.php">Login here</a></p>
        </form>
    </div>    
</body>
</html>