<?php
/*
Plugin Name: WooCommerce čeština
Donate link: https://www.paypal.com/donate/?hosted_button_id=WSYSW77FTMHQ2
Plugin URI: http://wpress.tech
Description: Přeloží WooCommerce a vybrané dodatkové pluginy do češtiny.
Version: 2.8.3
Author: WPressTech
Author URI: https://wpress.tech
Text Domain: woocomerce-cestina
Requires Plugins: woocommerce

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

if (!defined('WC_CZ_PLUGIN_URL')) {
    define('WC_CZ_PLUGIN_URL', plugin_dir_url(__FILE__));
}

require_once(plugin_dir_path(__FILE__) . 'core/plugins/woocommerce.php');
require_once(plugin_dir_path(__FILE__) . 'core/create-admin-menu.php');
require_once plugin_dir_path(__FILE__) . 'core/create-admin-submenu.php';
