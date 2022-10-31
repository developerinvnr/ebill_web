    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          		<h5 class="text-primary" id="page-heading">Create Meter</h5>
          		<hr/>
          		<form name="f1" method="POST" action="<?php echo base_url();?>master/meter">
          		
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Company<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="mid" name="mid" type="hidden" class="form-control" value="<?php echo set_value('mid'); ?>">
                          <select id="cid" name="cid" class="form-control">
                            <option value="" selected>Select Company</option>
                                <?php foreach($companies as $company){ ?>
                                    <option value="<?php echo $company['cid']; ?>"><?php echo $company['name']; ?></option>
                                <?php } ?>
                            </select>
                            <?php echo form_error('cid'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Cost-center<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="costc_id" name="costc_id" class="form-control">
                             <option value="" selected>Select Cost-Center</option>
                          </select>
                          <?php echo form_error('costc_id'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Location<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="loc_id" name="loc_id" class="form-control">
                            <option value="" selected>Select Location</option>
                        </select>
                        <?php echo form_error('loc_id'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Meter Type<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="mtype" name="mtype" class="form-control">
                            <option value="">Select Meter Type</option>
                            <option value="main-meter" <?php if(set_value('mtype') == 'main-meter'){ echo "selected"; }?>>Main</option>
                            <option value="sub-meter" <?php if(set_value('mtype') == 'sub-meter'){ echo "selected"; }?>>Sub meter</option>
                          </select>
                          <?php echo form_error('mtype'); ?>
                        </div>
                    </div>
                    <div class="form-group row" id="main-meter-block" style="display:<?php 
                            if(set_value('mtype') == ''){ 
                                echo "none"; 
                            } else { 
                                if(set_value('mtype') == 'sub-meter'){
                                    echo "";
                                } else {
                                    echo "none";
                                }
                            }?>"
                    >
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Main Meter</label>
                        <div class="col-sm-8">
                          <select id="main_meter" name="main_meter" class="form-control">
                          	<option value="">Select Main Meter</option>
                          </select>
                          <?php echo form_error('main_meter'); ?>
                        </div>
                    </div>
                    
                    <div id="connection-type-box" style="display:<?php 
                            if(set_value('connection_type') == ''){ 
                                echo "none"; 
                            } else { 
                                if(set_value('connection_type') == 'permanent'){
                                    echo "";
                                } else {
                                    echo "none";
                                }
                            }?>">
                        <div class="form-group row" id="main-meter-block">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Connection Type</label>
                            <div class="col-sm-8">
                              <select id="connection_type" name="connection_type" class="form-control">
                              	<option value="">Select Connection Type</option>
                              	<option value="permanent">Permanent</option>
                              	<option value="temporary">Temporary</option>
                              </select>
                              <?php echo form_error('connection_type'); ?>
                            </div>
                        </div>
                        
                        <div class="form-group row" id="main-meter-block">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">From Date</label>
                            <div class="col-sm-8">
                              <input type="date" name="connection_from_date" id="connection_from_date" class="form-control" />
                              <?php echo form_error('connection_from_date'); ?>
                            </div>
                        </div>
                        
                        <div class="form-group row" id="main-meter-block">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">To Date</label>
                            <div class="col-sm-8">
                              <input type="date" name="connection_to_date" id="connection_to_date" class="form-control" />
                              <?php echo form_error('connection_to_date'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">BP No.<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="bpno" name="bpno" type="text" class="form-control" value="<?php echo set_value('bpno'); ?>">
                          <?php echo form_error('bpno'); ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                      <input type="submit" class="btn btn-success uppercase" id="meter-create" value="Create">
                      <button class="btn btn-warning uppercase" id="meter-update" style="display:none;">Update</button>
    	
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
      			<div class="col-md-12 bg-dark">
      			<div class="row p-2">
      				<div class="col">
      					<font size="5px;">Meter List</font>
      				</div>
      				<div class="col">
            			<select id="com_filter" class="form-control">
            				<option value="">Select Company</option>
            				<?php foreach($companies as $company) { ?>
            					<option value="<?php echo $company['cid']; ?>" <?php if($company['cid'] == $this->uri->segment('2')){ echo 'selected'; }?>>
            						<?php echo substr($company['name'],0,25); ?>
            					</option>
            				<?php } ?>
            			</select>
            		</div>
            		<div class="col">
            			<select id="costc_filter" class="form-control">
            				<option value="">Select Costcenter</option>
            				<?php foreach ($cost_centers as $cost_center){ ?>
            					<option value="<?php echo $cost_center['costc_id']; ?>" <?php if($cost_center['costc_id'] == $this->uri->segment('3')){ echo 'selected'; }?>><?php echo $cost_center['name']; ?></option>
            				<?php } ?>
            			</select>
            		</div>
            		<div class="col">
            			<select id="location_filter" class="form-control">
            				<option value="">Select Location</option>
            				<?php foreach ($locations as $location){ ?>
            					<option value="<?php echo $location['loc_id']; ?>" <?php if($location['loc_id'] == $this->uri->segment('4')){ echo 'selected'; }?>><?php echo $location['name']; ?></option>
            				<?php } ?>
            			</select>
            		</div>
      			</div>
      			</div>
      			
          		<div class="table-responsive">
                    <table class="table table-bordered text-sm" id="meterTable">
                          <thead class="bg-info">
                              <tr>
                                <th class="text-center uppercase">S.No.</th>
                                <th class="text-center uppercase">BP No.</th>
                                <th class="text-center uppercase">Meter Type</th>
                                <th class="text-center uppercase">Company Name</th>
                                <th class="text-center uppercase">Cost-Center</th>
                                <th class="text-center uppercase">Location</th>
                                <th class="text-center uppercase">Created At</th>
                                <th class="text-center uppercase">Created By</th>
                                <th class="text-center uppercase">Action</th>
                              </tr>
                          </thead>
                          <tbody id="meterList"></tbody>
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
       tableFilter();
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
       tableFilter();	
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
       
       tableFilter();	
	});
    
    tableFilter();
    
    function tableFilter(){
    	const company = $('#com_filter').val();
    	const cost_center = $('#costc_filter').val();
    	const location = $('#location_filter').val();
    		
    	$.ajax({
            url: `${baseUrl}Meter_ctrl/metersFilter`,
            method: "POST",
            dataType: "json",
            data : {
            	company : company,
            	costcenter : cost_center,
            	location : location
            },
            beforeSend(){},
            success(response){
            	$("#meterTable").dataTable().fnDestroy();
                if(response.status == 200){
                	var x = '';
                	$.each(response.data,function(key,value){
                		x = x + '<tr>'+
                					'<td>'+ parseInt(key+1) +'</td>'+
                					'<td>'+ value.bpno +'</td>'+
                					'<td>'+ value.mtype +'</td>'+
                					'<td><label title="'+ value.company_name +'">'+ (value.company_name).substr(0,5) +'</label></td>'+
                					'<td>'+ value.costcenter_name +'</td>'+
                					'<td>'+ value.location_name +'</td>'+
                					'<td>'+ value.created_at +'</td>'+
                					'<td>'+ value.fname +' '+ value.lname +'</td>'+
                					'<td style="width:70px;" class="text-center">'+
                                        '<a title="Edit" href="javascript:void(0);" class="meter_edit mr-1" data-id="'+ value.mid +'"><i class="fas fa-edit"></i></a> |'+ 
                                        '<a title="Delete" href="javascript:void(0);" class="meter_delete ml-1" data-id="'+ value.mid +'"><i class="fas fa-trash text-red"></i></a>'+
                                    '</td>'+
                				'</tr>';
                	});
                	$('#meterList').html(x);
                }
                    $('#meterTable').DataTable({
                //         "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": false 
                    });
            }
        });
    }
    
       $(document).on('change','#cid',function(){
        let cid = $(this).val();
        $.ajax({
            url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${cid}`,
            method: "GET",
            dataType: "json",
            beforeSend(){},
            success(response){
                console.log(response);
                if(response.status == 200){
                    var x = '<option value="">Select cost-center</option>';
                    $.each(response.data,function(key,value){
                        x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                    });
                    $('#costc_id').html(x);
                }
            }
        });
      });

	  $(document).on('change','#mtype',function(){
	  	if($(this).val() == 'sub-meter'){
    	  		$('#main-meter-block').show();
    	  		$('#connection-type-box').hide(); 
    	  			
    	  		$.ajax({
                url: `${baseUrl}Meter_ctrl/getMeterByLocationId/${$('#loc_id').val()}`,
                method: "POST",
                dataType: "json",
                beforeSend(){},
                success(response){
                    if(response.status == 200){
                        var x = '<option value="">Select Main Meter</option>';
                        $.each(response.data,function(key,value){
                            x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
                        });
                        $('#main_meter').html(x);
                    }
                }
            });
	  	} 
	  	else if($(this).val() == 'main-meter') {
	  		$('#main-meter-block').hide();
	  		$('#connection-type-box').show();
	  		 
	  		var x = '<option value="">Select Main Meter</option>';
            $('#main_meter').html(x);
	  	}
	  	
	  	else {
	  		$('#main-meter-block').hide();
	  		$('#connection-type-box').hide();
	  	}
	  	
	  	
	  });

      $(document).on('change','#costc_id',function(){
        let costc_id = $(this).val();
        $.ajax({
            url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${costc_id}`,
            method: "GET",
            dataType: "json",
            beforeSend(){},
            success(response){
                if(response.status == 200){
                    var x = '<option value="">Select location</option>';
                    $.each(response.data,function(key,value){
                        x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
                    });
                    $('#loc_id').html(x);
                }
            }
        });
      });

    
      $(document).on('click','.meter_edit',function(){
        var request = $.ajax({
                url: `${baseUrl}Meter_ctrl/getMeterById`,
                method: "POST",
                data: { lid : $(this).data('id') },
                dataType: "json",
                });    
            request.done(function( response ) {
                console.log(response);
                if(response.status == 200){
                	$('#page-heading').html('Update Meter');
                    $('#meter-update').show();
                    $('#cancel-btn').show();
                    $('#meter-create').hide();
                    $('#reset-btn').hide();

                    $('#mid').val(response.data['mid']);
                    $('#cid').val(response.data['cid']);

                    //////////
                    $.ajax({
                        url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${response.data['cid']}`,
                        method: "GET",
                        dataType: "json",
                        async : false,
                        beforeSend(){},
                        success(response1){
                            if(response1.status == 200){
                                var x = '<option value="">Select location</option>';
                                $.each(response1.data,function(key,value){
                                    x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                                });
                                $('#costc_id').html(x);
                            }
                        }
                    });
                    $('#costc_id').val(response.data['costc_id']);
                    /////////////
                    $.ajax({
                        url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${response.data['costc_id']}`,
                        method: "GET",
                        dataType: "json",
                        async: false,
                        beforeSend(){},
                        success(response){
                            if(response.status == 200){
                                var x = '<option value="">Select location</option>';
                                $.each(response.data,function(key,value){
                                    x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
                                });
                                $('#loc_id').html(x);
                            }
                        }
                    });
                    $('#loc_id').val(response.data['loc_id']);
                    //////////////////
                    $('#mtype').val(response.data['mtype']);
                    
                    if(response.data['parent_meter'] != null){
                        $.ajax({
                            url: `${baseUrl}Meter_ctrl/getMeterByLocationId/${response.data['loc_id']}`,
                            method: "GET",
                            dataType: "json",
                            async: false,
                            beforeSend(){},
                            success(response){
                                if(response.status == 200){
                                    var x = '<option value="">Select Main Meter</option>';
                                    $.each(response.data,function(key,value){
                                        x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
                                    });
                                    $('#main_meter').html(x);
                                }
                            }
                        });
                        debugger;
                        $('#main-meter-block').show();
                    }
                    if(response.data['mtype'] == 'main-meter'){
                    	$('#connection-type-box').show();
                    	$('#connection_type').val(response.data['connection_type']);
                    	$('#connection_from_date').val(response.data['connection_from_date']);
                    	$('#connection_to_date').val(response.data['connection_to_date']);
                    }
                    
                    $('#main_meter').val(response.data['parent_meter']);
                    $('#bpno').val(response.data['bpno']);
                }
            });
            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            });
      });

      $('#cancel-btn').on('click',function(){
      		$('#page-heading').html('Create Meter');
            $('#meter-update').hide();
            $('#cancel-btn').hide();
            $('#meter-create').show();
            $('#reset-btn').show();
            $('#connection-type-box').hide();
      });


      $(document).on('click','.meter_delete',function(){
        $.ajax({
            url: `${baseUrl}Meter_ctrl/delete_meter`,
            method: "POST",
            dataType: "json",
            data : {
                mid : $(this).data('id')
            },
            beforeSend(){
                $('#meterList').html('<tr><td colspan="9"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                alert(response.msg);
                reload();
            }
        });
      });


      

      function reload(){
        $.ajax({
            url: `${baseUrl}Meter_ctrl/getMeters`,
            method: "GET",
            dataType: "json",
            beforeSend(){
                $('#meterList').html('<tr><td colspan="9"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
            	$("#meterTable").dataTable().fnDestroy();
                if(response.status == 200){
                    var x = '';
                    $.each(response.data,function(key,value){
                        x = x + '<tr>'+
                                    '<td class="text-center">'+ parseInt(key+1) +'</td>'+
                                    '<td class="text-center">'+ value.bpno +'</td>'+
                                    '<td class="text-center">'+ value.mtype +'</td>'+
                                    '<td class="text-center">'+ value.company_name +'</td>'+
                                    '<td class="text-center">'+ value.cost_center +'</td>'+
                                    '<td class="text-center">'+ value.location_name +'</td>'+
                                    '<td class="text-center">'+ value.created_at +'</td>'+
                                    '<td class="text-center">'+ value.fname +' '+ value.lname +'</td>'+
                                    '<td class="text-center">'+
                                        '<a href="javascript:void(0);" class="meter_edit" data-id="'+ value.mid +'"><i class="la la-pencil"></i></a>'+
                                        '<a href="javascript:void(0);" class="meter_delete" data-id="'+ value.mid +'"><i class="la la-trash"></i></a>'+
                                    '</td>'+
                                '</tr>';

                        $('#meterList').html(x);
                    })
                }
            }
        });
      }

	$('#meterTable').DataTable({
       	"searching": false,
//         "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });

    </script>