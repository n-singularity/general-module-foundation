<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Responser\Api;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ResponseFactory
{
    private $qb;

    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    /**
     * @param string $arrayType
     * @param int $pageSize
     * @param null $page
     * @param string $include
     * @return array
     */
    public function toArray($arrayType='default', $pageSize = 1000, $page = null, $include = ""){
        return (new ResponseArray($arrayType, $pageSize, $page, $include))->render($this->qb);
    }

    /**
     * @param string $arrayType
     * @param int $pageSize
     * @param null $page
     * @param string $include
     * @return Entity[]
     */
    public function toArrayEntity($pageSize = 1000, $page = null){
        return (new ResponseEntity($pageSize, $page))->render($this->qb);
    }

    /**
     * @param string $arrayType
     * @param int $pageSize
     * @param null $page
     * @param string $include
     * @return array
     */
    public function toPaginate($arrayType='default', $pageSize = 1000, $page = null, $include = ""){
        return (new ResponsePagination($arrayType, $pageSize, $page, $include))->render($this->qb);
    }
}
