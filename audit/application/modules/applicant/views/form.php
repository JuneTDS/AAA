<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="10;url=<?php echo base_url("logout"); ?>" /> -->

    <!-- <title><?= $page_title ?> - <?= $Settings->site_name ?></title> -->
    <title><?= $page_title ?> - PAYROLL SYSTEM</title>

    <!-- <link rel="shortcut icon" href="<?= $assets ?>images/ACT_payroll.ico"/> -->
    <!-- <link href="<?= $assets ?>styles/print.css" rel="stylesheet"/> -->
	
	<!-- <link rel="stylesheet" href="<?= $assets ?>/styles/token-input.css" type="text/css" /> -->
		
	<!-- <link rel="stylesheet" href="<?= $assets ?>js/bootstrap-datepicker/css/datepicker3.css" /> -->
	<!-- <link rel="stylesheet" href="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.css" /> -->
    <!-- <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script> -->
    <!-- <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script> -->

    <link href="<?= base_url() ?>application/css/theme-default.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>application/css/theme-custom.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>application/css/theme.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script>

    <script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
    <script src="<?= base_url() ?>node_modules/moment/min/moment.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/js/transition.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/js/collapse.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.js"></script>

    <script src="<?= base_url() ?>node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <noscript><style type="text/css">#loading { display: none; }</style></noscript>

   <!--  <?php if ($Settings->rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
        </script>
    <?php } ?> -->

    <!-- <script type="text/javascript">
		var $add_sale_price = 0;
        $(window).load(function () {
            $("#loading").fadeOut("slow");
        });
    </script> -->

	<!-- <?php
	$arr = get_defined_vars();
	// print_r($quote_items);

	?> -->
	<style>
		ul.menu_padding5 li{
			padding-left:15px;
		}
	</style>
		
	<!-- Basic -->
	<meta charset="UTF-8">

	<!-- <title>Acumen Cognitive Technology Pte Ltd</title> -->
	<meta name="keywords" content="Acumen Cognitive Technology Pte Ltd" />
	<meta name="description" content="Corporate Secretary System">
	<meta name="author" content="Graphica">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css" />

	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.css" />
	<!-- <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" /> -->
	<!-- <link rel="stylesheet" href="node_modules/bootstrap-datepicker/css/datepicker3.css" /> -->

	<!-- Specific Page Vendor CSS -->
	<!-- <link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" /> -->
	<!-- <link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" /> -->
	<!-- <link rel="stylesheet" href="assets/vendor/morris/morris.css" />
	<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.css" />
	<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.print.css" media="print" />
 -->
	<!-- Chosen plugin for dropdown list with search -->
	<!-- <link rel="stylesheet" href="node_modules/chosen_v1.8.7/chosen.css" /> -->	
    <link rel="stylesheet" href="<?= base_url() ?>node_modules/chosen_v1.8.7/chosen.css" />
    <script src="<?= base_url() ?>node_modules/chosen_v1.8.7/chosen.jquery.js"></script>

    <!-- File input (Drag and drop) -->
    <link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
    <script src="<?= base_url() ?>application/js/fileinput.js"></script>

    <!-- Toastr plugin to pop out message -->
    <link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
    <script src="<?= base_url() ?>application/js/toastr.min.js"></script>

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?= base_url() ?>application/css/theme.css" />
    <link rel="stylesheet" href="<?= base_url() ?>application/css/modules/applicant/form.css" />

	<!-- Skin CSS -->
	<!-- <link rel="stylesheet" href="application/stylesheets/skins/default.css" /> -->

	<!-- Theme Custom CSS -->
	<!-- <link rel="stylesheet" href="assets/stylesheets/theme-custom.css"> -->

	<!-- Head Libs -->
	<!-- <script src="assets/vendor/modernizr/modernizr.js"></script> -->
</head>

<!-- Custom js files -->
<script src="<?= base_url() ?>application/js/custom/applicant_profile_image.js"></script>

