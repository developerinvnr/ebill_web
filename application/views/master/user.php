    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          		<h5 class="text-primary" id="page-heading">Create User</h5>
          		<hr/>
          		<form name="f1" method="POST" action="<?php echo base_url();?>master/user">
          		
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">First Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="uid" name="uid" type="hidden" class="form-control" value="<?php echo set_value('uid'); ?>">
                          <input id="fname" name="fname" type="text" class="form-control" value="<?php echo set_value('fname'); ?>">
                          <?php echo form_error('fname'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Last Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="lname" name="lname" type="text" class="form-control" value="<?php echo set_value('lname'); ?>">
                          <?php echo form_error('lname'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Email<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="email" name="email" type="email" class="form-control" value="<?php echo set_value('email'); ?>">
                          <?php echo form_error('email'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Contact No.<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="contact" name="contact" type="number" class="form-control" value="<?php echo set_value('contact'); ?>">
                          <?php echo form_error('contact'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Password<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="password" name="password" type="text" class="form-control" value="<?php echo set_value('password'); ?>">
                          <?php echo form_error('password'); ?>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Gender<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select name="sex" id="sex" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <?php echo form_error('sex'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Role<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select name="utype" id="utype" class="form-control">
                                <option value="">Select User Role</option>
                                  <?php foreach($user_types as $user_type){ ?>
                                <option value="<?php echo $user_type['utype_id']; ?>"><?php echo $user_type['type_name']?></option>
                                <?php } ?>
                          </select>
                          <?php echo form_error('utype'); ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                      <input type="submit" class="btn btn-success uppercase" id="user-create" value="Create">
                      <button class="btn btn-warning uppercase" id="user-update" style="display:none;">Update</button>
    	
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
          		<div class="table-responsive">
                    <table class="table table-bordered text-sm">
                          <thead class="bg-light">
                                  <tr>
                                    <th class="text-center uppercase">S.No.</th>
                                    <th class="text-center uppercase">Name</th>
                                    <th class="text-center uppercase">Email</th>
                                    <th class="text-center uppercase">Contact No.</th>
                                    <th class="text-center uppercase">Gender</th>
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
                                        <td style="width:70px;" class="text-center">
                                            <a title="Edit" href="javascript:void(0);" class="user_edit mr-1" data-id="<?= $user['uid']; ?>"><i class="fas fa-edit"></i></a> | 
                                            <a title="Dlete" href="javascript:void(0);" class="user_delete ml-1" data-id="<?= $user['uid']; ?>"><i class="fas fa-trash text-red"></i></a>
                                        </td>
                                    </tr>
                                <?php } } else {  echo "<tr><td class='text-center' colspan='6'>Record Not Found.</td></tr>"; } ?>
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
    
       $(document).on('click','.user_edit',function(){
        var request = $.ajax({
                url: `${baseUrl}User_ctrl/getUserById`,
                method: "POST",
                data: { uid : $(this).data('id') },
                dataType: "json"
                });    
            request.done(function( response ) {
                console.log(response);
                if(response.status == 200){
                    $('#user-update').show();
                    $('#cancel-btn').show();
                    $('#user-create').hide();
                    $('#reset-btn').hide();

                    $('#uid').val(response.data['uid']);
                    $('#fname').val(response.data['fname']);
                    $('#lname').val(response.data['lname']);
                    $('#email').val(response.data['email']);
                    $('#contact').val(response.data['contact_no']);
                    $('#sex').val(response.data['sex']);
                    $('#utype').val(response.data['utype']);
                }
            });
            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            });
      });

      $('#cancel-btn').on('click',function(){
            $('#user-update').hide();
            $('#cancel-btn').hide();
            $('#user-create').show();
            $('#reset-btn').show();
      });


      $(document).on('keyup','#contact',function(){
        $('#password').val($(this).val());
      })


      $(document).on('click','.user_delete',function(){
        $.ajax({
            url: `${baseUrl}User_ctrl/delete_user`,
            method: "POST",
            dataType: "json",
            data : {
                uid : $(this).data('id')
            },
            beforeSend(){
                $('#userList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
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
            url: `${baseUrl}User_ctrl/getUsers`,
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
                                    '<td class="text-center">'+ value.fname +' '+ value.lname +'</td>'+
                                    '<td class="text-center">'+ value.email +'</td>'+
                                    '<td class="text-center">'+ value.contact_no +'</td>'+
                                    '<td class="text-center">'+ value.sex +'</td>'+
                                    '<td class="text-center">'+ value.role +'</td>'+
                                    '<td class="text-center">'+
                                        '<a href="javascript:void(0);" class="user_edit" data-id="'+ value.uid + '"><i class="fas fa-pencil"></i></a>'+
                                        '<a href="javascript:void(0);" class="user_delete" data-id="'+ value.uid +'"><i class="fas	 fa-trash"></i></a>'+
                                    '</td>'+
                                '</tr>';
                        $('#userList').html(x);
                    })
                }
            }
        });
      }
    </script>