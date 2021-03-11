<?php 

$select_user = mysqli_query($con,"select * from users where id='$_SESSION[user_id]' ");

$fetch_user = mysqli_fetch_array($select_user);
?>
	
<div class="register_box">

  <form method = "post" action="" enctype="multipart/form-data">
    
	<table align="left" width="70%">
	
	  <tr align="left">	   
	   <td colspan="4">
	   <h2>Edit Profile Name.</h2><br />
	   
	   </td>	   
	  </tr>
	  
	  <tr>
	   <td width="25%"><b>Change Name:</b></td>
	   <td colspan="3"><input type="text" name="name" value="<?php echo $fetch_user['name'];?>" required placeholder="Name" /></td>
	  </tr>	 
	  
	  <tr align="left">
	   <td></td>
	   <td colspan="4">
	   <input type="submit" name="edit_profile" value="Save" />
	   </td>
	  </tr>
	
	</table>
	
	
  </form>

</div>

<?php 
if(isset($_POST['edit_profile'])){  
  
  if($_POST['name'] !=''){
  
   
   $name = $_POST['name'];
   
   
   $update_profile = mysqli_query(
	   $con,"update users set name='$name' where id='$_SESSION[user_id]'");
   
   if($update_profile){
   echo "<script>alert('Your updated was successfully!')</script>";
   
   echo "<script>window.open(window.location.href,'_self')</script>";
   }
   
  }
  
}

?>




  