<body>
	<section class="body">
        <!-- <?php echo json_encode($applicant); ?> -->
		<div>
			<section role="main" class="content-body" style="margin-left:0; display: inherit;">
				<header class="page-header" style="background-color:#154069;">
					<h2>Interview - Applicant's Detail</h2>
				</header>

				<div style="margin-top: 30px;">
				  <!--   <?php echo $breadcrumbs;?> -->
				</div>

                <div class="box" style="margin-bottom: 30px;">
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- <form id ="applicant_info" method="post" enctype="multipart/form-data"> -->
                                <?php echo form_open_multipart('Applicant/save_applicant', array('id' => 'applicant_info', 'enctype' => "multipart/form-data")); ?>
                                <input type="hidden" class="form-control" id="applicant_id" name="applicant_id" value="<?=$applicant_id ?>"/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                        	<div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label></label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                
                                                        <div class="profile">
                                                            <div class="photo">
                                                                <input type="file" accept="image/*" name="applicant_pic">
                                                                <div class="photo__helper">
                                                                    <div class="photo__frame photo__frame--circle">
                                                                        <canvas class="photo__canvas"></canvas>
                                                                        <div class="message is-empty">
                                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                                        </div>
                                                                        <div class="message is-loading">
                                                                            <i class="fa fa-2x fa-spin fa-spinner"></i>
                                                                        </div>
                                                                        <div class="message is-dragover">
                                                                            <i class="fa fa-2x fa-cloud-upload"></i>
                                                                            <p>Drop your photo</p>
                                                                        </div>
                                                                        <div class="message is-wrong-file-type">
                                                                            <p>Only images allowed.</p>
                                                                            <p class="message--desktop">Drop your photo here or browse your computer.</p>
                                                                            <p class="message--mobile">Tap here to select your picture.</p>
                                                                        </div>
                                                                        <div class="message is-wrong-image-size">
                                                                            <p>Your photo must be larger than 350px.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="photo__options hide">
                                                                    <div class="photo__zoom">
                                                                        <!-- <input type="range" class="zoom-handler"> -->
                                                                    </div><a href="javascript:;" class="remove"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                                <div class="okBtn" style="display: none;">
                                                                    <hr>
                                                                        <button type="button" id="previewBtn">OK</button>
                                                                    <hr>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="showImage" style="display: none; width:300px; text-align: center;">
                                                            <div>
                                                                <img src="<?=$applicant->pic ?>" alt="" class="preview">
                                                                <input type="hidden" id="applicant_pic" name="applicant_preview_pic" value="<?=$applicant->pic ?>" />
                                                            </div>
                                                            <div>
                                                                <a id="editProfilePicBtn" style="cursor: pointer;">Edit</a>
                                                                <a id="removeProfilePicBtn" style="cursor: pointer; color:red; display: none">Remove</a>
                                                            </div>
                                                        </div>
                		                            </div>


                		                        </div>
                		                    </div>
                                        	<div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Position applied :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_position" name="applicant_position" value="<?=$applicant->position?>" style="width: 400px;" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Name :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_name" name="applicant_name" value="<?=$applicant->name?>" style="width: 400px;" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Email :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                            <input type="email" class="form-control" id="applicant_email" name="applicant_email" value="<?=$applicant->email?>" style="width: 400px;" placeholder="eg. examples@gmail.com" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Phone no :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;" >
                                                        	<input type="text" class="form-control" id="applicant_phoneno" name="applicant_phoneno" value="<?=$applicant->phoneno?>" style="width: 400px;" required/>
                                                    	</div>
                                                    <div id="form_telephone"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>IC/Passport no. :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;">
                                                            <input type="text" class="form-control" id="applicant_ic_passport_no" name="applicant_ic_passport_no" value="<?=$applicant->ic_passport_no?>" style="width: 400px;" required/>
                                                        </div>
                                                        <div id="form_fax"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Nationality :</label>
                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 20%;" >
                                                        	<?php
                												echo form_dropdown('applicant_nationality', $nationality_list, $applicant->nationality_id, 'class="nationality-select" style="width:150%;" required');
                											?>
                                                        </div>
                                                        <div id="form_url"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Address :</label>

                                                    </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 60%;" >
                                                            <?php 
                                                                echo '<textarea class="form-control" rows="5" id="applicant_address" name="applicant_address">'.$applicant->address.'</textarea>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="form-group">
                                                <div style="width: 100%;">
                                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                                        <label>Address: </label>
                                                    </div>
                                                	<div style="width: 65%;float:left;margin-bottom:5px;">
                                                    	<div style="width: 100%;">
                                                            <div style="width: 25%;float:left;margin-right: 20px;">
                                                                <label>Postal Code :</label>
                                                            </div>
                                                            <div style="width: 65%;float:left;margin-bottom:5px;">
                                                                <div class="input-group" style="width: 20%;">
                                                                    <input type="text" class="form-control" id="applicant_postal_code" name="applicant_postal_code" value="<?=$applicant->postal_code?>" maxlength="6">
                                                                </div>
                                                                <div id="form_postal_code"></div>
                                                            </div>
                                                        </div>

                                                        <div style="margin-bottom:5px;">
                                                            <div style="width: 25%;float:left;margin-right: 20px;">
                                                                <label>Street Name :</label>
                                                            </div>
                                                            <div style="width: 71%;float:left;margin-bottom:5px;">
                                                                <div class="input-group" style="width: 100%;" >
                                                                    <input type="text" class="form-control" id="applicant_street_name" name="applicant_street_name" value="<?=$applicant->street_name?>">
                                                                </div>
                                                                <div id="form_street_name"></div>
                                                            </div>
                                                        </div>

                                                        <div style="margin-bottom:5px;">
                                                            <label style="width: 25%;float:left;margin-right: 20px;">Building Name :</label>
                                                            <div class="input-group" style="width: 71%;" >
                                                                <input style="width: 100%;" type="text" class="form-control" id="applicant_building_name" name="applicant_building_name" value="<?=$applicant->building_name?>">
                                                                <?php echo form_error('building_name','<span class="help-block">*','</span>'); ?>
                                                            </div>
                                                        </div>

                                                        <div style="margin-bottom:2px;">
                                                            <div style="width: 25%;">
                                                            <label style="width: 100%;float:left;margin-right: 20px;">Unit No :</label>
                                                        </div>
                                                            <div style="width: 75%;" > 
                                                            <div class="input-group" style="width: 10%;display: inline-block">
                                                                <input style="width: 100%; margin-right: 10px;" maxlength="3" type="text" class="form-control" id="applicant_unit_no1" name="applicant_unit_no1" value="<?=$applicant->unit_no_floor?>" placeholder="#00">
                                                                <?php echo form_error('unit_no1','<span class="help-block">*','</span>'); ?>
                                                            </div>
                                                            <div class="input-group" style="width: 20%;display: inline-block" >
                                                                <input style="width: 100%;" maxlength="10" type="text" class="form-control" id="applicant_unit_no2" name="applicant_unit_no2" value="<?=$applicant->unit_no?>">
                                                                
                                                                <?php echo form_error('unit_no2','<span class="help-block">*','</span>'); ?>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->

                		                    <div class="form-group">
                		                    	<div style="width: 100%;">
                			                    	<div style="width: 25%;float:left;margin-right: 20px;">
                			                            <label>Date of birth:</label>
                			                        </div>
                			                    	<div style="width: 30%;float: left;">
                			                            <div class="input-group date" data-provide="datepicker">
                										    <input type="text" class="form-control" id="applicant_DOB" name="applicant_DOB" value="<?=($applicant->dob!='0000-00-00 00:00:00')?$applicant->dob: '' ?>" required>
                										    <div class="input-group-addon">
                										        <span class="glyphicon glyphicon-calendar"></span>
                										    </div>
                										</div>
                			                        </div>
                			                    </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Gender: </label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;" >
                		                                	<?php
                                                                echo form_dropdown('applicant_gender', $gender, $applicant->gender, 'required');
                                                            ?>
                		                                </div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Education :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                            	<div class="input-group" style="width: 100%;">
                		                            		<a class="btn btn_blue" onclick="add_education()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Education</label>
                		                            		</a>
                                                        </div>
                                                        <div id="education_section" class="wrap input-group"></div>
                		                            </div>
                		                        </div>
                		                    </div>
                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Experience :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;">
                		                            		<a class="btn btn_blue" onclick="add_experience()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Experience</label>
                		                            		</a>
                                                        </div>
                                                        <div id="experience_section" class="wrap input-group"></div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Language :</label>
                		                            </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                    		                            <div class="col-md-12">
                                                            <div class="input-group" style="width: 100%;">
                                                                <label>Proficiency level: 0 - Poor, 10 - Excellent</label>
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Languages</th>
                                                                            <th>Spoken</th>
                                                                            <th>Written</th>
                                                                            <th>
                                                                                <span class="glyphicon glyphicon-plus-sign blue_addBtn" onclick="add_language_tr()"></span>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="language_tr"></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Skills :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 20%;">
                                                        	<input type="text" class="form-control" id="applicant_skills" name="applicant_skills" value="<?=$applicant->skills?>" style="width: 400px;"/>
                                                        </div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Professional membership (if any) :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;">
                		                            		<a class="btn btn_blue" onclick="add_professional()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 3%"></span>
                                                                <label class="addBtn_title">Add Professional Membership</label>
                		                            		</a>
                                                        </div>
                                                        <div id="professional_section" class="wrap input-group"></div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Expected Salary :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;">
                		                                    <input type="number" class="form-control" id="applicant_expected_salary" name="applicant_expected_salary" value="<?=$applicant->expected_salary?>" style="width: 400px;"/>
                		                                </div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Last drawn salary :</label>

                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;" >
                		                                    <input type="number" class="form-control" id="applicant_last_drawn_salary" name="applicant_last_drawn_salary" value="<?=$applicant->last_drawn_salary?>" style="width: 400px;"/>
                		                                </div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Referral :</label>
                		                            </div>
                                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 100%;">
                                                            <a class="btn btn_blue" onclick="add_referral()">
                                                                <span class="glyphicon glyphicon-plus-sign" style="margin-right: 5%"></span>
                                                                <label class="addBtn_title">Add Referral</label>
                                                            </a>
                                                        </div>
                                                        <div id="referral_section" class="wrap input-group"></div>
                                                    </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Upload Resume :</label>
                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                		                                <div class="input-group" style="width: 100%;">
                		                                    <div class="file-loading">
                		                                        <input type="file" id="applicant_resume" class="file" name="applicant_resume" data-min-file-count="0" accept="pdf">
                		                                    </div>
                		                                </div>
                		                            </div>
                		                        </div>
                		                    </div>

                		                    <div class="form-group">
                		                        <div style="width: 100%;">
                		                            <div style="width: 25%;float:left;margin-right: 20px;">
                		                                <label>Tell us a little bit about yourself :</label>

                		                            </div>
                		                            <div style="width: 65%;float:left;margin-bottom:5px;">
                                                        <?php 
                                                            echo '<textarea class="form-control" rows="5" id="applicant_about" name="applicant_about">'.$applicant->about.'</textarea>';
                                                        ?>
                		                            </div>
                		                        </div>
                		                    </div>
                		                </div>
                		                <div class="col-md-12 text-right" style="margin-top: 10px;">
                                            <button class="btn btn_blue" type="submit">Save</button>
                		                    <!-- <?php echo form_submit('save_applicant', 'Save', 'class="btn btn_blue"'); ?> -->
                                            <a href="applicant/" class="btn btn_cancel">Cancel</a>
                		                </div>

                                <!-- <?php echo form_close(); ?> -->
                                    </div>
                                </div>

                                </form>
                            </div>
                    </div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript" charset="utf-8">
    var count_edu     = 0;
    var count_exp     = 0;
    var count_pro     = 0;
    var count_ref     = 0;
    var count_lang_tr = 0;

    var education    = <?php echo json_encode($education); ?>;
    var experience   = <?php echo json_encode($experience); ?>;
    var professional = <?php echo json_encode($professional); ?>;
    var referral     = <?php echo json_encode($referral); ?>;
    var language     = <?php echo json_encode($language); ?>;

    var delete_edu  = [];
    var delete_exp  = [];
    var delete_pro  = [];
    var delete_ref  = [];
    var delete_lang = [];

    // console.log(language);

    if(<?php echo !empty($applicant->pic)?1:0 ?>){
        $('.showImage').show();
        $('.photo').hide();

        $('#editProfilePicBtn').hide(); // hide edit button.
        $('#removeProfilePicBtn').show(); // show remove button.
    }

    $.ajaxSetup({async: false}); 

    for(var index in language){
        add_language_tr(language[index]);
    }

    for(var index in education){
        add_education(education[index]);
    }

    for(var index in experience){
        add_experience(experience[index]);
    }

    for(var index in professional){
        add_professional(professional[index]);
    }

    for(var index in referral){
        add_referral(referral[index]);
    }

    $.ajaxSetup({async: true}); 

	$(".nationality-select").chosen({no_results_text: "Oops, nothing found!"});

	$('#dateOfBirth').datepicker();

	function add_education(){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

		$.post('applicant/education_partial', { 'count': count_edu }, function(data, status){
			$('#education_section').prepend(data);

            count_edu++;
	    });
	}

    function add_education(content){
        // console.log('<?php echo base_url(); ?>' + "applicant/education_partial");

        $.post('applicant/education_partial', { 'count': count_edu, 'content': content }, function(data, status){
            $('#education_section').prepend(data);

            count_edu++;
        });
    }

	function cancel_education(element, edu_id){
		var education_form = $(element).closest(".education_detail");

        if(edu_id != undefined){
            delete_edu.push(edu_id);
        }

		education_form.remove();
	}

	function add_experience(){

		$.post("applicant/experience_partial", { 'count': count_exp }, function(data, status){
			$('#experience_section').prepend(data);

            count_exp++;
	    });

	}

    function add_experience(content){

        $.post("applicant/experience_partial", { 'count': count_exp, 'content': content }, function(data, status){
            $('#experience_section').prepend(data);

            count_exp++;
        });

    }

    function cancel_experience(element, exp_id){
        var education_form = $(element).closest(".experience_detail");

        if(exp_id != undefined){
            delete_exp.push(exp_id);
        }

        education_form.remove();
    }

    function add_language_tr(){
        // console.log('count_lang_tr', count_lang_tr);
        $.post("applicant/add_language_tr_partial", { 'count': count_lang_tr }, function(data, status){
            $('#language_tr').prepend(data);

            count_lang_tr++;
        });
    }

    function add_language_tr(content){
        // console.log('count_lang_tr', count_lang_tr);
        $.post("applicant/add_language_tr_partial", { 'count': count_lang_tr, 'content': content }, function(data, status){
            $('#language_tr').prepend(data);

            count_lang_tr++;
        });
    }

    function cancel_lang(element, lang_id){
        var lang_tr = $(element).closest(".lang_tr");

        if(lang_id != undefined){
            delete_lang.push(lang_id);
        }

        lang_tr.remove();
    }

    function add_professional(){
        $.post("applicant/professional_partial", { 'count': count_pro }, function(data, status){
            $('#professional_section').prepend(data);

            count_pro++;
        });
    }

    function add_professional(content){
        $.post("applicant/professional_partial", { 'count': count_pro, 'content': content }, function(data, status){
            $('#professional_section').prepend(data);

            count_pro++;
        });
    }

    function cancel_professional(element, pro_id){
        var referral_form = $(element).closest(".professional_detail");

        if(pro_id != undefined){
            delete_pro.push(pro_id);
        }

        referral_form.remove();
    }

    function add_referral(){
        $.post("applicant/referral_partial", { 'count': count_ref }, function(data, status){
            $('#referral_section').prepend(data);

            count_ref++;
        });
    }

    function add_referral(content){
        $.post("applicant/referral_partial", { 'count': count_ref, 'content':content }, function(data, status){
            $('#referral_section').prepend(data);

            count_ref++;
        });
    }

    function cancel_referral(element, ref_id){
        var referral_form = $(element).closest(".referral_detail");

        if(ref_id != undefined){
            delete_ref.push(ref_id);
        }

        referral_form.remove();
    }

    $("#applicant_info").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "applicant/save_applicant",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
                $.post("applicant/delete_data", { 'edu': delete_edu, 'exp': delete_exp, 'pro': delete_pro, 'ref': delete_ref, 'lang': delete_lang }, function(data, status){});

               // console.log($('#applicant_resume').fileinput('getFilesCount'));
               $('#applicant_resume').fileinput('upload');
           }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.
    });

