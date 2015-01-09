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

class ModelMailerliteList extends Model {

    public function getCustomers($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id)";

        $implode = array();

        if (isset($data['filter_customer_group_id']) && !empty($data['filter_customer_group_id'])) {
            $implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = 1";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAffiliates($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "affiliate a";

        $implode = array();

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = 1";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCountry($country_id) {
        $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$country_id . "'");
        if ($country_query->num_rows) {
            return $country_query->row['name'];
        } else {
            return false;
        }
    }

    public function getZone($zone_id) {
        $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$zone_id . "'");
        if ($zone_query->num_rows) {
            return $zone_query->row['name'];
        } else {
            return false;
        }
    }

    public function getAddress($address_id) {
        $address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

        if ($address_query->num_rows) {
            return array(
                'company'        => $address_query->row['company'],
                'postcode'       => $address_query->row['postcode'],
                'city'           => $address_query->row['city'],
                'country'        => $this->getCountry($address_query->row['country_id']),
                'zone'           => $this->getZone($address_query->row['zone_id']),
            );
        } else {
            return false;
        }
    }
}

