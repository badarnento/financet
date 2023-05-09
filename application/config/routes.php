<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override']       = 'handler_ctl/error_404';
$route['unauthorized']       = 'handler_ctl/error_401';

// Main
$route['index']     = 'home';
$route['home']      = 'home';
$route['dashboard'] = 'home';

//handler
$route['handler/execute/(:any)']    = 'handler_ctl/execute/$1';
$route['handler/get/(:any)']        = 'handler_ctl/get_something/$1';
$route['handler/get/(:any)/(:any)'] = 'handler_ctl/get_something/$1/$2';
$route['translate_uri_dashes']      = FALSE;

// Auth
$login_route         = $this->config->item('login_hash_url');
$route[$login_route] = 'ion_auth/auth/login_post';
$route['login']      = 'ion_auth/auth/login';
$route['logout']     = 'ion_auth/auth/logout';

// User Account
$route['account/edit'] = 'ion_auth/account/edit_profile';

// Administrator
$route['admin/users']           = 'administrator/users_ctl';
$route['api/users/(:any)']      = 'administrator/users_ctl/$1';
$route['admin/groups']          = 'administrator/groups_ctl';
$route['api/groups/(:any)']     = 'administrator/groups_ctl/$1';
$route['admin/menu-management'] = 'menurights';

// Master
$route['master/directorat']     = 'master/directorat';
$route['master/division']       = 'master/division';
$route['master/unit']           = 'master/unit';
$route['master/tribe']          = 'master/tribe';
$route['master/grouprpt']       = 'master/grouprpt';
$route['master/fpjp']           = 'master/fpjp';
$route['master/pph']            = 'master/pph';
$route['master/ppn']            = 'master/ppn';
$route['master/program']        = 'master/program';
$route['master/master_bank']    = 'master/master_bank';
$route['master/master_bank_la'] = 'master/master_bank_la';
$route['master/rekanan_tax'] 	= 'master/vendor_tax';

// Budget
$route['budget/rkap']          = 'capex/index';
$route['budget/budget-header'] = 'capex/budget_header';

// GL Header
$route['gl/gl-header']                               = 'gl/gl_header';
$route['uservalidate/user-validate']                 = 'uservalidate/user_validate';
$route['userapprovedjournal/user-approved-journal']  = 'userapprovedjournal/user_approved_journal';
$route['paymentbatch/payment-batch-inquiry']         = 'paymentbatch/payment_batch_inquiry';
$route['paymentbatch/payment-batch']                 = 'paymentbatch/payment_batch';
$route['budgetrelocation/budget-relocation']         = 'budgetrelocation/budget_relocation';
$route['budgetredistribution/budget-redistribution'] = 'budgetredistribution/budget_redistribution';

$route['user-tax/(:any)']                         = 'user_tax/user_tax/$1';
$route['user-verification/(:any)']                   = 'uservalidate/user_validate/$1';
$route['user-approved-journal/(:any)']               = 'userapprovedjournal/user_approved_journal/$1';
$route['user-approved-journal-hou/(:any)']           = 'userapprovedjournalhou/user_approved_journal/$1';
$route['user-approved-journal-hog/(:any)']           = 'userapprovedjournalhog/user_approved_journal/$1';

$route['invoice-tax']                   = 'user_tax/user_tax';


// Purchase
$route['budget/purchase-requisition']             = 'purchase/requisition';
$route['budget/purchase-requisition/create']      = 'purchase/new_pr';
$route['budget/purchase-requisition/(:any)']      = 'purchase/view_pr/$1';
$route['budget/purchase-requisition/edit/(:any)'] = 'purchase/edit_pr/$1';

$route['budget/purchase-order']                   = 'purchase/order';
$route['budget/purchase-order/create']            = 'purchase/new_po';
$route['budget/purchase-order/(:any)']            = 'purchase/view_po/$1';
$route['budget/purchase-order/create/(:any)']     = 'purchase/create_po/$1';
$route['budget/purchase-order/edit/(:any)']       = 'purchase/edit_po/$1';

