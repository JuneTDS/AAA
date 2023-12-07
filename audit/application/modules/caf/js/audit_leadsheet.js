$(".print_icon").hide();
$(".access_icon").hide();

$(".save_icon").addClass("disable");
document.getElementById("save_icon").src = base_url + "img/save-disable.png";

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
    hide_show_leadsheet(display_leadsheet);
    hide_show_documentation(display_documention);
});

 var base_data = {
    "data": leadsheet_documentation
};

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();
    // var form = $('#form_balance_sheet').serialize();

    // //get table data to be converted into a HTMLTable
    // const tableData = getTableArray({}, null, null, $$("awp").$$("cells"));
    // //tranform the data into an actual table
    // const table = getTableHTML(tableData, {}, $$("awp").$$("cells"));
    var styles = ((typeof leadsheet_documentation.data !== "undefined") ? leadsheet_documentation.data[0]['content']['styles'] : '');

    // const c = webix.copy($$("awp").config);
    // //store the data added via user input
    // const d = $$("awp").serialize();

    // //temporarily change rowCount, columnCount to 1
    // webix.ui(webix.extend({rowCount: 1, columnCount: 1}, c), $$("awp"));
    // //restore data
    // $$("awp").parse(d);

    // //get table data to be converted into a HTMLTable
    // const tableData = getTableArray({}, null, null, $$("awp").$$("cells"));
    // //tranform the data into an actual table
    // const table = getTableHTML(tableData, {}, $$("awp").$$("cells"));

    // //attach HTML container to the existing template component
    // $$("tpl").setContent(table);

    //change rowCount, columnCount to their original values
    // webix.ui(webix.extend({rowCount: 50, columnCount: 20}, c), $$("awp"));
    //restore data
    // $$("awp").parse(d);

    const c = webix.copy($$("awp").config);
    //store the data added via user input
    const d = $$("awp").serialize();

    //temporarily change rowCount, columnCount to 1
    webix.ui(webix.extend({rowCount: 1, columnCount: 1}, c), $$("awp"));
    //restore data
    $$("awp").parse(d);

    //get table data to be converted into a HTMLTable
    const tableData = getTableArray({}, null, null, $$("awp").$$("cells"));
    //tranform the data into an actual table
    const table = getTableHTML(tableData, {}, $$("awp").$$("cells"));

    //attach HTML container to the existing template component
    // $("#testing_table").append(table);
    // console.log(table);

    //change rowCount, columnCount to their original values
    webix.ui(webix.extend({rowCount: 50, columnCount: 20}, c), $$("awp"));
    //restore data
    $$("awp").parse(d);



    // console.log(tableData);
    // var all_style = [];
    // var styleSheetList = document.getElementsByTagName('style');
    // var first_rule = styleSheetList[0].cssRules[0];
    // for(var i=0; i<styleSheetList.length; i++)
    // {
    //     all_style.push(styleSheetList[i].innerHTML);
    // }
    // console.log(table.outerHTML);

    // var used = [];
    // var elements = null;

    //get all elements
    // if (typeof $(table.outerHTML).getElementsByTagName != 'undefined') {
    //     elements = document.getElementsByTagName('*');
    // }

    // if (!elements || !elements.length) {
    //     elements = document.all; // ie5
    // }

    // //loop over all element
    // for (var i = 0; i < elements.length; i++){

    //     //loop over element's classes
    //     var classes = elements[i].className.split(' ');
    //     for (var j = 0; j < classes.length; j++) {

    //         var name = classes[j];

    //         //add if not exists
    //         if (name.length > 0 && used.indexOf(name) === -1) {
    //             used.push(name);
    //         }
    //     }
    // }

    // console.log(leadsheet_documentation.data[0]['content']);s

    $.ajax({
        type: 'post',
        url: export_audit_leadsheet_pdf_url,
        dataType: 'json',
        data: {"doc_table": table.outerHTML, "styles": styles},
        success: function (response) 
        {
            $('#loadingMessage').hide();
            window.open(
              response.link,
              '_blank' // <- This is what makes it open in a new window.
            );
        }
    });

});

// console.log(leadsheet_documentation.data[0].content);

