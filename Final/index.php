<?php include('includes/header.php'); ?>

	<div class="content_wrapper">
	
		<?php if(!isset($_GET['action'])){ ?>
	
			<div id="sidebar">
				<div id="sidebar_title">Categories</div>
					<ul id="cats"><?php getCats();?></ul>
			</div>

			<div id="content_area"><?php cart();?>
				<div id="products_box">
					<?php getPro();?>
					<?php get_pro_by_cat_id();?>
				</div>
			</div>

		<?php }else{ ?><?php include('login.php'); } ?>
	
	</div>

<?php include ('includes/footer.php'); ?>