$(".print_icon").hide();
$(".access_icon").hide();

$(".addline_icon").addClass("disable");
document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

$(".orientation_icon").addClass("disable");
document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

$(".textbox_icon").addClass("disable");
document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(".delete_icon").addClass("disable");
document.getElementById("delete_icon").src = base_url + "img/delete-disable.png";


$(document).ready(function () {
	$('.select2').select2();
});



if(benchmark_value['equity_benchmark'] < 0)
{
	$('.benchmark option:nth-child(6)').prop("disabled", true);
}

if(benchmark_value['profit_before_tax_c'] < 0)
{
	$('.benchmark option:nth-child(4)').prop("disabled", true);
}

$(document).on('change',".benchmark",function(e){
	//console.log($(this).parent());
	var dropdown = $(this);
	var selected_val= dropdown.find("option:selected").val();
	
	switch(selected_val) {
	  	case "1":
	    	$('#initial_benchmark_val').html(format_value(benchmark_value['revenue_benchmark']));
	    	$('.initial_benchmark_input').val(benchmark_value['revenue_benchmark']);
	    	$('#final_benchmark_val').html(format_value(benchmark_value['revenue_benchmark_adjusted']));
	    	$('.final_benchmark_input').val(benchmark_value['revenue_benchmark_adjusted']);
	    	break;
	  	case "2":
	    	$('#initial_benchmark_val').html(format_value(benchmark_value['asset_benchmark']));
	    	$('.initial_benchmark_input').val(benchmark_value['asset_benchmark']);
	    	$('#final_benchmark_val').html(format_value(benchmark_value['asset_benchmark_adjusted']));
	    	$('.final_benchmark_input').val(benchmark_value['asset_benchmark_adjusted']);
	   		break;
	   	case "3":
	    	$('#initial_benchmark_val').html(format_value(benchmark_value['profit_before_tax_c']));
	    	$('.initial_benchmark_input').val(benchmark_value['profit_before_tax_c']);
	    	$('#final_benchmark_val').html(format_value(benchmark_value['profit_before_tax_c_adjusted']));
	    	$('.final_benchmark_input').val(benchmark_value['profit_before_tax_c_adjusted']);
	   		break;
	   	case "4":
	    	$('#initial_benchmark_val').html(format_value(benchmark_value['expenses_benchmark']));
	    	$('.initial_benchmark_input').val(benchmark_value['expenses_benchmark']);
	    	$('#final_benchmark_val').html(format_value(benchmark_value['expenses_benchmark_adjusted']));
	    	$('.final_benchmark_input').val(benchmark_value['expenses_benchmark_adjusted']);
	   		break;
	   	case "5":
	    	$('#initial_benchmark_val').html(format_value(benchmark_value['equity_benchmark']));
	    	$('.initial_benchmark_input').val(benchmark_value['equity_benchmark']);
	    	$('#final_benchmark_val').html(format_value(benchmark_value['equity_benchmark_adjusted']));
	    	$('.final_benchmark_input').val(benchmark_value['equity_benchmark_adjusted']);
	   		break;
	   	default:
	   		$('#initial_benchmark_val').html("-");
	   		$('.initial_benchmark_input').val("");
	   		$('#final_benchmark_val').html("-");
	   		$('.final_benchmark_input').val("");
	}

	calculate_assessment();

});

$(document).ready(function () {

	$('.benchmark').trigger('change'); 

    $(document).on('change',".prior_period_materiality",function(e){
		$(this).val(parseFloat($(this).val()).toFixed(2));

	});

	$(document).on('change',".percent_used",function(e){
		calculate_assessment();

	});


});

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
   	var form = $('#materiality_form').serialize();

    $.ajax({
        type: 'post',
        url: save_materiality_url,
        data: form ,
        dataType: 'json',
        success: function (response) 
        {
        	$('#loadingMessage').hide();
        	$('.materiality_id').val(response.id);
        	
        }
    });

});

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();
   	// var form = $('#form_balance_sheet').serialize();
   	var form = $('#materiality_form').serialize();

    $.ajax({
        type: 'post',
        url: save_materiality_url,
        data: form ,
        dataType: 'json',
        success: function (response) 
        {
        	// console.log("saved");
        	// $('#loadingMessage').hide();
        	$('.materiality_id').val(response.id);
        	$.ajax({
		        type: 'post',
		        url: export_materiality_pdf_url,
		        dataType: 'json',
		        success: function (response) 
		        {
		        	$('#loadingMessage').hide();
		        	window.open(
		              response.link,
		              '_blank' // <- This is what makes it open in a new window.
		            );
		        }
		    });
		        	
        }
    });
    

});


function format_value(value)
{
	// value = Math.round((value + Number.EPSILON) * 100) / 100;
	// var parts = value.toString().split(".");
 //    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
 //    return parts.join(".");

    value = Number(parseFloat(value).toFixed(2)).toLocaleString('en', {
	    minimumFractionDigits: 2
	});

	return value;
}

function calculate_assessment()
{
	var percentage = $(".percent_used").val();
	var initial_benchmark_val = $('.initial_benchmark_input').val();
	var initial_assessment = 0.00;
	var final_benchmark_val = $('.final_benchmark_input').val();
	var final_assessment = 0.00;
	var trival_threshold = 0.00;

	// alert(initial_benchmark_val);

	if(percentage != "" && initial_benchmark_val != ""  && final_benchmark_val != "")
	{
		
		initial_assessment = (percentage/100) * (parseFloat(initial_benchmark_val));
		ori_initial_assessment = Math.ceil(initial_assessment);
		$('.initial_assessment').html(format_value(Math.ceil(ori_initial_assessment/1000)*1000));
		$('.initial_assessment_input').val(Math.ceil(ori_initial_assessment/1000)*1000);

		final_assessment = (percentage/100) * (parseFloat(final_benchmark_val));
		ori_final_assessment = Math.ceil(final_assessment);
		$('.final_assessment').html(format_value(Math.ceil(ori_final_assessment/1000)*1000));
		$('.final_assessment_input').val(Math.ceil(ori_final_assessment/1000)*1000);
	}

	if(initial_assessment != "")
	{
		trival_threshold = (5/100) * (parseFloat(initial_assessment));
		$('.trival_threshold').html(format_value(Math.ceil(trival_threshold/100)*100));
		$('.trival_threshold_input').val(Math.ceil(trival_threshold/100)*100);

	}


}

