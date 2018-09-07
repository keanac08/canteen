<?php 
//~ $this->load->helper('number_helper');
?>
<link href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/datatables/datatables.min.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/material-switch/switch.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/css/vue-transition.css') ?>" rel="stylesheet" >
<input type="hidden" value="<?php echo base_url(); ?>" id="base_url">
<section class="content" id="vue_app">
	<div class="row">
		<div class="col-md-4">
			<div class="box box-danger">
				<div class="box-header with-border">
					<div class="pull-right box-tools">
						<button type="button" class="btn btn-danger btn-sm pull-right" v-on:click="new_category()">
							 Add Category
						</button>
					</div>
					<h3 class="box-title">
						&nbsp;
					</h3>
				</div>
<!--
				style="max-height: 520px;overflow-y: scroll;overflow-x: hidden;"
-->
				<div class="box-body">
					<div class="table-responsive" >
						<table id="myTables" class="table table-hover" >
							<thead>
								<tr>
									<th width="50%" class="">Name</th>
									<th width="15%" class="text-center">Update</th>
									<th width="15%" class="text-right">State</th>
									<th width="5%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(category, index) in categories" v-if="category.id != 15">
									<td>{{ category.name }}</td>
									<td class="text-center"><a href="#" v-on:click="update_category(category.id, category.name)"><i class="fa fa-edit"></i></a></td>
									<td>
										 <div class="material-switch pull-right">
											<input type="checkbox" :checked="category.active == 1" v-bind:id="category.id" v-on:click="change_active_status(category.id)"/>
											<label v-bind:for="category.id" class="label-success"></label>
										</div>
									</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal New -->
	<div class="modal fade" id="new_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">New Category Form</h4>
				</div>
				<div class="modal-body">
					<form action="/action_page.php">
						<div class="form-group">
							<label>Category Name <span class="text-red">*</span></label>
							<input required v-model="new_category_name" type="text" class="form-control">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger" v-on:click="save_new_category()">Save Item</button>
				</div>
			</div>
			<transition name="fade">
				<div v-if="show_error_msg" id="new_item_error" class="alert alert-danger text-center">
					{{ error_message }}
				</div>
				<div v-if="show_success_msg" id="new_item_success" class="alert alert-success text-center">
					{{ success_message }}
				</div>
			</transition>
		</div>
	</div>
	
	<!-- Modal Update -->
	<div class="modal fade" id="update_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Item Form</h4>
				</div>
				<div class="modal-body">
					<form action="/action_page.php">
						<div class="form-group">
							<label>Category Name <span class="text-red">*</span></label>
							<input required v-model="update_category_name" type="text" class="form-control">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger" v-on:click="save_update_category()">Save Item</button>
				</div>
			</div>
			<transition name="fade">
				<div v-if="show_error_msg" id="update_item_error" class="alert alert-danger text-center">
					{{ error_message }}
				</div>
				<div v-if="show_success_msg" id="update_item_success" class="alert alert-success text-center">
					{{ success_message }}
				</div>
			</transition>
		</div>
	</div>
</section>

<script src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/floatThead/jquery.floatThead.min.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/datatables/datatables.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/vue/vue-2.5.17.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/lodash/lodash.js') ?>"></script>


<script>
	const base_url = $('#base_url').val();
	
	vue = new Vue({
		el: '#vue_app',
		data: {
			categories: [],
			
			new_category_name: '',
			
			update_category_name: '',
			update_category_id: '',
			
			old_category_name: '',
			old_category_id: '',
			
			show_error_msg : false,
			show_success_msg : false 
		},
		created() {
			this.fetch_categories();	
		},
		methods: {
			fetch_categories: function() {
				axios.get(base_url + 'category/ajax_categories')
				.then((response) => {
					this.categories = response.data
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			change_active_status: function (category_id) {
				
				
				//~ alert(category_id)
				axios.get(base_url + '/category/ajax_change_category_status', { 
					params: {
						id: category_id
					}
				})
				.then((response) => {
					//~ console.log(response);
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			new_category: function () {
				
				$('#new_category_modal').modal('show');
			},			
			update_category: function (category_id, category_name) {
				
				this.update_category_id = category_id;
				this.update_category_name = category_name;
				
				this.old_category_id = category_id;
				this.old_category_name = category_name;
				
				$('#update_category_modal').modal('show');
			},			
			save_new_category: function () {
				
				if(this.new_category_name != ''){
					
					axios.get(base_url + '/category/ajax_new_category', { 
						params: {
							category_name: this.new_category_name
						}
					})
					.then((response) => {
						//~ console.log(response.data);
						if(response.data != false){
							this.new_category_name = '';
							
							this.fetch_categories();
							
							this.success_message = 'Category saved.';
							this.show_success_msg = true;
							setTimeout(() => {
							  this.show_success_msg = false;
							}, 2000);
						}
						else{
							this.error_message = 'Category name already exist.';
							this.show_error_msg = true;
							setTimeout(() => {
								this.show_error_msg = false;
							}, 2000);
						}
					})
					.catch(function (err) {
						console.log(err.message);
					});
				}
				else{
					this.error_message = 'All fields are required.';
					this.show_error_msg = true;
					setTimeout(() => {
						this.show_error_msg = false;
					}, 2000);
				}
			},
			save_update_category: function () {
				
				if(this.update_category_name != ''){
					
					if(this.update_category_name != this.old_category_name){
						
						axios.get(base_url + '/category/ajax_update_category', { 
							params: {
								category_id: this.update_category_id,
								category_name: this.update_category_name,
							}
						})
						.then((response) => {
							console.log(response.data);
							if(response.data){

								this.fetch_categories();
								
								this.success_message = 'Category updated.';
								this.show_success_msg = true;
								setTimeout(() => {
								  this.show_success_msg = false;
								}, 2000);
							}
							else{
								this.error_message = 'Category name already exist.';
								this.show_error_msg = true;
								setTimeout(() => {
									this.show_error_msg = false;
								}, 2000);
							}
						})
						.catch(function (err) {
							console.log(err.message);
						});
						
					}
					else{
						this.error_message = 'No changes found.';
						this.show_error_msg = true;
						setTimeout(() => {
							this.show_error_msg = false;
						}, 2000);
					}
					
				}
				else{
					this.error_message = 'All fields are required.';
					this.show_error_msg = true;
					setTimeout(() => {
						this.show_error_msg = false;
					}, 2000);
				}
			}
		}
	});
	
	$(document).ready(function() {
		
		//~ var $table = $('table#myTables');
		//~ $table.floatThead({
			//~ scrollContainer: function($table){
				//~ return $table.closest('.table-responsive');
			//~ }
		//~ });
		
		$('.selectpicker').selectpicker();
		
	});
</script>