webix.ready(function(){
    //object constructor
    webix.ui({
        container: "leadsheet_doc",
        width: 1250,
        view:"tabview",
        id:"awp",

        cells:[
          {
            header:"Leadsheet Documentation",
            body:{
              view:"spreadsheet",
              //rowCount: 1,

              id:"awp",
              data:((leadsheet_documentation.data != undefined) ? leadsheet_documentation.data[0].content : ''),
              bottombar:false,
              save: {
                    all:save_leadsheet_doc_url
                },
              buttons: {
                    "Undo/Redo": ["undo","redo"],
                    "font": ["font-family","font-size","font-weight","font-style",
                    "text-decoration","color","background","borders"],
                    "align": ["text-align","vertical-align","wrap", "span"],
                    "format": ["format"],
                    "$Custom" : [{
                                    view: "button", name: "my-button", width : 40,  label: "<span class='webix_ssheet_button_icon icon_linknote'></span>",
                                    tooltip: "Link FS note disclosure", click: function(){link_to_fs()}
                                }]
                }
            }
          }
        ]
        //loaded data object
        // toolbar: "full",
        // data: ((leadsheet_documentation.data != undefined) ? leadsheet_documentation.data[0].content : ''),
        // save: {
        //     all:save_leadsheet_doc_url
        // },
        // buttons: {
        //     "Undo/Redo": ["undo","redo"],
        //     "font": ["font-family","font-size","font-weight","font-style",
        //     "text-decoration","color","background","borders"],
        //     "align": ["text-align","vertical-align","wrap", "span"],
        //     "format": ["format"],
        //     "$Custom" : [{
        //                     view: "button", name: "my-button", width : 40,  label: "<span class='webix_ssheet_button_icon icon_linknote'></span>",
        //                     tooltip: "Link FS note disclosure", click: function(){link_to_fs()}
        //                 }]
        // }
        // save: save_awp_url
    });

    // $$("awp").lockCell(2, 3, true);
   	// webix.toPDF($$("awp"), { styles:true });

});

// $(document).on('click',".save_icon",function(e) 
// {
//     $('#loadingMessage').show();
//     $(".save_icon").addClass("disable");
//     location.reload();
      
// });




function generate_table()
{
    const c = webix.copy($$("awp").config);

    console.log(c);
    //store the data added via user input
    const d = $$("awp").serialize(true);

    console.log(d);

    //temporarily change rowCount, columnCount to 1
    webix.ui(webix.extend({rowCount: 1, columnCount: 1}, c), $$("awp"));
    //restore data
    $$("awp").parse(d);

    //get table data to be converted into a HTMLTable
    const tableData = getTableArray({}, null, null, $$("awp").$$("cells"));
    //tranform the data into an actual table
    const table = getTableHTML(tableData, {}, $$("awp").$$("cells"));

    // //attach HTML container to the existing template component
    // $$("tpl").setContent(table);

    //change rowCount, columnCount to their original values
    webix.ui(webix.extend({rowCount: 50, columnCount: 20}, c), $$("awp"));
    //restore data
    $$("awp").parse(d);
    // console.log(style = window.getComputedStyle(element));
    $("#testing_table").append(table);
}

