<?php

namespace GuildBot\Model\Base;

use \Exception;
use \PDO;
use GuildBot\Model\Announcement as ChildAnnouncement;
use GuildBot\Model\AnnouncementQuery as ChildAnnouncementQuery;
use GuildBot\Model\Map\AnnouncementTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'announcement' table.
 *
 *
 *
 * @method     ChildAnnouncementQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAnnouncementQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildAnnouncementQuery orderByGuildId($order = Criteria::ASC) Order by the guild_id column
 * @method     ChildAnnouncementQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAnnouncementQuery orderByBroadcastedAt($order = Criteria::ASC) Order by the broadcasted_at column
 *
 * @method     ChildAnnouncementQuery groupById() Group by the id column
 * @method     ChildAnnouncementQuery groupByMessage() Group by the message column
 * @method     ChildAnnouncementQuery groupByGuildId() Group by the guild_id column
 * @method     ChildAnnouncementQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAnnouncementQuery groupByBroadcastedAt() Group by the broadcasted_at column
 *
 * @method     ChildAnnouncementQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAnnouncementQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAnnouncementQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAnnouncementQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAnnouncementQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAnnouncementQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAnnouncementQuery leftJoinGuild($relationAlias = null) Adds a LEFT JOIN clause to the query using the Guild relation
 * @method     ChildAnnouncementQuery rightJoinGuild($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Guild relation
 * @method     ChildAnnouncementQuery innerJoinGuild($relationAlias = null) Adds a INNER JOIN clause to the query using the Guild relation
 *
 * @method     ChildAnnouncementQuery joinWithGuild($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Guild relation
 *
 * @method     ChildAnnouncementQuery leftJoinWithGuild() Adds a LEFT JOIN clause and with to the query using the Guild relation
 * @method     ChildAnnouncementQuery rightJoinWithGuild() Adds a RIGHT JOIN clause and with to the query using the Guild relation
 * @method     ChildAnnouncementQuery innerJoinWithGuild() Adds a INNER JOIN clause and with to the query using the Guild relation
 *
 * @method     \GuildBot\Model\GuildQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAnnouncement findOne(ConnectionInterface $con = null) Return the first ChildAnnouncement matching the query
 * @method     ChildAnnouncement findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAnnouncement matching the query, or a new ChildAnnouncement object populated from the query conditions when no match is found
 *
 * @method     ChildAnnouncement findOneById(string $id) Return the first ChildAnnouncement filtered by the id column
 * @method     ChildAnnouncement findOneByMessage(string $message) Return the first ChildAnnouncement filtered by the message column
 * @method     ChildAnnouncement findOneByGuildId(string $guild_id) Return the first ChildAnnouncement filtered by the guild_id column
 * @method     ChildAnnouncement findOneByCreatedAt(string $created_at) Return the first ChildAnnouncement filtered by the created_at column
 * @method     ChildAnnouncement findOneByBroadcastedAt(string $broadcasted_at) Return the first ChildAnnouncement filtered by the broadcasted_at column *

 * @method     ChildAnnouncement requirePk($key, ConnectionInterface $con = null) Return the ChildAnnouncement by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnnouncement requireOne(ConnectionInterface $con = null) Return the first ChildAnnouncement matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAnnouncement requireOneById(string $id) Return the first ChildAnnouncement filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnnouncement requireOneByMessage(string $message) Return the first ChildAnnouncement filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnnouncement requireOneByGuildId(string $guild_id) Return the first ChildAnnouncement filtered by the guild_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnnouncement requireOneByCreatedAt(string $created_at) Return the first ChildAnnouncement filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAnnouncement requireOneByBroadcastedAt(string $broadcasted_at) Return the first ChildAnnouncement filtered by the broadcasted_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAnnouncement[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAnnouncement objects based on current ModelCriteria
 * @method     ChildAnnouncement[]|ObjectCollection findById(string $id) Return ChildAnnouncement objects filtered by the id column
 * @method     ChildAnnouncement[]|ObjectCollection findByMessage(string $message) Return ChildAnnouncement objects filtered by the message column
 * @method     ChildAnnouncement[]|ObjectCollection findByGuildId(string $guild_id) Return ChildAnnouncement objects filtered by the guild_id column
 * @method     ChildAnnouncement[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildAnnouncement objects filtered by the created_at column
 * @method     ChildAnnouncement[]|ObjectCollection findByBroadcastedAt(string $broadcasted_at) Return ChildAnnouncement objects filtered by the broadcasted_at column
 * @method     ChildAnnouncement[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AnnouncementQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \GuildBot\Model\Base\AnnouncementQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\GuildBot\\Model\\Announcement', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAnnouncementQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAnnouncementQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAnnouncementQuery) {
            return $criteria;
        }
        $query = new ChildAnnouncementQuery();
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
     * @return ChildAnnouncement|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AnnouncementTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AnnouncementTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAnnouncement A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, message, guild_id, created_at, broadcasted_at FROM announcement WHERE id = :p0';
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
            /** @var ChildAnnouncement $obj */
            $obj = new ChildAnnouncement();
            $obj->hydrate($row);
            AnnouncementTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAnnouncement|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AnnouncementTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AnnouncementTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnnouncementTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnnouncementTableMap::COL_MESSAGE, $message, $comparison);
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
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByGuildId($guildId = null, $comparison = null)
    {
        if (is_array($guildId)) {
            $useMinMax = false;
            if (isset($guildId['min'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_GUILD_ID, $guildId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($guildId['max'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_GUILD_ID, $guildId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnnouncementTableMap::COL_GUILD_ID, $guildId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnnouncementTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the broadcasted_at column
     *
     * Example usage:
     * <code>
     * $query->filterByBroadcastedAt('2011-03-14'); // WHERE broadcasted_at = '2011-03-14'
     * $query->filterByBroadcastedAt('now'); // WHERE broadcasted_at = '2011-03-14'
     * $query->filterByBroadcastedAt(array('max' => 'yesterday')); // WHERE broadcasted_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $broadcastedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByBroadcastedAt($broadcastedAt = null, $comparison = null)
    {
        if (is_array($broadcastedAt)) {
            $useMinMax = false;
            if (isset($broadcastedAt['min'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_BROADCASTED_AT, $broadcastedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($broadcastedAt['max'])) {
                $this->addUsingAlias(AnnouncementTableMap::COL_BROADCASTED_AT, $broadcastedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnnouncementTableMap::COL_BROADCASTED_AT, $broadcastedAt, $comparison);
    }

    /**
     * Filter the query by a related \GuildBot\Model\Guild object
     *
     * @param \GuildBot\Model\Guild|ObjectCollection $guild The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAnnouncementQuery The current query, for fluid interface
     */
    public function filterByGuild($guild, $comparison = null)
    {
        if ($guild instanceof \GuildBot\Model\Guild) {
            return $this
                ->addUsingAlias(AnnouncementTableMap::COL_GUILD_ID, $guild->getId(), $comparison);
        } elseif ($guild instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AnnouncementTableMap::COL_GUILD_ID, $guild->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
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
     * @param   ChildAnnouncement $announcement Object to remove from the list of results
     *
     * @return $this|ChildAnnouncementQuery The current query, for fluid interface
     */
    public function prune($announcement = null)
    {
        if ($announcement) {
            $this->addUsingAlias(AnnouncementTableMap::COL_ID, $announcement->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the announcement table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AnnouncementTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AnnouncementTableMap::clearInstancePool();
            AnnouncementTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AnnouncementTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AnnouncementTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AnnouncementTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AnnouncementTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AnnouncementQuery
