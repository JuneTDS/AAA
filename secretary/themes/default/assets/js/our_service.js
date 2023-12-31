var acc_selector_id;

$(document).ready(function() {
    var counter = 0;
    if (is_admin && !is_individual)
    {
        var columns = [
            { 
                "orderable": false,
                "data": null,
                "defaultContent": '<span id="our_service_Edit" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit Our Service" style="font-size:14px;"><i class="far fa-edit details-control"></i></span>'
            },
            { 
                "className": "col_service_type_name",
                "data": "service_type_name",
                "defaultContent": ''
            },
            { 
                "className": "col_service_name",
                "data": "service_name",
                "defaultContent": ''
            },
            { 
                "className": "col_amount",
                "data": "amount",
                render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
                "defaultContent": ''
            },
            { 
                "className": "col_quickbook",
                "data": "qb_item_id",
                "render": function(data){
                    if(data != undefined)
                    {
                        return "Yes";
                    }
                    else
                    {
                        return "Not";
                    }
                },
                "defaultContent": ''
            },
            { 
                "className": "col_approved",
                "data": "approved",
                "render": function(data){
                    if(data == 0)
                    {
                        return "Not";
                    }
                    else if(data == 1)
                    {
                        return "Yes";
                    }
                },
                "defaultContent": ''
            }
        ];
    }
    else
    {
        var columns = [
            { 
                "orderable": false,
                "data": null,
                "defaultContent": '<span id="our_service_Edit" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit Our Service" style="font-size:14px;"><i class="far fa-edit details-control"></i></span>'
            },
            { 
                "className": "col_service_type_name",
                "data": "service_type_name",
                "defaultContent": ''
            },
            { 
                "className": "col_service_name",
                "data": "service_name",
                "defaultContent": ''
            },
            { 
                "className": "col_amount",
                "data": "amount",
                render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
                "defaultContent": ''
            },
            { 
                "className": "col_quickbook",
                "data": "qb_item_id",
                "render": function(data){
                    if(data != undefined)
                    {
                        return "Yes";
                    } 
                    else
                    {
                        return "Not";
                    }
                },
                "defaultContent": ''
            }
        ];
    }
    var t = $('#our_service_datatable').DataTable({
        //init data
        "processing": true,
        "ajax": "our_services/get_our_service_data/"+user_admin_code_id+"/"+is_admin+"/"+is_individual,
        "columns": columns,
        // render: function ( data, type, row ) {
        //     if(data['amount'])
        //     {
        //         return '$'+ data;
        //     }
        // },
        "createdRow": function( row, data, dataIndex ) {
            //retrieve from database data
            if(data['user_admin_code_id'] != undefined)
            {   
                $(row).addClass( 'row'+dataIndex );

                //for open or close tab
                $(".row"+dataIndex).find('.details-control').click();

                //style
                $(row).find('td:eq(0)').addClass( 'style_edit_icon' );
                $(row).find('td:eq(1)').addClass( 'style_service_type_name' );
                $(row).find('td:eq(2)').addClass( 'style_service_name' );
                $(row).find('td:eq(3)').addClass( 'style_amount' );
                $(row).find('td:eq(4)').addClass( 'style_service_name' );
                if (is_admin && !is_individual)
                {
                    $(row).find('td:eq(5)').addClass( 'style_service_name' );
                }

                counter++;
            }
        },
        "order": [[1, 'asc']],
        "initComplete": function() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    

    //click to add row
    $('#addRow').on( 'click', function () {
        if(!$('#addRow').hasClass("fa-disabled"))
        {
            var t = $('#our_service_datatable').DataTable();  
            //var tr = $(this).closest('tr');
            //var current_row = t.row( tr );
            var row = t.row.add( [
                '<span id="our_service_Edit" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit Our Service" style="font-size:14px;"><i class="far fa-edit details-control"></i></span>',
                '',
                '',
                ''
            ] ).draw(false).nodes().to$().addClass( "row"+t.row().index() );
            //console.log(t.row().index());
            $(".row"+t.row().index()).find('.details-control').click();

            $(".row"+t.row().index()).find('td:eq(0)').addClass( 'style_edit_icon' );
            $(".row"+t.row().index()).find('td:eq(1)').addClass( 'style_service_type_name' );
            $(".row"+t.row().index()).find('td:eq(2)').addClass( 'style_service_name' );
            $(".row"+t.row().index()).find('td:eq(3)').addClass( 'style_amount' );
            $(".row"+t.row().index()).find('td:eq(4)').addClass( 'style_service_name' );
            if (is_admin && !is_individual)
            {
                $(".row"+t.row().index()).find('td:eq(5)').addClass( 'style_service_name' );
            }
            
            $('#addRow').addClass("fa-disabled");
            //$('#addRow').removeAttr('id');

            //console.log(t.row().index());

            counter++;
            
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
 
    // Automatically add a first row of data
    //$('#addRow').click();

    $('#our_service_datatable tfoot td').each( function (index) {
        if(index != 0)
        {   
            var title = $(this).text();
            $(this).html( '<input class="form-control search_box" style="height: 28px; font-size: 12px;" type="text" placeholder="Search '+title+'" />' );
        }
        
    });

    // DataTable
    var table = $('#our_service_datatable').DataTable();
 
    // Apply the search
    table.columns().every( function (){
        var that = this;
        //console.log(this.footer());
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });

    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('#our_service_datatable tbody').on( 'click', 'tr td .details-control', function () {
        var tr = $(this).closest('tr');
        var row = t.row( tr );
        var idx = $.inArray( tr.attr('id'), detailRows );
        //console.log($(this).closest('tr'));
        // console.log(row.data());
        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            tr.removeClass( 'selected' );
            row.child( format(row.data(), row.index()) ).hide();
 
            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else 
        {
            tr.addClass( 'details' );
            tr.addClass('selected');
            row.child( format(row.data(), row.index()) ).show();
            get_billing_info_service_category(row.child(), row.data());
            //get_unit_pricing(row.child(), row.data());
            //console.log(row.data());
            //if(row.data()["postal_code"] != null && row.data()["street_name"] != null)
            if(row.data()["service_type"] == 7)
            {
                row.child().find(".registration_address").show();
                row.child().find(".jurisdiction").prop('disabled', false);
                if(row.data()["jurisdiction_name"].toUpperCase() == "SINGAPORE")
                {
                    row.child().find(".sg_part").show();
                    row.child().find(".foreign_part").hide();
                    row.child().find(".service_postal_code").prop('disabled', false);
                    row.child().find(".service_street_name").prop('disabled', false);
                    row.child().find(".service_building_name").prop('disabled', false);
                    row.child().find(".service_unit_no1").prop('disabled', false);
                    row.child().find(".service_unit_no2").prop('disabled', false);
                }
                else
                {
                    row.child().find(".sg_part").hide();
                    row.child().find(".foreign_part").show();
                    row.child().find(".foreign_address_1").prop('disabled', false);
                    row.child().find(".foreign_address_2").prop('disabled', false);
                    row.child().find(".foreign_address_3").prop('disabled', false);
                }
            }
            else
            {
                row.child().find(".registration_address").hide();
                row.child().find(".jurisdiction").prop('disabled', true);
                row.child().find(".service_postal_code").prop('disabled', true);
                row.child().find(".service_street_name").prop('disabled', true);
                row.child().find(".service_building_name").prop('disabled', true);
                row.child().find(".service_unit_no1").prop('disabled', true);
                row.child().find(".service_unit_no2").prop('disabled', true);

                row.child().find(".foreign_address_1").prop('disabled', true);
                row.child().find(".foreign_address_2").prop('disabled', true);
                row.child().find(".foreign_address_3").prop('disabled', true);
            }

            if(row.data()["service_type"] == "8")
            {
                row.child().find(".display_in_se_section").show();
                row.child().find("#display_in_se").prop('disabled', false);
            }
            else
            {
                row.child().find(".display_in_se_section").hide();
                row.child().find("#display_in_se").prop('disabled', true);
            }

            if(row.data()["sp_required_id"] == "1")
            {
                row.child().find(".sp_textarea").show();
                row.child().find("#service_proposal_description").prop('disabled', false);
            }
            else
            {
                row.child().find(".sp_textarea").hide();
                row.child().find("#service_proposal_description").prop('disabled', true);
            }

            if(row.data()["el_required_id"] == "1")
            {
                row.child().find(".el_textarea").show();
                row.child().find("#engagement_letter_list").prop('disabled', false);
            }
            else
            {
                row.child().find(".el_textarea").hide();
                row.child().find("#engagement_letter_list").prop('disabled', true);
            }
            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }

            $('[data-toggle="tooltip"]').tooltip();
        }

        if(qb_company_id == "")
        {
            $(".import_to_qb").hide();
        }
        else
        {
            $(".import_to_qb").show();
        }
    } );

    // On each draw, loop over the `detailRows` array and show any child rows
    t.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td .details-control').trigger( 'click' );
        } );
    } );

    $("#service_type").live('change',function(){
        if($(this).val() == 7)
        {   
            $(this).parent().parent().parent().parent('form').find(".registration_address").show();
            $(this).parent().parent().parent().parent('form').find(".jurisdiction").prop('disabled', false);

            if($(this).parent().parent().parent().parent('form').find(".jurisdiction option:selected").text().toUpperCase() == "SINGAPORE")
            {
                $(this).parent().parent().parent().parent('form').find(".sg_part").show();
                $(this).parent().parent().parent().parent('form').find(".foreign_part").hide();
                $(this).parent().parent().parent().parent('form').find(".service_postal_code").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".service_street_name").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".service_building_name").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".service_unit_no1").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".service_unit_no2").prop('disabled', false);
            }
            else
            {
                $(this).parent().parent().parent().parent('form').find(".sg_part").hide();
                $(this).parent().parent().parent().parent('form').find(".foreign_part").show();
                $(this).parent().parent().parent().parent('form').find(".foreign_address_1").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".foreign_address_2").prop('disabled', false);
                $(this).parent().parent().parent().parent('form').find(".foreign_address_3").prop('disabled', false);
            }
        }
        else
        {
            $(this).parent().parent().parent().parent('form').find(".registration_address").hide();
            $(this).parent().parent().parent().parent('form').find(".jurisdiction").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_postal_code").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_street_name").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_building_name").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no1").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no2").prop('disabled', true);

            $(this).parent().parent().parent().parent('form').find(".foreign_address_1").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_2").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_3").prop('disabled', true);
        }
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_type_name").html( "" );

        if($(this).val() == 8)
        {
            $(this).parent().parent().parent().parent('form').find(".display_in_se_section").show();
            $(this).parent().parent().parent().parent('form').find("#display_in_se").attr("disabled", false);
        }
        else
        {
            $(this).parent().parent().parent().parent('form').find(".display_in_se_section").hide();
            $(this).parent().parent().parent().parent('form').find("#display_in_se").attr("disabled", true);
            $(this).parent().parent().parent().parent('form').find("#display_in_se").val("0");
        }
    });

    $("#jurisdiction").live('change',function(){
        if($(this).parent().parent().parent().parent('form').find(".jurisdiction option:selected").text().toUpperCase() == "SINGAPORE")
        {
            $(this).parent().parent().parent().parent('form').find(".sg_part").show();
            $(this).parent().parent().parent().parent('form').find(".foreign_part").hide();
            $(this).parent().parent().parent().parent('form').find(".service_postal_code").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".service_street_name").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".service_building_name").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no1").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no2").prop('disabled', false);

            $(this).parent().parent().parent().parent('form').find(".foreign_address_1").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_2").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_3").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_1").val("");
            $(this).parent().parent().parent().parent('form').find(".foreign_address_2").val("");
            $(this).parent().parent().parent().parent('form').find(".foreign_address_3").val("");
        }
        else
        {
            $(this).parent().parent().parent().parent('form').find(".sg_part").hide();
            $(this).parent().parent().parent().parent('form').find(".foreign_part").show();
            $(this).parent().parent().parent().parent('form').find(".foreign_address_1").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_2").prop('disabled', false);
            $(this).parent().parent().parent().parent('form').find(".foreign_address_3").prop('disabled', false);

            $(this).parent().parent().parent().parent('form').find(".service_postal_code").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_street_name").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_building_name").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no1").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_unit_no2").prop('disabled', true);
            $(this).parent().parent().parent().parent('form').find(".service_postal_code").val("");
            $(this).parent().parent().parent().parent('form').find(".service_street_name").val("");
            $(this).parent().parent().parent().parent('form').find(".service_building_name").val("");
            $(this).parent().parent().parent().parent('form').find(".service_unit_no1").val("");
            $(this).parent().parent().parent().parent('form').find(".service_unit_no2").val("");
        }
    });

    $("#calculate_by_quantity_rate").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_calculate_by_quantity_rate").html( "" );
    });

    $("#service_name").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_name").html( "" );
    });

    $("#invoice_description").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_invoice_description").html( "" );
    });

    $("#amount").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_amount").html( "" );
    });

    $("#unit_pricing").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_unit_pricing").html( "" );
    });

    $("#currency").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_currency").html( "" );
    });

    $("#service_postal_code").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_postal_code").html( "" );
    });

    $("#service_street_name").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_street_name").html( "" );
    });

    $("#foreign_address_1").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_foreign_address_1").html( "" );
    });

    $("#foreign_address_2").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_foreign_address_2").html( "" );
    });

    $("#service_proposal_description").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_proposal_description").html( "" );
    });

    $("#engagement_letter_list").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_engagement_letter_list").html( "" );
    });

    $("#service_proposal_letter_required").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_service_proposal_letter_required").html( "" );
    });

    $("#engagement_letter_required").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_engagement_letter_required").html( "" );
    });

    $("#display_in_se").live('change',function(){
        $(this).parent().parent().parent().parent('form').find("DIV#form_display_in_se").html( "" );
    });

    $(document).on("change",".service_proposal_letter_required",function(element) {
        element.preventDefault();

        if($(this).val() == 1)
        {
            $(this).parent().parent().parent().parent('form').find(".sp_textarea").show();
            $(this).parent().parent().parent().parent('form').find("#service_proposal_description").attr("disabled", false);
        }
        else
        {
            $(this).parent().parent().parent().parent('form').find(".sp_textarea").hide();
            $(this).parent().parent().parent().parent('form').find("#service_proposal_description").val("");
            $(this).parent().parent().parent().parent('form').find("#service_proposal_description").attr("disabled", true);
        }
    });

    $(document).on("change",".engagement_letter_required",function(element) {
        element.preventDefault();

        if($(this).val() == 1)
        {
            $(this).parent().parent().parent().parent('form').find(".el_textarea").show();
             $(this).parent().parent().parent().parent('form').find("#engagement_letter_list").attr("disabled", false);
        }
        else
        {
            $(this).parent().parent().parent().parent('form').find(".el_textarea").hide();
            $(this).parent().parent().parent().parent('form').find("#engagement_letter_list").val("0");
            $(this).parent().parent().parent().parent('form').find("#engagement_letter_list").attr("disabled", true);
        }
    });

    $(document).on("click",".approve_services",function(element){
        var frm = $(this).closest('form');

        frm.find('.service_type').prop('disabled', false);
        frm.find('.click_button_approve_or_reject').val(1);
        var frm_serialized = frm.serialize();

        var parent_index = $(this).closest('form').attr("id");
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "our_services/approve_our_service_data",
            data: frm_serialized,
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if (response.Status === 1) 
                {
                    //console.log(frm.find('div.approve_and_reject_div'));
                    //frm.find('.click_button_approve_or_reject').val(1);
                    t.row( $(".row"+parent_index) ).data(response.row_of_our_service_info[0]);
                    toastr.success("Information Updated", "Success");
                    
                    $(".row"+parent_index).find('.details-control').click();
                    var rowIndex = ".row"+parent_index;
                    if(response.row_of_our_service_info[0]['approved'] == 0)
                    {
                        var is_approve = "Not";
                    }
                    else if (response.row_of_our_service_info[0]['approved'] == 1)
                    {
                        var is_approve = "Yes";
                    }
                    $(".row"+parent_index).find('.col_approved').text(is_approve);
                }
            }
        });
    });

    $(document).on("click",".reject_services",function(element){
        var frm = $(this).closest('form');

        frm.find('.service_type').prop('disabled', false);

        var frm_serialized = frm.serialize();

        var parent_index = $(this).closest('form').attr("id");
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "our_services/reject_our_service_data",
            data: frm_serialized,
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if (response.Status === 1) 
                {
                    //frm.find('.approve_and_reject_div').hide();
                    t.row( $(".row"+parent_index) ).data(response.row_of_our_service_info[0]);
                    toastr.success("Information Updated", "Success");
                    
                    $(".row"+parent_index).find('.details-control').click();
                    var rowIndex = ".row"+parent_index;
                    if(response.row_of_our_service_info[0]['approved'] == 0)
                    {
                        var is_approve = "Not";
                    }
                    else if (response.row_of_our_service_info[0]['approved'] == 1)
                    {
                        var is_approve = "Yes";
                    }
                    $(".row"+parent_index).find('.col_approved').text(is_approve);
                }
            }
        });
    });
    //$('#our_service_datatable .save_services').on( 'click', function (element) {
    $(document).on("click",".save_services",function(element) {
        var frm = $(this).closest('form');

        frm.find('.service_type').prop('disabled', false);

        var frm_serialized = frm.serialize();

        var parent_index = $(this).closest('form').attr("id");

        $('#loadingmessage').show();
        //console.log(frm_serialized);
        $.ajax({
            type: 'POST',
            url: "our_services/save_our_service_data",
            data: frm_serialized,
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                //console.log(response.error);
                if (response.Status === 1) 
                {
                    frm.find('.service_type').prop('disabled', true);
                    $('#addRow').removeClass("fa-disabled");
                    if(response.enable_add_row)
                    {   
                        if (is_admin && !is_individual)
                        {
                            toastr.success("Information Updated", "Success");
                        }
                        else
                        {
                            toastr.warning("Service will be on hold until you have received the email notification of approval.", "Success");
                            //$('#addRow').attr('id', 'addRow');
                        }
                    }
                    else
                    {
                        toastr.success("Information Updated", "Success");
                    }

                    //console.log(response.row_of_our_service_info[0]);
                    $(".row"+parent_index).find('.details-control').click();
                    var rowIndex = ".row"+parent_index;
                    if (is_admin && !is_individual)
                    {
                        if(response.row_of_our_service_info[0]['approved'] == 0)
                        {
                            var is_approved = "Not";
                        }
                        else
                        {
                            var is_approved = "Yes";
                        }

                        if(response.row_of_our_service_info[0]['qb_item_id'] != undefined)
                        {
                            var is_imported = "Yes";
                        }
                        else
                        {
                            var is_imported = "Not";
                        }
                        //add data to the row
                        t.row( $(".row"+parent_index) ).data(response.row_of_our_service_info[0]);
                        $(".row"+parent_index).find('.col_service_type_name').text(response.row_of_our_service_info[0]['service_type_name']);
                        $(".row"+parent_index).find('.col_service_name').text(response.row_of_our_service_info[0]['service_name']);
                        $(".row"+parent_index).find('.col_amount').text(addCommas(response.row_of_our_service_info[0]['amount']));
                        $(".row"+parent_index).find('.col_quickbook').text(is_imported);
                        $(".row"+parent_index).find('.col_approved').text(is_approved);
                    }
                    else
                    {
                        //delete row
                        t.row( $(".row"+parent_index) ).remove();
                        $(".row"+parent_index).remove();
                    }
                }
                else if (response.Status === 2)
                {
                    toastr.error(response.message, response.title);
                }
                else
                {
                    toastr.error(response.message, response.title);

                    if (response.error["service_type_name"] != "")
                    {
                        var errorsServiceTypeName = '<span class="help-block">*' + response.error["service_type_name"] + '</span>';
                        frm.find("DIV#form_service_type_name").html( errorsServiceTypeName );
                        //frm.find("#service_type_name").val("");

                    }
                    else
                    {
                        var errorsServiceTypeName = ' ';
                        frm.find("DIV#form_service_type_name").html( errorsServiceTypeName );
                    }

                    if (response.error["calculate_by_quantity_rate"] != "")
                    {
                        var errorsCalculateByQuantityRate = '<span class="help-block">*' + response.error["calculate_by_quantity_rate"] + '</span>';
                        frm.find("DIV#form_calculate_by_quantity_rate").html( errorsCalculateByQuantityRate );
                        //frm.find("#service_type_name").val("");

                    }
                    else
                    {
                        var errorsCalculateByQuantityRate = ' ';
                        frm.find("DIV#form_calculate_by_quantity_rate").html( errorsCalculateByQuantityRate );
                    }

                    if (response.error["service_name"] != "")
                    {
                        var errorsServiceName = '<span class="help-block">*' + response.error["service_name"] + '</span>';
                        frm.find("DIV#form_service_name").html( errorsServiceName );
                        frm.find("#service_name").val("");

                    }
                    else
                    {
                        var errorsServiceName = ' ';
                        frm.find("DIV#form_service_name").html( errorsServiceName );
                    }

                    if (response.error["invoice_description"] != "")
                    {
                        var errorsInvoiceDescription = '<span class="help-block">*' + response.error["invoice_description"] + '</span>';
                        frm.find("DIV#form_invoice_description").html( errorsInvoiceDescription );
                        frm.find("#invoice_description").val("");

                    }
                    else
                    {
                        var errorsInvoiceDescription = ' ';
                        frm.find("DIV#form_invoice_description").html( errorsInvoiceDescription );
                    }

                    if (response.error["amount"] != "")
                    {
                        var errorsAmount = '<span class="help-block">*' + response.error["amount"] + '</span>';
                        frm.find("DIV#form_amount").html( errorsAmount );
                        frm.find("#amount").val("");

                    }
                    else
                    {
                        var errorsAmount = ' ';
                        frm.find("DIV#form_amount").html( errorsAmount );
                    }

                    if (response.error["currency"] != "")
                    {
                        var errorsCurrency = '<span class="help-block">*' + response.error["currency"] + '</span>';
                        frm.find("DIV#form_currency").html( errorsCurrency );
                        //frm.find("#unit_pricing").val("");

                    }
                    else
                    {
                        var errorsCurrency = ' ';
                        frm.find("DIV#form_currency").html( errorsCurrency );
                    }

                    if (response.error["unit_pricing"] != "")
                    {
                        var errorsUnitPricing = '<span class="help-block">*' + response.error["unit_pricing"] + '</span>';
                        frm.find("DIV#form_unit_pricing").html( errorsUnitPricing );
                        //frm.find("#unit_pricing").val("");

                    }
                    else
                    {
                        var errorsUnitPricing = ' ';
                        frm.find("DIV#form_unit_pricing").html( errorsUnitPricing );
                    }

                    if (response.error["service_postal_code"] != "")
                    {
                        var errorsServicePostalCode = '<span class="help-block">*' + response.error["service_postal_code"] + '</span>';
                        frm.find("DIV#form_service_postal_code").html( errorsServicePostalCode );
                        frm.find("#service_postal_code").val("");

                    }
                    else
                    {
                        var errorsServicePostalCode = ' ';
                        frm.find("DIV#form_service_postal_code").html( errorsServicePostalCode );
                    }

                    if (response.error["service_street_name"] != "")
                    {
                        var errorsServiceNtreetName = '<span class="help-block">*' + response.error["service_street_name"] + '</span>';
                        frm.find("DIV#form_service_street_name").html( errorsServiceNtreetName );
                        frm.find("#service_street_name").val("");

                    }
                    else
                    {
                        var errorsServiceNtreetName = ' ';
                        frm.find("DIV#form_service_street_name").html( errorsServiceNtreetName );
                    }

                    if (response.error["service_proposal_description"] != "")
                    {
                        var errorsServiceProposalDescription = '<span class="help-block">*' + response.error["service_proposal_description"] + '</span>';
                        frm.find("DIV#form_service_proposal_description").html( errorsServiceProposalDescription );
                        frm.find("#service_proposal_description").val("");

                    }
                    else
                    {
                        var errorsServiceProposalDescription = ' ';
                        frm.find("DIV#form_service_proposal_description").html( errorsServiceProposalDescription );
                    }

                    if (response.error["engagement_letter_list"] != "")
                    {
                        var errorsEngagementLetterList = '<span class="help-block">*' + response.error["engagement_letter_list"] + '</span>';
                        frm.find("DIV#form_engagement_letter_list").html( errorsEngagementLetterList );
                        frm.find("#engagement_letter_list").val("0");

                    }
                    else
                    {
                        var errorsEngagementLetterList = ' ';
                        frm.find("DIV#form_engagement_letter_list").html( errorsEngagementLetterList );
                    }

                    if (response.error["sp_letter_required_error"] != "")
                    {
                        var errorsSPLetterRequired = '<span class="help-block">*' + response.error["sp_letter_required_error"] + '</span>';
                        frm.find("DIV#form_service_proposal_letter_required").html( errorsSPLetterRequired );
                        frm.find("#service_proposal_letter_required").val("0");

                    }
                    else
                    {
                        var errorsSPLetterRequired = ' ';
                        frm.find("DIV#form_service_proposal_letter_required").html( errorsSPLetterRequired );
                    }

                    if (response.error["el_required_error"] != "")
                    {
                        var errorsELRequired = '<span class="help-block">*' + response.error["el_required_error"] + '</span>';
                        frm.find("DIV#form_engagement_letter_required").html( errorsELRequired );
                        frm.find("#engagement_letter_required").val("0");

                    }
                    else
                    {
                        var errorsELRequired = ' ';
                        frm.find("DIV#form_engagement_letter_required").html( errorsELRequired );
                    }

                    if (response.error["display_in_se"] != "")
                    {
                        var errorsDisplayInSE = '<span class="help-block">*' + response.error["display_in_se"] + '</span>';
                        frm.find("DIV#form_display_in_se").html( errorsDisplayInSE );
                        frm.find("#display_in_se").val("0");

                    }
                    else
                    {
                        var errorsDisplayInSE = ' ';
                        frm.find("DIV#form_display_in_se").html( errorsDisplayInSE );
                    }

                    if (response.error["foreign_address_1"] != "")
                    {
                        var errorsForeignAddress1 = '<span class="help-block">*' + response.error["foreign_address_1"] + '</span>';
                        frm.find("DIV#form_foreign_address_1").html( errorsForeignAddress1 );
                        frm.find("#foreign_address_1").val("");

                    }
                    else
                    {
                        var errorsForeignAddress1 = ' ';
                        frm.find("DIV#form_foreign_address_1").html( errorsForeignAddress1 );
                    }

                    if (response.error["foreign_address_2"] != "")
                    {
                        var errorsForeignAddress2 = '<span class="help-block">*' + response.error["foreign_address_2"] + '</span>';
                        frm.find("DIV#form_foreign_address_2").html( errorsForeignAddress2 );
                        frm.find("#foreign_address_2").val("");

                    }
                    else
                    {
                        var errorsForeignAddress2 = ' ';
                        frm.find("DIV#form_foreign_address_2").html( errorsForeignAddress2 );
                    }
                }
            }
        });
    });

    $(document).on("click",".delete_services",function(element) {
        var parent_index = $(this).closest('tr form').attr("id");
        $(".row"+parent_index).find('.details-control').click();
        var our_service_id = $(this).closest('tr form').find('.our_service_id').val(); 
        $.ajax({
            type: 'POST',
            url: "our_services/delete_our_service_data",
            data: {"id": $(this).closest('tr form').find('.our_service_id').val(), "user_admin_code_id": user_admin_code_id},
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                if (response.Status === 1) 
                {
                    toastr.success("Successful Deleted", "Success");
                    t.row( $(".row"+parent_index) ).remove();
                    $(".row"+parent_index).remove();

                    if(our_service_id == "")
                    {
                        $('#addRow').removeClass("fa-disabled");
                    }
                }
                else
                {
                    toastr.error("Cannot be delete. This service is use in service engagement.", "Error");
                }
            }
        });
    });

    $(document).on("click",".import_to_qb",function(element) {
        var our_service_id = $(this).closest('tr form').find('.our_service_id').val(); 
        $('#loadingmessage').show();
        $.ajax({
            type: 'POST',
            url: "our_services/get_income_account",
            data: {"id": our_service_id},
            dataType: 'json',
            success: function(response){
                $('#loadingmessage').hide();
                $("div.smartselect").remove();
                if (response.Status === 1) 
                {
                    $(".div_qb_accSelector").append('<select id="acc-selector" class="acc-selector"></select>');
                    let parentId;
                    let dataLvl;
                    let subParentId;
                    let accText;
                    let qb_acc_id = response.qb_acc_id;
                    let qb_acc_attribute = JSON.parse(response.data);
                    let qb_acc = qb_acc_attribute["QueryResponse"]["Account"];

                    for (var i = 0; i < qb_acc.length; i++)
                    {
                        let selector_lvl = 1;
                        if(qb_acc[i]["SubAccount"] == "false")
                        {
                            dataLvl = 2;
                            parentId = qb_acc[i]["Id"];
                            if(qb_acc[i]["AcctNum"] != undefined)
                            {
                                accText = qb_acc[i]["AcctNum"] + ' ' + qb_acc[i]["Name"];
                            }
                            else
                            {
                                accText = qb_acc[i]["Name"];
                            }
                            $("select.acc-selector").append($('<option>', {
                                value: qb_acc[i]["Id"],
                                text: accText
                            }));
                        }
                        else if(qb_acc[i]["SubAccount"] == "true")
                        {
                            if(parentId == qb_acc[i]["ParentRef"] || subParentId == qb_acc[i]["ParentRef"])
                            {
                                if(dataLvl != 2 && subParentId == qb_acc[i]["ParentRef"])
                                {
                                    dataLvl = dataLvl;
                                }
                                else
                                {
                                    dataLvl = 2;
                                }
                                $("select.acc-selector").append('<option value="'+qb_acc[i]["Id"]+'" data-level="'+dataLvl+'">'+qb_acc[i]["Name"]+'</option>');
                            }
                            else if (parentId != qb_acc[i]["ParentRef"])
                            {
                                dataLvl = dataLvl + 1; 
                                subParentId = qb_acc[i]["ParentRef"];
                                $("select.acc-selector").append('<option value="'+qb_acc[i]["Id"]+'" data-level="'+dataLvl+'">'+qb_acc[i]["Name"]+'</option>');
                            }
                        }
                    }
                    
                    $("select.acc-selector").val(qb_acc_id);

                    $("select#acc-selector").smartselect({
                        toolbar: false,
                        defaultView: 'expand',
                        text: {
                            selectLabel: 'Select Income Account'
                        },
                        atLeast: 1,
                        handler: {
                            atLeast: function() {
                                toastr.error("Please select at least one Income Account before you save.", "Error");
                            }
                        },
                        callback: {
                            onOptionSelected: [ assignValue ]
                        }

                    });

                    $('#modal_import_services_to_qb').modal('toggle');
                }
                else
                {
                    toastr.error(response.message, response.title);
                }
            }
        });
    });

    var assignValue = function($target, event) {
                        acc_selector_id = $target.attr('data-value');
                        return true;
                    };

    $(document).on("click","#saveImportServicesToQB",function(element) {
        var parent_index = $(".import_to_qb").closest('tr form').attr("id");
        var our_service_id = $(".import_to_qb").closest('tr form').find('.our_service_id').val(); 
        if(acc_selector_id != null)
        {
            $('#loadingmessage').show();
            $.ajax({
                type: 'POST',
                url: "our_services/import_service_to_qb",
                data: {"id": our_service_id, "acc_selector_id": acc_selector_id},
                dataType: 'json',
                success: function(response){
                    $('#loadingmessage').hide();
                    if(response.Status == 1)
                    {
                        $('#modal_import_services_to_qb').modal('toggle');
                        toastr.success(response.message, response.title);

                        $(".row"+parent_index).find('.details-control').click();
                        var rowIndex = ".row"+parent_index;
                        if(response.qb_item_id != undefined)
                        {
                            var is_import = "Yes";
                        }
                        else
                        {
                            var is_import = "Not";
                        }
                        $(".row"+parent_index).find('.col_quickbook').text(is_import);
                    } 
                    else if(response.Status == 2)
                    {
                        toastr.error(response.message, response.title);
                    }
                }
            });
        }
        else
        {
            toastr.error("Please select one Income Account before you save.", "Error");
        }
    });

    $(document).on("click",".close_form",function(element) {
        var parent_index = $(this).closest('tr form').attr("id");

        $(".row"+parent_index).find('.details-control').click();
    });

    $(document).on("keyup",".service_postal_code",function(element) {
        var register_add_row = $(this).closest('tr form');
        if($(this).val().length == 6){
            var zip = $(this).val();
            //var address = "068914";
            $.ajax({
              url:    'https://gothere.sg/maps/geo',
              dataType: 'jsonp',
              data:   {
                'output'  : 'json',
                'q'     : zip,
                'client'  : '',
                'sensor'  : false
              },
              type: 'GET',
              success: function(data) {
                //console.log(data);
                //var field = $("textarea");
                var myString = "";
                
                var status = data.Status;
                /*myString += "Status.code: " + status.code + "\n";
                myString += "Status.request: " + status.request + "\n";
                myString += "Status.name: " + status.name + "\n";*/
                
                if (status.code == 200) {         
                  for (var i = 0; i < data.Placemark.length; i++) {
                    var placemark = data.Placemark[i];
                    var status = data.Status[i];

                    register_add_row.find("#service_street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

                    if(placemark.AddressDetails.Country.AddressLine == "undefined")
                    {
                        register_add_row.find("#service_building_name").val("");
                    }
                    else
                    {
                        register_add_row.find("#service_building_name").val(placemark.AddressDetails.Country.AddressLine);
                    }
                    
                  }
                  register_add_row.find( '#form_service_postal_code' ).html('');
                  register_add_row.find( '#form_service_street_name' ).html('');
                  //field.val(myString);
                } else if (status.code == 603) {
                    register_add_row.find( '#form_service_postal_code' ).html('<span class="help-block">*No Record Found</span>');
                  //field.val("No Record Found");
                }

              },
              statusCode: {
                404: function() {
                  alert('Page not found');
                }
              },
            });
        }
        else
        {
            register_add_row.find("#service_street_name").val("");
            register_add_row.find("#service_building_name").val("");

            /*$("#street_name").attr("readonly", false);
            $("#building_name").attr("readonly", false);*/
        }
    });
});