/* image settings in js/custom/applicant_profile_image.js */

/* This section contains file input plugin settings */
var initialPreviewArray       = []; 
var initialPreviewConfigArray = []; 
var base_url                  = '<?php echo base_url() ?>';
var files                     = '<?php echo isset($applicant->uploaded_resume)?$applicant->uploaded_resume:''; ?>';

// console.log(base_url + "uploads/applicant_resume/" + files);

if(files != '')
{
    var url = base_url + "uploads/applicant_resume/";
    // console.log(url + files);

    initialPreviewArray.push( url + files );
    initialPreviewConfigArray.push({
        type: "pdf",
        caption: files,
        url: base_url + "applicant/delete_resume/" + '<?php echo isset($applicant->id)?$applicant->id:''; ?>',
        width: "120px",
        key: 0
    });
}

$("#applicant_resume").fileinput({
    theme: 'fa',
    uploadUrl: base_url + 'applicant/uploadFile/' + '<?php echo $applicant_id; ?>', // you must set a valid URL here else you will get an error
    uploadAsync: false,
    browseClass: "btn btn_blue",
    fileType: "any",
    required: false,
    showCaption: false,
    showUpload: false,
    showRemove: false,
    //showClose: false,
    autoReplace: true,
    overwriteInitial: true,
    maxFileCount: 1,
    fileActionSettings: {
                    showRemove: false,
                    showUpload: false,
                    showZoom: true,
                    showDrag: false
                },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    initialPreviewShowDelete: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + 'uploads/applicant_resume/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    allowedFileExtensions: ["pdf"],
    //deleteUrl: "/dot/personprofile/deleteFile",
    /*maxFileSize: 20000048,
    maxImageWidth: 1000,
    maxImageHeight: 1500,
    resizePreference: 'height',
    resizeImage: true,*/
    purifyHtml: true // this by default purifies HTML data for preview
    /*uploadExtraData: { 
        officer_id: $('input[name="officer_id"]').val() 
    }*/
    /*width:auto;height:auto;max-width:100%;max-height:100%;*/

}).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
    $("#loadingmessage").hide();

    alert("Successfully submitted.");

    window.location.href = base_url + "applicant/form/" + '<?php echo $applicant_id; ?>';
    toastr.success('Information Updated', 'Updated');
    // console.log(data);
}).on('fileuploaderror', function(event, data, msg) {
    $("#loadingmessage").hide();

    alert("Successfully submitted.");

    window.location.href = base_url + "applicant/form/" + '<?php echo $applicant_id; ?>';
    // toastr.error(msg);
    // alert(msg);
});

</script>
</body>

