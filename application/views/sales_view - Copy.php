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
<!--
<link href="<?php echo base_url('resources/plugins/select2/dist/css/select2.min.css') ?>" rel="stylesheet" >
-->
<input type="hidden" value="<?php echo $ip; ?>" id="ip_address">
<input type="hidden" value="<?php echo base_url(); ?>" id="base_url">
<section class="content" id="vue_app">
	<div class="row">
		<div class="col-md-8">
			<div id="items" class="box box-danger">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div v-for="item in category_items" v-on:click="add_item(item)" class="item col-sm-1">
								<span class="badge bg-green">&#8369; {{ Number(item.price).toFixed(2) }}</span>
								<div class="item_name">
									<span>{{ item.name }}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
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
					<div id="grand_total">Grand Total :<span class="pull-right"> {{ '&#8369;' + ' ' + cart_total }}</span></div>
				</div>
				<div class="box-footer text-right">
					<button class="btn btn-default btn-lg" v-on:click="clear_cart">Clear</button>
					<button class="btn btn-danger btn-lg" v-on:click="check_out">Check Out</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Check Out</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div id="num_pad" class="col-sm-4">
							<?php 
							$ctr = 9;
							while($ctr != 0){
							?>
								<div><?php echo $ctr; ?></div>
							<?php 
							$ctr--;
							}
							?>
							<div><i class="fa fa-chevron-left"></i></div>
							<div style="font-size: 25px">Clear</div>
							<div>0</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="<?php echo base_url('resources/plugins/vue/vue-2.5.17.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/plugins/socket_io/socket.io-1.7.3.min.js') ?>"></script>
<script>
	//~ var socket = socket.connect('http://localhost:3000');
	//~ const session = $('#ip_address').val();
	const base_url = $('#base_url').val();
	const session = 's1';
	//~ const socket = io('http://'+ window.location.hostname +':3000/canteen');
	
	//~ socket.emit('join_session', session);
	//~ socket.emit('refresh');
	
	
	
	new Vue({
		el: '#vue_app',
		data: {
			category_items: [],
			cart: [],
			cart_total: 0
		},
		created() {
			this.fetch_category_items()
			},
		watch: {
			cart: function() {
				this.update_cart_total();
				//~ console.log(this.cart);
			}
		},
		methods : {
			fetch_category_items: function() {
				axios.get(base_url + '/category/ajax_category_active_items', { 
					params: {
						category_id: 12
					}
				})
				.then((response) => {
					this.category_items = response.data
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
			check_out: function() {
				$('#myModal').modal('show');
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
		
		
	});
	
	
</script>

