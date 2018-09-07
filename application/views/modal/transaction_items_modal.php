<?php 
$this->load->helper('number_helper');
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<span class="pull-right">Reference No: </span>
	<h4 class="modal-title">Transaction Items</h4>
</div>
<div class="modal-body">
	<div class="row">
		<table class="table">
			<thead>
				<tr>
					<td>Item Name</td>
					<td>Quantity</td>
					<td class="text-right">Price</td>
					<td class="text-right">Subtotal</td>
				</tr>
			</thead>
			<tbody>
				<?php 
				$total = 0;
				foreach($data as $row){
				?>
				<tr>
					<td><?php echo $row->name; ?></td>
					<td><?php echo $row->quantity; ?></td>
					<td class="text-right"><?php echo amount($row->price); ?></td>
					<td class="text-right"><?php echo amount($row->total); ?></td>
				</tr>
				<?php 
				$total += $row->total;
				}
				?>
				<tr>
					<td colspan="3" class="text-right"><strong>Total : </strong></td>
					<td class="text-right"><strong><?php echo amount($total); ?></strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

