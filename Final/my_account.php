<?php include('includes/header.php'); ?>

  <div class="content_wrapper">
  
  <?php if(isset($_SESSION['user_id'])){ ?>
  
  <div class="user_container">
  
  <div class="user_content">
  
  <?php 
  if(isset($_GET['action'])){
    $action = $_GET['action'];
  }else{
    $action = '';
  }
  
  switch($action){
  
  case "edit_email";
  include('users/edit_email.php');
  break;
  
  case "edit_name";
  include('users/edit_name.php');
  break;
  
  case "user_profile_picture";
  include('users/user_profile_picture.php');
  break;
  
  case "change_password";
  include('users/change_password.php');
  break;
  
  case "delete_account";
  include('users/delete_account.php');
  break;  
  
  default;
  echo "This area is for my profile informations!";
  break;
  }
  
  
  ?>
  
  </div>
  
  <div class="user_sidebar">
  
  <?php 
  $run_image = mysqli_query($con,"select * from users where id='$_SESSION[user_id]'");
  
  $row_image = mysqli_fetch_array($run_image);
  
  if($row_image['image'] !=''){  
  ?>
  
  <div class="user_image" align="center">
    <img src="upload-files/<?php echo $row_image['image']; ?>" />
  </div>
  
  <?php }else{ ?>
  
  <div class="user_image" align="center">
    <img src="images/profile-icon.png" />
  </div>
  
  <?php } ?>
  
  <ul>
    <li><a href="my_account.php?action=my_order">My Profile</a></li>
	<li><a href="my_account.php?action=edit_email">Edit Email</a></li>
	<li><a href="my_account.php?action=edit_name">Edit Name</a></li>
	<li><a href="my_account.php?action=user_profile_picture">Edit Profile Picture</a></li>
	<li><a href="my_account.php?action=change_password">Edit Password</a></li>
	<li><a href="my_account.php?action=delete_account">Delete Account</a></li>
  </ul>
  
  </div>
  
  </div>
  
  <?php }else{ ?>
  
  <h1>Account Setting Page</h1>
  
  <h5><a href="index.php?action=login">Log In </a> to Your Account!</h5>
  
  <?php } ?>
  
  </div>
  
  <?php include ('includes/footer.php'); ?>