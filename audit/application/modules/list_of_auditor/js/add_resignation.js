var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;
var clearance_history_table = "";


function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

function Client() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getClientName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            console.log(data);
            if(data.tp == 1)
            {
                $.each(data['result'], function(key, val) 
                {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_client_name != null)
                    {
                        // option.attr('selected', 'selected');
                        $('.client_name').attr('disabled', true);
                    }
                    // console.log(option);
                    $('.client_name').append(option);
                });
                $('#client_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else
            {
                alert(data.msg);
            }
        }); 
    };

    this.getPicName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getPicName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.our_auditor').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.our_auditor').find("option:eq(0)").html("Select Auditor Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_auditor_name != null && key == data.selected_auditor_name)
                    {
                        option.attr('selected', 'selected');
                        $('.our_auditor').attr('disabled', true);
                    }
                    $('.our_auditor').append(option);
                });
                // $('#our_auditor').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getAuditorName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getAuditorName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.audit_firm_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.audit_firm_name').find("option:eq(0)").html("Select Audit Firm Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_auditor_name != null && key == data.selected_auditor_name)
                    {
                        option.attr('selected', 'selected');
                        $('.audit_firm_name').attr('disabled', true);
                    }
                    $('.audit_firm_name').append(option);
                });
                $('#audit_firm_name').select2({
                    escapeMarkup: function (markup) { return markup; },
                    language: {
                        noResults: function () {
                             return "<a href='"+auditor_url+"'>Audit firm not exist. Add here</a>";
                        }
                    }
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getFirmName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getFirmName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.our_firm_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.our_firm_name').find("option:eq(0)").html("Select Our Firm Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_firm_name != null && key == data.selected_firm_name)
                    {
                        option.attr('selected', 'selected');
                        $('.our_firm_name').attr('disabled', true);
                    }
                    $('.our_firm_name').append(option);
                });
                $('#our_firm_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };


    // this.getAuthStatus = function() {
    //     var url = base_url+"/"+folder+"/"+'bank/getAuthStatus';
    //     //console.log(url);
    //     var method = "get";
    //     var data = {};
    //     $('.auth_status').find("option:eq(0)").html("Please wait..");
    //     call.send(data, url, method, function(data) {
    //         //console.log(data);
    //         $('.auth_status').find("option:eq(0)").html("Select Status:");
    //         //console.log(data);
    //         if(data.tp == 1){
    //             $.each(data['result'], function(key, val) {
    //                 var option = $('<option />');
    //                 console.log(data.select_auth_status);
    //                 option.attr('value', key).text(val);
    //                 if(data.select_auth_status != null && key == data.select_auth_status)
    //                 {
    //                     option.attr('selected', 'selected');
    //                 }
    //                 $('.auth_status').append(option);
    //             });
    //             //$(".nationality").prop("disabled",false);
    //         }
    //         else{
    //             alert(data.msg);
    //         }
    //     }); 
    // };

}

$(function() {
    var cn = new Client();
    cn.getClientName();
    cn.getPicName();
    cn.getAuditorName();
    cn.getFirmName();
    // cn.getAuthStatus();
    //cn.frequency();
    //cn.type_of_day();
    //cm.getDirectorSignature1();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
}


function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$(document).ready(function() {
    $('#loadingBilling').hide();
    
    // console.log("nani");

     $('#create_resignation_letter_form').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            client_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Client name is required and cannot be empty'
                    }
                }
            },
            audit_firm_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Audit firm name is required and cannot be empty'
                    }
                }
            },
            our_firm_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Our firm is required and cannot be empty'
                    }
                }
            },
            our_date: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Date of our letter is required and cannot be empty'
                    }
                }
            },
            their_date: {
                validators: {
                    notEmpty: {
                        message: 'Date of their letter is required and cannot be empty'
                    }
                }
            }
        }
    });
});

$('.their_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#create_resignation_letter_form').bootstrapValidator('revalidateField', 'their_date');
});


$('.our_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    $('#create_resignation_letter_form').bootstrapValidator('revalidateField', 'our_date');
});


// if(edit_first_letter != "")
// {
//    $('[name="fye_date"]').val(edit_first_letter[0]["fye_date"]); 
// }

// if(edit_first_letter != "")
// {
//    $('[name="send_date"]').val(edit_first_letter[0]["send_date"]); 
// }



