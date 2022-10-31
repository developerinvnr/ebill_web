    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          		<h5 class="text-primary" id="page-heading">Create Cost-Center</h5>
          		<hr/>
          		<form name="f1" method="POST" action="<?php echo base_url();?>master/cost-center">
          		
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Company<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="cid" name="cid" type="hidden" class="form-control" value="<?php echo set_value('cid'); ?>">
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
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Cost Center<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="cost_center" name="cost_center" class="form-control">
                            <option value="" selected>Select Cost Center</option>
                                <?php foreach($ccids as $ccid){ ?>
                                    <option value="<?php echo $ccid['cc_id']; ?>"><?php echo $ccid['cc_name']; ?></option>
                                <?php } ?>
                          </select>
                          <?php echo form_error('cost_center'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row" style="display:none;">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Cost Center Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="cname" name="cname" type="text" class="form-control" value="<?php echo set_value('cname'); ?>">
                          <?php echo form_error('cname'); ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                      <input type="submit" class="btn btn-success uppercase" id="costcenter-create" value="Create">
                      <button class="btn btn-warning uppercase" id="costcenter-update" style="display:none;">Update</button>
    
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
          		<p class="text-lg text-bold text-info bg-secondary p-2 mb-0 text-left">
          			Cost-Center List
          			<select class="float-right" id="company_filter">
          				<option value="">Select Company</option>
          				<?php foreach($companies as $company){ ?>
          					<option value="<?php echo $company['cid']; ?>">
          						<?php echo substr($company['name'],0,15); ?>
          					</option>
          				<?php } ?>
          			</select>
          		</p>
          		<div class="table-responsive">
                    <table class="table table-bordered text-sm" id="cost-centerTable">
                          <thead class="bg-info">
                              <tr>
                                <th class="text-center align-middle uppercase">S.No.</th>
                                <th class="text-center align-middle uppercase">Cost Center Name</th>
                                <th class="text-center align-middle uppercase">Company Name</th>
                                <th class="text-center align-middle uppercase">Created At</th>
                                <th class="text-center align-middle uppercase">Created By</th>
                                <th class="text-center align-middle uppercase">Action</th>
                              </tr>
                          </thead>
                          <tbody id="costcenterList">
                            <?php if(isset($costceners)){ $c=1; foreach($costceners as $costcener){ ?>
                                <tr>
                                    <td class="text-center"><?= $c++; ?></td>
                                    <td class="text-center"><?= $costcener['name']; ?></td>
                                    <td class="text-left"><?= $costcener['company_name']; ?></td>
                                    <td class="text-center"><?= $costcener['created_at']; ?></td>
                                    <td class="text-center"><?= $costcener['fname'].' '.$costcener['lname']; ?></td>
                                    <td style="width:70px;" class="text-center">
                                        <a title="Edit" href="javascript:void(0);" class="costcenter_edit mr-1" data-id="<?= $costcener['costc_id']; ?>"><i class="fas fa-edit"></i></a> | 
                                        <a title="Delete" href="javascript:void(0);" class="costcenter_delete ml-1" data-id="<?= $costcener['costc_id']; ?>"><i class="fas fa-trash text-red"></i></a>
                                    </td>
                                </tr>
                            <?php } } else {  echo "<tr><td class='text-center' colspan='6'> Record Not Found.</td></tr>"; } ?>
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
    var costCenterList = [];
        	
	<?php foreach($costceners as $oi){ ?>
		costCenterList.push({
		'costc_id': '<?php echo $oi['costc_id']; ?>',
		'company_id': '<?php echo $oi['company_id']; ?>',
		'name' : '<?php echo $oi['name']; ?>',
		'cc_id' : '<?php echo $oi['cc_id']; ?>',
		'created_by' : '<?php echo $oi['created_by']; ?>',
		'created_at' : '<?php echo $oi['created_at']; ?>',
		'status' : '<?php echo $oi['status']; ?>',
		'fname' : '<?php echo $oi['fname']; ?>',
		'lname' : '<?php echo $oi['lname']; ?>',
		'type_name' : '<?php echo $oi['type_name']; ?>',
		'company_name' : '<?php echo $oi['company_name']; ?>',
		});
	<?php } ?>
    
    console.log(costCenterList);
    
    $(document).on('click','#costcenter-create,#costcenter-update',function(){
    	$('#loaderModal').modal({
   			'show':true
   		});
   });
    
      $(document).on('click','.costcenter_edit',function(){
      	$('#loaderModal').modal({
   			'show':true,
   			'backdrop' :'static',
   			'keyboard' : false
   		});
   		
        var request = $.ajax({
                url: `${baseUrl}Costcenter_ctrl/getCostCenterById`,
                method: "POST",
                data: { cid : $(this).data('id') },
                dataType: "json"
                });    
            request.done(function( response ) {
            	$('#loaderModal').modal('toggle');
                console.log(response);
                if(response.status == 200){
                	$('#page-heading').html('Update Cost-Center');
                    $('#costcenter-update').show();
                    $('#cancel-btn').show();
                    $('#costcenter-create').hide();
                    $('#reset-btn').hide();
                    
                    $('#cost_center').val(response.data['cc_id']);
					$('#cid').val(response.data['costc_id']);
                    $('#company').val(response.data['company_id']);
                    $('#cname').val(response.data['name']);
                }
            });
            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            });
      });

      $('#cancel-btn').on('click',function(){
      		$('#page-heading').html('Create Cost-Center');
            $('#costcenter-update').hide();
            $('#cancel-btn').hide();
            $('#costcenter-create').show();
            $('#reset-btn').show();
      });


      $(document).on('click','.costcenter_delete',function(){
      	var cc_id = $(this).data('id');
      	
      	if(confirm('Are you sure?')){
            $.ajax({
                url: `${baseUrl}Costcenter_ctrl/delete_costcenter`,
                method: "POST",
                dataType: "json",
                data : {
                    cid : cc_id
                },
                beforeSend(){
                    $('#costcenterList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
                },
                success(response){
                    alert(response.msg);
                    if(response.status == 200){
                        costCenterList = costCenterList.filter(function(item){
                                  			return item['costc_id'] !== String(cc_id)
                                  	});
                        $('#company_filter').trigger('change');
                    }
                }
            });
        }
      });
      		
	$(document).on('change','#cost_center',function(){
		let costcenter = $('#cost_center option:selected').text();
		$('#cname').val(costcenter);
	});	

	
	$(document).on('change','#company_filter',function(){
		console.log('costCenterList', costCenterList);
		
		$('#cancel-btn').trigger('click');
		
		function checkAdult(data) {
          if(data['company_id'] == $('#company_filter').val()){
          	return true;
          }
          else{
          	return false;
          }
        }
        
        if($('#company_filter').val() == ''){
        	var result = costCenterList;
        } else {
    		var result = costCenterList.filter(checkAdult);
    	}
		
		var tableData = '';
		$.each(result,function(key,value){
    		tableData = tableData + '<tr>'+
    						'<td>'+ parseInt(key+1) +'</td>'+
    						'<td>'+ value.name +'</td>'+
    						'<td>'+ value.company_name +'</td>'+
    						'<td>'+ value.created_at +'</td>'+
    						'<td>'+ value.fname +'</td>'+
    						'<td style="width:70px;" class="text-center">'+
                                '<a title="Edit" href="javascript:void(0);" class="costcenter_edit mr-1" data-id="'+ value.costc_id +'"><i class="fas fa-edit"></i></a> |'+ 
                                '<a title="Delete" href="javascript:void(0);" class="costcenter_delete ml-1" data-id="'+ value.costc_id +'"><i class="fas fa-trash text-red"></i></a>'+
                    		'</td>'+
    					'</tr>';
                    	});
        $('#cost-centerTable').html(tableData);
                    	
    	$('#cost-centerTable').dataTable().fnClearTable();
		$('#cost-centerTable').dataTable().fnDestroy();
        $('#cost-centerTable').DataTable({
               	"searching": false,
               	"destroy" : true,
        //         "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": false 
            });
    });
    
    
    $(document).on('keyup','#search',function(){
		filter();
    });
    

	$('#cost-centerTable').DataTable({
       	"searching": false,
       	"destroy" : true,
//         "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });


    </script>