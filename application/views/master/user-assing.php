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
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Users<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <select name="users" id="users" class="form-control">
                                <option value="">Select Reporting</option>
                                <?php foreach($users as $user){ ?>
                                	<option value="<?php echo $user['uid'];?>"><?php echo $user['fname'].' '.$user['lname']; ?></option>
                                <?php } ?>
                            </select>
                            <?php echo form_error('sex'); ?>
                        </div>
                    </div>
                </form>
                
                <div id="userList">
                	
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
    
    MyUserList = [];
    
    function myUserList(){
    	let user_id = $('#users').val();
    	
    	$.ajax({
            url: `${baseUrl}User_ctrl/my_user_list/${user_id}`,
            method: "GET",
            dataType: "json",
            success(response){
                if(response.status == 200){
                	console.log(response.data);
                	$.each(response.data,function(key,value){
                		MyUserList.push(value.uid);
                	});
                	
                	userList();
                }
            }
        });
    }
    
    function userList(){
    	$.ajax({
            url: `${baseUrl}User_ctrl/getUsers`,
            method: "GET",
            dataType: "json",
            beforeSend(){
                $('#userList').html('');
            },
            success(response){
                if(response.status == 200){
                	var x = '';
                    $.each(response.data,function(key,value){
                    	if(MyUserList.includes(value.uid)){
                    		x = x + ' <input type="checkbox" checked class="usercheck" data-id="'+ value.uid +'" />'+ value.fname + ' '+ value.lname  +'';
                    	} else {
                    		x = x + ' <input type="checkbox" class="usercheck" data-id="'+ value.uid +'" />'+ value.fname + ' '+ value.lname  +'';
                    	}
                    	
                    });
                }
                $('#userList').html(x);
            }
        });
    }
    
    function onFulfilled(users) {
      console.log(users);
    }
    function onRejected(error) {
      console.log('error');
    }
    
    $(document).on('change','#users',function(){
    	if($(this).val() == ''){
    		$('#userList').html('');
    		return false;
    	}
		myUserList();
    });
    
    $(document).on('click','.usercheck',function(){
    	const user_id = $(this).data('id');
    	const reporting_id = $('#users').val();
    	let type;
    	if ($(this).is(':checked')) {
    		type = 'add';
    	} else {
    		type = 'remove';
    	}
    	
    	$.ajax({
            url: `${baseUrl}User_ctrl/manage_user_assign`,
            method: "POST",
            dataType: "json",
            data: {
            	type : type,
            	reporting_id : reporting_id,
            	user_id : user_id
            },
            success(response){
                if(response.status == 200){
              		console.log('Done');  	
                }
            }
        });
    });
    
    </script>