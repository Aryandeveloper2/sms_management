<?php

class ControllerExtensionModuleSmsSetting extends Controller {
	private $error = array(); 
	public function install() {
		
		$this->load->model('setting/setting');

        $this->model_setting_setting->editModuleVersion('sms_setting',  '3.2.0.0');
        
	}
	public function index() {   
		$this->load->language('extension/module/sms_setting');
																 
	    $this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
        $this->model_setting_setting->editModuleVersion('sms_setting',  '3.2.0.0');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('sms', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
			$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');		
		

		
        
		$data['user_token'] = $this->session->data['user_token'];


 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
 		if (isset($this->error['sms_number'])) {
			$data['sms_number'] = $this->error['sms_number'];
		} else {
			$data['sms_number'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/sms_setting', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/module/sms_setting', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL');

		

		if (isset($this->request->post['sms_from'])) {
			$data['sms_from'] = $this->request->post['sms_from'];
		} else {
			$data['sms_from'] = $this->config->get('sms_from');
		}
		if (isset($this->request->post['sms_sample'])) {
			$data['sms_sample'] = $this->request->post['sms_sample'];
		} else {
			$data['sms_sample'] = $this->config->get('sms_sample');
		}
	   
	   if (isset($this->request->post['sms_user'])) {
			$data['sms_user'] = $this->request->post['sms_user'];
		} else {
			$data['sms_user'] = $this->config->get('sms_user');
		}
		
		if (isset($this->request->post['sms_pass'])) {
			$data['sms_pass'] = $this->request->post['sms_pass'];
		} else {
			$data['sms_pass'] = $this->config->get('sms_pass');
		}
		
		if (isset($this->request->post['sms_smssignup'])) {
			$data['sms_smssignup'] = $this->request->post['sms_smssignup'];
		} else {
			$data['sms_smssignup'] = $this->config->get('sms_smssignup');
		}
		if (isset($this->request->post['sms_smssignup_txt'])) {
			$data['sms_smssignup_txt'] = $this->request->post['sms_smssignup_txt'];
		} else {
			$data['sms_smssignup_txt'] = $this->config->get('sms_smssignup_txt');
		}
		if (isset($this->request->post['sms_smslogin'])) {
			$data['sms_smslogin'] = $this->request->post['sms_smslogin'];
		} else {
			$data['sms_smslogin'] = $this->config->get('sms_smslogin');
		}
		if (isset($this->request->post['sms_smslogin_txt'])) {
			$data['sms_smslogin_txt'] = $this->request->post['sms_smslogin_txt'];
		} else {
			$data['sms_smslogin_txt'] = $this->config->get('sms_smslogin_txt');
		}
		if (isset($this->request->post['sms_smslogout'])) {
			$data['sms_smslogout'] = $this->request->post['sms_smslogout'];
		} else {
			$data['sms_smslogout'] = $this->config->get('sms_smslogout');
		}
		if (isset($this->request->post['sms_smslogout_txt'])) {
			$data['sms_smslogout_txt'] = $this->request->post['sms_smslogout_txt'];
		} else {
			$data['sms_smslogout_txt'] = $this->config->get('sms_smslogout_txt');
		}
		
		if (isset($this->request->post['sms_smssignup_voice'])) {
			$data['sms_smssignup_voice'] = $this->request->post['sms_smssignup_voice'];
		} else {
			$data['sms_smssignup_voice'] = $this->config->get('sms_smssignup_voice');
		}
		
		if (isset($this->request->post['sms_login_voice'])) {
			$data['sms_login_voice'] = $this->request->post['sms_login_voice'];
		} else {
			$data['sms_login_voice'] = $this->config->get('sms_login_voice');
		}
		
		
		if (isset($this->request->post['sms_logout_voice'])) {
			$data['sms_logout_voice'] = $this->request->post['sms_logout_voice'];
		} else {
			$data['sms_logout_voice'] = $this->config->get('sms_logout_voice');
		}
		
		if (isset($this->request->post['sms_order_voice'])) {
			$data['sms_order_voice'] = $this->request->post['sms_order_voice'];
		} else {
			$data['sms_order_voice'] = $this->config->get('sms_order_voice');
		}

		if (isset($this->request->post['sms_smsplaced'])) {
			$data['sms_smsplaced'] = $this->request->post['sms_smsplaced'];
		} else {
			$data['sms_smsplaced'] = $this->config->get('sms_smsplaced');
		}
		if (isset($this->request->post['sms_smsplaced_txt'])) {
			$data['sms_smsplaced_txt'] = $this->request->post['sms_smsplaced_txt'];
		
		} else {
			
			$data['sms_smsplaced_txt'] = $this->config->get('sms_smsplaced_txt');
		}
		if (isset($this->request->post['sms_smsproccessed'])) {
			$data['sms_smsproccessed'] = $this->request->post['sms_smsproccessed'];
		
		} else {
			
			$data['sms_smsproccessed'] = $this->config->get('sms_smsproccessed');
		}
		if (isset($this->request->post['sms_smsproccessed_txt'])) {
			$data['sms_smsproccessed_txt'] = $this->request->post['sms_smsproccessed_txt'];
		
		} else {
			
			$data['sms_smsproccessed_txt'] = $this->config->get('sms_smsproccessed_txt');
		}
		if (isset($this->request->post['sms_smsplaced_txt'])) {
			$data['sms_smsplaced_txt'] = $this->request->post['sms_smsplaced_txt'];
		
		} else {
			
			$data['sms_smsplaced_txt'] = $this->config->get('sms_smsplaced_txt');
		}
		
		if (isset($this->request->post['sms_smsnewsignup'])) {
			$data['sms_smsnewsignup'] = $this->request->post['sms_smsnewsignup'];
		
		} else {
			
			$data['sms_smsnewsignup'] = $this->config->get('sms_smsnewsignup');
		}
		
		if (isset($this->request->post['sms_smsnewsignup_txt'])) {
			$data['sms_smsnewsignup_txt'] = $this->request->post['sms_smsnewsignup_txt'];
		
		} else {
			
			$data['sms_smsnewsignup_txt'] = $this->config->get('sms_smsnewsignup_txt');
		}
		
		if (isset($this->request->post['sms_smsneworder'])) {
			$data['sms_smsneworder'] = $this->request->post['sms_smsneworder'];
		
		} else {
			
			$data['sms_smsneworder'] = $this->config->get('sms_smsneworder');
		}
		
		if (isset($this->request->post['sms_smsneworder_txt'])) {
			$data['sms_smsneworder_txt'] = $this->request->post['sms_smsneworder_txt'];
		
		} else {
			
			$data['sms_smsneworder_txt'] = $this->config->get('sms_smsneworder_txt');
		}
		
			
		if (isset($this->request->post['sms_smsnewfish'])) {
			$data['sms_smsnewfish'] = $this->request->post['sms_smsnewfish'];
		
		} else {
			
			$data['sms_smsnewfish'] = $this->config->get('sms_smsnewfish');
		}
		
		if (isset($this->request->post['sms_smsnewfish_txt'])) {
			$data['sms_smsnewfish_txt'] = $this->request->post['sms_smsnewfish_txt'];
		
		} else {
			
			$data['sms_smsnewfish_txt'] = $this->config->get('sms_smsnewfish_txt');
		}
		
		if (isset($this->request->post['sms_smsadminlogin'])) {
			$data['sms_smsadminlogin'] = $this->request->post['sms_smsadminlogin'];
		
		} else {
			
			$data['sms_smsadminlogin'] = $this->config->get('sms_smsadminlogin');
		}
		
		if (isset($this->request->post['sms_smsadminlogin_txt'])) {
			$data['sms_smsadminlogin_txt'] = $this->request->post['sms_smsadminlogin_txt'];
		
		} else {
			
			$data['sms_smsadminlogin_txt'] = $this->config->get('sms_smsadminlogin_txt');
		}
		
		//////////////////////////////////////////////////pay_dasti////////////////////////
		
		if (isset($this->request->post['sms_smspayprice_txt'])) {
			$data['sms_smspayprice_txt'] = $this->request->post['sms_smspayprice_txt'];
		
		} else {
			
			$data['sms_smspayprice_txt'] = $this->config->get('sms_smspayprice_txt');
		}
		
		
		if (isset($this->request->post['sms_smspayprice_admin_txt'])) {
			$data['sms_smspayprice_admin_txt'] = $this->request->post['sms_smspayprice_admin_txt'];
		
		} else {
			
			$data['sms_smspayprice_admin_txt'] = $this->config->get('sms_smspayprice_admin_txt');
		}
		
		
		if (isset($this->request->post['sms_smspayprice'])) {
			$data['sms_smspayprice'] = $this->request->post['sms_smspayprice'];
		
		} else {
			
			$data['sms_smspayprice'] = $this->config->get('sms_smspayprice');
		}
		
		
		if (isset($this->request->post['sms_smspayprice_admin'])) {
			$data['sms_smspayprice_admin'] = $this->request->post['sms_smspayprice_admin'];
		
		} else {
			
			$data['sms_smspayprice_admin'] = $this->config->get('sms_smspayprice_admin');
		}
		
		if (isset($this->request->post['sms_shopnum'])) {
			$data['sms_shopnum'] = $this->request->post['sms_shopnum'];
		} else {
			$data['sms_shopnum'] = $this->config->get('sms_shopnum');
		}
		
	    if (isset($this->request->post['sms_status'])) {
			$data['sms_status'] = $this->request->post['sms_status'];
		} else {
			$data['sms_status'] = $this->config->get('sms_status');
		}

        if (isset($this->request->post['sms_samane_sms'])) {
			$data['sms_samane_sms'] = $this->request->post['sms_samane_sms'];
		} else {
			$data['sms_samane_sms'] = $this->config->get('sms_samane_sms');
		}

		$credit=1;

        $data['sms_credit'] = "";

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('extension/module/sms_setting', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/sms_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	public function testsms(){
	$json = array();
	
	$tel=$this->request->post['teltest'];
	$message=$this->request->post['message'];
	$type=$this->request->post['type'];
	if($type=="voice"){
	    $voice=1;
	}else{
	    $voice='';
	}

    $this->sms = new Sms();
	
    $result=$this->sms->send_sms($tel,$message,$this->config->get('sms_user'),$this->config->get('sms_pass'),$this->config->get('sms_samane_sms'),$this->config->get('sms_from'),$voice);
	
	$json['alert']=$result;
	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}