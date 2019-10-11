<?php

namespace GuildBot\Model\Base;

use \Exception;
use \PDO;
use GuildBot\Model\Guild as ChildGuild;
use GuildBot\Model\GuildQuery as ChildGuildQuery;
use GuildBot\Model\Map\GuildTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'guild' table.
 *
 *
 *
 * @method     ChildGuildQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGuildQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildGuildQuery orderBySheetId($order = Criteria::ASC) Order by the sheet_id column
 * @method     ChildGuildQuery orderByWorkSheetTitle($order = Criteria::ASC) Order by the work_sheet_title column
 * @method     ChildGuildQuery orderByAdminRoles($order = Criteria::ASC) Order by the admin_roles column
 * @method     ChildGuildQuery orderByMemberRoles($order = Criteria::ASC) Order by the member_roles column
 * @method     ChildGuildQuery orderByChannels($order = Criteria::ASC) Order by the channels column
 * @method     ChildGuildQuery orderByActive($order = Criteria::ASC) Order by the active column
 *
 * @method     ChildGuildQuery groupById() Group by the id column
 * @method     ChildGuildQuery groupByName() Group by the name column
 * @method     ChildGuildQuery groupBySheetId() Group by the sheet_id column
 * @method     ChildGuildQuery groupByWorkSheetTitle() Group by the work_sheet_title column
 * @method     ChildGuildQuery groupByAdminRoles() Group by the admin_roles column
 * @method     ChildGuildQuery groupByMemberRoles() Group by the member_roles column
 * @method     ChildGuildQuery groupByChannels() Group by the channels column
 * @method     ChildGuildQuery groupByActive() Group by the active column
 *
 * @method     ChildGuildQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGuildQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGuildQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGuildQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGuildQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGuildQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGuildQuery leftJoinAnnouncement($relationAlias = null) Adds a LEFT JOIN clause to the query using the Announcement relation
 * @method     ChildGuildQuery rightJoinAnnouncement($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Announcement relation
 * @method     ChildGuildQuery innerJoinAnnouncement($relationAlias = null) Adds a INNER JOIN clause to the query using the Announcement relation
 *
 * @method     ChildGuildQuery joinWithAnnouncement($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Announcement relation
 *
 * @method     ChildGuildQuery leftJoinWithAnnouncement() Adds a LEFT JOIN clause and with to the query using the Announcement relation
 * @method     ChildGuildQuery rightJoinWithAnnouncement() Adds a RIGHT JOIN clause and with to the query using the Announcement relation
 * @method     ChildGuildQuery innerJoinWithAnnouncement() Adds a INNER JOIN clause and with to the query using the Announcement relation
 *
 * @method     ChildGuildQuery leftJoinSheetConfig($relationAlias = null) Adds a LEFT JOIN clause to the query using the SheetConfig relation
 * @method     ChildGuildQuery rightJoinSheetConfig($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SheetConfig relation
 * @method     ChildGuildQuery innerJoinSheetConfig($relationAlias = null) Adds a INNER JOIN clause to the query using the SheetConfig relation
 *
 * @method     ChildGuildQuery joinWithSheetConfig($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SheetConfig relation
 *
 * @method     ChildGuildQuery leftJoinWithSheetConfig() Adds a LEFT JOIN clause and with to the query using the SheetConfig relation
 * @method     ChildGuildQuery rightJoinWithSheetConfig() Adds a RIGHT JOIN clause and with to the query using the SheetConfig relation
 * @method     ChildGuildQuery innerJoinWithSheetConfig() Adds a INNER JOIN clause and with to the query using the SheetConfig relation
 *
 * @method     \GuildBot\Model\AnnouncementQuery|\GuildBot\Model\SheetConfigQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildGuild findOne(ConnectionInterface $con = null) Return the first ChildGuild matching the query
 * @method     ChildGuild findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGuild matching the query, or a new ChildGuild object populated from the query conditions when no match is found
 *
 * @method     ChildGuild findOneById(string $id) Return the first ChildGuild filtered by the id column
 * @method     ChildGuild findOneByName(string $name) Return the first ChildGuild filtered by the name column
 * @method     ChildGuild findOneBySheetId(string $sheet_id) Return the first ChildGuild filtered by the sheet_id column
 * @method     ChildGuild findOneByWorkSheetTitle(string $work_sheet_title) Return the first ChildGuild filtered by the work_sheet_title column
 * @method     ChildGuild findOneByAdminRoles(array $admin_roles) Return the first ChildGuild filtered by the admin_roles column
 * @method     ChildGuild findOneByMemberRoles(array $member_roles) Return the first ChildGuild filtered by the member_roles column
 * @method     ChildGuild findOneByChannels(array $channels) Return the first ChildGuild filtered by the channels column
 * @method     ChildGuild findOneByActive(boolean $active) Return the first ChildGuild filtered by the active column *

 * @method     ChildGuild requirePk($key, ConnectionInterface $con = null) Return the ChildGuild by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOne(ConnectionInterface $con = null) Return the first ChildGuild matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGuild requireOneById(string $id) Return the first ChildGuild filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByName(string $name) Return the first ChildGuild filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneBySheetId(string $sheet_id) Return the first ChildGuild filtered by the sheet_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByWorkSheetTitle(string $work_sheet_title) Return the first ChildGuild filtered by the work_sheet_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByAdminRoles(array $admin_roles) Return the first ChildGuild filtered by the admin_roles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByMemberRoles(array $member_roles) Return the first ChildGuild filtered by the member_roles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByChannels(array $channels) Return the first ChildGuild filtered by the channels column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGuild requireOneByActive(boolean $active) Return the first ChildGuild filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGuild[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGuild objects based on current ModelCriteria
 * @method     ChildGuild[]|ObjectCollection findById(string $id) Return ChildGuild objects filtered by the id column
 * @method     ChildGuild[]|ObjectCollection findByName(string $name) Return ChildGuild objects filtered by the name column
 * @method     ChildGuild[]|ObjectCollection findBySheetId(string $sheet_id) Return ChildGuild objects filtered by the sheet_id column
 * @method     ChildGuild[]|ObjectCollection findByWorkSheetTitle(string $work_sheet_title) Return ChildGuild objects filtered by the work_sheet_title column
 * @method     ChildGuild[]|ObjectCollection findByAdminRoles(array $admin_roles) Return ChildGuild objects filtered by the admin_roles column
 * @method     ChildGuild[]|ObjectCollection findByMemberRoles(array $member_roles) Return ChildGuild objects filtered by the member_roles column
 * @method     ChildGuild[]|ObjectCollection findByChannels(array $channels) Return ChildGuild objects filtered by the channels column
 * @method     ChildGuild[]|ObjectCollection findByActive(boolean $active) Return ChildGuild objects filtered by the active column
 * @method     ChildGuild[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GuildQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \GuildBot\Model\Base\GuildQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\GuildBot\\Model\\Guild', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGuildQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGuildQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGuildQuery) {
            return $criteria;
        }
        $query = new ChildGuildQuery();
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
     * @return ChildGuild|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GuildTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GuildTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildGuild A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, sheet_id, work_sheet_title, admin_roles, member_roles, channels, active FROM guild WHERE id = :p0';
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
            /** @var ChildGuild $obj */
            $obj = new ChildGuild();
            $obj->hydrate($row);
            GuildTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildGuild|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GuildTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GuildTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GuildTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GuildTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GuildTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GuildTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the sheet_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySheetId('fooValue');   // WHERE sheet_id = 'fooValue'
     * $query->filterBySheetId('%fooValue%', Criteria::LIKE); // WHERE sheet_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sheetId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterBySheetId($sheetId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sheetId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GuildTableMap::COL_SHEET_ID, $sheetId, $comparison);
    }

    /**
     * Filter the query on the work_sheet_title column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkSheetTitle('fooValue');   // WHERE work_sheet_title = 'fooValue'
     * $query->filterByWorkSheetTitle('%fooValue%', Criteria::LIKE); // WHERE work_sheet_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $workSheetTitle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByWorkSheetTitle($workSheetTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($workSheetTitle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GuildTableMap::COL_WORK_SHEET_TITLE, $workSheetTitle, $comparison);
    }

    /**
     * Filter the query on the admin_roles column
     *
     * @param     array $adminRoles The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByAdminRoles($adminRoles = null, $comparison = null)
    {
        $key = $this->getAliasedColName(GuildTableMap::COL_ADMIN_ROLES);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($adminRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($adminRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($adminRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_ADMIN_ROLES, $adminRoles, $comparison);
    }

    /**
     * Filter the query on the admin_roles column
     * @param     mixed $adminRoles The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByAdminRole($adminRoles = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($adminRoles)) {
                $adminRoles = '%| ' . $adminRoles . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $adminRoles = '%| ' . $adminRoles . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(GuildTableMap::COL_ADMIN_ROLES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $adminRoles, $comparison);
            } else {
                $this->addAnd($key, $adminRoles, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_ADMIN_ROLES, $adminRoles, $comparison);
    }

    /**
     * Filter the query on the member_roles column
     *
     * @param     array $memberRoles The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByMemberRoles($memberRoles = null, $comparison = null)
    {
        $key = $this->getAliasedColName(GuildTableMap::COL_MEMBER_ROLES);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($memberRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($memberRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($memberRoles as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_MEMBER_ROLES, $memberRoles, $comparison);
    }

    /**
     * Filter the query on the member_roles column
     * @param     mixed $memberRoles The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByMemberRole($memberRoles = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($memberRoles)) {
                $memberRoles = '%| ' . $memberRoles . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $memberRoles = '%| ' . $memberRoles . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(GuildTableMap::COL_MEMBER_ROLES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $memberRoles, $comparison);
            } else {
                $this->addAnd($key, $memberRoles, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_MEMBER_ROLES, $memberRoles, $comparison);
    }

    /**
     * Filter the query on the channels column
     *
     * @param     array $channels The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByChannels($channels = null, $comparison = null)
    {
        $key = $this->getAliasedColName(GuildTableMap::COL_CHANNELS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($channels as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($channels as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($channels as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_CHANNELS, $channels, $comparison);
    }

    /**
     * Filter the query on the channels column
     * @param     mixed $channels The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByChannel($channels = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($channels)) {
                $channels = '%| ' . $channels . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $channels = '%| ' . $channels . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(GuildTableMap::COL_CHANNELS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $channels, $comparison);
            } else {
                $this->addAnd($key, $channels, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(GuildTableMap::COL_CHANNELS, $channels, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(GuildTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query by a related \GuildBot\Model\Announcement object
     *
     * @param \GuildBot\Model\Announcement|ObjectCollection $announcement the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGuildQuery The current query, for fluid interface
     */
    public function filterByAnnouncement($announcement, $comparison = null)
    {
        if ($announcement instanceof \GuildBot\Model\Announcement) {
            return $this
                ->addUsingAlias(GuildTableMap::COL_ID, $announcement->getGuildId(), $comparison);
        } elseif ($announcement instanceof ObjectCollection) {
            return $this
                ->useAnnouncementQuery()
                ->filterByPrimaryKeys($announcement->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAnnouncement() only accepts arguments of type \GuildBot\Model\Announcement or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Announcement relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function joinAnnouncement($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Announcement');

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
            $this->addJoinObject($join, 'Announcement');
        }

        return $this;
    }

    /**
     * Use the Announcement relation Announcement object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GuildBot\Model\AnnouncementQuery A secondary query class using the current class as primary query
     */
    public function useAnnouncementQuery($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        return $this
            ->joinAnnouncement($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Announcement', '\GuildBot\Model\AnnouncementQuery');
    }

    /**
     * Filter the query by a related \GuildBot\Model\SheetConfig object
     *
     * @param \GuildBot\Model\SheetConfig|ObjectCollection $sheetConfig the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildGuildQuery The current query, for fluid interface
     */
    public function filterBySheetConfig($sheetConfig, $comparison = null)
    {
        if ($sheetConfig instanceof \GuildBot\Model\SheetConfig) {
            return $this
                ->addUsingAlias(GuildTableMap::COL_ID, $sheetConfig->getGuildId(), $comparison);
        } elseif ($sheetConfig instanceof ObjectCollection) {
            return $this
                ->useSheetConfigQuery()
                ->filterByPrimaryKeys($sheetConfig->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySheetConfig() only accepts arguments of type \GuildBot\Model\SheetConfig or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SheetConfig relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function joinSheetConfig($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SheetConfig');

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
            $this->addJoinObject($join, 'SheetConfig');
        }

        return $this;
    }

    /**
     * Use the SheetConfig relation SheetConfig object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \GuildBot\Model\SheetConfigQuery A secondary query class using the current class as primary query
     */
    public function useSheetConfigQuery($relationAlias = null, $joinType = 'Criteria::LEFT_JOIN')
    {
        return $this
            ->joinSheetConfig($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SheetConfig', '\GuildBot\Model\SheetConfigQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGuild $guild Object to remove from the list of results
     *
     * @return $this|ChildGuildQuery The current query, for fluid interface
     */
    public function prune($guild = null)
    {
        if ($guild) {
            $this->addUsingAlias(GuildTableMap::COL_ID, $guild->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the guild table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GuildTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GuildTableMap::clearInstancePool();
            GuildTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GuildTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GuildTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GuildTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GuildTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GuildQuery
