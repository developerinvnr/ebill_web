    <style>
.table-fd{

}
.table-fd tr td{border-bottom:1px solid #ddd;border-top:0px solid #ddd;border-left:0px solid #ddd;border-right:0px solid #ddd;}
.table-fd tr:last-child td{border-bottom:0px solid #ddd;}
.tbl-task tr td{border-bottom:1px solid #ddd !important;}
.tbl-task tr:last-child td{border-bottom:1px solid #ddd !important;}
.tbl-task tr:nth-child(2) td{border-bottom:1px solid #ddd !important;}
    </style>
    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
        <div class="table-responsive mt-3">
          		<span class="text-primary" id="page-heading">Assigned Users</span>
          		<hr>
        	<div class="row mb-4">
            
        		<div class="col">
            <label>Company</label>
        			<select id="com_filter" class="form-control">
        				<option value="">Select Company</option>
        				<?php foreach($companies as $company) { ?>
        					<option value="<?php echo $company['cid']; ?>" <?php if($company['cid'] == $this->uri->segment('2')){ echo 'selected'; }?>><?php echo $company['name']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col">
            <label>Select Costcenter</label>
        			<select id="costc_filter" class="form-control">
        				<option value="">Select Costcenter</option>
        				<?php foreach ($cost_centers as $cost_center){ ?>
        					<option value="<?php echo $cost_center['costc_id']; ?>" <?php if($cost_center['costc_id'] == $this->uri->segment('3')){ echo 'selected'; }?>><?php echo $cost_center['name']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col">
            <label>Select Location</label>
        			<select id="location_filter" class="form-control">
        				<option value="">Select Location</option>
        				<?php foreach ($locations as $location){ ?>
        					<option value="<?php echo $location['loc_id']; ?>" <?php if($location['loc_id'] == $this->uri->segment('4')){ echo 'selected'; }?>><?php echo $location['name']; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col mt-4">
        			<input class="btn btn-success mt-2" type="button" id="filter" value="Search" />
        		</div>
        	</div>
			
			
			
			
			
			
				
          	
              <table class="table table-bordered text-sm">
                <thead class="bg-light">
                  <tr>
                    <th>Id</th>
                    <th>Main Meter</th>
                    <th>Sub Meter</th>
                    <th>Company</th>
                    <th>CostCenter</th>
                    <th>Location</th>
                    <th>Task</th>
                    <th>Assign User</th>
                    <th>Frequency of Upload</th>
                    <th>Operations</th>
                  </tr>
                </thead>
                <tbody>	
                  <?php $c=1; foreach($records as $record){
                      if($record['mtype'] != 'main-meter'){
                          continue;
                      }
                  ?>
                      <tr>
                        <!-- S.No. -->
                        <td style="vertical-align: middle;"><?php echo $c++; ?></td>
                        <?php $ic=1; foreach($records as $r) { 
                          if($r['parent_meter'] == $record['mid']){
                            $ic++;
                          }
                        } 
                        ?> 
                        <!-- Service No. -->
                        <td class="text-center m-0 p-0" style="vertical-align: middle;height:<?php echo ($ic * 2) * 30; ?>px;"><?php echo $record['bpno']; ?><br/><?php echo $record['fname']; ?></td>
                        
                        <!-- Sub Meter -->
                        <td class="text-center m-0 p-0">
                          <table class="table-fd" width="100%">
                          		<tr><td class='m-0 p-0' style='height:<?php echo ((($ic * 2) * 30)/$ic) ?>px;vertical-align: middle;'><?php echo $record['bpno']; ?></td></tr>
                            <?php foreach($records as $r) {
                              if($r['parent_meter'] == $record['mid']){
                                echo "<tr><td class='m-0 p-0' style='height:".((($ic * 2) * 30)/$ic)."px;vertical-align: middle;'>".$r['bpno']."</td></tr>";
                              }
                            } ?> 
                          </table>
                        </td>
                        <!-- company -->
                        <td class="text-center m-0 p-0">
                          <table class="table-fd" width="100%">
                          		<tr><td class='m-0 p-0' style='height:<?php echo ((($ic * 2) * 30)/$ic) ?>px;vertical-align: middle;'><span title="<?php echo $record['company'];?>"><?php echo substr($record['company'], 0,5); ?></span></td></tr>
                            <?php foreach($records as $r) {
                              if($r['parent_meter'] == $record['mid']){
                                  echo "<tr><td class='m-0 p-0' style='height:".((($ic * 2) * 30)/$ic)."px;vertical-align: middle;'><span title='".$r['company']."'>".substr($r['company'], 0,5)."</span></td></tr>";
                              }
                            } ?> 
                          </table>
                        </td>
                        
                        <!-- costcenter -->
                        <td class="text-center m-0 p-0">
                          <table class="table-fd" width="100%">
                          		<tr><td class='m-0 p-0' style='height:<?php echo ((($ic * 2) * 30)/$ic) ?>px;vertical-align: middle;'><span title="<?php echo $record['cost_center_name']; ?>"><?php echo substr($record['cost_center_name'], 0,5); ?></span></td></tr>
                            <?php foreach($records as $r) {
                              if($r['parent_meter'] == $record['mid']){
                                  echo "<tr><td class='m-0 p-0' style='height:".((($ic * 2) * 30)/$ic)."px;vertical-align: middle;'><span title='".$r['cost_center_name']."'>".substr($r['cost_center_name'], 0,5)."</span></td></tr>";
                              }
                            } ?> 
                          </table>
                        </td>
                        

                        <!-- Location -->
                        <td class="text-center m-0 p-0">
                          <table class="table-fd" width="100%">
                          	<tr><td class='m-0 p-0' style='height:<?php echo ((($ic * 2) * 30)/$ic); ?>px;vertical-align: middle;'><span title="<?php echo $record['location']; ?>"><?php echo $record['location']; ?></span></td></tr>
                            <?php foreach($records as $r) { 
                              if($r['parent_meter'] == $record['mid']){
                                  echo "<tr><td class='m-0 p-0' style='height:".((($ic * 2) * 30)/$ic)."px;vertical-align: middle;'><span title='".$r['location']."'>".$r['location']."</span></td></tr>";
                              }
                            } ?> 
                          </table>
                        </td>
                        <!-- Task -->
                        <td class="text-center m-0 p-0">
                        	<table class="table-fd tbl-task" width="100%" style='border:0px;'>
                              <tr>
                                <td class='m-0 p-0' style='height:30px;'>Meter Reading</td>
                              </tr>
                              <tr>
                                <td class='m-0 p-0' style='height:30px;'>Bill Upload</td>
                              </tr>
                            </table>
                            <?php foreach($records as $r) { 
                              if($r['parent_meter'] == $record['mid']){ ?>
                                <table class="table-fd tbl-task" width='100%' style='border:0px;'>
                                  <tr>
                                    <td class='m-0 p-0' style='height:30px;'>Meter Reading</td>
                                  </tr>
                                  <tr>	
                                    <td class='m-0 p-0' style='height:30px;'>Bill Upload</td>
                                  </tr>
                                </table>
                              <?php }
                            } ?> 
                        </td>
                        <!-- Assign user -->
                        <td class="text-center m-0 p-0">
                        	<table width="100%">
                        		<tr>
                        			<td style='' class='m-0 p-0'>
                        			
                                      <select style='width:100%;height:30px;' id="assign_user_meter_reading_<?php echo $record['mid'].'_0'; ?>">
                                        <option value=''>Select User</option>
                                        <?php foreach($users as $user){
                                          if($user['uid'] == $record['user_id']){
                                            echo "<option value='".$user['uid']."' selected>".$user['fname']."</option>";
                                          } else {
                                            echo "<option value='".$user['uid']."'>".$user['fname']."</option>";
                                          }
                                        } ?>
                                      </select>
                                    </td>
                        		</tr>
                        		<tr>
                        			<td style='' class='m-0 p-0'>
                                      <select style='width:100%;height:30px;' id="assign_user_bill_uploading_<?php echo $record['mid'].'_0'; ?>">
                                        <option value=''>Select User</option>
                                        <?php foreach($users as $user){
                                          if($user['uid'] == $record['user_id']){
                                            echo "<option value='".$user['uid']."' selected>".$user['fname']."</option>";
                                          } else {
                                            echo "<option value='".$user['uid']."'>".$user['fname']."</option>";
                                          }
                                        } ?>
                                      </select>
                                    </td>
                        		</tr>
                        	</table>
                            <?php foreach($records as $key =>$r) { 
                              if($r['parent_meter'] == $record['mid']){
                                echo "<table width='100%' style='height:100%'>
                                  <tr>
                                    <td style='' class='m-0 p-0'>
                                      <select style='width:100%;height:28px;' id='assign_user_meter_reading_".$r['mid']."_".$record['mid']."'>
                                        <option value=''>Select User</option>";
                                        foreach($users as $user){
                                          if($user['uid'] == $r['user_id']){
                                            echo "<option value='".$user['uid']."' selected>".$user['fname']."</option>";
                                          } else {
                                            echo "<option value='".$user['uid']."'>".$user['fname']."</option>";
                                          }
                                        }
                                      echo "</select>
                                    </td>
                                  </tr>
                                  <tr>
                                  <td style='' class='m-0 p-0'>
                                    <select style='width:100%;height:28px;' id='assign_user_bill_uploading_".$r['mid']."_".$record['mid']."'>
                                      <option value=''>Select User</option>";
                                      foreach($users as $user){
                                        if($user['uid'] == $r['user_id']){
                                          echo "<option value='".$user['uid']."' selected>".$user['fname']."</option>";
                                        } else {
                                          echo "<option value='".$user['uid']."'>".$user['fname']."</option>";
                                        }
                                      }
                                    echo "</select>
                                  </td>
                                  </tr>
                                </table>";
                              }
                            } ?> 
                        </td>
                        
                        <!-- Frequency -->
                        <td class="text-center m-0 p-0">
                        	<table style='height:100%'>
                              <tr>
                                <td class='m-0 p-0'>
                                  <input style='height:28px;' type='number' id='reading_freq_<?php echo $record['mid'].'_0'; ?>' value="<?php if($record['meter_reading'] == 1){
                                      echo $record['reading_frq'];
                                  }?>"/>
                                </td>
                              </tr>
                              <tr>
                                <td class='m-0 p-0'>
                                  <input style='height:28px;' type='number' id='bill_upload_freq_<?php echo $record['mid'].'_0'; ?>' value="<?php if($record['bill_upload'] == 1){
                                      echo $record['upload_frq'];
                                  }?>"/>
                                </td>
                              </tr>
                            </table>
                            <?php foreach($records as $key => $r) { 
                              if($r['parent_meter'] == $record['mid']){ ?>
                                <table style='height:100%'>
                                  <tr>
                                    <td class='m-0 p-0'>
                                      <input style='height:28px;' type='number' id='reading_freq_<?php echo $r['mid']; ?>_<?php echo $record['mid']; ?>' value="<?php
                                      if($r['meter_reading'] == 1){
                                          echo $r['reading_frq'];
                                      }
                                      ?>"/>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class='m-0 p-0'>
                                      <input style='height:28px;' id='bill_upload_freq_<?php echo $r['mid']; ?>_<?php echo $record['mid']; ?>' type='number' value="<?php
                                      if($r['bill_upload'] == 1){
                                          echo $r['upload_frq'];
                                      }
                                      ?>"/>
                                    </td>
                                  </tr>
                                </table>
                             <?php  }
                            } ?> 
                        </td>
                        <td class="m-0 p-0 text-center">
                        	<table width="100%" class="m-0 p-0">
                        		<tr>
                        			<td>
                        				<input class="btn btn-xs btn-info assign_btn" data-id="assign_<?php echo $r['cid'].'_'.$r['costc_id'].'_'.$r['loc_id'].'_'. $record['mid'].'_0'; ?>" type="button" value="Assign" />
                        				<input class="btn btn-xs btn-default assign_btn_reset" data-id="assign_<?php echo $r['cid'].'_'.$r['costc_id'].'_'.$r['loc_id'].'_'. $record['mid'].'_0'; ?>" type="button" value="Reset" />
                        			</td>
                        		</tr>
                        	</table>
                        	<?php foreach($records as $key => $r) { 
                        	    if($r['parent_meter'] == $record['mid']){ ?>
                        	    <table width="100%" class="m-0 p-0">
                            		<tr>
                            			<td>
                            				<input class="btn btn-xs btn-info assign_btn" data-id="assign_<?php echo $r['cid'].'_'.$r['costc_id'].'_'.$r['loc_id'].'_'. $r['mid'].'_'.$record['mid']; ?>" type="button" value="Assign" />
                            				<input class="btn btn-xs btn-default assign_btn_reset" data-id="assign_<?php echo $r['cid'].'_'.$r['costc_id'].'_'.$r['loc_id'].'_'. $r['mid'].'_'.$record['mid']; ?>" type="button" value="Reset" />
                            			</td>
                            		</tr>
                            	</table>
                        	<?php } } ?>
                        </td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>

          	</div>

          
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    

    <script>
    const baseUrl = $('#base_url').val();
    
    $(document).on('change','#serviceno',function(){
    	var serviceNo = $(this).val();
		$.ajax({
            url: `${baseUrl}Meter_ctrl/getMeters/${serviceNo}`,
            method: "GET",
            dataType: "json",
            success(response){
                if(response.status == 200){
                    $('#costcenter').html('<option value="'+ response.data[0]['costc_id'] +'">'+ response.data[0]['cost_center'] +'</option>');
                    $('#location').html('<option value="'+ response.data[0]['loc_id'] +'">'+ response.data[0]['location_name'] +'</option>');
                    $('#company').html('<option value="'+ response.data[0]['cid'] +'">'+ response.data[0]['company_name'] +'</option>');
                }
            }
        });
    });
    
    
    $(document).on('click','.assign_btn_reset',function(){
    	let str = $(this).data('id');
    	const x = str.split('_');
    	
    	const company = x[1];
    	const costcenter = x[2];
    	const location = x[3];
    	const subMeter = x[4];
    	const mainMeter = x[5];
    	
    	$.ajax({
                url: `${baseUrl}Assigntask_ctrl/assign_user_remove`,
                method: "POST",
                dataType: "json",
                data : {
                	'company': company, 
                	'costcenter' : costcenter,
                	'location' : location,
                	'meter' : mainMeter,
                	'sub-meter' : subMeter
                },
                beforeSend(){},
                success(response){
                	alert(response.msg);
                	location.reload();
                }
          });
    });
    
    $(document).on('click','.assign_btn',function(){
    	let str = $(this).data('id');
    	const x = str.split('_');
    	
    	const company = x[1];
    	const costcenter = x[2];
    	const location = x[3];
    	const subMeter = x[4];
    	const mainMeter = x[5];
    	
    	console.log($('#assign_user_meter_reading_'+ subMeter +'_'+ mainMeter).val());
    	console.log($('#reading_freq_'+ subMeter +'_'+ mainMeter).val());
    	console.log($('#assign_user_bill_uploading_'+ subMeter +'_'+ mainMeter).val());
    	console.log($('#bill_upload_freq_'+ subMeter +'_'+ mainMeter).val());
    	
    	formvalid = false;
    	
    	if($('#assign_user_meter_reading_'+ subMeter +'_'+ mainMeter).val() > 0){
    		if($('#reading_freq_'+ subMeter +'_'+ mainMeter).val() > 0){
    			if($('#assign_user_bill_uploading_'+ subMeter +'_'+ mainMeter).val() > 0){
    				if($('#bill_upload_freq_'+ subMeter +'_'+ mainMeter).val() >0){
    					formvalid = true;
    				}
    			}
    		}
    	}
    	
    	if(formvalid){ 
        	$.ajax({
                url: `${baseUrl}Assigntask_ctrl/assign_user_list_submit`,
                method: "POST",
                dataType: "json",
                data : {
                	'company': company, 
                	'costcenter' : costcenter,
                	'location' : location,
                	'meter' : mainMeter,
                	'sub-meter' : subMeter,
                	'reading-user' : $('#assign_user_meter_reading_'+ subMeter +'_'+ mainMeter).val(),
                	'billupload-user' : $('#assign_user_bill_uploading_'+ subMeter +'_'+ mainMeter).val(),
                	'meter_reading' : 1,
                	'reading_frq' : $('#reading_freq_'+ subMeter +'_'+ mainMeter).val(),
                	'bill_upload' : 1,
                	'upload_frq' :$('#bill_upload_freq_'+ subMeter +'_'+ mainMeter).val()
                },
                beforeSend(){},
                success(response){
                    if(response.status == 200){
                        alert(response.msg);
                        location.reload();
                    }
                }
            });
        } else {
        	alert('Please fill all required fields.');
        }
    });
    
    
    $(document).on('change','#com_filter',function(){
    	const comId = $(this).val();
    	if(comId){
        	$.ajax({
                url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${comId}`,
                method: "GET",
                dataType: "json",
                beforeSend(){},
                success(response){
                	var x = '<option value="">Select Cost-center</option>';
                    if(response.status == 200){
                    	$.each(response.data,function(key,value){
                    		x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                    	});
                    	$('#costc_filter').html(x);
                    } else {
                    	$('#costc_filter').html('<option value="">Select Cost-center</option>');
                    }
                    $('#location_filter').html('<option value="">Select Location</option>');
                }
            });
        } else {
        	$('#costc_filter').html('<option value="">Select Cost-center</option>');
        	$('#location_filter').html('<option value="">Select Location</option>');
        }
    });
    
    $(document).on('change','#costc_filter',function(){
    	const costId = $(this).val();
    	$.ajax({
            url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${costId}`,
            method: "GET",
            dataType: "json",
            beforeSend(){},
            success(response){
            	var x = '<option value="">Select Location</option>';
                if(response.status == 200){
                	$.each(response.data,function(key,value){
                		x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
                	});
                	$('#location_filter').html(x);
                } else {
                	$('#location_filter').html('<option value="">Select Location</option>');
                }
            }
        });
    });
    
    
    $(document).on('click','#filter',function(){
    	const company = $('#com_filter').val();
    	const costcenter = $('#costc_filter').val();
    	const location = $('#location_filter').val();
    	if(company){
    		if(costcenter){
    			if(location){
    				window.location.href = `${baseUrl}assign-meter-show/${company}/${costcenter}/${location}`;
    			}else{
    				window.location.href = `${baseUrl}assign-meter-show/${company}/${costcenter}`;
    			}
    		} else {
    			window.location.href = `${baseUrl}assign-meter-show/${company}`;
    		}
    		
    	} else {
    		window.location.href = `${baseUrl}assign-meter-show`;
    	}
    });
	
    </script>
