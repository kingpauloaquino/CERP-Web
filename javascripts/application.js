var host = "http://"+ location.hostname +"/cerp";
var request_data = null;

$(function() {

  $('#signin-form').core_position();
  $('a.show-submenu').show_submenu();
  $('#menu-profile').show_account_submenu();
  
  $('.currency').currency();
  $('.text-currency').currency_format();
  $('.text-date').date_format();
  $('.date-pick').date_pick();
  $('.month_year_pick').month_year_pick();
  $('.date-pick-week').date_pick_restrict(null, '');
  $('.date-pick-thursday').date_pick_restrict(null, 'thursday');
  $('.date-pick-friday').date_pick_restrict(null, 'friday');
  $('.date-string').format_date_string();
  $('.ctrl-date-string').format_ctrl_date_string();
  $('.redirect-to').redirect_to();
  $('.btn-download').download(); 
  
  $('.numbers').digits();
  $('.numeric').numeric_only();
  $('.decimal').decimal_only();
  $('.tooltip').tooltip();
  
  
  //$('.to-numeric').to_numeric();
  
  
  // $(".dot-loader").Loadingdotdotdot({
    // "speed": 500,
    // "maxDots": 4,
    // "word": "Loading Records "
  // });
})

/* MOVE ELEMENT IN MIDDLE - CENTER OF A SCREEN */
$.fn.core_position = function() {
  $(this).css('top', (($(document).height() - $(this).height()) - 100) / 2);
  $(this).css('left', ($(document).width() - $(this).width()) / 2);
}

// $.fn.show_submenu = function() {
//   
  // $(this).hover(function() {
		// hide_menus();
  	// $(".main-sub-menu").hide();
  	// var menu = $(this).attr('alt');
		// $(menu).toggle('fast');
		// return false;
	// }, function(){
// 		
	// });
// }

$.fn.show_submenu = function() {
  
  $(this).click(function() {
	hide_menus();
  	$(".main-sub-menu").hide();
  	var menu = $(this).attr('alt');
  	
		$(menu).toggle('fast');
		return false;
	});
}

function hide_menus() {
	$("body").click(function() {
		$(".main-sub-menu").hide("fast");
		$(".profile-sub-menu").hide("fast");
	});
	$(".main-sub-menu").click(function(e) {
		e.stopPropagation();
	});
	$(".profile-sub-menu").click(function(e) {
		e.stopPropagation();
	});
	$(".main-sub-menu").hide("fast");
	$(".profile-sub-menu").hide("fast");
}

$.fn.show_account_submenu = function() {
  
  $(this).click(function() {
	hide_menus();
  	$(".profile-sub-menu").toggle('fast');
		return false;
	});
	
  // $(this).click(function(e) {
    // e.preventDefault();
//   	
  	// $('.profile-sub-menu').toggleClass('block');
  // });
}

$.fn.tooltip = function() {
  $(this).live('click', function(e) {
    e.preventDefault();
  });
}

$.fn.redirect_to = function() {
  $(this).live('click', function(e) {
    e.preventDefault();
    window.location = $(this).attr('rel');
  });
}

$.fn.download = function() {
  $(this).live('click', function(e) {
    e.preventDefault();
    window.open($(this).attr('rel'));
  });
}

$.fn.currency = function() {
  this.live('blur', function() {
    $(this).formatCurrency({region: "en-PH"});
  })
}

$.fn.currency_format = function(amount) {
  if(typeof(amount) == "undefined") amount = 0.00;
  $(this).attr('value', amount).formatCurrency({region: "en-PH"});
}

$.fn.date_format = function(format) {
  if(typeof(format) == "undefined") format = "mm/dd/yyyy";
  $(this).attr('placeholder', format);
}

$.fn.date_pick = function(format) { 
	format = format || 'MM dd, yy';
  $(this).datepicker({
		inline: true, dateFormat: format
	});
}

$.fn.date_pick_restrict = function(format, restrict) {
	format = format || 'MM dd, yy';
  $(this).datepicker({
		inline: true, dateFormat: format,
		beforeShowDay: function(date) {
        var day = date.getDay();
        switch(restrict){
        	case 'thursday':
    				return [(day != 1 && day != 2 && day != 3 && day != 5 && day != 6 && day != 0 )];
        	case 'friday':
    				return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 6 && day != 0 )];
        	case '':
        		return [(day != 6 && day != 0 )];
        }
        
    }
	});
}

$.fn.month_year_pick = function(format) { 
	format = format || 'MM dd, yy';
	$(this).datepicker({
      dateFormat: 'MM yy',
      changeMonth: true,
      changeYear: true,
      showButtonPanel: true,

      onClose: function(dateText, inst) {
          var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
          var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
          $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1))); 
      }
  });

  $(this).focus(function () {
      $(".ui-datepicker-calendar").hide();
      $("#ui-datepicker-div").position({
          my: "center top",
          at: "center bottom",
          of: $(this)
      });
  });
}

$.fn.format_ctrl_date_string = function(format) {
	format = format || 'MM dd, yy';
	if($(this).val() != '') {
		$(this).val($.datepicker.formatDate(format, new Date($(this).val()))); 
	} 
}

$.fn.format_date_string = function(ctrl, format) {
	format = format || 'MM dd, yy';
	//var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "August", "Sep", "Oct", "Nov", "Dec" ];
	//var date = new Date($(this).val());
	//$(this).val(monthNames[date.getMonth()] + );
	console.log($(this).text());
	if(ctrl) {
		if($(this).val() != '') {
			$(this).val($.datepicker.formatDate(format, new Date($(this).val()))); 
		} 
	} else {
		if($(this).html() != '') {
			$(this).html($.datepicker.formatDate(format, new Date($(this).html()))); 
		} 
	}
}

$.fn.digits = function(){ 
    return this.each(function(){ 
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
        $(this).val( $(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
    })
}

// $.fn.to_numeric = function(){ 
    // return parseInt($(this).replace(/,/g, ''), 10);
// }

$.fn.numeric_only = function() {
	$(this).keydown(function(event) {
     // Allow: backspace, delete, tab, escape, and enter
     if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
          // Allow: Ctrl+A
         (event.keyCode == 65 && event.ctrlKey === true) || 
          // Allow: home, end, left, right
         (event.keyCode >= 35 && event.keyCode <= 39)
        ) {
              // let it happen, don't do anything
              return;
     } else {
         // Ensure that it is a number and stop the keypress
           if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
               event.preventDefault(); 
           }   
       }
   });
 }

$.fn.decimal_only = function() {
   $(this).keydown(function(event) {
      // Allow: backspace, delete, tab, escape, and enter
      if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
           // Allow: Ctrl+A
          (event.keyCode == 65 && event.ctrlKey === true) || 
           // Allow: home, end, left, right
          (event.keyCode >= 35 && event.keyCode <= 39)
         ) {
               // let it happen, don't do anything
               return;
      } else 
      if (event.keyCode == 190) {  // period
          if ($(this).val().indexOf('.') !== -1) // period already exists
              event.preventDefault();
          else
              return;
      } else {
          // Ensure that it is a number and stop the keypress
          if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
              event.preventDefault(); 
          }   
      }
  });
}

$.fn.is_existing = function(ptable, pcol, pjoin, pcond) {
	$(this).val($(this).val().toUpperCase());
	var stat = $(this).attr('notice');
	if(check_unique(ptable, pcol, pjoin, pcond).trim() === 'true') {
		$('#'+stat).html('*');
		return true;
	} else {
		$('#'+stat).html(''); 
		return false;
	}
}

function check_unique(ptable, pcol, pjoin, pcond){
	var result; 
	$.ajax({
			type: "POST",
			url: "../include/is-existing.php",
			data: {  
							table: 			ptable,
							columns: 		pcol,
							joins: 			pjoin,
							conditions: pcond
						},
			cache: false,
			dataType : "text",
      async:false,
			success: function(data) {
				result = data;
			}
		});
		return result;
}


// $.fn.search = function() {  
	// $(document).keypress(function(e) {
    // if(e.which == 13) {
// 
      // var keyword = $('#keyword').val();
	    // //args['params'] = keyword;
	    // //alert('test');
	    // //grid_population(table, args);
    // }
	// });
