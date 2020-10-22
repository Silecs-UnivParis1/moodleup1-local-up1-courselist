<?php
/**
 * @package    local
 * @subpackage up1_courselist
 * @copyright  2013-2016 Silecs {@link http://www.silecs.info/societe}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2020100300;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2020060900;        // Requires this Moodle version
$plugin->component = 'local_up1_courselist';       // Full name of the plugin (used for diagnostics)

$plugin->cron      = 0;
$plugin->maturity  = MATURITY_BETA;
$plugin->release   = 'TODO';

$plugin->dependencies = [
	'local_roftools' => 2020100300,
    'local_up1_metadata' => 2020100300
];
