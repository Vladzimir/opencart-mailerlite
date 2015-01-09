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

class ControllerMailerLiteCampaign extends Controller {

    private $_campaigns;

    public function index() {
        require_once(DIR_SYSTEM . 'library/mailerlite/ML_Campaigns.php');
        $this->_campaigns = new ML_Campaigns($this->config->get('mailerlite_api_key'));

        $this->load->language('mailerlite/campaign');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    private function getList() {

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
            'href'      => $this->url->link('mailerlite/campaign', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['campaigns'] = array();

        $results   = $this->_campaigns->getAll(array('limit' => $this->config->get('config_admin_limit'), 'page' => $page));
        $campaigns = json_decode($results, true);

        if (isset($campaigns['message'])) {
            $this->log->write('ML_Campaigns::getAll() HTTP Request Error: ' . $campaigns['message']);

        } else if (isset($campaigns['Results'])) {
            foreach ($campaigns['Results'] as $campaign) {
                $this->data['campaigns'][] = array(
                    'id'           => $campaign['id'],
                    'name'         => isset($campaign['name']) ? $campaign['name'] : false,
                    'started'      => isset($campaign['started']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($campaign['started'])) : '-',
                    'updated'      => isset($campaign['updated']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($campaign['updated'])) : '-',
                    'total'        => $campaign['total'],
                    'opens'        => $campaign['opens'],
                    'unique_opens' => $campaign['uniqueOpens'],
                    'clicks'       => $campaign['clicks'],
                    'unsubscribes' => $campaign['unsubscribes'],
                    'bounces'      => $campaign['bounces'],
                    'junk'         => $campaign['junk']
                );
            }
        }

        $this->data['heading_title']       = $this->language->get('heading_title');

        $this->data['text_no_results']     = $this->language->get('text_no_results');

        $this->data['column_name']         = $this->language->get('column_name');
        $this->data['column_started']      = $this->language->get('column_started');
        $this->data['column_updated']      = $this->language->get('column_updated');
        $this->data['column_total']        = $this->language->get('column_total');
        $this->data['column_opens']        = $this->language->get('column_opens');
        $this->data['column_unique_opens'] = $this->language->get('column_unique_opens');
        $this->data['column_clicks']       = $this->language->get('column_clicks');
        $this->data['column_unsubscribes'] = $this->language->get('column_unsubscribes');
        $this->data['column_bounces']      = $this->language->get('column_bounces');
        $this->data['column_junk']         = $this->language->get('column_junk');

        $pagination = new Pagination();
        $pagination->total = $campaigns['RecordsOnPage'];
        $pagination->page  = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->url->link('mailerlite/campaign', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'mailerlite/campaign.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
}

