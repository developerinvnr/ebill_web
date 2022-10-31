    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="row">
          	<div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          		<h5 class="text-primary" id="page-heading">Create Company</h5>
          		<hr/>
          		<form name="f1" method="POST" action="<?php echo base_url();?>master/company">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Name<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <input id="cid" name="cid" type="hidden" class="form-control" value="<?php echo set_value('cid'); ?>">
                          <input id="cname" name="cname" type="text" class="form-control" value="<?php echo set_value('cname'); ?>">
                          <?php echo form_error('cname'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Address<label class="text-danger">*</label></label>
                        <div class="col-sm-8">
                          <textarea id="address" class="form-control" name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                          <?php echo form_error('address'); ?>
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
                        <label for="inputEmail3" class="col-sm-4 col-form-label">Alternet No.</label>
                        <div class="col-sm-8">
                          <input id="alternet_no" name="alternet_no" type="number" class="form-control" value="<?php echo set_value('alternet_no'); ?>">
                          <?php echo form_error('alternet_no'); ?>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                      <input type="submit" class="btn btn-success uppercase" id="company-create" value="Create">
                      <button class="btn btn-warning uppercase" id="company-update" style="display:none;">Update</button>
    
                      <input type="reset" class="btn btn-secondary uppercase" id="cancel-btn" style="display:none;" value="Cancel">
                      <input type="reset" class="btn btn-secondary uppercase" id="reset-btn" value="Reset">
                    </div>
                </form>
          	</div>
          	<div class="col-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
          		<div class="table-responsive">
                    <table class="table table-bordered text-sm" id="companyTable">
                        <thead class="bg-light">
                            <tr>
                            <th class="text-center uppercase">S.No.</th>
                            <th class="text-center uppercase">Company Name</th>
                            <th class="text-center uppercase">Contact No.</th>
                            <th class="text-center uppercase">Alternet No.</th>
                            <th class="text-center uppercase">Email</th>
                            <th class="text-center uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody id="companyList">
                            <?php $c=1; foreach($companies as $company){ ?>
                                <tr>
                                    <td class="text-center"><?= $c++; ?></td>
                                    <td class="text-left"><?= $company['name']; ?></td>
                                    <td class="text-center"><?= $company['contact_no']; ?></td>
                                    <td class="text-center"><?= $company['alternet_no']; ?></td>
                                    <td class="text-center"><?= $company['email']; ?></td>
                                    <td style="width:70px;" class="text-center">
                                        <a title="Edit" href="javascript:void(0);" class="company_edit mr-1" data-id="<?= $company['cid']; ?>"><i class="fas fa-edit"></i></a> | 
                                        <a title="Delete" href="javascript:void(0);" class="company_delete ml-1" data-id="<?= $company['cid']; ?>"><i class="fas fa-trash text-red"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
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
    
      $(document).on('click','.company_edit',function(){
        var request = $.ajax({
                url: `${baseUrl}Company_ctrl/getCompanyById`,
                method: "POST",
                data: { cid : $(this).data('id') },
                dataType: "json"
                });    
            request.done(function( response ) {
                if(response.status == 200){
                	$('#page-heading').html('Update Company');
                    $('#company-update').show();
                    $('#cancel-btn').show();
                    $('#company-create').hide();
                    $('#reset-btn').hide();

                    $('#cid').val(response.data['cid']);
                    $('#cname').val(response.data['name']);
                    $('#address').val(response.data['address']);
                    $('#email').val(response.data['email']);
                    $('#contact').val(response.data['contact_no']);
                    $('#alternet_no').val(response.data['alternet_no']);
                }
            });
            request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
            });
      });

      $('#cancel-btn').on('click',function(){
      		$('#page-heading').html('Create Company');
            $('#company-update').hide();
            $('#cancel-btn').hide();
            $('#company-create').show();
            $('#reset-btn').show();
      });


      $(document).on('click','.company_delete',function(){
        $.ajax({
            url: `${baseUrl}Company_ctrl/delete_company`,
            method: "POST",
            dataType: "json",
            data : {
                cid : $(this).data('id')
            },
            beforeSend(){
                $('#companyList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                alert(response.msg);
                if(response.status == 200){
                    reload();
                }
            }
        });
      });


	$(document).on('click','#add_more',function(){
		$('#exampleModal').modal({
			show : true,
			keyboard : false
		})
	});

      function reload(){
        $.ajax({
            url: `${baseUrl}Company_ctrl/getCompanies`,
            method: "GET",
            dataType: "json",
            beforeSend(){
                $('#companyList').html('<tr><td colspan="6"><p class="text-center">Loading..</p></td></tr>');
            },
            success(response){
                if(response.status == 200){
                    var x = '';
                    $.each(response.data,function(key,value){
                        x = x + '<tr>'+
                            '<td class="text-center">' + parseInt(key+1) + '</td>'+
                            '<td class="text-center">'+ value.name +'</td>'+
                            '<td class="text-center">'+ value.contact_no +'</td>'+
                            '<td class="text-center">'+ value.alternet_no +'</td>'+
                            '<td class="text-center">'+ value.email +'</td>'+
                            '<td class="text-center">'+
                                '<a href="javascript:void(0);" class="company_edit" data-id="'+ value.cid +'"><i class="fas fa-edit"></i></a>'+
                                '<a href="javascript:void(0);" class="company_delete" data-id="'+ value.cid +'"><i class="fas fa-trash"></i></a>'+
                            '</td>'+
                        '</tr>';

                        $('#companyList').html(x);
                    })
                }
            }
        });
      }
      
      
      $('#companyTable').DataTable({
//        	"searching": false,
//         "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false 
    });


    </script>