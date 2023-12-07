$(document).on('click',"#saveAdjustment",function(e)
{
    // var bootstrapValidator = $("#adjustment_form").data('bootstrapValidator');
    // bootstrapValidator.validate();
    // if(bootstrapValidator.isValid())
    // {
    //     $("#adjustment_form").submit();
    // }
    // else
    // {
    //     return;
    // }
    // // console.log($("#adjustment_form"));
    $("#adjustment_form").validate({
        errorPlacement: function (error, element) {
            if(element.hasClass('select2') && element.next('.select2-container').length) {
                error.insertAfter(element.next('.select2-container'));
            }
            else
            {
                error.insertAfter(element);
                    // console.log(element)
            }
            // error.insertAfter(element);
        }
    });
    if($("#adjustment_form").valid())
        $("#adjustment_form").submit();
    else return;
    // $("#adjustment_form").submit();
    // $("#adjustment_form").validate();
});

$( "#adjustment_form" ).submit(function(e) {
    e.preventDefault();
    var $form = $(e.target);

    var adjust_value_input = $("#body_add_adjustment .adjustment_value");
    var adjust_value_sum = 0.00;
    var je_no = $(".je_no").val();
    var delete_data = "";
    var check_zero_value = true;

    $(adjust_value_input).each(function() { 
       
        var each_row_val = $(this).val();
        adjust_value_sum += parseFloat(each_row_val);
        adjust_value_sum = parseFloat(parseFloat(adjust_value_sum).toFixed(2));
        
        if(parseFloat($(this).val()) == 0)
        {
            alert("Adjustment value cannot be 0!");
            check_zero_value = false;
            return;
        }
        
    });

    if(check_zero_value)
    {
        if(adjust_value_sum == 0)
        {
            if(arr_deleted_info.length > 0 )
            {
                delete_data = '&arr_deleted_info=' + encodeURIComponent(arr_deleted_info);
            }
            else
            {
                delete_data = "";
            }

            if($('.je_no_hidden').val() == je_no){
                $("#loadingMessage").show();
                $.ajax({
                    type: 'POST',
                    // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
                    url: save_adjustment_url,
                    data: $form.serialize() + delete_data,
                    dataType: 'json',
                    success: function(response){

                        if(response.status == "success")
                        {
                          $("#loadingMessage").hide();
                          $("#adjustment").modal('hide');
                          $(".type_filter").trigger("change");
                          $('.je_no_hidden').val('');
                            
                        }
                        else if(response.status == "error")
                        {
                            $("#loadingMessage").hide();
                            alert(response.message);
                        }

                        
                    }
                });
            }
            else if(!reserved_je_no.includes(je_no))
            {
                $("#loadingMessage").show();
                $.ajax({
                    type: 'POST',
                    // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
                    url: save_adjustment_url,
                    data: $form.serialize() + delete_data,
                    dataType: 'json',
                    success: function(response){

                        if(response.status == "success")
                        {
                          $("#loadingMessage").hide();
                          $("#adjustment").modal('hide');
                          $(".type_filter").trigger("change");
                            
                        }
                        else if(response.status == "error")
                        {
                            $("#loadingMessage").hide();
                            alert(response.message);
                        }

                        
                    }
                });
            }
            else
            {
                alert("Fail to save. JE Number already exist. Please check again.");
            }
        }
        else
        {
            alert("Fail to save. Your adjustment is not balance. Please check again.");
        }
    }

});


$(document).on('click',"#cancel_adjustment",function(e) 
{
    //check if there is any selected checkbox
    bootbox.confirm({
        message: "Changes made will be discarded. Are you sure you want to cancel?",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn_blue'
            },
            cancel: {
                label: 'No',
                className: 'btn_cancel'
            }
        },
        callback: function (result) {
            //console.log(result);
            if(result == true)
            {
              $("#adjustment").modal('hide');

            }
        }
    })

});


$('.adjustment_icon').click(function() {
  // reset modal if it isn't visible
  if (!($('#adjustment .modal.in').length)) {
    $('#adjustment .modal-dialog').css({
      top: "25%",
      right: "10%"
    });
  }

  $('#adjustment').modal({
    backdrop: false,
    show: true
  });

  $('#adjustment .modal-dialog').draggable({
    handle: ".modal-header",
    containment: 'window' 
  });

  $('#adjustment').on('show.bs.modal', function() {     
    $('[data-toggle="tooltip"]').tooltip();
  });

});

function add_adjustment_line()
{
	var content = jQuery('#clone_model tr'),
	size = jQuery('#adjustment_tbl >tbody >tr').length + 1,
	element = null,    
	element = content.clone();
	// console.log(size);
	// element.attr('id', 'rec-'+size);
	// element.find('.delete-record').attr('data-id', size);
	element.appendTo('#body_add_adjustment');
	var this_account_dropdown = element.find(".account_dropdown");

	this_account_dropdown.select2();

	check_adjustment_line_length();
}

function remove_adjustment_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.adjustment_info_id').val();
    // console.log(tr);
    // console.log(deleted_row_id);
    // console.log($(tr));
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    arr_deleted_info.push(deleted_row_id);
    tr.closest("tr").remove();

    check_adjustment_line_length();


}

function check_adjustment_line_length()
{
  var rowCount = $('#body_add_adjustment tr').length;
  // console.log(rowCount);
  if(rowCount == 1)
  {
    $('.remove_adjustment_line').hide();
  }
  else 
  {
    $('.remove_adjustment_line').show();
  }
}

$(document).on('change',".adjustment_type",function(){
    var type     = $(".adjustment_type").val();
    var smallest_avail_no = "";
    reserved_je_no = {};
    // $(".je_no").val("");

    if(type && adjustment_caf_id != 0)
    {
        // var check_je_no = $(".hidden_je_no").val();
        // console.log(check_je_no);
        // if(check_je_no == "")
        // {
            $("#loadingMessage").show();

            $.ajax({
                type: "POST",
                url:  get_je_no_url,
                data: '&caf_id=' + adjustment_caf_id + '&type=' + type, 
                success: function(data)
                {
                    reserved_je_no = data;
                    smallest_avail_no = get_smallest_avail_no(data);
                    $(".je_no").val(smallest_avail_no);

                    $("#loadingMessage").hide();
                }

            });
        // }
    }

});

$('#adjustment').on('hidden.bs.modal', function(e)
{ 
    $("#adjustment_form")[0].reset();
    $(this).find('#adjustment_master_id').val("");
    $(this).find('.je_no').val("");
    $(this).find('.je_no_hidden').val("");
    $(this).find('.adjustment_value').val("");
    $(this).find('.reference').val("");


    $(this).find("select").each(function() { 
       
        $(this).val('').trigger('change');;
        
    });
    
    $('#body_add_adjustment tr').each(function(){
        if($(this).index() != 0)
        {
            $(this).closest("tr").remove();
        }
    });


}) ;

function get_smallest_avail_no(arr) {
  for (i = 1; i < 1000000; i++) {
    if(!arr.includes(i)) return i;
  }
}
