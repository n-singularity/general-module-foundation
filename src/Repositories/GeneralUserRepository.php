<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\AbstractResponse;
use ReflectionException;


class GeneralUserRepository extends AbstractRepository
{
    /**
     * User constructor.
     */
    public function __construct(GeneralUser $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }
    
    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return mixed'
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("adm")
            ->from(get_class($this->getEntityModel()), "adm");

        //filter
        $this->filterEntities($qb, "adm.id", @$filter['id']);

        $this->filterEntities($qb, "adm.hash_id", @$filter['hash_id']);

        //search
        $this->searchEntities($qb, $search, ["adm.name", "adm.email"]);

        //sort
        $this->sortEntities($qb, ["name" => "adm.name"], @$sort["field"], @$sort["order"], "adm.name", "asc");

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return GeneralUser
     * @throws CustomMessagesException
     */
    public function showByBasicCriteria(array $criteria = [], $toArray = "default", $include='', $interrupt = true)
    {
        return parent::showByBasicCriteriaContract($criteria, $toArray, $include, $interrupt);
    }

    /**
     * @param GeneralUser $entity
     * @param array $data
     * @param string $toArray
     * @return GeneralUser
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