// Purchase NEW
$route['purchase-requisition']                   = 'pr_po/PR_ctl';
$route['purchase-requisition/create']            = 'pr_po/PR_ctl/create_pr';
$route['purchase-requisition/(:any)']            = 'pr_po/PR_ctl/view_pr/$1';
$route['purchase-requisition/edit/(:any)']       = 'pr_po/PR_ctl/edit_pr/$1';
$route['purchase-requisition/print/(:any)']      = 'pr_po/PR_ctl/printPDF/$1';
$route['purchase-requisition/api/(:any)']        = 'pr_po/PR_ctl/$1';
$route['purchase-requisition/api/(:any)/(:any)'] = 'pr_po/PR_ctl/$1/$2';

$route['pr/approval']            = 'pr_po/ApprovalPR_ctl';
$route['pr/approval/(:any)']     = 'pr_po/ApprovalPR_ctl/approval_single/$1';
$route['pr/approval/api/(:any)'] = 'pr_po/ApprovalPR_ctl/$1';

$route['pr/assign']            = 'pr_po/ApprovalPR_ctl/assign_pr';
$route['pr/assign/(:any)']     = 'pr_po/ApprovalPR_ctl/assign_single/$1';
$route['pr/assign/api/(:any)'] = 'pr_po/ApprovalPR_ctl/$1';

$route['purchase-order']               = 'pr_po/PO_ctl';
$route['purchase-order/create']        = 'pr_po/PO_ctl/create_po';
$route['purchase-order/upload-po']         = 'pr_po/PO_manual_ctl';
$route['purchase-order/upload-po-header']  = 'pr_po/PO_manual_ctl/upload_po_header';
$route['purchase-order/upload-po-boq']     = 'pr_po/PO_manual_ctl/upload_po_boq';

$route['purchase-order/(:any)']        = 'pr_po/PO_ctl/view_po/$1';
$route['purchase-order/create/(:any)'] = 'pr_po/PO_ctl/create_po/$1';
$route['purchase-order/edit/(:any)']   = 'pr_po/PO_ctl/edit_po/$1';
$route['purchase-order/api/(:any)']    = 'pr_po/PO_ctl/$1';
$route['purchase-order/api/(:any)/(:any)'] = 'pr_po/PO_ctl/$1/$2';

$route['po/drafting']            = 'pr_po/PO_ctl/create_po';
$route['po/drafting/(:any)']            = 'pr_po/PO_ctl/create_po/$1';
$route['po/approval']            = 'pr_po/ApprovalPO_ctl';
$route['po/approval/(:any)']     = 'pr_po/ApprovalPO_ctl/approval_single/$1';
$route['po/approval/api/(:any)'] = 'pr_po/ApprovalPO_ctl/$1';

// COA Review
$route['coa-review']                  = 'Coa_review_ctl';
$route['coa-review/pr/(:any)']        = 'Coa_review_ctl/review_pr/$1';
$route['coa-review/pr-edit/(:any)']   = 'Coa_review_ctl/review_pr_edit/$1';
$route['coa-review/fpjp/(:any)']      = 'Coa_review_ctl/review_fpjp/$1';
$route['coa-review/fpjp-edit/(:any)'] = 'Coa_review_ctl/review_fpjp_edit/$1';
$route['coa-review/api/(:any)']       = 'Coa_review_ctl/$1';

// FPJP NEW
$route['fpjp']        = 'fpjp_new/Fpjp_ctl';
$route['fpjp/create'] = 'fpjp_new/Fpjp_ctl/create_fpjp';

$route['fpjp/approval']            = 'fpjp_new/ApprovalFPJP_ctl';
$route['fpjp/approval/(:any)']     = 'fpjp_new/ApprovalFPJP_ctl/approval_single/$1';
$route['fpjp/approval/api/(:any)'] = 'fpjp_new/ApprovalFPJP_ctl/$1';

$route['fpjp/(:any)']      = 'fpjp_new/Fpjp_ctl/view_fpjp/$1';
$route['fpjp/edit/(:any)'] = 'fpjp_new/Fpjp_ctl/edit_fpjp/$1';
$route['fpjp/api/(:any)']  = 'fpjp_new/Fpjp_ctl/$1';
$route['fpjp/api/(:any)/(:any)'] = 'fpjp_new/Fpjp_ctl/$1/$2';

$route['fpjp/update-coa/(:any)']         = 'fpjp_new/ApprovalFPJP_ctl/update_coa/$1';
$route['fpjp/confirm-coa/(:any)']        = 'fpjp_new/ApprovalFPJP_ctl/confirm_coa/$1';

// General Receipt
$route['general-receipt']                   = 'gr/GR_ctl';
$route['general-receipt/create']            = 'gr/GR_ctl/create_gr';
$route['general-receipt/(:any)']            = 'gr/GR_ctl/view_gr/$1';
$route['general-receipt/edit/(:any)']       = 'gr/GR_ctl/edit_gr/$1';
$route['general-receipt/print/(:any)']      = 'gr/GR_ctl/printPDF/$1';
$route['general-receipt/api/(:any)']        = 'gr/GR_ctl/$1';
$route['general-receipt/api/(:any)/(:any)'] = 'gr/GR_ctl/$1/$2';

$route['gr/approval']            = 'gr/ApprovalGR_ctl';
$route['gr/approval/(:any)']     = 'gr/ApprovalGR_ctl/approval_single/$1';
$route['gr/approval/api/(:any)'] = 'gr/ApprovalGR_ctl/$1';
$route['gr/review']              = 'gr/ApprovalGR_ctl/review_gr';
$route['gr/review/(:any)']       = 'gr/ApprovalGR_ctl/review_single/$1';
// Budget History
$route['budget/budget-history'] = 'Budgethistory_ctl/budget_history_header';

// Feasibility Study
$route['feasibility-study']                        = 'feasibility_study/FS_ctl';
$route['feasibility-study/create']                 = 'feasibility_study/FS_ctl/new_fs';
$route['feasibility-study/api_save']               = 'feasibility_study/FS_ctl/save_fs';
$route['feasibility-study/api_load_header']        = 'feasibility_study/FS_ctl/load_data_fs';
$route['feasibility-study/(:any)']                 = 'feasibility_study/FS_ctl/view_fs/$1';
$route['feasibility-study/edit/(:any)']            = 'feasibility_study/FS_ctl/edit_fs/$1';
$route['feasibility-study/print/(:any)']           = 'feasibility_study/FS_ctl/printPDF/$1';
$route['feasibility-study/api/load_data_fs_lines'] = 'feasibility_study/FS_ctl/load_data_fs_lines';
$route['feasibility-study/api/delete']             = 'feasibility_study/FS_ctl/delete_fs';
$route['feasibility-study/api/update']             = 'feasibility_study/FS_ctl/save_fs_edit';
$route['feasibility-study/api/(:any)']             = 'feasibility_study/FS_ctl/$1';
$route['feasibility-study/detail/(:any)']          = 'feasibility_study/Approval_ctl/redirect_to_fs/$1';

// Justification
$route['justification']                        = 'feasibility_study/FS_ctl';
$route['justification/create']                 = 'feasibility_study/FS_ctl/new_fs';
$route['justification/api_save']               = 'feasibility_study/FS_ctl/save_fs';
$route['justification/api_load_header']        = 'feasibility_study/FS_ctl/load_data_fs';
$route['justification/(:any)']                 = 'feasibility_study/FS_ctl/view_fs/$1';
$route['justification/edit/(:any)']            = 'feasibility_study/FS_ctl/edit_fs/$1';
$route['justification/document/(:any)']        = 'feasibility_study/FS_ctl/get_attachment/$1';
$route['justification/api/load_data_fs_lines'] = 'feasibility_study/FS_ctl/load_data_fs_lines';
$route['justification/api/delete']             = 'feasibility_study/FS_ctl/delete_fs';
$route['justification/api/update']             = 'feasibility_study/FS_ctl/save_fs_edit';
$route['justification/api/(:any)']             = 'feasibility_study/FS_ctl/$1';
$route['justification/detail/(:any)']          = 'feasibility_study/Approval_ctl/redirect_to_fs/$1';


$route['budget/fs-cronjob']                        = 'CronJob_ctl/check_auto_reject';