// }


// ====================================
// GRIDVIEW
// ====================================
$.fn.grid = function(args) {
  // $(this).ready(function() {
  var table = $(this);
  
  // DATA SORTING
  table.find('thead a.sort').bind('click', function(e) { 
    e.preventDefault();
    var active	= ($(this).hasClass('up') == true) ? 'active' : 'active up';
    var column	= $(this).attr('column') || $(this).text();
    var sort	= (active == "active") ? 'DESC' : 'ASC';
    
    args['order']	= column;
    args['sort']		= sort;

    grid_population(table, args);
    
    table.find('thead a.sort').removeClass('active up');
    $(this).addClass(active);
  });
  
  if(args['searchable'] == true) {
		$('.search').keyup(function(e) { 
	    //if(e.which == 13) { // enter key
	    	args['page'] = 1;
		    //args['params'] = $('.keyword').val();
				args['params'] = $(this).children('.keyword').val();
		    
		    grid_population(table, args);
	    //}
		});
 	}
  args['params'] = $('.keyword').val();	
  grid_population(table, args);
  // })
}

function grid_population(table, args) {
  var tbody			= table.find('tbody');
  var min_height	= 500;
  
  table.find('.chk-all').toggle_check_items(table);
  // if($(this).height() < min_height) $(this).height(min_height);
  
  if(typeof(args['url']) == "undefined") return false;
  tbody.html('<tr class="empty"><td colspan="99"><h3 class="dot-loader">Loading Records ....</h3></td></tr>');

  args['page'] = args['page'] || 1;
  args['limit'] = args['limit'] || 15;
  args['order'] = args['order'] || "";
  args['sort'] = args['sort'] || 'ASC';
	var params = typeof args['params'] == "undefined" ? "" : args['params'];
	
	var opt = args['url'].indexOf("?") !== -1 ? "&" : "?";
	//alert(params);
  // DATA POPULATION

  $.ajax({
    url: host + args['url'] + opt + 'page='+ args['page'] +'&limit='+ args['limit'] +'&order='+ args['order'] +'&sort='+ args['sort'] +'&params='+ params,
    dataType: "json",
    // async: false, // set as synchronous for table total quantities 
    data: args['data'] || null,
    success: function(data) {
      // Remove Loader
      tbody.empty();
      //alert(data['test']);
      var totalItems = data['total'] || 0;

      // Populate Grid Rows
      // console.log(data[args['data_key']]);
      $.each(data[args['data_key']], function(x, y) {
      	var row = $(window[args['row_template']](y));
        tbody.append(row);
        populate_index(tbody); 
      });
      
      // Populate Grid Pagination
      $(args['pagination']).pagination({
        items: totalItems,
        itemsOnPage: args['limit'],
        currentPage: args['page'],
        cssStyle: "light-theme",
        onPageClick: function(pageNumber) {
          args['page'] = pageNumber;
          grid_population(table, args);
        }
      });  
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
			// console.log("error :"+XMLHttpRequest.responseText);
			// console.log("errorThrown :"+errorThrown);
			// console.log("textStatus :"+textStatus);
      // alert("error :"+XMLHttpRequest.responseText);
    }
    // beforeSend: function ( xhr ) {
    // xhr.overrideMimeType("text/plain; charset=x-user-defined");
  }).done(function( html ) {
    // if(table.height() < min_height) table.height(min_height);
    table.find('tbody tr').bind('dblclick', function() {
      var forward = $(this).attr('forward');
         
      if(typeof forward == "undefined" || forward == '') return false;
      window.location = forward;
    })
  })
}

$.fn.toggle_check_items = function(table) { 
  this.click(function() { 
  	var checked = this.checked;
  	var check_items = table.find('.chk-item'); 	
  	
  	$(check_items).each(function(i) {
  		if(!$(check_items[i]).prop('disabled')) {
  			$(check_items[i]).prop('checked', checked);
  		}  		
  	})
  })
}

// ====================================
// ROW TEMPLATES
// ====================================

