<?php

/*
  Plugin Name: countdown365 - sales booster for WooCommerce 
  Plugin URI: https://countdown365.vuiphor.com/
  Description: Countdown365 is a plug-in that allows you to quickly and easily add a flipclock and it can be a game-changer for your woocommerce marketing strategy.
  Version: 1.1.5
  Author: vuiphor
  Author URI: https://vuiphor.com/
  Text Domain: countdown365
  Domain Path: /languages
  WC requires at least: 3.0
  WC tested up to: 6.0.0
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


/**
 * Copyright (c) YEAR weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COUNTDOWN365_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-countdown365-activator.php
 */
function activate_countdown365() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-countdown365-activator.php';
	Countdown365_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-countdown365-deactivator.php
 */
function deactivate_countdown365() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-countdown365-deactivator.php';
	Countdown365_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_countdown365' );
register_deactivation_hook( __FILE__, 'deactivate_countdown365' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-countdown365.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_countdown365() {

	$plugin = new Countdown365();
	$plugin->run();

}
run_countdown365();
