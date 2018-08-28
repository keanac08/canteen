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
		<div class="col-md-2">
			<div class="list-group">
				<a href="#" class="list-group-item" v-for="(category, index) in categories" v-bind:class="{ active: active_index === index }" v-on:click="set_active(index, category.id)">{{ category.name }}</a>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="box-header with-border">
					<div class="pull-right box-tools">
						<button type="button" class="btn btn-danger btn-sm  pull-right" v-on:click="new_item">
							 Add Item
						</button>
					</div>
					<h3 class="box-title">
						&nbsp;
					</h3>
				</div>
				<div class="box-body">
					<div class="table-responsive" style="max-height: 512px;overflow-y: scroll;overflow-x: hidden;">
						<table id="myTables" class="table table-hover" >
							<thead>
								<tr>
									
									<th width="15%">Item ID</th>
									<th width="40%" class="">Name</th>
									<th class="text-right">Price</th>
									<th width="10%" class="text-center">Update</th>
									<th width="10%" class="text-right">State</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, index) in category_items">
								
									<td>{{ item.id }}</td>
									<td>{{ item.name }}</td>
									<td class="text-right">{{amount(item.price)}}</td>
									<td class="text-center"><a href="#" v-on:click="update_item(item.id, item.name, item.price, item.category_id)"><i class="fa fa-edit"></i></a></td>
									<td>
										 <div  class="material-switch pull-right">
											<input type="checkbox" :checked="item.active == 1" v-bind:id="item.id" v-on:click="change_active_status(item.id)"/>
											<label v-bind:for="item.id" class="label-success"></label>
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
	<div class="modal fade" id="new_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">New Item Form</h4>
				</div>
				<div class="modal-body">
					<form action="/action_page.php">
						<div class="form-group">
							<label>Item Name <span class="text-red">*</span></label>
							<input required v-model="new_item_name" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label>Item Price <span class="text-red">*</span></label>
							<input required v-model="new_item_price" type="number" class="form-control">
						</div>
						<div class="form-group">
							<label>Category <span class="text-red">*</span></label>
							<select required v-model="new_item_category" class="form-control selectpicker">
								<option></option>
								<option v-for="category in categories" v-bind:value="{ category_id: category.id }">{{ category.name }}</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger" v-on:click="save_new_item()">Save Item</button>
				</div>
			</div>
			<transition name="fade">
				<div v-if="show_error_msg" id="new_item_error" class="alert alert-danger">
					{{ error_message }}
				</div>
				<div v-if="show_success_msg" id="new_item_success" class="alert alert-success">
					{{ success_message }}
				</div>
			</transition>
		</div>
	</div>
	
	<!-- Modal Update -->
	<div class="modal fade" id="update_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Update Item Form</h4>
				</div>
				<div class="modal-body">
					<form action="/action_page.php">
						<div class="form-group">
							<label>Item Name <span class="text-red">*</span></label>
							<input required v-model="update_item_name" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label>Item Price <span class="text-red">*</span></label>
							<input required v-model="update_item_price" type="number" class="form-control">
						</div>
						<div class="form-group">
							<label>Category <span class="text-red">*</span></label>
							<select required v-model="update_item_category" class="form-control selectpicker">
								<option></option>
								<option v-for="category in categories" v-bind:value="category.id">{{ category.name }}</option>
							</select>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" v-on:click="save_update_item()">Save Item</button>
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

	var helper = {
	  methods: {
		amount: function (number) {
			return Number(number).toFixed(2)
		}
	  }
	}
	
	vue = new Vue({
		el: '#vue_app',
		data: {
			categories: [],
			category_items: [],
			
			active_category_id: 1,
			active_index: 0,
			item_status: [],
			
			error_message: '',
			show_error_msg: false,
			success_message: '',
			show_success_msg: false,
			
			new_item_name: '',
			new_item_price: '',
			new_item_category: '',
			
			old_item_name: '',
			old_item_price: '',
			old_item_category: '',
			
			update_item_id: '',
			update_item_name: '',
			update_item_price: '',
			update_item_category: ''
		},
		mixins: [helper],
		created() {
			this.fetch_categories();
			this.fetch_category_items(1);
		},
		watch: {

		},
		methods : {
			set_active(index, category_id) {
				this.active_index = index;
				this.fetch_category_items(category_id);
				this.active_category_id = category_id;
			},	
			fetch_categories: function() {
				axios.get(base_url + 'category/ajax_categories')
				.then((response) => {
					this.categories = response.data
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			fetch_category_items: function(category_id) {
				
				axios.get(base_url + 'category/ajax_category_items', { 
					params: {
						id: category_id
					}
				})
				.then((response) => {
					this.category_items = response.data;
					//~ this.$nextTick(function() {	
						
					//~ });
				})
				.catch(function (error) {
					console.log(error);
				})
			},
			change_active_status: function (item_id) {
				
				axios.get(base_url + '/category/ajax_change_item_status', { 
					params: {
						id: item_id
					}
				})
				.then((response) => {
					//~ console.log(response);
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			new_item: function(){
				
				$('.selectpicker').selectpicker('refresh');
				$('#new_item_modal').modal('show');
			},
			save_new_item: function(){
				
				if(this.new_item_name != '' && this.new_item_price != '' && this.new_item_category != ''){
					
					axios.get(base_url + '/category/ajax_new_item', { 
						params: {
							item_name: this.new_item_name,
							item_price: this.new_item_price,
							item_category: this.new_item_category.category_id
						}
					})
					.then((response) => {
						//~ console.log(response.data);
						if(response.data != false){
							this.new_item_name = '';
							this.new_item_price = '';
							this.new_item_category = '';
							
							$('.selectpicker').selectpicker('val', '');
							
							this.fetch_category_items(this.active_category_id);
							
							this.success_message = 'Item saved.';
							this.show_success_msg = true;
							setTimeout(() => {
							  this.show_success_msg = false;
							}, 2000);
						}
						else{
							this.error_message = 'Item name already exist.';
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
			update_item: function(item_id, item_name, item_price, category_id){
				
				this.update_item_id = item_id;
				this.update_item_name = item_name;
				this.update_item_price = item_price;
				this.update_item_category = category_id;
				
				this.old_item_name = item_name;
				this.old_item_price = item_price;
				this.old_item_category = category_id;
				
				$('.selectpicker').selectpicker('refresh');
				$('.selectpicker').selectpicker('val', category_id);
				$('#update_item_modal').modal('show');
			},
			save_update_item: function(){
				
				if(this.update_item_name != '' && this.update_item_price != '' && this.update_item_category != ''){
					
					if(this.update_item_name != this.old_item_name || this.update_item_price != this.old_item_price || this.update_item_category != this.old_item_category){
						
						axios.get(base_url + '/category/ajax_update_item', { 
							params: {
								item_id: this.update_item_id,
								item_name: this.update_item_name,
								item_price: this.update_item_price,
								item_category: this.update_item_category,
								
								old_name: this.old_item_name,
								old_price: this.old_item_price,
								old_category: this.old_item_category
							}
						})
						.then((response) => {
							console.log(response.data);
							if(response.data){
								item_id = '';
								item_name = '';
								item_price = '';
								item_category = '';
								
								old_name = '';
								old_price = '';
								old_category = '';
								
								$('.selectpicker').selectpicker('val', '');
								
								this.fetch_category_items(this.active_category_id);
								
								this.success_message = 'Item updated.';
								this.show_success_msg = true;
								setTimeout(() => {
								  this.show_success_msg = false;
								}, 2000);
							}
							else{
								this.error_message = 'Item name already exist.';
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
		
		var $table = $('table#myTables');
		$table.floatThead({
			scrollContainer: function($table){
				return $table.closest('.table-responsive');
			}
		});
		
		$('.selectpicker').selectpicker();
		
	});
</script>

