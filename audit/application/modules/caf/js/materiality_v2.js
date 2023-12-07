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

var measurement_percentage_min = 0;
var measurement_percentage_max = 100;

// window.onbeforeunload = function (e) {
//     e = e || window.event;

//     // For IE and Firefox prior to version 4
//     if (e) {
//         e.returnValue = 'Sure?';
//     }

//     // For Safari
//     return 'Sure?';
// };


$(document).ready(function () {
	$('.select2').select2();
	if(materiality_data)
	{
		$('input:radio[name="engagement_risk"][value="'+materiality_data.engagement_risk+'"]').prop('checked',true);
		$('input:radio[name="engagement_risk"]').trigger('change');
		$('input:radio[name="benchmark"][value="'+materiality_data.benchmark+'"]').prop('checked',true);
		$('.relevant_materiality').trigger('change');
		$('input:radio[name="benchmark_source"][value="'+materiality_data.benchmark_source+'"]').prop('checked',true);
		$('input:radio[name="benchmark_reason"][value="'+materiality_data.benchmark_reason+'"]').prop('checked',true);
		if(materiality_data.editable_materiality)
		{

			$('.editable_materiality').val(parseFloat(materiality_data.editable_materiality));
		}

	}
});



if(benchmark_value['equity_benchmark'] < 0)
{
	$('.benchmark option:nth-child(6)').prop("disabled", true);
}

if(benchmark_value['profit_before_tax_c'] < 0)
{
	$('.benchmark option:nth-child(4)').prop("disabled", true);
}

$(document).on('change',".engagement_risk",function(e){
	//console.log($(this).parent());
	var radio = $(this);

	if(radio.is(":checked"))
	{
		var selected_val= $(radio).val();

		console.log(selected_val);

		// $('[name="materiality_percentage"]').removeAttr('checked');
		
		$('input:radio[name="performance_materiality_percentage"][value="'+selected_val+'"]').prop('checked',true);
		switch(selected_val) {
			case "1":
				materiality_percentage = 85;
				break;
			case "2":
				materiality_percentage = 80;
				break;
			case "3":
				materiality_percentage = 70;
				break;
		   	default:
		   		materiality_percentage = "";
		}

		$('.materiality_percentage_calculate').html(materiality_percentage);

		calculate_assessment();
	}
});

$(document).on('change',".performance_materiality_percentage",function(e){
	//console.log($(this).parent());
	var radio = $(this);

	if(radio.is(":checked"))
	{
		var selected_val= radio.val();
		var materiality_percentage = "";

		// $('[name="materiality_percentage"]').removeAttr('checked');
		
		$('input:radio[name="engagement_risk"][value="'+selected_val+'"]').prop('checked',true);
		switch(selected_val) {
			case "1":
				materiality_percentage = 85;
				break;
			case "2":
				materiality_percentage = 80;
				break;
			case "3":
				materiality_percentage = 70;
				break;
		   	default:
		   		materiality_percentage = "";
		}

		$('.materiality_percentage_calculate').html(materiality_percentage);

		calculate_assessment();
	}

});

$(document).on('change',".initial_benchmark_input",function(e){
	//console.log($(this).parent());
	var input = $(this);
	var initial_benchmark_val = input.val();


	// $('[name="materiality_percentage"]').removeAttr('checked');
	
	$('#initial_benchmark_val').html(format_value(initial_benchmark_val));

	calculate_assessment();

});

$(document).on('change',".ctt_basis_input",function(e){
	var input = $(this);
	var ctt_basis_val = input.val();
	
	$('.ctt_basis').html(format_value(ctt_basis_val));

	calculate_assessment();
});

