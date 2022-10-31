
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model(array('Costcenter_model','Company_model','Location_model','User_model','Assigntask_model','Meter_model'));
  }

  
  function bill_upload_data(){
      if($this->session->userdata('role') == 'super_admin'){
              $bills =  $this->db->query("CALL bill_upload_record_super_admin()")->result_array();
              $this->db->reconnect();
              $payment_pending = $this->db->query("select count(*) as total from bill WHERE sno_id in (SELECT if(isnull(sub_meter_id),sno_id,sub_meter_id) as sno_id FROM task_assign WHERE  status = 1)
                                                AND status = 1
                                                AND payment_amount IS NULL")->result_array();
              
      } else if($this->session->userdata('role') == 'manager'){
          $bills =  $this->db->query("CALL bill_upload_record_manager(".$this->session->userdata('user_id').")")->result_array();
          $this->db->reconnect();
          $payment_pending = $this->db->query("select count(*) as total from bill WHERE sno_id in (SELECT if(isnull(sub_meter_id),sno_id,sub_meter_id) as sno_id FROM task_assign WHERE user_id in (SELECT uid from users WHERE reporting_to = ".$this->session->userdata('user_id')." AND status = 1) AND status = 1)
                                                AND status = 1
                                                AND payment_amount IS NULL")->result_array();
      }else {
          $bills =  $this->db->query("CALL bill_upload_record_operator(".$this->session->userdata('user_id').")")->result_array();
          $this->db->reconnect();
          $payment_pending = $this->db->query("select count(*) as total from bill WHERE sno_id in (SELECT if(isnull(sub_meter_id),sno_id,sub_meter_id) as sno_id FROM task_assign WHERE user_id = ".$this->session->userdata('user_id')." AND status = 1)
                                                AND status = 1
                                                AND payment_amount IS NULL")->result_array();
      }
      $finalarray = array();
      $finalarray['OVER DUE'] = '0';
      $finalarray['DUE'] = '0';
      $finalarray['NOT FILLED'] = '0';
      $finalarray['NOT ASSIGN'] = '0';
      $finalarray['URGENT'] = '0';
      
      foreach($bills as $bill){
          $temp = array();
          if($bill['status'] == 'OVER DUE'){
              $finalarray['OVER DUE'] = $bill['total'];
          }
          if($bill['status'] == 'DUE'){
              $finalarray['DUE'] = $bill['total'];
          }
          if($bill['status'] == 'NOT FILLED'){
              $finalarray['NOT FILLED'] = $bill['total'];
          }
          if($bill['status'] == 'URGENT'){
              $finalarray['URGENT'] = $bill['total'];
          }
          if($bill['status'] == 'NOT ASSIGN'){
              $finalarray['NOT ASSIGN'] = $bill['total'];
          }
      } 
      
      $finalarray['total_meters'] = $finalarray['NOT ASSIGN'] + $finalarray['OVER DUE'] + $finalarray['DUE'] + $finalarray['NOT FILLED']+ $finalarray['URGENT'];
      $finalarray['payment_pending'] = $payment_pending[0]['total'];
      echo json_encode(array('data'=>$bills,'data1'=>$finalarray,'status'=>200)); 
  }
  
  
  function bill_payments(){
      $company = $this->input->post('company');
      if($this->input->post('month') != ''){
        $month = $this->input->post('month');
      } else {
          $month = date('m');
      }
      if($this->input->post('year') != ''){
        $year = $this->input->post('year');
      } else {
          $year = date('Y');
      }
      
      $fromdate = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.'01';
      //echo $fromdate;
      $last_date = cal_days_in_month(CAL_GREGORIAN,$month,$year);
      $todate = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.$last_date;
      
      $query = "select mm.cid,cm.name as company_name,sum(b.gross_amount) as total_bill from meter_master mm
                        JOIN bill b on b.sno_id = mm.mid AND b.from_date BETWEEN '".$fromdate."' AND '".$todate."'
                        JOIN company_master cm on cm.cid = mm.cid";
      if($company != ''){
          $query .= " AND cm.cid = ".$company;
      }
  
    $query .= " GROUP by cid";
      
      
      $result = $this->db->query($query)->result_array();
      if(count($result)>0) {
          echo json_encode(array('data'=>$result,'status'=>200));
      } else {
          echo json_encode(array('msg'=>'no record found.','status'=>500));
      }
  }
  
  
  function index(){
    $data['companies'] = $this->Company_model->company_list();
    $data['main_content'] = $this->load->view('dashboard',$data,true);
  	$this->load->view('admin_layout',$data);
  }

    public function search_bill_report()
    {
        $company = $this->input->post('search_br_company', true);
        $month = $this->input->post('search_br_month', true);
        $year = $this->input->post('search_br_year', true);

        $sql_condition = "b.status = 1 AND mm.status = 1";

        if ($company != "All") {
            $sql_condition .= " AND mm.cid = '$company'";
        }

        if ($month != "All") {
            $sql_condition .= " AND MONTH(b.from_date) = '$month'";
        }
        
        $sql_condition .= " AND YEAR(b.from_date) = '$year'";


        if($this->session->userdata('role') == 'super_admin')
        {
            $data = $this->db->select("
                cm.cid as company_id, 
                cm.name as company_name, 
                sum(b.gross_amount) as payable_amount,
                sum(b.payment_amount) as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->where($sql_condition)
            ->group_by("cm.cid")
            ->order_by("cm.name")
            ->get()->result_array();
        }
        else if($this->session->userdata('role') == 'manager'){
            $user_id = $this->session->userdata('user_id');

            $data = $this->db->select("
                cm.cid as company_id, 
                cm.name as company_name, 
                sum(b.gross_amount) as payable_amount,
                sum(b.payment_amount) as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno, user_id from task_assign where status = 1) m2", "m2.mno = mm.mid")
            ->join("users u", "u.uid = m2.user_id AND u.reporting_to = ".$user_id." AND u.status = 1")
            ->where($sql_condition)
            ->group_by("cm.cid")
            ->order_by("cm.name")
            ->get()->result_array();
        }
        else {
            $user_id = $this->session->userdata('user_id');

            $data = $this->db->select("
                cm.cid as company_id, 
                cm.name as company_name, 
                sum(b.gross_amount) as payable_amount,
                sum(b.payment_amount) as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno from task_assign where user_id = '$user_id' and status = 1) m2", "m2.mno = mm.mid")
            ->where($sql_condition)
            ->group_by("cm.cid")
            ->order_by("cm.name")
            ->get()->result_array();
        }

        ?>
        <table class="table table-bordered table-sm text-sm border" id="datatable_search_bill">
            <thead class="bg-light ">
            <tr class="bg-dark text-center">
                <th>S.No.</th>
                <th>Company</th>
                <th>Bill Amount</th>
                <th>Paid Amount</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                $total_payable_amount = 0;
                $total_payment_amount = 0;
                foreach ($data as $key => $row) {
                    ?>
                    <tr>
                        <td class="text-center"><?=$i; ?></td>
                        <td><?=$row['company_name']; ?></td>
                        <td class="text-right">
                            <?php 
                                $total_payable_amount += $row['payable_amount'];
                                echo $row['payable_amount']; 
                            ?>
                        </td>
                        <td class="text-right">
                            <?php 
                                $total_payment_amount += $row['payment_amount'];
                                echo $row['payment_amount']; 
                            ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" onclick="view_company_bill_report('<?=$row['company_id'];?>','<?=$month;?>','<?=$year;?>')">View Details</button>  
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                    <tr class="bg-secondary">
                        <td class="text-center"></td>
                        <td><b>Total:</b></td>
                        <td class="text-right"><?=number_format($total_payable_amount, 2, '.','');?></td>
                        <td class="text-right"><?=number_format($total_payment_amount, 2, '.','');?></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
        <?php
    }

    public function view_company_bill_report()
    {
        $company = $this->input->post('company_id', true);
        $month = $this->input->post('month', true);
        $year = $this->input->post('year', true);

        $sql_condition = "b.status = 1 AND mm.status = 1 AND mm.cid = '$company'";

        if ($month != "All") {
            $sql_condition .= " AND MONTH(b.from_date) = '$month'";
        }
        
        $sql_condition .= " AND YEAR(b.from_date) = '$year'";

        if($this->session->userdata('role') == 'super_admin'){
            $data = $this->db->select("
                cm.name as company_name, 
                mm.bpno,
                b.bill_no,
                UPPER(DATE_FORMAT(b.from_date, '%b-%Y')) as bill_month,
                b.gross_amount as payable_amount,
                b.payment_amount as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->where($sql_condition)
            ->order_by("b.from_date")
            ->get()->result_array();
        }
        else if($this->session->userdata('role') == 'manager'){
            $user_id = $this->session->userdata('user_id');
            $data = $this->db->select("
                cm.name as company_name, 
                mm.bpno,
                b.bill_no,
                UPPER(DATE_FORMAT(b.from_date, '%b-%Y')) as bill_month,
                b.gross_amount as payable_amount,
                b.payment_amount as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno, user_id from task_assign where status = 1) m2", "m2.mno = mm.mid")
            ->join("users u", "u.uid = m2.user_id AND u.reporting_to = ".$user_id." AND u.status = 1")
            ->where($sql_condition)
            ->order_by("b.from_date")
            ->get()->result_array();
        }
        else {
            $user_id = $this->session->userdata('user_id');
            $data = $this->db->select("
                cm.name as company_name, 
                mm.bpno,
                b.bill_no,
                UPPER(DATE_FORMAT(b.from_date, '%b-%Y')) as bill_month,
                b.gross_amount as payable_amount,
                b.payment_amount as payment_amount
            ")
            ->from("bill b")
            ->join("meter_master mm", "mm.mid = b.sno_id")
            ->join("company_master cm", "cm.cid = mm.cid")
            ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno from task_assign where user_id = '$user_id' and status = 1) m2", "m2.mno = mm.mid") 
            ->where($sql_condition)
            ->order_by("b.from_date")
            ->get()->result_array();
        }

        ?>
        <table class="table table-bordered table-sm text-sm border text-center" id="datatable_view_bill">
            <thead class="bg-light ">
            <tr class="bg-dark text-center">
                <th>S.No.</th>
                <th>Company</th>
                <th>Service No.</th>
                <th>Bill No.</th>
                <th>Bill Month</th>
                <th>Bill Amount</th>
                <th>Paid Amount</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                $total_payable_amount = 0;
                $total_payment_amount = 0;
                foreach ($data as $key => $row) {
                    ?>
                    <tr>
                        <td><?=$i; ?></td>
                        <td class="text-left"><?=$row['company_name']; ?></td>
                        <td><?=$row['bpno']; ?></td>
                        <td><?=$row['bill_no']; ?></td>
                        <td><?=$row['bill_month']; ?></td>
                        <td class="text-right">
                            <?php 
                                $total_payable_amount += $row['payable_amount'];
                                echo $row['payable_amount']; 
                            ?>
                        </td>
                        <td class="text-right">
                            <?php 
                                $total_payment_amount += $row['payment_amount'];
                                echo $row['payment_amount']; 
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                    <tr class="bg-secondary">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><b>Total:</b></td>
                        <td class="text-right"><?=number_format($total_payable_amount, 2, '.','');?></td>
                        <td class="text-right"><?=number_format($total_payment_amount, 2, '.','');?></td>
                    </tr>
            </tbody>
        </table>
        <?php
    }

    public function search_month_wise_bill_report()
    {
        $company = $this->input->post('search_bp_company', true);
        $year = $this->input->post('search_bp_year', true);
        $is_bp = $this->input->post('search_bp_no', true);

        $sql_condition = "b.status = 1 AND mm.status = 1";

        if ($company != "All") {
            $sql_condition .= " AND mm.cid = '$company'";
        }

        
        $sql_condition .= " AND YEAR(b.from_date) = '$year'";


        if($is_bp)
        {
            if($this->session->userdata('role') == 'super_admin')
            {
                $data = $this->db->select("
                    cm.name as company_name,
                    l.name as location_name,
                    mm.bpno,
                    
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 1) THEN b.bill_no ELSE NULL END) AS jan_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 2) THEN b.bill_no ELSE NULL END) AS feb_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 3) THEN b.bill_no ELSE NULL END) AS mar_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 4) THEN b.bill_no ELSE NULL END) AS apr_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 5) THEN b.bill_no ELSE NULL END) AS may_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 6) THEN b.bill_no ELSE NULL END) AS jun_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 7) THEN b.bill_no ELSE NULL END) AS jul_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 8) THEN b.bill_no ELSE NULL END) AS aug_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 9) THEN b.bill_no ELSE NULL END) AS sep_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 10) THEN b.bill_no ELSE NULL END) AS oct_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 11) THEN b.bill_no ELSE NULL END) AS nov_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 12) THEN b.bill_no ELSE NULL END) AS dec_bill_no,
                    
                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->join("location_master l", "l.loc_id = mm.loc_id")
                ->where($sql_condition)
                ->group_by("mm.bpno")
                ->order_by("cm.name, l.name, mm.bpno")
                ->get()->result_array();
            }
            else if($this->session->userdata('role') == 'manager'){
                $user_id = $this->session->userdata('user_id');

                $data = $this->db->select("
                    cm.name as company_name,
                    l.name as location_name,
                    mm.bpno,

                    GROUP_CONCAT(CASE WHEN (month(from_date) = 1) THEN b.bill_no ELSE NULL END) AS jan_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 2) THEN b.bill_no ELSE NULL END) AS feb_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 3) THEN b.bill_no ELSE NULL END) AS mar_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 4) THEN b.bill_no ELSE NULL END) AS apr_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 5) THEN b.bill_no ELSE NULL END) AS may_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 6) THEN b.bill_no ELSE NULL END) AS jun_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 7) THEN b.bill_no ELSE NULL END) AS jul_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 8) THEN b.bill_no ELSE NULL END) AS aug_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 9) THEN b.bill_no ELSE NULL END) AS sep_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 10) THEN b.bill_no ELSE NULL END) AS oct_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 11) THEN b.bill_no ELSE NULL END) AS nov_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 12) THEN b.bill_no ELSE NULL END) AS dec_bill_no,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno, user_id from task_assign where status = 1) m2", "m2.mno = mm.mid")
                ->join("location_master l", "l.loc_id = mm.loc_id")
                ->join("users u", "u.uid = m2.user_id AND u.reporting_to = ".$user_id." AND u.status = 1")
                ->where($sql_condition)
                ->group_by("mm.bpno")
                ->order_by("cm.name, l.name, mm.bpno")
                ->get()->result_array();
            }
            else {
                $user_id = $this->session->userdata('user_id');

                $data = $this->db->select("
                    cm.name as company_name,
                    l.name as location_name,
                    mm.bpno,

                    GROUP_CONCAT(CASE WHEN (month(from_date) = 1) THEN b.bill_no ELSE NULL END) AS jan_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 2) THEN b.bill_no ELSE NULL END) AS feb_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 3) THEN b.bill_no ELSE NULL END) AS mar_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 4) THEN b.bill_no ELSE NULL END) AS apr_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 5) THEN b.bill_no ELSE NULL END) AS may_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 6) THEN b.bill_no ELSE NULL END) AS jun_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 7) THEN b.bill_no ELSE NULL END) AS jul_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 8) THEN b.bill_no ELSE NULL END) AS aug_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 9) THEN b.bill_no ELSE NULL END) AS sep_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 10) THEN b.bill_no ELSE NULL END) AS oct_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 11) THEN b.bill_no ELSE NULL END) AS nov_bill_no,
                    GROUP_CONCAT(CASE WHEN (month(from_date) = 12) THEN b.bill_no ELSE NULL END) AS dec_bill_no,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno from task_assign where user_id = '$user_id' and status = 1) m2", "m2.mno = mm.mid")
                ->join("location_master l", "l.loc_id = mm.loc_id")
                ->where($sql_condition)
                ->group_by("mm.bpno")
                ->order_by("cm.name, l.name, mm.bpno")
                ->get()->result_array();
            }

            ?>
            <table class="table table-bordered table-sm text-sm border text-center" id="datatable_month_wise_bill">
                <thead class="bg-light ">
                <tr class="bg-dark text-center">
                    <th rowspan="2">S.No.</th>
                    <th rowspan="2">Company</th>
                    <th rowspan="2">Location</th>
                    <th rowspan="2">Service No.</th>
                    <th colspan="3" class="bg-secondary">JAN</th>
                    <th colspan="3">FEB</th>
                    <th colspan="3" class="bg-secondary">MAR</th>
                    <th colspan="3">APR</th>
                    <th colspan="3" class="bg-secondary">MAY</th>
                    <th colspan="3">JUN</th>
                    <th colspan="3" class="bg-secondary">JUL</th>
                    <th colspan="3">AUG</th>
                    <th colspan="3" class="bg-secondary">SEP</th>
                    <th colspan="3">OCT</th>
                    <th colspan="3" class="bg-secondary">NOV</th>
                    <th colspan="3">DEC</th>
                    <th class="bg-primary" rowspan="2">Total Bill</th>
                    <th class="bg-success" rowspan="2">Total Paid</th>
                </tr>
                <tr class="bg-dark text-center">
                    <th class="bg-info">Jan Bill No.</th>
                    <th class="bg-primary">Jan Bill</th>
                    <th class="bg-success">Jan Paid</th>
                    <th class="bg-info">Feb Bill No.</th>
                    <th class="bg-primary">Feb Bill</th>
                    <th class="bg-success">Feb Paid</th>
                    <th class="bg-info">Mar Bill No.</th>
                    <th class="bg-primary">Mar Bill</th>
                    <th class="bg-success">Mar Paid</th>
                    <th class="bg-info">Apr Bill No.</th>
                    <th class="bg-primary">Apr Bill</th>
                    <th class="bg-success">Apr Paid</th>
                    <th class="bg-info">May Bill No.</th>
                    <th class="bg-primary">May Bill</th>
                    <th class="bg-success">May Paid</th>
                    <th class="bg-info">Jun Bill No.</th>
                    <th class="bg-primary">Jun Bill</th>
                    <th class="bg-success">Jun Paid</th>
                    <th class="bg-info">Jul Bill No.</th>
                    <th class="bg-primary">Jul Bill</th>
                    <th class="bg-success">Jul Paid</th>
                    <th class="bg-info">Aug Bill No.</th>
                    <th class="bg-primary">Aug Bill</th>
                    <th class="bg-success">Aug Paid</th>
                    <th class="bg-info">Sep Bill No.</th>
                    <th class="bg-primary">Sep Bill</th>
                    <th class="bg-success">Sep Paid</th>
                    <th class="bg-info">Oct Bill No.</th>
                    <th class="bg-primary">Oct Bill</th>
                    <th class="bg-success">Oct Paid</th>
                    <th class="bg-info">Nov Bill No.</th>
                    <th class="bg-primary">Nov Bill</th>
                    <th class="bg-success">Nov Paid</th>
                    <th class="bg-info">Dec Bill No.</th>
                    <th class="bg-primary">Dec Bill</th>
                    <th class="bg-success">Dec Paid</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $jan_total_payable_amount = 0;
                    $feb_total_payable_amount = 0;
                    $mar_total_payable_amount = 0;
                    $apr_total_payable_amount = 0;
                    $may_total_payable_amount = 0;
                    $jun_total_payable_amount = 0;
                    $jul_total_payable_amount = 0;
                    $aug_total_payable_amount = 0;
                    $sep_total_payable_amount = 0;
                    $oct_total_payable_amount = 0;
                    $nov_total_payable_amount = 0;
                    $dec_total_payable_amount = 0;
                    $total_payable_amount = 0;

                    $jan_total_payment_amount = 0;
                    $feb_total_payment_amount = 0;
                    $mar_total_payment_amount = 0;
                    $apr_total_payment_amount = 0;
                    $may_total_payment_amount = 0;
                    $jun_total_payment_amount = 0;
                    $jul_total_payment_amount = 0;
                    $aug_total_payment_amount = 0;
                    $sep_total_payment_amount = 0;
                    $oct_total_payment_amount = 0;
                    $nov_total_payment_amount = 0;
                    $dec_total_payment_amount = 0;
                    $total_payment_amount = 0;
                    foreach ($data as $key => $row) {
                        ?>
                        <tr>
                            <td class="text-center"><?=$i; ?></td>
                            <td><?=$row['company_name']; ?></td>
                            <td><?=$row['location_name']; ?></td>
                            <td><?=$row['bpno']; ?></td>
                            <td class="text-center bg-info-100"><?=$row['jan_bill_no'];?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jan_total_payable_amount += $row['jan_payable_amount'];
                                    echo $row['jan_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jan_total_payment_amount += $row['jan_payment_amount'];
                                    echo $row['jan_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['feb_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $feb_total_payable_amount += $row['feb_payable_amount'];
                                    echo $row['feb_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $feb_total_payment_amount += $row['feb_payment_amount'];
                                    echo $row['feb_payment_amount']; 
                                ?>
                            </td>
                            
                            <td class="text-center bg-info-100"><?=$row['mar_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $mar_total_payable_amount += $row['mar_payable_amount'];
                                    echo $row['mar_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $mar_total_payment_amount += $row['mar_payment_amount'];
                                    echo $row['mar_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['apr_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $apr_total_payable_amount += $row['apr_payable_amount'];
                                    echo $row['apr_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $apr_total_payment_amount += $row['apr_payment_amount'];
                                    echo $row['apr_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['may_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $may_total_payable_amount += $row['may_payable_amount'];
                                    echo $row['may_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $may_total_payment_amount += $row['may_payment_amount'];
                                    echo $row['may_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['jun_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jun_total_payable_amount += $row['jun_payable_amount'];
                                    echo $row['jun_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jun_total_payment_amount += $row['jun_payment_amount'];
                                    echo $row['jun_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['jul_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jul_total_payable_amount += $row['jul_payable_amount'];
                                    echo $row['jul_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jul_total_payment_amount += $row['jul_payment_amount'];
                                    echo $row['jul_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['aug_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $aug_total_payable_amount += $row['aug_payable_amount'];
                                    echo $row['aug_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $aug_total_payment_amount += $row['aug_payment_amount'];
                                    echo $row['aug_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['sep_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $sep_total_payable_amount += $row['sep_payable_amount'];
                                    echo $row['sep_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $sep_total_payment_amount += $row['sep_payment_amount'];
                                    echo $row['sep_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['oct_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $oct_total_payable_amount += $row['oct_payable_amount'];
                                    echo $row['oct_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $oct_total_payment_amount += $row['oct_payment_amount'];
                                    echo $row['oct_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['nov_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $nov_total_payable_amount += $row['nov_payable_amount'];
                                    echo $row['nov_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $nov_total_payment_amount += $row['nov_payment_amount'];
                                    echo $row['nov_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-center bg-info-100"><?=$row['dec_bill_no']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $dec_total_payable_amount += $row['dec_payable_amount'];
                                    echo $row['dec_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $dec_total_payment_amount += $row['dec_payment_amount'];
                                    echo $row['dec_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $total_payable_amount += $row['total_payable_amount'];
                                    echo $row['total_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $total_payment_amount += $row['total_payment_amount'];
                                    echo $row['total_payment_amount']; 
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                        <tr class="bg-secondary">
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"><b>Total:</b></td>
                            <td class="text-right bg-primary"><?=number_format($jan_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jan_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($feb_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($feb_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($mar_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($mar_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($apr_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($apr_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($may_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($may_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($jun_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jun_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($jul_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jul_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($aug_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($aug_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($sep_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($sep_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($oct_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($oct_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($nov_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($nov_total_payment_amount, 2, '.','');?></td>
                            <td></td>
                            <td class="text-right bg-primary"><?=number_format($dec_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($dec_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($total_payment_amount, 2, '.','');?></td>
                        </tr>
                </tbody>
            </table>
            <?php
        } else {

            if($this->session->userdata('role') == 'super_admin')
            {
                $data = $this->db->select("
                    cm.name as company_name,
                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->where($sql_condition)
                ->group_by("cm.cid")
                ->order_by("cm.name")
                ->get()->result_array();
            }
            else if($this->session->userdata('role') == 'manager'){
                $user_id = $this->session->userdata('user_id');

                $data = $this->db->select("
                    cm.name as company_name,
                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno, user_id from task_assign where status = 1) m2", "m2.mno = mm.mid")
                ->join("users u", "u.uid = m2.user_id AND u.reporting_to = ".$user_id." AND u.status = 1")
                ->where($sql_condition)
                ->group_by("cm.cid")
                ->order_by("cm.name")
                ->get()->result_array();
            }
            else {
                $user_id = $this->session->userdata('user_id');

                $data = $this->db->select("
                    cm.name as company_name,
                    SUM(CASE WHEN (month(from_date) = 1) THEN b.gross_amount ELSE 0 END) AS jan_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.gross_amount ELSE 0 END) AS feb_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.gross_amount ELSE 0 END) AS mar_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.gross_amount ELSE 0 END) AS apr_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.gross_amount ELSE 0 END) AS may_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.gross_amount ELSE 0 END) AS jun_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.gross_amount ELSE 0 END) AS jul_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.gross_amount ELSE 0 END) AS aug_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.gross_amount ELSE 0 END) AS sep_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.gross_amount ELSE 0 END) AS oct_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.gross_amount ELSE 0 END) AS nov_payable_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.gross_amount ELSE 0 END) AS dec_payable_amount,
                    sum(b.gross_amount) as total_payable_amount,

                    SUM(CASE WHEN (month(from_date) = 1) THEN b.payment_amount ELSE 0 END) AS jan_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 2) THEN b.payment_amount ELSE 0 END) AS feb_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 3) THEN b.payment_amount ELSE 0 END) AS mar_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 4) THEN b.payment_amount ELSE 0 END) AS apr_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 5) THEN b.payment_amount ELSE 0 END) AS may_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 6) THEN b.payment_amount ELSE 0 END) AS jun_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 7) THEN b.payment_amount ELSE 0 END) AS jul_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 8) THEN b.payment_amount ELSE 0 END) AS aug_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 9) THEN b.payment_amount ELSE 0 END) AS sep_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 10) THEN b.payment_amount ELSE 0 END) AS oct_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 11) THEN b.payment_amount ELSE 0 END) AS nov_payment_amount,
                    SUM(CASE WHEN (month(from_date) = 12) THEN b.payment_amount ELSE 0 END) AS dec_payment_amount,
                    sum(b.payment_amount) as total_payment_amount
                ")
                ->from("bill b")
                ->join("meter_master mm", "mm.mid = b.sno_id")
                ->join("company_master cm", "cm.cid = mm.cid")
                ->join("(select if(isnull(sub_meter_id),sno_id,sub_meter_id) as mno from task_assign where user_id = '$user_id' and status = 1) m2", "m2.mno = mm.mid")
                ->where($sql_condition)
                ->group_by("cm.cid")
                ->order_by("cm.name")
                ->get()->result_array();
            }

            ?>
            <table class="table table-bordered table-sm text-sm border" id="datatable_month_wise_bill">
                <thead class="bg-light ">
                <tr class="bg-dark text-center">
                    <th>S.No.</th>
                    <th>Company</th>
                    <th class="bg-primary">Jan Bill</th>
                    <th class="bg-success">Jan Paid</th>
                    <th class="bg-primary">Feb Bill</th>
                    <th class="bg-success">Feb Paid</th>
                    <th class="bg-primary">Mar Bill</th>
                    <th class="bg-success">Mar Paid</th>
                    <th class="bg-primary">Apr Bill</th>
                    <th class="bg-success">Apr Paid</th>
                    <th class="bg-primary">May Bill</th>
                    <th class="bg-success">May Paid</th>
                    <th class="bg-primary">Jun Bill</th>
                    <th class="bg-success">Jun Paid</th>
                    <th class="bg-primary">Jul Bill</th>
                    <th class="bg-success">Jul Paid</th>
                    <th class="bg-primary">Aug Bill</th>
                    <th class="bg-success">Aug Paid</th>
                    <th class="bg-primary">Sep Bill</th>
                    <th class="bg-success">Sep Paid</th>
                    <th class="bg-primary">Oct Bill</th>
                    <th class="bg-success">Oct Paid</th>
                    <th class="bg-primary">Nov Bill</th>
                    <th class="bg-success">Nov Paid</th>
                    <th class="bg-primary">Dec Bill</th>
                    <th class="bg-success">Dec Paid</th>
                    <th class="bg-primary">Total Bill</th>
                    <th class="bg-success">Total Paid</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $jan_total_payable_amount = 0;
                    $feb_total_payable_amount = 0;
                    $mar_total_payable_amount = 0;
                    $apr_total_payable_amount = 0;
                    $may_total_payable_amount = 0;
                    $jun_total_payable_amount = 0;
                    $jul_total_payable_amount = 0;
                    $aug_total_payable_amount = 0;
                    $sep_total_payable_amount = 0;
                    $oct_total_payable_amount = 0;
                    $nov_total_payable_amount = 0;
                    $dec_total_payable_amount = 0;
                    $total_payable_amount = 0;

                    $jan_total_payment_amount = 0;
                    $feb_total_payment_amount = 0;
                    $mar_total_payment_amount = 0;
                    $apr_total_payment_amount = 0;
                    $may_total_payment_amount = 0;
                    $jun_total_payment_amount = 0;
                    $jul_total_payment_amount = 0;
                    $aug_total_payment_amount = 0;
                    $sep_total_payment_amount = 0;
                    $oct_total_payment_amount = 0;
                    $nov_total_payment_amount = 0;
                    $dec_total_payment_amount = 0;
                    $total_payment_amount = 0;
                    foreach ($data as $key => $row) {
                        ?>
                        <tr>
                            <td class="text-center"><?=$i; ?></td>
                            <td><?=$row['company_name']; ?></td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jan_total_payable_amount += $row['jan_payable_amount'];
                                    echo $row['jan_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jan_total_payment_amount += $row['jan_payment_amount'];
                                    echo $row['jan_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $feb_total_payable_amount += $row['feb_payable_amount'];
                                    echo $row['feb_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $feb_total_payment_amount += $row['feb_payment_amount'];
                                    echo $row['feb_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $mar_total_payable_amount += $row['mar_payable_amount'];
                                    echo $row['mar_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $mar_total_payment_amount += $row['mar_payment_amount'];
                                    echo $row['mar_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $apr_total_payable_amount += $row['apr_payable_amount'];
                                    echo $row['apr_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $apr_total_payment_amount += $row['apr_payment_amount'];
                                    echo $row['apr_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $may_total_payable_amount += $row['may_payable_amount'];
                                    echo $row['may_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $may_total_payment_amount += $row['may_payment_amount'];
                                    echo $row['may_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jun_total_payable_amount += $row['jun_payable_amount'];
                                    echo $row['jun_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jun_total_payment_amount += $row['jun_payment_amount'];
                                    echo $row['jun_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $jul_total_payable_amount += $row['jul_payable_amount'];
                                    echo $row['jul_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $jul_total_payment_amount += $row['jul_payment_amount'];
                                    echo $row['jul_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $aug_total_payable_amount += $row['aug_payable_amount'];
                                    echo $row['aug_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $aug_total_payment_amount += $row['aug_payment_amount'];
                                    echo $row['aug_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $sep_total_payable_amount += $row['sep_payable_amount'];
                                    echo $row['sep_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $sep_total_payment_amount += $row['sep_payment_amount'];
                                    echo $row['sep_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $oct_total_payable_amount += $row['oct_payable_amount'];
                                    echo $row['oct_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $oct_total_payment_amount += $row['oct_payment_amount'];
                                    echo $row['oct_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $nov_total_payable_amount += $row['nov_payable_amount'];
                                    echo $row['nov_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $nov_total_payment_amount += $row['nov_payment_amount'];
                                    echo $row['nov_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $dec_total_payable_amount += $row['dec_payable_amount'];
                                    echo $row['dec_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $dec_total_payment_amount += $row['dec_payment_amount'];
                                    echo $row['dec_payment_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-primary-100">
                                <?php 
                                    $total_payable_amount += $row['total_payable_amount'];
                                    echo $row['total_payable_amount']; 
                                ?>
                            </td>
                            <td class="text-right bg-success-100">
                                <?php 
                                    $total_payment_amount += $row['total_payment_amount'];
                                    echo $row['total_payment_amount']; 
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                        <tr class="bg-secondary">
                            <td class="text-center"></td>
                            <td><b>Total:</b></td>
                            <td class="text-right bg-primary"><?=number_format($jan_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jan_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($feb_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($feb_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($mar_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($mar_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($apr_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($apr_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($may_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($may_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($jun_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jun_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($jul_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($jul_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($aug_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($aug_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($sep_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($sep_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($oct_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($oct_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($nov_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($nov_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($dec_total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($dec_total_payment_amount, 2, '.','');?></td>
                            <td class="text-right bg-primary"><?=number_format($total_payable_amount, 2, '.','');?></td>
                            <td class="text-right bg-success"><?=number_format($total_payment_amount, 2, '.','');?></td>
                        </tr>
                </tbody>
            </table>
            <?php
        }
    }
}