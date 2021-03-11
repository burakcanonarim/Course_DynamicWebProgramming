<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: customer_welcome.php");
        exit;
    }
    
    require_once "config.php";
    $username = "";
    $password = "";
    $email = "";
    $username_err = "";
    $password_err = "";
    $email_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["username"]))){
            $username_err = "Username cannot be empty";
        } else {
            $username = trim($_POST["username"]);
        }
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Password cannot be empty";
        } else {
            $password = trim($_POST["password"]);
        }
        
        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, email, username, password FROM customers WHERE username = :username";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_POST["username"]);
                
                if($statement -> execute()){
                    if($statement -> rowCount() == 1){
                        if($row = $statement -> fetch()){
                            $id = $row["id"];
                            $email = $row["email"];
                            $username = $row["username"];
                            $hashed_password = $row["password"];
                            
                            if(password_verify($password, $hashed_password)){
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;
                                $_SESSION["username"] = $username;
                                header("location: customer_welcome.php");
                            } else {
                                $password_err = "The password is not valid.";
                            }
                        }
                    } else {
                        $username_err = "The username is not valid.";
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
            <h2>Login for Customers</h2>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="customer_register.php">Sign up now</a></p>
        </form>
    </div>
</body>
</html>