$(document).on('change',".relevant_materiality",function(e){
	
	var radio = $(this);

	if(radio.is(":checked"))
	{
		var selected_val= radio.val();

		check_is_revenue(selected_val);

		switch(selected_val) {
			case "1":
			case "2":
			case "3":
		  	case "4":
		    	$('#initial_benchmark_val').html(format_value(benchmark_value['profit_before_tax_c']));
		    	$('.initial_benchmark_input').val(benchmark_value['profit_before_tax_c']);
		    	break;
			case "5":
		  	case "6":
		    	$('#initial_benchmark_val').html(format_value(benchmark_value['revenue_benchmark']));
		    	$('.initial_benchmark_input').val(benchmark_value['revenue_benchmark']);
		    	break;
		  	case "7":
		    	$('#initial_benchmark_val').html(format_value(benchmark_value['asset_benchmark']));
		    	$('.initial_benchmark_input').val(benchmark_value['asset_benchmark']);
		   		break;
		   	case "9":
		    	$('#initial_benchmark_val').html(format_value(benchmark_value['expenses_benchmark']));
		    	$('.initial_benchmark_input').val(benchmark_value['expenses_benchmark']);
		   		break;
		   	case "8":
		    	$('#initial_benchmark_val').html(format_value(benchmark_value['equity_benchmark']));
		    	$('.initial_benchmark_input').val(benchmark_value['equity_benchmark']);
		   		break;
		   	default:
		   		$('#initial_benchmark_val').html("-");
		   		$('.initial_benchmark_input').val("");
		}

		switch(selected_val) {
			case "1":
			case "3":
				measurement_percentage_min = 5;
				measurement_percentage_max = 15;
				break;
		  	case "2":
		  	case "4":
		    	measurement_percentage_min = 5;
				measurement_percentage_max = 10;
		    	break;
		    case "5":
		  	case "8":
		  	case "9":
		    	measurement_percentage_min = 1;
				measurement_percentage_max = 10;
		    	break;
		    case "6":
		    	measurement_percentage_min = 1;
				measurement_percentage_max = 5;
		    	break;
		    case "7":
		    	measurement_percentage_min = 0.5;
				measurement_percentage_max = 5;
		    	break;
		   	default:

		}

		$(".measurement_percentage").attr({
	       "max" : measurement_percentage_max,   
	       "min" : measurement_percentage_min 
	    });

		calculate_assessment();
	}

});

$(document).on('change',".materiality_measurement_percentage",function(e){
    // Check correct, else revert back to old value.
    if (!$(this).val() || ($(this).val() <= measurement_percentage_max && $(this).val() >= measurement_percentage_min))
      ;
    else
      $(this).val($(this).data("old"));

  	$(".measurement_percentage_calculate").html($(this).val()); 
  	$(".measurement_percentage_calculate").change();
 });

