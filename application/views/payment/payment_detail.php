    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          		<span class="text-primary" id="page-heading">Bill Payment Details</span>
              <!-- <span class="pull-right" style="float: right;">
                <a class="btn btn-sm btn-primary" href="<?php //echo base_url('Show-Meter-Reading'); ?>">Your Pending Readings</a>
              </span> -->
          		<hr/>
              <div class="row">
                <div class="col-12">
                <?php echo $this->session->flashdata('msg'); ?>
                <form name="f1" method="POST" enctype='multipart/form-data' action="<?php echo base_url();?>payment">
                    <div class="row">
                    	<div class="col-md-3">
                            <label for="inputEmail3" class="col-form-label text-xs">Company</label>
                            <div class="col-sm-12">
                              <select id="company" name="company" class="form-control">
                                    <option value="">Select Company</option>
                                    <?php foreach($companies as $company){ ?>
                                      <option value="<?php echo $company['cid']; ?>"><?php echo $company['name']; ?></option>
                                    <?php } ?>
                                  </select>
                                <?php echo form_error('company'); ?>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="inputEmail3" class="col-form-label text-xs">Cost-Center</label>
                            <div class="col-sm-12">
                                <select id="costcenter" name="costcenter" class="form-control">
                                  <option value="">Select Cost-Center</option>
                                </select>
                              <?php echo form_error('costcenter'); ?>
                          	</div>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="inputEmail3" class="col-form-label text-xs">Location</label>
                            <div class="col-sm-12">
                            	<select id="location" name="location" class="form-control">
                              		<option value="">Select Location</option>
                            	</select>
                          		<?php echo form_error('location'); ?>
                          	</div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="inputEmail3" class="col-form-label text-xs">Service No</label>
                            <div class="col-sm-12">
                            	<select id="serviceno" name="serviceno" class="form-control">
                              		<option value="">Select Serviceno</option>
                              		<?php foreach($service_no as $s){ ?>
                              		    <option value="<?php echo $s['mid']; ?>"><?php echo $s['bpno']; ?></option>
                              		<?php }?>
                            	</select>
                          		<?php echo form_error('serviceno'); ?>
                          	</div>
                        </div>
                        
                    	
                    	<div class="col-md-2 mt-4">
                    		<input type="button" class="btn btn-primary mt-2" name="" value="Search" id="search_btn" />	
                    	</div>
                    </div>
                  	
                  	<div class="table-responsive mt-3">
                  		<table class="table table-bordered table-striped text-sm text-center" id="billTable">
                  			<thead class="bg-light">
                  				<tr>
                  					<th>S.No.</th>
                  					<th>Bill No.</th>
                  					<th>Service No.</th>
                  					<th>Company</th>
                  					<th>Cost-Center</th>
                  					<th>Location</th>
                  					<th>Total Bill</th>
                  					<th>Net Amount</th>
                  					<th>Gross Amount</th>
                  					<th>Payment Amount</th>
                  					<th>Bill Date</th>
                  					<th>Due Date</th>
                  					<th>Payment Date</th>
                  				</tr>
                  			</thead>
                  			<tbody id="meter-paymets">
                  			</tbody>
                  		</table>
                  	</div>
                  
                  </form>
                </div>
              </div>
          	

          
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    
    
    <div class="modal" id="meterEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body text-center meterEditbody">
          </div>
        </div>
      </div>
    </div>
    

    <script>
    const baseUrl = $('#base_url').val();
    $('#serviceno').select2();
    pyment_detail();
    
    $(document).on('click','#search_btn',function(){
    	pyment_detail();	
    });
    
   	function pyment_detail(){
   		$.ajax({
            url: `${baseUrl}Payment_ctrl/paymentDetails`,
            method: "POST",
            dataType: "json",
            data : {
            	'company' : $('#company').val(),
           		'costcenter' : $('#costcenter').val(),
           		'location' : $('#location').val(),
           		'sno' : $('#serviceno').val()
            },
            success(response){
                if(response.status == 200){
                	var x = '';
                	$.each(response.data,function(key,value){

                        if(!value.payment_amount)
                        {
                            value.payment_amount = '';
                        }

                		x = x + '<tr>'+
                					'<td>'+ parseInt(key + 1) +'</td>'+
                  					'<td><a href="javascript:void(0);" data-id="'+ value.bill_id +'" class="bill_edit">'+ value.bill_no +'<br>('+value.bill_month+')</a></td>'+
                  					'<td>'+ value.bpno +'</td>'+
                  					'<td>'+ value.company_name +'</td>'+
                  					'<td>'+ value.cost_center +'</td>'+
                  					'<td>'+ value.location_name +'</td>'+
                  					'<td>'+ value.total_bill +'</td>'+
                  					'<td>'+ value.payable_amount +'</td>'+
                  					'<td>'+ value.gross_amount +'</td>'+
                  					'<td>'+ value.payment_amount+'</td>'+
                  					'<td>'+ value.date_of_bill +'</td>'+
                  					'<td>'+ value.due_date +'</td>'+
                  					'<td>'+ value.payment_date +'</td>'+
                				'</tr>'; 
                	});
                	
                	$('#billTable').dataTable().fnClearTable();
    				$('#billTable').dataTable().fnDestroy();
                	$('#meter-paymets').html(x);
                	
                	$('#billTable').DataTable({
                       	"searching": true,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": false 
                    });
                    
                }
                
            }
        });
   }
    
    $('#pending-readingsk').DataTable();

    $(document).on('change','#company',function(){
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
                console.log(response);
                if(response.status == 200){
                    var x = '<option value="">Select Cost-Center</option>';
                    $.each(response.data,function(key,value){
                      x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                    });
                    $('#costcenter').html(x); 
                }
                else {
                  $('#costcenter').html('<option value="">Select Cost-Center</option>');
                }
              $('#loaderModal').modal('toggle');
            }
        });
    });

    $(document).on('change','#costcenter',function(){
      var costcenter = $(this).val();
      $.ajax({
            url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${costcenter}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
                console.log(response);
                if(response.status == 200){
                    var x = '<option value="">Select Location</option>';
                    $.each(response.data,function(key,value){
                      x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
                    });
                    $('#location').html(x); 
                }
                else {
                  $('#location').html('<option value="">Select Location</option>');
                }
              $('#loaderModal').modal('toggle');
            }
        });
    });

    $(document).on('change','#location',function(){
      var location = $(this).val();
      if(location > 0){
      $.ajax({
            url: `${baseUrl}Meter_ctrl/getMeterByLocationId/${location}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
                console.log(response);
                if(response.status == 200){
                    var x = '<option value="">Select Service No</option>';
                    $.each(response.data,function(key,value){
                      x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
                    });
                    $('#serviceno').html(x); 
                }
                else {
                  $('#serviceno').html('<option value="">Select Service No</option>');
                }
               $('#loaderModal').modal('toggle');
               $('#serviceno').select2();
            }
            
        });
      } else {
        $('#serviceno').html('<option value="">Select Service No</option>');
      }
    });
    
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });
    
    $(document).on('click','.bill_edit',function(){
    	var bill_id = $(this).data('id');
    	
    	$.ajax({
            url: `${baseUrl}Meter_ctrl/getbill_detail/${bill_id}`,
            method: "GET",
            dataType: "json",
            success(response){
                console.log(response);
                if(response.status == 200){

                if(!response.data.payment_amount)
                {
                    response.data.payment_amount = response.data.payable_amount;
                    response.data.payment_date = new Date().toDateInputValue();
                }

                 var x = '<div class="container-fluid">'+
                 	'<table width="100%" border="0" class="mb-3 text-left">'+
                    	'<tr>'+
                    		'<td>Service No.</td>'+
                    		'<td class="text-danger text-bold">: '+ response.data.bpno +'</td>'+
                    	'</tr>'+
                    	'<tr>'+
                    		'<td>Company</td>'+
                    		'<td>: '+ response.data.company_name +'</td>'+
                    	'</tr>'+
                    	'<tr>'+
                    		'<td>Cost Center</td>'+
                    		'<td>: '+ response.data.cost_center +'</td>'+
                    	'</tr>'+
                    	'<tr>'+
                    		'<td>Location</td>'+
                    		'<td>: '+ response.data.location_name +'</td>'+
                    	'</tr>'+
                    	'<tr>'+
                    		'<td>Bill No</td>'+
                    		'<td class="text-danger">: <span id="bill_no">'+ response.data.bill_no +'</span></td>'+
                    	'</tr>'+
                    	'<tr>'+
                    		'<td>Date of Bill</td>'+
                    		'<td>: '+ response.data.date_of_bill +'</td>'+
                    	'</tr>'+
                        '<tr>'+
                            '<td>Due Date</td>'+
                            '<td>: '+ response.data.due_date +'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Bill Amount</td>'+
                            '<td>: '+ response.data.payable_amount +'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td>Bill Month</td>'+
                            '<td>: '+ response.data.bill_month +'</td>'+
                        '</tr>'+
                    '</table>'+
            		'<div class="row">'+
                      '<div class="col-md-6">'+
                      	'<div class="form-group row">'+
                          	'<label for="inputEmail3" class="col-sm-3 col-form-label">Payment Amount</label>'+
                          	'<div class="col-sm-9">'+
                            	'<input type="text" name="payment_amount" id="payment_amount" value="'+ response.data.payment_amount +'" class="form-control">'+
							'</div>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                      '<div class="form-group row">'+
                          '<label for="inputEmail3" class="col-sm-3 col-form-label">Payment Date</label>'+
                          '<div class="col-sm-9">'+
                            '<input type="date" name="payment_date" id="payment_date" class="form-control" value="'+ response.data.payment_date +'">'+
                           '</div>'+
                      '</div>'+
                     '</div>'+
                     '<div class="col-md-6">'+
                      	'<div class="form-group row">'+
                          '<label for="inputEmail3" class="col-sm-3 col-form-label">Payment Type</label>'+
                          	'<div class="col-sm-9">'+
                            	'<select id="p_type" name="p_type" class="form-control">';
                            		
                            		var ptype = '';
                            		if(response.data.payment_type == 'cheque'){
                            			ptype = 'block';
                            			x = x + '<option value="cheque" selected>Cheque</option>'+
                            				'<option value="cash">Cash</option>';
                            		} else if(response.data.payment_type == 'online'){
                            			ptype = 'none';
                            			x = x + '<option value="cheque">Cheque</option>'+
                            					'<option value="online" selected>Online</option>'+
                            				'<option value="cash">Cash</option>';
                            		} else {
                            			ptype = 'none';
                            			x = x + '<option value="cheque">Cheque</option>'+
                            				'<option value="cash" selected>Cash</option>';
                            		}
                            	x = x + '</select>'+
                          	'</div>'+
                      	'</div>'+
                       '</div>';
                      
                      if(response.data.payment_type == 'online'){
                      	x = x + '<div class="col-md-6" id="checknobox">'+
                      		'<div class="form-group row">'+
                          		'<label for="" class="col-sm-3 col-form-label">Remark</label>'+
                          		'<div class="col-sm-9">'+
                            		'<input type="text" id="checkno" name="checkno" value="'+ response.data.check_no +'" class="form-control" placeholder="Remark">'+
                          		'</div>'+
                      		'</div>'+
                           '</div>'+
                       '</div>';
                      }
                      
                       x = x + '<div class="col-md-6" id="checknobox" style="display:'+ ptype +';">'+
                      		'<div class="form-group row">'+
                          		'<label for="inputEmail3" class="col-sm-3 col-form-label">Cheque No.</label>'+
                          		'<div class="col-sm-9">'+
                            		'<input type="text" id="checkno" name="checkno" value="'+ response.data.check_no +'" class="form-control" placeholder="Cheque no.">'+
                          		'</div>'+
                      		'</div>'+
                           '</div>'+
                       '</div>'+
                       '<div>'+

                            '<input type="button" value="Cancel" class="mr-2 btn btn-secondary"  data-dismiss="modal">'+
                       		'<input type="button" id="meter_payment_submit" value="Update" class="btn btn-success">'+
                       	'</div>'+
            		'</div>';	
            		
            		$('.meterEditbody').html(x);
                }
            }
        });
    	
    	$('#meterEdit').modal({
    		'show':true
    	});
   
    });
    
    $(document).on('change','#p_type',function(){
    	var x = $(this).val();
    	if(x == 'cheque'){
    		$('#checknobox').show();
    	} else {
    		$('#checknobox').hide();
    	}
    });
    
    
    $(document).on('click','#meter_payment_submit',function(){
    
    	$.ajax({
            url: `${baseUrl}Payment_ctrl/payment_submit`,
            method: "POST",
            dataType: "json",
            data : {
            	'bill_no' : $('#bill_no').text(),
            	'payment_amount' : $('#payment_amount').val(),
            	'payment_date' : $('#payment_date').val(),
            	'payment_type' : $('#p_type').val(),
            	'cheque_no' : $('#checkno').val()
            },
            success(response){
                if(response.status)
                {
                    $('#meterEdit').modal('toggle');
                    alert('Payment update successfully.');
                    pyment_detail();
                }else {
                    alert('Payment failed!');
                }
            	
            }
        });
    });
	
    </script>

    