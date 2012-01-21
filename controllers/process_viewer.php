<?php

/**
 * Process viewer controller.
 *
 * @category   Apps
 * @package    Process_Viewer
 * @subpackage Controllers
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
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Process manager controller.
 *
 * @category   Apps
 * @package    Process_Viewer
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/process_viewer/
 */

class Process_Viewer extends ClearOS_Controller
{
    /**
     * Default controller.
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->load->library('process_viewer/Process_Manager');

        // Load view data
        //---------------

        try {
            $data['processes'] = $this->process_manager->get_raw_data();
        } catch (Engine_Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $options['type'] = 'report';

        $this->page->view_form('summary', $data, lang('process_viewer_app_name'), $options);
    }

    /**
     * Kills the given process.
     *
     * @param integer $pid process ID
     *
     * @return view
     */

    function destroy($pid)
    {
        // Load libraries
        //---------------

        $this->load->library('process_viewer/Process_Manager');

        // Handle form submit
        //-------------------

        try {
            $this->process_manager->kill($pid);
            $this->page->set_status_updated();
            redirect('/process_viewer');
        } catch (Engine_Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Kill a process view.
     *
     * @param int $pid process ID
     *
     * @return view
     */

    function kill($pid)
    {
        $this->lang->load('process_viewer');

        $confirm_uri = '/app/process_viewer/destroy/' . $pid;
        $cancel_uri = '/app/process_viewer';
        $items = array(lang('process_viewer_process') . ' - ' . $pid);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }
}
