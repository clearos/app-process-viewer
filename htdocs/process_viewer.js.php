<?php

/**
 * Process viewer ajax helper.
 *
 * @category   apps
 * @package    process-viewer
 * @subpackage javascript
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('process_viewer');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type:application/x-javascript');
?>

$(document).ready(function() {

    // Translations
    //-------------

    lang_cpu_usage = '<?php echo lang("process_viewer_cpu_usage"); ?>';
    lang_process = '<?php echo lang("process_viewer_process"); ?>';

    // Main
    //-----

    if ($('#process_viewer_cpu_usage').length != 0) 
        generate_process_viewer_cpu_usage();
});

/**
 * Ajax call for dashboard report.
 */

function generate_process_viewer_cpu_usage() {
    $.ajax({
        url: '/app/process_viewer/process_viewer_dashboard/get_data',
        method: 'GET',
        dataType: 'json',
        success : function(data) {
            create_process_viewer_cpu_usage_chart(data);
            window.setTimeout(generate_process_viewer_cpu_usage, 3000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            window.setTimeout(generate_process_viewer_cpu_usage, 10000);
        }
    });
}

/**
 * Generates dashboard report.
 */

function create_process_viewer_cpu_usage_chart(data) {

    data_titles = Array(lang_process, lang_cpu_usage);
    data_types = Array('string', 'int');

    options = Array();
    options.series_threshold = 8;
    options.series_sum_above_threshold = true;
    options.series_label_threshold = 0.05;

    clearos_chart(
        'process_viewer_cpu_usage',
        'pie',
        data,
        data_titles,
        data_types,
        Array(),
        options
    )
}

// vim: ts=4 syntax=javascript
