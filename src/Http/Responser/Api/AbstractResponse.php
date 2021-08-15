<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Responser\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Http\Request;

abstract class AbstractResponse
{
    protected $pageSize;

    protected $page;

    protected $currentPage;

    protected $ignoreRequest = false;

    public function __construct($pageSize = null, $page = null){
        $this->pageSize = (int)$pageSize?$pageSize:10;
        $this->page = $page;

        $this->generatePageAndSize();
    }

    /**
     * @return EntityManager
     */
    protected function em()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em;
    }

    /**
     * @param Paginator $paginator
     * @param $totalItems
     * @param $pagesCount
     */
    protected function createPagination(Paginator &$paginator,&$totalItems,&$pagesCount)
    {
        $totalItems = $paginator->count();
        $pagesCount = ceil($totalItems / $this->pageSize);

        $paginator
            ->getQuery()
            ->setFirstResult($this->pageSize * ($this->currentPage - 1))// set the offset
            ->setMaxResults($this->pageSize);
    }

    /**
     * @param QueryBuilder $query
     * @return mixed
     */
    abstract public function render(QueryBuilder $query);

    /**
     * @param bool $ignoreRequest
     * @return $this
     */
    public function setIgnoreRequest(bool $ignoreRequest)
    {
        $this->ignoreRequest = $ignoreRequest;
        $this->generatePageAndSize();
        return $this;
    }

    protected function generatePageAndSize(){
        if (!$this->ignoreRequest) {
            $request   = app(Request::class);
            $page      = $request->input("page") ? $request->input("page") : $this->page;
            $pageSize = $request->input("page_size") ? $request->input("page_size") : $this->pageSize;
        } else {
            $page      = $this->page;
            $pageSize = $this->pageSize;
        }

        $this->currentPage = (int)$page ? (int)$page : 1;
        $this->pageSize = (int)$pageSize ? (int)$pageSize : 1000;
    }
}
