<?php

/**
 * Process viewer CPU summary.
 *
 * @category   apps
 * @package    process-viewer
 * @subpackage views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2015 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/process_viewer/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('process_viewer');

///////////////////////////////////////////////////////////////////////////////
// Chart
///////////////////////////////////////////////////////////////////////////////

$options['action'] = button_set(
    array(anchor_custom('/app/process_viewer', lang('process_viewer_app_name')))
);

echo chart_container(lang('process_viewer_cpu_usage'), 'process_viewer_cpu_usage', $options);
