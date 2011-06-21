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
 * @package     Axis_Cms
 * @subpackage  Axis_Cms_Model
 * @copyright   Copyright 2008-2010 Axis
 * @license     GNU Public License V3.0
 */

/**
 *
 * @category    Axis
 * @package     Axis_Cms
 * @subpackage  Axis_Cms_Model
 * @author      Axis Core Team <core@axiscommerce.com>
 */
class Axis_Cms_Model_Category extends Axis_Db_Table
{
    protected $_name = 'cms_category';

    protected $_rowClass = 'Axis_Cms_Model_Category_Row';

    protected $_selectClass = 'Axis_Cms_Model_Category_Select';

    protected $_primary = 'id';

    /**
     * Update or insert category row
     *
     * @param array $data
     * <code>
     *  column_name => value,
     *  content     => array(
     *      langId => array()
     *  )
     * </code>
     */
    public function save(array $data)
    {
        if (!$data['id']) {
            unset($data['id']);
        }
        if (empty($data['parent_id'])) {
            $data['parent_id'] = new Zend_Db_Expr('NULL');
        }
        $row = $this->getRow($data);
        $row->save();
        
//        // $this->getRow($data)->save();
//
//        if (!isset($data['id'])
//            || !$row = $this->find($data['id'])->current()) {
//
//            $row = $this->createRow();
//        }
//        unset($data['id']);
//        if (empty($data['parent_id'])) {
//            $data['parent_id'] = new Zend_Db_Expr('NULL');
//        }
//        $row->setFromArray($data)
//            ->save();
        return $row;
    }

    public function getCategoryIdByLink($link)
    {
        return Axis::single('cms/category_content')
            ->select('cms_category_id')
            ->joinInner(
                'cms_category',
                'cc.id = ccc.cms_category_id'
            )
            ->where('ccc.link = ?', $link)
            ->where('cc.site_id = ?', Axis::getSiteId())
            ->fetchOne();
    }

    /**
     *
     * @param int $pageId
     * @return array
     */
    public function getIdsByPage($pageId)
    {
        return Axis::single('cms/page_category')
            ->select('cms_category_id')
            ->where('cms_page_id = ? ', $pageId)
            ->fetchCol();
    }

    private function _recurse(&$arr, $id, &$res = array())
    {
        if (isset($arr[$id]) && (null !== $arr[$id])) {
            $res[$id] = $arr[$id];
        } else {
            return $res;
        }
        if (null !== $arr[$id]['parent_id'] ) {
            $this->_recurse($arr, $arr[$id]['parent_id'], $res);
        }
        return $res;
    }

    public function getParentCategory($id, $isPage = false)
    {
        $all = $this->select(array('id', 'parent_id'))
            ->joinInner('cms_category_content',
                'ccc.cms_category_id = cc.id',
                array('link', 'title', 'meta_title', 'meta_description', 'meta_keyword'))
            ->where('cc.is_active = 1')
            ->where('cc.site_id = ?', Axis::getSiteId())
            ->where('ccc.language_id = ?', Axis_Locale::getLanguageId())
            ->where('ccc.link IS NOT NULL')
            ->fetchAssoc();

        if ($isPage) {
            $ids = $this->getIdsByPage($id);
            $id = current($ids);
        }
        $result = array();
        $this->_recurse($all, $id, $result);
        return array_reverse($result);
    }

    /**
     *  return parent category for some site and lang
     *
     */
    public function getRootCategories()
    {
        return $this->select('id')
           ->joinInner('cms_category_content',
                'ccc.cms_category_id = cc.id',
                array('link', 'title', 'description')
           )
           ->where('cc.is_active = 1')
           ->where('cc.site_id = ?', Axis::getSiteId())
           ->where('ccc.language_id = ?', Axis_Locale::getLanguageId())
           ->where('cc.parent_id IS NULL')
//           ->where('ccc.link IS NOT NULL')
           ->fetchAll();
    }
}