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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="center"><?php echo $column_started; ?></td>
            <td class="center"><?php echo $column_updated; ?></td>
            <td class="center"><?php echo $column_total; ?></td>
            <td class="center"><?php echo $column_opens; ?></td>
            <td class="center"><?php echo $column_unique_opens; ?></td>
            <td class="center"><?php echo $column_clicks; ?></td>
            <td class="center"><?php echo $column_unsubscribes; ?></td>
            <td class="center"><?php echo $column_bounces; ?></td>
            <td class="center"><?php echo $column_junk; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($campaigns) { ?>
            <?php foreach ($campaigns as $campaign) { ?>
              <tr>
                <td class="left"><?php echo $campaign['name']; ?></td>
                <td class="center"><?php echo $campaign['started']; ?></td>
                <td class="center"><?php echo $campaign['updated']; ?></td>
                <td class="center"><?php echo $campaign['total']; ?></td>
                <td class="center"><?php echo $campaign['opens']; ?></td>
                <td class="center"><?php echo $campaign['unique_opens']; ?></td>
                <td class="center"><?php echo $campaign['clicks']; ?></td>
                <td class="center"><?php echo $campaign['unsubscribes']; ?></td>
                <td class="center"><?php echo $campaign['bounces']; ?></td>
                <td class="center"><?php echo $campaign['junk']; ?></td>
              </tr>
            <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
