    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="offset-sm-1 col-md-10 col-sm-12">
          		<h5 class="text-primary" id="page-heading">Forgot Password</h5>
          		<hr/>
          		<form name="f1" method="POST" enctype='multipart/form-data' action="<?php echo base_url();?>Forgot-Password">
          		<?php echo $this->session->flashdata('msg');?>
                        <div class="row mb-3">
                              <label for="inputEmail3" class="col-md-3">Old Password<label class="text-danger">*</label></label>
                              <div class="col-md-8">
                                    <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" autocomplete="off" />
                                    <?php echo form_error('old_password'); ?>
                              </div>
                              <div id="old_password_success" style="display:none;">Valid</div>
                              <div id="old_password_error" style="display:none;">InValid</div>
                        </div>
                        <div class="row mb-3">
                              <label for="inputEmail3" class="col-md-3">New Password<label class="text-danger">*</label></label>
                              <div class="col-md-8">
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" autocomplete="off" />
                                    <?php echo form_error('new_password'); ?>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="inputEmail3" class="col-md-3">Confirm Password<label class="text-danger">*</label></label>
                              <div class="col-md-8">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" autocomplete="off" />
                                    <?php echo form_error('confirm_password'); ?>
                              </div>
                        </div>
                        
                    <div class="text-center mt-4">
                      <input type="submit" class="btn btn-success uppercase" id="forgot-submit" value="Submit">
                      <button class="btn btn-warning uppercase" id="assign-update" style="display:none;">Update</button>
    	
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
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
    
	$(document).on('blur','#old_password',function(){
		var password = $(this).val();
		$.ajax({
            url: `${baseUrl}User_ctrl/check_password`,
            method: "POST",
            dataType: "json",
            data : {
               'password' : password
            },
            success(response){
                if(response.status == 200){
                  $('#old_password_success').show();
                  $('#forgot-submit').removeAttr('disabled');
                  $('#old_password_error').hide();
                } else {
                  $('#old_password_success').hide();
                  $('#forgot-submit').attr('disabled','true');;
                  $('#old_password_error').show();    
                }
            }
        });
	});
	
	
    </script>
