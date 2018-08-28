<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo base_url('resources/images/thumbs/girl_2.png'); ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?php echo $this->session->userdata()['ctn_fullname'];?></p>
			</div>
		</div>
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="">
				<span class="input-group-btn">
					<button type="button" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>
		<!-- Sidebar Menu -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">MAIN NAVIGATION</li>
				<li class="<?php echo ($this->uri->segment(1) == 'sales') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('sales/');?>">
						<i class="fa fa-shopping-cart"></i> <span>POS</span>
					</a>
				</li>
				<li class="<?php echo ($this->uri->segment(1) == 'category') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('category/');?> ">
						<i class="fa fa-wrench"></i> <span>Item Setup</span>
					</a>
				</li>
			</ul><!-- /.sidebar-menu -->
	</section>
<!-- /.sidebar -->
</aside>

