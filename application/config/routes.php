<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'Auth_ctrl/login';
$route['dashboard'] = 'Dashboard_ctrl';
$route['master/company'] = 'company_ctrl';
$route['master/cost-center'] = 'costcenter_ctrl';
$route['master/assign-user-reporting'] = "user_ctrl/user_assign";

$route['master/location'] = 'location_ctrl';
$route['master/meter'] = 'meter_ctrl';
$route['master/user'] = 'user_ctrl';
$route['assign-meter'] = 'Assigntask_ctrl';
$route['assign-meter-show'] = 'Assigntask_ctrl/assign_user_list';
$route['assign-meter-show/(:any)'] = 'Assigntask_ctrl/assign_user_list/$1';
$route['assign-meter-show/(:any)/(:any)'] = 'Assigntask_ctrl/assign_user_list/$1/$2';
$route['assign-meter-show/(:any)/(:any)/(:any)'] = 'Assigntask_ctrl/assign_user_list/$1/$2/$3';

$route['bill-report'] = 'Meter_ctrl/bill_reports';
$route['bill-upload'] = 'Meter_ctrl/bill_upload';
$route['bill-upload/(:any)'] = 'Meter_ctrl/bill_upload/$1';
$route['bill-list'] = 'Meter_ctrl/bill_list';

$route['bill-list/(:any)'] = 'Meter_ctrl/bill_list/$1';
$route['bill-list/(:any)/(:any)'] = 'Meter_ctrl/bill_list/$1/$2';
$route['bill-list/(:any)/(:any)/(:any)'] = 'Meter_ctrl/bill_list/$1/$2/$3';
$route['bill-list/(:any)/(:any)/(:any)/(:any)'] = 'Meter_ctrl/bill_list/$1/$2/$3/$4';
//$route['pending-bill'] = 'Meter_ctrl/bill_pending';

$route['payment/add-payment'] = 'Payment_ctrl/payment';
$route['payment/payment-detail'] = 'Payment_ctrl/payment_detail';
$route['payment/payment-report'] = 'Payment_ctrl/payment_reports';

$route['meter-reading'] = 'Meter_ctrl/meter_reading';
$route['Show-Meter-Reading'] = 'Meter_ctrl/show_meter_readings';
$route['dashboard'] = 'Dashboard_ctrl';
$route['forgot-password'] = 'User_ctrl/change_password';
$route['log-out'] = 'Company_ctrl/logout';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


///////////////////////////////////////////////////////////////////////////////////
////////////////////////// API ROUTES /////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
$route['api/app/version'] = 'api/Auth/app_version';
$route['api/auth/login'] = 'api/Auth/login';
$route['api/company'] = 'api/Company_ctrl';
$route['api/bill-list'] = 'api/Bill_ctrl';
$route['api/bill_upload/tasks'] = 'api/Task_ctrl/billupload_task_summary';
$route['api/bill_upload/tasks/(:any)'] = 'api/Task_ctrl/billupload_task_summary/$1';

$route['api/bill_upload/bill-detail/(:any)'] = 'api/Task_ctrl/bill_detail/$1';
$route['api/bill_upload/bill_submit'] = 'api/Bill_ctrl/index';
$route['api/bill_upload/bill_update/(:any)'] = 'api/Bill_ctrl/index/$1';
