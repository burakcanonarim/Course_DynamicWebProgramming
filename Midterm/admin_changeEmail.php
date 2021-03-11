<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: admin_login.php");
        exit;
    }
    
    require_once "config.php";
    
    $admin_new_email = "";
    $admin_confirm_email = "";
    $admin_new_email_err = "";
    $admin_confirm_email_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!filter_var(trim($_POST["admin_new_email"]), FILTER_VALIDATE_EMAIL)){
            $admin_new_email_err = "e-Mail must be standard format";
        } else {
            $sql = "SELECT admin_id FROM admins WHERE admin_email = :admin_email";

            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_email", $param_email, PDO::PARAM_STR);
                $param_email = trim($_POST["admin_new_email"]);
                
                if($statement -> execute()){
                    $admin_new_email = trim($_POST["admin_new_email"]);
                } else{
                    echo "Oops! Something went wrong";
                }
                unset($statement);
            }
        }

        if(empty(trim($_POST["admin_confirm_email"]))){
            $admin_confirm_email_err = "Please confirm the e-Mail.";
        } else{
            $admin_confirm_email = trim($_POST["admin_confirm_email"]);
            if(empty($admin_new_email_err) && ($admin_new_email != $admin_confirm_email)){
                $admin_confirm_email_err = "e-Mail did not match.";
            }
        }

        if(empty($admin_new_email_err) && empty($admin_confirm_email_err)){
            $sql = "UPDATE admins SET admin_email = :admin_email WHERE admin_id = :admin_id";
            
            if($statement = $connection -> prepare($sql)){
                $statement -> bindParam(":admin_email", $param_email, PDO::PARAM_STR);
                $statement -> bindParam(":admin_id", $param_id, PDO::PARAM_INT);
                
                $param_email = $admin_new_email;
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
            <div class="form-group <?php echo (!empty($admin_new_email_err)) ? 'has-error' : ''; ?>">
                <label>New e-Mail</label>
                <input type="text" name="admin_new_email" class="form-control" value="<?php echo $admin_new_email; ?>">
                <span class="help-block"><?php echo $admin_new_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($admin_confirm_email_err)) ? 'has-error' : ''; ?>">
                <label>Confirm e-Mail</label>
                <input type="text" name="admin_confirm_email" class="form-control" value="<?php echo $admin_confirm_email; ?>">
                <span class="help-block"><?php echo $admin_confirm_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change">
                <a class="btn btn-link" href="customer_logout.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>