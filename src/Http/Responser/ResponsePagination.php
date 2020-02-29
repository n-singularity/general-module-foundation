<?php

namespace Nsingularity\GeneralModul\Foundation\Http\Responser\Api;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ResponsePagination extends AbstractResponse
{
    private $arrayType = "default";
    private $include   = true;

    public function __construct($arrayType = "default", $pageSize = 10, $page = null, $include = "")
    {
        $this->arrayType = $arrayType;
        $this->include   = $include;

        parent::__construct($pageSize, $page);
    }

    public function render(QueryBuilder $query)
    {
        $paginator = new Paginator($query);

        $this->createPagination($paginator, $totalItems, $pagesCount);

        $data = listEntityToListArray($paginator->getQuery()->getResult(), $this->arrayType, $this->include);

        return [
            "total"        => (int)$totalItems,
            "per_page"     => (int)$this->pageSize,
            "current_page" => (int)$this->currentPage,
            "last_page"    => (int)$pagesCount,
            "data"         => $data,
        ];
    }
}
