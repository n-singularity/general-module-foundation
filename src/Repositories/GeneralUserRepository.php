<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\ResponseFactory;
use ReflectionException;


class GeneralUserRepository extends AbstractRepository
{
    /**
     * GeneralUserRepository constructor.
     * @param GeneralUser $entity
     * @param $errorText
     */
    public function __construct(GeneralUser $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "user.id", @$filter['id']);

        $this->filterEntities($qb, "user.hash_id", @$filter['hash_id']);

        //search
        $this->searchEntities($qb, $search, ["user.name", "user.email"]);

        //sort
        $this->sortEntities($qb, ["name" => "user.name"], @$sort["field"], @$sort["order"], "user.name", "asc");
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
        $qb->addSelect("user")
            ->from(get_class($this->getEntityModel()), "user");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return new ResponseFactory($qb);
    }

    /**
     * @param array $filter
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return GeneralUser
     * @throws CustomMessagesException
     */
    public function showByBasicFilter(array $filter = [], $toArray = "default", $include = '', $interrupt = true)
    {
        return parent::showByBasicFilterContract($filter, $toArray, $include, $interrupt);
    }

    /**
     * @param GeneralUser $entity
     * @param array $data
     * @param string $toArray
     * @return mixed
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(GeneralUser $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param GeneralUser $entity
     * @return array|Translator|null|string
     */
    public function delete(GeneralUser $entity)
    {
        return parent::deleteEntity($entity);
    }
}
