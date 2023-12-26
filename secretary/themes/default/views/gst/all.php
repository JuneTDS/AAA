<div class="header_between_all_section">
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a class="create_gst amber" href="/secretary/gst/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Client" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create GST</a>
            </div>
            <h2></h2>
            <p ><i>If you create one more GST, please fill end date for last one.</i></p>
        </header>
        <div class="panel-body">
            <input type="hidden" name="token" value="4319793a345422bd9f7aa52e802250ae">
            <div class="col-md-12">
                <div style="display:block;padding-top:10px;table-layout: fixed;width:100%">
                    <table class="table table-bordered table-striped mb-none" id="datatable-client" style="width:100%">
                        <thead>
                            <tr style="background-color:white;">
                                <th class="text-center">Country</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">GST Rate</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($gst as $key => $value )
                                {
                            ?>
                                <tr>
                                    <td><?php echo $value->jurisdiction; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($value->start_date)); ?></td>
                                    <td><?php echo ($value->end_date != null) ? date("d-m-Y", strtotime($value->end_date)) : "Present"; ?></td>
                                    <td><?php echo $value->rate."%"; ?></td>
                                    <td>
                                        <a href="/secretary/gst/edit/<?= $value->id ?>"><button class="btn btn-primary pointer mb-sm mr-sm" type="button">Edit</button></a>
                                        <button class="btn btn-primary pointer mb-sm mr-sm delete_btn" data-id="<?= $value->id ?>" type="button">Delete</button>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete GST</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control input-sm" id="delete_id" value="">
                    <p>Are you sure to delete this one?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click','.delete_btn',function() {
            $("#delete_id").val($(this).attr("data-id"));
            $('#myModal').modal('show');
        });

        $(document).on('click','#delete',function() {
            let deleteId = $("#delete_id").val();
            $.ajax({
                "url":"<?=base_url("gst/delete")?>",
                "method":"POST",
                "dataType":"JSON",
                "data":{
                    token: $("input[name='token']").val(),
                    id: deleteId
                },
                success:function(data){
                    if (data.Status == 1) {
                        toastr.success(data.message, "Success");
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
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