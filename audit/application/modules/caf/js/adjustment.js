$(".print_icon").hide();
$(".access_icon").hide();

$(".addline_icon").addClass("disable");
document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

$(".orientation_icon").addClass("disable");
document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

$(".textbox_icon").addClass("disable");
document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

if(!show_data_content)
{
    $(".export_icon").addClass("disable");
    document.getElementById("export_icon").src = base_url + "img/export-disable.png";
}




$(document).ready(function () {
	$('.select2').select2();
    $('[data-toggle="tooltip"]').tooltip();
    check_adjustment_line_length();

    // $('#adjustment_form').bootstrapValidator({
    //     excluded: ':disabled',
    //     fields: {
    //         adjustment_type: {
    //             rows:".form-group",
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Client name is required and cannot be empty'
    //                 }
    //             }
    //         },
    //         je_no: {
    //             rows:".form-group",
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Bank name is required and cannot be empty'
    //                 }
    //             }
    //         },
    //         narration: {
    //             rows:".form-group",
    //             validators: {
    //                 notEmpty: {
    //                     message: 'FYE date is required and cannot be empty'
    //                 }
    //             }
    //         }
    //     }
    // });
  // $("body").tooltip({ selector: '.create_st_arrangement' });
    // $(document).keydown(function(e) {
    //     // console.log(e.keyCode);
    //     if(e.keyCode === 27)
    //     {
    //         $('#cancel_adjustment').click();
    //     }
      
    // });
});

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
    var adjust_value_sum = 0;
    var je_no = $(".je_no").val();
    var delete_data = "";
    var check_zero_value = true;

    $(adjust_value_input).each(function() { 
       
        var each_row_val = $(this).val();
        adjust_value_sum += parseFloat(each_row_val);
        adjust_value_sum = parseFloat(parseFloat(adjust_value_sum).toFixed(2));

        console.log("each row: " + each_row_val);
        console.log("total: " + adjust_value_sum);
        
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

$('#adjustment').on('hidden.bs.modal', function(e)
{ 
    $("#adjustment_form")[0].reset();
    $(this).find('#adjustment_master_id').val("");
    $(this).find('.je_no').val("");
    $(this).find('.je_no_hidden').val("");
    $(this).find('.adjustment_value').val("");
    $(this).find('.reference').val("");
    $(this).find('.adjustment_info_id').val("");


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

$(document).on('change',".type_filter",function(){
    // var employee  = $(".timesheet_employee_filter").val();
    // var year      = $(".timesheet_year_filter").val();
    $("#loadingMessage").show();
    var type     = $(".type_filter").val();
    $("#adjustment_display_body").empty();

    $.ajax({
        type: "POST",
        url:  get_adjustment_data_url,
        data: '&caf_id=' + caf_id,
        success: function(data)
        {
          $("#loadingMessage").hide();
          adjustment_data = JSON.parse(data);

          if(adjustment_data != null)
          {
              for (var i = 0; i < adjustment_data.length; i++) {
                  if(adjustment_data[i]['type'] == type)
                  {
                      for (var j = 0; j <  adjustment_data[i]['adjustment_info'].length; j++) {
                          // console.log(adjustment_data[i]['adjustment_info'][j]);
                          var adjustment_info = adjustment_data[i]['adjustment_info'][j];
                          if(j == 0)
                          {
                            je_no = "<input type='checkbox' class='cbx' name='' value='"+ adjustment_data[i]['id'] +"'><span style='float:right;'>" + adjustment_data[i]['je_no'] + "</span>";
                          }
                          else
                          {
                            je_no = "";
                          }

                          if(adjustment_info['adjust_value'] >= 0)
                          {
                              var rowHtml =   "<tr class='no_bottom'>"+
                                                "<td width='6%'>"+je_no+"</td>"+
                                                "<td width='49%'>"+adjustment_info['description']+"</td>"+
                                                "<td align='right'>"+format_value(adjustment_info['adjust_value'])+"</td>"+
                                                "<td align='right'></td>"+
                                                "<td align='center'>"+adjustment_info['reference']+"</td>"+
                                              "</tr>";
                              $("#adjustment_display_body").append(rowHtml);
                          }
                          else if(adjustment_info['adjust_value'] < 0)
                          {
                              var rowHtml =   "<tr class='no_bottom'>"+
                                                "<td width='6%'>"+je_no+"</td>"+
                                                "<td width='49%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+adjustment_info['description']+"</td>"+
                                                "<td align='right'></td>"+
                                                "<td align='right'>"+format_value((Math.abs(adjustment_info['adjust_value'])).toFixed(2))+"</td>"+
                                                "<td align='center'>"+adjustment_info['reference']+"</td>"+
                                              "</tr>";
                              $("#adjustment_display_body").append(rowHtml);
                          }
                          
                      }

                      var rowHtml =   "<tr class='bordered'>"+
                                        "<td width='6%'></td>"+
                                        "<td colspan='4'>("+adjustment_data[i]['narration']+")"+
                                            "<a class='edit-adjustment' href='javascript:void(0);' onclick='edit_adjustment("+adjustment_data[i]['id']+")' data-toggle='tooltip' data-trigger='hover' style='height:45px;font-weight:bold;float:right;' data-original-title='Edit Adjustment' >"+
                                                "<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>"+
                                        "</a></td>"+
                                      "</tr>";
                      $("#adjustment_display_body").append(rowHtml);

                  }
                  
              }

              $('[data-toggle="tooltip"]').tooltip();
          }
        } 
      });
    
});

$(document).on('change',".adjustment_type",function(){
    var type     = $(".adjustment_type").val();
    var smallest_avail_no = "";
    reserved_je_no = {};
    // $(".je_no").val("");

    if(type)
    {
        // var check_je_no = $(".hidden_je_no").val();
        // console.log(check_je_no);
        // if(check_je_no == "")
        // {
            $("#loadingMessage").show();

            $.ajax({
                type: "POST",
                url:  get_je_no_url,
                data: '&caf_id=' + caf_id + '&type=' + type, 
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

$(document).on('click',".delete_icon",function(e) 
{
    //check if there is any selected checkbox
    var check_cbx = false;
    var selected_adjustment = [];
    $( ".cbx" ).each(function() {
        if($(this).is(':checked'))
        {
            check_cbx = true;
            selected_adjustment.push($(this).val());
        }
    });

    if(check_cbx)
    {
        bootbox.confirm({
            message: "Do you want to delete selected line(s)?",
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
                    // console.log(selected_line);
                    $.post(delete_adjustment_master_url, { 'selected_adjustment_master': selected_adjustment }, function(data, status){
                        if(data){
                            $(".type_filter").trigger("change");
                        }
                    });
                }
            }
        })
    }
    else
    {
        alert("No selected item");
    }

});

$(document).on('click',".export_icon",function(e) 
{
    
    // var form = $('#form_balance_sheet').serialize();
    var type     = $(".type_filter").val();

    if(type != ''){
        $('#loadingMessage').show();
        $.ajax({
            type: 'post',
            url: export_adjustment_pdf_url,
            dataType: 'json',
            data: '&type=' + type,
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
    else
    {
        alert("No adjustment type chosen to export.");
    }

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
  console.log(rowCount);
  if(rowCount == 1)
  {
    $('.remove_adjustment_line').hide();
  }
  else 
  {
    $('.remove_adjustment_line').show();
  }
}

function get_smallest_avail_no(arr) {
  for (i = 1; i < 1000000; i++) {
    if(!arr.includes(i)) return i;
  }
}

function edit_adjustment(adjustment_master_id)
{
    $("#body_add_adjustment").empty();
    var selected_adjustment = {};
    var account_dropdown = document.createElement("select");
    $('#loadingMessage').show();
   

    // var account_dropdown = $("#account_dropdown_clone").clone();
    // account_dropdown.val(selected_adjustment['adjustment_info'][i]['categorized_account_id']);

    if(adjustment_data != null)
    {
        for (var i = 0; i < adjustment_data.length; i++) 
        {
            if(adjustment_data[i]['id'] == adjustment_master_id)
            {
                selected_adjustment = adjustment_data[i];
            }
        }
    }


    if (!($('.modal.in').length)) {
        $('.modal-dialog').css({
            top: "25%",
            right: "10%"
        });
    }
  
    $('#adjustment').modal({
        backdrop: false,
        show: true
    });

    $('#adjustment .modal-dialog').draggable({
        handle: "#adjustment .modal-header",
        containment: 'window' 
    });

    $('#adjustment').on('show.bs.modal', function() {     
        $('[data-toggle="tooltip"]').tooltip();
    });

    $.ajax({
        type: "POST",
        url:  get_je_no_url,
        data: '&caf_id=' + selected_adjustment['caf_id'] + '&type=' + selected_adjustment['type'], 
        success: function(data)
        {
            $('#loadingMessage').hide();
            reserved_je_no = data;
        }
    })


    $(".adjustment_type").val(selected_adjustment['type']).trigger("change.select2");

    $("#adjustment_master_id").val(selected_adjustment['id']);
    $(".je_no_hidden").val(selected_adjustment['je_no']);
    $(".je_no").val(selected_adjustment['je_no']);

    $("[name='narration']").val(selected_adjustment['narration']);
    console.log(selected_adjustment);

    if((selected_adjustment['adjustment_info']).length > 0)
    {
        for (var i = 0; i <  selected_adjustment['adjustment_info'].length; i++) 
        {
            // preserve newlines, etc - use valid JSON
            account_dropdown_arr = decodeEntities(account_dropdown_arr).replace(/\\n/g, "\\n")  
                           .replace(/\\'/g, "\\'")
                           .replace(/\\"/g, '\\"')
                           .replace(/\\&/g, "\\&")
                           .replace(/\\r/g, "\\r")
                           .replace(/\\t/g, "\\t")
                           .replace(/\\b/g, "\\b")
                           .replace(/\\f/g, "\\f");
            // remove non-printable and other non-valid JSON chars
            account_dropdown_arr = account_dropdown_arr.replace(/[\u0000-\u0019]+/g,""); 
            $.each(JSON.parse(account_dropdown_arr), function(key, val) {
                var option = $('<option />');
                // console.log(data.select_auth_status);
                option.attr('value', key).text(val);
                // option += '<option value="'+key+ '">' + val + '</option>';
                if(selected_adjustment['adjustment_info'][i]['categorized_account_id'] != null && key == selected_adjustment['adjustment_info'][i]['categorized_account_id'])
                {
                    $(option).attr('selected', 'selected');
                }
                // console.log(option);
                $(account_dropdown).append(option);
            });
            // console.log(account_dropdown);



                 
            var rowHtml =   "<tr><input type='hidden' name='adjustment_info_id[]' class='adjustment_info_id' value='"+selected_adjustment['adjustment_info'][i]['id']+"'>"+
                                "<td style='width: 36%;'>"+"<select name='account_id[]' class='account_dropdown select2'>"+$(account_dropdown).html()+"</select>"+"</td>"+
                                "<td style='width: 30%;'><input type='number' name='adjustment_value[]' class='form-control adjustment_value' style='width: 100%;' value='"+ selected_adjustment['adjustment_info'][i]['adjust_value']+"'/></td>"+
                                "<td style='width: 30%;'><input type='text' name='reference[]' class='form-control reference' style='width: 100%;' value='"+selected_adjustment['adjustment_info'][i]['reference']+"'/></td>"+
                                "<td style='width: 4%;'><a class='amber remove_adjustment_line' href='javascript:void(0)' data-toggle='tooltip' data-trigger='hover' style='font-weight:bold;float: right;top:50px;' data-original-title='Remove this line'  onclick=remove_adjustment_line(this) ><i class='fa fa-minus-circle amber' style='font-size:13px;'></i></a></td>"+
                            "</tr>";
            $("#body_add_adjustment").append(rowHtml);
           
        }
    }
    check_adjustment_line_length();
    $('.select2').select2();

    

    // console.log($(".narration").val());
    
    //$(document).ready(function(){
        
    //});
    
    // console.log(selected_adjustment);


}

function format_value(value)
{
    var parts = value.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function decodeEntities(encodedString) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = encodedString;
  return textArea.value;
}