$("[name='own_letterhead_checkbox']").bootstrapSwitch({
    state: state_own_letterhead_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NO',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

// Triggered on switch state change.
$("[name='own_letterhead_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    console.log(state); // true | false

    if(state == true)
    {
        $("[name='hidden_own_letterhead_checkbox']").val(1);
    }
    else
    {
        $("[name='hidden_own_letterhead_checkbox']").val(0);
    }
});




function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function delete_billing(element) {
    /*if(confirm("Delete This Record?"))
    {*/
        var tr = jQuery(element).parent().parent(),
            billing_service_id = tr.find('input[name="billing_service_id[]"]').val();

        tr.closest("DIV.tr").remove();
        //console.log($("#allotment_add > div").length);
        if($("#body_create_billing > div").length == 1)
        {
            if($('.delete_billing_button').css('display') == 'block')
            {
                $('.delete_billing_button').css('display','none');
            }
        }
        sum_total();
    //}
}




$(document).on("submit",function(e){
    
        e.preventDefault();
        var $form = $(e.target);
        

        if($('#create_resignation_letter_form').css('display') != 'none')
        {
            //$("#create_billing_form").formValidation('destroy');
            //$('[name="invoice_no"]').attr('disabled', false);
            // $('[name="billing_date"]').attr('disabled', false);
            // $('[name="address"]').attr('disabled', false);
            // $('.currency').attr('disabled', false);

            $('.client_name').attr('disabled', false);
            $('.audit_firm_name').attr('disabled', false);
            $('.our_firm_name').attr('disabled', false);
            //console.log($(".client_name option:selected").text());
            // var selected_company_name = $(".client_name option:selected").text();
            $.ajax({
                type: 'POST',
                url: save_resignation_url,
                data: $form.serialize(),
                dataType: 'json',
                success: function(response){
                    //console.log(response.Status);

                    // if (response.Status === 1) 
                    // {
                    //     toastr.success(response.message, response.title);
                    //     //console.log(response);
                    //     var getUrl = window.location;
                    //     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                    //     //console.log(baseUrl);
                    //     window.location.href = baseUrl;

                    // }
                 

                    if(response.status == "success")
                    {
                        $('.client_name').attr('disabled', true);
                        $('.audit_firm_name').attr('disabled', true);
                        $('.our_firm_name').attr('disabled', true);

                      
                            // generate_first_clearance_letter(response.letter_id, $('.send_date').val());
                        

                        window.location.href = auditor_url;
                        // console.log(bank_url);
                        
                    }
                    else
                    {
                        alert("Selected company may not assigned to any service or servicing firm. Failed to add first letter.");
                        window.location.href = auditor_url;
                    }
                 
                   
                    
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "error");
        }
        

});

function generate_first_clearance_letter(letter_id, send_date)
{


    $.ajax({ //Upload common input
      url       : generate_first_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"letter_id": letter_id, "send_date": send_date},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
            window.open(
              response.pdf_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

function generate_resignation_letter(letter_id)
{
    // var auth_id     = div.find('.auth_id').val();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_resignation_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"rl_id": letter_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
        window.open(
          response.pdf_link,
          '_blank' // <- This is what makes it open in a new window.
        );
        window.location.href = auditor_url;
        // return true;

        }
    })
}

$(document).on('click',"#saveLetter",function(e){
    var bootstrapValidator = $("#create_resignation_letter_form").data('bootstrapValidator');
    $('.client_name').attr('disabled', false);
    $('.our_firm_name').attr('disabled', false);
    bootstrapValidator.validate();


    if(bootstrapValidator.isValid())
    {
        bootbox.confirm({
            message: "Resigning this company will deactivate the service of statutory audit, stocktake reminder and bank confirmation. Are you sure you want to continue?",
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
                   $("#resignation_doc").fileinput('upload');
                }
            }
        })
    }
        // $("#resignation_doc").fileinput('upload');
    else
    {
        return;
    } 
    // $("#create_bank_auth_form").submit();
});


function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (i == 1)
    {
        return i + "<sup>st</sup> letter";
    }
    if (j == 1 && k != 11) {
        return i + "<sup>st</sup> follow up";
    }
    if (j == 2 && k != 12) {
        return i + "<sup>nd</sup> follow up";
    }
    if (j == 3 && k != 13) {
        return i + "<sup>rd</sup> follow up";
    }
    return i + "<sup>th</sup> follow up";
}

$("#resignation_doc").fileinput({
    'async' : false,
    theme: 'fa',
    uploadUrl: '/audit/list_of_auditor/uploadResignationDoc', // you must set a valid URL here else you will get an error
    // uploadUrl: '/test_audit/bank/uploadBcDoc', // you must set a valid URL here else you will get an error
    uploadAsync: false,
    browseClass: "btn btn-primary",
    fileType: "any",
    required: true,
    showCaption: false,
    showUpload: false,
    showRemove: false,
    showClose: false,
    fileActionSettings: {
                    showRemove: true,
                    showUpload: false,
                    showZoom: true,
                    showDrag: true,
                },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + '/audit/document/clearance/{filename}',
    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    // deleteUrl: "/audit/bank/deleteBaFile",
    // maxFileSize: 20000048,
    // maxImageWidth: 1000,
  //   maxImageHeight: 1500,
  //   resizePreference: 'height',
  //   resizeImage: true,
    // purifyHtml: true,// this by default purifies HTML data for preview
    /*uploadExtraData: { 
        officer_id: $('input[name="officer_id"]').val() 
    }*/
    /*width:auto;height:auto;max-width:100%;max-height:100%;*/
    uploadExtraData: function() {
        return {
            resignation_form: JSON.stringify(objectifyForm($('#create_resignation_letter_form').serializeArray()))
            // username: $("#username").val()
        };
    }

}).on('filesorted', function(e, params) {
    //console.log('File sorted params', params);
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
    if($("#close_page").val() == 1)
    {
        // window.close();
    }
    else
    {

            
        console.log(data);
        // $("#upload_cl_doc_modal").modal("hide");
        // alert('Pause');
        toastr.success("Information Updated", "Success");

        generate_resignation_letter(data.response)
        
            
        
        // generate_resignation_letter(data.response);
        
        
    }
    
    //console.log(data);
}).on('fileuploaderror', function(event, data, msg) {
    // break;

        //window.location.href = base_url + "personprofile";
        // if(corporate_reload_link != false)
        // {
            // window.location.href = corporate_reload_link;
        // }
        // console.log(msg);
        // $("#upload_ba_doc_modal").modal("hide");
        toastr.error(msg, "Error");
    
    //toastr.error("Error", "Error");
});

function objectifyForm(formArray) {//serialize data function

  var returnArray = {};
  for (var i = 0; i < formArray.length; i++){
    returnArray[formArray[i]['name']] = formArray[i]['value'];
  }
  return returnArray;
}
