<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\Modules\Users\GeneralUserSession;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\ResponseFactory;
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
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @return ResponseFactory
     */
    public function get($filter = [], $sort = [], $search = '')
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("user_sess")
            ->from(get_class($this->getEntityModel()), "user_sess");

        $this->basicFilterSearchSort($qb, $filter, $sort, $search);

        //show
        return new ResponseFactory($qb);
    }

    /**
     * @param array $filter
     * @param string $toArray
     * @param $include
     * @param bool $interrupt
     * @return GeneralUserSession
     * @throws CustomMessagesException
     */
    public function showByBasicFilter(array $filter = [], $toArray = "default", $include='', $interrupt = true)
    {
        return parent::showByBasicFilterContract($filter, $toArray, $include, $interrupt);
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
