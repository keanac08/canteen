<?php 

$this->load->helper('number_helper');

if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} 
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} 
else {
	$ip = $_SERVER['REMOTE_ADDR'];
}
?>

<link href="<?php echo base_url('resources/plugins/vertical-tabs/bootstrap.vertical-tabs.min.css') ?>" rel="stylesheet" >
<section class="content" id="vue_app">
	<div class="row">
		
		<div class="col-md-2" style="width:15%;">
			<div class="list-group">
				<a href="#" class="list-group-item" v-for="(category, index) in categories" v-bind:class="{ active: active_index === index }" v-on:click="set_active(index, category.id)">{{ category.name }}</a>
			</div>
		</div>
		<div class="col-md-9" style="width:55%;">
			<div id="items" class="box box-danger">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div v-for="item in category_active_items" v-bind:class="{ 'font-90': reduce_font_size(item.name) }"  v-on:click="add_item(item)" class="item col-sm-1">
								<span class="badge bg-green">&#8369; {{ Number(item.price).toFixed(2) }}</span>
								<div class="item_name">
									<span>{{ item.name.toLowerCase() }}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3" style="width:30%;">
			<div id="transactions" class="box box-danger">
				<div class="box-body">
					<table id="cart" class="table table-striped">
						<thead>
							<tr>
								<th class="col-sm-6">Item Name</th>
								<th class="col-sm-2 text-right">Price</th>
								<th class="col-sm-2 text-center">Quantity</th>
								<th class="col-sm-2 text-right">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in cart">
								<td>{{ item.name }}</td>
								<td class="text-right">{{ item.price }}</td>
								<td class="text-center">
									<div class="input-group">
										<span class="input-group-btn">
											<button class="btn btn-danger" type="button" v-on:click="plus_qty(index)">+</button>
										</span>
										<input readonly type="text" class="form-control text-center" style="padding:0;width: 30px;background-color: #ffffff;" v-model="item.quantity">
										<span class="input-group-btn">
											<button class="btn btn-danger" type="button" v-on:click="minus_qty(index)">-</button>
										</span>
									</div><!-- /input-group -->
								</td>

								<td class="text-right">{{ item.total }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="gt_box_footer" class="box-footer" style="">
					<div id="grand_total">Grand Total :<span class="pull-right"> {{ '&#8369;' + ' ' + cart_total }} </span></div>
				</div>
				<div class="box-footer text-right">
					<img :src="employee.image_link" @error="imageLoadError" v-if="employee.id != ''" class="img-thumbnail pull-left" style="width:48px;" alt="Employee Picture">
					<button class="btn btn-default btn-lg" v-on:click="clear_cart">Clear</button>
					<button class="btn btn-danger btn-lg" :disabled="check_out_disabled" v-on:click="check_out">Check Out</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Check Out-->
	<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Check Out</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-3" style="width:27%;">
							<p>
								<img :src="employee.image_link" @error="imageLoadError" class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<input type="text" id="employee_number" class="form-control text-center" maxlength="6" v-model="employee_number" v-on:keyup="get_employee_details" placeholder="Employee Number" />
							</p>
						</div>
						<div class="col-sm-9" style="width:73%;">
							<table class="table">
								<tbody>
									<tr>
										<td><strong>Name :<strong></td>
										<td colspan="2" style="text-transform: capitalize;">{{ lowcase(employee.name) }}</td>
									</tr>
									<tr>
										<td><strong>Section :<strong></td>
										<td colspan="2">{{ employee.section }}</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td></td>
										<td class="text-right"><strong>Meal Allowance : </strong></td>
										<td width="80px" class="text-right">{{ employee.allowance }}</td>
									</tr>
									<tr>
										<td></td>
										<td class="text-right"><strong>Purchase Amount : </strong></td>
										<td class="text-right">{{ cart_total }}</td>
									</tr>
									<tr>
										<td></td>
										<td class="text-right"><strong>Remaining Meal Allowance : </strong></td>
										<td class="text-right">{{ balance }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-lg btn-default" v-on:click="close_check_out">Close</button>
					<button type="button" class="btn btn-lg btn-danger" v-show="proceed_check_out_show" :disabled="proceed_check_out_disabled" v-on:click="proceed_check_out" >{{ proceed_check_out_btn }}</button>
				</div>
			</div>
			<transition name="fade">
				<div v-if="message.show_error" id="new_item_error" class="alert alert-danger text-center">
					{{ message.error }}
				</div>
				<div v-if="message.show_success" id="new_item_success" class="alert alert-success">
					{{ message.success }}
				</div>
			</transition>
		</div>
	</div>
</section>
<script src="<?php echo base_url('resources/plugins/vue/vue-2.5.17.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/socket_io/socket.io-1.7.3.min.js') ?>"></script>
<script>
	
	
	
	//~ var socket = socket.connect('http://localhost:3000');
	const base_url = '<?php echo base_url(); ?>'
	const session = '<?php echo $ip; ?>'
	const socket = io('http://'+ window.location.hostname +':3000/canteen');
	
	socket.emit('join_session', session);
	socket.emit('refresh');
	
	var helper = {
	  methods: {
		amount: function (number) {
			return Number(number).toFixed(2)
		},
		lowcase: function (str) {
			return str.toLowerCase();
		},
		reduce_font_size: function (str) {
			if(str.length > 27 || str.split(' ').length > 3){
				return true;
			}
			else{
				return false;
			}
		},
		imageLoadError: function() {
			this.employee_image_link = base_url + '/resources/images/default.png';
		}
	  }
	}
	
	new Vue({
		el: '#vue_app',
		data: {
			categories: [],
			category_active_items: [],
			
			cart: [],
			cart_total: 0,
			balance: '',
			employee_number: '',
			
			employee : {
				id: '',
				number: '',
				name: '',
				section: '',
				allowance: '',
				image_link: base_url + '/resources/images/default.png'
			},
			
			message : {
				error: '',
				show_error : false,
				success: '',
				show_success : false
			},
			
			active_index: 0,
			last_transaction_id: '',
			check_out_disabled: true,
			proceed_check_out_disabled: true,
			proceed_check_out_show: true,
			proceed_check_out_btn : 'Proceed Check Out'
		},
		mixins: [helper],
		created() {
			this.fetch_categories();
			this.fetch_category_active_items();
			
			},
		watch: {
			cart: function() {
				
				this.update_cart_total();
				
				if(this.employee.id != ''){
					this.update_balance();
				}
			},
			balance: function(){
				if(this.balance > -200){
					this.proceed_check_out_disabled = false;
					this.message.error = '';
					this.message.show_error = false;
				}
				else{
					this.proceed_check_out_disabled = true;
					this.message.error = 'Cannot proceed! Credit limit (200.00) has been exceeded.';
					this.message.show_error = true;
				}
			},
			cart_total: function(){
				if(this.cart_total > 0){
					this.check_out_disabled = false;
				}
				else{
					this.check_out_disabled = true;
				}
			}
		},
		computed: {
			
		},
		methods : {
			set_active(index, category_id) {
				this.active_index = index;
				this.fetch_category_active_items(category_id);
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
			fetch_category_active_items: function(category_id) {
				axios.get(base_url + '/category/ajax_category_active_items', { 
					params: {
						category_id: category_id
					}
				})
				.then((response) => {
					this.category_active_items = response.data
					console.log(response.data)
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			add_item: function(item) {
				
				this.new_items = {
					id: item.id,
					name: item.name,
					price: Number(item.price).toFixed(2),
					quantity: 1,
					total: Number(item.price).toFixed(2)
				};
				
				let index = this.cart_index(this.new_items);
				
				if (index !== undefined) {
					this.new_items = {
						id: this.cart[index].id,
						name: this.cart[index].name,
						price: Number(this.cart[index].price).toFixed(2),
						quantity: ++this.cart[index].quantity,
						total: Number(this.cart[index].quantity * this.cart[index].price).toFixed(2)
					}

					this.cart.splice(index, 1, this.new_items)
					
					socket.emit('join_session', session);
					socket.emit('update_cart_item', {
						id: this.new_items.id, 
						name: this.new_items.name, 
						price: this.new_items.price,
						quantity: this.new_items.quantity,
						total: this.new_items.total
					});
				}
				else {
					this.cart.push(this.new_items);
					
					socket.emit('join_session', session);
					socket.emit('new_cart_item', {
						id: this.new_items.id, 
						name: this.new_items.name, 
						price: this.new_items.price,
						quantity: this.new_items.quantity,
						total: this.new_items.total
					});

				}
			},
			cart_index: function(item) {
				var i = undefined;

				for(var [index, value] of this.cart.entries()){
					if (value.id === item.id)
					{
						i = index;
						break;
					}
				}

				return i
			},
			clear_cart: function() {
				
				this.cart.splice(0, this.cart.length);
				
				this.employee = {
					id: '',
					number: '',
					name: '',
					section: '',
					allowance: '',
					image_link: base_url + '/resources/images/default.png'
				},
				
				this.balance = ''
				this.employee_number = ''
				
				socket.emit('join_session', session);
				socket.emit('clear_cart');
			},
			plus_qty: function(index){
				this.new_items = {
					id: this.cart[index].id,
					name: this.cart[index].name,
					price: Number(this.cart[index].price).toFixed(2),
					quantity: ++this.cart[index].quantity,
					total: Number(this.cart[index].quantity * this.cart[index].price).toFixed(2)
				};
				this.cart.splice(index, 1, this.new_items);
				
				socket.emit('join_session', session);
				socket.emit('update_cart_item', {
					id: this.new_items.id, 
					name: this.new_items.name, 
					price: this.new_items.price,
					quantity: this.new_items.quantity,
					total: this.new_items.total
				});
			},
			minus_qty: function(index){
				if(this.cart[index].quantity > 1){
					this.new_items = {
						id: this.cart[index].id,
						name: this.cart[index].name,
						price: Number(this.cart[index].price).toFixed(2),
						quantity: --this.cart[index].quantity,
						total: Number(this.cart[index].quantity * this.cart[index].price).toFixed(2)
					};
					
					this.cart.splice(index, 1, this.new_items);
					
					socket.emit('join_session', session);
					socket.emit('update_cart_item', {
						id: this.new_items.id, 
						name: this.new_items.name, 
						price: this.new_items.price,
						quantity: this.new_items.quantity,
						total: this.new_items.total
					});
				}
				else{
					this.new_items = {
						id: this.cart[index].id,
						name: this.new_items.name
					};
					
					this.cart.splice(index, 1);
					
					socket.emit('join_session', session);
					socket.emit('delete_cart_item', {
						id: this.new_items.id,
						name: this.new_items.name
					});
				}
				
			},
			update_cart_total: function() {
				this.cart_total = _.chain(this.cart).map((item) => { return Number(item.total) }).sum();
				this.cart_total = Number(this.cart_total).toFixed(2);
				
				socket.emit('join_session', session);
				socket.emit('update_cart_total', {
					total: this.cart_total
				});
			},
			update_balance: function() {
			
				this.balance = Number(this.employee.allowance - this.cart_total).toFixed(2)
				
				socket.emit('join_session', session);
				socket.emit('update_balance', {
					balance: this.balance
				});
			},
			print_receipt: function(){
					
			},
			close_check_out: function(){
				$('#myModal').modal('hide');
			},
			check_out: function() {

				$('#myModal').modal({backdrop: 'static'});	
			},
			proceed_check_out: function() {
				
				//CHECK OUT
				if(this.last_transaction_id == ''){
					axios({
							url: base_url + '/sales/ajax_check_out',
							method: 'post',
							data: {
								cart: this.cart,
								employee_id: this.employee.id,
								total_purchase: this.cart_total
							}
						})
						.then((response) => {
							
							//~ console.log(response.data)
							if(response.data != false){
								
								this.message.success = 'Transaction Completed!';
								this.message.show_success = true;
								
								this.last_transaction_id = response.data
								this.proceed_check_out_btn = 'Print Receipt'
								
								
							}
							else{
								alert('Transaction Error!')
							}
							
						})
						.catch(function (error) {
							// your action on error success
							console.log(error);
						});
				}
				//PRINT RECEIPT
				else{
					
				}
			},
			get_employee_details: function() {
				
				if((this.employee_number).length == 6){
				
					axios.get(base_url + '/sales/ajax_employee_details', { 
						params: {
							employee_number: this.employee_number
						}
					})
					.then((response) => {
						
						console.log(response.data);
						
						if(response.data != false){
							this.employee = {
								id: response.data[0]['id'],
								number: response.data[0]['employee_no'],
								name: response.data[0]['first_name'] + ' ' + response.data[0]['middle_name'] + ' ' + response.data[0]['last_name'],
								section: response.data[0]['section'],
								allowance: Number(response.data[0]['meal_allowance']).toFixed(2),
								image_link: base_url + 'resources/images/emp_pics/' + response.data[0]['employee_no']
							}
							
							this.balance = Number(response.data[0]['meal_allowance'] - this.cart_total).toFixed(2)
							
							socket.emit('join_session', session);
							socket.emit('employee_details', {
								employee : this.employee,
								balance : this.balance
							});
						}
						else{
							alert('Employee does not exist!')
						}
						
					})
					.catch(function (err) {
						console.log(err.message);
					});
				}
				else if((this.employee_number).length < 6){
					
					//~ this.employee_id = ''
					//~ this.employee_name = ''
					//~ this.employee_section = ''
					//~ this.employee_meal_allowance = ''
					//~ this.employee_image_link = base_url + '/resources/images/default.png'
					this.employee = {
						id: '',
						number: '',
						name: '',
						section: '',
						allowance: '',
						image_link: base_url + '/resources/images/default.png'
					}
					this.balance = ''
				}
			}
		}
	});
	
	$(function(){
		
		
		$('ul#nav_pos').append('<li><a href="#" id="btn-customer-window"><i class="fa fa-window-restore"></i></a></li>');
		
		$('body').on('click', '#btn-customer-window', function() {
			var left = 1360;
			left += window.screenX;
			
			window.open('<?php echo base_url("sales/customer"); ?>','windowName','resizable=0,scrollbars=1,fullscreen=yes,height='+screen.availHeight+',width=' + screen.availWidth + '  , left=' + left + ', toolbar=0, menubar=0,status=1');    
			return 0;
		});
		
		$('#myModal').on('shown.bs.modal', function () {
			$('#employee_number').focus();
		})
	});
	
	
</script>

