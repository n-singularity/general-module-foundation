<?php

namespace Nsingularity\GeneralModul\Foundation\Repositories;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Exception;


abstract class AbstractFunctionRepository
{
    const SPECIFIC_DATE           = "specific_date";
    const BETWEEN_TYPE_TODAY      = "today";
    const BETWEEN_TYPE_LAST_WEEK  = "last_week";
    const BETWEEN_TYPE_LAST_MONTH = "last_month";

    private $index = 0;

    /**
     * @return EntityManagerInterface
     */
    protected function em()
    {
        /** @var EntityManagerInterface $em */
        $em = app(EntityManager::class);
        return $em;
    }

    /**
     * @param $search
     * @param QueryBuilder $qb
     * @param array $field
     */
    protected function searchEntities(QueryBuilder &$qb, $search, array $field)
    {
        if ($search && $field) {
            $searchArray = explode(" ", $search);
            $field       = implode(",", $field);
            $index       = 0;
            foreach ($searchArray as $search) {
                $index++;
                $qb = $qb->andWhere("CONCAT_WS(' ',$field,' ') LIKE :search_$index")
                    ->setParameter("search_$index", "%$search%");
            }
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param $field
     * @param $filter
     * @param string $operator
     */
    protected function filterEntities(QueryBuilder &$qb, $field, $filter, $operator = "=")
    {
        $index = $this->index++;
        if ($filter !== null && $filter !== "") {
            if (is_array($filter)) {
                $operator = @$filter[0];
                $filter   = @$filter[1];
            }

            if (is_array($filter) || $operator == 'IN' || $operator == 'NOT IN') {
                if($filter){
                    $qb->andWhere("$field $operator (:filter_$index)")
                        ->setParameter("filter_$index", $filter);
                }
            } elseif ($operator == "IS NULL" || $operator == "IS NOT NULL") {
                $qb->andWhere("$field $operator");
            }elseif ($operator == "REGEXP") {
                $qb->andWhere("REGEXP($field, :filter_$index) = true")
                    ->setParameter("filter_$index", $filter);
            } else {
                $qb->andWhere("$field $operator :filter_$index")
                    ->setParameter("filter_$index", $filter);
            }
        }
    }
    
    /**
     * @param QueryBuilder $qb
     * @param $field
     * @param $startDate
     * @param $endDate
     * @throws Exception
     */
    protected function filterEntitiesStartEndDate(QueryBuilder &$qb, $field, $startDate, $endDate)
    {
        if ($startDate) {
            $dateS = new Carbon($startDate . " 00:00:00");
            $this->filterEntities($qb, $field, $dateS, ">=");
        }

        if ($endDate) {
            $dateE = new Carbon($endDate . " 23:59:59");
            $this->filterEntities($qb, $field, $dateE, "<=");
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array $sortableField
     * @param $sortBy
     * @param string $orderBy
     * @param string $defaultSortBy
     * @param string $defaultOrderBy
     */
    protected function sortEntities(QueryBuilder &$qb, array $sortableField, $sortBy, $orderBy = "asc", $defaultSortBy = "", $defaultOrderBy = "asc")
    {
        if (array_key_exists($sortBy, $sortableField) && in_array($orderBy, ["asc", "desc"])) {
            $qb = $qb->addOrderBy($sortableField[$sortBy], "$orderBy");
        } else if (in_array($orderBy, ["rand"])) {
            $qb = $qb->addOrderBy("RAND()");
        } elseif ($defaultSortBy) {
            $qb = $qb->addOrderBy("$defaultSortBy", "$defaultOrderBy");
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param array $sortableField
     * @param $sorts
     * @param string $defaultSortBy
     * @param string $defaultOrderBy
     */
    protected function sortMultipleEntities(QueryBuilder &$qb, array $sortableField, $sorts, $defaultSortBy = "", $defaultOrderBy = "asc")
    {
        $sorting = false;

        if (is_array($sorts)) {
            foreach ($sorts as $sortBy => $orderBy) {
                if (array_key_exists($sortBy, $sortableField) && in_array($orderBy, ["asc", "desc"])) {
                    $qb = $qb->addOrderBy($sortableField[$sortBy], "$orderBy");
                    $sorting =true;
                }
            }
        }

        if(!$sorting){
            $qb = $qb->addOrderBy("$defaultSortBy", "$defaultOrderBy");
        }
    }


    /**
     * @param QueryBuilder $qb
     * @param $field
     * @param $type
     * @param null $startDate
     * @param null $endDate
     * @throws Exception
     */
    protected function filterEntitiesBetweenType(QueryBuilder &$qb, $field, $type, $startDate = null, $endDate = null)
    {

        try {
            $startDate = ($startDate instanceof DateTime) ? $startDate : new Carbon($startDate);
            $endDate   = ($endDate instanceof DateTime) ? $endDate : new Carbon($endDate);
        } catch (Exception $e) {
            $startDate = ($startDate instanceof DateTime) ? $startDate : new Carbon();
            $endDate   = ($endDate instanceof DateTime) ? $endDate : new Carbon();
        }

        switch ($type) {
            case $this::BETWEEN_TYPE_TODAY:
                $endDate   = new Carbon();
                $startDate = new Carbon($endDate->format("Y-m-d 00:00:00"));
                break;
            case $this::BETWEEN_TYPE_LAST_WEEK:
                $endDate   = new Carbon();
                $startDate = (clone $endDate)->subWeeks(1);
                break;
            case $this::BETWEEN_TYPE_LAST_MONTH:
                $endDate   = new Carbon();
                $startDate = (clone $endDate)->subWeeks(1);
                break;
            case $this::SPECIFIC_DATE:
                $startDate = $startDate ? new Carbon($startDate->format("Y-m-d 00:00:00")) : new Carbon(date("Y-m-d 00:00:00"));
                $endDate   = new Carbon($startDate->format("Y-m-d 23:59:59"));
                break;
        }

        if ($startDate instanceof DateTime && $endDate instanceof DateTime) {
            $qb->andWhere("($field BETWEEN :startDate AND :endDate)")
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param $sortBy
     * @param $fieldIndex
     * @param string $orderBy
     */
    protected function sortCustomFieldEntities(QueryBuilder &$qb, $sortBy, array $fieldIndex, $orderBy = "asc")
    {
        if ($sortBy && in_array($orderBy, ["asc", "desc"]) && $fieldIndex) {
            $fieldIndex = implode(',', $fieldIndex);

            $qb = $qb->addOrderBy(" ", "FIELD($sortBy,$fieldIndex) $orderBy");
        }
    }
}
