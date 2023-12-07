<!DOCTYPE html>
	<html>
		<head>
			<link rel="shortcut icon" href="<?= base_url() ?>assets/logo/logo_1.ico" />

			<link href="<?= base_url() ?>application/css/theme-default.css" rel="stylesheet" type="text/css">
			<link href="<?= base_url() ?>application/css/theme-custom.css" rel="stylesheet" type="text/css">
			<link href="<?= base_url() ?>application/css/theme.css" rel="stylesheet" type="text/css">
			<link href="<?= base_url() ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
			
			<link href="<?= base_url() ?>node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
			<link href="<?= base_url() ?>node_modules/select2/dist/css/select2.css" rel="stylesheet"/>
			<link href="<?= base_url() ?>assets/vendor/font-awesome/css/all.css" rel="stylesheet" />
			
			<!-- <script src="<?= base_url() ?>node_modules/jquery/dist/jquery.min.js"></script> -->
			<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
			<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
			<!-- <script src="<?= base_url() ?>node_modules/moment-develop/min/moment.min.js"></script> -->
			<script src="<?= base_url() ?>node_modules/bootstrap/js/transition.js"></script>
			<script src="<?= base_url() ?>node_modules/bootstrap/js/collapse.js"></script>
			<script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
			<script src="<?= base_url() ?>node_modules/select2/dist/js/select2.js"></script>

			
			<!-- <script src="<?= base_url() ?>assets/vendor/modernizr/modernizr.js"></script> -->
			<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-toggle-master/css/bootstrap-toggle.css" />
			<script src="<?= base_url() ?>node_modules/bootstrap-toggle-master/js/bootstrap-toggle.js"></script> -->

			<title><?php echo ($page_title) ?></title>
		</head>

		<header class="main-header ">
			<div class="logo-container">
				<a href="" class="logo" style="padding:0px;">
					<?php if($logo != null) { ?>
						<img src="<?php echo base_url('../secretary/uploads/logo/'.$logo.'');?>" width=35 style="margin:5px" height=auto alt="" />
					<?php } ?>
				</a>
				<?php echo $firm_name?>
			</div>

			<div class="pull-right" style="margin-right: 6rem; z-index: 1100px;">
				<span class="separator"></span>

				<div id="userbox" class="userbox">
					<a data-toggle="dropdown" style="cursor:pointer;">
						<div class="profile-info" data-lock-name="<?= $this->session->userdata('first_name'); ?>" data-lock-email="admin@graphica.co.id">
							<span class="name"><?= $this->session->userdata('first_name'); ?></span>
							<span class="role"><?= $this->session->userdata('role'); ?></span>
						</div>

						<i class="fa custom-caret"></i>
					</a>

					<div class="dropdown-menu">
						<ul class="list-unstyled">
							<li class="divider"></li>
							<li id="header_our_firm"><a href="<?= site_url('firm/'); ?>">
								<i class="fa fa-user"></i> Our Firm</a></li>
							<li id="header_user_profile">
								<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fa fa-key" style="font-size: 1.5rem;"></i> User Profile</a>
							</li>
					 		<?php 
								if($Admin || $Manager) 
								{
									echo 
										'<li>
											<a href="'. base_url() . 'setting" class="btn-default">
												<i class="fas fa-cog"></i> Setting
											</a>
										</li>';
								}
							?>
							<li class="divider"></li>
							<li>
								<a href="<?= site_url('auth/logout'); ?>">
									<i class="fa fa-sign-out-alt"></i> 
									<!-- <?= lang('logout'); ?> -->
									Logout
								</a>
		                    </li>
						</ul>
					</div>
				</div>
				
				<span class="separator"></span>
			</div>
		</header>
		<header>
			<div class="page_sticky_header">
				<h2><?php if ($page_name != "") echo $page_name; else echo "Dashboard";?></h2>
		  		<nav>
					<ul class="notifications right-wrapper" style="margin-right:10px;">
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bars" style="font-size:14px"></i>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="content">
									<ul>
										<li id="header_dashboard">
											<a href="<?= base_url();?>welcome" class="btn-default">
												Dashboard
											</a>
										</li>
										<?php 
											if($Admin) 
											{
												// echo 
												// 	'<li>
												// 		<a href="'. base_url() . 'interview" class="btn-default">
												// 			Interview
												// 		</a>
												// 	</li>';
												// echo 
												// 	'<li>
												// 		<a href="'. base_url() . 'employee" class="btn-default">
												// 			Employee
												// 		</a>
												// 	</li>';
											}
										?>
									<!-- 	<li>
											<a href="<?= base_url();?>employee" class="btn-default">Employee</a>
										</li> -->
										<!-- <li>
											<a href="<?= base_url();?>payslip" class="btn-default">
												Payslip
											</a>
										</li>
										<li>
											<a href="<?= base_url();?>mc_claim" class="btn-default">
												MC & Claims
											</a>
										</li> -->
								<!-- 		<li>
											<a href="<?= base_url();?>assignment" class="btn-default">
												Assignment
											</a>
										</li> -->
										<li>
											<a href="<?= base_url();?>client" class="btn-default">Clients</a>
										</li>


										<li>
											<a href="<?= base_url();?>bank" class="btn-default">Bank Confirmations</a>
										</li>

										<li>
											<a href="<?= base_url();?>stocktake" class="btn-default">Stock take Reminder</a>
										</li>

							
										<li>
											<a href="<?= base_url();?>list_of_auditor" class="btn-default">Audit clearance</a>
										</li>

										<li>
											<a href="<?= base_url();?>engagement" class="btn-default">Engagement</a>
										</li>

										<li>
											<a href="<?= base_url();?>report" class="btn-default">Report</a>
										</li>
										<!-- <?php 
											if($Admin) 
											{
												echo 
												'<li>
													<a href="' . base_url() . 'block_holiday" class="btn-default">
														Block Holiday
													</a>
												</li>';
											}
										?> -->
										<!-- <li>
											<a href="<?= base_url();?>reimbursement" class="btn-default">
												Reimbursement
											</a>
										</li>
										<li>
											<a href="<?= base_url();?>timesheet" class="btn-default">
												Timesheet
											</a>
										</li> -->
									</ul>
								</div>
							</div>
						</li>
					</ul>
					<span class="separator" style="width:20px">&nbsp;</span>
				</nav>

			</div>
		</header>
		<body>
			<div class="inner-wrapper">

<script>
	createSticky($(".page_sticky_header"));

	function createSticky(sticky) {
		
		if (typeof sticky !== "undefined") {

			var	pos = sticky.offset().top,
					win = $(window);
				
			win.on("scroll", function() {
	    		win.scrollTop() >= pos ? sticky.addClass("sticky_header_fixed") : sticky.removeClass("sticky_header_fixed");      
			});			
		}
	}
</script>