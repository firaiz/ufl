<?php

namespace AnySys\Inherits;

use AnySys\Database;
use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;

class QueryCacheBuilder extends DoctrineQueryBuilder
{
    public function execute()
    {
        if ($this->getType() !== self::SELECT) {
            return parent::execute();
        }
        return $this->getConnection()
            ->executeQuery($this->getSQL(), $this->getParameters(), $this->getParameterTypes(),
                Database::getInstance()->getCacheProfile());
    }
}