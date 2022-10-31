<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style>
   table.dataTable tbody th, table.dataTable tbody td {
       padding: 2px 5px;
       vertical-align: middle;
   }
   .bg-info-100 {
       background-color: #17a2b840 !important;
   }
   .bg-success-100 {
       background-color: #28a7456b !important;
   }
   .bg-primary-100 {
       background-color: #007bff5e !important;
   }
</style>
<div class="content-wrapper ml-0">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6"></div>
         </div>
      </div>
   </div>
   <section class="content">	
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                  <div class="inner">
                     <h3><span id="over_due">00</span></h3>
                     <p>Bills Over Due</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-bag"></i>
                  </div>
                  <a href="<?=base_url('bill-report?status=over_due');?>" class="small-box-footer">Total No of Meters :<span id="total_meter">00</span> 
                  <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-success">
                  <div class="inner">
                     <h3><span id="urgent_bill">00</span></h3>
                     <p>Bills need to upload Today</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="<?=base_url('bill-report?status=urgent');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-warning">
                  <div class="inner">
                     <h3><span>00</span></h3>
                     <p>--</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-person-add"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-danger">
                  <div class="inner">
                     <h3><span id="pending_payments">00</span></h3>
                     <p>Bills Payment Pending</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="<?=base_url('payment/payment-report?status=unpaid');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
         </div>
         <div class="row">
            <section class="col-lg-5 connectedSortable">
               <div class="card">
                  <div class="card-body">
                     <div class="tab-content p-0">
                        <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 400px;">
                        	<div id="piechart" style="height: 400px;"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            
            <section class="col-lg-7 connectedSortable">
               <div class="card bg-gradient-default">
                  <div class="card-header border-0">
                     <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        OverAll Bills
                     </h3> <br/><br/>
                     <div class="card-tools">
                        <div class="row">
                           <div class="col-md-6 mb-2">
                              <select id="company" class="form-control">
                                 <option value="">All Company</option>
                                 <?php foreach($companies as $company){ ?>
                                    <option value="<?php echo $company['cid'];?>"><?php echo $company['name']; ?></option>
                                 <?php }?>
                              </select>
                           </div>
                           <div class="col-md-3 mb-2">
                              <select id="month" class="form-control">
                                 <option value="">Select Month</option>
                                 <?php for($i=1;$i<=12;$i++){ ?>
                                 <option value="<?php echo $i; ?>" <?php if($i == date('n')){ echo "selected"; } ?>><?php echo DateTime::createFromFormat('!m', $i)->format('F');?></option>
                                 <?php } ?>
                              </select>
                           </div>
                           <div class="col-md-3 mb-2">
                              <select id="year" class="form-control">
                                 <option value="">Select Year</option>
                                 <option value="2021" <?php if(date('Y') == '2021'){ echo "selected"; }?>>2021</option>
                                 <option value="2022" <?php if(date('Y') == '2022'){ echo "selected"; }?>>2022</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <div id="piechart" style="height: 400px;">
                     	<!--<div id="piechart2" style="height: 400px;"></div>-->
                     	<div id="columnchart_values" style="height: 400px;"></div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
         <section class="mt-2">
            <!-- Default box -->
            <div class="card">
            <div class="card-header border-0">
                  <h3 class="card-title">
                     <i class="fas fa-building mr-1"></i>
                     Company wise Overall Bills
                  </h3>
               </div>
               <div class="card-body">
                  <div class="row">          		
                     <div class="col-md-4">
                        <label>Company</label>
                        <select id="search_br_company" class="form-control select2">
                           <option value="All">All</option>
                           <?php foreach($companies as $company) { ?>
                              <option value="<?php echo $company['cid']; ?>" <?php if($company['cid'] == $this->uri->segment('2')){ echo 'selected'; }?>>
                                 <?php echo substr($company['name'],0,25); ?>
                              </option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label>Month</label>
                        <select id="search_br_month" class="form-control select2">
                           <option value="All">All</option>
                           <?php for($i=1;$i<=12;$i++){ ?>
                           <option value="<?php echo $i; ?>" <?php if($i == date('n')){ echo "selected"; } ?>><?php echo DateTime::createFromFormat('!m', $i)->format('F');?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label>Year</label>
                        <select id="search_br_year" class="form-control select2">
                           <option value="2021" <?php if(date('Y') == '2021'){ echo "selected"; }?>>2021</option>
                           <option value="2022" <?php if(date('Y') == '2022'){ echo "selected"; }?>>2022</option>
                        </select>
                     </div>
                     <div class="col-md-2 mt-2">
                        <input class="btn btn-success mt-4" type="button" id="filter" value="Search" onclick="search_bill_report()"/>
                     </div>
                  </div>
                  <br>
                  <div class="table-responsive" id="search_br_result">
                     
                  </div>
               </div>
            </div>
         </section>
         <section class="mt-2">
            <!-- Default box -->
            <div class="card">
            <div class="card-header border-0">
                  <h3 class="card-title">
                     <i class="fas fa-calendar mr-1"></i>
                     Month wise Overall Bills
                  </h3>
               </div>
               <div class="card-body">
                  <div class="row">                
                     <div class="col-md-4">
                        <label>Company</label>
                        <select id="search_bp_company" class="form-control select2">
                           <option value="All">All</option>
                           <?php foreach($companies as $company) { ?>
                              <option value="<?php echo $company['cid']; ?>" <?php if($company['cid'] == $this->uri->segment('2')){ echo 'selected'; }?>>
                                 <?php echo substr($company['name'],0,25); ?>
                              </option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label>Year</label>
                        <select id="search_bp_year" class="form-control select2">
                           <option value="2021" <?php if(date('Y') == '2021'){ echo "selected"; }?>>2021</option>
                           <option value="2022" <?php if(date('Y') == '2022'){ echo "selected"; }?>>2022</option>
                        </select>
                     </div>
                     <div class="col-md-3 pt-4">
                        <label class="custom-control custom-checkbox-inverse custom-checkbox mb-2">
                           <input id="search_bp_no" type="checkbox" class="custom-control-input">
                           <span class="custom-control-label">BP No.</span>
                        </label>
                     </div>
                     <div class="col-md-2 mt-2">
                        <input class="btn btn-success mt-4" type="button" value="Search" onclick="search_month_wise_bill_report()"/>
                     </div>
                  </div>
                  <br>
                  <div class="table-responsive" id="search_month_wise_bill_result">
                     
                  </div>
               </div>
            </div>
         </section>
      </div>
   </section>
</div>
<aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<div class="modal fade text-start" id="ebill_modal" role="dialog" aria-labelledby="ebill_modal_label" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <?php echo form_open(base_url(uri_string()), array('method' => 'post', 'id' => 'ebill_form')); ?>
            <div class="modal-header bg-primary text-white pt-2 pb-2">
               <h5 class="modal-title" id="ebill_modal_label">Company's Bill List</h5>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="company_bill_result">
               
            </div>
            <div class="modal-footer bg-light pt-2 pb-2">
               <button class="btn btn-danger" data-dismiss="modal"><i class="icon-cross2 font-size-base mr-1"></i> Close</button>
            </div>
         <?php echo form_close();?>
      </div>
   </div>
</div>
<script>
	$(document).ready(function(){
    	const baseUrl = $('#base_url').val();
    	chartData = [];
    	chartData2 = [];
    	barChartData = [];
    	
    	function drawchart(){	
              google.charts.load('current', {'packages':['corechart']});
              google.charts.setOnLoadCallback(drawChart);
              
              function drawChart() {
        
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows(chartData);
        
        		console.log(chartData);
        		console.log('pie');
                var options = {
                    'title':'Meter Bill Upload Detail',
                    'legend' : {position: 'left', textStyle: {color: 'blue', fontSize: 16}},
                    'legend' : {alignment: 'center'},
                    'titleTextStyle' : { color: 'red',bold:true},
                     pieHole: 0.3,
                    //'width':screen.width/2,
                    //'height':screen.height/2
                };
        
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
               
                google.visualization.events.addListener(chart, 'select', selectHandler2);
                
                function selectHandler2(e) {	
                    var selectedItem = chart.getSelection();
                    console.log(chartArray[selectedItem[0].row] );
                    if(selectedItem.length){
                        farmerList(chartArray[selectedItem[0].row].StateId);
                    }
                }
        
              }
         }
    	
    	
    	
    	function drawchart2(){	
              google.charts.load('current', {'packages':['corechart']});
              google.charts.setOnLoadCallback(drawChart3);
              
              function drawChart3() {
        
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows(chartData2);
        
                var options = {
                    'title':'Bill Payment Detail',
                    'legend' : {position: 'left', textStyle: {color: 'blue', fontSize: 16}},
                    'legend' : {alignment: 'center'},
                    'titleTextStyle' : { color: 'red',bold:true},
                     //pieHole: 0.4,
                    //'width':screen.width/2,
                    //'height':screen.height/2
                };
        
                var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
                chart.draw(data, options);
               
                google.visualization.events.addListener(chart, 'select', selectHandler2);
                
                function selectHandler2(e) {	
                    var selectedItem = chart.getSelection();
                    console.log(chartArray[selectedItem[0].row] );
                    if(selectedItem.length){
                        farmerList(chartArray[selectedItem[0].row].StateId);
                    }
                }
        
              }
         }
         
         
         function drawBarChart(){
         	 google.charts.load("current", {packages:['corechart']});
    		 google.charts.setOnLoadCallback(drawbarChart);
    		 
             function drawbarChart() {
             		var data = new google.visualization.DataTable();
             		data.addColumn('string', 'company');
                    data.addColumn('number', 'bill');
                    data.addColumn({type: 'string', role: 'style'});
                    data.addRows(barChartData);

                  var options = {
                    title: "Corresponding month bill",
                    width: 600,
                    height: 400,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                  };
                  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                  chart.draw(data, options);
              }
    	}
    	///bill upload detail///////////////
    	//////////////////////////////////////
    	fetch(`${baseUrl}dashboard_ctrl/bill_upload_data`)
      		.then(response => response.json())
      		.then(response => {      		
      			$('#over_due').html(response.data1['OVER DUE']);
      			$('#urgent_bill').html(response.data1['URGENT']);
      			$('#total_meter').html(response.data1['total_meters']);
      			$('#pending_payments').html(response.data1['payment_pending']);
      			var l = response.data.length;
      			var c=0;
      			while(c < l){
      				chartData.push([response.data[c].status, parseInt(response.data[c].total)]);
                    c++;
      			}
      		}).then(response =>{
      			drawchart();
      		});
      		
      	
      	$(document).on('change','#company,#month,#year',function(){
      		bill_payment_chart();
      	});
      		
      	/////////////// bill payments //////////////////
      	//////////////////////////////////////////////
      	bill_payment_chart();
      	function bill_payment_chart(){
      		let myColor = ['#FF5733 ','#E9D47C','#C3E97C','#90BAAB','#149065','#38B8C1','#0B9EA7','#0B2CA7','#6A7396'];
          	$.ajax({
                url: `${baseUrl}Dashboard_ctrl/bill_payments`,
                method: "POST",
                dataType: "json",
                data : {
      				'company' : $('#company').val(),
      				'month' : $('#month').val(),
      				'year' : $('#year').val()
      			},
                success(response){
                	chartData2 = [];
                	barChartData = [];
                    if(response.status == 200){
                    	$.each(response.data,function(key,value){
//                     		chartData2.push([value.company_name, parseInt(value.total_bill)]);
                    		barChartData.push([value.company_name, parseInt(value.total_bill),myColor[Math.floor((Math.random() * 10) + 1)]]);
                    	});
//                     	drawchart2();
                    	drawBarChart();
                    } else {
                    	barChartData = [];
                    	drawBarChart();
                    	console.log('No record found.');
                    }
                }
            });
        }	
  	});

   search_bill_report();
   search_month_wise_bill_report();

   function search_bill_report(){
      var search_br_company = $('#search_br_company').val();
      var search_br_month = $('#search_br_month').val();
      var search_br_year = $('#search_br_year').val();
      $.ajax({
        url: "<?php echo base_url('dashboard_ctrl/search_bill_report');?>",
        method: "POST",
        data : {
          'search_br_company' : search_br_company,
          'search_br_month' : search_br_month,
          'search_br_year' : search_br_year
        },
        success(data){
            $('#search_br_result').html('');
            $('#search_br_result').html(data);
            if(search_br_month == 'All')
            {
               var message = 'Year-'+search_br_year;
            } else {
               var message = $("#search_br_month option:selected").text()+'-'+search_br_year;
            }
            
            initialise_datatable('datatable_search_bill', message)
        },
         error(response){
            $('#search_br_result').html('No record found.');
         }
      });
   }
   

    function view_company_bill_report(company_id, month, year)
    {
         $.ajax({
         url: "<?php echo base_url('dashboard_ctrl/view_company_bill_report');?>",
         method: "POST",
         data : {
            'company_id' : company_id,
            'month' : month,
            'year' : year
         },
         success(data){
               $('#company_bill_result').html('');
               $('#company_bill_result').html(data);
               initialise_datatable('datatable_view_bill')
               $("#ebill_modal").modal({backdrop: 'static', keyboard: false});
         },
            error(response){
               $('#company_bill_result').html('No record found.');
            }
         });
    }

    function search_month_wise_bill_report(){
      var search_bp_company = $('#search_bp_company').val();
      var search_bp_year = $('#search_bp_year').val();

      if($("#search_bp_no").is(':checked')) 
      {
         var search_bp_no = 1;
      } else {
         var search_bp_no = 0;
      }

      $.ajax({
        url: "<?php echo base_url('dashboard_ctrl/search_month_wise_bill_report');?>",
        method: "POST",
        data : {
          'search_bp_company' : search_bp_company,
          'search_bp_year' : search_bp_year,
          'search_bp_no' : search_bp_no
        },
        success(data){
            $('#search_month_wise_bill_result').html('');
            $('#search_month_wise_bill_result').html(data);
            var message = 'Year-' + search_bp_year;
            initialise_datatable('datatable_month_wise_bill', message)
        },
         error(response){
            $('#search_month_wise_bill_result').html('No record found.');
         }
      });
   }

    function initialise_datatable(id, message = '')
    {
    	$('#'+id).DataTable({
	        	dom: 'Bfrtip',
            ordering: false,
            paging: false,
            
	        	buttons: [
	            	{
                extend: 'excel',
                   messageTop: message
               }
	        	]
	    	});
    }
</script>
