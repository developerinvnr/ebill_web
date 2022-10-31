  <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-sm-12">
          		<h5 class="text-primary" id="page-heading">My Bill Report</h5>
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
        		<div class="col-sm-2">
        			<label>Users</label>
        			<select id="users" class="form-control">
        				<option value="">Select Users</option>
        				<?php foreach($users as $u){?>
        					<option value="<?php echo $u['uid']; ?>"><?php echo $u['fname'].' '.$u['lname']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col-sm-2">
        			<label>Status</label>
					<?php  $status = $this->input->get('status', TRUE); ?>
        			<select id="status" class="form-control">
        				<option value="">Payment Status</option>
        				<option value="paid">PAID</option>
        				<option value="unpaid" <?=isset($status)?($status == 'unpaid'?'selected':''):'';?>>UNPAID</option>
        			</select>
        		</div>
        		<div class="col mt-4">
        			<input class="btn btn-primary mt-2" type="button" id="filter" value="Search" />
        		</div>
        	</div>
          		
          		<?php echo $this->session->flashdata('msg'); ?>
          		<div class="table-responsive">
          		<table class="table table-bordered table-sm text-sm border" id="payment_report">
          			<thead class="bg-light ">
          				<tr class="bg-dark">
          					<th>S.No.</th>
          					<th>Service No.</th>
          					<th>Bill No.</th>
          					<th>Company</th>
          					<th>Cost Center</th>
          					<th>Location</th>
          					<th>Amount</th>
          					<th>Gross Amount</th>
          					<th>Payment Amount</th>
          					<th>Payment Date</th>
          					<th>Submitted By</th>
          					<th>Status</th>
          				</tr>
          			</thead>
          			<tbody id="payment_report_list">
          				
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
    
    bill_report();
    function bill_report(){
        $('#loaderModal').modal('show');
    	$.ajax({
            url: `${baseUrl}Payment_ctrl/payment_report_ajax`,
            method: "POST",
            dataType: "json",
            data : {
            	'u_id'	: $('#users').val(),
            	'cid'	: $('#com_filter').val(),
            	'costc_id' : $('#costc_filter').val(),
            	'loc_id' : $('#location_filter').val(),
            	'sno_id'	: $('#serviceno').val(),
            	'status' : $('#status').val()
            },
            success(response){
            	$('#payment_report').dataTable().fnClearTable();
    			$('#payment_report').dataTable().fnDestroy();
    			
            	if(response.status == 200){
                	var x = '';
                	$.each(response.data,function(key,value){
                		x = x+ '<tr><td>'+ parseInt(key + 1) +'</td>'+
              					'<td>'+ value.bpno +'</td>'+
              					'<td>'+ value.bill_no +'</td>'+
              					'<td>'+ value.company_name	 +'</td>'+
              					'<td>'+ value.costcenter_name +'</td>'+
              					'<td>'+ value.location_name +'</td>'+
              					'<td>'+ value.payable_amount +'</td>'+
              					'<td>'+ value.gross_amount +'</td>'+
              					'<td>'+ value.payment_amount +'</td>'+
              					'<td>'+ value.payment_date +'</td>'+
              					'<td>'+ value.user_name +'</td>'+
              					'<td>'+ value.bill_status +'</td></tr>';
                	});
                	$('#payment_report_list').html(x);
            	} else {
            		$('#payment_report_list').html('NO record found');
            	}
            	
                $('#payment_report').DataTable({
                       	"searching": true,
                //         "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": false 
                    });
            	$('#loaderModal').modal('hide');
            }
       });
    }
    
    $(document).on('click','#filter',function(){
    	bill_report();
    });
    
    
    </script>
