<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUserSession;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\AbstractResponse;
use ReflectionException;

class GeneralUserSessionRepository extends AbstractRepository
{
    /**
     * GeneralUserSessionRepository constructor.
     * @param GeneralUserSession $entity
     * @param $errorText
     */
    public function __construct(GeneralUserSession $entity, $errorText)
    {
        parent::__construct($entity, $errorText);
    }

    protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '')
    {
        //filter
        $this->filterEntities($qb, "user_sess.id", @$filter['id']);

        $this->filterEntities($qb, "user_sess.hash_id", @$filter['hash_id']);

        //sort
        $this->sortEntities($qb, ["name" => "user_sess.created_at"], @$sort["field"], @$sort["order"], "user_sess.created_at", "desc");
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
        $qb->addSelect("user_sess")
            ->from(get_class($this->getEntityModel()), "user_sess");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return $responseContract->render($qb);
    }

    /**
     * @param array $criteria
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return GeneralUserSession
     * @throws CustomMessagesException
     */
    public function showByBasicCriteria(array $criteria = [], $toArray = "default", $include='', $interrupt = true)
    {
        return parent::showByBasicCriteriaContract($criteria, $toArray, $include, $interrupt);
    }

    /**
     * @param GeneralUserSession $entity
     * @param array $data
     * @param string $toArray
     * @return GeneralUserSession
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(GeneralUserSession $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param GeneralUserSession $entity
     * @return array|Translator|null|string
     */
    public function delete(GeneralUserSession $entity)
    {
        return parent::deleteEntity($entity);
    }
}
