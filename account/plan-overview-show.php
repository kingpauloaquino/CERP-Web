<?php
  /* Module: Plan Overview  */
  $capability_key = 'show_plan_overview';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		$active_year = date('Y');
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?> &raquo; <span class="red"><?php echo $active_year?></span></span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<input type="hidden" id="active_year" value="<?php echo $active_year?>"/>
	    	<div style="min-width:400px; width:auto; height:300px; margin:5px; padding:4px; border:solid 1px #eee">
					<div id="chart" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
				</div>
      	<br/>
				<div id="grid-overview" class="grid jq-grid" style="min-height:146px;">
					<table cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<td colspan="2" class="border-right text-center">CRESC</td>
								<td width="70" class="border-right text-center">Prev</td>
								<td width="60" class="border-right text-center">Jan</td>
								<td width="60" class="border-right text-center">Feb</td>
								<td width="60" class="border-right text-center">Mar</td>
								<td width="60" class="border-right text-center">Apr</td>
								<td width="60" class="border-right text-center">May</td>
								<td width="60" class="border-right text-center">Jun</td>
								<td width="60" class="border-right text-center">Jul</td>
								<td width="60" class="border-right text-center">Aug</td>
								<td width="60" class="border-right text-center">Sep</td>
								<td width="60" class="border-right text-center">Oct</td>
								<td width="60" class="border-right text-center">Nov</td>
								<td width="60" class="border-right text-center">Dec</td>
								<td width="70" class="border-right text-center">Month Lap</td>
								<td width="80" class="border-right text-center">Total</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php
									$shipment_all_plan = $DB->Find('shipment_plan_view', array('columns'=> '*', 'conditions'=> 'plan_year='.$active_year));
									$production_all_plan = $DB->Find('shipment_plan_view', array('columns'=> '*', 'conditions'=> 'plan_year='.$active_year));
									$stock_all_plan = $DB->Find('shipment_plan_view', array('columns'=> '*', 'conditions'=> 'plan_year='.$active_year));
								?>
								<td rowspan="3" class="border-right text-center">Ship</td>
								<td class="border-right text-center">Plan</td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers shipment-plan-jan"><?php echo $shipment_all_plan['jan']?></td>
								<td class="border-right text-right numbers shipment-plan-feb"><?php echo $shipment_all_plan['feb']?></td>
								<td class="border-right text-right numbers shipment-plan-mar"><?php echo $shipment_all_plan['mar']?></td>
								<td class="border-right text-right numbers shipment-plan-apr"><?php echo $shipment_all_plan['apr']?></td>
								<td class="border-right text-right numbers shipment-plan-may"><?php echo $shipment_all_plan['may']?></td>
								<td class="border-right text-right numbers shipment-plan-jun"><?php echo $shipment_all_plan['jun']?></td>
								<td class="border-right text-right numbers shipment-plan-jul"><?php echo $shipment_all_plan['jul']?></td>
								<td class="border-right text-right numbers shipment-plan-aug"><?php echo $shipment_all_plan['aug']?></td>
								<td class="border-right text-right numbers shipment-plan-sep"><?php echo $shipment_all_plan['sep']?></td>
								<td class="border-right text-right numbers shipment-plan-oct"><?php echo $shipment_all_plan['oct']?></td>
								<td class="border-right text-right numbers shipment-plan-nov"><?php echo $shipment_all_plan['nov']?></td>
								<td class="border-right text-right numbers shipment-plan-dec"><?php echo $shipment_all_plan['dece']?></td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers"><?php echo $shipment_all_plan['total_qty']?></td>
							</tr>	
							<tr>
								<td class="border-right text-center">Actual</td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers production-plan-jan"><?php echo $production_all_plan['jan']?></td>
								<td class="border-right text-right numbers production-plan-feb"><?php echo $production_all_plan['feb']?></td>
								<td class="border-right text-right numbers production-plan-mar"><?php echo $production_all_plan['mar']?></td>
								<td class="border-right text-right numbers production-plan-apr"><?php echo $production_all_plan['apr']?></td>
								<td class="border-right text-right numbers production-plan-may"><?php echo $production_all_plan['may']?></td>
								<td class="border-right text-right numbers production-plan-jun"><?php echo $production_all_plan['jun']?></td>
								<td class="border-right text-right numbers production-plan-jul"><?php echo $production_all_plan['jul']?></td>
								<td class="border-right text-right numbers production-plan-aug"><?php echo $production_all_plan['aug']?></td>
								<td class="border-right text-right numbers production-plan-sep"><?php echo $production_all_plan['sep']?></td>
								<td class="border-right text-right numbers production-plan-oct"><?php echo $production_all_plan['oct']?></td>
								<td class="border-right text-right numbers production-plan-nov"><?php echo $production_all_plan['nov']?></td>
								<td class="border-right text-right numbers production-plan-dec"><?php echo $production_all_plan['dece']?></td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers"><?php echo $production_all_plan['total_qty']?></td>
							</tr>	
							<tr>
								<td class="border-right text-center">%</td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers stock-plan-jan"><?php echo $stock_all_plan['jan']?></td>
								<td class="border-right text-right numbers stock-plan-feb"><?php echo $stock_all_plan['feb']?></td>
								<td class="border-right text-right numbers stock-plan-mar"><?php echo $stock_all_plan['mar']?></td>
								<td class="border-right text-right numbers stock-plan-apr"><?php echo $stock_all_plan['apr']?></td>
								<td class="border-right text-right numbers stock-plan-may"><?php echo $stock_all_plan['may']?></td>
								<td class="border-right text-right numbers stock-plan-jun"><?php echo $stock_all_plan['jun']?></td>
								<td class="border-right text-right numbers stock-plan-jul"><?php echo $stock_all_plan['jul']?></td>
								<td class="border-right text-right numbers stock-plan-aug"><?php echo $stock_all_plan['aug']?></td>
								<td class="border-right text-right numbers stock-plan-sep"><?php echo $stock_all_plan['sep']?></td>
								<td class="border-right text-right numbers stock-plan-oct"><?php echo $stock_all_plan['oct']?></td>
								<td class="border-right text-right numbers stock-plan-nov"><?php echo $stock_all_plan['nov']?></td>
								<td class="border-right text-right numbers stock-plan-dec"><?php echo $stock_all_plan['dece']?></td>
								<td class="border-right text-right numbers">0</td>
								<td class="border-right text-right numbers"><?php echo $stock_all_plan['total_qty']?></td>
							</tr>	
						</tbody>
					</table>
				</div>
      </form>
		</div>
	</div>