function get_billing_info_service_category(tr, dropdown_data)
{
    $("#loadingmessage").show();

    if(dropdown_data.calculate_by_quantity_rate != undefined)
    {
        tr.find("#calculate_by_quantity_rate").val(dropdown_data.calculate_by_quantity_rate);
    }
    
    if(dropdown_data.click_button_approve_or_reject == 0)
    {
        tr.find('.approve_and_reject_div').show();
    }
    else if(dropdown_data.click_button_approve_or_reject == 1)
    {
        tr.find('.approve_and_reject_div').hide();
    }
    $.ajax({
        type: "GET",
        url: "masterclient/get_billing_info_service_category",
        dataType: "json",
        success: function(data){
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.service_type != undefined && key == dropdown_data.service_type)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#service_type").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_unit_pricing",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.unit_pricing != undefined && key == dropdown_data.unit_pricing)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#unit_pricing").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_service_proposal_letter_required",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.sp_required_id != undefined && key == dropdown_data.sp_required_id)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#service_proposal_letter_required").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_engagement_letter_required",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.el_required_id != undefined && key == dropdown_data.el_required_id)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#engagement_letter_required").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_display_in_service_engagement",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.display_in_se_id != undefined && key == dropdown_data.display_in_se_id)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#display_in_se").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_display_in_engagement_letter_list",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.engagement_letter_list_id != undefined && key == dropdown_data.engagement_letter_list_id)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#engagement_letter_list").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "masterclient/get_currency",
        dataType: "json",
        success: function(data){
            //$("#loadingmessage").hide();
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(dropdown_data.currency != undefined && key == dropdown_data.currency)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#currency").append(option);
            });
        }
    }); 

    $.ajax({
        type: "GET",
        url: "admin_setting/get_dropdown_jurisdiction_info",
        dataType: "json",
        success: function(data){
            $("#loadingmessage").hide();
            $.each(data[0]['dropdown_jurisdiction_info'], function(key, val) {
                var option = $('<option />');
                option.attr('value', val["id"]).text(val["jurisdiction"]);
                if(dropdown_data.jurisdiction_id != undefined && val["id"] == dropdown_data.jurisdiction_id)
                {
                    option.attr('selected', 'selected');
                }
                tr.find("#jurisdiction").append(option);
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "admin_setting/get_gst_category",
        dataType: "json",
        success: function(data){
            if(data.Status == 1)
            {
                var dropdown_jurisdiction_info = data[0]["dropdown_jurisdiction_info"];
                var category_info = data[0]["category_info"];
                var length = 0;
                if(dropdown_data.our_service_gst != undefined)
                {
                    var dropdown_our_service_gst = dropdown_data.our_service_gst;
                }

                if(dropdown_jurisdiction_info != false)
                {
                    for (x of dropdown_jurisdiction_info) 
                    {
                        $a = "";
                        $a += "<tr>";
                        $a += '<td><input type="hidden" name="jurisdiction_id[]" value="'+x["id"]+'"/><input type="text" name="jurisdiction[]" class="form-control jurisdiction'+length+'" value="'+x["jurisdiction"]+'" id="jurisdiction" readonly="true"/></td>';
                        $a += '<td><select class="form-control category'+length+'" style="width: 100%;" name="category[]" id="category"><option value="0">Select Category</option></select></td>';
                        $a += "</tr>";

                        tr.find("#rate_body").append($a);

                        for (y of category_info) 
                        {
                            if(y["jurisdiction_id"] == x["id"])
                            {
                                var option = $('<option />');
                                option.attr('value', y["id"]).text(y["category"] + " (" + y["rate"] + "%)");
                                if(dropdown_data.our_service_gst != undefined)
                                {
                                    for (z of dropdown_our_service_gst) 
                                    {
                                        if(x["id"] == z["jurisdiction_id"] && y["id"] == z["category_id"])
                                        {
                                            option.attr('selected', 'selected');
                                        }
                                    }
                                }
                                tr.find(".category"+length).append(option);
                            }
                        }
                        length++;
                    }
                }
            }
        }
    });

    // $.ajax({
    //     type: "GET",
    //     url: "masterclient/get_click_button_approve_or_reject",
    //     dataType: "json",
    //     success: function(data){
    //         // console.log(data['result']);
    //         //console.log(tr.find("#service_type"));
    //         //console.log(dropdown_data.unit_pricing);
    //         $("#loadingmessage").hide();
    //         $.each(data['result'], function(key, val) {
    //             var option = $('<option />');
    //             option.attr('value', key).text(val);
    //             if(dropdown_data.currency != undefined && key == dropdown_data.currency)
    //             {
    //                 option.attr('selected', 'selected');
    //             }
    //             tr.find("#currency").append(option);
    //         });
    //     }
    // });
}

// function get_unit_pricing(tr, dropdown_data)
// {
    
// }

function format (d, index) {
    //console.log(index);
    $a = '';
    $a += '<form class="our_service_form" id="'+index+'">';
    $a += '<div class="col-sm-6">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="user_admin_code_id[]" id="user_admin_code_id" value="'+user_admin_code_id+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control our_service_id" name="our_service_id[]" value="'+(d.id != undefined ? d.id : '')+'"/></div>';
    //$a += '<input type="hidden" class="form-control click_button_approve_or_reject" name="click_button_approve_or_reject[]" value="'+d.click_button_approve_or_reject+'"/>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Service Type</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control service_type" style="width: 100%;" name="service_type[]" id="service_type" '+(d.id != undefined ? "disabled" : '')+'><option value="0">Select Service Type</option></select><div id="form_service_type_name"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Calculate by Quantity/Rate<span data-toggle="tooltip" data-html="true" data-trigger="hover" data-original-title="Example: For Water/ Electric Bill" class="fa fa-info-circle errspan" style="margin-top: 2px !important; margin-left: 6px !important;"></span></label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control calculate_by_quantity_rate" style="width: 100%;" name="calculate_by_quantity_rate[]" id="calculate_by_quantity_rate"><option value="0">Select Calculate by Quantity/Rate</option><option value="1">Yes</option><option value="2">No</option></select><div id="form_calculate_by_quantity_rate"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Service Name</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" name="service_name[]" class="form-control service_name" value="'+(d.service_name != undefined ? d.service_name : '')+'" id="service_name" style="width:100%;"/><div id="form_service_name"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Invoice Description</label>';
    $a += '<div class="col-sm-8">';
    $a += '<textarea class="form-control" name="invoice_description[]" id="invoice_description" rows="5" style="width:100%">'+(d.invoice_description != undefined ? d.invoice_description : '')+'</textarea><div id="form_invoice_description"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Service Proposal Letter Required</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control service_proposal_letter_required" style="width: 100%;" name="service_proposal_letter_required[]" id="service_proposal_letter_required"><option value="0">Select Service Proposal Letter Required</option></select><div id="form_service_proposal_letter_required"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group sp_textarea" style="display:none;">';
    $a += '<label class="col-sm-4">Service Proposal Description</label>';
    $a += '<div class="col-sm-8">';
    $a += '<textarea class="form-control" name="service_proposal_description[]"  id="service_proposal_description" rows="5" style="width:100%" disabled>'+(d.service_proposal_description != undefined ? d.service_proposal_description : '')+'</textarea><div id="form_service_proposal_description"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Engagement Letter Required</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control engagement_letter_required" style="width: 100%;" name="engagement_letter_required[]" id="engagement_letter_required"><option value="0">Select Engagement Letter Required</option></select><div id="form_engagement_letter_required"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group el_textarea" style="display:none;">';
    $a += '<label class="col-sm-4">Engagement Letter List</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control engagement_letter_list" style="width: 100%;" name="engagement_letter_list[]" id="engagement_letter_list" disabled><option value="0">Select Engagement Letter</option></select><div id="form_engagement_letter_list"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Currency</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control currency" style="width: 100%;" name="currency[]" id="currency"><option value="0">Select Currency</option></select><div id="form_currency"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Amount</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" name="amount[]" pattern="[0-9,.]" class="numberdes form-control amount" value="'+(d.amount != undefined ? addCommas(d.amount) : '')+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Unit Pricing (Per)</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control unit_pricing" style="width: 100%;" name="unit_pricing[]" id="unit_pricing"><option value="0">Select Unit Pricing</option></select><div id="form_unit_pricing"></div>';
    $a += '</div>';
    $a += '</div>';
    
    // gst ignore
    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Ignore GST</label>';
    $a += '<div class="col-sm-8">';
    // $a += '<input type="checkbox" name="ignore_gst[]" value="'+d.ignore_gst+'" '+(d.ignore_gst == 1 ? 'checked' : '')+' />';
    $a += `<select class="form-control ignore_gst" style="width: 100%;" name="ignore_gst[]" id="ignore_gst">
                <option value="">Select GST Status</option>
                <option value="1" ${d.ignore_gst == 1 ? "selected" : ""}>Yes</option>
                <option value="0" ${d.ignore_gst == 0 ? "selected" : ""}>No</option>
            </select>
            <div id="ignore_gst"></div>`;
    $a += '</div>';
    $a += '</div>';
    // gst ignore

    $a += '<div class="form-group display_in_se_section" style="display:none;">';
    $a += '<label class="col-sm-4">Display in Service Engagement</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control display_in_se" style="width: 100%;" name="display_in_se[]" id="display_in_se" disabled><option value="0">Select Display in Service Engagement</option></select><div id="form_display_in_se"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="col-sm-6 registration_address" style="display: none;">';

    $a += '<div class="form-group">';
    $a += '<label class="col-sm-4">Jurisdiction</label>';
    $a += '<div class="col-sm-8">';
    $a += '<select class="form-control jurisdiction" style="width: 100%;" name="jurisdiction[]" id="jurisdiction" disabled></select>';
    $a += '</div>';
    $a += '</div>';

    $a += '<div class="form-group sg_part">';
    $a += '<label class="col-sm-4">Postal Code</label>';
    $a += '<div class="col-sm-4">';
    $a += '<input type="text" class="form-control service_postal_code" id="service_postal_code" name="service_postal_code[]" value="'+(d.postal_code != undefined ? d.postal_code : '')+'" maxlength="6" disabled><div id="form_service_postal_code"></div>';
    $a += '</div>';
    $a += '</div>';

    $a += '<div class="form-group sg_part">';
    $a += '<label class="col-sm-4">Street Name</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" class="form-control service_street_name" id="service_street_name" name="service_street_name[]" value="'+(d.street_name != undefined ? d.street_name : '')+'" disabled><div id="form_service_street_name"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group sg_part">';
    $a += '<label class="col-sm-4">Building Name</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" class="form-control service_building_name" id="service_building_name" name="service_building_name[]" value="'+(d.building_name != undefined ? d.building_name : '')+'" disabled>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group sg_part">';
    $a += '<label class="col-sm-4">Unit No</label>';
    $a += '<div class="col-sm-8">';
    $a += '<div style="width: 15%;display: inline-block; margin-right: 20px;"><input style="width: 50px;" maxlength="3" type="text" class="form-control service_unit_no1" id="service_unit_no1" name="service_unit_no1[]" value="'+(d.unit_no1 != undefined ? d.unit_no1 : '')+'" disabled></div><div style="width: 20%;display: inline-block" ><input style="width: 100%;" maxlength="10" type="text" class="form-control service_unit_no2" id="service_unit_no2" name="service_unit_no2[]" value="'+(d.unit_no2 != undefined ? d.unit_no2 : '')+'" disabled></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group foreign_part">';
    $a += '<label class="col-sm-4">Foreign Address 1</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" class="form-control foreign_address_1" id="foreign_address_1" name="foreign_address_1[]" value="'+(d.foreign_address_1 != undefined ? d.foreign_address_1 : '')+'" disabled><div id="form_foreign_address_1"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group foreign_part">';
    $a += '<label class="col-sm-4">Foreign Address 2</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" class="form-control foreign_address_2" id="foreign_address_2" name="foreign_address_2[]" value="'+(d.foreign_address_2 != undefined ? d.foreign_address_2 : '')+'" disabled><div id="form_foreign_address_2"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '<div class="form-group foreign_part">';
    $a += '<label class="col-sm-4">Foreign Address 3</label>';
    $a += '<div class="col-sm-8">';
    $a += '<input type="text" class="form-control foreign_address_3" id="foreign_address_3" name="foreign_address_3[]" value="'+(d.foreign_address_3 != undefined ? d.foreign_address_3 : '')+'" disabled><div id="form_foreign_address_3"></div>';
    $a += '</div>';
    $a += '</div>';
    $a += '</div>';

    $a += '<div class="col-md-12 gst_part" style="display:none"><div class="col-md-6"><h4>GST</h4>';
    $a += '<table class="table table-bordered table-striped table-condensed">';

    $a += '<thead>';
    $a += '<tr>';
    $a += '<th>Jurisdiction</th>';
    $a += '<th>Category</th>';
    $a += '</tr>';
    $a += '</thead>';
    $a += '<tbody id="rate_body">';
    
    $a += '</tbody>';
    $a += '</table>';
    $a += '</div></div>';

    $a += '<div class="col-md-12 text-right">';
    $a += '<button type="button" class="btn btn-primary save_services" name="save_services" id="save_services" style="margin-right: 10px;">Save</button>';
    $a += '<button type="button" class="btn btn-primary delete_services" name="delete_services" id="delete_services" style="margin-right: 10px;">Delete</button>';
    $a += '<button type="button" class="btn btn-primary import_to_qb" name="import_to_qb" id="import_to_qb" style="margin-right: 10px;">Import to Quickbook</button>';
    $a += '<a class="btn btn-primary close_form">Close</a>';
    $a += '</div>';

    if(d.click_button_approve_or_reject == 0)
    {
        $a += '<div class="col-md-12 text-right approve_and_reject_div" style="margin-top:10px;">';
        $a += '<button type="button" class="btn btn-success approve_services" name="approve_services" id="approve_services" style="margin-right: 10px;">Approve</button>';
        $a += '<button type="button" class="btn btn-danger reject_services" name="reject_services" id="reject_services">Reject</button>';
        $a += '</div>';
    }

    $a += '</form>';

    return $a;
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

//For billing template 
// $('#form_template').formValidation({
//     framework: 'bootstrap',
//     icon: {
//     },
//     fields: {
//         'invoice_description[]' : {
//             row: '.input-group',
//             validators: {
//                 notEmpty: {
//                     message: 'The Invoice Description field is required.'
//                 }
//             }
//         },
//         'amount[]' : {
//             row: '.input-group',
//             validators: {
//                 notEmpty: {
//                     message: 'The Amount field is required.'
//                 }
//             }
//         }
//     }
// });

// toastr.options = {

//   "positionClass": "toast-bottom-right"

// }

// for(var y = 0; y < template.length; y++)
// {
// 	$v = y;
// 	$a=""; 
// 	$a += '<div class="tr editing" method="post" name="form'+$v+'" id="form'+$v+'" num="'+$v+'">';
// 	$a += '<div class="hidden"><input type="text" class="form-control" name="id[]" value="'+template[y]["id"]+'"/></div>';
// 	// $a += '<div class="td" style="width:250px;"><div class="input-group"><input type="text" name="service[]" class="form-control service" value="'+template[y]["service_name"]+'" id="service" style="width:250px" readOnly/></div></div>';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="service_id[]" class="form-control service_id" value="'+template[y]["service_id"]+'" id="service_id" style="width:100%;"/><div id="form_service_id"></div></div></div>';
//     $a += '<div class="td" style="width:250px;"><div class=""><select class="form-control" style="text-align:right;width: 100%;" name="service['+$v+']" id="service" onchange="checkService(this);"><option value="0">Select Service</option></select></div></div>';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="service_name[]" class="form-control service_name" value="'+template[y]["service_name"]+'" id="service_name" style="width:100%;text-align:right;"/><div id="form_service_name"></div></div></div>';
//     $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description[]"  id="invoice_description" rows="5" style="width:250px">'+template[y]["invoice_description"]+'</textarea></div></div>';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="amount[]" pattern="[0-9,.]" class="numberdes form-control amount" value="'+addCommas(template[y]["amount"])+'" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
// 	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
// 	$a += '</div>';

// 	$("#body_template_info").prepend($a); 
// 	//console.log(template);
// 	!function ($v) {

//         $.ajax({
//             type: "POST",
//             url: "masterclient/get_billing_service",
//             data: {"company_code": company_code, "service": template[y]["service"]},
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 $("#form"+$v+" #service").find("option:eq(0)").html("Select Service");
//                 if(data.tp == 1){
//                     var category_description = '';
//                     var optgroup = '';
//                     for(var t = 0; t < data.result.length; t++)
//                     {
//                         if(category_description != data.result[t]['category_description'])
//                         {
//                             if(optgroup != '')
//                             {
//                                 $("#form"+$v+" #service").append(optgroup);
//                             }
//                             optgroup = $('<optgroup label="' + data.result[t]['category_description'] + '" />');
//                         }

//                         var option = $('<option />');
//                         option.attr('value', data.result[t]['id']).text(data.result[t]['service']).appendTo(optgroup);

//                         if(data.selected_service != null && data.result[t]['id'] == data.selected_service)
//                         {
//                             option.attr('selected', 'selected');
//                         }

//                         category_description = data.result[t]['category_description'];
//                     }
//                     $("#form"+$v+" #service").append(optgroup);

//                     $("#form"+$v+" #service option").filter(function()
//                     {
//                         return $.inArray($(this).val(),data.selected_query)>-1;
//                     }).attr("disabled","disabled");  

//                     $('select[name="service['+$v+']"] option').filter(function()
//                     {
//                         return $(this).val() === data.selected_service;
//                     }).attr("disabled", false);
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     } ($v);
// }

// if(template)
// {
//     $count_template_info = template.length + 1;
// }
// else
// {
//     $count_template_info = 0;
// }

// $(document).on('click',"#billing_info_Add",function() {
// 	$a=""; 
// 	$a += '<div class="tr editing" method="post" name="form'+$count_template_info+'" id="form'+$count_template_info+'" num="'+$count_template_info+'">';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="service_id['+$count_template_info+']" class="form-control service_id" value="" id="service_id" style="width:100%;"/><div id="form_service_id"></div></div></div>';
//     $a += '<div class="td" style="width:250px;"><div class=""><select class="form-control" style="text-align:right;width: 100%;" name="service['+$count_template_info+']" id="service" onchange="checkService(this);"><option value="0" >Select Service</option></select></div></div>';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="service_name['+$count_template_info+']" class="form-control service_name" value="" id="service_name" style="width:100%;text-align:right;"/><div id="form_service_name"></div></div></div>';
//     $a += '<div class="td"><div class="input-group"><textarea class="form-control" name="invoice_description['+$count_template_info+']"  id="invoice_description" rows="5" style="width:350px"></textarea></div></div>';
// 	$a += '<div class="td"><div class="input-group"><input type="text" name="amount['+$count_template_info+']" pattern="[0-9,.]" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/><div id="form_amount"></div></div></div>';
// 	$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
// 	$a += '</div>';
//     $a += '<div class="tr" method="post" name="form'+$count_template_info+'" id="form'+$count_template_info+'" num="'+$count_template_info+'">';
//     $a += '<div style="width:100%">element1</div>';
//     $a += '</div>';

// 	$("#body_template_info").prepend($a); 

// 	!function ($count_template_info) {

//         $.ajax({
//             type: "GET",
//             url: "masterclient/get_billing_service",
//             dataType: "json",
//             success: function(data){
//                 //console.log(data);
//                 $("#form"+$count_template_info+" #service").find("option:eq(0)").html("Select Service");
//                 if(data.tp == 1){
//                     var category_description = '';
//                     var optgroup = '';
//                     for(var t = 0; t < data.result.length; t++)
//                     {
//                         if(category_description != data.result[t]['category_description'])
//                         {
//                             if(optgroup != '')
//                             {
//                                 $("#form"+$count_template_info+" #service").append(optgroup);
//                             }
//                             optgroup = $('<optgroup label="' + data.result[t]['category_description'] + '" />');
//                         }

//                         var option = $('<option />');
//                         option.attr('value', data.result[t]['id']).text(data.result[t]['service']).appendTo(optgroup);

//                         if(data.selected_service != null && data.result[t]['id'] == data.selected_service)
//                         {
//                             option.attr('selected', 'selected');
//                         }

//                         category_description = data.result[t]['category_description'];
//                     }
//                     $("#form"+$count_template_info+" #service").append(optgroup);

//                     var arr = $.map
//                     (
//                         $("select#service option:selected"), function(n)
//                         {
//                             return n.value;
//                         }
//                     );

//                     $('select[name="service['+$count_template_info+']"] option').filter(function()
//                     {
//                         return $.inArray($(this).val(),arr)>-1;
//                      }).attr("disabled","disabled"); 
//                 }
//                 else{
//                     alert(data.msg);
//                 }  
//             }               
//         });
//     } ($count_template_info);

//     $('#form_template').formValidation('addField', 'invoice_description['+$count_template_info+']', invoice_description);
//     $('#form_template').formValidation('addField', 'amount['+$count_template_info+']', amount);

//     $count_template_info++;
// });

//     $(document).on("submit", "#form_template", function(e){
//     e.preventDefault();
//     var $form = $(e.target);
        
//     // and the FormValidation instance
//     var fv = $form.data('formValidation');
//     console.log(fv);
//     // Get the first invalid field
//     var $invalidFields = fv.getInvalidFields().eq(0);
//     // Get the tab that contains the first invalid field

//     console.log($invalidFields);
//     var $tabPane     = $invalidFields.parents();
//     var valid_setup = fv.isValidContainer($tabPane);

//     fv.disableSubmitButtons(false);

//     if(valid_setup)
//     {
//         $('#loadingmessage').show();
//         $.ajax({
//             type: 'POST',
//             url: "billings/save_template",
//             data: $form.serialize(),
//             dataType: 'json',
//             success: function(response){
//                 $('#loadingmessage').hide();
//                 //console.log(response.error);
//                 if (response.Status === 1) 
//                 {
//                     toastr.success("Information Updated", "Success");//contact, title
//                 }
//             }
//         });
//     }
//     else
//     {
//         toastr.error("Please complete all required field", "Error");
//     }
// });

// $(document).on('click',"#save_template",function(e){
//     $("#form_template").submit();
// });

// function delete_billing_info(element)
// {
//     var tr = jQuery(element).parent().parent();

//     //var client_billing_info_id = tr.find('input[name="client_billing_info_id[]"]').val();

//     toastr.success("Updated Information", "Success");
//     tr.remove();

//     $("select#service option").attr("disabled",false); //enable everything
         
//      //collect the values from selected;
//     var arr = $.map
//     (
//         $("select#service option:selected"), function(n)
//         {
//             return n.value;
//         }
//     );

//     $("select#service").each(function() {

//         var other_num = $(this).parent().parent().parent().attr("num");

//         // console.log($(this).parent().parent().parent());
//         console.log($('select[name="service['+other_num+']"]').val());
//         var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();

//          $('select[name="service['+other_num+']"] option').filter(function()
//         {
//             return $.inArray($(this).val(),arr)>-1;
//         }).attr("disabled","disabled"); 

//         $('select[name="service['+other_num+']"] option').filter(function()
//         {
//             return $(this).val() === selected_dropdown_value;
//         }).attr("disabled", false);

//     });
// }

// $tab_aktif ="ourFirm";

// if($tab_aktif == "ourFirm" || $tab_aktif == "bankInfo")
// {
//     //console.log($("#billing_footer_button"));
//     $("#billing_footer_button").hide();
// }
// else
// {
//     $("#billing_footer_button").show();
// }

// $(document).on('click',".our_firm_check_stat",function() {
//         $tab_aktif = $(this).data("information");

//         if($tab_aktif == "ourFirm" || $tab_aktif == "bankInfo")
//         {
//             //console.log($("#billing_footer_button"));
//             $("#billing_footer_button").hide();
//         }
//         else
//         {
//             $("#billing_footer_button").show();
//         }

// });