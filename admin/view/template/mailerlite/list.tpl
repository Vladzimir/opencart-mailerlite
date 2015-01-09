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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
              </td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="center"><?php echo $column_date; ?></td>
              <td class="center"><?php echo $column_updated; ?></td>
              <td class="center"><?php echo $column_total; ?></td>
              <td class="center"><?php echo $column_unsubscribed; ?></td>
              <td class="center"><?php echo $column_bounced; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($lists) { ?>
              <?php foreach ($lists as $list) { ?>
                <tr>
                  <td style="text-align: center;"><?php if ($list['selected']) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $list['id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $list['id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="left"><?php echo $list['name']; ?></td>
                  <td class="center"><?php echo $list['date']; ?></td>
                  <td class="center"><?php echo $list['updated']; ?></td>
                  <td class="center"><?php echo $list['total']; ?></td>
                  <td class="center"><?php echo $list['unsubscribed']; ?></td>
                  <td class="center"><?php echo $list['bounced']; ?></td>
                  <td class="right">
                    <?php foreach ($list['action'] as $action) { ?>
                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