function getTableArray(options, base, start, view){
    options.xCorrection = options.xCorrection || 1;

    var columns = view.config.columns;
    var sel = view.getSelectedId(true);
    var maxWidth = Infinity;

    
    var totalWidth = 0;
    var rightRestriction = 0;
    var bottomRestriction = 0;
    var tableArray = [];
    var newTableStart = 0;
    var widths = [];
    var colwidth = [];


    start = start || (0 + options.xCorrection);
    base = base || [];

    // console.log(columns);
    // console.log(view.config);
    view.eachColumn(webix.bind(function(column){
     
        if(columns[column])
        {
            totalWidth += columns[column].width;
            colwidth[column] = columns[column].width;
        }

        
        // console.log(columns[column]);
        // totalWidth += columns[column].width;
        
    }, view));

    // console.log(colwidth)

    view.eachRow(webix.bind(function(row){
        var width = 0;
        var rowItem = view.getItem(row);
        var rowIndex = view.getIndexById(row);

        var colrow = [];
        var datarow = false;

        for(var c=start; c<columns.length; c++){
            var column = columns[c].id;
            var colIndex = view.getColumnIndex(column)-start;
            var calculatedSpanWidth  = 0;

            if(columns[c]){
                width += columns[c].width;
                if(rowIndex === 0)
                    widths.push(columns[c].width);

                if(width > maxWidth && c>start)
                { // 'c>start' ensures that a single long column will have to fit the page
                    newTableStart = c; break; 
                }
        
                var span;
                if(view.getSpan)
                    span = view.getSpan(row, column);

                //check span from previous table
                if(span && view.getColumnIndex(column) === start)
                {
                    var spanStart = view.getColumnIndex(span[1]);
                    if(spanStart < start){
                        span[2] = span[2] - (start-spanStart);
                        span[4] = span[4] ? span[4] : (rowItem[span[1]] ? view.getText(row, span[1]) : null);
                        span[1] = column;
                    }
                }

                if(!span || (span && span[0] == row && span[1] == column))
                {
                    var cellValue = span && span[4] ? span[4] : (view.config.columns[column] ? view.getText(row, column) : "");
                    var className = view.getCss(row, column)+" "+(columns[c].css || "")+(span? (" webix_dtable_span "+ (span[5] || "")):"" );
                    
                    //calculate span width
                    if(span)
                    {
                        var startSpan = parseInt(span[1]);
                        var endSpan   = parseInt(span[1]) + span[2];

                        for(var i=startSpan; i<endSpan; i++)
                        {
                            // console.log(colwidth[i]);
                            calculatedSpanWidth += colwidth[i];
                        }

                    }

                    var style  = {
                        height:span && span[3] > 1? "auto" : (((rowItem.$height || view.config.rowHeight)/2) + "px"),
                        width: span && span[2] > 1? (calculatedSpanWidth/totalWidth)*100 + "%" : (columns[c].width/totalWidth)*100 + "%"
                    };

                    // var style  = {
                    //     height:span && span[3] > 1? "auto": (((rowItem.$height || view.config.rowHeight)/2) + "px")
                    // };
                
                    colrow.push({
                        txt: cellValue, className: className, style: style,
                        span: (span ? {colspan:span[2], spanStart:view.getColumnIndex(span[1]), rowspan:span[3]}:null)
                    });

                    if (cellValue || cellValue===0) 
                    {
                        rightRestriction = Math.max(colIndex+1, rightRestriction);
                        bottomRestriction = Math.max(rowIndex+1, bottomRestriction);
                    }
                    datarow = datarow || !!cellValue;
                }
                else if(span)
                {
                    colrow.push({$inspan:true});
                    rightRestriction = Math.max(colIndex+1, rightRestriction);
                    bottomRestriction = Math.max(rowIndex+1, bottomRestriction);
                }
            }
        }

        if(!options.skiprows || datarow)
            tableArray.push(colrow);
    }, view));

    if(bottomRestriction && rightRestriction){
        if(options.trim){
            tableArray.length = bottomRestriction;
            tableArray = tableArray.map(function(item){
                for(var i = item.length-1; i>=0; i--){
                    if(item[i].span && item[i].span.colspan){
                        item[i].span.colspan = Math.min(item[i].span.colspan, item.length-i);
                        break;
                    }
                }
                item.length = rightRestriction;
                return item;
            });
        }
        base.push(tableArray);
    }

    if(newTableStart) 
        getTableArray(options, base, newTableStart);
    
    return base;
}

function getTableHTML(tableData, options, view){

    var container = webix.html.create("div");

    tableData.forEach(webix.bind(function(table, i){

      var tableHTML = webix.html.create("table", {
        "class":"webix_view webix_dtable",
        "style":"border-collapse:collapse",
        "id":view.$view.getAttribute("id")
      });

      table.forEach(function(row){
        var tr = webix.html.create("tr");

        row.forEach(function(cell){
          if(!cell.$inspan){
            var td = webix.html.create("td"); 

            td.innerHTML = cell.txt;
            check_class  = cell.className;
            check_class  = check_class.replace(" webix_dtable_span", "");
            td.className = check_class;

            // console.log(cell.className)

            for(var key in cell.style){
              td.style[key] = cell.style[key];
              // console.log(cell.style)
            }

            if(cell.span){ 
              td.colSpan = cell.span.colspan;
              td.rowSpan = cell.span.rowspan;
            }
            tr.appendChild(td); 
          }

        });
        tableHTML.appendChild(tr);
      });
      container.appendChild(tableHTML);

      if(i+1 < tableData.length){
        var br = create("DIV", {"class":"webix_print_pagebreak"});
        container.appendChild(br);
      }

    }, this));

    return container;
}

function link_to_fs()
{
    var selected_range = $$("awp").getSelectedRange();

    alert(selected_range);
}

function change_setup(element)
{
    var current_flag = 1;
    var current_field = "";

    if ($(element).is(':checked')) 
    {
        current_flag = 1;

    }
    else
    {
        current_flag = 0;
    }

    if($(element).hasClass("leadsheet_flag"))
    {
        current_field = "leadsheet_flag";
        hide_show_leadsheet(current_flag);
    }
    else if($(element).hasClass("documentation_flag"))
    {
        current_field = "documentation_flag";
        hide_show_documentation(current_flag);
    }

    $.ajax({
        type: 'post',
        url: update_leadsheet_setup_flag_url,
        dataType: 'json',
        data: {"caf_id": $(element).val(), "field": current_field, "flag": current_flag},
        success: function (response) 
        {
            $('#loadingMessage').hide();
    
        }
    });
}


function hide_show_leadsheet(flag)
{
    if(flag)
    {
        $(".leadsheet_table").show();
    }
    else
    {
        $(".leadsheet_table").hide();
    }
}

function hide_show_documentation(flag)
{
    if(flag)
    {
        $(".leadsheet_doc_tr").show();
    }
    else
    {
        $(".leadsheet_doc_tr").hide();
    }
}
