<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Responser\Api;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ResponseEntity extends AbstractResponse
{

    public function __construct($pageSize = 1000, $page = null)
    {
        parent::__construct($pageSize, $page);
    }


    public function render(QueryBuilder $query)
    {
        $paginator = new Paginator($query);

        $this->createPagination($paginator,$totalItems,$pagesCount);

        return $paginator->getQuery()->getResult();
    }

}