$route['budget/approval']            = 'feasibility_study/Approval_ctl/approval_fs';
$route['budget/approval/(:any)']     = 'feasibility_study/Approval_ctl/approval_fs_single/$1';
$route['budget/approval/api/(:any)'] = 'feasibility_study/Approval_ctl/$1';

// API Budget
$route['api-budget/(:any)']                        = 'api/budget_api_ctl/$1';

// Upload
$route['uploadclearingbank/upload-clearing-bank'] = 'uploadclearingbank/upload_clearing_bank';
$route['uploadjournal/upload-journal']            = 'uploadjournal/upload_journal';

// Payment Batch
$route['payment-batch']             = 'payment/batch_payment_ctl';
$route['payment-batch/create']      = 'payment/batch_payment_ctl/new_pb';
$route['payment-batch/(:any)']      = 'payment/batch_payment_ctl/view_pb/$1';
$route['payment-batch/edit/(:any)'] = 'payment/batch_payment_ctl/edit_pb/$1';
$route['payment-batch/api/(:any)']  = 'payment/batch_payment_ctl/$1';

// Outstanding AP
$route['outstanding-ap']            = 'payment/Outstanding_ap_ctl';
$route['outstanding-ap/api/(:any)'] = 'payment/Outstanding_ap_ctl/$1';

$route['tbreport/report']                = 'tb_report/tbReport_ctl/show_tb';
$route['tbreport/trial_balance']         = 'tb_report/tbReport_ctl/show_trial';
$route['report/bswp']                    = 'report_xls/Bswp/show_bswp';
$route['report/pldetail']                = 'report_xls/PlDetail/show_pldetail';
$route['report/bs']                      = 'report_xls/Bs/show_bs';
$route['report/she']                     = 'report_xls/She/show_she';
$route['report/pl']                      = 'report_xls/Pl/show_pl';
$route['report/list-payment']            = 'report_xls/list_payment_ctl';
$route['report/list-unpayment']          = 'report_xls/list_payment_ctl/unpayment';
$route['report/list-payment/api/(:any)'] = 'report_xls/list_payment_ctl/$1';
$route['report/status_po'] 				 = 'report_xls/StatusPO_ctl/show_status_po';
$route['report/ap_appr_journal'] 		 = 'report_xls/AP_Approved_Journal/show_ap_approuved';
$route['report/expense'] 		 		 = 'report_xls/Expense/show_expense';
$route['report/pph23'] 		 		 	 = 'report_xls/Pph/show_pph_23';
$route['report/pph26'] 		 		 	 = 'report_xls/Pph/show_pph_26';
$route['report/pph42'] 		 		 	 = 'report_xls/Pph/show_pph_42';
$route['report/pphjk'] 		 		 	 = 'report_xls/Pph/show_pph_42jk';
$route['report/pphpp23'] 		 	 	 = 'report_xls/Pph/show_pph_42_pp23';
$route['report/ppn_masukan'] 		 	 = 'report_xls/Pph/show_ppn_masukan';
$route['report/wht'] 		 	 		 = 'report_xls/Wht_list/show_wht';
$route['report/accrued_po'] 		 	 = 'report_xls/Accrued_po/show_accrued_po';
$route['report/accrued_po/(:any)'] 	 	 = 'report_xls/Accrued_po/$1';
$route['report/appr_inv_tracker'] 		 = 'report_xls/Appr_inv/show_appr_tracker';

// Journal Manual
$route['journal-manual']            = 'journal/gl_manual_ctl';
$route['journal-manua/api/(:any)']  = 'journal/gl_manual_ctl/$1';

// New Route
$route['original-rkap']      = 'Rkap_ctl';
$route['budget-information'] = 'rkap/budget_info_ctl';

$route['api-enc'] = 'api/Encrypt/get_enc';
$route['api-dec'] = 'api/Encrypt/get_dec';

//Cron Job
$route['cron/check_auto_reject'] = 'CronJob_ctl/check_auto_reject';
$route['cron/history_budget']    = 'CronJob_ctl/history_budget';

// Download
$route['download/(:any)']     = 'Home/download/$1';

// $route['(.*)'] = "home/error_404";


// API

$route['api/fpjp']   = 'api/fpjp_rest';
$route['api-call/fpjp'] = 'api/Api_call';

