<?php

namespace AnySys\Inherits;

use AnySys\Database;
use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;

class QueryCacheBuilder extends DoctrineQueryBuilder
{
    /**
     * @return \Doctrine\DBAL\Driver\Statement|int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function execute()
    {
        if ($this->getType() !== self::SELECT) {
            return parent::execute();
        }
        $result = $this->getConnection()->executeQuery($this->getSQL(), $this->getParameters(),
            $this->getParameterTypes(), Database::getInstance()->getCacheProfile());
        $result->closeCursor();
        return $result;
    }
}