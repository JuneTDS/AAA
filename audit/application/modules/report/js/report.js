 

	// (function( $ ) {
	// 	'use strict';

	// 	var datatableInit = function() {

	// 		var table1 = $('#datatable-report').DataTable({
				
	//             "order": [[ 0, 'asc' ]]
	// 		});

	// 	};

	// 	$(function() {
	// 		datatableInit();
	// 	});

	// }).apply( this, [ jQuery ]);
	$(document).on('click',".open_caf_report_list",function(){
	    open_caf_report_list(this);
	});

	function open_caf_report_list(element)
	{
		$("#caf_report_list").modal("show"); 
		var tr = $(element).parent().parent();
		var company_code = tr.find('.company_code').val();
		var company_name = tr.find('.company_name').val();

		$.ajax({
	       type: "POST",
	       url:  "report/get_caf_report_list",
	       data: '&company_code=' + company_code,
	       success: function(data)
	       {

	       		var caf_report_list = JSON.parse(data);

	       		// console.log(caf_report_list);

	       		$("#caf_report_list .panel-title").html("Current files - " + company_name);
	       		// console.log(caf_report_list);
	       		$("#caf_report_list_body").empty();


	       		for(var i=0; i<caf_report_list.length; i++)
	       		{
	       			var rowHtml = 	"<tr>"+
		           						  "<td style='width:50%;'><a href='javascript:generate_ml_report("+caf_report_list[i].id+")' class='pointer mb-sm mt-sm mr-sm'>"+ "Current File - " + moment(caf_report_list[i].FYE).format('DD MMMM YYYY') +"</a></td>"+
		           						  "<td style='width:50%;'>"+ caf_report_list[i].job +"</td>"+
		           					"</tr>";
	           		$("#caf_report_list_body").append(rowHtml);
	       		}

	       		if(caf_report_list.length == 0)
	       		{
	       			var rowHtml = 	"<tr>"+
		           						  "<td colspan='2' style='width:100%;text-align:center;'>No current file(s)</td>"+
		           					"</tr>";
	           		$("#caf_report_list_body").append(rowHtml);
	       		}

	       }
	   	});
		// var modal = $("#confirm_caf_modal");

		// $('#fin_period').find('option').not(':nth-child(1)').remove();

		// var cn = new Client();
	 //    cn.getFinPeriod(tr.find('.company_code').val());

		// console.log(tr);
		// modal.find('.panel-title').html(tr.find('.company_name').text()+'<br/><label class="control-label" style="font-size:13px;"><i>File bank confirmation to current audit file</i></label>');
	}

	

    function generate_ml_report(assignment_id)
    {
    	$('#loadingMessage').show();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
	      url		: "report/generate_ml_report",
	      type 		: "POST",
	      dataType	: 'json',
	      data 	: {"assignment_id": assignment_id},
	      beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	      success	: function (data) {

	        $('#loadingMessage').hide();

	        if(data.status)
	        {
	        	window.open(
		            data.pdf_link,
		            '_blank' // <- This is what makes it open in a new window.
		        );
	        }
	        else
	        {
	        	alert("Account category is not managed in selected current file!")
	        }
	        

	            // setTimeout(function(){ deletePDF(response.path); }, 5000);
	      }
	    })
    }

	// function add_setting_line()
	// {
	// 	var new_row = '<tr>'+
	// 					'<td>'+
	// 					 	'<div class="input-group" id="confirm_month" style="width: 80%;">'+
	// 	                    	'<span class="input-group-addon">'+
	// 	                    		'<i class="far fa-calendar-alt"></i>'+
	// 	                    	'</span>'+
	// 	                    	'<input type="text" class="confirm_month form-control" name="confirm_month" data-date-format="mm/yyyy" required value="">'+
	// 						'</div>'+
	// 					'</td>'+
	// 					'<td>'+
	// 						'<div class="input-group dropdown_pic_name" style="width: 80%;">'+
	// 							'<select id="pic_name" class="form-control pic_name" style="width: 100%;" name="pic_name" required>'+
	// 				                    '<option value="">Select PIC Name</option>'+
	// 				            '</select>'+
	// 				        '</div>'+
	// 					'</td>'+
	// 					'<td style="text-align:center;" width="15%"><button type="button" style="margin:4px;" class="btn btn_blue"  onclick=delete_confirm_setting(this)>Save</button></td>'+
	// 				   '</tr>';

	// 	$('#datatable-confirm-setting').prepend($(new_row));


	// $('.confirm_month').datepicker({
	//     autoclose: true,
	//     minViewMode: 1,
	//     format: 'MM yyyy'
	// });

	// $(function() {
	//     var cn = new Client();
	//     cn.getPicName();
	// });


	// }