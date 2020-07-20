<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use App\Entities\Location\Village;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralVillage;
use ReflectionException;


class VillageRepository extends AbstractRepository
{
    /**x
     * GeneralUserRepository constructor.
     * @param GeneralVillage $entity
     * @param $errorText
     */
    public function __construct(GeneralVillage $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "vilage.id", @$filter['id']);
        $this->filterEntities($qb, "vilage.hash_id", @$filter['hash_id']);
        $this->filterEntities($qb, "vilage.district", @$filter['district']);

        //search
        $this->searchEntities($qb, $search, ["vilage.name"]);

        //sort
        $this->sortEntities($qb, ["id" => "vilage.id"], @$sort["field"], @$sort["order"], "vilage.id", "asc");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|Village[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("vilage")
            ->from(Village::class, "vilage");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return Village
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
        $entity = new Village();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param Village $entity
     * @param array $data
     * @param string $toArray
     * @return Village
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(Village $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param Village $entity
     * @return array|Translator|null|string
     */
    public function delete(Village $entity)
    {
        return parent::deleteEntity($entity);
    }
}
