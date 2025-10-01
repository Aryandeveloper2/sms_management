<?php
class ControllerExtensionModuleSmsSend extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('extension/module/sms_send');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['user_token'] = $this->session->data['user_token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('marketing/contact', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['cancel'] = $this->url->link('marketing/contact', 'user_token=' . $this->session->data['user_token'], true);

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $this->load->model('customer/customer_group');

        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/sms_send', $data));
    }
    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/sms_send')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['message']) {
            $this->error['message'] = $this->language->get('error_message');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
	public function send() {
	    $json = array();
        $this->load->language('extension/module/sms_send');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
                $this->load->model('setting/store');

                $store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

                if ($store_info) {
                    $store_name = $store_info['name'];
                } else {
                    $store_name = $this->config->get('config_name');
                }
                $telephones = array();

                $this->load->model('customer/customer');

                $this->load->model('customer/customer_group');

                $this->load->model('sale/order');
                if (isset($this->request->get['page'])) {
                    $page = $this->request->get['page'];
                } else {
                    $page = 1;
                }
                $telephone_total = 0;

                $telephones = array();

                switch ($this->request->post['to']) {
                    case 'newsletter':
                        $customer_data = array(
                            'filter_newsletter' => 1,
                            'start'             => ($page - 1) * 10,
                            'limit'             => 10
                        );

                        $telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

                        $results = $this->model_customer_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $telephones[] = $result['telephone'];
                        }
                        break;
                    case 'customer_all':
                        $customer_data = array(
                            'start' => ($page - 1) * 10,
                            'limit' => 10
                        );

                        $telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

                        $results = $this->model_customer_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $telephones[] = $result['telephone'];
                        }
                        break;
                    case 'customer_group':
                        $customer_data = array(
                            'filter_customer_group_id' => $this->request->post['customer_group_id'],
                            'start'                    => ($page - 1) * 10,
                            'limit'                    => 10
                        );

                        $telephone_total = $this->model_customer_customer->getTotalCustomers($customer_data);

                        $results = $this->model_customer_customer->getCustomers($customer_data);

                        foreach ($results as $result) {
                            $telephones[$result['customer_id']] = $result['telephone'];
                        }
                        break;
                    case 'customer':
                        if (!empty($this->request->post['customer'])) {
                            foreach ($this->request->post['customer'] as $customer_id) {
                                $customer_info = $this->model_customer_customer->getCustomer($customer_id);

                                if ($customer_info) {
                                    $telephones[] = $customer_info['telephone'];
                                }
                            }
                        }
                        break;
                    case 'affiliate_all':
                        $affiliate_data = array(
                            'filter_affiliate' => 1,
                            'start'            => ($page - 1) * 10,
                            'limit'            => 10
                        );

                        $telephone_total = $this->model_customer_customer->getTotalCustomers($affiliate_data);

                        $results = $this->model_customer_customer->getCustomers($affiliate_data);

                        foreach ($results as $result) {
                            $telephones[] = $result['telephone'];
                        }
                        break;
                    case 'affiliate':
                        if (!empty($this->request->post['affiliate'])) {
                            foreach ($this->request->post['affiliate'] as $affiliate_id) {
                                $affiliate_info = $this->model_customer_customer->getCustomer($affiliate_id);

                                if ($affiliate_info) {
                                    $telephones[] = $affiliate_info['telephone'];
                                }
                            }
                        }
                        break;
                    case 'product':
                        if (isset($this->request->post['product'])) {
                            $telephone_total = $this->model_sale_order->getTotalTelephonesByProductsOrdered($this->request->post['product']);

                            $results = $this->model_sale_order->getTelephonesByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

                            foreach ($results as $result) {
                                $telephones[] = $result['telephone'];
                            }
                        }
                        break;
                }
                $telephones = array_unique($telephones);
                if ($telephones) {
                    $json['success'] = $this->language->get('text_success');

                    $start = ($page - 1) * 10;
                    $end = $start + 10;

                    $json['success'] = sprintf($this->language->get('text_sent'), $start, $telephone_total);
                    $message =$this->request->post['message'];
                    foreach ($telephones as $telephone) {
                        $this->config->get('config_sms_user');
                        $this->sms = new Sms();
                        $this->sms->send_sms($telephone,$message,$this->config->get('sms_user'),$this->config->get('sms_pass'),$this->config->get('sms_samane_sms'),$this->config->get('sms_from'),$this->config->get('sms_sample'));
                    }
                }
                $json['success'] = $this->language->get('text_success');

                $this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
            }
	    }
	}
}