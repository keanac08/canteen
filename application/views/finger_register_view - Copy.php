<?php 
//~ $this->load->helper('number_helper');
?>

<link href="<?php echo base_url('resources/plugins/vertical-tabs/bootstrap.vertical-tabs.min.css') ?>" rel="stylesheet" >
<section class="content" id="vue_app">
	<div class="row">
		<div class="col-md-6" style="width:60%">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h6 class="box-title">Employee Details</h6>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-3">
							<p>
								<img src="<?php echo base_url('resources/images/default.jpg'); ?>"  class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<input type="text" id="employee_number" class="form-control text-center" maxlength="6" placeholder="Employee Number" />
							</p>
						</div>
						<div class="col-sm-9">
							<table class="table">
								<tbody>
									<tr>
										<td><strong>Name :<strong></td>
										<td colspan="2" style="text-transform: capitalize;">Christopher Desiderio</td>
									</tr>
									<tr>
										<td><strong>Section :<strong></td>
										<td colspan="2">Management Information System</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="box-footer text-right">
					<button class="btn btn-danger" v-on:click="save_finger_template()">Save</button>
				</div>
			</div>
		</div>
		<div class="col-md-6" style="width:40%">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-4">
								<p>
									<img id="image1" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-danger" v-on:click="capture_fingerprint(1)">Left Index (A)</button>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-success" v-on:click="get_template(1)">Get (A)</button>
									</div>
								</p>
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image2" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-danger" v-on:click="capture_fingerprint(2)">Left Index (B)</button>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-success" v-on:click="get_template(2)">Get (B)</button>
									</div>
								</p>
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image3" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-danger" v-on:click="capture_fingerprint(3)">Left Index (C)</button>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<button class="btn btn-success" v-on:click="get_template(3)">Get (C)</button>
									</div>
								</p>
							</div>
						</div>
						<div class="row" >
							<div class="col-sm-4">
								<p>
									<img id="image4" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-danger" v-on:click="capture_fingerprint(4)">Right Index (D)</a>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-success" v-on:click="get_template(4)">Get (D)</a>
									</div>
								</p>
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image5" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
									<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-danger" v-on:click="capture_fingerprint(5)">Right Index (E)</a>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-success" v-on:click="get_template(5)">Get (E)</a>
									</div>
								</p>
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image6" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
									<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-danger" v-on:click="capture_fingerprint(6)">Right Index (F)</a>
									</div>
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a href="javascript:;" class="btn btn-success" v-on:click="get_template(6)">Get (F)</a>
									</div>
								</p>
							</div>
						</div>
					</div>
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
	
	const base_url = '<?php echo base_url(); ?>'
	
	new Vue({
		el: '#vue_app',
		data: {
			ws : '',
			wsl : '',
			serverIP : "172.16.2.52",	
			clientIP : "172.16.2.52",
			state : 0,
			
			//~ finger default image
			finger_default_image : base_url + "/resources/images/fingerprint_default.png",
			current_finger_id : '',
			
			//~ finger templates
			template_1 : '',
			template_2 : '',
			template_3 : '',
			template_4 : '',
			template_5 : '',
			template_6 : ''
		},
		created() {
			setTimeout(()=>{
				this.connect_device('FDU03')
			},1000);
		},
		watch: {

		},
		computed: {
			
		},
		methods : {
			request : function (cmd, deviceName, fingerprintId, referenceId, base64data1, base64data2, base64data3, base64data4, base64data5, base64data6, base64data7, base64data8, base64data9, base64data10, timeout, threshold){
			
				var var_request = {"FingerPrintCommand" : cmd,
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
				if(!this.ws){
					try{
						if(this.serverIP == ""){
							alert('Please setup WebSocket to Server');
						}else{
							if(this.clientIP != this.serverIP)
								this.wsl = new WebSocket("ws://" + this.serverIP + ":5200/secugentoolservice/fingerprint");									
							this.ws = new WebSocket("ws://" + this.clientIP + ":5200/secugentoolservice/fingerprint");	
						}				
					}catch(e){
						this.ws = null;
					}
				}
				
				this.state = this.ws.readyState;	
				
				if(this.state == 0){		
						
						//~ this.ws.onopen = function(){					
							//~ this.ws.send(JSON.stringify(var_request));		
						//~ };
						
						setTimeout(()=>{
							this.request(cmd, deviceName, fingerprintId, referenceId, base64data1, base64data2, base64data3, base64data4, base64data5, base64data6, base64data7, base64data8, base64data9, base64data10, timeout, threshold)
						},1000);
											
						return;
				}

						
				try{	
					if(this.state == 0){
						this.ws.onopen = function(){					
							this.ws.send(JSON.stringify(var_request));		
						};					
					}
					else if(this.state == 3){
						if(this.serverIP == ""){
							alert('Please setup WebSocket to Server');
						}else{
							if(this.clientIP != this.serverIP)
								this.wsl = new WebSocket("ws://" + this.serverIP + ":5200/secugentoolservice/fingerprint");									
							this.ws = new WebSocket("ws://" + this.clientIP + ":5200/secugentoolservice/fingerprint");	
						}		
						this.ws.onopen = function(){					
							this.ws.send(JSON.stringify(var_request));		
						};						
					}
					else{	
						if(cmd == "Connect" || cmd == "Disconnect" || cmd == "CaptureFingerprint" || cmd == "GetFingerprintTemplate" || cmd == "VerifyTemplate")
						{
							this.ws.send(JSON.stringify(var_request));	
						}	
						else
						{
							if(this.wsl)
								this.wsl.send(JSON.stringify(var_request));
							else
								this.ws.send(JSON.stringify(var_request));
						}
					}								
				}catch(e){
					this.ws = null;
					Console.log("could not connect to server");
				}			
			},
			connect_device : function(device_name){
				
				this.request("GetDeviceList", "", 0, "","","","","","","","","","","",0,0);
				if(this.wsl){
					this.wsl.onmessage = function(e){			
						
					}
				}
				else{
					this.ws.onmessage = function(e){			
						
					}
				}
				setTimeout(()=>{
					this.request("Connect", device_name, 0, "","","","","","","","","","","",0,0);
					this.ws.onmessage = function(e){		
							   
					}	
				 }, 1000);
					
			},
			capture_fingerprint : function(finger_id){
				
				this.ws.onmessage = function(e){		
				
					var response = JSON.parse(e.data);		
					console.log(response); 
					
					if(response.ResponseCode == 0){
						alert('Capture success.');
						document.getElementById("image"+finger_id).src = "data:image/png;base64," + response.Base64Data;
					}
					else{
						alert('Capture fail.');
					}	
				}	
				this.request("CaptureFingerprint", "", 0, "140201","","","","","","","","","","",5000,50);	
			},
			get_template : function(finger_id){	
				
				this.ws.onmessage = function(e){			 
					var response = JSON.parse(e.data);	
					alert('Get template ' + (response.ResponseCode == 0 ? 'success.' : 'fail.'));	
					if(response.ResponseCode == 0){			   			     
						console.log(response);
						if(finger_id == 1){
							this.template1 = response.Base64Data;
						}
						else if(finger_id == 2){
							this.template2 = response.Base64Data;
						}
						else if(finger_id == 3){
							this.template3 = response.Base64Data;
						}
						else if(finger_id == 4){
							this.template4 = response.Base64Data;
						}
						else if(finger_id == 5){
							this.template5 = response.Base64Data;
						}
						else if(finger_id == 6){
							this.template6 = response.Base64Data;
						}
					}	
				}
				this.request("GetFingerprintTemplate", "", 0, "","","","","","","","","","","",0,0); 
				 
			},
			save_finger_template : function(){
				
				if(template1 == 'undefined' || template1 == null) {
					template1 = "";
				}
				if(template2 == 'undefined' || template2 == null) {
					template2 = "";
				}
				if(template3 == 'undefined' || template3 == null) {
					template3 = "";
				}
				if(template4 == 'undefined' || template4 == null) {
					template4 = "";
				}
				if(template5 == 'undefined' || template5 == null) {
					template5 = "";
				}
				if(template6 == 'undefined' || template6 == null) {
					template6 = "";
				}
				this.request("AddFingerprintTemplate", "", 0, '140201', template1, template2, template3, template4, template5, template6, "", "", "", "", 0, 0);
				if(this.wsl){
					this.wsl.onmessage = function(e){	
						var response = JSON.parse(e.data);		
						if(response.ResponseCode == 0){		
							alert('Fingerprint added to server.');
							console.log(response);				
						}else{
							alert('Fingerprint adding failed.');
						}	
					}
				}else{
					this.ws.onmessage = function(e){	
						var response = JSON.parse(e.data);	
						if(response.ResponseCode == 0){		
							alert('Fingerprint added to server.');
							console.log(response);				
						}else{
							alert('Fingerprint adding failed.');
						}	
					}
				}	
			}
		}
	});
	
	$(function(){
		
		
	});

</script>

