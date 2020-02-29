<?php

namespace Nsingularity\GeneralModul\Foundation\Http\Responser\Api;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ResponseArray extends AbstractResponse
{
    private $arrayType = "default";
    private $include   = "";

    public function __construct($arrayType='default', $pageSize = 1000, $page = null, $include = "")
    {
        $this->arrayType = $arrayType;
        $this->include   = $include;

        parent::__construct($pageSize, $page);
    }

    public function render(QueryBuilder $query)
    {
        $paginator = new Paginator($query);

        $this->createPagination($paginator, $totalItems, $pagesCount);

        return listEntityToListArray($paginator->getQuery()->getResult(), $this->arrayType, $this->include);
    }
}
