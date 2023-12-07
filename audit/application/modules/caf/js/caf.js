$(".save_icon").hide();
$(".orientation_icon").hide();
$(".textbox_icon").hide();
$(".export_icon").hide();
$(".crossref_icon").hide();

$(document).ready(function () {
    $('.select2').select2();
    $('[data-toggle="tooltip"]').tooltip();
    // $("#programme_list-table").DataTable( {
    //     "paging":   false,
    //     "ordering": false,
    //     "info":     false
    // } );

    // $('#programme_list-table_wrapper .dataTables_filter input[type="search"]').css(
    //     {'text-align':'left'}
    // );

});

var $tr = $('.main-category tr.no-sort'); //get the reference of row with the class no-sort
for (var i = $tr.length - 1; i >= 0; i--) {
	priority_row.push($tr[i]);
	priority_row_html.push($tr[i].outerHTML);

}
// var mySpecialRow = i_row.prop('outerHTML'); //get html code of tr
// var mySpecialRow = i_row.outerHTML;
// console.log($tr);
// console.log(mySpecialRow);
$tr.remove(); //remove row of table
priority_row_first_alphabet = getFirstLetterFromDom(priority_row);

function getFirstLetterFromDom(arr)
{
	var temp_arr = [];

	for (var a = 0; a <= arr.length; a++) {
		temp_arr.push(($(arr[a]).find('.index_no').text()).substring(0,1));
		// console.log(arr[a]);
	}

	// console.log(temp_arr);
	return temp_arr;
}	

$('#planning-table').DataTable({
	"aoColumns": [null, {"sType": "natural"}, null, null, null],
    "aaSorting": [[ 1, "asc" ]],
    searching: false, 
    paging: false,
    "bInfo": false,
    
    "fnDrawCallback": function(){
        //add the row with 'prepend' method: in the first children of TBODY
        var sorted_table = $('#planning-table tbody tr');
        var check_alphabet;
        // var same_alphabet_flag = false;
        // var added_check;
        sorted_table.each(function(index){
        	// console.log(index);
        	check_alphabet = ($(this).find('.index_no').text()).substring(0,1);
        	for (var j = priority_row_first_alphabet.length - 1; j >= 0; j--) {
        		// console.log(check_alphabet+"==="+priority_row_first_alphabet[j]);
        		if(priority_row_first_alphabet[j] == check_alphabet)
        		{
        			console.log(check_alphabet+index+"==="+priority_row_first_alphabet[j]+j);
        			$(sorted_table).eq(index).before(priority_row_html[j]);
        			// added_check = j;
        			priority_row_first_alphabet[j] = "occured";
        		}

        	}

        	
        });
        // $('#planning-table tbody').prepend(mySpecialRow);

    }
});

$('#final-table').DataTable({
    "aoColumns": [null, {"sType": "natural"}, null, null, null],
    "aaSorting": [[ 1, "asc" ]],
    searching: false, 
    paging: false,
    "bInfo": false,
    
    "fnDrawCallback": function(){
        //add the row with 'prepend' method: in the first children of TBODY
        var sorted_table = $('#final-table tbody tr');
        var check_alphabet;
        // var same_alphabet_flag = false;
        // var added_check;
        sorted_table.each(function(index){
            // console.log(index);
            check_alphabet = ($(this).find('.index_no').text()).substring(0,1);
            for (var j = priority_row_first_alphabet.length - 1; j >= 0; j--) {
                // console.log(check_alphabet+"==="+priority_row_first_alphabet[j]);
                if(priority_row_first_alphabet[j] == check_alphabet)
                {
                    console.log(check_alphabet+index+"==="+priority_row_first_alphabet[j]+j);
                    $(sorted_table).eq(index).before(priority_row_html[j]);
                    // added_check = j;
                    priority_row_first_alphabet[j] = "occured";
                }

            }

            
        });
        // $('#planning-table tbody').prepend(mySpecialRow);

    }
});

