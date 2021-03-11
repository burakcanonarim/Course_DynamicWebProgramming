<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: customer_login.php");
        exit;
    }
    
    require_once "config.php";
    
    $new_email = "";
    $confirm_email = "";
    $new_email_err = "";
    $confirm_email_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!filter_var(trim($_POST["new_email"]), FILTER_VALIDATE_EMAIL)){
            $new_email_err = "e-Mail must be standard format";
        } else {
            $sql = "SELECT cid FROM customers WHERE email = :email";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["new_email"]);
                
                if($statement -> execute()){
                    $new_email = trim($_POST["new_email"]);
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }

        if(empty(trim($_POST["confirm_email"]))){
            $confirm_email_err = "Please confirm the e-Mail.";
        } else{
            $confirm_email = trim($_POST["confirm_email"]);
            if(empty($new_email_err) && ($new_email != $confirm_email)){
                $confirm_email_err = "e-Mail did not match.";
            }
        }

        if(empty($new_email_err) && empty($confirm_email_err)){
            $sql = "UPDATE customers SET email = :email WHERE id = :id";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":email", $param_email, PDO::PARAM_STR);
                $statement -> bindParam(":id", $param_id, PDO::PARAM_INT);
                
                $param_email = $new_email;
                $param_id = $_SESSION["id"];

                if($statement -> execute()){
                    session_destroy();
                    header("location: customer_logout.php");
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
    <title>Change e-Mail</title>
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
            <h2>Change e-Mail</h2><br>
            <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
                <label>New e-Mail</label>
                <input type="text" name="new_email" class="form-control" value="<?php echo $new_email; ?>">
                <span class="help-block"><?php echo $new_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_email_err)) ? 'has-error' : ''; ?>">
                <label>Confirm e-Mail</label>
                <input type="text" name="confirm_email" class="form-control" value="<?php echo $confirm_email; ?>">
                <span class="help-block"><?php echo $confirm_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change">
                <a class="btn btn-link" href="customer_logout.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>