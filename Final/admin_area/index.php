
<?php 
session_start();

if(!isset($_SESSION['role']) && $_SESSION['role'] !='admin'){

 echo "<script>window.open('login.php','_self')</script>";

}else{

?>

<?php include '../includes/db.php'; ?>

<!DOCTYPE html>

<html>
<head>
<title>Management for Admin</title>

<link href="styles/desktop.css" type="text/css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="../js/jquery-3.4.1.js"></script>
<head>

<body>
 <div class="container">
   <div class="header">
     <div class="navbar-header">
	  <h1><a class="admin_name"> Admin Area </a></h1>
	 </div>
	 
	 <div class="navbar-right-header">
	 
	 <ul class="dropdown-navbar-right">
	   <li>
	     <a><i class="fa fa-user"></i>&nbsp;<i class="fa fa-caret-down"></i></a>
		 
		 <ul class="subnavbar-right">
		   <li><a>User Account</a></li>
		   <li><a href="logout.php">Logout</a></li>
		 </ul>
	   </li>
	 </ul>
	 
	 </div>
   </div>
   
   <div class="body_container">
     <div class="left_sidebar">
	   <div class="left_sidebar_box">
	   
	   <ul class="left_sidebar_first_level">

         <li><a href="../index.php" target="_blank" ><i class="fa fa-dashboard"></i> My Site</a></li>	   
	     
		 <li>
		  <a href="#"><i class="fa fa-th-large"></i>&nbsp;Products <i class="arrow fa fa-angle-left"></i></a>
		 
		   <ul class="left_sidebar_second_level">
		     <li><a href="index.php?action=add_pro">Add Product</a></li>
			 <li><a href="index.php?action=view_pro">View Products</a></li>
		   </ul>
		 </li>
		 
		 <li>
		  <a href="#"><i class="fa fa-plus"></i>&nbsp;Categories <i class="arrow fa fa-angle-left"></i></a>
		 
		   <ul class="left_sidebar_second_level">
		     <li><a href="index.php?action=add_cat">Add Category</a></li>
			 <li><a href="index.php?action=view_cat">View Categories</a></li>
		   </ul>
		 </li>
		 
		 <li>
		  <a href="#"><i class="fa fa-gift"></i>&nbsp;Users <i class="arrow fa fa-angle-left"></i></a>
		 
		   <ul class="left_sidebar_second_level">
		     <li><a href="index.php?action=add_user">Add User</a></li>
			 <li><a href="index.php?action=view_users">List Users</a></li>
		   </ul>
		 </li>
		 
		 </ul>
	   </div>
	   
	 </div>
	 
	 <div class="content">
	   <div class="content_box">
	   <?php 
	   if(isset($_GET['action'])){
	    $action = $_GET['action'];
	   }else{
	    $action ='';
	   }
	   
	   switch($action){
	    case 'add_pro';
		include 'includes/insert_product.php';
		break;
		
		case 'view_pro';
		include 'includes/view_products.php';
		break;
		
		case 'edit_pro';
		include 'includes/edit_product.php';
		break;
		
		case 'add_cat';
		include 'includes/insert_category.php';
		break;
		
		case 'view_cat';
		include 'includes/view_categories.php';
		break;

		case 'add_user';
		include 'includes/add_user.php';
		break;

		case 'view_users';
		include 'includes/view_users.php';
		break;
		
	   }
	   
	   ?>
	   </div>
	   
	 </div>
	 
   </div>
   
 </div>
 
</body>

</html>



<script>
$(document).ready(function(){
  
  $(".dropdown-navbar-right").on('click',function(){
   $(this).find(".subnavbar-right").slideToggle('fast');
  });
  
  $(".left_sidebar_first_level li").on('click',this,function(){
    $(this).find(".left_sidebar_second_level").slideToggle('fast');
  });
  
});
</script>

<?php } ?>

