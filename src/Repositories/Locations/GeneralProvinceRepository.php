<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories\Locations;

use App\Entities\Location\Province;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\Locations\GeneralProvince;
use ReflectionException;


class GeneralProvinceRepository extends AbstractRepository
{
    /**
     * GeneralUserRepository constructor.
     * @param GeneralProvince $entity
     * @param $errorText
     */
    public function __construct(GeneralProvince $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "province.id", @$filter['id']);
        $this->filterEntities($qb, "province.hash_id", @$filter['hash_id']);
        $this->filterEntities($qb, "province.country", @$filter['country']);

        //search
        $this->searchEntities($qb, $search, ["province.name"]);

        //sort
        $this->sortEntities($qb, ["id" => "province.id"], @$sort["field"], @$sort["order"], "province.id", "asc");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|Province[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("province")
            ->from(Province::class, "province");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return Province
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
        $entity = new Province();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param Province $entity
     * @param array $data
     * @param string $toArray
     * @return Province
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(Province $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param Province $entity
     * @return array|Translator|null|string
     */
    public function delete(Province $entity)
    {
        return parent::deleteEntity($entity);
    }
}
