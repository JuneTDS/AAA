<div class="header_between_all_section">
    <section class="panel">
        <!-- <header class="panel-heading">
            <div class="panel-actions">
                <a class="create_gst amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Client" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create GST</a>
            </div>
            <h2>Edit GST</h2>
        </header> -->
        <div class="panel-body">
            <div class="col-md-12">
                <div style="display:block;padding-top:10px;table-layout: fixed;width:100%">
                    <header class="panel-heading">
                        <h2 class="panel-title">Create GST</h2>
                    </header>
                    <div class="panel-body">
                        <input type="hidden" name="token" value="4319793a345422bd9f7aa52e802250ae">
                        <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">
                            <tbody>
                                <tr>
                                    <th>Country</th>
                                    <td>
                                        <select id="entity_type" class="form-control entity_type" style="text-align:right; width: 400px;" name="jurisdiction">
                                            <option value="0">Select Company Type</option>
                                            <?php
                                            foreach ($jurisdiction as $key => $value) {
                                            ?>
                                                <option value="<?= $value->id; ?>"><?= $value->jurisdiction; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div id="form_entity_type"></div>
                                    </td>               
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td>
                                        <input type="date" class="form-control input-xs" id="start_date" name="start_date" required />
                                    </td>               
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                    <td>
                                    <input type="date" class="form-control input-xs" id="end_date" name="end_date" />
                                    </td>               
                                </tr>
                                <tr>
                                    <th>Rate</th>
                                    <td>
                                        <input type="number" class="form-control input-xs" id="rate" name="rate" />
                                    </td>               
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <input type="button" class="btn btn-primary" value="Create" id="create">
                                <a href="<?=base_url("gst")?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#create', function() {
            $.ajax({
                "url":"<?=base_url("gst/save")?>",
                "method":"POST",
                "dataType":"JSON",
                "data":{
                    token: $("input[name='token']").val(),
                    jurisdiction: $("select[name='jurisdiction']").val(),
                    start_date: $("input[name='start_date']").val(),
                    end_date: $("input[name='end_date']").val(),
                    rate: $("input[name='rate']").val(),
                },
                success:function(data){
                    if (data.Status == 1) {
                        toastr.success(data.message, "Success");
                    } else {
                        toastr.error(data.message, "Error");
                    }
                },
                error: function(data) {
                    console.log(data)
                },
            });
        });
    });
</script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>