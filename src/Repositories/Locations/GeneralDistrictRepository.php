<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories\Locations;

use App\Entities\Location\District;
use App\Exceptions\CustomMessagesException;
use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\Locations\GeneralDistrict;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\ResponseFactory;
use Nsingularity\GeneralModule\Foundation\Repositories\AbstractRepository;
use ReflectionException;


class GeneralDistrictRepository extends AbstractRepository
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
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @return ResponseFactory
     */
    public function get($filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("district")
            ->from(District::class, "district");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return new ResponseFactory($qb);
    }

    /**
     * @param array $filter
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return District
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
