    <section class="content mt-2">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          	<div class="offset-sm-1 col-10">
          		<h4 class="text-primary" id="page-heading">Meter Readings</h4>
          		<table class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th>S.No.</th>
                      <th>Service No.</th>
                      <th>Reading value</th>
                      <th>Reading Date</th>
                      <th>Next Reading Date</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php $c=1; foreach($readings as $reading){ ?>
                          <td><?php echo $c; ?></td>
                          <td><?php echo $reading['bpno']; ?></td>
                          <td><?php echo $reading['reading_value']; ?></td>
                          <td><?php echo $reading['reading_date']; ?></td>
                          <td><?php echo date('Y-m-d', strtotime("+".$reading['upload_frq']."days")); ?></td>
                        </tr>
                        <?php $c++; } ?>
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
	
    </script>
