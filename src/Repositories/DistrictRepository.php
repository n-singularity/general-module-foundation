<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use App\Entities\Location\District;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralDistrict;
use ReflectionException;


class DistrictRepository extends AbstractRepository
{
    /**x
     * GeneralUserRepository constructor.
     * @param GeneralDistrict $entity
     * @param $errorText
     */
    public function __construct(GeneralDistrict $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "district.id", @$filter['id']);
        $this->filterEntities($qb, "district.hash_id", @$filter['hash_id']);
        $this->filterEntities($qb, "district.city", @$filter['city']);

        //search
        $this->searchEntities($qb, $search, ["district.name"]);

        //sort
        $this->sortEntities($qb, ["id" => "district.id"], @$sort["field"], @$sort["order"], "district.id", "asc");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|District[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("district")
            ->from(District::class, "district");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return District
     * @throws CustomMessagesException
     */
    public function showByBasicCriteria(array $criteria = [], $toArray = "default", $include = '', $interrupt = true)
    {
        return parent::showByBasicCriteriaContract($criteria, $toArray, $include, $interrupt);
    }

    /**
     * @param array $data
     * @param string $toArray
     * @return mixed
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function store(array $data, $toArray = "default")
    {
        $entity = new District();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param District $entity
     * @param array $data
     * @param string $toArray
     * @return District
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(District $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param District $entity
     * @return array|Translator|null|string
     */
    public function delete(District $entity)
    {
        return parent::deleteEntity($entity);
    }
}
