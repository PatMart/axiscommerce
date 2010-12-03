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
 * @package     Axis_Catalog
 * @subpackage  Axis_Catalog_Box
 * @copyright   Copyright 2008-2010 Axis
 * @license     GNU Public License V3.0
 */

/**
 *
 * @category    Axis
 * @package     Axis_Catalog
 * @subpackage  Axis_Catalog_Box
 * @author      Axis Core Team <core@axiscommerce.com>
 */
class Axis_Catalog_Box_Popular extends Axis_Catalog_Box_Product_Listing
{
    protected $_title = 'Popular';
    protected $_class = 'box-popular';

    public function initData()
    {
        $select = Axis::model('catalog/product')->select('id')
            ->addFilterByAvailability()
            ->joinCategory()
            ->where('cc.site_id = ?', Axis::getSiteId())
            ->where('cp.viewed > 0')
            ->order(array('cp.viewed DESC', 'cp.id DESC'))
            ->limit($this->getProductsCount());

        if ($catId = Axis_HumanUri::getInstance()->getParamValue('cat')) {
            $select->where('cc.id = ?', $catId);
        }

        $list = $select->fetchList();

        if (!$list['count']) {
            return false;
        }

        $this->products = $list['data'];
    }
}