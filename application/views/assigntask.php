    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12">

          		<h5 class="text-primary" id="page-heading">Assign User</h5>
          		<hr/>
          		<?php echo $this->session->flashdata('msg'); ?>
          		<form name="f1" method="POST" action="<?php echo base_url();?>assign-meter">
          		
                    <div class="form-group row">
                    	<div class="col row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Company<label class="text-danger">*</label></label>
                            <div class="col-sm-8">
                              <input id="uid" name="uid" type="hidden" class="form-control" value="<?php echo set_value('uid'); ?>">
                              <select id="company" name="company" class="form-control">
                                <option value="" selected>Select Company</option>
                                    <?php foreach($companies as $company){ ?>
                                        <option value="<?php echo $company['cid']; ?>"><?php echo $company['name']; ?></option>
                                    <?php } ?>
                                </select>
                              <?php echo form_error('company'); ?>
                            </div>
                        </div>
                    	<div class="col row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Cost-Center<label class="text-danger">*</label></label>
                            <div class="col-sm-8">
                              <select id="costc_id" name="costc_id" class="form-control">
                                <option value="">Select Cost-Center</option>
                            </select>
                            <?php echo form_error('costc_id'); ?>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="form-group row">
                    <div class="col row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Location<label class="text-danger">*</label></label>
                            <div class="col-sm-8">
                              <select id="loc_id" name="loc_id" class="form-control">
                                <option value="">Select Location</option>
                              </select>
                              <?php echo form_error('loc_id'); ?>
                            </div>
                        </div>
                    <div class="col row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Service No.<label class="text-danger">*</label></label>
                            <div class="col-sm-8">
                              <select id="meter" name="meter" class="form-control">
                                <option value="" selected>Select Meter</option>
                              </select>
                              <?php echo form_error('meter'); ?>
                            </div>
                        </div>
                       
                        
                    </div>
                    
                    <div class="form-group row">
                    <div class="col row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Submeter No.</label>
                            <div class="col-sm-8">
                              <select id="sub-meter" name="sub-meter" class="form-control">
                                <option value="" selected>Select Sub-Meter</option>
                              </select>
                              <?php echo form_error('sub-meter'); ?>
                            </div>
                        </div>    
                        <div class="col row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Employee<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select id="user" name="user" class="form-control">
                            <option value="" selected>Select Employee</option>
                                <?php foreach($users as $user){ ?>
                                    <option value="<?php echo $user['uid']; ?>"><?php echo $user['fname'].' '.$user['lname'].' {['. $user["role"] .']}'; ?></option>
                                <?php } ?>
                          </select>
                        <?php echo form_error('user'); ?>
                        </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Task<label class="text-danger">*</label></label>
                        <div class="col-sm-10">
                          <div class="row">
                          <div class="col-sm-2">
                          <input class="" type="checkbox" name="meter_reading" style="margin-top:7px;"> Meter Reading
                                </div>
                                <div class="col-sm-8">
                          	<input name="reading_frq" width="20" type="number" value="1" class=" mr-1">Days
                                </div>
                          </div>
                          
                          <div class="row mt-2">
                          
                          <div class="col-sm-2">
                          <input class="mr-2" type="checkbox" name="bill_upload" style="margin-top:6px;">Bill Upload
                                </div>
                                <div class="col-sm-8">
                          	<input name="upload_frq" width="20" type="number" value="1" class=" mr-1"> Months
                                </div>
                                
                          </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                      <input type="submit" class="btn btn-success uppercase" id="assign-create" value="Assign">
                      <button class="btn btn-warning uppercase" id="assign-update" style="display:none;">Update</button>
    	
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8" style="display:none;">
          		<div class="table-responsive">
                    <table class="table table-bordered">
                          <thead class="bg-light">
                                  <tr>
                                    <th class="text-center uppercase">S.No.</th>
                                    <th class="text-center uppercase">Name</th>
                                    <th class="text-center uppercase">Email</th>
                                    <th class="text-center uppercase">Contact No.</th>
                                    <th class="text-center uppercase">Sex</th>
                                    <th class="text-center uppercase">Role</th>
                                    <th class="text-center uppercase">Action</th>
                                  </tr>
                              </thead>
                              <tbody id="userList">
                                <?php if(isset($users)){ $c=1; foreach($users as $user){ ?>
                                    <tr>
                                        <td class="text-center"><?= $c++; ?></td>
                                        <td class="text-center"><?= $user['fname'] .' '.$user['lname'] ?></td>
                                        <td class="text-center"><?= $user['email'] ?></td>
                                        <td class="text-center"><?= $user['contact_no']; ?></td>
                                        <td class="text-center"><?= $user['sex']; ?></td>
                                        <td class="text-center"><?= $user['role']; ?></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" class="user_edit" data-id="<?= $user['uid']; ?>"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0);" class="user_delete" data-id="<?= $user['uid']; ?>"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php } } else {  echo "<tr><td class='text-center' colspan='6'>No record found.</td></tr>"; } ?>
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
    
       function getCostCenter(cid){
    $.ajax({
        url: `${baseUrl}Costcenter_ctrl/getCostcenterByCompnayId/${cid}`,
        method: "POST",
        dataType: "json",
        data : {
            cid : $(this).data('id')
        },
        success(response){
            var x = '<option value="">Select Cost-center</option>';
            if(response.status == 200){
                $.each(response.data,function(key,value){
                    x = x + '<option value="'+ value.costc_id +'">'+ value.name +'</option>';
                });
                $('#costc_id').html(x);
            }
            $('#costc_id').html(x);
        }
    });
}

