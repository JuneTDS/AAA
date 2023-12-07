<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrapvalidator/dist/css/bootstrapValidator.min.css"/>
<script type="text/javascript" src="<?=base_url()?>node_modules/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>

<!-- Toastr plugin to pop out message -->
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<script src="<?= base_url() ?>application/js/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<link rel="<?= base_url() ?>application/css/bootstrap-multiselect/bootstrap-multiselect.css">
<link href="<?= base_url() ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<section class="content-body" style="margin-left:0; padding-top: 0;">
<div style="margin-top: 30px;">
	<?php echo isset($breadcrumbs);?>
</div>
<div class="row">

    <!-- <div class="col-sm-2" style="margin-top: 30px;">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>

                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
            </div>
        </div>
    </div> -->
    <div class="col-sm-12" style="margin-top: 20px;">
	<div class="tabs">
											
		<ul class="nav nav-tabs nav-justify">
			<li class="active check_stat" id="#li-account" data-information="account">
				<a href="#w2-account" data-toggle="tab" class="text-center">
					Profile
				</a>
			</li>
			<li class="check_stat" id="#li-change_password" data-information="change_password">
				<a href="#w2-change_password" data-toggle="tab" class="text-center">
					Password
				</a>
			</li>
			<?php if($id == $this->session->userdata('user_id')){ ?>
				<li class="check_stat" id="#li-rules" data-information="rules">
					<a href="#w2-rules" data-toggle="tab" class="text-center">
						Rules
					</a>
				</li>
			<?php }?>
			<!-- <li class="check_stat" id="#li-Image" data-information="Image">
				<a href="#w2-Image" data-toggle="tab" class="text-center">
					Image
				</a>
			</li> -->
		</ul>
	
		<div class="tab-content">
			<div id="w2-account" class="tab-pane active">
				<div class="row">
					<div class="col-lg-12">

						<?php $attrib = array('id' => 'update_profile','class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
						echo form_open('', $attrib);
						?>
						<!-- <?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?> -->
						<!-- <?php echo ($user->firm_id) ?> -->
						<h4 class="text-primary" style="margin-bottom: 30px;"><?= $user->email; ?></h4>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-8">

									<div class="form-group">
										<label for="first_name" class="profile_label"><?php echo lang('first_name', 'first_name'); ?> :</label>
										<div class="col-sm-5">
											<?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required" style="width:300px;" maxlength="50"'); ?>
										</div>
									</div>

									<div class="form-group">
										<label for="first_name" class="profile_label"><?php echo lang('last_name', 'last_name'); ?> :</label>

										<div class="col-sm-5">
											<?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required" style="width:300px;" maxlength="50"'); ?>
										</div>
									</div>

									<?php if($part == "access_client"){ ?>
										<input type="hidden" id="role" name="role" class="form-control"value="4" />

										<div class="form-group" style="overflow: visible; height: 100px">
											<div class="col-sm-2">
			                                	<label for="client" class="profile_label">Client:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                    <select class="form-control" id="selected_client" multiple="multiple" name="selected_client[]">
			                                    </select>
			                                </div>
			                                
			                            </div>
									<?php } ?>
									<?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
										<div class="form-group">
											<div class="col-sm-2">
			                                	<label for="email" class="profile_label">Role:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                        <select class="form-control" style="text-align:right;width: 300px;" name="role" id="role">
			                                            <option value="0" >Select Role</option>
			                                        </select>
			                                    
			                                </div>
			                            </div>
			                            <div class="form_group has-success manager_in_charge_div">
                            			</div>
			                        <?php } ?>
			                        <?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
			                        	<div class="form-group">
											<div class="col-sm-2">
			                                	<label for="department" class="profile_label">Department:</label>
			                                </div>
			                                <div class="col-sm-5">
			                                        <select class="form-control" style="text-align:right;width: 300px;" name="department" id="department">
			                                            <option value="0" >Select Department</option>
			                                        </select>
			                                    
			                                </div>
			                            </div>
		                            <?php } ?>
									<?php if (($Admin && $Admin != null && $id != $this->session->userdata('user_id') && $part != "access_client") || ($Manager && $Manager != null && $id != $this->session->userdata('user_id') && $part != "access_client")) { ?>
										<div class="form-group" style="overflow: visible; height: 100px">
											<div class="col-sm-2">
			                                	<label for="firm" class="profile_label">Firm: </label>
			                                </div>
			                                <div class="col-sm-5">
			                                    <select id="selected_firm" multiple="multiple" name="selected_firm[]">
			                                    </select>
			                                </div>
			                            </div>
		                            <?php } ?>
		                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="form-group">
											<?php echo lang('username', 'username'); ?>
											<input type="text" name="username" class="form-control"
												   id="username" value="<?= $user->username ?>"
												   required="required"/>
										</div>
										<div class="form-group">
											<?php echo lang('email', 'email'); ?>

											<input type="email" name="email" class="form-control" id="email"
												   value="<?= $user->email ?>" required="required"/>
										</div>

									<?php } ?>

								</div>
								<div class="col-md-5 col-md-offset-1">
									<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
										<div class="row">
											<div class="panel panel-warning">
												<div
													class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
												<div class="panel-body" style="padding: 5px;">
													<div class="col-md-12">
														<div class="col-md-12">
															<div class="form-group">
																<?php echo lang('password', 'password'); ?>
																<?php echo form_input($password); ?>
															</div>

															<div class="form-group">
																<?php echo lang('confirm_password', 'password_confirm'); ?>
																<?php echo form_input($password_confirm); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

									<?php } ?>
									<?php echo form_hidden('id', $id); ?>
									<?php echo form_hidden($csrf); ?>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-right" style="margin-top: 10px;">
		                    <input type="button" value="Update" id="update" class="btn btn_blue ">
		                    <?php if ($id != $this->session->userdata('user_id') && $part != "access_client") { ?>
		                    	<a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                    <?php if ($id == $this->session->userdata('user_id') && $part != "access_client") { ?>
		                    	<a href="<?= base_url();?>welcome/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                    <?php if($part == "access_client"){ ?>
		                    	<a href="<?= base_url();?>auth/client/" class="btn btn-default">Cancel</a>
		                    <?php } ?>
		                </div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
			<div id="w2-change_password" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
                            <div class="col-lg-12">
                                <!-- <?php echo form_open("auth/change_password", 'id="change-password-form"'); ?> -->
                                <?php $attrib = array('id' => 'change-password-form','class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open('', $attrib);
								?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="old_password" class="profile_label"><?php echo lang('old_password', 'curr_password'); ?> :</label>
                                                <div class="col-sm-5">
                                                	<?php echo form_password('old_password', '', 'class="form-control" id="curr_password" style="width:300px" required="required" '); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
												<label for="new_password" class="profile_label"><?php echo sprintf(lang('new_password'), $min_password_length); ?> :</label>
												<div class="col-sm-8">
	                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" style="width:300px" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>

	                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
	                                            </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="confirm_password" class="profile_label"><?php echo lang('confirm_password', 'new_password_confirm'); ?> :</label>
                                                <div class="col-sm-5">
	                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" style="width:300px" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
	                                            </div>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                            <!-- <p style="margin-top: 10px;"><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn_blue" id="change_password_button"'); ?> </p> -->
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- <input style="margin-top: 10px;" type="button" value="Change Password" id="change_password_button" class="btn btn-primary "> -->
                                <div class="col-md-12 text-right" style="margin-top: 10px;">
				                    <input type="button" value="Change Password" id="change_password_button" class="btn btn_blue ">
				                    <?php if ($id != $this->session->userdata('user_id') && $part != "access_client") { ?>
				                    	<a href="<?= base_url();?>auth/users/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                    <?php if ($id == $this->session->userdata('user_id') && $part != "access_client") { ?>
				                    	<a href="<?= base_url();?>welcome/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                    <?php if($part == "access_client"){ ?>
				                    	<a href="<?= base_url();?>auth/client/" class="btn btn-default">Cancel</a>
				                    <?php } ?>
				                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    
					</div>
				</div>
			</div>
			<div id="w2-rules" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-md-12">
								<h2>Rules</h2>
								<table class="table table-bordered table-striped mb-none" id="rules_table">
									<thead>
									  <tr>
									    <th>Type</th>
									    <th>Description</th>
									  </tr>
									</thead>
									<tbody class="rules_table">
									</tbody>

								  
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="w2-Image" class="tab-pane">
				<div class="row">
					<div class="col-lg-12">
					    <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-5">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $user->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo form_open_multipart("auth/update_avatar"); ?>
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?>
                                        
                                        <input type="file" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="file"/>
                                        
                                               <!-- <div class="file-loading">
								                <input type="file" id="multiple_file" class="file" name="uploadimages[]" multiple data-min-file-count="0">
								            </div> -->
                                    </div>
                                    <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    
					</div>
				</div>
			</div>
		</div>
	</div>
        
    </div>
</div>
</section>
    <div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
    <script>
    	var in_use_user_id = <?php echo json_encode($id);?>;
    	var user_id = <?php echo json_encode($this->session->userdata('user_id'));?>;
    	var field_name;
	    var manager_in_charge_value = <?php echo json_encode($user->manager_in_charge);?>;
	    var part = <?php echo json_encode($part);?>;
	    var base_url = '<?php echo base_url() ?>';

    	$("#header_our_firm").removeClass("header_disabled");
	    
	    $("#header_access_right").removeClass("header_disabled");
	    if(in_use_user_id == user_id)
	    {
	    	$("#header_manage_user").removeClass("header_disabled");
	    	$("#header_user_profile").addClass("header_disabled");
	    }
	    else if(in_use_user_id != user_id)
	    {
	    	$("#header_manage_user").addClass("header_disabled");
	    	$("#header_user_profile").removeClass("header_disabled");
	    }
	    $("#header_setting").removeClass("header_disabled");
	    $("#header_dashboard").removeClass("header_disabled");
	    $("#header_client").removeClass("header_disabled");
	    $("#header_person").removeClass("header_disabled");
	    $("#header_document").removeClass("header_disabled");
	    $("#header_report").removeClass("header_disabled");
	    $("#header_billings").removeClass("header_disabled");

	    
	    //console.log(manager_in_charge_value);
	    /*$(document).ready(function() {
	        $('#selected_firm').multiselect({
	            buttonWidth: '300px',
	            preventInputChangeEvent: true,
	            buttonText: function(options, select) {
	                if (options.length === 0) {
	                    return 'Select the Firm';
	                }
	                else if (options.length > 3) {
	                    return 'More than 3 firm selected!';
	                }
	                else {
	                     var labels = [];
	                     options.each(function() {
	                         if ($(this).attr('label') !== undefined) {
	                             labels.push($(this).attr('label'));
	                         }
	                         else {
	                             labels.push($(this).html());
	                         }
	                     });
	                     return labels.join(', ') + '';
	                }
	            }
	        });
	    });*/
	    

	    $(document).on('click',".check_stat",function() 
		{
			$profile_index_tab_aktif = $(this).data("information");

			if($profile_index_tab_aktif == "rules")
			{
				$(".list_of_rules").remove();
				var depart_id = <?php echo json_encode($user->department_id) ?>;

				$.ajax({
			        type: "POST",
			        url: "<?php echo site_url('auth/get_rules'); ?>",
			        data: '&department_id=' + depart_id,
			        dataType: "json",
			        async: false,
			        success: function(data){
			            if(data.length > 0)
			            {

			                for(var f = 0; f < data.length; f++)
			                {
					            $b=""; 
						        $b += '<tr class="list_of_rules">';
						        $b += '<td>'+(data[f]["type"])+'</td>';
						        $b += '<td>'+(data[f]["description"])+'</td>';
						        $b += '</tr>';

						        $(".rules_table").append($b);

			                }
			            }
			            else
			            {
			                $b=""; 
					        $b += '<tr class="list_of_rules">';
					        $b += '<td colspan="2" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
					        $b += '</tr>';

					        $(".rules_table").append($b);
			            }
			        }               
			    });

			}
		});

        $(document).ready(function () {

            $('#change-password-form').bootstrapValidator({
                //message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]',
                fields: {
		            old_password: {
		                validators: {
		                    notEmpty: {
		                        message: 'The Old Password is required.'
		                    }/*,
		                    regexp: {
                              regexp: '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                              message: 'The value is not a valid password.'
                            }*/
		                }
		            }
		        }
            });

            if(part == "access_client")
            {
            	field_name = "selected_client[]";
            }
            else
            {
            	field_name = "selected_firm[]";
            }
            //console.log(field_name);
            $('#update_profile')
            	.find('[name="'+field_name+'"]')
                .multiselect({
                    buttonWidth: '300px',
                    maxHeight: 200,
                    buttonText: function(options, select) {
                        if (options.length === 0) {
                        	if(part == "access_client")
            				{
                            	return 'Select the client';
                            }
                            else
                            {
                            	return 'Select the Firm';
                            }
                        }
                        else if (options.length > 1) {
                        	if(part == "access_client")
            				{
                            	return 'More than 1 client selected!';
                            }
                            else
                            {
                            	return 'More than 1 Firm selected!';
                            }
                        }
                        else {
                             var labels = [];
                             options.each(function() {
                                 if ($(this).attr('label') !== undefined) {
                                     labels.push($(this).attr('label'));
                                 }
                                 else {
                                     labels.push($(this).html());
                                 }
                             });
                             return labels.join(', ') + '';
                        }
                    },
                    // Re-validate the multiselect field when it is changed
                    onChange: function(element, checked) {
                        //console.log($('#create_user_form').bootstrapValidator('revalidateField', 'selected_firm'));
                        //console.log(field_name);
                        if(part == "access_client")
            			{
                        	$('#update_profile').bootstrapValidator('revalidateField', "selected_client[]");
                        }
                        else
                        {
                        	$('#update_profile').bootstrapValidator('revalidateField', 'selected_firm[]');
                        }
                    }
                })
                .end()
            	.bootstrapValidator({
                submitButtons: 'input[id="update"]',
                excluded: ':disabled',
                fields: {
		            first_name: {
		                validators: {
		                    notEmpty: {
		                        message: 'The first name is required'
		                    }
		                }
		            },
		            last_name: {
		                validators: {
		                    notEmpty: {
		                        message: 'The last name is required'
		                    }
		                }
		            },
		            role: {
                        validators: {
                            callback: {
                                message: 'The role is required',
                                callback: function(value, validator, $field) {
                                    //var num = jQuery($field).parent().parent().parent().attr("num");
                                    var options = validator.getFieldElements('role').val();
                                    return (options != null && options != "0");
                                }
                            }
                        }
                    },
                    department: {
                        validators: {
                            callback: {
                                message: 'The department is required',
                                callback: function(value, validator, $field) {
                                    //var num = jQuery($field).parent().parent().parent().attr("num");
                                    var options = validator.getFieldElements('department').val();
                                    return (options != null && options != "0");
                                }
                            }
                        }
                    },
		            field_name: {
                        validators: {
                            callback: {
                                message: 'Please choose at least one firm.',
                                callback: function(value, validator, $field) {
                                    var options = validator.getFieldElements(''+field_name+'').val();
                                    //console.log(options);
                                    return (options != null
                                        && options.length >= 1);
                                }
                            }
                        }
                    }
		        }
            });
        });
		
		var validate_role = {
	        //excluded: [':disabled', ':hidden', ':not(:visible)'],
	        row: '.form-group',
	        validators: {
	            callback: {
	                message: 'The role is required.',
	                callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('role').val();
                        return (options != null && options != "0");
                    }
	            }
	        }
	    },
	    validate_department = {
	        //excluded: [':disabled', ':hidden', ':not(:visible)'],
	        row: '.form-group',
	        validators: {
	            callback: {
	                message: 'The department is required.',
	                callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('department').val();
                        return (options != null && options != "0");
                    }
	            }
	        }
	    },
	    selected_firm = {
	        //excluded: [':disabled', ':hidden', ':not(:visible)'],
	        row: '.form-group',
	        validators: {
	            callback: {
	                message: 'Please choose at least one firm.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements("selected_firm[]").val();
                        return (options != null
                            && options.length >= 1);
                    }
	            }
	        }
	    };

	    $.ajax({
	        type: "GET",
	        url: "<?php echo site_url('auth/get_department'); ?>",
	        dataType: "json",
	        async: false,
	        success: function(data){
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    var str = <?php echo json_encode($user->department_id) ?>;
						var temp = new Array();
						if(str)
						{
							// this will return an array with strings "1", "2", etc.
							temp = str.split(",");
							
							for($k = 0; $k < temp.length; $k++)
							{
								if(key == temp[$k])
		                    	{
		                    		option.attr('selected', 'selected');
		                    	}
							}
						}
	                    $('#department').append(option);
	                });
	                $('#update_profile').bootstrapValidator('addField', 'department', validate_department);
	            }
	            else{
	                alert(data.msg);
	            }
	        }               
	    });

	    $.ajax({
	        type: "GET",
	        url: "<?php echo site_url('auth/get_group'); ?>",
	        dataType: "json",
	        async: false,
	        success: function(data){
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    var str = <?php echo json_encode($user->group_id) ?>;
						var temp = new Array();
						if(str)
						{
							// this will return an array with strings "1", "2", etc.
							temp = str.split(",");
							
							for($k = 0; $k < temp.length; $k++)
							{
								if(key == temp[$k])
		                    	{
		                    		option.attr('selected', 'selected');
		                    	}
							}
						}
	                    $('#role').append(option);
	                });
	                $('#update_profile').bootstrapValidator('addField', 'role', validate_role);
	            }
	            else{
	                alert(data.msg);
	            }
	        }               
	    });

	    $.ajax({
            type: "GET",
            url: "<?php echo site_url('auth/get_manager_name'); ?>",
            async: false,
            success: function(response){
                response = JSON.parse(response);
                if(response.result.length != 0)
                {
                    if($('#role').val() == 3 || $('#role').val() == 6)
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );

                        $a = "";
                        $a = '<div class="col-sm-2 manager_in_charge_div_chill" style="margin-left: -15px; margin-right: 5px;"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

                        $(".manager_in_charge_div").append($a); 
                        $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

                        $.each(response.result, function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            console.log($('#role').val());
                            if(manager_in_charge_value != null && key == manager_in_charge_value)
                            {
                                option.attr('selected', 'selected');
                            }
                            
                            $("#manager_in_charge").append(option);
                        });
                    }
                    else
                    {
                        $(".manager_in_charge_div_chill").remove(); 
                        $(".manager_in_charge_div").removeAttr( 'style' );
                    }
                }
            }
        });

        $(document).on('change',"#role",function() {
	        $role = $("#role option:selected").text();
	        $role_value = $("#role option:selected").val();
	        $.ajax({
	            type: "GET",
	            url: "<?php echo site_url('auth/get_manager_name'); ?>",
	            success: function(response){
	                response = JSON.parse(response)
	                //console.log(response.result);
	                if(response.result.length != 0)
	                {
	                    if($role_value == 3 || $role_value == 6)
	                    {
	                        $(".manager_in_charge_div_chill").remove(); 
	                        $(".manager_in_charge_div").removeAttr( 'style' );

	                        $a = "";
	                        $a = '<div class="col-sm-2 manager_in_charge_div_chill" style="margin-left: -15px; margin-right: 5px;"><label for="manager_in_charge" class="profile_label">Manager In Charge:</label></div><div class="col-sm-10 manager_in_charge_div_chill"><select class="form-control" style="text-align:right;width: 300px;" name="manager_in_charge" id="manager_in_charge"></select></div>';

	                        $(".manager_in_charge_div").append($a); 
	                        $(".manager_in_charge_div").attr("style","margin-bottom: 65px;");

	                        $.each(response.result, function(key, val) {
	                            var option = $('<option />');
	                            option.attr('value', key).text(val);

	                            // if(claim_below_info[t]["type_id"] != null && key == claim_below_info[t]["type_id"])
	                            // {
	                            //     option.attr('selected', 'selected');
	                            // }
	                            
	                            $("#manager_in_charge").append(option);
	                        });
	                    }
	                    else
	                    {
	                        $(".manager_in_charge_div_chill").remove(); 
	                        $(".manager_in_charge_div").removeAttr( 'style' );
	                    }
	                }
	            }
	        });
	    });
		

		if(part == "access_client")
        {
        	$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('auth/get_client'); ?>",
		        dataType: "json",
		        async: false,
		        success: function(data){
		            if(data.tp == 1){
		                $.each(data['result'], function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);
		                    var str = <?php echo json_encode(isset($user->client_id)) ?>;
							
							var temp = new Array();
							if(str)
							{
								// this will return an array with strings "1", "2", etc.
								temp = str.split(",");
								
								for($k = 0; $k < temp.length; $k++)
								{
									if(key == temp[$k])
			                    	{
			                    		option.attr('selected', 'selected');
			                    	}
								}
							}
		                    $('#selected_client').append(option);
		                });
		            }
		            else{
		                alert(data.msg);
		            }
		        }               
		    });
        }
        else
        {
	    	$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('auth/get_firm'); ?>",
		        dataType: "json",
		        async: false,
		        //data: {"currency": client_charges[i]["currency"]},
		        success: function(data){
		            //$("#form"+$count_charges+" #currency"+$count_charges+"").find("option:eq(0)").html("Select Currency");
		            if(data.tp == 1){
		                $.each(data['result'], function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);


		                    var str = <?php echo json_encode($user->firm_id) ?>;
							var temp = new Array();
							if(str)
							{
								// this will return an array with strings "1", "2", etc.
								temp = str.split(",");
								
								for($k = 0; $k < temp.length; $k++)
								{
									if(key == temp[$k])
			                    	{
			                    		option.attr('selected', 'selected');
			                    	}
								}
							}
		                    $('#selected_firm').append(option);
		                });
		                $('#update_profile').bootstrapValidator('addField', "selected_firm[]", selected_firm);
		            }
		            else{
		                alert(data.msg);
		            }
		        }               
		    });
	    }


        toastr.options = {

		  "positionClass": "toast-bottom-right"

		}

        
        $(document).on("submit",function(e){
	        e.preventDefault();

	        var $form = $(e.target);
        
	        // and the FormValidation instance
	        var fv = $form.data('bootstrapValidator');
	        console.log($form);
	        // Get the first invalid field
	        var $invalidFields = fv.getInvalidFields().eq(0);
	        // Get the tab that contains the first invalid field
	        var $tabPane     = $invalidFields.parents();
	        var valid_setup = fv.isValidContainer($tabPane);

	        if(valid_setup)
	        {
		        if(part == "access_client")
	            {
		        	var link = 'auth/edit_user/'+ <?php echo json_encode($user->id);?> + "/access_client";
		        }
		        else
		        {
		        	var link = 'auth/edit_user/'+ <?php echo json_encode($user->id);?>;
		        }

		        $('#loadingmessage').show();
		        //console.log(link);
		        $.ajax({ //Upload common input
	                type: "POST",
	                url: base_url + link,
	                data: $("#update_profile").serialize(),
	                dataType: 'json',
	                success: function (response) {
	                	console.log(response);
	                	$('#loadingmessage').hide();
	                	//console.log(response.client_billing["client_billing_data"]);
	                    //if (response.Status === 1) {
	                    	toastr.success(response.message, response.title);

	                    	if(response.direct == "homepage")
	                    	{
	                    		window.location.href = base_url;
	                    	}
	                    	else if(response.direct == "user_page")
	                    	{
	                    		window.location.href = base_url + "auth/users/";
	                    	}
	                    	else if(response.direct == "user_client_page")
	                    	{
	                    		window.location.href = base_url + "auth/client/";
	                    	}
	                    	
	                    //}
	                }
	            });
		    }
	    });

		$(document).on('click',"#update",function(e){
		    $("#update_profile").submit();
		});

	    $(document).on('click',"#change_password_button",function(e){
	        e.preventDefault();
	        
	        if(part == "access_client")
            {
	        	var link = 'auth/profile_change_password/access_client';
	        }
	        else
	        {
	        	var link = 'auth/profile_change_password';
	        }
	        $('#loadingmessage').show();
	        $.ajax({ //Upload common input
                url: base_url + link,
                type: "POST",
                data: $("#change-password-form").serialize(),
                dataType: 'json',
                success: function (response) {
                	$('#loadingmessage').hide();
                	//console.log(response);
                    //if (response.Status === 1) {
                    	
                    	if(response != null)
                    	{
	                    	if(response.Status == 2)
	                    	{
	                    		toastr.success(response.message, response.title);
	                    		window.location.href = base_url;
	                    		//window.location.href = base_url + "auth/profile/" +response.user_id+ "/#cpassword";
	                    	}
	                    	else if(response.Status == 1)
	                    	{
	                    		toastr.success(response.message, response.title);
	                    		window.location.href = base_url + "auth/users/";
	                    	}
	                    	else if(response.Status == 3)
	                    	{
	                    		toastr.error(response.message, response.title);
	                    	}
	                    	else if(response.Status == 4)
	                    	{
	                    		toastr.success(response.message, response.title);
	                    		window.location.href = base_url + "auth/client/";
	                    	}
	                    }
                    	
                    //}
                }
            });
	    });

  //       $(document).on('click',"#update",function(e){
  //       	 e.preventDefault();
		// 	$("#update_profile").submit();
		// });

		// $(document).on('click',"#change_password_button",function(e){
		// 	$("#change-password-form").submit();
		// });
    </script>