$route['api/vendor'] = 'api/Api_vendor_ctl';
$route['api/major_category'] = 'api/Api_major_category_ctl';
$route['api/minor_category'] = 'api/Api_minor_category_ctl';
$route['api/region'] = 'api/Api_region_ctl';
$route['api/location'] = 'api/Api_location_ctl';
$route['api/contract_identification'] = 'api/Api_contract_identification_ctl';
$route['api/ownership'] = 'api/Api_ownership_ctl';
$route['api/project_ownership_unit'] = 'api/Api_project_ownership_unit_ctl';
$route['api/justifikasi'] = 'api/Api_justifikasi_ctl';

//API Master
$route['api/master_coa'] = 'api/Api_master_coa_ctl';



$route['api/po'] = 'api/Api_po_ctl';
$route['api/docs/vendor'] = 'api/Api_docs_ctl/vendor';
$route['api/gr'] = 'api/Api_gr_ctl';


$route['api/link//docs/vendor'] = 'api/Api_docs_ctl/vendor';
$route['api/link/print-po/(:any)']    = 'api/Free_access_ctl/print_po/$1';
$route['api/link/print-pr/(:any)']     = 'api/Free_access_ctl/print_pr/$1';
$route['api/link/print-fpjp/(:any)']   = 'api/Free_access_ctl/print_fpjp/$1';
$route['api/link/submit_fpjp']    = 'api/Free_access_ctl/submit_pending_fpjp_to_ap';
$route['api/link/submit_fpjp/(:any)']    = 'api/Free_access_ctl/submit_pending_fpjp_to_ap/$1';
$route['api/link/resend_email']        = 'api/Free_access_ctl/resend_email';
$route['api/link/resend_email/(:any)'] = 'api/Free_access_ctl/resend_email/$1';
//dpl
/*$route['dpl']              = 'dpl/Dpl_ctl';
$route['dpl/(:any)']       = 'dpl/Dpl_ctl/view_dpl/$1';
$route['dpl/create']       = 'dpl/Dpl_ctl/create_dpl';
$route['dpl/edit/(:any)']  = 'dpl/Dpl_ctl/edit_dpl/$1';
$route['dpl/cetak/(:any)'] = 'dpl/Dpl_ctl/printPDF/$1';*/


$route['dpl']              = 'dpl/Dpl_ctl';
$route['dpl/create']       = 'dpl/Dpl_ctl/create_dpl';
$route['dpl/form-create']  = 'dpl/Dpl_ctl/create_dpl_justif';

$route['dpl/verification']            = 'dpl/Approval_dpl_ctl/approval_dpl';
$route['dpl/verification/(:any)']     = 'dpl/Approval_dpl_ctl/verify_single/$1';
$route['dpl/verification/api/(:any)'] = 'dpl/Approval_dpl_ctl/$1';

$route['dpl/approval']            = 'dpl/Approval_dpl_ctl';
$route['dpl/approval/(:any)']     = 'dpl/Approval_dpl_ctl/approval_single/$1';
$route['dpl/approval/api/(:any)'] = 'dpl/Approval_dpl_ctl/$1';

$route['dpl/(:any)']       = 'dpl/Dpl_ctl/view_dpl/$1';
$route['dpl/edit/(:any)']  = 'dpl/Dpl_ctl/edit_dpl/$1';
$route['dpl/print/(:any)'] = 'dpl/Dpl_ctl/printPDF/$1';
$route['dpl/api/(:any)']   = 'dpl/Dpl_ctl/$1';

$route['test-email-acc'] = 'fpjp_new/ApprovalFPJP_ctl/test_email_accounting';


// CRON
$route['cron/fpjp-digipos']        = 'api/fpjp_digipos';


// Upload CSV Digipos
$route['upload-csv/settlement']        = 'BA_tools_ctl';
$route['upload-csv/settlement/(:any)'] = 'BA_tools_ctl/$1';
$route['upload-csv/nondigipos']        = 'BA_tools_ctl/non_digipos';
$route['upload-csv/nondigipos/(:any)'] = 'BA_tools_ctl/$1';


$route['report/print-fpjp/(:any)']   = 'api/Report/print_fpjp/$1';
