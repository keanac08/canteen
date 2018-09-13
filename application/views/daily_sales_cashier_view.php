<?php 
	$this->load->helper('number');
	$this->load->helper('date');
	$this->load->helper('string');
?>
<link href="<?php echo base_url('resources/plugins/datatables/datatables.min.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css'); ?>" rel="stylesheet">

<section class="content">
	<div class="row">
		<div class="col-md-9">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h6 class="box-title">Cashier Sales</h6>
				</div>
				<div class="row">
					<div class="col-sm-9" style="padding: 10px;margin-left: 20px;">
						<form id="form_filters" class="form-horizontal" method="POST" accept-charset="utf-8">
							<input type="hidden" name="from_date" value="<?php echo $from_date; ?>"/>
							<input type="hidden" name="to_date" value="<?php echo $to_date; ?>"/>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="unput1">Purchase Date</label>
								<div class="col-sm-4">
									<input class="form-control" type="text" name="purchase_date" value="<?php echo date('m/d/Y', strtotime($from_date)) .' - '. date('m/d/Y', strtotime($to_date)); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="unput1">Cashier</label>
								<div class="col-sm-4">
									<select class="form-control selectpicker" data-live-search="true" name="cashier_id">
										<option value="99999">Nothing Selected...</option>
										<?php 
										foreach($cashiers as $cashier){
											if($cashier_id == $cashier->id){
											?>
												<option selected value="<?php echo $cashier->id;?>"><?php echo $cashier->name;?></option>
											<?php 
											}
											else{
											?>
												<option value="<?php echo $cashier->id;?>"><?php echo $cashier->name;?></option>
											<?php	
											}
										}
										?>
									</select>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="box-body">
					<div class="col-sm-12">
						<table class="table" id="sales_report" class="display" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Reference Number</th>
									<th>Customer Name</th>
									<th>Cashier Name</th>
									<th>Purchase Date</th>
									<th width="10%" class="text-right">Amount</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$total = 0;
							foreach($data as $row){
							?>
								<tr>
									<td><a href="#" class="btn_modal" data-id="<?php echo $row->id; ?>"><?php echo $row->id; ?></a></td>
									<td><?php echo camelcase($row->customer_name); ?></td>
									<td><?php echo camelcase($row->cashier_name); ?></td>
									<td><?php echo long_date($row->purchase_date); ?></td>
									<td class="text-right"><?php echo amount($row->total_purchase); ?></td>
								</tr>
							<?php 
							$total += $row->total_purchase;
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="box-footer text-right">
					<strong>Total Amount &nbsp;:&nbsp; <?php echo amount($total);?></strong>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	<div class="modal-dialog modal-danger">
		<div class="modal-content">
		
		</div>
	</div>
</div>
<script src="<?php echo base_url('resources/plugins/daterangepicker/moment.min.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/daterangepicker/daterangepicker.js');?>"></script>
<script src="<?php echo base_url('resources/plugins/datatables/datatables.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js'); ?>"></script>

<script>
	$(document).ready(function() {   
		
		$('#sales_report').DataTable({
			//~ 'bSort' : false
			"order": []
		});
		
		$('input[name="purchase_date"]').daterangepicker();
		$('input[name="purchase_date"]').on('apply.daterangepicker', function(ev, picker) {
			$('input[name="from_date"]').val(picker.startDate.format('YYYY-MM-DD'));
			$('input[name="to_date"]').val(picker.endDate.format('YYYY-MM-DD'));
			form_filters.submit();
		});
		
		//~ $('.selectpicker').selectpicker();
		$('.selectpicker').on('change', function(){
			$('input[name="from_date"]').val($('input[name=purchase_date]').data('daterangepicker').startDate.format('YYYY-MM-DD'));
			$('input[name="to_date"]').val($('input[name=purchase_date]').data('daterangepicker').endDate.format('YYYY-MM-DD'));
			form_filters.submit();
		});

		$('body').on('click','a.btn_modal',function(){
			var id = $(this).data('id');
			$.ajax({
				method: 'POST',
				url: '<?php echo base_url(); ?>sales/ajax_transaction_items',
				data: {
					id : id
				},
				success: function(data)
				{				
					console.log(data);	
					$('.modal-content').html(data);
					$('#myModal').modal('show');
				}
			});
		});
	});
</script>

