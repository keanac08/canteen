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
				
				<?php 
				if($this->session->ctn_usertype != 'administrator' OR $this->session->ctn_user_id == 1){
				?>
				<li class="<?php echo ($this->uri->segment(1) == 'sales' and $this->uri->segment(2) == 'index') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('sales/index');?>">
						<i class="fa fa-shopping-cart"></i> <span>POS</span>
					</a>
				</li>
				<li class="<?php echo ($this->uri->segment(1) == 'sales' and $this->uri->segment(2) == 'cashier') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('sales/cashier');?>">
						<i class="fa fa-line-chart"></i> <span>My Daily Sales</span>
					</a>
				</li>
				<?php 
				}
				?>

				<?php 
				if($this->session->ctn_usertype == 'administrator' OR $this->session->ctn_user_id == 1){
				?>
				<li class="treeview <?php  echo ($this->uri->segment(1) == 'category') ? 'active' : ''; ?>">
					<a href="#">
						<i class="fa fa-wrench"></i> <span>Setup</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo ($this->uri->segment(1) == 'category' and $this->uri->segment(2) == 'index') ? 'active' : ''; ?>" >
							<a href="<?php echo base_url('category/index'); ?> ">
								<i class="fa fa-circle-o"></i>Category
							</a>
						</li>
						<li class="<?php echo ($this->uri->segment(1) == 'category' and $this->uri->segment(2) == 'item') ? 'active' : ''; ?>" >
							<a href="<?php echo base_url('category/item'); ?> ">
								<i class="fa fa-circle-o"></i>Item
							</a>
						</li>
					</ul>
				</li>
				<li class="<?php echo ($this->uri->segment(1) == 'sales' AND $this->uri->segment(2) == 'report') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('sales/report');?>">
						<i class="fa fa-bar-chart"></i> <span>Sales/Billing Report</span>
					</a>
				</li>
				<li class="<?php echo ($this->uri->segment(1) == 'sales' AND $this->uri->segment(2) == 'sales_by_cashier') ? 'active' : ''; ?>">
					<a href="<?php echo base_url('sales/sales_by_cashier');?>">
						<i class="fa fa-line-chart"></i> <span>Cashier Sales</span>
					</a>
				</li>
				<?php 
				}
				?>
			</ul><!-- /.sidebar-menu -->
	</section>
<!-- /.sidebar -->
</aside>

