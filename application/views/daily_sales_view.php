<?php 
	$this->load->helper('number');
	$this->load->helper('date');
	$this->load->helper('string');
?>
<link href="<?php echo base_url('resources/plugins/datatables/datatables.min.css') ?>" rel="stylesheet" >
<link href="<?php echo base_url('resources/plugins/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" >
<section class="content">
	<div class="row">
		<div class="col-md-9">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h6 class="box-title">Daily Sales Report</h6>
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

