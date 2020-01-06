<form action="<?php echo base_url('MealAllowance/transfer_meal_allowance'); ?>" id="meal_allowance_form" method="POST" enctype="multipart/form-data">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title pull-right">
                            <!-- excel/Uploading_Template.xlsx -->
                            <a class="fa fa-file-excel-o" href="<?php echo base_url('resources/excel/MEAL ALLOWANCE.xlsx')?>" data-toggle="tooltip" title="Sample Template" style="font-size:20px; color:#575962; font-weight: bold; color:#008d4c;"></a>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group row">
                            <label for="meal_allowance_excel" class="col-sm-2" style="padding-left: 30px;">File</label>
                            <div class="col-sm-7">
                                <input type="file" class="form-control" name="excel_file" id="excel_file">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success" id="btn_upload" name="btn_upload"><i class="fa fa-upload"></i>&nbsp;&nbsp;<b>Upload</b></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            </div> 
        </div>
    </div>
</form>

<script type="text/javascript">
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));
    }

    $(document).ready(function(){

    });
</script>