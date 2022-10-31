    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          		<h5 class="text-primary" id="page-heading">Create Location</h5>
          		<hr/>
          		<form name="f1" method="POST" action="<?php echo base_url();?>master/location">
          		
          			<div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Company<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="lid" name="lid" type="hidden" class="form-control" value="<?php echo set_value('lid'); ?>">
                          <select id="company" name="company" class="form-control">
                            <option value="" selected>Select Company</option>
                            	<?php foreach($companies as $company){ ?>
                            		<option value="<?php echo $company['cid']; ?>"><?php echo $company['name']; ?></option>
                            	<?php } ?>
                            </select>
                            <?php echo form_error('company'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Cost-Center<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="cost_center" name="cost_center" class="form-control">
                            <option value="" selected>Select Cost-Center</option>
                          </select>
                            <?php echo form_error('cost_center'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Location Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="location" name="location" class="form-control">
                            <option value="" selected>Select Location</option>
                            <?php foreach($locations2 as $location){ ?>
                            	<option value="<?php echo $location['lc_id']; ?>"><?php echo $location['lc_name']; ?></option>
                            <?php }?>
                          </select>
                            <?php echo form_error('location'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row" style="display:none;">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Location Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="lname" name="lname" type="text" class="form-control" value="<?php echo set_value('lname'); ?>">
                          <?php echo form_error('lname'); ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                      <input type="submit" class="btn btn-success uppercase" id="location-create" value="Create">
                      <button class="btn btn-warning uppercase" id="location-update" style="display:none;">Update</button>
    	
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
          		<p class="text-lg text-bold text-info bg-secondary mb-0 text-center" style="height:45px;">Location List
          		<input class="float-right mt-1 mr-1" type="text" id="search" placeholder="search"/>
          		</p>
          		<div class="table-responsive">
                    <table class="table table-bordered text-sm" id="locationListTable">
                              <thead class="bg-info">
                                  <tr>
                                    <th class="text-center align-middle uppercase">S.No.</th>
                                    <th class="text-center align-middle uppercase">Location Name</th>
                                    <th class="text-center align-middle uppercase">Cost-Center / Company Name</th>
                                    <th class="text-center align-middle uppercase">Created At</th>
                                    <th class="text-center align-middle uppercase">Created By</th>
                                    <th class="text-center align-middle uppercase">Action</th>
                                  </tr>
                              </thead>
                              <tbody id="locationList">
                                <?php if(isset($locations)){ $c=1; foreach($locations as $location){ ?>
                                    <tr>
                                        <td class="text-center"><?= $c++; ?></td>
                                        <td class="text-center"><?= $location['name']; ?></td>
                                        <td class="text-center"><?= $location['cost_center']; ?> / <?php echo $location['company_name']; ?></td>
                                        <td class="text-center"><?= $location['created_at']; ?></td>
                                        <td class="text-center"><?= $location['fname'].' '.$location['lname']; ?></td>
                                        <td style="width:70px;" class="text-center">
                                            <a title="Edit" href="javascript:void(0);" class="location_edit mr-1" data-id="<?= $location['loc_id']; ?>"><i class="fas fa-edit"></i></a> | 
                                            <a title="Delete" href="javascript:void(0);" class="location_delete ml-1" data-id="<?= $location['loc_id']; ?>"><i class="fas fa-trash text-red"></i></a>
                                        </td>
                                    </tr>
                                <?php } } else {  echo "<tr><td class='text-center' colspan='6'>Record Not Found.</td></tr>"; } ?>
                              </tbody>
                          </table>
                  </div>
          	</div>
          </div>
          
        </div>
      </div>
      <!-- /.card -->
    </section>
    

    <script>
    const baseUrl = $('#base_url').val();
    
    $(document).on('click','#location-create,#location-update',function(){
   		$('#loaderModal').modal({
   			'show':true
   		});
   });
   
   $(document).on('keyup','#search',function(){
   		var searchText = $(this).val();
   		var request = $.ajax({
                url: `${baseUrl}Location_ctrl/getLocationFilter/${searchText}`,
                method: "POST",
                dataType: "json"
               });    
            
            request.done(function(response){
            	if(response.status == 200){
            		var x = '';
                   	 $.each(response.data,function(key,value){
                            x = x + '<tr>'+
                                        '<td class="text-center">'+ parseInt(key+1) +'</td>'+
                                        '<td class="text-center">'+ value.name +'</td>'+
                                        '<td class="text-center">'+ value.cost_center+ ' / '+ value.company_name +'</td>'+
                                        '<td class="text-center">'+ value.created_at +'</td>'+
                                        '<td class="text-center">'+ value.fname +' '+ value.lname +'</td>'+
                                        '<td class="text-center">'+
                                            '<a href="#" class="location_edit mr-1" data-id="'+ value.loc_id +'">Edit</a>'+
                                            '<a href="#" class="location_delete" data-id="'+ value.loc_id +'">Delete</a>'+
                                        '</td>'+
                                    '</tr>';
                        });
                        console.log(x);
                        $('#locationList').html(x);
            	}
            });
   });
   
    
      $(document).on('click','.location_edit',function(){
      	
      	$('#loaderModal').modal({
   			'show':true,
   			'backdrop' :'static',
   			'keyboard' : false
   		});
   		
        var request = $.ajax({
                url: `${baseUrl}Location_ctrl/getLocationById`,
                method: "POST",
                data: { lid : $(this).data('id') },
                dataType: "json"
                });    
            request.done(function( response ) {
            	$('#loaderModal').modal('toggle');
                console.log(response);
                if(response.status == 200){
                	$('#page-heading').html('Update Location');
                    $('#location-update').show();
                    $('#cancel-btn').show();
                    $('#location-create').hide();
                    $('#reset-btn').hide();
                    
                    $.ajax({
                        url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${response.data['cid']}`,
                        method: "GET",
                        dataType: "json",
                        async: false,
                        beforeSend(){},
                        success(response){
                        	console.log(response);
                            if(response.status == 200){
                                var x = '<option value="">Select Cost-Center</option>';
                                $.each(response.data,function(key,value){
                                	x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                                });
                                $('#cost_center').html(x);
                            }
                        }
                    });
					
					$('#location').val(response.data['lc_id']);
					$('#company').val(response.data['cid'])
                    $('#lid').val(response.data['loc_id']);
                    $('#cost_center').val(response.data['cost_center_id']);
                    $('#lname').val(response.data['name']);
                }
            });
            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            });
      });

      $('#cancel-btn').on('click',function(){
      		$('#page-heading').html('Create Location');
            $('#location-update').hide();
            $('#cancel-btn').hide();
            $('#location-create').show();
            $('#reset-btn').show();
      });


      $(document).on('click','.location_delete',function(){
      	if(confirm('Are you sure?')){
            $.ajax({
                url: `${baseUrl}Location_ctrl/delete_location`,
                method: "POST",
                dataType: "json",
                data : {
                    lid : $(this).data('id')
                },
                beforeSend(){
                    $('#locationList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
                },
                success(response){
                    alert(response.msg);
                    if(response.status == 200){
                        reload();
                    } else {
                    	reload();
                    }
                }
            });
        }
      });


	 $(document).on('change','#company',function(){
	 	let cid = $(this).val();
	 	if(cid){
    	 	$.ajax({
                url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${cid}`,
                method: "GET",
                dataType: "json",
                beforeSend(){
                    $('#loaderModal').modal({
               			'show':true,
               			'backdrop' :'static',
               			'keyboard' : false
               		});
                },
                success(response){
                	console.log(response);
                    if(response.status == 200){
                        var x = '<option value="">Select Cost-Center</option>';
                        $.each(response.data,function(key,value){
                        	x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>'; 
                            $('#cost_center').html(x);
                        })
                    }
                    
                    $('#loaderModal').modal('toggle');
                }
            });
        } else {
        	$('#cost_center').html('<option value="">Select Cost-Center</option>');
        }
	 });

      

      function reload(){
        $.ajax({
            url: `${baseUrl}Location_ctrl/getLocations`,
            method: "GET",
            dataType: "json",
            beforeSend(){
                $('#locationList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                if(response.status == 200){
                    var x = '';
                    $.each(response.data,function(key,value){
                        x = x + '<tr>'+
                                    '<td class="text-center">'+ parseInt(key+1) +'</td>'+
                                    '<td class="text-center">'+ value.name +'</td>'+
                                    '<td class="text-center">'+ value.cost_center+ ' / '+ value.company_name +'</td>'+
                                    '<td class="text-center">'+ value.created_at +'</td>'+
                                    '<td class="text-center">'+ value.fname +' '+ value.lname +'</td>'+
                                    '<td class="text-center">'+
                                        '<a href="javascript:void(0);" class="location_edit" data-id="'+ value.loc_id +'"><i class="la la-pencil"></i></a>'+
                                        '<a href="javascript:void(0);" class="location_delete" data-id="'+ value.loc_id +'"><i class="la la-trash"></i></a>'+
                                    '</td>'+
                                '</tr>';

                        $('#locationList').html(x);
                    })
                }
            }
        });
      }
      
      
      $(document).on('change','#location',function(){
      	$('#lname').val($('#location option:selected').text());
      });


	$('#locationListTable').DataTable({
   		"searching": false,
//         "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false  
    });
    </script>