function getLocations(cid){
    $.ajax({
        url: `${baseUrl}Location_ctrl/getLocationByCostcenterId/${cid}`,
        method: "POST",
        dataType: "json",
        data : {
            cid : $(this).data('id')
        },
        success(response){
            var x = '<option value="">Select Location</option>';
            if(response.status == 200){
                $.each(response.data,function(key,value){
                    x = x + '<option value="'+ value.loc_id +'">'+ value.name +'</option>';
                });
                $('#loc_id').html(x);
            }
            $('#loc_id').html(x);
        }
    });
}

function getMeters(lid){
    $.ajax({
        url: `${baseUrl}Meter_ctrl/getMeterByLocationId/${lid}`,
        method: "POST",
        dataType: "json",
        success(response){
            var x = '<option value="">Select Location</option>';
            if(response.status == 200){
                $.each(response.data,function(key,value){
                    x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
                });
                $('#meter').html(x);
            }
            $('#meter').html(x);
        }
    });
}

function getSubMeters(mid){
    $.ajax({
        url: `${baseUrl}Meter_ctrl/getSubMeters/${mid}`,
        method: "POST",
        dataType: "json",
        success(response){
            var x = '<option value="">Select sub meter</option>';
            if(response.status == 200){
                $.each(response.data,function(key,value){
                    x = x + '<option value="'+ value.mid +'">'+ value.bpno +'</option>';
                });
                $('#sub-meter').html(x);
            }
            $('#sub-meter').html(x);
        }
    });
}


      $(document).on('change','#company',function(){
          var cid = $(this).val();
        getCostCenter(cid);
      });

      $(document).on('change','#costc_id',function(){
          var cid = $(this).val();
          getLocations(cid);
      });

      $(document).on('change','#loc_id',function(){
          var lid = $(this).val();
          getMeters(lid);
      });

      $(document).on('change','#meter',function(){
          var mid = $(this).val();
          getSubMeters(mid);
      });

    
      $(document).on('click','.costcenter_edit',function(){
        var request = $.ajax({
                url: `${baseUrl}Costcenter_ctrl/getCostCenterById`,
                method: "POST",
                data: { cid : $(this).data('id') },
                dataType: "json"
                });    
            request.done(function( response ) {
                console.log(response);
                if(response.status == 200){
                    $('#costcenter-update').show();
                    $('#cancel-btn').show();
                    $('#costcenter-create').hide();
                    $('#reset-btn').hide();

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
            $('#costcenter-update').hide();
            $('#cancel-btn').hide();
            $('#costcenter-create').show();
            $('#reset-btn').show();
      });


      $(document).on('click','.costcenter_delete',function(){
        $.ajax({
            url: `${baseUrl}Costcenter_ctrl/delete_costcenter`,
            method: "POST",
            dataType: "json",
            data : {
                cid : $(this).data('id')
            },
            beforeSend(){
                $('#costcenterList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                alert(response.msg);
                if(response.status == 200){
                    reload();
                }
            }
        });
      });


      

      function reload(){
        $.ajax({
            url: `${baseUrl}Costcenter_ctrl/getCostcenters`,
            method: "GET",
            dataType: "json",
            beforeSend(){
                $('#costcenterList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                if(response.status == 200){
                    var x = '';
                    $.each(response.data,function(key,value){
                        x = x + '<tr>'+
                                        '<td class="text-center">'+ parseInt(key+1) +'</td>'+
                                        '<td class="text-center">'+ value.name +'</td>'+
                                        '<td class="text-center">'+ value.company_name + '</td>'+
                                        '<td class="text-center">'+ value.created_at +'</td>'+
                                        '<td class="text-center">'+ value.fname + ' '+ value.lname +'</td>'+
                                        '<td class="text-center">'+
                                            '<a href="javascript:void(0);" class="costcenter_edit" data-id="'+ value.costc_id +'"><i class="la la-pencil"></i></a>'+
                                            '<a href="javascript:void(0);" class="costcenter_delete" data-id="'+ value.costc_id +'"><i class="la la-trash"></i></a>'+
                                        '</td>'+
                                    '</tr>';

                        $('#costcenterList').html(x);
                    })
                }
            }
        });
      }


    </script>
