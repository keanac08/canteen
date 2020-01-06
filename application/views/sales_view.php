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

$server_ip = SERVER_IP;
if($ip == "::1"){
	$client_ip = $server_ip;
}
else{
	$client_ip = $ip;
}
?>

<link href="<?php echo base_url('resources/plugins/vertical-tabs/bootstrap.vertical-tabs.min.css') ?>" rel="stylesheet" >
<section class="content" id="vue_app">
	<div class="row">
		
		<div class="col-md-2 sales_category" style="width:15%;">
			<div class="list-group">
				<a href="#" class="list-group-item" v-for="(category, index) in categories" v-if="category.active == 1" v-bind:class="{ active: active_index === index }" v-on:click="set_active(index, category.id)">{{ category.name }}</a>
			</div>
		</div>
		<div class="col-md-9" style="width:55%;">
			<div id="items" class="box box-danger">
				<div class="box-body" style="">
					<div class="row">
						<div class="col-sm-12">
							<div v-for="item in category_active_items" v-bind:class="reduce_font_size(item.name)"  v-on:click="add_item(item)" class="item col-sm-1">
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
					<button class="btn btn-danger btn-lg" :disabled="finger_disabled" v-on:click="check_out(2)">Finger</button>
					<button class="btn btn-danger btn-lg" :disabled="check_out_disabled" v-on:click="check_out(1)">Barcode</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Check Out-->
	<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Check Out Details</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-3" style="width:35%;">
							<div class="numpad">
								<div class="numpad-block" v-on:click="numpad_click('9')">9</div>
								<div class="numpad-block" v-on:click="numpad_click('8')">8</div>
								<div class="numpad-block" v-on:click="numpad_click('7')">7</div>
								<div class="numpad-block" v-on:click="numpad_click('6')">6</div>
								<div class="numpad-block" v-on:click="numpad_click('5')">5</div>
								<div class="numpad-block" v-on:click="numpad_click('4')">4</div>
								<div class="numpad-block" v-on:click="numpad_click('3')">3</div>
								<div class="numpad-block" v-on:click="numpad_click('2')">2</div>
								<div class="numpad-block" v-on:click="numpad_click('1')">1</div>
								<div class="numpad-block" v-on:click="numpad_click('B')"><i class="fa fa-arrow-left"></i></div>
								<div class="numpad-block" v-on:click="numpad_click('C')">Clear</div>
								<div class="numpad-block" v-on:click="numpad_click('0')">0</div>
							</div>
						</div>
						<div class="col-sm-3" style="width:20%;">
							<p>
								<img :src="employee.image_link" @error="imageLoadError" class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<input type="text" id="employee_number" class="form-control text-center" maxlength="6" v-model="employee_number" v-on:keyup="get_employee_details" placeholder="Employee Number" />
							</p>
						</div>
						<div class="col-sm-9" style="width:45%;">
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
					<button type="button" class="btn btn-lg btn-default" v-on:click="close_check_out" id="close_btn">Close</button>
					<button type="button" class="btn btn-lg btn-danger" v-show="proceed_check_out_show" :disabled="proceed_check_out_disabled" v-on:click="proceed_check_out" >{{ proceed_check_out_btn }}</button>
				</div>
			</div>
			<transition name="fade">
				<div v-if="message.show_error" id="new_item_error" class="alert alert-danger text-center">
					{{ message.error }}
				</div>
				<div v-if="message.show_success" id="new_item_success" class="alert alert-success text-center">
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
	
	var ws;	
	var wsl;
	var serverIP = "<?php echo $server_ip; ?>";
	var clientIP = "<?php echo $client_ip; ?>";
	var state = 0;			
	var template;
	var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
	
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
			if(str.length > 60 || str.split(' ').length > 5){
				return 'font-70';
			}
			else if(str.length > 50 || str.split(' ').length > 4){
				return 'font-80';
			}
			else if(str.length > 27 || str.split(' ').length > 3){
				return 'font-90';
			}
			else{
				return '';
			}
		},
		imageLoadError: function() {
			this.employee_image_link = base_url + '/resources/images/default.png';
		}
	  }
	}
	
	var vue = new Vue({
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
			finger_disabled: true,
			proceed_check_out_disabled: true,
			proceed_check_out_show: true,
			proceed_check_out_btn : 'Proceed Check Out',
			
			active_scan : false
		},
		mixins: [helper],
		created() {
			this.start();
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
				if(this.balance == ''){
					this.proceed_check_out_disabled = true;
					this.message.error = '';
					this.message.show_error = false;
				}
				else if(this.balance > -200){
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
				if(this.active_scan == false){
					if(this.cart_total > 0){
						this.check_out_disabled = false;
						this.finger_disabled = false;
					}
					else{
						this.check_out_disabled = true;
						this.finger_disabled = true;
					}
				}
			},
			employee_number : function(){
				this.get_employee_details();
			}
		},
		computed: {
			
		},
		methods : {
			start : function(){
				ConnectDevice();
			},
			disconnect : function(){
				DisconnectDevice();
			},
			capture_fingerprint : function(){
				CaptureFingerprint(0, 0);
			},
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
					//~ console.log(response.data)
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
					
					//~ socket.emit('join_session', session);
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
					
					//~ socket.emit('join_session', session);
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
				this.last_transaction_id = ''
				this.proceed_check_out_btn = 'Proceed Check Out'
				
				//~ socket.emit('join_session', session);
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
				
				//~ socket.emit('join_session', session);
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
					
					//~ socket.emit('join_session', session);
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
					
					//~ socket.emit('join_session', session);
					socket.emit('delete_cart_item', {
						id: this.new_items.id,
						name: this.new_items.name
					});
				}
				
			},
			update_cart_total: function() {
				this.cart_total = _.chain(this.cart).map((item) => { return Number(item.total) }).sum();
				this.cart_total = Number(this.cart_total).toFixed(2);
				
				//~ socket.emit('join_session', session);
				socket.emit('update_cart_total', {
					total: this.cart_total
				});
			},
			update_balance: function() {
			
				this.balance = Number(this.employee.allowance - this.cart_total).toFixed(2)
				
				//~ socket.emit('join_session', session);
				socket.emit('update_balance', {
					balance: this.balance
				});
			},
			close_check_out: function(){
				
				$('#myModal').modal('hide');
				
				if(this.last_transaction_id != ''){
					
					this.message = {
						success: '',
						show_success : false
					}
					
					this.clear_cart();
				}
			},
			check_out: function(method) {
				if(method == 2){
					this.capture_fingerprint();
					socket.emit('check_out');
				}
				$('#myModal').modal({backdrop: 'static'});
				
				
			},
			proceed_check_out: function() {
				
				this.proceed_check_out_show = false

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

								this.proceed_check_out_show = true
							
								this.message.success = 'Transaction Completed!';
								this.message.show_success = true;
								
								this.last_transaction_id = response.data
								this.proceed_check_out_btn = 'Print Receipt'
								
								//~ socket.emit('join_session', session);
								socket.emit('transaction_completed');
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
					axios({
							url: base_url + '/sales/print_receipt',
							method: 'post',
							data: {
								cart: this.cart,
								employee_name: this.employee.name,
								meal_allowance: this.employee.allowance,
								total_purchase: this.cart_total,
								transaction_id: this.last_transaction_id
							}
						})
						.then((response) => {
							
							console.log(response.data)
							
						})
						.catch(function (error) {
							// your action on error success
							console.log(error);
						});
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
								name: response.data[0]['first_name']  + ' ' + response.data[0]['last_name'],
								section: response.data[0]['section'],
								allowance: Number(response.data[0]['meal_allowance']).toFixed(2),
								image_link: base_url + 'resources/images/emp_pics/' + response.data[0]['employee_no']
							}
							
							this.balance = Number(response.data[0]['meal_allowance'] - this.cart_total).toFixed(2)
							
							//~ socket.emit('join_session', session);
							socket.emit('employee_details', {
								employee : this.employee,
								balance : this.balance
							});
						}
						else{
							//~ alert('Employee does not exist!')
							this.message.error = 'Employee does not exist!';
							this.message.show_error = true;
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
					
					this.message.show_error = false;
					
					socket.emit('employee_details', {
						employee : this.employee,
						balance : this.balance
					});
				}
			},
			numpad_click: function(value){

				let id = this.employee_number;
				
				if(value == 'C'){
					id = '';
				}
				else if(value == 'B'){
					id = id.slice(0, -1)
				}
				else{
					if(id.length < 6){
						id = id + value;
					}
				}

				this.employee_number = id;
				this.get_employee_details();
			}
		}
	});
	
	function Request(cmd, deviceName, fingerprintId, referenceId, base64data1, base64data2, base64data3, base64data4, base64data5, base64data6, base64data7, base64data8, base64data9, base64data10, timeout, threshold){
		//~ ControlDisabled(false, true, true, true, true);
		var request = {"FingerPrintCommand" : cmd,
							"DeviceName" : deviceName,
							"FingerprintId" : fingerprintId, 
							"ReferenceId" : referenceId,
							"Base64Data1" : base64data1,
							"Base64Data2" : base64data2,
							"Base64Data3" : base64data3,
							"Base64Data4" : base64data4,
							"Base64Data5" : base64data5,
							"Base64Data6" : base64data6,
							"Base64Data7" : base64data7,
							"Base64Data8" : base64data8,
							"Base64Data9" : base64data9,
							"Base64Data10" : base64data10,
							"Timeout" : timeout,
							"Threshold" : threshold};	
		//~ alert(serverIP + " - " + clientIP);	
		if(!ws){
			try{
				if(serverIP == ""){
					alert('Please setup WebSocket to Server');
				}else{
					if(clientIP != serverIP){
						wsl = new WebSocket("ws://" + serverIP + ":5200/secugentoolservice/fingerprint");	
					}								
					ws = new WebSocket("ws://" + clientIP + ":5200/secugentoolservice/fingerprint");	
				}				
			}catch(e){
				ws = null;
			}
		}
		state = ws.readyState;	
		if(state == 0){		
			
				ws.onopen = function(){					
					ws.send(JSON.stringify(request));		
				};
						
				setTimeout(function() {
					Request(cmd, deviceName, fingerprintId, referenceId, base64data1, base64data2, base64data3, base64data4, base64data5, base64data6, base64data7, base64data8, base64data9, base64data10, timeout, threshold);
				}, 1000);
									
				return;
		}
				
		try{	
			if(state == 0){
				ws.onopen = function(){					
					ws.send(JSON.stringify(request));		
				};					
			}else if(state == 3){
				if(serverIP == ""){
					alert('Please setup WebSocket to Server');
				}else{
					if(clientIP != serverIP){
						wsl = new WebSocket("ws://" + serverIP + ":5200/secugentoolservice/fingerprint");	
					}								
					ws = new WebSocket("ws://" + clientIP + ":5200/secugentoolservice/fingerprint");	
				}		
				ws.onopen = function(){					
					ws.send(JSON.stringify(request));		
				};						
			}else{	
				if(cmd == "GetDeviceList" || cmd == "Connect" || cmd == "Disconnect" || cmd == "CaptureFingerprint" || cmd == "GetFingerprintTemplate" || cmd == "VerifyTemplate")
				{
					ws.send(JSON.stringify(request));	
				}	
				else
				{
					if(wsl)
						wsl.send(JSON.stringify(request));
					else
						ws.send(JSON.stringify(request));
				}
			}								
		}catch(e){
			ws = null;
			Console.log("could not connect to server");
		}			
	}
	
	function ConnectSecugen(deviceName){
	
		Request("Connect", deviceName, 0, "","","","","","","","","","","",0,0);
		ws.onmessage = function(e){		
			var response = JSON.parse(e.data);	
			if(response.ResponseCode == 0){
			}
			else{	
			}			   
		}	
	}
	
	function ConnectDevice(){
		
		Request("GetDeviceList", "", 0, "","","","","","","","","","","",0,0);
		
		ws.onmessage = function(e){			
			var response = JSON.parse(e.data);		
			console.log(response);	
			if(response.length > 0){		
				console.log('Device detected');			
				ConnectSecugen(response[0]);
			}else{
				   //prompt that no device was detected	
				   console.log('No device detected');
			}
		}
	}
	
	function CaptureFingerprint(fingerprintId,imageNo){
		
		vue.finger_disabled = true;
		vue.active_scan = true;
		
		ws.onmessage = function(e){			
			var response = JSON.parse(e.data);	
			//~ console.log(response);
			
			if(response.ResponseCode == 0){
				console.log('Fingerprint captured. Quality :' + response.Quality)
				GetFingerprintTemplate();
			}
			else{
				new Promise(function(resolve, reject) {
					ConnectDevice();
					setTimeout(() => resolve(1), 1000); 
				}).then(function() { 
					document.getElementById("close_btn").disabled = true;
				}).then(function() { 
					alert('Capture fail. Please try again!')
				}).then(function() {
					setTimeout(function() {
						document.getElementById("close_btn").disabled = false;
						vue.finger_disabled = false;
						vue.active_scan = false;
					}, 2000);
				});
			}	
		}	
		Request("CaptureFingerprint", "", fingerprintId, "","","","","","","","","","","",5000,50);	
	}
	
	function GetFingerprintTemplate(templateNo){
		ws.onmessage = function(e){			 
			var response = JSON.parse(e.data);
			if(response.ResponseCode == 0){		
				template = response.Base64Data;
				VerifyFingerprintTemplate()
			}
		}
		Request("GetFingerprintTemplate", "", 0, "","","","","","","","","","","",0,0);
	}
	
	function VerifyFingerprintTemplate(){
		Request("VerifyTemplateToMany", "", 0, "",template,"","","","","","","","","",0,0);
		if(wsl){
			wsl.onmessage = function(e){
				var response = JSON.parse(e.data);	
				if(response.ResponseCode == 0){
					if(response.Quality > 40){
						vue.employee_number = response.ReferenceId;
						console.log('Match found. Quality : '  + response.Quality + ', ID : '  + response.ReferenceId);	
					}
					else{
						alert('Match false! Please Try again.');
						console.log('Match found. Quality : '  + response.Quality + ', ID : '  + response.ReferenceId);	
					}			
				}
				else{
					alert('Match false! Please Try again.');
					console.log('Match false!');	
					//~ console.log(response);	
				}
				vue.finger_disabled = false;	
				vue.active_scan = false;
			}
		}
		else{
			ws.onmessage = function(e){
				var response = JSON.parse(e.data);	
				if(response.ResponseCode == 0){
					if(response.Quality > 40){
						vue.employee_number = response.ReferenceId;
						console.log('Match found. Quality : '  + response.Quality + ', ID : '  + response.ReferenceId);	
					}
					else{
						alert('Match false! Please Try again.');
						console.log('Match found. Quality : '  + response.Quality + ', ID : '  + response.ReferenceId);	
					}			
				}
				else{
					alert('Match false! Please Try again.');
					console.log('Match false!');	
					//~ console.log(response);	
				}
				vue.finger_disabled = false;	
			}
		}
	}
	
	function DisconnectDevice(){
		Request("Disconnect", "", 0, "","","","","","","","","","","",0,0);
		ws.onmessage = function(e){			 
			var response = JSON.parse(e.data);		
			if(response.ResponseCode == 0){
				console.log('Device Disconnected');		
			}else{
				console.log('Device Disconnection Failed');	
			}			   
		}			
	}
	
	$(function(){
		
		$('ul#nav_pos').append('<li><a href="#" id="btn-customer-window"><i class="fa fa-window-restore"></i></a></li>');
		
		$('body').on('click', '#btn-customer-window', function() {
			var left = 1360;
			left += window.screenX;
			
			window.open('<?php echo base_url("sales/customer"); ?>','IPC Canteen v.2.0','resizable=0,scrollbars=1,fullscreen=yes,height='+screen.availHeight+',width=' + screen.availWidth + '  , left=' + left + ', toolbar=0, menubar=0,status=1');    
			return 0;
		});
		
		$('#myModal').on('shown.bs.modal', function () {
			$('#employee_number').focus();
		})

	});
	
	
	
	
</script>

