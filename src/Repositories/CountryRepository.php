<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use App\Entities\Locations\Country;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralCity;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralCountry;
use ReflectionException;


class CountryRepository extends AbstractRepository
{
    /**
     * GeneralUserRepository constructor.
     * @param GeneralCity $entity
     * @param $errorText
     */
    public function __construct(GeneralCountry $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "country.id", @$filter['id']);
        $this->filterEntities($qb, "country.hash_id", @$filter['hash_id']);

        //search
        $this->searchEntities($qb, $search, ["country.name"]);

        //sort
        $this->sortEntities($qb, ["id" => "country.id"], @$sort["field"], @$sort["order"], "country.id", "asc");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|Country[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '', $addFunction = [])
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("country")
            ->from(Country::class, "Country");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return Country
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
        $entity = new Country();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param Country $entity
     * @param array $data
     * @param string $toArray
     * @return Country
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(Country $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param Country $entity
     * @return array|Translator|null|string
     */
    public function delete(Country $entity)
    {
        return parent::deleteEntity($entity);
    }
}