$('#workings-table').DataTable({
    "aoColumns": [null, {"sType": "natural"}, null, null, null],
    "aaSorting": [[ 1, "asc" ]],
    searching: false, 
    paging: false,
    "bInfo": false,
    
    "fnDrawCallback": function(){
        //add the row with 'prepend' method: in the first children of TBODY
        var sorted_table = $('#workings-table tbody tr');
        var check_alphabet;
        // var same_alphabet_flag = false;
        // var added_check;
        sorted_table.each(function(index){
            // console.log(index);
            check_alphabet = ($(this).find('.index_no').text()).substring(0,1);
            for (var j = priority_row_first_alphabet.length - 1; j >= 0; j--) {
                // console.log(check_alphabet+"==="+priority_row_first_alphabet[j]);
                if(priority_row_first_alphabet[j] == check_alphabet)
                {
                    console.log(check_alphabet+index+"==="+priority_row_first_alphabet[j]+j);
                    $(sorted_table).eq(index).before(priority_row_html[j]);
                    // added_check = j;
                    priority_row_first_alphabet[j] = "occured";
                }

            }

            
        });
        // $('#planning-table tbody').prepend(mySpecialRow);

    }
});



$('.addline_icon').click(function() {
    $("#addline_popup").modal("show");

});

$(document).on('change',".caf_type",function(){
    
    var selected_caf_type = $('.caf_type').val();
    // console.log(selected_caf_type);
    if(selected_caf_type == "7")
    {
        $('.programme_div').show();
    }
    else
    {
        $('.programme_div').hide();
    }

    if(selected_caf_type == "16")
    {
        $('.awp_div').show();
    }
    else
    {
        $('.awp_div').hide();
    }

    if(selected_caf_type == "17")
    {
        $('.leadsheet_div').show();
    }
    else
    {
        $('.leadsheet_div').hide();
    }

});

$(document).on('change',".leadsheet_dropdown",function(){
    // console.log(this);
    if($(this).find('option:selected').val() != "all")
    {
        $(".leadsheet_name").val($(this).find('option:selected').val());
        $(".caf_name").val($(this).find('option:selected').text());
    }

    if($(this).hasClass("lvl1_leadsheet"))
    {
        get_child_dropdown($('.lvl2_leadsheet'), $('.lvl1_leadsheet :selected').val());
    }

    if($(this).hasClass("lvl2_leadsheet"))
    {

        get_child_dropdown($('.lvl3_leadsheet'), $('.lvl2_leadsheet :selected').val());
        
    }

    if($(this).hasClass("lvl3_leadsheet"))
    {
        get_child_dropdown($('.lvl4_leadsheet'), $('.lvl3_leadsheet :selected').val());
    }

    if($(this).hasClass("lvl4_leadsheet"))
    {
        get_child_dropdown($('.lvl5_leadsheet'), $('.lvl4_leadsheet :selected').val());
    }
});

$(document).on('click',"#addCafLine",function(e)
{
    
    var selected_caf_type = $('.caf_type').val();
    if(selected_caf_type == "7")
    {
        $("#addline_popup").modal("hide");
        $("#loadingMessage").show();
        // console.log($("#add_programme_caf_form"));
        $("#add_programme_caf_form").submit();
        // console.log("hiiiiiii")
    }
    if(selected_caf_type == "16")
    {

        $("#audit_awp_info_form").validate({
            rules: {
                "caf_index": {
                    checkIndexUnique: true,
                    required: true
                }
            }
        });

        if(awp_form_is_valid && $("#audit_awp_info_form").valid())
        {
            $("#addline_popup").modal("hide");
            $("#loadingMessage").show();
            // console.log($("#audit_awp_info_form"));
            $("#audit_awp_info_form").submit();

        }
        // console.log("hiiiiiii")
    }
    if(selected_caf_type == "17")
    {

        $("#audit_leadsheet_form").validate({
            rules: {
                "caf_index": {
                    checkIndexUnique: true,
                    required: true
                }
            }
        });

        if(awp_form_is_valid && $("#audit_leadsheet_form").valid())
        {

            $("#addline_popup").modal("hide");
            $("#loadingMessage").show();
            // console.log($("#audit_awp_info_form"));
            $("#audit_leadsheet_form").submit();

        }
        // console.log("hiiiiiii")
    }
    // $("#adjustment_form").submit();
    // $("#adjustment_form").validate();
});

