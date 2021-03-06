<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories\Locations;

use App\Entities\Location\Village;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\Modules\Locations\GeneralVillage;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\ResponseFactory;
use Nsingularity\GeneralModule\Foundation\Repositories\AbstractRepository;
use ReflectionException;


class GeneralVillageRepository extends AbstractRepository
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
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @return ResponseFactory
     */
    public function get($filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("vilage")
            ->from(Village::class, "vilage");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return new ResponseFactory($qb);
    }

    /**
     * @param array $filter
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return Village
     * @throws CustomMessagesException
     */
    public function showByBasicFilter(array $filter = [], $toArray = "default", $include = '', $interrupt = true)
    {
        return parent::showByBasicFilterContract($filter, $toArray, $include, $interrupt);
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
