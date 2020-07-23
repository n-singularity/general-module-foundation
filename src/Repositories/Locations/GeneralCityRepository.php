<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories\Locations;

use App\Entities\Location\City;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\Locations\GeneralCity;
use Nsingularity\GeneralModule\Foundation\Repositories\AbstractRepository;
use ReflectionException;


class GeneralCityRepository extends AbstractRepository
{
    /**
     * GeneralUserRepository constructor.
     * @param GeneralCity $entity
     * @param $errorText
     */
    public function __construct(GeneralCity $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "city.id", @$filter['id']);
        $this->filterEntities($qb, "city.hash_id", @$filter['hash_id']);
        $this->filterEntities($qb, "city.province", @$filter['province']);

        //search
        $this->searchEntities($qb, $search, ["city.name"]);

        //sort
        $this->sortEntities($qb, ["id" => "city.id"], @$sort["field"], @$sort["order"], "city.id", "asc");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|City[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("city")
            ->from(City::class, "city");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $filter
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return City
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
        $entity = new City();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param City $entity
     * @param array $data
     * @param string $toArray
     * @return City
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(City $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param City $entity
     * @return array|Translator|null|string
     */
    public function delete(City $entity)
    {
        return parent::deleteEntity($entity);
    }
}
