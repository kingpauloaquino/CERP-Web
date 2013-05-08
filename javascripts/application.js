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
  $('.redirect-to').redirect_to();
  $('.btn-download').download(); 
  
  $(".numbers").digits();
  
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

$.fn.redirect_to = function() {
  $(this).live('click', function(e) {
    e.preventDefault();
    window.location = $(this).attr('rel');
  });
}

$.fn.download = function() {
  $(this).live('click', function(e) {
    e.preventDefault();
    window.open($(this).attr('rel'), '_blank');
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

$.fn.date_pick = function() {
  $(this).datepicker({
		inline: true, dateFormat: 'MM dd, yy'
	});
}

$.fn.digits = function(){ 
    return this.each(function(){ 
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
    })
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
    
    args['order_by']	= column;
    args['sort_by']		= sort;
    
    grid_population(table, args);
    
    table.find('thead a.sort').removeClass('active up');
    $(this).addClass(active);
  });
  
  if(args['searchable'] == true) {
		$('.search').keypress(function(e) { 
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
  args['order_by'] = args['order_by'] || "";
  args['sort_by'] = args['sort_by'] || 'ASC';
	var params = typeof args['params'] == "undefined" ? "" : args['params'];
	
	var opt = args['url'].indexOf("?") !== -1 ? "&" : "?";
	//alert(params);
  // DATA POPULATION

  $.ajax({
    url: host + args['url'] + opt + 'page='+ args['page'] +'&limit='+ args['limit'] +'&order='+ args['order_by'] +'&sort='+ args['sort_by'] +'&params='+ params,
    dataType: "json",
    data: args['data'] || null,
    success: function(data) {
      // Remove Loader
      tbody.empty();
      //alert(data['test']);
      var totalItems = data['total'] || 0;

      // Populate Grid Rows
      console.log(data[args['data_key']]);
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
  var forward	= host + "/account/minventory-show.php?id="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['classification'] +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "<td class=\"border-right text-center \">"+ data['uom'] +"</td>" +
    "<td class=\"border-right text-right numbers \">"+ data['qty'] +"</td>" +
    "</tr>");

  row.find('.numbers').digits();
  return row;
}

function row_template_products(data) {
  var forward	= host + "/account/products-show.php?pid="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['brand'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['pack'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['color'] || '') +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_products_inventory(data) {
  var forward	= host + "/account/pinventory-show.php?id="+ data['pid'] +"";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ (data['brand'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['pack'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ (data['color'] || '') +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "<td class=\"border-right text-right numbers \">"+ data['qty'] +"</td>" +
    "</tr>");

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
  var prod_plan = parseFloat((parseFloat(data['prod_plan']) * parseFloat(data['defect_rate'])) + parseFloat(data['prod_plan']));
  var balance = (open_po + (parseFloat(data['inventory']) || 0) - prod_plan);
  var po_qty = (Math.ceil(parseFloat(parseFloat(Math.abs(balance) / parseFloat(data['moq'])).toFixed(1)) * 1) / 1) * parseFloat(data['moq']);
  
  var row		= $("<tr forward=\""+ forward +"\">" +
  	"<td class=\"border-right\">" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][item_id]\" value=\""+ data['id'] +"\" />" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][quantity]\" value=\""+ po_qty +"\" />" +
  		"<input type=\"hidden\" name=\"items["+data['id']+"][item_price]\" value=\""+ parseFloat(data['price']) +"\" />" +
  	"<a target=\"_blank\" href=\""+ forward +"\">"+ data['material_code'] +"</a></td>" +
    "<td class=\"border-right\">"+ data['model'] +"</td>" +
    "<td class=\"border-right text-center\">"+ (parseFloat(data['defect_rate'])*100) +"%</td>" +
    "<td class=\"border-right text-right numbers\">"+ (prod_plan.toFixed(2) || 'N/A') +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (parseFloat(data['inventory']) || 0) +"</td>" +
    "<td class=\"border-right text-right numbers\">"+ (open_po.toFixed(2) || 0) +"</td>" +
    "<td class=\"border-right text-center numbers\">"+ sp +"</td>" +
    "<td class=\"border-right text-right numbers "+ ((balance < 0) ? "red" : "") +"\">"+ balance +"</td>" +
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
    "<td class=\"border-right text-center\">"+ data['created_at'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['type'] +"</td>" +
  	"<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['title'] +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['value'] +"</td>" +
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
  row.append('<td class="border-right"><input type="text" name="items['+id+'][remarks]" value="'+ (data['remarks'] || '') +'" class="text-field-max"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" readonly/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
           	
  row.find('.numbers').digits();
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

function row_template_terminal_prod_items(data) {
  var forward	= host + "/account/#"+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\">" +
    "<td class=\"border-right text-center\"></td>" +
    "<td class=\"border-right text-center\"></td>" +
  	"<td class=\"border-right text-center\">"+ data['prod_lot_no'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['tracking_no'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['mat_lot_no'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['material_code'] +"</td>" +
    "<td class=\"border-right\">"+ data['description'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['status'] +"</td>" +
    "<td class=\"border-right text-right\">"+ data['qty'] +"</td>" +
    "</tr>");

  return row;
}

function row_template_parts_requests(data) {
	$('.timepick').datetimepicker({
		dateFormat: "yy-mm-dd",
		timeFormat: "HH:mm"
	});
	var id = data['id'];
  var forward	= host + "/account/materials-show.php?mid="+ data['mat_id'];
  var row		= $("<tr forward=\""+ forward +"\">" +
    "<td class=\"border-right text-center\"><input type=\"checkbox\" name=\"parts["+id+"][requested]\" value=\""+ id +"\" " + ((data['is_requested'] == 1) ? "checked disabled" : "") + " class=\"chk-item\"/></td></td>" +    
		"<td class=\"border-right text-center\" replace=\"#{index}\"></td><input type='hidden' name='parts["+id+"][id]' value='" +id+ "' />" +
  	"<td class=\"border-right\"><a href=\""+ forward +"\">"+ (data['material_code'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\"><input type='text' name='parts["+id+"][expected_datetime]' value='"+ 
    	(data['expected_datetime'] || '') +"' class='auto_width_center text-center timepick' " + ((data['is_requested'] == 1) ? "disabled" : "") + "/></td>" +
    "<td class=\"border-right text-center\">"+ (data['status'] || '') +"</td>" +
    "<td class=\"border-right text-center\">"+ data['unit'] +"</td>" +
    "<td class=\"border-right text-right\">"+ parseFloat(data['qty']) +"</td>" +
    "<td class=\"border-right text-center\"><input type='text' name='parts["+id+"][plan_qty]' value='"+ 
    	(parseFloat(data['plan_qty']) || '') +"' class='auto_width_right' " + ((data['is_requested'] == 1) ? "disabled" : "") + "/></td>" +
    "<td class=\"border-right text-center\"></td>" +
    "</tr>");
		
  return row;
}

function row_template_material_requests(data) {
  var forward	= host + "/account/material-requests-show.php?mrid="+ data['id'] + "";
  var row		= $("<tr forward=\""+ forward +"\"><td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ (data['po_number'] || '--') +"</a></td>" +
    "<td class=\"border-right text-center\">"+ data['lot_no'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['brand'] +"</td>" +
    "<td class=\"border-right \">"+ data['product_code'] +"</td>" +
    "<td class=\"border-right text-center\">"+ data['request_date'] +"</td>" +
    "</tr>");

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

// function row_template_purchase_material(data) {
  // var id		= data['id'];
  // var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  // var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
// 
  // row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  // row.append('<td class="border-right text-center" replace="#{index}"></td>');
  // row.append('<td class="border-right">'+ data['code'] +'</td>');
  // row.append('<td class="border-right">'+ data['description'] +'</td>');
  // row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
  // row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  // row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price"/></td>');
  // row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
//            	
  // row.find('.currency').formatCurrency({region:"en-PH"});
  // return row;   
// }

function row_template_purchase_material(data) {
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['item_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));
  
  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item" /><input type="hidden" name="items['+id+'][item_id]" value="'+ data['item_id'] +'" /></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right">'+ data['code'] +'</td>');
  row.append('<td class="border-right">'+ data['description'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][item_price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price"/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
 
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
  
  row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-all\"/></td>");
  row.append("<td class=\"border-right\">"+ data['material_code'] +"</td>");
  row.append("<td class=\"border-right\">"+ data['material_description'] +"</td>");
  row.append("<td class=\"border-right text-right\">"+ data['quantity'] +"</td>");
  row.append("<td>"+ (data['remarks'] || '--') +"</td>");
  
  return row;
}

function row_template_receiving(data) {
  var row		= $("<tr quantity=\""+ data['quantity'] + "\" id=\""+ data['id'] +"\"" + 
                    "item=\""+ data['id'] +"\" title=\""+ data['code'] +"\" invoice=\""+ (data['invoice'] || '') +"\"" +
                    "received=\""+ (data['received'] || 0) +"\" remarks=\""+ (data['remarks'] || '') +"\" \"></tr>");
  
  //var code = data['status'] != "Complete" ? "<a href=\"#\">"+ data['code'] +"</a>" : data['code']; //remove line
  var code = "<a href=\"#\">"+ data['code'] +"</a>";
  
  row.append("<td class=\"border-right text-center magenta\">"+ (data['invoice'] || '') +"</td>");
  row.append("<td class=\"border-right\">"+ code +"</td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ data['quantity'] +"</td>");
  //row.append("<td class=\"border-right text-right numbers\">"+ (data['delivered'] || 0) +"</td>");
  row.append("<td class=\"border-right text-right numbers\">"+ (data['received'] || 0) +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['unit'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['status'] +"</td>");
                  
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

function row_template_deliveries(data) {
  var forward	= host + "/account/deliveries-show.php?id="+ data['id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  
  row.append("<td class=\"border-right text-center\"><a href=\""+ forward +"\">"+ data['purchase_number'] +"</a></td>");
  row.append("<td class=\"border-right\">"+ data['supplier_name'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ data['status'] +"</td>");
  row.append("<td class=\"border-right text-center\">"+ dtime_basic(data['delivery_date']) +"</td>");
  
  row.find('.text-currency').formatCurrency({region:"en-PH"});
  return row;
}

function row_template_delivery_items_read_only(data) {
  var forward	= host + "/account/materials-show.php?mid="+ data['item_id'] +"";
  var row		= $("<tr forward=\""+ forward +"\"></tr>");
  
  //row.append("<td class=\"border-right text-center\"><input type=\"checkbox\" class=\"chk-all\" disabled/></td>");
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append("<td class=\"border-right text-center\"><a target=\"_blank\"  href=\""+ forward +"\">"+ data['code'] +"</a></td>");
  row.append("<td class=\"border-right\">"+ data['description'] +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ parseFloat(data['quantity']) +"</td>");
  row.append("<td class=\"border-right text-right numbers \">"+ (parseFloat(data['delivered']) || 0) +"</td>");
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

function row_template_parts_tree(data) {
	var forward	= host + "/account/materials-show.php?mid="+ data['material_id'] + "";
  var id		= data['id'];
  var row		= $('<tr id="mat-'+ data['material_id'] +'"></tr>');
  var amount	= parseFloat(data['quantity'] * clean_currency(data['item_price']));

  row.append('<td class="border-right text-center"><input type="checkbox" value="" class="chk-item"/></td>');
  row.append('<td class="border-right text-center" replace="#{index}"></td>');
  row.append('<td class="border-right"><a href="'+ forward +'">'+ data['code'] +'</a><input type="hidden" name="items['+id+'][material_id]" value="'+ (data['material_id'] || '') +'" /></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][quantity]" value="'+ data['quantity'] +'" class="text-field-smallest text-right get-amount item-quantity" autocomplete="off"/></td>');
  row.append('<td class="border-right text-center">'+ data['unit'] +'</td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][price]" value="'+ data['item_price'] +'" class="currency text-field-price text-right get-amount item-price" disabled /></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items[amount]" value="'+ amount +'" class="currency text-field-price text-right item-amount" disabled/></td>');
  row.append('<td class="border-right text-center"><input type="text" name="items['+id+'][remarks]" value="'+ (data['remarks'] || '') +'" class="text-field-max" autocomplete="off"/></td>');
           	
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
