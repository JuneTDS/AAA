$(".paf_rights").select2();

// console.log(caf_list);


$(document).on('click','.toggle_morepic',function (){
	$("#pic_table tr").remove()
	var id = $(this).data('id');
	var caf_pic;
	var client_name;
	var table = "";
	// console.log(id);
	for(var i = 0; i < caf_list.length; i++)
	{
		if(caf_list[i]["id"] == id)
		{
			caf_pic = JSON.parse(caf_list[i].PIC);
			client_name = caf_list[i].client_name;
		}
	}
	var partner, manager, lead, assistant;

	partner = caf_pic.partner;
	manager = caf_pic.manager;
	lead = caf_pic.leader;
	assistant = caf_pic.assistant.toString();

	table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Partner</p></th><td style="width:60%"><p id="name">'+partner+'</p></td></tr>';
	table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Manager/Supervisor</p></th><td style="width:60%"><p id="name">'+manager+'</p></td></tr>';
	table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Team Lead</p></th><td style="width:60%"><p id="name">'+lead+'</p></td></tr>';
	table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Assistant(s)</p></th><td style="width:60%"><p id="name">'+assistant+'</p></td></tr>';
	// console.log(caf_pic);
	$(table).appendTo('#pic_table');

	var modal = $("#more_pic_modal");
	modal.find('.panel-title').text(client_name.toUpperCase() + ' - Team');


	$("#more_pic_modal").modal("show");
});

