   
   <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-sm-12">
          		<h5 class="text-primary" id="page-heading">My Bill Entries</h5>
          		<hr/>
          		
          		<div class="row mb-4">
          		
        		<div class="col">
        			<label>Company</label>
        			<select id="com_filter" class="form-control">
        				<option value="">Select Company</option>
        				<?php foreach($companies as $company) { ?>
        					<option value="<?php echo $company['cid']; ?>" <?php if($company['cid'] == $this->uri->segment('2')){ echo 'selected'; }?>>
        						<?php echo substr($company['name'],0,25); ?>
        					</option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col-sm-2">
        			<label>Cost-center</label>
        			<select id="costc_filter" class="form-control">
        				<option value="">Select Costcenter</option>
        				<?php foreach ($cost_centers as $cost_center){ ?>
        					<option value="<?php echo $cost_center['costc_id']; ?>" <?php if($cost_center['costc_id'] == $this->uri->segment('3')){ echo 'selected'; }?>><?php echo $cost_center['name']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col-sm-2">
        			<label>Location</label>
        			<select id="location_filter" class="form-control">
        				<option value="">Select Location</option>
        				<?php foreach ($locations as $location){ ?>
        					<option value="<?php echo $location['loc_id']; ?>" <?php if($location['loc_id'] == $this->uri->segment('4')){ echo 'selected'; }?>><?php echo $location['name']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col">
        			<label>Service no.</label>
        			<select id="serviceno" class="form-control">
        				<option value="">Select Serviceno</option>
        				<?php foreach($service_no as $serviceno) { ?>
        					<option value="<?php echo $serviceno['mid'];?>"><?php echo $serviceno['bpno']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<?php /*?>
        		<div class="col-sm-2">
        			<label>Status</label>
        			<select id="status_filter" class="form-control">
        				<option value="">Select Status</option>
        				<option value="date_passed" <?php if('date_passed' == $this->input->get('status',true)){ echo 'selected'; }?>>OVER DUE</option>
        				<option value="today" <?php if('today' == $this->input->get('status',true)){ echo 'selected'; }?>>URGENT</option>
        				<option value="date_remaining" <?php if('date_remaining' == $this->input->get('status',true)){ echo 'selected'; }?>>DUE</option>
        				<option value="not_filled" <?php if('not_filled' == $this->input->get('status',true)){ echo 'selected'; }?>>NOT FILLED</option>
        			</select>
        		</div>
        		<? */ ?>
        		<div class="col mt-4">
        			<input class="btn btn-success mt-2" type="button" id="filter" value="Search" />
        		</div>
        	</div>
          		
          		<?php echo $this->session->flashdata('msg'); ?>
          		<div class="table-responsive">
          		<table class="table table-bordered table-sm text-sm border" id="bill_test">
          			<thead class="bg-light ">
          				<tr class="bg-dark">
          					<th>S.No.</th>
          					<th>Service No.</th>
          					<th>Company</th>
          					<th>Cost-Center</th>
          					<th>Location</th>
          					<th>Billing Period From</th>
          					<th>Billing Period To</th>
          					<th>Bill No.</th>
          					<th>Date of Bill</th>
          					<th>Due Date</th>
          					<th>Current Reading</th>
          					<th>Reading Date</th>
          					<th>Previous Reading</th>
          					<th>Previous Reading Date</th>
          					<th>Total Bill</th>
          					<th>Past Dues</th>
          					<th>Payable Amount</th>
          					<th>Gross Payable Amount</th>
          					<?php if($this->session->userdata('role')=='super_admin'){?>
          					<th>Uploaded By</th>
          					<?php } ?>
          				</tr>
          			</thead>
          			<tbody id="bill_list">
          			
          				<?php if(count($bills)>0){ 
          				    $c=0;
          				    $bgColor = '';
          				    foreach($bills as $bill){ 
          				        if($bill['status'] == 'Not Filled'){
          				            $bgColor = '';
          				        } else {
          				            $bgColor = 'bg-success';
          				        }
          				?>
          					<tr <?php echo $bgColor; ?>>
          						<td><?php echo ++$c; ?></td>	
          						<td><?php echo $bill['bpno']; ?></td>
          						<td><?php echo $bill['companyName']; ?></td>
          						<td><?php echo $bill['costcenterName']; ?></td>
          						<td><?php echo $bill['locationName']; ?></td>
          						
								<td>
									<?php
								        if($bill['from_date'] != '')
								            echo date('d/m/Y',strtotime($bill['from_date'])); 
								    ?>
								</td>
								<td>
									<?php 
									   if($bill['to_date'] != '')
									       echo date('d/m/Y',strtotime($bill['to_date'])); 
									?>
								</td>
								<td><a href="<?php echo base_url(); ?>bill-upload/<?php echo $bill['bill_id']; ?>"><?php echo $bill['bill_no']; ?></a></td>
          						<td>
          							<?php
          						        if($bill['date_of_bill'] != '')
          						        echo date('d/m/Y',strtotime($bill['date_of_bill'])); 
          						    ?>
          						</td>
								<td>
									<?php
									   if($bill['due_date'] != '')
									   echo date('d/m/Y',strtotime($bill['due_date'])); 
									?>
								</td>
								<td><?php echo $bill['reading']; ?></td>
								<td>
									<?php
									   if($bill['reading_date'] != '')
									   echo date('d/m/Y',strtotime($bill['reading_date'])); 
									?>
								</td>
								<td><?php echo $bill['previous_reading']; ?></td>
								<td>
									<?php 
									if($bill['previous_reading_date'] != '')
									echo date('d/m/Y',strtotime($bill['previous_reading_date'])); 
								    ?>
								</td>
								<td><?php echo $bill['total_bill']; ?></td>
								<td><?php echo $bill['past_dues']; ?></td>
								<td><?php echo $bill['payable_amount']; ?></td>
								<td><?php echo $bill['gross_amount']; ?></td>
								<?php if($this->session->userdata('role') == 'super_admin'){ ?>
									<td>
										<?php echo $bill['fname'].' '.$bill['lname']; ?>
									</td>
								<?php }?>
          					</tr>
          					
          				<?php }} else { ?>
          					<tr><td colspan="18" class="text-center">No Record Found.</td></tr>
          				<?php } ?>
          			</tbody>
          		</table>
          		</div>
          	</div>
          </div>
          
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    

    <script>
    const baseUrl = $('#base_url').val();
    $('#serviceno').select2();
    initialise_datatable();

    $(document).on('change','#com_filter',function(){
		var company = $(this).val();
		$.ajax({
            url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${company}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
            	if(response.status == 200){
            		var x = '<option value="">Select Cost-center</option>';
            		$.each(response.data,function(key,value){
            			x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
            		});
            		$('#costc_filter').html(x);
            	} else {
            		$('#costc_filter').html('<option value="">Select Cost-center</option>');
            	}
            	$('#location_filter').html('<option value="">Select Location</option>');
            	
            	$('#loaderModal').modal('toggle');
            }
       });		
	});
	
	$(document).on('change','#costc_filter',function(){
		var company = $('#com_filter').val();
		var costcenter = $(this).val();
		$.ajax({
            url: `${baseUrl}Location_ctrl/get_my_location/${company}/${costcenter}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
            	if(response.status == 200){
            		var x = '<option value="">Select Location</option>';
            		$.each(response.data,function(key,value){
            			x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
            		});
            		$('#location_filter').html(x);
            	} else {
            		$('#location_filter').html('<option value="">Select Location</option>');
            	}
            	$('#loaderModal').modal('toggle');
            }
       });		
	});
	
	
	$(document).on('change','#location_filter',function(){
		var company = $('#com_filter').val();
		var costcenter = $('#costc_filter').val();
		var location = $(this).val();
		$.ajax({
            url: `${baseUrl}Meter_ctrl/get_my_meters/${company}/${costcenter}/${location}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
            	if(response.status == 200){
            		var x = '<option value="">Select Service No</option>';
            		$.each(response.data,function(key,value){
            			x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
            		});
            		$('#serviceno').html(x).select2();
            	} else {
            		$('#serviceno').html('<option value="">Select Service No</option>');
            	}
            	$('#loaderModal').modal('toggle');
            }
       });		
	});
    
    
    $(document).on('click','#filter',function(){
    	const company = $('#com_filter').val();
    	const costcenter = $('#costc_filter').val();
    	const location = $('#location_filter').val();
    	const status = $('#status_filter').val();
    	
    	
    	if($('#serviceno').val() != ''){
    		var serviceno = $('#serviceno option:selected').text();
    	} else {
    		var serviceno = '';
    	}
    	
    	$.ajax({
            url: `${baseUrl}bill-list`,
            method: "POST",
            dataType: "json",
            data : {
            	'companyId' : company,
            	'costCenterId' : costcenter,
            	'location' : location,
            	'sno' : serviceno,
            	'status' : status
            },
            beforeSend: function(){
            	$('#bill_test').DataTable().clear()
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
            	console.log(response);
            	if(response.status == 200){
            		var x = '';
            		$.each(response.data,function(key,value){
            			x = x + '<tr>'+
          						'<td>'+ parseInt(key+1) +'</td>'+
          						'<td>'+ value.bpno +'</td>'+
          						'<td>'+ value.companyName +'</td>'+
          						'<td>'+ value.costcenterName +'</td>'+
          						'<td>'+ value.locationName +'</td>';
          						if(value.from_date != ''){
          							x = x + '<td>'+ value.from_date +'</td>';
          						}
          						
          						if(value.to_date != ''){
          							x = x + '<td>'+ value.to_date +'</td>';
          						}
          						
								x = x + '<td><a href="'+ baseUrl+'bill-upload/'+ value.bill_id +'">'+ value.bill_no +'</a></td>';
								
								if(value.date_of_bill != ''){
          							x = x + '<td>'+ value.date_of_bill +'</td>';
          						}
          						if(value.due_date != ''){
          							x = x + '<td>'+ value.due_date +'</td>';
          						}
								
								x = x + '<td>'+ value.reading  +'</td>';
								if(value.reading_date != ''){
          							x = x + '<td>'+ value.reading_date +'</td>';
          						}
								x = x + '<td>'+ value.previous_reading +'</td>';
								if(value.previous_reading_date != ''){
          							x = x + '<td>'+ value.previous_reading_date +'</td>';
          						}
								x = x + '<td>' + value.total_bill +'</td>'+
        								'<td>' + value.past_dues +'</td>'+
        								'<td>' + value.payable_amount +'</td>'+
        								'<td>' + value.gross_amount +'</td>';
								
								<?php if($this->session->userdata('role') == 'super_admin'){ ?>
									x = x + '<td>'+ value.fname + ' '+ value.lname + '</td>';	      
								<?php } ?>
          					x = x + '</tr>';
            		});
            		$('#bill_list').html(x);
            	}
            	$('#loaderModal').modal('hide');
            }, complete(response){
            	initialise_datatable()
            }
       });
    });


    function initialise_datatable()
    {
    	$('#bill_test').DataTable({
	        	dom: 'Bfrtip',
	        	paging: false,
	        	buttons: [
	            	'copy', 'csv', 'excel', 'pdf', 'print'
	        	]
	    	});
    }
    </script>