function row_template(data) {
  var id		= data['id'];
  var row		= $('<tr id="'+id+'">' +
  							'<input type="hidden" name="array['+id+'][id]" value="'+id+'"/>' +
  						'</tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
 
  row.find('.numeric').numeric_only();
  row.find('.numbers').digits();
  return row;   
}

function row_template_materials(data) {
  var forward	= host + "/account/materials-show.php?mid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['classification'] +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_indirect_materials(data) {
  var forward	= host + "/account/indirect-materials-show.php?mid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['classification'] +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_materials_inventory(data) {
  var id		= data['id'];
  var forward	= host + '/account/minventory-show.php?id='+ id;
  var row		= $('<tr id="'+ data['id'] +'"></tr>');
  var highlight = '';
  // if(Number(data['qty']) > 0 && Number(data['qty']) <= Number(data['msq'])) {
  	// highlight = 'highlight-orange';
  // }
  // if(Number(data['qty']) == 0) {
  	// highlight = 'highlight-red';
  // }
  
  row.append('<td class="border-right '+ highlight +'"><a href="'+ forward +'">'+ (data['code'] || '--') +'</a></td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['classification'] +'</td>');
  row.append('<td class="border-right '+ highlight +'">'+ data['description'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['uom'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-right numbers">'+ parseFloat((data['qty'] || 0)) +'</td>');
 
  row.find('.numbers').digits();
  return row;   
}

function row_template_materials_inventory_report(data) {
  var id		= data['id'];
  var forward	= host + '/account/materials-show.php?mid='+ id;
  var row		= $('<tr id="'+ data['id'] +'"></tr>');
  var highlight = '';
  // if(Number(data['qty']) > 0 && Number(data['qty']) <= Number(data['msq'])) {
  	// highlight = 'highlight-orange';
  // }
  // if(Number(data['qty']) == 0) {
  	// highlight = 'highlight-red';
  // }
  
  row.append('<td class="border-right '+ highlight +'"><a href="'+ forward +'">'+ (data['code'] || '--') +'</a></td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['classification'] +'</td>');
  row.append('<td class="border-right '+ highlight +'">'+ data['description'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['uom'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-right numbers">'+ parseFloat((data['qty'] || 0)) +'</td>');
  row.append('<td class="border-right '+ highlight +' text-right numbers">'+ parseFloat((data['physical_qty'] || 0)) +'</td>');
 
  row.find('.numbers').digits();
  return row;   
}

function row_template_minventory_items_read_only(data) { 
  var id		= data['id'];
  var row		= $('<tr id="'+ data['id'] +'" qty="'+parseFloat(data['qty'])+'"></tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center item-invoice">'+ data['invoice_no'] +'</td>');
  row.append('<td class="border-right text-center item-lot">'+ data['lot_no'] +'</td>');
  row.append('<td class="border-right item-remarks">'+ (data['remarks'] || '') +'</td>');
  row.append('<td class="border-right text-center item-unit">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right item-qty numbers">'+ parseFloat(data['qty']) +'</td>');
 
  row.find('.numbers').digits();
  return row;   
}

function row_template_minventory_items(data) { 
  var id		= data['id'];
  var row		= $('<tr qty="'+parseFloat(data['qty'])+'"></tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center item-invoice">'+ data['invoice_no'] +'</td>');
  row.append('<td class="border-right text-center item-lot">'+ data['lot_no'] +'</td>');
  row.append('<td class="border-right item-remarks">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-center item-unit">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right item-qty numbers">'+ parseFloat(data['qty']) +'</td>');
  row.append('<td class="border-right text-center"><a id="'+ data['id'] +'" class="chk-item" href="#">edit</a></td>');
 
  row.find('.numbers').digits();
  return row;   
}

function row_template_actual_minventory_items(data) { 
  var id		= data['id'];
  var row		= $('<tr id="'+id+'">' +
  							'<input type="hidden" name="actual['+id+'][id]" value="'+id+'"/>' +
  							'<input type="hidden" name="actual['+id+'][invoice_no]" value="'+data['invoice_no']+'"/>' +
  							'<input type="hidden" name="actual['+id+'][lot_no]" value="'+data['lot_no']+'"/>' +
  						'</tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center item-invoice">'+ data['invoice_no'] +'</td>');
  row.append('<td class="border-right text-center item-lot">'+ data['lot_no'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="actual['+id+'][remarks]" class="text-field-max" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center item-unit">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right item-qty numbers">'+ parseFloat(data['qty']) +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="actual['+id+'][qty]" class="text-field-smallest text-right actual-qty numeric" value="0" autocomplete="off"/></td>');
 
  row.find('.numeric').numeric_only();
  row.find('.numbers').digits();
  return row;   
}

function row_template_minventory_requests(data) { 
  var id		= data['mrid'];
  var row		= $('<tr id="'+ id +'"></tr>');
  var forward	= host + "/account/production-material-requests-show.php?rid="+ data['rid'] +"";
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center"><a target="_blank" href="'+ forward +'">'+ data['type'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['batch_no'] +'</td>');
  row.append('<td class="border-right text-center date-string">'+ data['requested_date'] +'</td>');
  row.append('<td class="border-right text-center date-string">'+ data['expected_date'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['terminal'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['completion_status'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['qty'] +'</td>');
 
	row.find('.date-string').format_date_string(false, 'M dd, yy');
  row.find('.numbers').digits();
  return row;   
}

function row_template_products(data) {
  var forward	= host + "/account/products-show.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['brand'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['series'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['pack_qty'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['color'] || '') +"</td>" +
    "<td class=\"border-right\">"+ (data['description'] || '') +"</td>" +
    "</tr>");

  return row;
}

function row_template_products_inventory(data) {
  var forward	= host + "/account/pinventory-show.php?id="+ data['pid'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ (data['brand'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['series'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['pack_qty'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['color'] || '') +"</td>" +
    "<td class=\"border-right\">"+ (data['description'] || '') +"</td>" +
    "<td class=\"border-right text-right numbers \">"+ parseFloat(data['qty'] || 0) +"</td>" +
    "</tr>");

  row.find('.numbers').digits();
  return row;
}

function row_template_products_inventory_report(data) {
  var id		= data['id'];
  var forward	= host + '/account/products-show.php?pid='+ id;
  var row		= $('<tr id="'+ data['id'] +'"></tr>');
  var highlight = '';
  // if(Number(data['qty']) > 0 && Number(data['qty']) <= Number(data['msq'])) {
  	// highlight = 'highlight-orange';
  // }
  // if(Number(data['qty']) == 0) {
  	// highlight = 'highlight-red';
  // }
  
  row.append('<td class="border-right '+ highlight +'"><a href="'+ forward +'">'+ (data['code'] || '--') +'</a></td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['brand'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right '+ highlight +'">'+ (data['description'] || '') +'</td>');
  row.append('<td class="border-right '+ highlight +' text-center">'+ data['uom'] +'</td>');
  row.append('<td class="border-right '+ highlight +' text-right numbers">'+ parseFloat((data['qty'] || 0)) +'</td>');
  row.append('<td class="border-right '+ highlight +' text-right numbers">'+ parseFloat((data['physical_qty'] || 0)) +'</td>');
 
  row.find('.numbers').digits();
  return row;   
}

function row_template_suppliers(data) {
  var forward	= host + "/account/suppliers-show.php?sid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right\">"+ data['name'] +"</td>" +
    "<td class=\"border-right\">"+ data['type'] +"</td>" +
    "<td class=\"border-right\">"+ data['prodserv'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_supplier_material_plan(data) {
  var forward	= host + "/account/material-plan-show.php?sid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right\">"+ data['name'] +"</td>" +
    "<td class=\"border-right\">"+ data['type'] +"</td>" +
    "<td class=\"border-right\">"+ data['prodserv'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_material_plan(data) {
  var forward	= host + "/account/material-plan-model-show.php?mid="+ data['id'] +"";
  
  var sorting_percentage = parseFloat(data['sorting_percentage']);
  var sp = (sorting_percentage > 0) ? (sorting_percentage*100) + '%' : 'N/A';
  var open_po = (sorting_percentage > 0) ? sorting_percentage * parseFloat(data['open_po']) : parseFloat(data['open_po']);
  var po_plan = parseFloat((data['po_plan'] || 0));
  var wo_plan = parseFloat((data['wo_plan'] || 0));
  var po_wo_plan = parseFloat(po_plan + wo_plan);
  
  var prod_plan = (parseFloat((po_wo_plan * parseFloat(data['defect_rate'])) + po_wo_plan)).toFixed(2);
  var balance = (open_po + (parseFloat(data['inventory']) || 0) - prod_plan);
  var po_qty = (Math.ceil(parseFloat(parseFloat(Math.abs(balance) / parseFloat(data['moq'])).toFixed(1)) * 1) / 1) * parseFloat(data['moq']);
  
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right\">" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][item_id]\" value=\""+ data['id'] +"\" />" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][quantity]\" value=\""+ po_qty +"\" />" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][item_price]\" value=\""+ parseFloat(data['price']) +"\" />" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][currency]\" value=\""+ data['currency'] +"\" />" +
  	"<a target=\"_blank\" href=\""+ forward +"\">"+ data['material_code'] +"</a></td>" +
    "<td class=\"border-right\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (parseFloat(data['defect_rate'])*100) +"%</td>" +
    "<td class=\"border-right text-right numbers\">"+ (isNaN(prod_plan) ? '0' : prod_plan) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['inventory']) || 0) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (open_po.toFixed(2) || 0) +"</td>" +
    "<td class=\"border-right text-center numbers\">"+ sp +"</td>" +
    "<td class=\"border-right text-right numbers "+ (((isNaN(balance) ? 0 : balance) < 0) ? "red" : "") +"\">"+ (isNaN(balance) ? 0 : balance) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['moq']) || 0) + data['unit'] +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (po_qty || 'N/A') +"</td>" +
    "<td class=\"border-right text-right numbers text-currency\">"+ (parseFloat(data['price']) || 0) +"</td>" +
    "</tr>");

  row.find('.numbers').digits();
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  return row;
}

function row_template_material_plan1(data) {
  var forward	= host + "/account/material-plan-model-show.php?mid="+ data['id'] +"";
  
  var prod_plan = parseFloat((parseFloat(data['prod_plan']) * parseFloat(data['defect_rate'])) + parseFloat(data['prod_plan']));
  var balance = ((parseFloat(data['open_po']) || 0) + (parseFloat(data['inventory']) || 0) - prod_plan);
  var po_qty = (Math.ceil(parseFloat(parseFloat(Math.abs(balance) / parseFloat(data['moq'])).toFixed(1)) * 1) / 1) * parseFloat(data['moq']);
  
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right\"><a target=\"_blank\" href=\""+ forward +"\">"+ data['material_code'] +"</a></td>" +
    "<td class=\"border-right\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (parseFloat(data['defect_rate'])*100) +"%</td>" +
    "<td class=\"border-right text-right numbers\">"+ (prod_plan.toFixed(2) || 'N/A') +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['inventory']) || 0) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['open_po']) || 0) +"</td>" +
    "<td class=\"border-right text-right numbers "+ ((balance < 0) ? "red" : "") +"\">"+ balance +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['moq']) || 0) + data['unit'] +"</td>" +
    "<td class=\"border-right text-right numbers text-currency\">"+ (parseFloat(data['price']) || 0) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (po_qty || 'N/A') +"</td>" +
    "</tr>");

  row.find('.numbers').digits();
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  return row;
  ///////////////////
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
  
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" /><input type="hidden" name="items['+id+'][item_id]" value="'+ data['item_id'] +'" /></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right">'+ data['code'] +'</td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
 
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row; 
}

function row_template_material_plan_model(data) {
  var forward	= host + "/account/material-plan-model-show.php?mid="+ data['item_id'] +"";
    
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right\"><a target=\"_blank\" href=\""+ forward +"\">"+ data['product_code'] +"</a></td>" +
    "<td class=\"border-right\">"+ data['brand'] +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (data['price'] || 0) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (data['qty'] || 0) +"</td>" +
    "</tr>");

  row.find('.numbers').digits();
  return row;
}

function row_template_notifications(data) {
  var forward	= host + "/account/"+ data['url'];
  var row		= $("<tr forward=\""+ forward +"\">" +
    "<td class=\"border-right text-center text-date\">"+ data['created_at'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['type'] +"</td>" +
  	"<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['title'] +"</a></td>" +
    "<td class=\"border-right \">"+ data['remarks'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_orders(data) {
  var forward	= host + "/account/orders-show.php?oid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['po_number'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['po_date'] +"</td>" +
    "<td class=\"border-right\">"+ data['payment_terms'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['delivery_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['completion_status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_purchase_orders(data) {
  var forward	= host + "/account/purchase-orders-show.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['po_number'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['po_date'] +"</td>" +
    "<td class=\"border-right\">"+ data['payment_terms'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['ship_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['completion_status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_plan_orders(data) {
  var forward	= host + "/account/plan-order-models.php?pid="+ data['id'] +"&t="+ data['order_type'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['order_no'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['order_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['ship_date'] +"</td>" +
    "<td class=\"border-right\">"+ data['remarks'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_plan_order_models_read_only(data) {
  var forward	= host + '/account/plan-order-model-shipment-show.php?ctrl_id='+ data['order_id'] +'&pid='+ data['item_id'] +'&t='+ data['order_type'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
           	
  row.find('.currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;   
}

function row_template_plan_po(data) {
  var forward	= host + "/account/plan-po-models.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['po_number'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['po_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['ship_date'] +"</td>" +
    "<td class=\"border-right\">"+ data['remarks'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_plan_po_models_read_only(data) {
  var forward	= host + '/account/plan-po-model-shipment-show.php?ctrl_id='+ data['purchase_order_id'] +'&pid='+ data['item_id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
           	
  row.find('.currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;   
}

function row_template_plan_po_model_shipments_read_only(data) {
  var forward	= host + '/account/';
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center date-string">'+ data['ship_date'] +'</td>');
  row.append('<td class="border-right text-center date-string">'+ (data['prod_date'] || '') +'</td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['completion'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers qty">'+ data['qty'] +'</td>');
           	
	//row.find('.date-string').format_date_string(false, 'M dd, yy');
  row.find('.numbers').digits();
  return row;   
}

function row_template_plan_po_model_shipments(data) {
  var forward	= host + '/account/';
  var id = data['id'];
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" /><input type="hidden" name="plan['+ id +'][id]" value="'+id+'" /></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center"><input name="plan['+ id +'][ship_date]" type="text" value="'+ data['ship_date'] +'" class="date-pick-thursday text-field-medium text-center" readonly/></td>');
  row.append('<td class="border-right text-center"><input name="plan['+ id +'][prod_date]" type="text" value="'+ (data['prod_date'] || '') +'" class="date-pick-friday text-field-medium text-center" readonly/></td>');
  row.append('<td class="border-right"><input name="plan['+ id +'][remarks]" type="text" value="'+ data['remarks'] +'" class="text-field-max"/></td>');
  row.append('<td class="border-right text-center">'+ data['completion'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right"><input name="plan['+ id +'][qty]" type="text" value="'+ data['qty'] +'" class="text-field-smallest text-right qty"/></td>');
           	
	row.find('.date-string').format_date_string(true, 'M dd, yy');
	row.find('.date-pick').date_pick('M dd, yy');
	row.find('.date-pick-thursday').date_pick_restrict('yy-mm-dd', 'thursday');
	row.find('.date-pick-friday').date_pick_restrict('yy-mm-dd', 'friday');
  row.find('.numbers').digits();
  return row;   
}

function row_template_plan_products(data) {
  var forward	= host + "/account/plan-model-po-show.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['brand'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['series'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['pack_qty'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['color'] || '') +"</td>" +
    "<td class=\"border-right\">"+ (data['description'] || '') +"</td>" +
    "</tr>");

  return row;
}

function row_template_plan_product_pos_read_only(data) {
  var forward	= host + '/account/plan-po-model-shipment-show.php?ctrl_id='+ data['id'] +'&pid='+ data['pid'];
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center"><a target="_blank" href="'+ forward +'">'+ data['po_number'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['po_date'] +'</td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['ship_date'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['quantity'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;   
}

function row_template_ship_plan_week(data) {
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

	if(data['type'] == 'FC') {
		row.append('<td class="border-right">'+ data['code'] +'</td>');
	} else {
		row.append('<td class="border-right"><a class="click-week" pid="'+ data['pid'] +'" ship_date="'+ data['ship_date'] +'" model="'+ data['code'] +'" href="#">'+ data['code'] +'</a></td>');
	}
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_ship_plan_month(data) {
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center">'+ data['ship_date'] +'</td>');
  if(data['type'] == 'FC'){
	  row.append('<td class="border-right">'+ data['code'] +'</td>');
  } else {
	  row.append('<td class="border-right"><a class="click-month" pid="'+ data['pid'] +'" ship_date="'+ data['ship_date'] +'" model="'+ data['code'] +'" href="#">'+ data['code'] +'</a></td>');
  }
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_ship_plan_month_all(data) {
  var forward	= host + '/account/plan-order-model-shipment-show.php?ctrl_id='+ data['ctrl_id'] +'&pid='+ data['pid'] +'&t='+ data['type'];
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  if(data['type'] == 'FC'){
	  row.append('<td class="border-right">'+ data['ctrl_no'] +'</td>');
  } else {
	  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['ctrl_no'] +'</a></td>');
  }
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right">'+ (data['remarks'] || '') +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_prod_plan_week(data) {
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

	if(data['type'] == 'FC') {
		row.append('<td class="border-right">'+ data['code'] +'</td>');
	} else {
		row.append('<td class="border-right"><a class="click-week" pid="'+ data['pid'] +'" prod_date="'+ data['prod_date'] +'" model="'+ data['code'] +'" href="#">'+ data['code'] +'</a></td>');
	}
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row; 
}

function row_template_prod_plan_month(data) {
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center">'+ data['prod_date'] +'</td>');
  if(data['type'] == 'FC'){
	  row.append('<td class="border-right">'+ data['code'] +'</td>');
  } else {
	  row.append('<td class="border-right"><a class="click-month" pid="'+ data['pid'] +'" prod_date="'+ data['prod_date'] +'" model="'+ data['code'] +'" href="#">'+ data['code'] +'</a></td>');
  }
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_prod_plan_month_all(data) {
  var forward	= host + '/account/plan-order-model-shipment-show.php?ctrl_id='+ data['ctrl_id'] +'&pid='+ data['pid'] +'&t='+ data['type'];
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  // if(data['type'] == 'FC'){
	  // row.append('<td class="border-right">'+ data['ctrl_no'] +'</td>');
  // } else {
	  row.append('<td class="border-right"><a class="click-model" pid="'+ data['pid'] +'" model="'+ data['code'] +'" prod_cp="'+ data['prod_cp'] +'" prod_qty="'+ data['ttl'] +'">'+ data['ctrl_no'] +'</a></td>');
  // }
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['series'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['pack_qty'] +'</td>');
  row.append('<td class="border-right">'+ (data['remarks'] || '') +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttl'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['ttls'] +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_product_inventory_items(data) {
	var forward	= host + '/account/plan-po-model-production-show.php?poid=';
  var row		= $('<tr id="'+ data['id'] +'"></tr>');

  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center">'+ data['prod_lot_no'] +'</td>');
  row.append('<td class="border-right text-center">'+ (data['stamp'] || '') +'</td>');
  row.append('<td class="border-right">'+ (data['remarks'] || '') +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers qty">'+ parseFloat(data['qty'] || 0) +'</td>');
           	
  row.find('.numbers').digits();
  return row;  
}

function row_template_work_orders(data) {
  var forward	= host + "/account/work-orders-show.php?wid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['wo_number'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['wo_date'] +"</td>" +
    "<td class=\"border-right\">"+ data['remarks'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['ship_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['completion_status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_work_order_items_read_only(data) {
  var forward	= host + "/account/work-order-parts-show.php?wid="+ data['work_order_id'] +"&wopid="+ data['id'];
  var row		= $('<tr id="prd-'+ data['product_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['product_code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right currency">'+ data['item_price'] +'</td>');
  row.append('<td class="border-right text-right currency amount">'+ amount +'</td>');
           	
  row.find('.numbers').digits();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_work_order_items(data) {
	var id = data['id'];
  var forward	= host + "/account/work-order-parts-show.php?wid="+ data['work_order_id'] +"&wopid="+ data['id'];
  var row		= $('<tr id="prd-'+ data['product_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
   
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['product_code'] +'</a><input type="hidden" name="items['+id+'][product_id]" value="'+ data['product_id'] +'" /></td>');
  row.append('<td class="border-right"><input type="text" name="items['+id+'][remarks]" value="'+ (data['remarks'] || '') +'" class="text-field-max" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity numeric" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
           	
  row.find('.numbers').digits();
	row.find('.numeric').numeric_only();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_work_order_item_parts_read_only(data) {
  var forward	= host + "/account/materials-show.php?mid="+ data['material_id'];
  var row		= $('<tr id="mat-'+ data['id'] +'"></tr>');
  
  var total_qty = parseFloat(data['parts_tree_qty']) * parseFloat(data['wo_qty']);
  var po_qty = (Math.ceil(parseFloat(parseFloat(Math.abs(total_qty) / parseFloat(data['moq'])).toFixed(1)) * 1) / 1) * parseFloat(data['moq']);
  var amount	= po_qty * clean_currency(data['item_price']);

  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['material_code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['parts_tree_qty']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['wo_qty']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ total_qty.toFixed(2) +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['moq']) +'</td>');
  row.append('<td class="border-right text-right currency">'+ parseFloat(data['item_price']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers po_qty">'+ po_qty +'</td>');
  row.append('<td class="border-right text-right currency amount">'+ amount +'</td>');
           	
  row.find('.numbers').digits();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}


function row_template_production_plans(data) {
  var forward	= host + "/account/production-plan-show.php?ppoid="+ data['id'] +"&oid="+ data['oid'];
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right text-center\"><a href=\""+ forward +"\">CPP-"+ data['id'] +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['po_number'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['po_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['target_date'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_material_requests(data) {
  var id		= data['id'];
  var forward	= host + "/account/production-material-requests-show.php?rid="+ id +"";
  var row		= $('<tr id="'+id+'">' +
  						'</tr>');
  
  row.append('<td class="border-right text-center"><a href="'+ forward +'">'+ data['request_type'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ (data['batch_no'] || '-') +'</td>');
  row.append('<td class="border-right">'+ (data['remarks'] || '-') +'</td>');
  row.append('<td class="border-right text-center">'+ data['requested_date'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['expected_date'] +'</td>');

  return row;   
}

function row_template_material_request_items(data) {
  var mid		= data['mid'];
  var forward	= host + "/account/materials-show.php?mid="+ mid +"";
  var row		= $('<tr id="mat-'+mid+'">' +
  							'<input type="hidden" name="request['+mid+'][mid]" value="'+mid+'"/>' +
  						'</tr>');
  
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="request['+mid+'][total]" value="'+ (data['qty'] || 0) +'" class="text-field-smallest numeric text-right"/></td>');

  return row;   
}

function row_template_material_request_items_read_only(data) {
  var mid		= data['mid'];
	var forward	= host + "/account/materials-show.php?mid="+ mid + "";
  
  var row		= $('<tr id="mat-'+mid+'">' +
  						'</tr>');

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ parseFloat(data['qty']) +'</td>');
 	
  row.find('.numbers').digits();
  return row;   
}

function row_template_material_request_issuance(data) {
  var mid		= data['mid'];
	var forward	= host + "/account/materials-show.php?mid="+ mid + "";
  
  var row		= $('<tr id="'+mid+'">' +
  						'</tr>');
	var wh_stock = (data['wh_stock'] || 0);
	
	var text = '';
	if(wh_stock < parseFloat(data['qty'])) {
		text = 'text-orange disable-issue';
	} 
	if(wh_stock == 0) {
		text = 'text-red disable-issue';
	}

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers '+ text +'">'+ parseFloat((data['qty'] || 0)) +'</td>');
  row.append('<td class="border-right text-right numbers wh-stock '+ text +'">'+ parseFloat((data['wh_stock'] || 0)) +'</td>');
 	
  row.find('.numbers').digits();
  return row;   
}

function row_template_locations(data) {
  var forward	= host + "/account/locations-show.php?lid="+ data['id'] + "";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['address'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ (data['item'] || '--') +"</td>" +
    "<td class=\"border-right text-center\">"+ data['bldg'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['description'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_devices(data) {
  var forward	= host + "/account/devices-show.php?did="+ data['id'] + "";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right\">"+ data['make'] +"</td>" +
    "<td class=\"border-right\">"+ data['model'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_users(data) {
  var forward	= host + "/account/users-show.php?uid="+ data['id'] + "";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['employee_id'] || '--') +"</a></td>" +
    "<td class=\"border-right\">"+ data['username'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['role'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_purchases(data) {
  var forward	= host + "/account/purchases-show.php?id="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['po_number'] || '--') +"</a></td>" +
    "<td class=\"border-right\"><a href=\"\">"+ data['supplier_name'] +"</a></td>" +
    "<td class=\"border-right text-center\">"+ dtime_basic(data['po_date']) +"</td>" +
    "<td class=\"border-right text-center\">"+ dtime_basic(data['delivery_date']) +"</td>" +
    "<td class=\"border-right text-right text-currency\">"+ data['total_amount'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['completion_status'] +"</td>" +
    "</tr>");
  
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  return row;
}

function row_template_purchase_material(data) {
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
  
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><input type="hidden" name="items['+id+'][item_id]" value="'+ data['item_id'] +'" /><input type="hidden" name="items['+id+'][currency]" value="'+ data['currency'] +'" />'+ data['code'] +'</td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right">'+ parseFloat(data['moq']) +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity numeric" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][item_price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
 
  row.find('.numeric').numeric_only();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_purchase_material_read_only(data) { 
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
  
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right">'+ data['code'] +'</td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right">'+ parseFloat(data['moq']) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right currency">'+ data['item_price'] +'</td>');
  row.append('<td class="border-right text-right currency amount">'+ amount +'</td>');
 
  row.find('.currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;   
}

function row_template_purchase_order_items(data) { 
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right">'+ data['code'] +'<input type="hidden" name="items['+id+'][item_id]" value="'+ data['item_id'] +'" /></td>');
  row.append('<td class="border-right text-center">'+ data['item_type'] +'<input type="hidden" name="items['+id+'][item_type]" value="'+ data['item_type'] +'" /></td>');
  row.append('<td class="border-right"><input type="text" name="items['+id+'][remarks]" value="'+ (data['remarks'] || '') +'" class="text-field-max"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][item_price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
           	
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_purchase_order_items_read_only(data) {
	var type = (data['item_type'] == 'PRD') ? 'purchase-order-parts-show.php?pid=' : 'materials-show.php?mid=';
  var forward	= host + "/account/"+ type + data['purchase_order_id'] +"&popid="+ data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['item_type'] +'</td>');
  row.append('<td class="border-right">'+ data['remarks'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right currency">'+ data['item_price'] +'</td>');
  row.append('<td class="border-right text-right currency amount">'+ amount +'</td>');
           	
  row.find('.currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;   
}

function row_template_purchase_order_item_parts_read_only(data) {
  var forward	= host + "/account/materials-show.php?mid="+ data['material_id'];
  var row		= $('<tr id="mat-'+ data['id'] +'"></tr>');
  
  var total_qty = parseFloat(data['parts_tree_qty']) * parseFloat(data['po_qty']);
  var po_qty = (Math.ceil(parseFloat(parseFloat(Math.abs(total_qty) / parseFloat(data['moq'])).toFixed(1)) * 1) / 1) * parseFloat(data['moq']);
  var amount	= po_qty * clean_currency(data['item_price']);

  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['material_code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['parts_tree_qty']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['po_qty']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ total_qty.toFixed(2) +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right text-center numbers">'+ parseFloat(data['moq']) +'</td>');
  row.append('<td class="border-right text-right currency">'+ parseFloat(data['item_price']) +'</td>');
  row.append('<td class="border-right text-right text-center numbers po_qty">'+ po_qty +'</td>');
  row.append('<td class="border-right text-right currency amount">'+ amount +'</td>');
           	
  row.find('.numbers').digits();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_deliver_materials(data) {
  var row = $("<tr></tr>");
  
  row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-item\"/></td>");
  row.append("<td class=\"border-right\">"+ data['material_code'] +"</td>");
  row.append("<td class=\"border-right\">"+ data['material_description'] +"</td>");
  row.append("<td class=\"border-right text-right\">"+ data['quantity'] +"</td>");
  row.append("<td>"+ (data['remarks'] || '--') +"</td>");
  
  return row;
}

function row_template_receiving(data) {
  var row		= $("<tr quantity=\""+ data['quantity'] + "\" id=\""+ data['id'] +"\"" + 
                    "item=\""+ data['id'] +"\" title=\""+ data['code'] +"\" invoice=\""+ (data['invoice'] || '') +"\"" + "\" status=\""+ (data['status'] || '') +"\"" +
                    "received=\""+ (data['received'] || 0) +"\" delivered=\""+ (data['delivered'] || 0) +"\" remarks=\""+ (data['remarks'] || '') +"\" \"></tr>");
  
  //var code = data['status'] != "Complete" ? "<a href=\"#\">"+ data['code'] +"</a>" : data['code']; //remove line
  //var code = "<a href=\"#\">"+ data['code'] +"</a>";
  var code = data['code'];
  
  row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-item\" name='items["+data['id']+"][id]' value='"+data['id']+"'/><input type='hidden' name='items["+data['id']+"][item_id]' class='delivered' value='"+data['item_id']+"' /><input type='hidden' name='items["+data['id']+"][delivered]' class='delivered' value='"+data['delivered']+"' /><input type='hidden' name='items["+data['id']+"][received]' class='received' value='' /><input type='hidden' name='items["+data['id']+"][status]' class='status' value='' /></td>");
  row.append("<td class=\"border-right\">"+ code +"</td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ data['quantity'] +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ (data['delivered'] || 0) +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['unit'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['status'] +"</td>");
  row.append("<td class=\"border-right text-right\"><input type='text' value='0' class='text-field-number item-received' readonly/></td>");
  row.append("<td class=\"border-right\"><input type='text' name='items["+data['id']+"][remarks]' value='' class='text-field-max item-remarks' readonly/></td>");
                  
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;
}

function row_template_receiving_read_only(data) {
  var row = $("<tr></tr>");
  
  row.append("<td class=\"border-right text-center magenta\">"+ (data['invoice'] || '') +"</td>");
  row.append("<td class=\"border-right\">"+ data['code'] +"</td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ data['quantity'] +"</td>");
  // row.append("<td class=\"border-right text-right numbers\">"+ (data['delivered'] || 0) +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ (data['received'] || 0) +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['unit'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['status'] +"</td>");
                  
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  row.find('.numbers').digits();
  return row;
}

function row_template_receive_date_report(data) {
  var forward1	= host + '/account/invoice-show.php?inv='+ data['invoice'];
  var forward2	= host + '/account/suppliers-show.php?sid='+ data['supplier_id'];
  var row		= $('<tr></tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center ">'+ (data['receive_date'] || '-') +'</td>');
  row.append('<td class="border-right "><a target="_blank"  href="'+ forward2 +'">'+ (data['supplier_name'] || '-') +'</a></td>');
  row.append('<td class="border-right text-center "><a target="_blank"  href="'+ forward1 +'">'+ (data['invoice'] || '-') +'</a></td>');
  row.append('<td class="border-right text-center">'+ (data['receipt'] || '-') +'</td>');
  
  return row;
}

function row_template_receive_supplier_report(data) {
  var forward1	= host + '/account/invoice-show.php?inv='+ data['invoice'];
  var row		= $('<tr></tr>');
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right text-center ">'+ (data['receive_date'] || '-') +'</td>');
  row.append('<td class="border-right text-center "><a target="_blank"  href="'+ forward1 +'">'+ (data['invoice'] || '-') +'</a></td>');
  row.append('<td class="border-right text-center">'+ (data['receipt'] || '-') +'</td>');
  
  return row;
}

function row_template_deliveries(data) {
  var forward	= host + "/account/deliveries-show.php?id="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  
  row.append("<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['po_number'] +"</a></td>");
  row.append("<td class=\"border-right\">"+ data['supplier_name'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ dtime_basic(data['delivery_date']) +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['status'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['completion_status'] +"</td>");
  
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  return row;
}

function row_template_delivery_items_read_only(data) {
  var forward	= host + "/account/materials-show.php?mid="+ data['item_id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  
  //row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-item\" disabled/></td>");
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append("<td class=\"border-right \"><a target=\"_blank\"  href=\""+ forward +"\">"+ data['code'] +"</a></td>");
  row.append("<td class=\"border-right \">"+ (data['invoice'] || 'N/A') +"</td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ parseFloat(data['quantity']) +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ (parseFloat(data['delivered']) || 0) +"</td>");
  row.append("<td class=\"border-right text-center \">"+ data['unit'] +"</td>");
  row.append("<td class=\"border-right text-center \">"+ data['status'] +"</td>");
  
  row.find('.numbers').digits();
  
  return row;
}

function row_template_delivery_items_partial_read_only(data) {
	var id = data['id'];
  var forward	= host + "/account/materials-show.php?mid="+ data['item_id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  var po_qty = parseFloat(data['quantity'] || 0);
  var delivered = parseFloat(data['delivered'] || 0);
  var undelivered = po_qty - delivered;
  
  //row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-item\" disabled/></td>");
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append("<td class=\"border-right \"><a target=\"_blank\"  href=\""+ forward +"\">"+ data['code'] +"</a><input type='hidden' name='items["+id+"][purchase_item_id]' value='"+data['purchase_item_id']+"' /></td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ po_qty +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ delivered +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ undelivered +"</td>");
  row.append("<td class=\"border-right text-center \">"+ data['unit'] +"</td>");
  row.append("<td class=\"border-right text-center \">"+ data['status'] +"</td>");
  
  row.find('.numbers').digits();
  
  return row;
}

function row_template_receiving_materials(data) {
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right">'+ data['code'] +'</td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="" value="'+ data['quantity'] +'" class="text-right text-field-smallest"/></td>');
  row.append('<td class="border-right text-right">0</td>');
  row.append('<td class="border-right text-center"><input type="text" name="" value="" class="text-field-medium text-left"/></td>');
           	
  return row;   
}

function row_template_invoices(data) {
  var forward	= host + "/account/invoice-show.php?inv="+ data['invoice'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  
  row.append("<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['invoice'] +"</a></td>");
  row.append("<td class=\"border-right\">"+ data['supplier'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['terms'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ dtime_basic(data['receive_date']) +"</td>");
  
  return row;
}

function row_template_invoices_items_read_only(data) {
  var forward	= host + "/account/purchases-show.php?id="+ data['purchase_id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  var amount = parseFloat(data['received']) * parseFloat(data['item_price']);
  
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append("<td class=\"border-right \"><a target=\"_blank\"  href=\""+ forward +"\">"+ data['po_number'] +"</a><input type=\"hidden\" class=\"amount\" value=\""+ parseFloat(amount)+"\" /></td>");
  row.append("<td class=\"border-right \">"+ data['material_code'] +"</td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ parseFloat(data['po_qty']) +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ parseFloat(data['received']) +"</td>");
  row.append("<td class=\"border-right text-right currency \">"+ parseFloat(data['item_price']) +"</td>");
  row.append("<td class=\"border-right text-right currency \">"+ parseFloat(amount) +"</td>");
  
  row.find('.numbers').digits();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;
}

function row_template_product_parts(data) {
	var forward	= host + "/account/materials-show.php?mid="+ data['mid'] + "";
  var row		= $('<tr id="mat-'+ data['mid'] +'"></tr>');

  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ parseFloat(data['qty']) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ parseFloat(data['total']) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (parseFloat(data['wh_stock']) || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ 0 +'</td>'); 	
 	
  row.find('.numbers').digits();
  return row;   
}

function row_template_product_parts_request(data) {
	var mid = data['mid'];
	var forward	= host + "/account/materials-show.php?mid="+ mid + "";
  var row		= $('<tr id="'+mid+'">' +
  							'<input type="hidden" name="request['+mid+'][mid]" value="'+mid+'"/>' +
  						'</tr>');

  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['type'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="request['+mid+'][qty]" value="'+ parseFloat(data['qty']) +'" class="text-field-smallest text-right request-quantity numeric" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="request['+mid+'][total]" value="'+ parseFloat(data['total']) +'" class="text-field-smallest text-right request-total numeric" /></td>');
 	
  row.find('.numeric').numeric_only();
  return row;   
}

function row_template_parts_tree(data) {
	var forward	= host + "/account/materials-show.php?mid="+ data['material_id'] + "";
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['material_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a href="'+ forward +'">'+ data['code'] +'</a><input type="hidden" name="items['+id+'][material_id]" value="'+ (data['material_id'] || '') +'" /></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right decimal get-amount item-quantity" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" disabled /></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][remarks]" value="'+ (data['remarks'] || '') +'" class="text-field-max" autocomplete="off"/></td>');
           	
 	row.find('.decimal').decimal_only();
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_parts_tree_read_only(data) {
	var forward	= host + "/account/materials-show.php?mid="+ data['material_id'] + "";
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['material_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" disabled/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a href="'+ forward +'" target="_blank"">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-center">'+ data['quantity'] +'</td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center currency">'+ data['item_price'] +'</td>');
  row.append('<td class="border-right text-center currency">'+ amount +'</td>');
  row.append('<td class="border-right text-center">'+ data['remarks'] +'</td>');
           	
  row.find('.currency').formatCurrency({region:"en-PH"});
  return row;   
}

function row_template_defects(data) {
  var forward	= host + "/account/defects-show.php?did="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">"+
    "<td class=\"border-right text-center\">"+ data['type'] +"</td>" +
  	"<td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['defect'] || '--') +"</a></td>" +
    "<td class=\"border-right\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['line'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['category'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['location'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_roles(data) {
  var forward	= host + "/account/roles-show.php?rid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">"+
  	"<td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['name'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['description'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_forecast_items_read_only(data) {
	var forward	= host + "/account/roles-show.php?rid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">"+
  	"<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['week'] +"</a></td>" +
    "<td class=\"border-right \">"+ (data['remarks'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['ship_date'] || '') +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ data['qty'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_forecast_items(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">"+
  	"<td class=\"border-right text-center\"><input type=\"hidden\" name=\"items["+data['id']+"][forecast_cal_id]\" value=\""+data['id']+"\" class=\"item-week-id\" /><a href=\""+ forward +"\">"+ data['week'] +"</a></td>" +
    "<td class=\"border-right text-center \"><input type=\"text\" name=\"items["+data['id']+"][remarks]\" class=\"text-field-max\" /></td>" +
    "<td class=\"border-right text-center \"><input type=\"text\" name=\"items["+data['id']+"][ship_date]\" class=\"date-pick text-center\" /></td>" +
    "<td class=\"border-right text-right \"><input type=\"text\" name=\"items["+data['id']+"][qty]\" class=\"text-field-medium text-right item-quantity\" /></td>" +
    "</tr>");

	row.find('.date-pick').date_pick();

  return row;
}

function row_template_forecast_read_only(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['product_id'] + "";
  var id		= data['product_id'];
  var row		= $('<tr id="prd-'+ data['id'] +'"></tr>');
  var ttls

  row.append('<td class="border-right"><a class="click-month" href="#" pid="'+ id +'" pcode="'+ data['code'] +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jan'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['feb'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['mar'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['apr'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['may'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jun'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jul'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['aug'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['sep'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['oct'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['nov'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['dece'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['total_qty'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['single_total_qty'] || 0) +'</td>');
           	
  row.find('.numbers').digits();
  return row; 
}

function row_template_forecast_months_read_only(data) {
	var id		= data['id'];
  var row		= $('<tr id="prd-'+ data['product_id'] +'"></tr>');
  var month = '';
  switch(data['forecast_month']) {
  	case '1': month = 'Jan'; break;
  	case '2': month = 'Feb'; break;
  	case '3': month = 'Mar'; break;
  	case '4': month = 'Apr'; break;
  	case '5': month = 'May'; break;
  	case '6': month = 'Jun'; break;
  	case '7': month = 'Jul'; break;
  	case '8': month = 'Aug'; break;
  	case '9': month = 'Sep'; break;
  	case '10': month = 'Oct'; break;
  	case '11': month = 'Nov'; break;
  	case '12': month = 'Dec'; break;
  }

  row.append('<td class="border-right text-center">'+ month +'</td>');
  row.append('<td class="border-right text-center">'+ (data['ctrl_no'] || '') +'</td>');
  row.append('<td class="border-right text-center">'+ (data['delivery_date'] || '-') +'</td>');
  row.append('<td class="border-right text-center">'+ (data['ship_date'] || '-') +'</td>');
  row.append('<td class="border-right text-center">'+ (data['prod_date'] || '-') +'</td>');
  row.append('<td class="border-right text-center">'+ (data['status'] || '') +'</td>');
  row.append('<td class="border-right">'+ (data['remarks'] || '') +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['qty'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['single_total_qty'] || 0) +'</td>');
           	
  row.find('.numbers').digits();
	row.find('.date-string').format_date_string(false, 'M dd, yy');
  return row;
}

function row_template_forecast_months(data) {
	var id		= data['id'];
  var row		= $('<tr id="'+ data['id'] +'" product_id="'+ data['product_id'] +'"></tr>');
  var month = '';
  switch(data['forecast_month']) {
  	case '1': month = 'Jan'; break;
  	case '2': month = 'Feb'; break;
  	case '3': month = 'Mar'; break;
  	case '4': month = 'Apr'; break;
  	case '5': month = 'May'; break;
  	case '6': month = 'Jun'; break;
  	case '7': month = 'Jul'; break;
  	case '8': month = 'Aug'; break;
  	case '9': month = 'Sep'; break;
  	case '10': month = 'Oct'; break;
  	case '11': month = 'Nov'; break;
  	case '12': month = 'Dec'; break;
  }

  row.append('<td class="border-right text-center"><input type="hidden" name="forecast['+id+'][id]"  value="'+id+'" /><input type="hidden" name="forecast['+id+'][product_id]"  value="'+data['product_id']+'" />'+ month +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="forecast['+id+'][ctrl_no]" class="text-center text-field-smallest" value="'+ (data['ctrl_no'] || '') +'"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="forecast['+id+'][delivery_date]" class="text-center text-field-medium date-pick-thursday" value="'+ (data['delivery_date'] || '') +'" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="forecast['+id+'][ship_date]" class="text-center text-field-medium date-pick-thursday" value="'+ (data['ship_date'] || '') +'" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="forecast['+id+'][prod_date]" class="text-center text-field-medium date-pick-friday" value="'+ (data['prod_date'] || '') +'" readonly/></td>');
  row.append('<td class="border-right text-center">'+ (data['status'] || '') +'</td>');
  row.append('<td class="border-right"><input type="text" name="forecast['+id+'][remarks]" class="text-field-max" value="'+ (data['remarks'] || '') +'"/></td>');
  row.append('<td class="border-right text-right"><input type="text" name="forecast['+id+'][qty]" class="text-center text-field-number numeric ttl" value="'+ (data['qty'] || 0) +'"/></td>');
  //row.append('<td class="border-right text-right"><input type="text" class="text-center text-field-number numeric ttls" value="'+ (data['qty'] || 0) +'" readonly/></td>');
           	
  row.find('.numbers').digits();
  row.find('.numeric').numeric_only();
	row.find('.date-string').format_date_string(true, 'M dd, yy');
	row.find('.date-pick').date_pick('M dd, yy');
	row.find('.date-pick-thursday').date_pick_restrict('yy-mm-dd', 'thursday');
	row.find('.date-pick-friday').date_pick_restrict('yy-mm-dd', 'friday');
  return row;
}

function row_template_forecast_h1(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['product_id'] + "";
  var id		= data['product_id'];
  var row		= $('<tr id="prd-'+ data['id'] +'"></tr>');

  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a><input type="hidden" name="items['+id+'][product_id]" value="'+ (id || '') +'" /></td>');
  row.append('<td class="border-right text-center">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][jan]" value="'+ (data['jan'] || 0) +'" class="text-field-medium text-right item-qty-jan" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][feb]" value="'+ (data['feb'] || 0) +'" class="text-field-medium text-right item-qty-feb" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][mar]" value="'+ (data['mar'] || 0) +'" class="text-field-medium text-right item-qty-mar" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][apr]" value="'+ (data['apr'] || 0) +'" class="text-field-medium text-right item-qty-apr" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][may]" value="'+ (data['may'] || 0) +'" class="text-field-medium text-right item-qty-may" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][jun]" value="'+ (data['jun'] || 0) +'" class="text-field-medium text-right item-qty-jun" autocomplete="off"/></td>');
           	
  return row; 
}

function row_template_forecast_h1_read_only(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['product_id'] + "";
  var id		= data['product_id'];
  var row		= $('<tr id="prd-'+ data['id'] +'"></tr>');

  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jan'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['feb'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['mar'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['apr'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['may'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jun'] || 0) +'</td>');
           	
  row.find('.numbers').digits();
  return row; 
}

function row_template_forecast_h2(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['product_id'] + "";
  var id		= data['product_id'];
  var row		= $('<tr id="prd-'+ data['id'] +'"></tr>');

  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a><input type="hidden" name="items['+id+'][product_id]" value="'+ (id || '') +'" /></td>');
  row.append('<td class="border-right text-center">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][jul]" value="'+ (data['jul'] || 0) +'" class="text-field-medium text-right item-qty-jul" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][aug]" value="'+ (data['aug'] || 0) +'" class="text-field-medium text-right item-qty-aug" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][sep]" value="'+ (data['sep'] || 0) +'" class="text-field-medium text-right item-qty-sep" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][oct]" value="'+ (data['oct'] || 0) +'" class="text-field-medium text-right item-qty-oct" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][nov]" value="'+ (data['nov'] || 0) +'" class="text-field-medium text-right item-qty-nov" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][dece]" value="'+ (data['dece'] || 0) +'" class="text-field-medium text-right item-qty-dece" autocomplete="off"/></td>');
           	
  return row; 
}

function row_template_forecast_h2_read_only(data) {
	var forward	= host + "/account/forecast-show.php?pid="+ data['product_id'] + "";
  var id		= data['product_id'];
  var row		= $('<tr id="prd-'+ data['id'] +'"></tr>');

  row.append('<td class="border-right"><a target="_blank" href="'+ forward +'">'+ data['code'] +'</a></td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['jul'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['aug'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['sep'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['oct'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['nov'] || 0) +'</td>');
  row.append('<td class="border-right text-right numbers">'+ (data['dece'] || 0) +'</td>');
           	
  row.find('.numbers').digits();
  return row; 
}

// function row_template_forecast_months(data) {
  // var year_total = parseFloat((data['jan'] || 0)) + parseFloat((data['feb'] || 0)) + parseFloat((data['mar'] || 0)) + parseFloat((data['apr'] || 0)) +
  									// parseFloat((data['may'] || 0)) + parseFloat((data['jun'] || 0)) + parseFloat((data['jul'] || 0)) + parseFloat((data['aug'] || 0)) +
  									// parseFloat((data['sep'] || 0)) + parseFloat((data['oct'] || 0)) + parseFloat((data['nov'] || 0)) + parseFloat((data['dece'] || 0));
  // var row		= $('<tr forecast_cal_id="'+ data['id'] +'"></tr>');
// 
  // row.append('<td class="border-right text-center">'+ data['forecast_year'] +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="1" month="January">'+ (data['jan'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="2" month="February">'+ (data['feb'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="3" month="March">'+ (data['mar'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="4" month="April">'+ (data['apr'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="5" month="May">'+ (data['may'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="6" month="June">'+ (data['jun'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="7" month="July">'+ (data['jul'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="8" month="August">'+ (data['aug'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="9" month="September">'+ (data['sep'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="10" month="October">'+ (data['oct'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="11" month="November">'+ (data['nov'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers month" year="'+ data['forecast_year'] +'" id="12" month="December">'+ (data['dece'] || 0) +'</td>');
  // row.append('<td class="border-right text-right numbers" year="'+ data['forecast_year'] +'">'+ (year_total || 0) +'</td>');
//   
  // row.find('.numbers').digits();
  // return row; 
// }

function row_template_forecast_week_days(data) {
	var forward	= "#";
  var id		= data['id'];
  var row		= $('<tr forecast_week_id="'+ data['id'] +'"></tr>');
  var week_total = (parseFloat(data['day_1']) || 0) + (parseFloat(data['day_2']) || 0) + (parseFloat(data['day_3']) || 0) +
  									(parseFloat(data['day_4']) || 0) + (parseFloat(data['day_5']) || 0);

  row.append('<td class="border-right text-center"><a class="line" href="'+ forward +'">Line'+ data['line_id'] +'</a><input type="hidden" name="item['+id+'][id]" value="'+ data['id'] +'" /><input type="hidden" name="item['+id+'][line_id]" value="'+ data['line_id'] +'" /></td>');
  row.append('<td class="border-right text-center"><input type="text" name="item['+id+'][day_1]" value="'+ (parseFloat(data['day_1']) || 0) +'" class="text-field-medium text-right day-1" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="item['+id+'][day_2]" value="'+ (parseFloat(data['day_2']) || 0) +'" class="text-field-medium text-right day-2" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="item['+id+'][day_3]" value="'+ (parseFloat(data['day_3']) || 0) +'" class="text-field-medium text-right day-3" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="item['+id+'][day_4]" value="'+ (parseFloat(data['day_4']) || 0) +'" class="text-field-medium text-right day-4" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="item['+id+'][day_5]" value="'+ (parseFloat(data['day_5']) || 0) +'" class="text-field-medium text-right day-5" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center"><input type="text" value="'+ (week_total || 0) +'" class="text-field-medium text-right week-total" readonly/></td>');         	
  row.find('.numbers').digits();
  return row; 
}


// ====================================
// FUNCTIONS
// ====================================
function dtime_basic(dtime) {
  var dt = new Date(dtime);
  
  if (isNaN(dt) == true) return '--';
  
  var d = dt.getDate();
  var m = dt.getMonth()+1;
  var y = dt.getFullYear();
  return '' + (m<=9?'0'+m:m) +'/'+ (d<=9?'0'+d:d) +'/'+ y;
}

function clean_currency(currency) {
  return Number(currency.toString().replace(/[^0-9\.]+/g, ''));
}
         
function populate_index(grid) {
  grid.find('td[replace="#{index}"]').each(function(index) {
    $(this).html(index + 1);
  });
}

function build_options_unit(parent) {
  $.getJSON(host +'/populate/units.php', function(data) {
    $.each(data['units'], function(index, item) {
      parent.append('<option value="'+ item['id'] +'">'+ item['description'] +'</option>');
    });
  });
}

function cancel_btn() {
	window.history.back();
	return false;
}

function add_live_search(pdsply, ptype, ptable, pcol, pjoin, pcond, psearch) {
	$.ajax({
			type: "POST",
			url: "../include/livesearch.php",
			data: { searchword: psearch, 
							searchtype: ptype,
							resultcount: 5,
							table: 			ptable,
							columns: 		pcol,
							joins: 			pjoin,
							conditions: pcond
						},
			cache: false,
			success: function(data) {
				//alert(pcond);
				$(pdsply).html(data).show();		
			}
		});
}

function get_barcode_data(ptable, pcol, pjoin, pcond) { 
	$.ajax({
			type: "POST",
			url: "../include/livescan.php",
			data: {  
							table: 			ptable,
							columns: 		pcol,
							joins: 			pjoin,
							conditions: pcond
						},
			cache: false,
			dataType : "text",
			success: function(data) {
				if(data.trim().length>0) {
					$('#item_id').val(data.trim().split('|')[0]);
					$('#item_code').val(data.trim().split('|')[1]);
					$("#btn_create").removeAttr('disabled');
					return false;
				}
				$('#item_code').val('');
				$('#entry_barcode').val('');
				$("#btn_create").attr( "disabled", "disabled" );		
				console.log(data);		
			}
		});
}
