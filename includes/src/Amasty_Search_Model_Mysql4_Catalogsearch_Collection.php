<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Search
 */
class Amasty_Search_Model_Mysql4_Catalogsearch_Collection extends Mage_CatalogSearch_Model_Mysql4_Advanced_Collection
{
    public function addFieldsToFilter($fields)
    {
        if ($fields) {
            /*$entityIds = null;*/
            $previousSelect = null;
            foreach ($fields as $table => $conditions) {
                foreach ($conditions as $attributeId => $conditionValue) {
                    $bindVarName = 'attribute_'.$attributeId;
                    $select = $this->getConnection()->select();
                    $select->from(array('t1' => $table), 'entity_id');
                    $conditionData = array();

                    if (is_array($conditionValue)) {
                        if (isset($conditionValue['in'])){
                            $conditionData[] = array('IN (?)', $conditionValue['in']);
                        }
                        elseif (isset($conditionValue['in_set'])) {
                            $conditionData[] = array('REGEXP \'(^|,)('.join('|', $conditionValue['in_set']).')(,|$)\'', $conditionValue['in_set']);
                        }
                        elseif (isset($conditionValue['like'])) {
                            $this->addBindParam($bindVarName, $conditionValue['like']);
                            $conditionData[] = 'LIKE :'.$bindVarName;
                        }
                        elseif (isset($conditionValue['from']) || isset($conditionValue['to'])) {
                            if (!empty($conditionValue['from'])) {
                                //change here
                                $isNum = (is_numeric($conditionValue['from']) || $conditionValue['from'] instanceof Zend_Db_Expr);
                                if (!$isNum){
                                    $conditionValue['from'] = date("Y-m-d H:i:s", strtotime($conditionValue['from']));
                                }
                                $conditionData[] = array('>= ?', $conditionValue['from']);
                            }
                            if (!empty($conditionValue['to'])) {
                                //change here
                                $isNum = (is_numeric($conditionValue['to']) || $conditionValue['to'] instanceof Zend_Db_Expr);
                                if (!$isNum){
                                    $conditionValue['to'] = date("Y-m-d H:i:s", strtotime($conditionValue['from']));
                                }
                                $conditionData[] = array('<= ?', $conditionValue['to']);
                            }
                        }
                    } else {
                        $conditionData[] = array('= ?', $conditionValue);
                    }

                    if (!is_numeric($attributeId)) {
                        foreach ($conditionData as $data) {
                            if (is_array($data)) {
                                $select->where('t1.'.$attributeId . ' ' . $data[0], $data[1]);
                            }
                            else {
                                $select->where('t1.'.$attributeId . ' ' . $data);
                            }
                        }
                    }
                    else {
                        $storeId = $this->getStoreId();
                        $select->joinLeft(
                            array('t2' => $table),
                            $this->getConnection()->quoteInto('t1.entity_id = t2.entity_id AND t1.attribute_id = t2.attribute_id AND t2.store_id=?', $storeId),
                            array()
                        );
                        $select->where('t1.store_id = ?', 0);
                        $select->where('t1.attribute_id = ?', $attributeId);

                        foreach ($conditionData as $data) {
                            if (is_array($data)) {
                                $select->where('IFNULL(t2.value, t1.value) ' . $data[0], $data[1]);
                            }
                            else {
                                $select->where('IFNULL(t2.value, t1.value) ' . $data);
                            }
                        }
                    }

                    if (!is_null($previousSelect)) {
                        $select->where('t1.entity_id IN(?)', new Zend_Db_Expr($previousSelect));
                    }
                    $previousSelect = $select;
                }

                /*if (!is_null($entityIds) && $entityIds) {
                    $select->where('t1.entity_id IN(?)', $entityIds);
                }
                elseif (!is_null($entityIds) && !$entityIds) {
                    continue;
                }

                $entityIds = array();
                $rowSet = $this->getConnection()->fetchAll($select);
                foreach ($rowSet as $row) {
                    $entityIds[] = $row['entity_id'];
                }*/
            }

            /*if ($entityIds) {
                $this->addFieldToFilter('entity_id', array('IN', $entityIds));
            }
            else {
                $this->addFieldToFilter('entity_id', 'IS NULL');
            }*/
            $this->addFieldToFilter('entity_id', array('in' => new Zend_Db_Expr($select)));
        }

        return $this;
    }
}