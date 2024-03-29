<?php

namespace GuildBot\Model\Base;

use \Exception;
use \PDO;
use GuildBot\Model\SheetConfig as ChildSheetConfig;
use GuildBot\Model\SheetConfigQuery as ChildSheetConfigQuery;
use GuildBot\Model\Map\SheetConfigTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sheet_config' table.
 *
 *
 *
 * @method     ChildSheetConfigQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSheetConfigQuery orderByField($order = Criteria::ASC) Order by the field column
 * @method     ChildSheetConfigQuery orderByType($order = Criteria::ASC) Order by the field_type column
 * @method     ChildSheetConfigQuery orderByDefaultValue($order = Criteria::ASC) Order by the default_value column
 * @method     ChildSheetConfigQuery orderByColumn($order = Criteria::ASC) Order by the field_column column
 * @method     ChildSheetConfigQuery orderByGuildId($order = Criteria::ASC) Order by the guild_id column
 *
 * @method     ChildSheetConfigQuery groupById() Group by the id column
 * @method     ChildSheetConfigQuery groupByField() Group by the field column
 * @method     ChildSheetConfigQuery groupByType() Group by the field_type column
 * @method     ChildSheetConfigQuery groupByDefaultValue() Group by the default_value column
 * @method     ChildSheetConfigQuery groupByColumn() Group by the field_column column
 * @method     ChildSheetConfigQuery groupByGuildId() Group by the guild_id column
 *
 * @method     ChildSheetConfigQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSheetConfigQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSheetConfigQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSheetConfigQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSheetConfigQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSheetConfigQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSheetConfigQuery leftJoinGuild($relationAlias = null) Adds a LEFT JOIN clause to the query using the Guild relation
 * @method     ChildSheetConfigQuery rightJoinGuild($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Guild relation
 * @method     ChildSheetConfigQuery innerJoinGuild($relationAlias = null) Adds a INNER JOIN clause to the query using the Guild relation
 *
 * @method     ChildSheetConfigQuery joinWithGuild($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Guild relation
 *
 * @method     ChildSheetConfigQuery leftJoinWithGuild() Adds a LEFT JOIN clause and with to the query using the Guild relation
 * @method     ChildSheetConfigQuery rightJoinWithGuild() Adds a RIGHT JOIN clause and with to the query using the Guild relation
 * @method     ChildSheetConfigQuery innerJoinWithGuild() Adds a INNER JOIN clause and with to the query using the Guild relation
 *
 * @method     \GuildBot\Model\GuildQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSheetConfig findOne(ConnectionInterface $con = null) Return the first ChildSheetConfig matching the query
 * @method     ChildSheetConfig findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSheetConfig matching the query, or a new ChildSheetConfig object populated from the query conditions when no match is found
 *
 * @method     ChildSheetConfig findOneById(string $id) Return the first ChildSheetConfig filtered by the id column
 * @method     ChildSheetConfig findOneByField(string $field) Return the first ChildSheetConfig filtered by the field column
 * @method     ChildSheetConfig findOneByType(string $field_type) Return the first ChildSheetConfig filtered by the field_type column
 * @method     ChildSheetConfig findOneByDefaultValue(string $default_value) Return the first ChildSheetConfig filtered by the default_value column
 * @method     ChildSheetConfig findOneByColumn(string $field_column) Return the first ChildSheetConfig filtered by the field_column column
 * @method     ChildSheetConfig findOneByGuildId(string $guild_id) Return the first ChildSheetConfig filtered by the guild_id column *

 * @method     ChildSheetConfig requirePk($key, ConnectionInterface $con = null) Return the ChildSheetConfig by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOne(ConnectionInterface $con = null) Return the first ChildSheetConfig matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSheetConfig requireOneById(string $id) Return the first ChildSheetConfig filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOneByField(string $field) Return the first ChildSheetConfig filtered by the field column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOneByType(string $field_type) Return the first ChildSheetConfig filtered by the field_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOneByDefaultValue(string $default_value) Return the first ChildSheetConfig filtered by the default_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOneByColumn(string $field_column) Return the first ChildSheetConfig filtered by the field_column column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSheetConfig requireOneByGuildId(string $guild_id) Return the first ChildSheetConfig filtered by the guild_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSheetConfig[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSheetConfig objects based on current ModelCriteria
 * @method     ChildSheetConfig[]|ObjectCollection findById(string $id) Return ChildSheetConfig objects filtered by the id column
 * @method     ChildSheetConfig[]|ObjectCollection findByField(string $field) Return ChildSheetConfig objects filtered by the field column
 * @method     ChildSheetConfig[]|ObjectCollection findByType(string $field_type) Return ChildSheetConfig objects filtered by the field_type column
 * @method     ChildSheetConfig[]|ObjectCollection findByDefaultValue(string $default_value) Return ChildSheetConfig objects filtered by the default_value column
 * @method     ChildSheetConfig[]|ObjectCollection findByColumn(string $field_column) Return ChildSheetConfig objects filtered by the field_column column
 * @method     ChildSheetConfig[]|ObjectCollection findByGuildId(string $guild_id) Return ChildSheetConfig objects filtered by the guild_id column
 * @method     ChildSheetConfig[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SheetConfigQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \GuildBot\Model\Base\SheetConfigQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\GuildBot\\Model\\SheetConfig', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSheetConfigQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSheetConfigQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSheetConfigQuery) {
            return $criteria;
        }
        $query = new ChildSheetConfigQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSheetConfig|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SheetConfigTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SheetConfigTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSheetConfig A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, field, field_type, default_value, field_column, guild_id FROM sheet_config WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSheetConfig $obj */
            $obj = new ChildSheetConfig();
            $obj->hydrate($row);
            SheetConfigTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSheetConfig|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SheetConfigTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SheetConfigTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SheetConfigTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SheetConfigTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the field column
     *
     * Example usage:
     * <code>
     * $query->filterByField('fooValue');   // WHERE field = 'fooValue'
     * $query->filterByField('%fooValue%', Criteria::LIKE); // WHERE field LIKE '%fooValue%'
     * </code>
     *
     * @param     string $field The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByField($field = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($field)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_FIELD, $field, $comparison);
    }

    /**
     * Filter the query on the field_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE field_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE field_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_FIELD_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the default_value column
     *
     * Example usage:
     * <code>
     * $query->filterByDefaultValue('fooValue');   // WHERE default_value = 'fooValue'
     * $query->filterByDefaultValue('%fooValue%', Criteria::LIKE); // WHERE default_value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $defaultValue The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByDefaultValue($defaultValue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($defaultValue)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_DEFAULT_VALUE, $defaultValue, $comparison);
    }

    /**
     * Filter the query on the field_column column
     *
     * Example usage:
     * <code>
     * $query->filterByColumn('fooValue');   // WHERE field_column = 'fooValue'
     * $query->filterByColumn('%fooValue%', Criteria::LIKE); // WHERE field_column LIKE '%fooValue%'
     * </code>
     *
     * @param     string $column The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByColumn($column = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($column)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_FIELD_COLUMN, $column, $comparison);
    }

    /**
     * Filter the query on the guild_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGuildId(1234); // WHERE guild_id = 1234
     * $query->filterByGuildId(array(12, 34)); // WHERE guild_id IN (12, 34)
     * $query->filterByGuildId(array('min' => 12)); // WHERE guild_id > 12
     * </code>
     *
     * @see       filterByGuild()
     *
     * @param     mixed $guildId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByGuildId($guildId = null, $comparison = null)
    {
        if (is_array($guildId)) {
            $useMinMax = false;
            if (isset($guildId['min'])) {
                $this->addUsingAlias(SheetConfigTableMap::COL_GUILD_ID, $guildId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($guildId['max'])) {
                $this->addUsingAlias(SheetConfigTableMap::COL_GUILD_ID, $guildId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SheetConfigTableMap::COL_GUILD_ID, $guildId, $comparison);
    }

    /**
     * Filter the query by a related \GuildBot\Model\Guild object
     *
     * @param \GuildBot\Model\Guild|ObjectCollection $guild The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSheetConfigQuery The current query, for fluid interface
     */
    public function filterByGuild($guild, $comparison = null)
    {
        if ($guild instanceof \GuildBot\Model\Guild) {
            return $this
                ->addUsingAlias(SheetConfigTableMap::COL_GUILD_ID, $guild->getId(), $comparison);
        } elseif ($guild instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SheetConfigTableMap::COL_GUILD_ID, $guild->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGuild() only accepts arguments of type \GuildBot\Model\Guild or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Guild relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function joinGuild($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Guild');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Guild');
        }

        return $this;
    }

    /**
     * Use the Guild relation Guild object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GuildBot\Model\GuildQuery A secondary query class using the current class as primary query
     */
    public function useGuildQuery($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        return $this
            ->joinGuild($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Guild', '\GuildBot\Model\GuildQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSheetConfig $sheetConfig Object to remove from the list of results
     *
     * @return $this|ChildSheetConfigQuery The current query, for fluid interface
     */
    public function prune($sheetConfig = null)
    {
        if ($sheetConfig) {
            $this->addUsingAlias(SheetConfigTableMap::COL_ID, $sheetConfig->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sheet_config table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SheetConfigTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SheetConfigTableMap::clearInstancePool();
            SheetConfigTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SheetConfigTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SheetConfigTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SheetConfigTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SheetConfigTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SheetConfigQuery
