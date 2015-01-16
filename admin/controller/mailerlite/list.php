<?php

/**
 * OpenCart Ukrainian Community
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License, Version 3
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 *
 * @category   OpenCart
 * @package    OCU MailerLite
 * @copyright  Copyright (c) 2011 Eugene Lifescale (a.k.a. Shaman) by OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html     GNU General Public License, Version 3
 * @version    $Id: catalog/model/shipping/ocu_ukrposhta.php 1.2 2015-01-09 17:00:21
 */
/**
 * @category   OpenCart
 * @package    OCU MailerLite
 * @copyright  Copyright (c) 2011 Eugene Lifescale (a.k.a. Shaman) by OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html     GNU General Public License, Version 3
 */

class ControllerMailerLiteList extends Controller {

    private $_error = array();
    private $_list;
    private $_subscribers;

    public function index() {
        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Lists.php');
        $this->_list = new ML_Lists($this->config->get('mailerlite_api_key'));

        $this->_getList();
    }

    public function insert() {

        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Lists.php');
        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Subscribers.php');

        $this->_subscribers = new ML_Subscribers($this->config->get('mailerlite_api_key'));
        $this->_list = new ML_Lists($this->config->get('mailerlite_api_key'));

        $this->load->language('mailerlite/list');
        $this->document->setTitle($this->language->get('heading_title'));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateForm()) {

            // Add new list
            $list_info = $this->_list->add(array('name' => $this->request->post['name']));
            $list_info = json_decode($list_info, true);

            if (isset($list_info['message'])) {
                $this->log->write('ML_Lists::getAll() HTTP Request Error: ' . $list_info['message']);

            } else if (isset($list_info['id'])) {

                // UnSubscribe
                if (isset($this->request->post['unsubscribed_subscriber'])) {
                    foreach ($this->request->post['unsubscribed_subscriber'] as $unsubscribed_subscriber) {
                        $response = $this->_subscribers->unsubscribe($unsubscribed_subscriber['email']);
                        $response = json_decode($response, true);

                        if (isset($response['message'])) {
                            $this->log->write('ML_Subscribers::unsubscribe() HTTP Request Error: ' . $response['message']);
                        }
                    }
                }

                // Subscribe Others
                if (isset($this->request->post['active_subscriber'])) {
                    $subscribers = array();

                    foreach ($this->request->post['active_subscriber'] as $key => $value) {

                        // Prepare CustomFields
                        $fields = array();
                        if (isset($value['CustomFields'])) {
                            foreach ($value['CustomFields'] as $field_key => $field_value) {
                                $fields[] = array('name' => $field_key, 'value' => $field_value);
                            }
                        }

                        $subscribers[] = array(
                            'email'  => $value['email'],
                            'name'   => $value['name'],
                            'fields' => $fields);
                    }

                    $response = $this->_subscribers->setId($list_info['id'])->addAll($subscribers, true);
                    $response = json_decode($response, true);

                    if (isset($response['message'])) {
                        $this->log->write('ML_Subscribers::addAll() HTTP Request Error: ' . $response['message']);
                    }
                }

                // Save Affiliate Settings
                $new_registration = array();

                $this->load->model('setting/setting');

                if ($this->config->get('mailerlite_affiliate_new_registration')) {
                    foreach ($this->config->get('mailerlite_affiliate_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$list_info['id']])) {
                    unset($new_registration[$list_info['id']]);
                }

                if (isset($this->request->post['mailerlite_affiliate'])) {
                    $new_registration[$list_info['id']] = $this->request->post['mailerlite_affiliate'];
                }

                $this->model_setting_setting->editSetting('mailerlite_affiliate', array('mailerlite_affiliate_new_registration' => $new_registration));

                // Save Customer Settings
                $new_registration = array();

                if ($this->config->get('mailerlite_customer_new_registration')) {
                    foreach ($this->config->get('mailerlite_customer_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$list_info['id']])) {
                    unset($new_registration[$list_info['id']]);
                }

                if ($this->request->post['mailerlite_customer']) {
                    $new_registration[$list_info['id']] = $this->request->post['mailerlite_customer'];
                }

                $this->model_setting_setting->editSetting('mailerlite_customer', array('mailerlite_customer_new_registration' => $new_registration));
            }

            $this->session->data['success'] = $this->language->get('text_success');


            if (isset($this->request->get['page'])) {
                $url = '&page=' . $this->request->get['page'];
            } else {
                $url = '';
            }

            $this->redirect($this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->_getForm();
    }

    public function update() {

        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Lists.php');
        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Subscribers.php');

        $this->_subscribers = new ML_Subscribers( $this->config->get('mailerlite_api_key') );
        $this->_list = new ML_Lists($this->config->get('mailerlite_api_key'));

        $this->load->language('mailerlite/list');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateForm()) {

            // Add new list
            $list_id = (int) $this->request->get['id'];
            $list_info = $this->_list->setId($list_id)->put(array('name' => $this->request->post['name']));
            $list_info = json_decode($list_info, true);

            if (isset($list_info['message'])) {
                $this->log->write('ML_Lists::put() HTTP Request Error: ' . $list_info['message']);

            } else {

                // Remove Deleted Subscribers
                $old_subscribers = array();
                $new_subscribers = array();

                $active       = json_decode($this->_list->setId($list_id)->getActive(), true);
                $unsubscribed = json_decode($this->_list->setId($list_id)->getUnsubscribed(), true);

                if (isset($active['Results']) && isset($unsubscribed['Results'])) {

                    foreach ($active['Results'] as $value) {
                        $old_subscribers[] = $value['email'];
                    }
                    foreach ($unsubscribed['Results'] as $value) {
                        $old_subscribers[] = $value['email'];
                    }

                    if (isset($this->request->post['active_subscriber'])) {
                        foreach ($this->request->post['active_subscriber'] as $value) {
                            $new_subscribers[] = $value['email'];
                        }
                    }

                    if (isset($this->request->post['unsubscribed_subscriber'])) {
                        foreach ($this->request->post['unsubscribed_subscriber'] as $value) {
                            $new_subscribers[] = $value['email'];
                        }
                    }

                    foreach ($old_subscribers as $value) {
                        if (!in_array($value, $new_subscribers)) {
                            $this->_subscribers->setId($list_id)->remove($value);
                        }
                    }
                }

                // UnSubscribe
                if (isset($this->request->post['unsubscribed_subscriber'])) {
                    foreach ($this->request->post['unsubscribed_subscriber'] as $unsubscribed_subscriber) {
                        $response = $this->_subscribers->unsubscribe($unsubscribed_subscriber['email']);
                        $response = json_decode($response, true);

                        if (isset($response['message'])) {
                            $this->log->write('ML_Subscribers::unsubscribe() HTTP Request Error: ' . $response['message']);
                        }
                    }
                }

                // Subscribe Others
                if (isset($this->request->post['active_subscriber'])) {
                    $subscribers = array();

                    foreach ($this->request->post['active_subscriber'] as $key => $value) {

                        // Prepare CustomFields
                        $fields = array();
                        if (isset($value['CustomFields'])) {
                            foreach ($value['CustomFields'] as $field_key => $field_value) {
                                $fields[] = array('name' => $field_key, 'value' => $field_value);
                            }
                        }

                        $subscribers[] = array(
                            'email'  => $value['email'],
                            'name'   => $value['name'],
                            'fields' => $fields);
                    }

                    $response = $this->_subscribers->setId($list_id)->addAll($subscribers, true);
                    $response = json_decode($response, true);

                    if (isset($response['message'])) {
                        $this->log->write('ML_Subscribers::addAll() HTTP Request Error: ' . $response['message']);
                    }
                }

                // Save Affiliate Settings
                $new_registration = array();

                $this->load->model('setting/setting');

                if ($this->config->get('mailerlite_affiliate_new_registration')) {
                    foreach ($this->config->get('mailerlite_affiliate_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$list_id])) {
                    unset($new_registration[$list_id]);
                }

                if (isset($this->request->post['mailerlite_affiliate'])) {
                    $new_registration[$list_id] = $this->request->post['mailerlite_affiliate'];
                }

                $this->model_setting_setting->editSetting('mailerlite_affiliate', array('mailerlite_affiliate_new_registration' => $new_registration));

                // Save Customer Settings
                $new_registration = array();

                if ($this->config->get('mailerlite_customer_new_registration')) {
                    foreach ($this->config->get('mailerlite_customer_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$list_id])) {
                    unset($new_registration[$list_id]);
                }

                if ($this->request->post['mailerlite_customer']) {
                    $new_registration[$list_id] = $this->request->post['mailerlite_customer'];
                }

                $this->model_setting_setting->editSetting('mailerlite_customer', array('mailerlite_customer_new_registration' => $new_registration));
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['page'])) {
                $url = '&page=' . $this->request->get['page'];
            } else {
                $url = '';
            }

            $this->redirect($this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->_getForm();
    }

    public function delete() {

        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Lists.php');
        $this->_list = new ML_Lists($this->config->get('mailerlite_api_key'));

        $this->load->language('mailerlite/list');
        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->post['selected']) && $this->_validateDelete()) {

            $this->load->model('setting/setting');

            foreach ($this->request->post['selected'] as $id) {

                // Remove list
                $response = $this->_list->setId($id)->remove();
                $response = json_decode($response, true);

                if (isset($response['message'])) {
                    $this->log->write('ML_Lists::getAll() HTTP Request Error: ' . $response['message']);
                }

                // Save Affiliate Settings
                $new_registration = array();

                if ($this->config->get('mailerlite_affiliate_new_registration')) {
                    foreach ($this->config->get('mailerlite_affiliate_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$id])) {
                    unset($new_registration[$id]);
                }

                $this->model_setting_setting->editSetting('mailerlite_affiliate', array('mailerlite_affiliate_new_registration' => $new_registration));

                // Save Customer Settings
                $new_registration = array();

                if ($this->config->get('mailerlite_customer_new_registration')) {
                    foreach ($this->config->get('mailerlite_customer_new_registration') as $key => $value) {
                        $new_registration[$key] = $value;
                    }
                }

                if (isset($new_registration[$id])) {
                    unset($new_registration[$id]);
                }

                $this->model_setting_setting->editSetting('mailerlite_customer', array('mailerlite_customer_new_registration' => $new_registration));



            }

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['page'])) {
                $url = '&page=' . $this->request->get['page'];
            } else {
                $url = '';
            }

            $this->redirect($this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->_getList();
    }

    private function _getList() {

        $this->load->language('mailerlite/list');
        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['page'])) {
            $url = '&page=' . $this->request->get['page'];
        } else {
            $url = false;
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_mailerlite'),
            'href'      => $this->url->link('module/mailerlite', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('mailerlite/list/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('mailerlite/list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['lists'] = array();

        $results = $this->_list->getAll(array('limit' => $this->config->get('config_admin_limit'), 'page' => $page));
        $lists   = json_decode($results, true);

        if (isset($lists['message'])) {
            $this->log->write('ML_Lists::getAll() HTTP Request Error: ' . $lists['message']);

        } else if (isset($lists['Results'])) {
            foreach ($lists['Results'] as $list) {
                $action = array();

                $action[] = array(
                    'text' => $this->language->get('text_edit'),
                    'href' => $this->url->link('mailerlite/list/update', 'token=' . $this->session->data['token'] . '&id=' . $list['id'] . $url, 'SSL')
                );

                $this->data['lists'][] = array(
                    'id'           => $list['id'],
                    'name'         => $list['name'],
                    'date'         => isset($list['date']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($list['date'])) : '-',
                    'updated'      => isset($list['updated']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($list['updated'])) : '-',
                    'total'        => $list['total'],
                    'unsubscribed' => $list['unsubscribed'],
                    'bounced'      => $list['bounced'],
                    'selected'     => isset($this->request->post['selected']) && in_array($list['id'], $this->request->post['selected']),
                    'action'       => $action
                );
            }
        }

        $this->data['heading_title']       = $this->language->get('heading_title');

        $this->data['text_no_results']     = $this->language->get('text_no_results');

        $this->data['column_name']         = $this->language->get('column_title');
        $this->data['column_date']         = $this->language->get('column_date');
        $this->data['column_updated']      = $this->language->get('column_updated');
        $this->data['column_total']        = $this->language->get('column_total');
        $this->data['column_unsubscribed'] = $this->language->get('column_unsubscribed');
        $this->data['column_bounced']      = $this->language->get('column_bounced');
        $this->data['column_action']       = $this->language->get('column_action');

        $this->data['button_insert']       = $this->language->get('button_insert');
        $this->data['button_delete']       = $this->language->get('button_delete');

        if (isset($this->_error['warning'])) {
            $this->data['error_warning'] = $this->_error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $pagination = new Pagination();
        $pagination->total = $lists['RecordsOnPage'];
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'mailerlite/list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function _getForm() {

        $this->data['heading_title']            = $this->language->get('heading_title_edit');

        $this->data['entry_email']              = $this->language->get('entry_email');
        $this->data['entry_name']               = $this->language->get('entry_name');
        $this->data['entry_lastname']           = $this->language->get('entry_lastname');
        $this->data['entry_company']            = $this->language->get('entry_company');
        $this->data['entry_country']            = $this->language->get('entry_country');
        $this->data['entry_state']              = $this->language->get('entry_state');
        $this->data['entry_city']               = $this->language->get('entry_city');
        $this->data['entry_phone']              = $this->language->get('entry_phone');
        $this->data['entry_zip']                = $this->language->get('entry_zip');
        $this->data['entry_name']               = $this->language->get('entry_name');
        $this->data['entry_title']              = $this->language->get('entry_title');
        $this->data['entry_select_customer_group'] = $this->language->get('entry_select_customer_group');

        $this->data['entry_new_customer']       = $this->language->get('entry_new_customer');
        $this->data['entry_new_affiliate']      = $this->language->get('entry_new_affiliate');
        $this->data['entry_add_subscriber']     = $this->language->get('entry_add_subscriber');

        $this->data['column_action']            = $this->language->get('column_action');
        $this->data['column_email']             = $this->language->get('column_email');
        $this->data['column_sent']              = $this->language->get('column_sent');
        $this->data['column_opened']            = $this->language->get('column_opened');
        $this->data['column_clicked']           = $this->language->get('column_clicked');
        $this->data['column_date']              = $this->language->get('column_date');
        $this->data['column_name']              = $this->language->get('column_name');

        $this->data['button_save']              = $this->language->get('button_save');
        $this->data['button_cancel']            = $this->language->get('button_cancel');
        $this->data['button_add_subscriber']    = $this->language->get('button_add_subscriber');
        $this->data['button_remove_subscriber'] = $this->language->get('button_remove_subscriber');
        $this->data['button_update_subscriber'] = $this->language->get('button_update_subscriber');
        $this->data['button_unsubscribe_subscriber'] = $this->language->get('button_unsubscribe_subscriber');
        $this->data['button_subscribe_subscriber']   = $this->language->get('button_subscribe_subscriber');

        $this->data['text_add_new_subscriber']  = $this->language->get('text_add_new_subscriber');
        $this->data['text_update_success']      = $this->language->get('text_update_success');

        $this->data['tab_add']                  = $this->language->get('tab_add');
        $this->data['tab_general']              = $this->language->get('tab_general');
        $this->data['tab_active']               = $this->language->get('tab_active');
        $this->data['tab_unsubscribed']         = $this->language->get('tab_unsubscribed');
        $this->data['tab_bounced']              = $this->language->get('tab_bounced');

        $this->data['error_email_required']     = $this->language->get('error_email_required');

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->request->get['id'])) {
            $this->data['id'] = $this->request->get['id'];
        } else {
            $this->data['id'] = 0;
        }

        $this->data['name'] = false;
        $this->data['mailerlite_customer']  = 0;
        $this->data['mailerlite_affiliate'] = 0;

        if (isset($this->request->post['name'])) {
            $this->data['name']                     = $this->request->post['name'];
            $this->data['mailerlite_customer']  = $this->request->post['mailerlite_customer'];
            $this->data['mailerlite_affiliate'] = $this->request->post['mailerlite_affiliate'];
        } else if (isset($this->request->get['id'])) {
            $list_info = json_decode($this->_list->setId($this->request->get['id'])->get(), true);

            if (isset($list_info['message'])) {
                $this->data['error_warning'] = 'ML_Lists::get() HTTP Request Error: ' . $list_info['message'];
                $this->log->write($this->data['error_warning']);
            } else if (isset($list_info['name'])) {
                $this->data['name'] = $list_info['name'];
            }

            $this->data['mailerlite_affiliate'] = 0;
            $mailerlite_affiliate_new_registration = $this->config->get('mailerlite_affiliate_new_registration');

            if (isset($this->request->get['id']) && isset($mailerlite_affiliate_new_registration[$this->request->get['id']])) {
                $this->data['mailerlite_affiliate'] = (int) $mailerlite_affiliate_new_registration[$this->request->get['id']];
            }

            $this->data['mailerlite_customer'] = 0;
            $mailerlite_customer_new_registration = $this->config->get('mailerlite_customer_new_registration');

            if (isset($this->request->get['id']) && isset($mailerlite_customer_new_registration[$this->request->get['id']])) {
                $this->data['mailerlite_customer'] = (int) $mailerlite_customer_new_registration[$this->request->get['id']];
            }
        }

        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->data['mailerlite_settings'] = array(0 => $this->language->get('text_disabled'),
                                                   1 => $this->language->get('text_newsletter'),
                                                   2 => $this->language->get('text_all'));

        $this->data['mailerlite_subscribers'] = array(
                                                      $this->language->get('text_customer_subscriber')  => array(array('key' => 11, 'value' => $this->language->get('text_newsletter'), 'selected' => false),
                                                                                                                 array('key' => 12, 'value' => $this->language->get('text_all'), 'selected' => false)),
                                                      $this->language->get('text_affiliate_subscriber') => array(array('key' => 21, 'value' => $this->language->get('text_all'), 'selected' => false)),
                                                      $this->language->get('text_other_subscriber')     => array(array('key' => 31, 'value' => $this->language->get('text_manual'), 'selected' => true)));

        if (isset($this->_error['warning'])) {
            $this->data['error_warning'] = $this->_error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->request->get['page'])) {
            $url = '&page=' . $this->request->get['page'];
        } else {
            $url = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_mailerlite'),
            'href'      => $this->url->link('module/mailerlite', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['id'])) {
            $this->data['action'] = $this->url->link('mailerlite/list/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('mailerlite/list/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('mailerlite/list', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['active'] = array();
        $this->data['unsubscribed'] = array();
        $this->data['bounced'] = array();

        if (isset($this->request->get['id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {

            $active       = json_decode($this->_list->setId($this->request->get['id'])->getActive(), true);
            $unsubscribed = json_decode($this->_list->setId($this->request->get['id'])->getUnsubscribed(), true);
            $bounced      = json_decode($this->_list->setId($this->request->get['id'])->getBounced(), true);

            /* todo
            if (isset($active['message'])) {
                $this->data['error_warning'] = 'ML_Lists::getActive() HTTP Request Error: ' . $active['message'];
                $this->log->write($this->data['error_warning']);
            } else if (isset($active['Results'])) {

                foreach ($active['Results'] as $active_result) {

                    $active_result_fields = array(  'lastname' => '',
                                                    'company'  => '',
                                                    'country'  => '',
                                                    'state'    => '',
                                                    'city'     => '',
                                                    'phone'    => '',
                                                    'zip'      => '');

                    if (isset($active_result['CustomFields'])) {
                        foreach ($active_result['CustomFields'] as $custom_field) {
                            $active_result_fields[strtolower($custom_field['name'])] = $custom_field['value'];
                        }
                    }

                    $this->data['active'][] = array(  'email'   => $active_result['email'],
                                                      'name'    => $active_result['name'],
                                                      'date'    => date($this->language->get('date_format_short'), strtotime($active_result['date'])),
                                                      'sent'    => $active_result['sent'],
                                                      'opened'  => $active_result['opened'],
                                                      'clicked' => $active_result['clicked'],
                                                      'fields'  => $active_result_fields);
                }
            }
            */

            if (isset($active['message'])) {
                $this->data['error_warning'] = 'ML_Lists::getActive() HTTP Request Error: ' . $active['message'];
                $this->log->write($this->data['error_warning']);
            } else if (isset($active['Results'])) {
                $this->data['active'] = $active['Results'];
            }

            if (isset($unsubscribed['message'])) {
                $this->data['error_warning'] = 'ML_Lists::getUnsubscribed() HTTP Request Error: ' . $unsubscribed['message'];
                $this->log->write($this->data['error_warning']);
            } else if (isset($unsubscribed['Results'])) {
                $this->data['unsubscribed'] = $unsubscribed['Results'];
            }

            if (isset($bounced['message'])) {
                $this->data['error_warning'] = 'ML_Lists::getBounced() HTTP Request Error: ' . $bounced['message'];
                $this->log->write($this->data['error_warning']);
            } else if (isset($bounced['Results'])) {
                $this->data['bounced'] = $bounced['Results'];
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->data['active'] = $this->request->post['active_subscriber'];
            $this->data['unsubscribed'] = $this->request->post['unsubscribed_subscriber'];
            $this->data['bounced'] = $this->request->post['bounced_subscriber'];
        }

        $this->template = 'mailerlite/list_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function _validateForm() {

        if (!$this->user->hasPermission('modify', 'mailerlite/list')) {
            $this->_error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->_error['warning'] = $this->language->get('error_name');
        }

        if (!$this->_error) {
            return true;
        } else {
            return false;
        }
    }

    private function _validateDelete() {

        if (!$this->user->hasPermission('modify', 'mailerlite/list')) {
            $this->_error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->_error) {
            return true;
        } else {
            return false;
        }
    }

    public function getCustomers() {
        $json = array();

        $this->load->model('mailerlite/list');

        $data = array('filter_customer_group_id' => $this->request->get['customer_group_id']);

        if (isset($this->request->get['subscribed']) && isset($this->request->get['customer_group_id'])) {
            $data = array('filter_newsletter' => true,
                          'filter_customer_group_id' => $this->request->get['customer_group_id']);
        }

        $results = $this->model_mailerlite_list->getCustomers($data);

        foreach ($results as $result) {

            $address_info = $this->model_mailerlite_list->getAddress($result['address_id']);

            if ($address_info) {
                $json[] = array(
                    'firstname'      => $result['firstname'],
                    'lastname'       => $result['lastname'],
                    'email'          => $result['email'],
                    'telephone'      => $result['telephone'],
                    'company'        => $address_info['company'],
                    'state'          => $address_info['zone'],
                    'country'        => $address_info['country'],
                    'postcode'       => $address_info['postcode'],
                    'city'           => $address_info['city'],
                    'zip'            => $address_info['postcode'],
                );
            } else {
                $json[] = array(
                    'firstname'      => $result['firstname'],
                    'lastname'       => $result['lastname'],
                    'email'          => $result['email'],
                    'telephone'      => $result['telephone'],
                    'company'        => '',
                    'state'          => '',
                    'country'        => '',
                    'postcode'       => '',
                    'city'           => '',
                    'zip'            => '',
                );
            }

        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->setOutput(json_encode($json));
    }

    public function getAffiliates() {
        $json = array();

        $this->load->model('mailerlite/list');
        $results = $this->model_mailerlite_list->getAffiliates();

        foreach ($results as $result) {

                $json[] = array(
                    'firstname'      => $result['firstname'],
                    'lastname'       => $result['lastname'],
                    'email'          => $result['email'],
                    'telephone'      => $result['telephone'],
                    'company'        => $result['company'],
                    'state'          => $this->model_mailerlite_list->getCountry($result['country_id']),
                    'country'        => $this->model_mailerlite_list->getZone($result['zone_id']),
                    'postcode'       => $result['postcode'],
                    'city'           => $result['city'],
                    'zip'            => $result['postcode'],
                );


        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->setOutput(json_encode($json));
    }
}

