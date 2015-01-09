# opencart-mailerlite
MailerLite Gateway for OpenCart

OpenCart Ukrainian Support:
http://opencart-ukraine.tumblr.com/post/107599151381/mailerlite-module-for-opencart

INSTALLATION

1. Install the MailerLite Module
2. Activate & set APIkey
3. Patch next files:

open:

/admin/language/russian/common/header.php

find:

<?php

add after:

$_['text_mailerlite']                   = 'MailerLite';
$_['text_mailerlite_campaign']          = 'Кампании';
$_['text_mailerlite_list']              = 'Списки рассылок';
$_['text_mailerlite_settings']          = 'Настройки';

open:

/admin/view/template/common/header.tpl

find:

<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>

add after:

<?php if ($this->config->get('mailerlite_status')) { ?>
<li><a class="parent"><?php echo $this->language->get('text_mailerlite'); ?></a>
  <ul>
    <li><a href="<?php echo $this->url->link('mailerlite/list', 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $this->language->get('text_mailerlite_list'); ?></a></li>
    <li><a href="<?php echo $this->url->link('mailerlite/campaign', 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $this->language->get('text_mailerlite_campaign'); ?></a></li>
    <li><a href="<?php echo $this->url->link('module/mailerlite', 'token=' . $this->session->data['token'], 'SSL'); ?>"><?php echo $this->language->get('text_mailerlite_settings'); ?></a></li>
  </ul>
</li>
<?php } ?>

open:

/catalog/model/account/customer.php

find:

public function addCustomer($data) {

add after:

if ($this->config->get('mailerlite_status') && $this->config->get('mailerlite_customer_new_registration')) {

	require_once(DIR_SYSTEM . 'library/mailerlite/ML_Subscribers.php');
	$subscriber = new ML_Subscribers($this->config->get('mailerlite_api_key'));

	if ($this->config->get('mailerlite_customer_new_registration')) {
		foreach ($this->config->get('mailerlite_customer_new_registration') as $list_id => $value) {

			if (isset($data['email']) && $data['email']) {

				$subscriber_data = array(
					'email' => $data['email'],
					'name'  => $data['firstname'],
					'fields' => array(
						array('name' => 'lastname', 'value' => (isset($data['lastname']) ? $data['lastname'] : false)),
						array('name' => 'city', 'value' => (isset($data['city']) ? $data['city'] : false)),
						array('name' => 'company', 'value' => (isset($data['company']) ? $data['company'] : false)),
						array('name' => 'phone', 'value' => (isset($data['phone']) ? $data['telephone'] : false)),
						array('name' => 'zip', 'value' => (isset($data['postcode']) ? $data['postcode'] : false)),
					)
				);

				if ($value == 2 || ($value == 1 && $data['newsletter'])) {
					$response = $subscriber->setId($list_id)->add($subscriber_data, 1);
					$response = json_decode($response, true);

					if (isset($response['message'])) {
						$this->log->write('ML_Subscribers::add() HTTP Request Error: ' . $response['message']);
					}
				}
			}
		}
	}
}

open:

/catalog/model/affiliate/affiliate.php

find:

public function addAffiliate($data) {

add after:

if ($this->config->get('mailerlite_status') && $this->config->get('mailerlite_affiliate_new_registration')) {

	require_once(DIR_SYSTEM . 'library/mailerlite/ML_Subscribers.php');
	$subscriber = new ML_Subscribers($this->config->get('mailerlite_api_key'));

	if ($this->config->get('mailerlite_affiliate_new_registration')) {
		foreach ($this->config->get('mailerlite_affiliate_new_registration') as $list_id => $value) {

			if ($value == 1 && $data['email']) {
				$subscriber_data = array(
					'email' => $data['email'],
					'name' => $data['firstname'],
					'fields' => array(
						array('name' => 'lastname', 'value' => (isset($data['lastname']) ? $data['lastname'] : false)),
						array('name' => 'city', 'value' => (isset($data['city']) ? $data['city'] : false)),
						array('name' => 'company', 'value' => (isset($data['company']) ? $data['company'] : false)),
						array('name' => 'phone', 'value' => (isset($data['phone']) ? $data['telephone'] : false)),
						array('name' => 'zip', 'value' => (isset($data['postcode']) ? $data['postcode'] : false)),
					)
				);

				$response = $subscriber->setId($list_id)->add($subscriber_data, 1);
				$response = json_decode($response, true);

				if (isset($response['message'])) {
					$this->log->write('ML_Subscribers::add() HTTP Request Error: ' . $response['message']);
				}
			}
		}
	}
}


enjoy!
