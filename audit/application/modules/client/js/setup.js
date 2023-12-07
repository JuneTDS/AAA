var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var array_client_billing_info_id = [];

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

if($("[name='stocktake_checkbox']").bootstrapSwitch('state') == 0){
    // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
    var stocktake_email = $(".stocktake_email");

    // hidden_val.val(0);
    stocktake_email.hide();
    $("[name='hidden_stocktake_checkbox']").val(0);
}
else
{
    // var hidden_val = $(event.target).parent().parent().parent().find("[name='emphasis_of_matter_checkbox']");
    var stocktake_email = $(".stocktake_email");

    // hidden_val.val(1);
    stocktake_email.show();
    $("[name='hidden_stocktake_checkbox']").val(1);
}

// Triggered on switch state change.
$("[name='stocktake_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    // var hidden_val = $(event.target).parent().parent().parent().find("[name='stocktake_checkbox']");
    var stocktake_email = $(".stocktake_email");

    if(state){
        // hidden_val.val(1);
        stocktake_email.show();
        $("[name='hidden_stocktake_checkbox']").val(1);
    }
    else{
        // hidden_val.val(0);
        stocktake_email.hide();
        $("[name='hidden_stocktake_checkbox']").val(0);
    }
    // console.log(hidden_val.val());
})

$(document).on('click',"#save_signing_information",function(e){
    e.preventDefault();
    setup_section = "signing_information_form";
    $("#w2-setup #signing_information_form").submit();
});

$(document).on('click',"#save_contact_information",function(e){
    e.preventDefault();
    setup_section = "contact_information_form";
    $("#w2-setup #contact_information_form").submit();
});

$(document).on('click',"#save_stocktake_setting",function(e){
    e.preventDefault();
    var valid_setup = $("#reminder_form").data('bootstrapValidator').validate();

    if(valid_setup.isValid())
    {
        setup_section = "reminder_form";
        $("#w2-setup #reminder_form").submit();
    }
    else
    {
        toastr.error("Please complete all required field", "Error");
    }
   
});

$(document).on('click',"#save_corporate_representative",function(e){
    e.preventDefault();
    setup_section = "corporate_representative_form";
    $("#w2-setup #corporate_representative_form").submit();
});


function open_new_tab(firm_id)
{
    window.open ('our_services','_blank');
}

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



toastr.options = {

  "positionClass": "toast-bottom-right"

}



function setup_change_date(latest_incorporation_date)
{
    $('.from_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

    $('.to_datepicker').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', latest_incorporation_date);

}

$(document).ready(function(){
    $('#reminder_form').bootstrapValidator({
         excluded: [':disabled', ':hidden', ':not(:visible)'],
         fields: {
            'stocktake_email': {
                validators: {
                    regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'The value is not a valid email address'
                    }
             }
            }
        }
    });

});

