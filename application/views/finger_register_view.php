<?php 
//~ $this->load->helper('number_helper');
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	$ip = $_SERVER['HTTP_CLIENT_IP'];
} 
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} 
else {
	$ip = $_SERVER['REMOTE_ADDR'];
}

$server_ip = "172.16.2.84";
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
		<div class="col-md-6" style="width:60%">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h6 class="box-title">Employee Details</h6>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-3">
							<p>
								<img :src="customer_default_image"  class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<input type="text" id="employee_number" class="form-control text-center" maxlength="6" v-model="employee_number" v-on:keyup="get_employee_details" placeholder="Employee Number" />
							</p>
						</div>
						<div class="col-sm-9">
							<table class="table">
								<tbody>
									<tr>
										<td><strong>Employee Number :<strong></td>
										<td>{{ employee.number }}</td>
									</tr>
									<tr>
										<td width="30%"><strong>Full Name :<strong></td>
										<td width="70%" style="text-transform: capitalize;">{{ (employee.name).toLowerCase() }}</td>
									</tr>
									<tr>
										<td><strong>Section :<strong></td>
										<td>{{ employee.section }}</td>
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
					<button disabled id="save_finger" class=" btn btn-success" v-on:click="add_finger()">Save</button>
					<button disabled id="test_finger" class="btn btn-danger" v-on:click="test_finger()">Test</button>
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
										<a id="c1" href="javascript:;" class="btn btn-success" v-on:click="capture_fingerprint(1)">Left Index (1)</a>
									</div>
								</p>

							</div>
							<div class="col-sm-4">
								<p>
									<img id="image2" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a id="c2" href="javascript:;" class="disabled btn btn-success" v-on:click="capture_fingerprint(2)">Left Index (2)</a>
									</div>
								</p>
								
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image3" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
								<p>
									<div class="btn-group btn-group-justified">
										<a id="c3" href="javascript:;" class="disabled btn btn-success" v-on:click="capture_fingerprint(3)">Left Index (3)</a>
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
										<a id="c4" href="javascript:;" class="disabled btn btn-success" v-on:click="capture_fingerprint(4)">Right Index (1)</a>
									</div>
								</p>
						
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image5" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
									<p>
									<div class="btn-group btn-group-justified">
										<a id="c5" href="javascript:;" class="disabled btn btn-success" v-on:click="capture_fingerprint(5)">Right Index (2)</a>
									</div>
								</p>
							
							</div>
							<div class="col-sm-4">
								<p>
									<img id="image6" v-bind:src="finger_default_image" class="img-thumbnail" style="width:137.38px;height:156.97px;">
								</p>
									<p>
									<div class="btn-group btn-group-justified">
										<a id="c6" href="javascript:;" class="disabled btn btn-success" v-on:click="capture_fingerprint(6)">Right Index (3)</a>
									</div>
								</p>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Check Out-->
	<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Test Fingerprint</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<p>
								<img :src="test_default_image_1"  class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<div class="btn-group btn-group-justified">
									<a id="c1" href="javascript:;" class="btn btn-danger" v-on:click="capture_fingerprint(7)">Capture Left Index</a>
								</div>
							</p>
							<p>
								<div class="btn-group btn-group-justified">
									<a id="g1" href="javascript:;" class="btn btn-success" v-on:click="get_template_test(7)">Get Left Index</a>
								</div>
							</p>
						</div>
						<div class="col-sm-6">
							<p>
								<img :src="test_default_image_2"  class="img-thumbnail" alt="Employee Picture">
							</p>
							<p>
								<div class="btn-group btn-group-justified">
									<a id="c1" href="javascript:;" class="btn btn-danger" v-on:click="capture_fingerprint(8)">Capture Right Index</a>
								</div>
							</p>
							<p>
								<div class="btn-group btn-group-justified">
									<a id="g1" href="javascript:;" class="btn btn-success" v-on:click="get_template_test(8)">Get Right Index</a>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" v-on:click="close_test">Close</button>
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
	
	var vue = new Vue({
		el: '#vue_app',
		data: {
			finger_default_image : base_url + "/resources/images/fingerprint_default.png",
			customer_default_image : base_url + "/resources/images/default.jpg",
			test_default_image_1 : base_url + "/resources/images/default.jpg",
			test_default_image_2 : base_url + "/resources/images/default.jpg",
			employee_number : '',
			employee : {
				id: '',
				number: '',
				name: '',
				section: '',
				allowance: '',
				image_link: base_url + '/resources/images/default.png'
			},
		},
		created() {
			//~ this.start();
		},
		watch: {
			employee_number : function(){
				this.get_employee_details(0);
			}
		},
		computed: {
			
		},
		methods : {
			start : function(){
				ConnectDevice('FDU03');
			},
			capture_fingerprint : function(finger_id){
				//~ setTimeout(()=>{
					CaptureFingerprint(0, finger_id);
				//~ },1000);
			},
			get_template : function(finger_id){
				setTimeout(()=>{
					GetFingerprintTemplate(finger_id);
				},1000);
			},
			add_finger : function(){
				if((this.employee_number).length == 6){
					setTimeout(()=>{
						AddFingerprintTemplate(this.employee_number);
					},1000);
				}
				else{
					alert('Employee Number is required.')
				}
			},
			get_employee_details: function(image_id) {
				
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
								section: response.data[0]['section']
							}
							if(image_id == 0){
								this.customer_default_image = base_url + 'resources/images/emp_pics/' + response.data[0]['employee_no']
							}
							else if(image_id == 1){
								this.test_default_image_1 = base_url + 'resources/images/emp_pics/' + response.data[0]['employee_no']
							}
							else if(image_id == 2){
								this.test_default_image_2 = base_url + 'resources/images/emp_pics/' + response.data[0]['employee_no']
							}
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
					
					this.employee = {
						id: '',
						number: '',
						name: '',
						section: ''
					}
					this.customer_default_image =  base_url + '/resources/images/default.png'
				}
			},
			test_finger : function(){
				setTimeout(()=>{
					VerifyFingerprintTemplate();
				},1000);
			},
			close_test : function(){
				$('#myModal').modal('hide');
			}
		}
	});
	
	var ws;	
	var wsl;
	var serverIP = "<?php echo $server_ip; ?>";	
	var clientIP = "<?php echo $client_ip; ?>";
	var state = 0;			
	var txtreference = document.getElementById("txtreference");
	var template1, template2, template3, template4, template5, template6, template7, template8, template9, template10;
	var globalTemplateNo;
	var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
	
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
				if(cmd == "Connect" || cmd == "Disconnect" || cmd == "CaptureFingerprint" || cmd == "GetFingerprintTemplate" || cmd == "VerifyTemplate")
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
	
	function ConnectDevice(deviceName){
		
		Request("GetDeviceList", "", 0, "","","","","","","","","","","",0,0);
		if(wsl){
			wsl.onmessage = function(e){			
				
			}
		}
		else{
			ws.onmessage = function(e){			
				
			}
		}
		
		setTimeout(function() {
			Request("Connect", deviceName, 0, "","","","","","","","","","","",0,0);
			ws.onmessage = function(e){		
				var response = JSON.parse(e.data);	
				if(response.ResponseCode == 0){
				}
				else{	
				}			   
			}
		}, 1000);			
	}
	
	function CaptureFingerprint(fingerprintId,imageNo){
		
		ws.onmessage = function(e){			
			var response = JSON.parse(e.data);	
			console.log(response);
			if(response.ResponseCode == 0){
				//~ alert('Capture success.');
				var imageUrl =  'data:image/png;base64,' + response.Base64Data;
				document.getElementById("image" + imageNo).src = imageUrl;
				if(imageNo != 7){
					//~ document.getElementById("g" + imageNo).classList.remove("disabled");
				}
				if(imageNo == 1){
					vue.employee_number = '';
					document.getElementById("test_finger").disabled = false;
				}
				setTimeout(function() {
					GetFingerprintTemplate(imageNo);
				}, 1000);	
			}
			else{
				alert('Capture fail.');
				ConnectDevice('FDU03');
			}	
		}	
		Request("CaptureFingerprint", "", fingerprintId, "","","","","","","","","","","",5000,50);	
	}
	
	function GetFingerprintTemplate(templateNo){
		ws.onmessage = function(e){			 
			var response = JSON.parse(e.data);
			alert('Get template ' + (response.ResponseCode == 0 ? 'success.' : 'fail.'));
		
			if(response.ResponseCode == 0){			   			     
				console.log(response);
				if(templateNo == 1){
					template1 = response.Base64Data;
				}else if(templateNo == 2){
					template2 = response.Base64Data;
				}else if(templateNo == 3){
					template3 = response.Base64Data;
				}else if(templateNo == 4){
					template4 = response.Base64Data;
				}else if(templateNo == 5){
					template5 = response.Base64Data;
				}else if(templateNo == 6){
					template6 = response.Base64Data;
				}else if(templateNo == 7){
					template7 = response.Base64Data;
				}else if(templateNo == 8){
					template8 = response.Base64Data;
				}else if(templateNo == 9){
					template9 = response.Base64Data;
				}else if(templateNo == 10){
					template10 = response.Base64Data;
				}
				if(templateNo != 6){
					document.getElementById("c" + (parseInt(templateNo) + 1)).classList.remove("disabled");
				}
				else{
					document.getElementById("save_finger").disabled = false;
				}	
			}
		
		}
		Request("GetFingerprintTemplate", "", 0, "","","","","","","","","","","",0,0);
	}
	
	function AddFingerprintTemplate(employeeNumber){
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
		if(template7 == 'undefined' || template7 == null) {
			template7 = "";
		}
		if(template8 == 'undefined' || template8 == null) {
			template8 = "";
		}
		if(template9 == 'undefined' || template9 == null) {
			template9 = "";
		}
		if(template10 == 'undefined' || template10 == null) {
			template10 = "";
		}
		Request("AddFingerprintTemplate", "", 0, employeeNumber, template1, template2, template3, template4, template5, template6, template7, template8, template9, template10, 0, 0);
		if(wsl){
			wsl.onmessage = function(e){	
				var response = JSON.parse(e.data);		   
				if(response.ResponseCode == 0){		
					alert('Fingerprint added to server.');
					console.log(response);	
					
					template1 = "";	
					template2 = "";	
					template3 = "";	
					template4 = "";	
					template5 = "";	
					template6 = "";	
					template7 = "";	
					template8 = "";	
					template9 = "";	
					template10 = "";	
					vue.employee_number = '';
					document.getElementById("save_finger").disabled = true;
					let ctr = 1
					while(ctr <= 6){
						document.getElementById("image" + ctr).src = base_url + "/resources/images/fingerprint_default.png";
						//~ document.getElementById("g" + ctr).classList.add("disabled");
						if(ctr != 1){
							document.getElementById("c" + ctr).classList.add("disabled");
						}
						ctr++;
					}
				}else{
					alert('Fingerprint adding failed.');
				}	
			}
		}else{
			ws.onmessage = function(e){	
				var response = JSON.parse(e.data);		   
				if(response.ResponseCode == 0){		
					alert('Fingerprint added to server.');
					console.log(response);			
					
					template1 = "";	
					template2 = "";	
					template3 = "";	
					template4 = "";	
					template5 = "";	
					template6 = "";	
					template7 = "";	
					template8 = "";	
					template9 = "";	
					template10 = "";	
					vue.employee_number = '';
					let ctr = 1
					document.getElementById("save_finger").disabled = true;
					while(ctr <= 6){
						document.getElementById("image" + ctr).src = base_url + "/resources/images/fingerprint_default.png";
						//~ document.getElementById("g" + ctr).classList.add("disabled");
						if(ctr != 1){
							document.getElementById("c" + ctr).classList.add("disabled");
						}
						ctr
						ctr++;
					}	
				}else{
					alert('Fingerprint adding failed.');
				}	
			}
		}
	}
	
	function VerifyFingerprintTemplate(){
		Request("VerifyTemplateToMany", "", 0, "",template1,"","","","","","","","","",0,0);
		if(wsl){
			wsl.onmessage = function(e){
				var response = JSON.parse(e.data);	
				if(response.ResponseCode == 0){		
					vue.employee_number = response.ReferenceId;
					alert('Match found! Employee ID: ' + response.ReferenceId);
					console.log(response);				
				}
				else{
					alert('Match false!');
					console.log(response);
				}	
			}
		}
		else{
			ws.onmessage = function(e){
				var response = JSON.parse(e.data);	
				if(response.ResponseCode == 0){		
					vue.employee_number = response.ReferenceId;
					alert('Match found! Employee ID: ' + response.ReferenceId);
					console.log(response);				
				}
				else{
					alert('Match false!');
					console.log(response);
				}
			}
		}
	}
</script>