$(document).on('submit',"#add_programme_caf_form",function(e) {
    e.preventDefault();
    var $form = $(e.target);
    // console.log("submit");

    $.ajax({
        type: 'POST',
        // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
        url: add_programme_caf_url,
        data: $form.serialize(),
        dataType: 'json',
        success: function(response){

            if(response.result)
            {
                location.reload();
            }

            
        }
    });
});

$("#audit_awp_info_form").submit(function(e) {
       e.preventDefault();
    var $form = $(e.target);
    console.log("submit");

    $.ajax({
        type: 'POST',
        // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
        url: add_awp_caf_url,
        data: $form.serialize(),
        dataType: 'json',
        success: function(response){

            if(response.result)
            {
                location.reload();
            }

            
        }
    });

});


$("#audit_leadsheet_form").submit(function(e) {
    e.preventDefault();
    var $form = $(e.target);
    // $('#audit_leadsheet_form').append('<input type="hidden" name="caf_name" class="caf_name" value="" />');
    // var selected_leadsheet = $(".leadsheet_name option:selected").text();
    // $('.caf_name').val(selected_leadsheet);

    $.ajax({
        type: 'POST',
        // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
        url: add_leadsheet_caf_url,
        data: $form.serialize(),
        dataType: 'json',
        success: function(response){

            if(response.result)
            {
                location.reload();
            }

            
        }
    });

});

// $(document).on('submit',"#audit_awp_info_form",function(e) {
//     e.preventDefault();
//     var $form = $(e.target);
//     console.log("submit");

//     $.ajax({
//         type: 'POST',
//         // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
//         url: add_awp_caf_url,
//         data: $form.serialize(),
//         dataType: 'json',
//         success: function(response){

//             if(response.result)
//             {
//                 // location.reload();
//             }

            
//         }
//     });
// });

$(document).on('click',".delete_icon",function(e) 
{
    //check if there is any selected checkbox
    var check_cbx = false;
    var selected_line = [];
    $( ".cbx" ).each(function() {
        if($(this).is(':checked'))
        {
            check_cbx = true;
            selected_line.push($(this).val());
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
                    console.log(selected_line);
                    $.post(delete_caf_line_url, { 'caf_ids': selected_line }, function(data, status){
                        if(data){
                            location.reload();
                        }
                    });
                }
            }
        })
    }
    else
    {
        alert("No selected line");
    }

});

$(function() {
    $.contextMenu({
        selector: '.caf_row', 
        callback: function(key, options) {
            if(options == "edit")
            {
                
                open_edit_popup(this);
            }
            
        },
        items: {
            "edit": {name: "Edit", icon: "edit"}
        }
    });  
});

function open_edit_popup(tr)
{
    var caf_id    = $(tr).find('.caf_id').val();
    var caf_index = $(tr).find('.index_no').text();
    var caf_name  = $(tr).find('.caf_name').val();
    // console.log(caf_name);
    $("#edit_modal").modal("show");

    $("#selected_caf_id").val(caf_id);
    $(".edit_index").val(caf_index);
    $(".edit_name").val(caf_name);

    // console.log(this);
}

