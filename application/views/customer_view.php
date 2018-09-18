<?php 
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
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="author" content="Chris Desiderio">
		<title>Canteen</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap core CSS -->
		<link href="<?php echo base_url('resources/templates/bootstrap-3.3.7/css/bootstrap.min.css');?>" rel="stylesheet" >
		<!-- Custom styles for this template -->
		<link href="<?php echo base_url('resources/templates/AdminLTE-2.4.2/dist/css/AdminLTE.min.css');?>" rel="stylesheet" >
		
		<link href="<?php echo base_url('resources/plugins/vegas/vegas.min.css');?>" rel="stylesheet" >
		<link href="<?php echo base_url('resources/plugins/toastr/build/toastr.min.css');?>" rel="stylesheet" >
		<link href="<?php echo base_url('resources/css/custom.css');?>" rel="stylesheet" >
	</head>
	<body class="page-v2 layout-full page-dark">
		<div class="page animsition" style="opacity: .90;">
			<div class="page-content">
				<div class="page-main">
					<div class="pull-left" style="padding: 5px 0 0 15px;">
						<img width="100px" style="margin-bottom: 5px;" src="<?php echo base_url('resources/images/logo_white.png')?>">
					</div>
					<div class="pull-right" style="padding-top: 5px">
						<?php echo date('D, d M Y')?>, <span id="clock"></span>
					</div>
					<div class="user-panel" style="visibility: hidden;">
						<div class="pull-left image">
							<img src="<?php echo base_url('resources/images/default.png')?>" onerror="load_default_img()" class="img-circle" alt="User Image">
						</div>
						<div class="pull-left info">
							<p id="customer_name" style="font-size: 14px;text-transform: capitalize;margin-bottom: 5px;"></p>
							<p id="customer_section" class="font-900" style="font-size: 85%;margin-bottom: 5px;"></p>
						</div>
					</div>
					<div id="table-wrapper" class="col-sm-12" style="min-height: 200px;">
						<table id="customer_items" class="customer_items" class="table">
							<thead>
								<tr>
									<th class="col-sm-6">Item</th>
									<th class="col-sm-2 text-right">Price</th>
									<th class="col-sm-2 text-center">Qty</th>
									<th class="col-sm-2 text-right">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
					
					<div class="col-sm-12">
						<hr>
						<table class="customer_items" class="table">
							<tr id="grand_total" style="font-weight: bold;">
								<td width="370px" class="text-right">Total :</td>
								<td width="100px" class="text-right"></td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td class="text-right">Meal Allowance :</td>
								<td id="meal_allowance" class="text-right"></td>
							</tr>
							<tr id="grand_total_2">
								<td class="text-right">Purchase Amount :</td>
								<td class="text-right"></td>
							</tr>
							<tr id="balance" style="font-weight: bold;">
								<td class="text-right">Remaining Meal Allowance :</td>
								<td id="balance" style="border-top: 1px solid #f1f1f1;" class="text-right"></td>
							</tr>
						</table>
						
					</div>
					<div id="customer-footer" class="page-copyright" style="margin-left: -10px;">
						<p>&copy; 2018 <span class="text-red">Management Information System</span> All Rights Reserved.</p>
					</div>
				</div>
			</div>
		</div>
		<script src="<?php echo base_url('resources/js/jquery-3.2.1/dist/jquery.min.js');?>"></script>
		<script src="<?php echo base_url('resources/plugins/vegas/vegas.min.js');?>"></script>
		<script src="<?php echo base_url('resources/plugins/toastr/build/toastr.min.js');?>"></script>
		<script src="<?php echo base_url('resources/templates/bootstrap-3.3.7/js/bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('resources/plugins/socket_io/socket.io-1.7.3.min.js') ?>"></script>
		<script>
			
			const base_url = '<?php echo base_url(); ?>'
			const session = '<?php echo $ip; ?>'
			const socket = io('http://'+ window.location.hostname +':3000/canteen');
			
			socket.emit('join_session', session);
			
			socket.on('new_cart_item', function (data) {
				
				if($('#table-wrapper').is(":hidden")){
					$('#table-wrapper').removeClass('hidden');
				}
				
				$("table#customer_items tbody")
					.append($('<tr>')
						.attr('id', data.id)
						.append($('<td>')
						.attr('class', 'padding-lr-10')
							.text(data.name)
						)
						.append($('<td>')
							.attr('class', 'text-right padding-lr-10')
							.text( data.price)
						)
						.append($('<td>')
							.attr('class', 'text-center padding-lr-10')
							.text(data.quantity)
						)
						.append($('<td>')
							.attr('class', 'text-right padding-lr-10')
							.text(data.total)
						)
					);
			});
			
			socket.on('update_cart_item', function (data) {
				$("table#customer_items tbody tr#"+data.id)
					.html($('<td>')
						.text(data.name)
					)
					.append($('<td>')
						.attr('class', 'text-right')
						.text( data.price)
					)
					.append($('<td>')
						.attr('class', 'text-center')
						.text(data.quantity)
					)
					.append($('<td>')
						.attr('class', 'text-right')
						.text(data.total)
					)
			});
			
			socket.on('delete_cart_item', function (data) {
				$("table#customer_items tbody tr#"+data.id).remove();	
			});
			
			socket.on('update_cart_total', function (data) {
				$("tr#grand_total td:nth-child(2), tr#grand_total_2 td:nth-child(2)").text(data.total);	
			});
			
			socket.on('update_balance', function (data) {
				$("tr#balance td:nth-child(2)").text(data.balance);	
				
				if(data.balance < -200){
					toastr.remove()
					toastr.options.timeOut = 0;
					toastr.options.extendedTimeOut = 0;
					toastr.error('Credit limit has been exceeded.','Cannot proceed!')
					//~ alert('error');
				}
				else{
					toastr.remove()
				}
			});
			
			socket.on('clear_cart', function (data) {
				$("table#customer_items tbody").html('');
				
				$(".user-panel").css('visibility', 'hidden');
				
				$('.image img').attr('src', base_url + 'resources/images/default.png');
				$('p#customer_name').text('');
				$('p#customer_employee_no').text('');
				$('p#customer_section').text('');
				$('td#meal_allowance').text('');
				$('td#balance').text('');
				
				toastr.remove()
			});

			socket.on('transaction_completed', function (data) {
				toastr.options.timeOut = 0;
				toastr.options.extendedTimeOut = 0;
				toastr.success('Please get your receipt.','Transaction Completed')
			});

			socket.on('employee_details', function (data) {
				
				$(".image img").attr("src", data.employee.image_link);
				$('p#customer_name').text(data.employee.name.toLowerCase());
				$('p#customer_employee_no').text(data.employee.number.toLowerCase());
				$('p#customer_section').text(data.employee.section);
				$('td#meal_allowance').text(data.employee.allowance);
				$('td#balance').text(data.balance);
				
				$(".user-panel").css('visibility', 'visible');
				
				if(data.balance < -200){
					toastr.remove()
					toastr.options.timeOut = 0;
					toastr.options.extendedTimeOut = 0;
					toastr.error('Credit limit has been exceeded.','Cannot proceed!')
					//~ alert('error');
				}
				else{
					toastr.remove()
				}
			});

			socket.on('refresh', function () {
				location.reload();
			});

			function load_default_img() {
				$('.image img').attr('src', base_url + 'resources/images/default.png');
			}
			
			function updateClock() 
				{
					var currentTime = new Date();
					// Operating System Clock Hours for 12h clock
					var currentHoursAP = currentTime.getHours();
					// Operating System Clock Hours for 24h clock
					var currentHours = currentTime.getHours();
					// Operating System Clock Minutes
					var currentMinutes = currentTime.getMinutes();
					// Operating System Clock Seconds
					var currentSeconds = currentTime.getSeconds();
					// Adding 0 if Minutes & Seconds is More or Less than 10
					currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
					currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
					// Picking "AM" or "PM" 12h clock if time is more or less than 12
					var timeOfDay = (currentHours < 12) ? "AM" : "PM";
					// transform clock to 12h version if needed
					currentHoursAP = (currentHours > 12) ? currentHours - 12 : currentHours;
					// transform clock to 12h version after mid night
					currentHoursAP = (currentHoursAP == 0) ? 12 : currentHoursAP;
					// display first 24h clock and after line break 12h version
					var currentTimeString = currentHoursAP + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
					// print clock js in div #clock.
					$("#clock").html(currentTimeString);
				}
			
			$(document).ready(function (){
				$("body.page-v2").vegas({
					//~ overlay: true, 
					transition: 'fade', 
					transitionDuration: 4000,
					delay: 10000,
					color: 'red',
					animation: 'random',
					animationDuration: 20000,
					slides: [
						{ src: base_url + 'resources/images/bg/ipc.jpg' },
						{ src: base_url + 'resources/images/bg/mux1.jpg' },
						{ src: base_url + 'resources/images/bg/dmax1.jpg' },
						{ src: base_url + 'resources/images/bg/truck1.jpg' },
						{ src: base_url + 'resources/images/bg/ipc.jpg' },
						{ src: base_url + 'resources/images/bg/mux2.jpg' },
						{ src: base_url + 'resources/images/bg/dmax2.jpg' },
						{ src: base_url + 'resources/images/bg/truck2.jpg' },
						{ src: base_url + 'resources/images/bg/ipc.jpg' },
						{ src: base_url + 'resources/images/bg/mux3.jpg' },
						{ src: base_url + 'resources/images/bg/dmax3.jpg' },
						{ src: base_url + 'resources/images/bg/truck3.jpg' },
						{ src: base_url + 'resources/images/bg/ipc.jpg' },
						{ src: base_url + 'resources/images/bg/mux4.jpg' },
						{ src: base_url + 'resources/images/bg/dmax4.jpg' }
						
					]
				});
				
				setInterval(updateClock, 1000);
			});
		</script>
	</body>
</html>
