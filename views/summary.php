<?php

/**
 * Process viewer summary.
 *
 * @category   apps
 * @package    process-viewer
 * @subpackage views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
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
// Headers
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('process_viewer_id'),
    lang('process_viewer_owner'),
    lang('process_viewer_running'),
    lang('process_viewer_cpu'),
    lang('process_viewer_memory'),
    lang('process_viewer_size'),
    lang('process_viewer_command'),
);

///////////////////////////////////////////////////////////////////////////////
// Anchors 
///////////////////////////////////////////////////////////////////////////////

if ($form_type == MY_Page::TYPE_CONFIGURATION)
    $anchors = array(
        anchor_custom('/app/process_viewer/index/' . MY_Page::TYPE_WIDE_CONFIGURATION, lang('base_detailed_view'))
    );
else
    $anchors = array(
        anchor_custom('/app/process_viewer', lang('base_default'))
    );

///////////////////////////////////////////////////////////////////////////////
// Items
///////////////////////////////////////////////////////////////////////////////

array_shift($processes);

foreach ($processes as $raw_data) {

    $data = preg_split('/\s+/', trim($raw_data));

    $item['title'] = $data[0] . " / " . $data[7];
    $item['action'] = $action;
    $item['anchors'] = anchor_custom('/app/process_viewer/kill/' . $data[0], 'Kill');
    $item['details'] = array(
        $data[0],
        $data[1],
        $data[2],
        $data[3],
        $data[4],
        $data[5],
        $data[7],
    );

    $items[] = $item;
}

sort($items);

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$options = array(
    'id' => 'process_view_summary',
    'default_rows' => 500,
    'responsive' => ($form_type == MY_Page::TYPE_CONFIGURATION ? array(0 => 'none', 1 => 'none', 5 => 'none') : NULL)
);

echo summary_table(
    lang('process_viewer_processes'),
    $anchors,
    $headers,
    $items,
    $options
);