$(document).ready(function () {

	$('.benchmark').trigger('change'); 
	$('.materiality_measurement_percentage').trigger("change"); 
	$('.ctt_basis_input').trigger("change");
	

	// $(".measurement_percentage").onChange(function () {
	//     // Save old value.
	//     if (!$(this).val() || (parseInt($(this).val()) <= measurement_percentage_max && parseInt($(this).val()) >= measurement_percentage_min))
	//     $(this).data("old", $(this).val());
	//   });
	 

    $(document).on('change',".prior_period_materiality",function(e){
		$(this).val(parseFloat($(this).val()).toFixed(2));

	});

	$(document).on('change',".measurement_percentage_calculate",function(e){
		calculate_assessment();

	});


});

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
   	var form = $('#materiality_form').serialize();

    $.ajax({
        type: 'post',
        url: save_materiality_v2_url,
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
        url: save_materiality_v2_url,
        data: form ,
        dataType: 'json',
        success: function (response) 
        {
        	// console.log("saved");
        	// $('#loadingMessage').hide();
        	$('.materiality_id').val(response.id);
        	$.ajax({
		        type: 'post',
		        url: export_materiality_v2_pdf_url,
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

$(document).on('keyup',".editable_materiality",function(e){

    calculate_performance_materiality();
	calculate_ctt();
	$('.step1_materiality').html(format_value($(this).val()))
 });

 $(document).on('change',".editable_materiality",function(e){

    calculate_performance_materiality();
	calculate_ctt();
	$('.step1_materiality').html(format_value($(this).val()))
 });


function reset_materiality()
{
	var val =  parseInt(($(".materiality_rounding_calculate").text()).replace(new RegExp(",", "g"),''));
	$('.editable_materiality').val(val);
	$('.editable_materiality').trigger("change");
}


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
	var percentage = $(".materiality_measurement_percentage").val();
	var initial_benchmark_val = $('.initial_benchmark_input').val();
	var initial_assessment = 0.00;
	var trival_threshold = 0.00;

	// alert(initial_benchmark_val);

	if(percentage != "" && initial_benchmark_val != "")
	{
		
		initial_assessment = (percentage/100) * (parseFloat(initial_benchmark_val));
		$(".materiality_calculate").html(format_value(initial_assessment));
		ori_initial_assessment = Math.ceil(initial_assessment);
		var rounded_materiality = Math.ceil(ori_initial_assessment/1000)*1000;
		$('.materiality_rounding_calculate').html(format_value(rounded_materiality));
		$('.step1_materiality').html(format_value(rounded_materiality));

		// console.log($('.editable_materiality'));
		if(!materiality_data.editable_materiality)
		{
			$('[name="editable_materiality"]').val(parseFloat(rounded_materiality));
			
		}
		else{
			$('.step1_materiality').html(format_value(materiality_data.editable_materiality));

		}
		$('.initial_assessment_input').val(Math.ceil(ori_initial_assessment/1000)*1000);

	}
	else
	{
		$('.materiality_rounding_calculate').html("");
		$(".materiality_calculate").html("");
	}

	// if(initial_assessment != "")
	// {
	// 	trival_threshold = (5/100) * (parseFloat(initial_assessment));
	// 	$('.trival_threshold').html(format_value(Math.ceil(trival_threshold/100)*100));
	// 	$('.trival_threshold_input').val(Math.ceil(trival_threshold/100)*100);

	// }

	calculate_performance_materiality();
	calculate_ctt();

}

function calculate_performance_materiality()
{
	var percentage = parseInt($(".materiality_percentage_calculate").text());
	// var materiality_rounding = parseInt(($(".materiality_rounding_calculate").text()).replace(new RegExp(",", "g"),''));
	var materiality_rounding = $(".editable_materiality").val();
	
	// console.log(($(".materiality_rounding_calculate").text()).replace(new RegExp(",", "g"),''));
	var performance_materiality = 0.00;
	var performance_materiality_rounded = 0.00;

	if(percentage != "" && materiality_rounding != "")
	{
		
		performance_materiality = (percentage/100) * (parseFloat(materiality_rounding));
		$(".performance_materiality").html(format_value(performance_materiality));
		performance_materiality_rounded = Math.ceil(performance_materiality);
		$('.performance_materiality_rounding').html(format_value(Math.ceil(performance_materiality_rounded/1000)*1000));

	}


}

function calculate_ctt()
{
	var percentage = $(".ctt_basis_input").val();
	// var materiality_rounding = parseInt(($(".materiality_rounding_calculate").text()).replace(new RegExp(",", "g"),''));
	var materiality_rounding = $(".editable_materiality").val();
	
	var ctt = 0.00;
	var ctt_rounded = 0.00;

	if(percentage != "" && materiality_rounding != "")
	{
		
		ctt = (percentage/100) * (parseFloat(materiality_rounding));
		$(".ctt_scope").html(format_value(ctt));
		ctt_rounded = Math.ceil(ctt);
		$('.ctt_scope_rounding').html(format_value(Math.ceil(ctt_rounded/1000)*1000));

	}


}

function check_is_revenue(selected_val)
{
	switch(selected_val) {
			
			case "5":
		  	case "6":
		    	$('.revenue_factor_table').show();
		    	break;
		 
		   	default:
		   		$('.revenue_factor_table').hide();
		}
}
