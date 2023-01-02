    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          		<span class="text-primary" id="page-heading">Add Bill Payment</span>
              <!-- <span class="pull-right" style="float: right;">
                <a class="btn btn-sm btn-primary" href="<?php //echo base_url('Show-Meter-Reading'); ?>">Your Pending Readings</a>
              </span> -->
          		<hr/>
              <div class="row">
                <div class="col-12">
                <?php echo $this->session->flashdata('msg'); ?>
                <form name="f1" method="POST" enctype='multipart/form-data' action="<?php echo base_url();?>payment/add-payment">
                <div class="form-group row">  
                
                    <div class="col-md-3">
                        <label for="inputEmail3" class="col-sm-12 col-form-label ">Service No.</label>
                        <div class="col-sm-12">
                          <select id="serviceno" name="serviceno" class="form-control">
                            <option value="">Select Service No</option>
                            <?php foreach($service_no as $sno){ ?>
                              <option value="<?php echo $sno['mid']; ?>"><?php echo $sno['bpno']; ?></option>
                            <?php }?>
                          </select>
                        <?php echo form_error('serviceno'); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="inputEmail3" class="col-sm-12 col-form-label">Company</label>
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
                    
                      
                      <div class="col-md-3">
                          <label for="inputEmail3" class="col-sm-12 col-form-label">Cost-Center</label>
                          <div class="col-sm-12">
                            <select id="costcenter" name="costcenter" class="form-control">
                              <option value="">Select Cost-Center</option>
                            </select>
                          <?php echo form_error('costcenter'); ?>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <label for="inputEmail3" class="col-sm-12 col-form-label">Location</label>
                          <div class="col-sm-12">
                            <select id="location" name="location" class="form-control">
                              <option value="">Select Location</option>
                            </select>
                          <?php echo form_error('location'); ?>
                          </div>
                      </div>
                      
                            </div>
                            <hr/>
                      <div class="form-group">
                      <div class="row">
                      	<div class="col-md-6">
                          	 <div class="form-group row">
                                  <label for="inputEmail3" class="col-sm-3 col-form-label">Bill No.</label>
                                  <div class="col-sm-9">
                                    <select class="form-control" name="bill_no" id="bill_no">
                                    	<option value="">Select Bill no.</option>
                                    </select>
                                  <?php echo form_error('bill_no'); ?>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group row">
                                  <label for="inputEmail3" class="col-sm-3 col-form-label">Date of Bill</label>
                                  <div class="col-sm-9">
                                    <input type="date" name="bill_date" id="bill_date" class="form-control" />
                                  <?php echo form_error('bill_date'); ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      
                      <div class="row">
                      	<div class="col-md-6">
                      		<div class="form-group row">
                                  <label for="inputEmail3" class="col-sm-3 col-form-label">Bill Amount</label>
                                  <div class="col-sm-9">
                                    <input type="text" name="bill_amount" id="bill_amount" class="form-control"/>
                                  <?php echo form_error('bill_amount'); ?>
                                  </div>
                              </div>
                      	</div>
                      	<div class="col-md-6">
                      		<div class="form-group row">
                                  <label for="inputEmail3" class="col-sm-3 col-form-label">Due Date</label>
                                  <div class="col-sm-9">
                                    <input type="date" name="due_date" id="due_date" class="form-control" />
                                  <?php echo form_error('due_date'); ?>
                                  </div>
                              </div>
                      	</div>
                      </div>
                      <hr/>
                      <div class="row">
                      <div class="col-md-6">
                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-3 col-form-label">Payment Amount</label>
                          <div class="col-sm-9">
                            <input type="text" name="payment_amount" id="payment_amount" class="form-control" />
                          <?php echo form_error('payment_amount'); ?>
                          </div>
                      </div>
                            </div>
                            <div class="col-md-6">
                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-3 col-form-label">Payment Date</label>
                          <div class="col-sm-9">
                            <input type="date" name="payment_date" id="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" />
                          <?php echo form_error('payment_date'); ?>
                          </div>
                      </div>
                            </div>
                            <div class="col-md-6">
                      <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-3 col-form-label">Payment Type</label>
                          <div class="col-sm-9">
                            <select id="p_type" name="p_type" class="form-control">
                            	<option value="cheque">Cheque</option>
                            	<option value="cash" selected>Cash</option>
                            	<option value="online">Online</option>
                            </select>
                          </div>
                      </div>
                            </div>
                            <div class="col-md-6" id="checknobox" style="display:none;">
                              <div class="form-group row">
                                  <label id="checknobox_label" class="col-sm-3 coidm-label">Cheque No.</label>
                                  <label id="onlinebox_label" class="col-sm-3 col-form-label">Remark</label>
                                  <div class="col-sm-9">
                                    <input type="text" id="checkno" name="checkno" class="form-control" placeholder="Please enter here!" />
                                  </div>
                              </div>
                            </div>
                            </div>
                      
                      <div class="offset-1 mt-3">
                        <input type="submit" class="btn btn-success uppercase" id="assign-create" value="Submit">
                        <button class="btn btn-warning uppercase" id="assign-update" style="display:none;">Update</button>
        
                        <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                        <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                      </div>
                            </div>
                  </form>
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
    $('#bill_no').select2();
    
   // var disabledDates = ["2022-03-28","2022-03-14","2022-03-20"];
    $( function() {
      
      $("#reading_dated").datepicker({ 
        dateFormat: 'dd/mm/yy',
//         beforeShowDay: function(date){
//             var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
//             return [ disabledDates.indexOf(string) == -1 ]
//         }
      });
    });
    
    $('#pending-readingsk').DataTable();
    
    function getBill(){
    	var serviceNo = $('#serviceno').val();
		$.ajax({
            url: `${baseUrl}Meter_ctrl/get_bill_nos/${serviceNo}`,
            method: "GET",
            dataType: "json",
            success(response){
                if(response.status == 200){
                	console.log(response);
                	var x = '<option value="">select billno.</option>';
                    $.each(response.data,function(key,value){
                    	x = x + '<option value="'+ value.bill_id +'">'+ value.bill_no +' ('+value.bill_month+')</option>';
                    });
                    $('#bill_no').html(x);
                } else {
                	$('#bill_no').html('<option value="">select billno.</option>');
                }
            }
        });
    };
    
    
    $(document).on('change','#serviceno',function(){
    	var serviceNo = $(this).val();
    	getBill();
    	$('#bill_date').val('');
        $('#bill_amount').val('');
        $('#due_date').val('');
        $('#payment_amount').val('');	
		$.ajax({
            url: `${baseUrl}Meter_ctrl/getMeters/${serviceNo}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
                if(response.status == 200){
                	console.log(response);
                    $('#costcenter').html('<option value="'+ response.data[0]['costc_id'] +'">'+ response.data[0]['cost_center'] +'</option>');
                    $('#location').html('<option value="'+ response.data[0]['loc_id'] +'">'+ response.data[0]['location_name'] +'</option>');
                    $('#company').html('<option value="'+ response.data[0]['cid'] +'">'+ response.data[0]['company_name'] +'</option>');
                    
//                     $('#bill_no').val(response.payment_detail[0]['bill_no']);
                }
                
                $('#loaderModal').modal('toggle');
            }
        });
    });
    
    
    $(document).on('change','#bill_no',function(){
    	var billno = $(this).val();
    	$.ajax({
            url: `${baseUrl}Meter_ctrl/getbill_detail/${billno}`,
            method: "GET",
            dataType: "json",
            beforeSend: function(){
            	$('#loaderModal').modal({
                  show: true
                });
            },
            success(response){
                if(response.status == 200){
                	console.log(response);
//                     $('#costcenter').html('<option value="'+ response.data[0]['costc_id'] +'">'+ response.data[0]['cost_center'] +'</option>');
//                     $('#location').html('<option value="'+ response.data[0]['loc_id'] +'">'+ response.data[0]['location_name'] +'</option>');
//                     $('#company').html('<option value="'+ response.data[0]['cid'] +'">'+ response.data[0]['company_name'] +'</option>');
                    
//                     $('#bill_no').val(response.payment_detail[0]['bill_no']);
//                     $('#bill_date').val(response.payment_detail[0]['date_of_bill']);
//                     $('#bill_amount').val(response.payment_detail[0]['total_bill']);
//                     $('#due_date').val(response.payment_detail[0]['due_date']);	
					
						var x = (response.data.date_of_bill).split("/");		
					   $('#bill_date').val(x[2]+'-'+x[1]+'-'+x[0]);	
					   $('#bill_amount').val(response.data.payable_amount);
					   var x = (response.data.due_date).split("/");
					   $('#due_date').val(x[2]+'-'+x[1]+'-'+x[0]);	
					   $('#payment_amount').val(response.data.payable_amount);
                }
                $('#loaderModal').modal('toggle');
            }
        });
    });
    
    


    $(document).on('change','#company',function(){
      var company = $(this).val();
      $.ajax({
            url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${company}`,
            method: "GET",
            dataType: "json",
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
            }
        });
    });

    $(document).on('change','#costcenter',function(){
      var costcenter = $(this).val();
      $.ajax({
            url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${costcenter}`,
            method: "GET",
            dataType: "json",
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
            }
        });
      } else {
        $('#serviceno').html('<option value="">Select Service No</option>');
      }
    });
    
    
    $(document).on('change','#p_type',function(){
    	var x = $(this).val();
    	if(x == 'cheque'){
    		$('#checknobox').show();
    		$('#onlinebox_label').hide();
    		$('#checknobox_label').show();
    	} else if(x == 'online'){
    		$('#checknobox_label').hide();
    		$('#onlinebox_label').show();
    		$('#checknobox').show();
    	} 
    	else {
    		$('#checknobox').hide();
        $('#checkno').val('');
    	}
    });
    
    

	
    </script>