<script type="text/javascript">
	$(function () {
    var chart;
    $(document).ready(function() {
    	var options = {
            chart: {
                renderTo: 'chart',
                type: 'column',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Production Plan Overview',
                x: -20 //center
            },
            subtitle: {
                text: 'Fiscal Year '+$('#active_year').val(),
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Quantity'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y +' pcs.';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: []
            // series: [{
                // name: 'Shipment',
                // data: [835201, 881343, 699886, 409478, 375667, 586020, 558080, 944550, 1509150, 1510000, 1565950, 473390],
                // color: '#ed3b83'
            // }, {
                // name: 'Production',
                // data: [835201, 881343, 800046, 621518, 824490, 1011370, 1080430, 1308600, 755600, 744100, 760550, 354090],
                // color: '#235d79'
            // }, {
                // name: 'Stock',
                // data: [462680, 462680, 562840, 774880, 1223703, 1649053, 2171403, 2535453, 1781903, 1016003, 210603, 91303],
                // color: '#bfad30'
            // }]
        };
    		options.series.push({
    			 name: 'Shipment',
            data: [
            			parseInt($('.shipment-plan-jan').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-feb').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-mar').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-apr').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-may').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-jun').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-jul').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-aug').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-sep').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-oct').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-nov').html().replace(/,/g, ''), 10), 
            			parseInt($('.shipment-plan-dec').html().replace(/,/g, ''), 10)
            			],
            color: '#ed3b83'
    		})
    		options.series.push({
    			 name: 'Production',
            data: [
            			parseInt($('.production-plan-jan').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-feb').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-mar').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-apr').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-may').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-jun').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-jul').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-aug').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-sep').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-oct').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-nov').html().replace(/,/g, ''), 10), 
            			parseInt($('.production-plan-dec').html().replace(/,/g, ''), 10)
            			],
            color: '#235d79'
    		})
    		options.series.push({
    			 name: 'Stock',
            data: [
            			parseInt($('.stock-plan-jan').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-feb').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-mar').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-apr').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-may').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-jun').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-jul').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-aug').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-sep').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-oct').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-nov').html().replace(/,/g, ''), 10), 
            			parseInt($('.stock-plan-dec').html().replace(/,/g, ''), 10)
            			],
            color: '#bfad30'
    		})
    	
        chart = new Highcharts.Chart(options);
    });
    
});
</script>
<script src="../Highcharts/js/highcharts.js"></script>
<script src="../Highcharts/js/modules/exporting.js"></script>

<?php }
require('footer.php'); ?>