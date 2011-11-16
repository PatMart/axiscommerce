<?php
/**
 * Axis
 *
 * This file is part of Axis.
 *
 * Axis is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Axis is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Axis.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category    Axis
 * @package     Axis_Core
 * @subpackage  Axis_Core_Model
 * @copyright   Copyright 2008-2011 Axis
 * @license     GNU Public License V3.0
 */

/**
 *
 * @category    Axis
 * @package     Axis_Core
 * @subpackage  Axis_Core_Model
 * @author      Axis Core Team <core@axiscommerce.com>
 */
abstract class Axis_Core_Model_Migration_Abstract implements Axis_Core_Model_Migration_Interface
{
    /**
     * @var Axis_Install_Model_Installer
     */
    protected $_installer;
    
    /**
     *
     * @var string 
     */
    protected $_info = '';
    
    /**
     *
     * @var string 
     */
    protected $_version;

    public function  __construct()
    {
        $this->_installer = Axis::single('install/installer');
    }
    
    /**
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->_info;
    }
    
    /**
     *
     * @return Axis_Install_Model_Installer 
     */
    public function getInstaller()
    {
        return $this->_installer;
    }

    /**
     *
     * @return void 
     */
    public function up() 
    {
        
    }
    
    /**
     *
     * @return void 
     */
    public function down() 
    {
        
    }
}