$(document).on('change',".caf_index",function(){ 
    
    $('#loadingmessage').show();
    // console.log("hiiii");
    var selected_index = $('.caf_index').val();

    // console.log($("#audit_awp_info_form"));

    $("#audit_awp_info_form").validate({
        rules: {
            "caf_index": {
                checkIndexUnique: true
            }
        }
    });

    $("#audit_leadsheet_form").validate({
        rules: {
            "caf_index": {
                checkIndexUnique: true
            }
        }
    });
});

$(document).on('click',"#btn_save_new_caf",function(e)
{
  
    $("#edit_caf_line").submit();
    // console.log("hiiiiiii")
    
});

$(document).on('submit',"#edit_caf_line",function(e) {
    e.preventDefault();
    var $form = $(e.target);
    // console.log("submit");

    $.ajax({
        type: 'POST',
        // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
        url: update_caf_line_url,
        data: $form.serialize(),
        dataType: 'json',
        success: function(response){

            if(response)
            {
                location.reload();
            }

            
        }
    });
});

$.validator.addMethod("checkIndexUnique", 
    function(value, element) {
        console.log(value);
        var result = false;
        $.ajax({
            type:"POST",
            async: false,
            url: check_avail_caf_index_url, // script to validate in server side
            data: {index: value, assg_id : assignment_id},
            success: function(data) {
                result = (data == "true") ? true : false;
                // console.log(result);
                awp_form_is_valid = result;
            }
        });
        // return true if username is exist in database
        return result; 
    }, 
    "This index already exist! Try another."
);

function get_sheet_id()
{       
        $("#loadingMessage").show();
        $.ajax({
        type: 'POST',
        // url: save_adjustment_url + '&arr_deleted_info=' + arr_deleted_info,
        url: get_sheet_id_url,
        dataType: 'json',
        success: function(response){

            if(response.result)
            {
                $("#loadingMessage").hide();
                var url = "https://docs.google.com/spreadsheets/d/" + response.spreadsheet_id;
                var win = window.open(url, '_blank');
                win.focus();
            }

            
        }
    });
} 

function get_child_dropdown(dropdown, parent_id)
{
    if(parent_id == undefined)
    {
        parent_id = ""
    }

    $.ajax({
        type: "POST",
        url:  get_leadsheet_list_url,
        data: "&parent_id="+ parent_id + "&assignment_id="+ $("#assignment_id").val() ,
        success: function(data)
        {

            var result = JSON.parse(data);

            // $(".charge_out_rate_designation_div").remove();

            // var dropdown = '<div class="charge_out_rate_designation_div" style="width: 40%;"><select class="form-control charge_out_rate_designation general" id="staff_designation" name="staff_designation" style="width: 100% !important" required><option value="" selected="selected">Please Select Designation</option>';

            if(result != '')
            {
                $(dropdown).empty();
                // console.log(result);
                // for($i=0;$i<result.length;$i++)
                // {
                //     dropdown += '<option value="'+result[$i]['designation']+'">'+result[$i]['designation']+'</option>';
                // }
                if(parent_id != "")
                {
                    var option = $('<option />');
                    option.attr('value', "all").text("All");
                    option.attr('selected', 'selected');
                }

                // var option = $('<option />');
                // option.attr('value', "all").text("All");
                // option.attr('selected', 'selected');
                     
                $(dropdown).append(option);

                $.each(result, function(key, val) {
                    option = $('<option />');
                    if(key!="")
                    {
                        option.attr('value', key).text(val);
                        $(dropdown).append(option);
                        
                    }
         
                    
                    
                });
                
                $(dropdown).trigger("change");

     
            }



            // dropdown += '</select></div>';
            // $(".charge_out_rate_designation_div_div").append(dropdown);

            // if(designation != ''){
            //     $(".charge_out_rate_designation").val(designation).trigger('change');
            //     // $(".designation").val("");

            //     // if(Admin == false && user_id != 79)
            //     if(!Admin && !Manager)
            //     {
            //         $(".general").attr("disabled", true);
            //     }
            // }
        }
});
}
