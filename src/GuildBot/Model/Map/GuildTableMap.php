<?php

namespace GuildBot\Model\Map;

use GuildBot\Model\Guild;
use GuildBot\Model\GuildQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'guild' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GuildTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'GuildBot.Model.Map.GuildTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'guild';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\GuildBot\\Model\\Guild';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'GuildBot.Model.Guild';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'guild.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'guild.name';

    /**
     * the column name for the sheet_id field
     */
    const COL_SHEET_ID = 'guild.sheet_id';

    /**
     * the column name for the work_sheet_title field
     */
    const COL_WORK_SHEET_TITLE = 'guild.work_sheet_title';

    /**
     * the column name for the admin_roles field
     */
    const COL_ADMIN_ROLES = 'guild.admin_roles';

    /**
     * the column name for the member_roles field
     */
    const COL_MEMBER_ROLES = 'guild.member_roles';

    /**
     * the column name for the channels field
     */
    const COL_CHANNELS = 'guild.channels';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'guild.active';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'SheetId', 'WorkSheetTitle', 'AdminRoles', 'MemberRoles', 'Channels', 'Active', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'sheetId', 'workSheetTitle', 'adminRoles', 'memberRoles', 'channels', 'active', ),
        self::TYPE_COLNAME       => array(GuildTableMap::COL_ID, GuildTableMap::COL_NAME, GuildTableMap::COL_SHEET_ID, GuildTableMap::COL_WORK_SHEET_TITLE, GuildTableMap::COL_ADMIN_ROLES, GuildTableMap::COL_MEMBER_ROLES, GuildTableMap::COL_CHANNELS, GuildTableMap::COL_ACTIVE, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'sheet_id', 'work_sheet_title', 'admin_roles', 'member_roles', 'channels', 'active', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'SheetId' => 2, 'WorkSheetTitle' => 3, 'AdminRoles' => 4, 'MemberRoles' => 5, 'Channels' => 6, 'Active' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'sheetId' => 2, 'workSheetTitle' => 3, 'adminRoles' => 4, 'memberRoles' => 5, 'channels' => 6, 'active' => 7, ),
        self::TYPE_COLNAME       => array(GuildTableMap::COL_ID => 0, GuildTableMap::COL_NAME => 1, GuildTableMap::COL_SHEET_ID => 2, GuildTableMap::COL_WORK_SHEET_TITLE => 3, GuildTableMap::COL_ADMIN_ROLES => 4, GuildTableMap::COL_MEMBER_ROLES => 5, GuildTableMap::COL_CHANNELS => 6, GuildTableMap::COL_ACTIVE => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'sheet_id' => 2, 'work_sheet_title' => 3, 'admin_roles' => 4, 'member_roles' => 5, 'channels' => 6, 'active' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('guild');
        $this->setPhpName('Guild');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\GuildBot\\Model\\Guild');
        $this->setPackage('GuildBot.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'BIGINT', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('sheet_id', 'SheetId', 'VARCHAR', false, 255, null);
        $this->addColumn('work_sheet_title', 'WorkSheetTitle', 'VARCHAR', false, 255, null);
        $this->addColumn('admin_roles', 'AdminRoles', 'ARRAY', false, null, null);
        $this->addColumn('member_roles', 'MemberRoles', 'ARRAY', false, null, null);
        $this->addColumn('channels', 'Channels', 'ARRAY', false, null, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', true, 1, true);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Announcement', '\\GuildBot\\Model\\Announcement', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':guild_id',
    1 => ':id',
  ),
), 'SET NULL', 'SET NULL', 'Announcements', false);
        $this->addRelation('SheetConfig', '\\GuildBot\\Model\\SheetConfig', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':guild_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', 'SheetConfigs', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to guild     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        AnnouncementTableMap::clearInstancePool();
        SheetConfigTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? GuildTableMap::CLASS_DEFAULT : GuildTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Guild object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GuildTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GuildTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GuildTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GuildTableMap::OM_CLASS;
            /** @var Guild $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GuildTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = GuildTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GuildTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Guild $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GuildTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(GuildTableMap::COL_ID);
            $criteria->addSelectColumn(GuildTableMap::COL_NAME);
            $criteria->addSelectColumn(GuildTableMap::COL_SHEET_ID);
            $criteria->addSelectColumn(GuildTableMap::COL_WORK_SHEET_TITLE);
            $criteria->addSelectColumn(GuildTableMap::COL_ADMIN_ROLES);
            $criteria->addSelectColumn(GuildTableMap::COL_MEMBER_ROLES);
            $criteria->addSelectColumn(GuildTableMap::COL_CHANNELS);
            $criteria->addSelectColumn(GuildTableMap::COL_ACTIVE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.sheet_id');
            $criteria->addSelectColumn($alias . '.work_sheet_title');
            $criteria->addSelectColumn($alias . '.admin_roles');
            $criteria->addSelectColumn($alias . '.member_roles');
            $criteria->addSelectColumn($alias . '.channels');
            $criteria->addSelectColumn($alias . '.active');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(GuildTableMap::DATABASE_NAME)->getTable(GuildTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GuildTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(GuildTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new GuildTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Guild or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Guild object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GuildTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \GuildBot\Model\Guild) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GuildTableMap::DATABASE_NAME);
            $criteria->add(GuildTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = GuildQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GuildTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GuildTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the guild table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GuildQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Guild or Criteria object.
     *
     * @param mixed               $criteria Criteria or Guild object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GuildTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Guild object
        }


        // Set the correct dbName
        $query = GuildQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GuildTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GuildTableMap::buildTableMap();
