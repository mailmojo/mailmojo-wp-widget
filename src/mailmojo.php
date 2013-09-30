<?php
/*
Plugin Name: MailMojo Widget
Plugin URI: http://github.com/eliksir/MailMojo-WP-Widget
Description: Adds a signup widget for a MailMojo mailing list to your WordPress site.
Author: Eliksir AS
Author URI: http://e5r.no
Version: 0.3
*/

/*
 * Copyright 2011 Eliksir AS <http://e5r.no>
 * License: GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
include('mailmojo-plugin.php');
include('mailmojo-widget.php');
include('lib/MailMojoApi.php');

// Inits the plugin and widget.
$mm = MailMojoPlugin::getInstance();

// Register hooks for the plugin.
register_activation_hook(__FILE__, array('MailMojoPlugin', 'setUpOptions'));
register_uninstall_hook( __FILE__, array('MailMojoPlugin', 'removeOptions'));
