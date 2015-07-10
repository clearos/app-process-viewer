<?php

/**
 * System process manager class.
 *
 * @category   apps
 * @package    process-viewer
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2006-2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/process_viewer/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\process_viewer;

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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Engine as Engine;
use \clearos\apps\base\Shell as Shell;

clearos_load_library('base/Engine');
clearos_load_library('base/Shell');

// Exceptions
//-----------

use \clearos\apps\base\Engine_Exception as Engine_Exception;

clearos_load_library('base/Engine_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * System process manager class.
 *
 * @category   apps
 * @package    process-viewer
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2006-2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/process_viewer/
 */

class Process_Manager extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const COMMAND_PS = '/bin/ps';
    const COMMAND_KILL = '/bin/kill';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Proccess_Manager constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Returns CPU summary information.
     *
     * @return array CPU summary information
     * @throws Engine_Exception
     */

    public function get_cpu_summary()
    {
        clearos_profile(__METHOD__, __LINE__);

        return $this->_get_summary('cpu');
    }

    /**
     * Returns memory summary information.
     *
     * @return array memory summary information
     * @throws Engine_Exception
     */

    public function get_memory_summary()
    {
        clearos_profile(__METHOD__, __LINE__);

        return $this->_get_summary('memory');
    }

    /**
     * Returns raw output from ps command.
     *
     * @return string raw output
     * @throws Engine_Exception
     */

    public function get_raw_data()
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();
        $shell->execute(self::COMMAND_PS, '-eo pid,user,time,%cpu,%mem,sz,tty,ucomm,command');
        $output = $shell->get_output();

        return $output;
    }

    /**
     * Kills processes in given list.
     *
     * @param array $pids list of process IDs
     *
     * @return void
     * @throws Engine_Exception
     */

    public function kill($pids)
    {
        clearos_profile(__METHOD__, __LINE__);

        $shell = new Shell();

        if (! is_array($pids))
            $pids = array($pids);

        foreach ($pids as $pid)
            $shell->execute(self::COMMAND_KILL, $pid, TRUE);
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E  M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Returns summary data.
     *
     * @return array summary data
     * @throws Engine_Exception
     */

    private function _get_summary($type)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($type === 'cpu')
            $column_number = 3;
        elseif ($type === 'memory')
            $column_number = 4;

        $raw_data = $this->get_raw_data();
        array_shift($raw_data);

        $summary = array();

        foreach ($raw_data as $line) {
            $items = preg_split('/\s+/', trim($line), 9);
            $process = preg_replace('/\/.*/', '', $items[7]);

            if (isset($summary[$process]))
                $summary[$process] += (float)($items[$column_number]);
            else
                $summary[$process] = (float)($items[$column_number]);
        }

        // Sort by value
        $sort_summary = array();

        foreach ($summary as $process => $total)
            $sort_summary[$process] = $total;

        array_multisort($sort_summary, SORT_DESC, $summary);

        // Throw out 0 values, and put in standard data format (database row-like)
        $clean_summary = array();
        foreach ($sort_summary as $process => $total) {
            if ($total > 0)
                $clean_summary[] = array($process, $total);
        }

        return $clean_summary;
    }
}
