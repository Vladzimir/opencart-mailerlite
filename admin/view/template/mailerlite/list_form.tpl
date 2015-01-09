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

?>

<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <div id="htabs" class="htabs">
        <a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-add"><?php echo $tab_add; ?></a>
        <a href="#tab-active"><?php echo $tab_active; ?></a>
        <a href="#tab-unsubscribed"><?php echo $tab_unsubscribed; ?></a>
        <a href="#tab-bounced"><?php echo $tab_bounced; ?></a>
      </div>

      <div id="tab-add">
        <table id="active-manual-add" class="list">
          <thead>
            <tr>
              <td colspan="7" class="left"><?php echo $text_add_new_subscriber; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="left" width="200"><?php echo $entry_add_subscriber; ?></td>
              <td class="left">
                <select name="active_subscriber_new">
                  <?php foreach ($mailerlite_subscribers as $mailerlite_subscriber_group => $mailerlite_subscribers_data) { ?>
                    <optgroup label="<?php echo $mailerlite_subscriber_group; ?>">
                      <?php foreach ($mailerlite_subscribers_data as $mailerlite_subscriber) { ?>
                        <?php if ($mailerlite_subscriber['selected']) { ?>
                          <option value="<?php echo $mailerlite_subscriber['key']; ?>" selected="selected"><?php echo $mailerlite_subscriber['value']; ?></option>
                        <?php } else { ?>
                          <option value="<?php echo $mailerlite_subscriber['key']; ?>"><?php echo $mailerlite_subscriber['value']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </optgroup>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr class="customer-group-form">
              <td class="left"><?php echo $entry_select_customer_group; ?></td>
              <td class="left">
                <select name="active_subscriber_group">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr class="manual-form">
              <td class="left"><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][email]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_name; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][name]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_lastname; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][lastname]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_company; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][company]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_country; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][country]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_state; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][state]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_city; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][city]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_phone; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][phone]" value="" /></td>
            </tr>
            <tr class="manual-form">
              <td class="left"><?php echo $entry_zip; ?></td>
              <td class="left"><input type="text" name="active_subscriber[new][CustomFields][zip]" value="" /></td>
            </tr>
          </tbody>
          <tfoot>
          <tr>
            <td class="left" colspan="2">
              <a onclick="updateSubscribers($('select[name=active_subscriber_new]').val(), false);" class="button"><?php echo $button_add_subscriber; ?></a>
              <a onclick="updateSubscribers($('select[name=active_subscriber_new]').val(), true);" class="button"><?php echo $button_update_subscriber; ?></a>
            </td>
          </tr>
          </tfoot>
        </table>
      </div>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_title; ?></td>
              <td><input type="text" name="name" value="<?php echo $name; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_new_customer; ?></td>
              <td>
                <select name="mailerlite_customer">
                  <?php foreach ($mailerlite_settings as $key => $value) { ?>
                    <?php if ($mailerlite_customer == $key) { ?>
                      <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_new_affiliate; ?></td>
              <td>
                <?php if ($mailerlite_affiliate) { ?>
                  <input type="checkbox" name="mailerlite_affiliate" value="1" checked="checked" />
                <?php } else { ?>
                  <input type="checkbox" name="mailerlite_affiliate" value="1" />
                <?php } ?>
              </td>
            </tr>
          </table>
        </div>
        <div id="tab-active">
          <table id="active" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_name; ?></td>
                <td class="left"><?php echo $column_email; ?></td>
                <td class="left"><?php echo $column_date; ?></td>
                <td class="center"><?php echo $column_sent; ?></td>
                <td class="center"><?php echo $column_opened; ?></td>
                <td class="center"><?php echo $column_clicked; ?></td>
                <td class="left"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
            <?php $active_row = 0; ?>
            <?php foreach ($active as $active_subscriber) { ?>
              <tr id="active-row-<?php echo $active_row; ?>">
                <td class="left">
                  <?php echo $active_subscriber['name']; ?>
                </td>
                <td class="left">
                  <?php echo $active_subscriber['email']; ?>
                </td>
                <td class="left">
                  <?php echo $active_subscriber['date']; ?>
                </td>
                <td class="center">
                  <?php echo $active_subscriber['sent']; ?>
                </td>
                <td class="center">
                  <?php echo $active_subscriber['opened']; ?>
                </td>
                <td class="center">
                  <?php echo $active_subscriber['clicked']; ?>
                </td>
                <td class="left">
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][email]" value="<?php echo $active_subscriber['email']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][name]" value="<?php echo $active_subscriber['name']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][lastname]" value="<?php echo $active_subscriber['CustomFields']['lastname']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][company]" value="<?php echo $active_subscriber['CustomFields']['company']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][country]" value="<?php echo $active_subscriber['CustomFields']['country']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][state]" value="<?php echo $active_subscriber['CustomFields']['state']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][city]" value="<?php echo $active_subscriber['CustomFields']['city']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][phone]" value="<?php echo $active_subscriber['CustomFields']['phone']; ?>" />
                  <input type="hidden" name="active_subscriber[<?php echo $active_row; ?>][CustomFields][zip]" value="<?php echo $active_subscriber['CustomFields']['zip']; ?>" />

                  <div id="active-actions-<?php echo $active_row; ?>">
                    <a onclick="removeActiveSubscriber('<?php echo $active_row; ?>');" class="button"><?php echo $button_remove_subscriber; ?></a>
                    <a onclick="unSubscribeSubscriber('<?php echo $active_row; ?>');" class="button"><?php echo $button_unsubscribe_subscriber; ?></a>
                  </div>
                </td>
              </tr>
            <?php $active_row++; ?>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="tab-unsubscribed">
          <table id="unsubscribed" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_name; ?></td>
                <td class="left"><?php echo $column_email; ?></td>
                <td class="left"><?php echo $column_date; ?></td>
                <td class="center"><?php echo $column_sent; ?></td>
                <td class="center"><?php echo $column_opened; ?></td>
                <td class="center"><?php echo $column_clicked; ?></td>
                <td class="left"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
            <?php $unsubscribed_row = 0; ?>
            <?php foreach ($unsubscribed as $unsubscribed_subscriber) { ?>
              <tr id="unsubscribed-row-<?php echo $unsubscribed_row; ?>">
                <td class="left">
                  <?php echo $unsubscribed_subscriber['name']; ?>
                </td>
                <td class="left">
                  <?php echo $unsubscribed_subscriber['email']; ?>
                </td>
                <td class="left">
                  <?php echo $unsubscribed_subscriber['date']; ?>
                </td>
                <td class="center">
                  <?php echo $unsubscribed_subscriber['sent']; ?>
                </td>
                <td class="center">
                  <?php echo $unsubscribed_subscriber['opened']; ?>
                </td>
                <td class="center">
                  <?php echo $unsubscribed_subscriber['clicked']; ?>
                </td>
                <td class="left">
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][email]" value="<?php echo $unsubscribed_subscriber['email']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][name]" value="<?php echo $unsubscribed_subscriber['name']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][lastname]" value="<?php echo $unsubscribed_subscriber['CustomFields']['lastname']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][company]" value="<?php echo $unsubscribed_subscriber['CustomFields']['company']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][country]" value="<?php echo $unsubscribed_subscriber['CustomFields']['country']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][state]" value="<?php echo $unsubscribed_subscriber['CustomFields']['state']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][city]" value="<?php echo $unsubscribed_subscriber['CustomFields']['city']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][phone]" value="<?php echo $unsubscribed_subscriber['CustomFields']['phone']; ?>" />
                  <input type="hidden" name="unsubscribed_subscriber[<?php echo $unsubscribed_row; ?>][CustomFields][zip]" value="<?php echo $unsubscribed_subscriber['CustomFields']['zip']; ?>" />

                  <div id="unsubscribed-actions-<?php echo $unsubscribed_row; ?>">
                    <a onclick="removeUnSubscribedSubscriber('<?php echo $unsubscribed_row; ?>');" class="button"><?php echo $button_remove_subscriber; ?></a>
                    <a onclick="subscribeUnSubscriber('<?php echo $unsubscribed_row; ?>');" class="button"><?php echo $button_subscribe_subscriber; ?></a>
                  </div>
                </td>
              </tr>
            <?php $unsubscribed_row++; ?>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="tab-bounced">
          <table id="bounced" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_name; ?></td>
                <td class="left"><?php echo $column_email; ?></td>
                <td class="left"><?php echo $column_date; ?></td>
                <td class="center"><?php echo $column_sent; ?></td>
                <td class="center"><?php echo $column_opened; ?></td>
                <td class="center"><?php echo $column_clicked; ?></td>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($bounced as $bounced_subscriber) { ?>
              <tr>
                <td class="left">
                  <?php echo $bounced_subscriber['name']; ?>
                </td>
                <td class="left">
                  <?php echo $bounced_subscriber['email']; ?>
                </td>
                <td class="left">
                  <?php echo $bounced_subscriber['date']; ?>
                </td>
                <td class="center">
                  <?php echo $bounced_subscriber['sent']; ?>
                </td>
                <td class="center">
                  <?php echo $bounced_subscriber['opened']; ?>
                </td>
                <td class="center">
                  <?php echo $bounced_subscriber['clicked']; ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

  var active_row = <?php echo $active_row; ?>;
  var unsubscribe_row = <?php echo $unsubscribed_row; ?>;

  function rowCounter() {
    // Clear
    $('#active-count, #unsubscribed-count, #bounced-count').remove();

    // Set actual values
    $('a[href=\#tab-active]').append(' <span id="active-count">(' + $('#active tbody tr').length + ')</span>');
    $('a[href=\#tab-unsubscribed]').append(' <span id="unsubscribed-count">(' + $('#unsubscribed tbody tr').length + ')</span>');
    $('a[href=\#tab-bounced]').append(' <span id="bounced-count">(' + $('#bounced tbody tr').length + ')</span>');
  }

  function getActiveField(row, name, email, lastname, company, country, state, city, phone, zip) {
    html = '<tr id="active-row-' + row + '">';
    html += '<td class="left">' + name + '</td>';
    html += '<td class="left">' + email + '</td>';
    html += '<td class="left"><?php echo date('Y-m-d'); ?></td>';
    html += '<td class="center">0</td>';
    html += '<td class="center">0</td>';
    html += '<td class="center">0</td>';
    html += '<td class="left">';

    html += '  <input type="hidden" name="active_subscriber[' + row + '][email]" value="' + email + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][name]" value="' + name + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][lastname]" value="' + lastname + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][company]" value="' + company + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][country]" value="' + country + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][state]" value="' + state + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][city]" value="' + city + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][phone]" value="' + phone + '" />';
    html += '  <input type="hidden" name="active_subscriber[' + row + '][CustomFields][zip]" value="' + zip + '" />';

    html += '  <div id="active-actions-' + row + '">';
    html += '  <a onclick="removeActiveSubscriber(' + row + ');" class="button"><?php echo $button_remove_subscriber; ?></a>';
    html += '  <a onclick="unSubscribeSubscriber(' + row + ');" class="button"><?php echo $button_unsubscribe_subscriber; ?></a>';
    html += '  </div>';

    html += '  </td>';
    html += '</tr>';

    return html;
  }

  function getUnSubscribedField(row, name, email, lastname, company, country, state, city, phone, zip) {
    html = '<tr id="unsubscribed-row-' + row + '">';
    html += '<td class="left">' + name + '</td>';
    html += '<td class="left">' + email + '</td>';
    html += '<td class="left"><?php echo date('Y-m-d'); ?></td>';
    html += '<td class="center">0</td>';
    html += '<td class="center">0</td>';
    html += '<td class="center">0</td>';
    html += '<td class="left">';

    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][email]" value="' + email + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][name]" value="' + name + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][lastname]" value="' + lastname + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][company]" value="' + company + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][country]" value="' + country + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][state]" value="' + state + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][city]" value="' + city + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][phone]" value="' + phone + '" />';
    html += '  <input type="hidden" name="unsubscribed_subscriber[' + row + '][CustomFields][zip]" value="' + zip + '" />';

    html += '  <div id="unsubscribed-actions-' + row + '">';
    html += '  <a onclick="removeUnSubscribedSubscriber(' + row + ');" class="button"><?php echo $button_remove_subscriber; ?></a>';
    html += '  <a onclick="subscribeUnSubscriber(' + row + ');" class="button"><?php echo $button_subscribe_subscriber; ?></a>';
    html += '  </div>';

    html += '  </td>';
    html += '</tr>';

    return html;
  }

