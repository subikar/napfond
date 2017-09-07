<?php
/**
 * GoMage Product Designer Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use/
 * @version      Release: 1.0.0
 * @since        Available since Release 1.0.0
 */

/**
 * Designer setup model
 *
 * @category   GoMage
 * @package    GoMage_ProductDesigner
 * @subpackage Model
 * @author     Roman Bublik <rb@gomage.com>
 */
class GoMage_ProductDesigner_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
    const LENGTH_FOREIGN_NAME   = 64;

    /**
     * Dictionary for generate short name
     *
     * @var array
     */
    protected static $_translateMap = array(
        'address'       => 'addr',
        'admin'         => 'adm',
        'attribute'     => 'attr',
        'enterprise'    => 'ent',
        'catalog'       => 'cat',
        'category'      => 'ctgr',
        'customer'      => 'cstr',
        'notification'  => 'ntfc',
        'product'       => 'prd',
        'session'       => 'sess',
        'user'          => 'usr',
        'entity'        => 'entt',
        'datetime'      => 'dtime',
        'decimal'       => 'dec',
        'varchar'       => 'vchr',
        'index'         => 'idx',
        'compare'       => 'cmp',
        'bundle'        => 'bndl',
        'option'        => 'opt',
        'gallery'       => 'glr',
        'media'         => 'mda',
        'value'         => 'val',
        'link'          => 'lnk',
        'title'         => 'ttl',
        'super'         => 'spr',
        'label'         => 'lbl',
        'website'       => 'ws',
        'aggregat'      => 'aggr',
        'minimal'       => 'min',
        'inventory'     => 'inv',
        'status'        => 'sts',
        'agreement'     => 'agrt',
        'layout'        => 'lyt',
        'resource'      => 'res',
        'directory'     => 'dir',
        'downloadable'  => 'dl',
        'element'       => 'elm',
        'fieldset'      => 'fset',
        'checkout'      => 'chkt',
        'newsletter'    => 'nlttr',
        'shipping'      => 'shpp',
        'calculation'   => 'calc',
        'search'        => 'srch',
        'query'         => 'qr'
    );

    /**
     * Retrieve valid foreign key name
     *
     * @param string $priTableName
     * @param string $priColumnName
     * @param string $refTableName
     * @param string $refColumnName
     * @return string
     */
    public function getFkName($priTableName, $priColumnName, $refTableName, $refColumnName)
    {
        return $this->getForeignKeyName($priTableName, $priColumnName, $refTableName, $refColumnName);
    }

    /**
     * Retrieve valid foreign key name
     * Check foreign key name length and allowed symbols
     *
     * @param string $priTableName
     * @param string $priColumnName
     * @param string $refTableName
     * @param string $refColumnName
     * @return string
     */
    public function getForeignKeyName($priTableName, $priColumnName, $refTableName, $refColumnName)
    {
        $prefix = 'fk_';
        $hash = sprintf('%s_%s_%s_%s', $priTableName, $priColumnName, $refTableName, $refColumnName);
        if (strlen($prefix.$hash) > self::LENGTH_FOREIGN_NAME) {
            $short = self::shortName($prefix.$hash);
            if (strlen($short) > self::LENGTH_FOREIGN_NAME) {
                $hash = md5($hash);
                if (strlen($prefix.$hash) > self::LENGTH_FOREIGN_NAME) {
                    $hash = $this->_minusSuperfluous($hash, $prefix, self::LENGTH_FOREIGN_NAME);
                } else {
                    $hash = $prefix . $hash;
                }
            } else {
                $hash = $short;
            }
        } else {
            $hash = $prefix . $hash;
        }

        return strtoupper($hash);
    }

    public function addForeignKey($fkName, $tableName, $columnName, $refTableName, $refColumnName,
        $onDelete = Varien_Db_Adapter_Interface::FK_ACTION_CASCADE,
        $onUpdate = Varien_Db_Adapter_Interface::FK_ACTION_CASCADE,
        $purge = false, $schemaName = null, $refSchemaName = null)
    {
        if (method_exists($this->getConnection(), 'addForeignKey') === true) {
            $this->getConnection()->addForeignKey($fkName, $tableName, $columnName, $refTableName, $refColumnName,
                $onDelete, $onUpdate, $purge, $schemaName, $refSchemaName);
        } elseif (method_exists($this->getConnection(), 'addConstraint')) {
            $this->getConnection()->addConstraint($fkName, $tableName, $columnName, $refTableName, $refColumnName,
                $onDelete, $onUpdate, $purge);
        }
    }

    /**
     * Convert name using dictionary
     *
     * @param string $name
     * @return string
     */
    public static function shortName($name)
    {
        return strtr($name, self::$_translateMap);
    }

    public function addAutoIncrement($table, $field)
    {
        $version = Mage::getVersionInfo();
        if (($version['major'] === '1') && ((int)$version['minor'] <= 5)) {
            $this->run("ALTER TABLE {$table} MODIFY {$field} INT(12) UNSIGNED NOT NULL AUTO_INCREMENT");
        }
    }

    public function updateDecimalField($table, $field, $allowedNull = true)
    {
        $version = Mage::getVersionInfo();
        if (($version['major'] === '1') && ((int)$version['minor'] <= 5)) {
            $nullValue = $allowedNull === false ? 'NOT NULL' : '';
            $this->run("ALTER TABLE {$table} MODIFY {$field} DECIMAL(12,4) {$nullValue}");
        }
    }

    public function addClipartsToCategory($category)
    {
        if($category && $category > 1) {
            $clipartsDir = Mage::getSingleton('gomage_designer/clipart_gallery_config')->getBaseMediaPath() . '/';

            $pngImages = glob($clipartsDir .'*.png');
            $jpgImages = glob($clipartsDir .'*.jpg');
            $jpegImages = glob($clipartsDir .'*.jpeg');
            $gifImages = glob($clipartsDir .'*.gif');

            $defaultImages = array_merge($pngImages, $jpegImages, $jpgImages, $gifImages);

            $imageIndex = 0;
            foreach($defaultImages as $image) {
                $imagePath = str_replace($clipartsDir, '/', $image);
                $clipart = Mage::getModel('gomage_designer/clipart');
                $clipart->setData(array(
                    'category_id' => $category,
                    'label' => '',
                    'image' => $imagePath,
                    'tags' => '',
                    'position' => $imageIndex,
                    'disabled' => 0,
                ));
                $clipart->save();
                $imageIndex++;
            }
        }
    }
}
