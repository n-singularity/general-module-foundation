<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use App\Http\Responser\AbstractResponse;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\User;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use ReflectionException;


final class UserRepository extends AbstractRepository
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct(new User(), "User");
    }

    /**
     * @param AbstractResponse $responseContract
     * @param array $filter
     * @param array $sort
     * @param string $search
     * @param array $addFunction
     * @return array|User[]
     */
    public function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '', $addFunction = [])
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em()->createQueryBuilder();
        $qb->addSelect("adm")
            ->from(User::class, "adm");

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
     * @return User
     * @throws CustomMessagesException
     */
    public function showByBasicCriteria(array $criteria = [], $toArray = "default", $include='', $interrupt = true)
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
        $entity = new User();
        return $this->update($entity, $data, $toArray);
    }

    /**
     * @param User $entity
     * @param array $data
     * @param string $toArray
     * @return User
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function update(User $entity, array $data, $toArray = "default")
    {
        return parent::updateEntity($entity, $data, $toArray);
    }

    /**
     * @param User $entity
     * @return array|Translator|null|string
     */
    public function delete(User $entity)
    {
        return parent::deleteEntity($entity);
    }
}