function removeActiveSubscriber(row) {
  $('#active-row-' + row).remove();
  rowCounter();
}

function removeUnSubscribedSubscriber(row) {
  $('#unsubscribed-row-' + row).remove();
  rowCounter();
}

function unSubscribeSubscriber(row) {

  unsubscribe_row++;

  $('#unsubscribed tbody').append(getUnSubscribedField(unsubscribe_row, $('input[name=active_subscriber\\[' + row + '\\]\\[name\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[email\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[lastname\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[company\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[country\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[state\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[city\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[phone\\]]').val(), $('input[name=active_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[zip\\]]').val()));
  $('#active-row-' + row +', #active-actions-' + row).remove();

  rowCounter();
}

function subscribeUnSubscriber(row) {

  active_row++;

  $('#active tbody').append(getActiveField(active_row, $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[name\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[email\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[lastname\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[company\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[country\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[state\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[city\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[phone\\]]').val(), $('input[name=unsubscribed_subscriber\\[' + row + '\\]\\[CustomFields\\]\\[zip\\]]').val()));
  $('#unsubscribed-row-' + row +', #unsubscribed-actions-' + row).remove();

  rowCounter();
}

function updateSubscribers(mode, update) {

  if (update) {
    $('#active tbody tr').remove();
  }

  var field_html = '';
  var new_rows = 0;

  var custom_url_params = '';
  $('.success').remove();

  // Manual Mode
  if (mode == 31) {
    if ($('input[name=active_subscriber\\[new\\]\\[email\\]]').val() == '') {
      alert('<?php echo $error_email_required; ?>');
    } else {
      active_row++;
      new_rows++;
      field_html += getActiveField(active_row, $('input[name=active_subscriber\\[new\\]\\[name\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[email\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[lastname\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[company\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[country\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[state\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[city\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[phone\\]]').val(), $('input[name=active_subscriber\\[new\\]\\[CustomFields\\]\\[zip\\]]').val());

      $('#active-manual-add input').val('');
      $('#active tbody').append(field_html);
      $('.breadcrumb').after('<div class="success"><?php echo $text_update_success; ?></div>');
      $('.success').delay(1500).fadeOut('slow');

      rowCounter();
    }

  // Get Customers Mode
  } else if (mode == 11 || mode == 12) {

    if (mode == 11) {
      custom_url_params = '&subscribed=true'
    }

    $.ajax({
      url: 'index.php?route=mailerlite/list/getcustomers&token=<?php echo $token; ?>&customer_group_id=' + $('select[name=active_subscriber_group]').val() + custom_url_params,
      dataType: 'json',
      success: function(json) {
        $.map(json, function(item) {
          if (item['email'] != '') {
            active_row++;
            new_rows++;
            field_html += getActiveField(active_row, item['firstname'], item['email'], item['lastname'], item['company'], item['country'], item['state'], item['city'], item['telephone'], item['zip']);
          }
        });

        $('#active tbody').append(field_html);
        $('.breadcrumb').after('<div class="success"><?php echo $text_update_success; ?></div>');
        $('.success').delay(1500).fadeOut('slow');

        rowCounter();
      }
    });

  // Get Affiliate Mode
  } else if (mode == 21) {

    $.ajax({
      url: 'index.php?route=mailerlite/list/getaffiliates&token=<?php echo $token; ?>',
      dataType: 'json',
      success: function(json) {
        $.map(json, function(item) {
          if (item['email'] != '') {
            active_row++;
            new_rows++;
            field_html += getActiveField(active_row, item['firstname'], item['email'], item['lastname'], item['company'], item['country'], item['state'], item['city'], item['telephone'], item['zip']);
          }
        });

        $('#active tbody').append(field_html);
        $('.breadcrumb').after('<div class="success"><?php echo $text_update_success; ?></div>');
        $('.success').delay(1500).fadeOut('slow');

        rowCounter();
      }
    });
  }
}

$('.htabs a').tabs();

$('#active-manual-add tbody tr.customer-group-form').hide();

$('select[name=\'active_subscriber_new\']').bind('change', function() {
  if ($(this).val() == '31') {
    $('#active-manual-add tbody tr.manual-form').show();
  } else {
    $('#active-manual-add tbody tr.manual-form').hide();
  }

  if ($(this).val() == '11' || $(this).val() == '12' ) {
    $('#active-manual-add tbody tr.customer-group-form').show();
  } else {
    $('#active-manual-add tbody tr.customer-group-form').hide();
  }
});

rowCounter();

//--></script>
<?php echo $footer; ?>
