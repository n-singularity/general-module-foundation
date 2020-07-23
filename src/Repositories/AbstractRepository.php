<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Http\Responser\Api\AbstractResponse;
use ReflectionException;

abstract class AbstractRepository extends AbstractFunctionRepository
{
    private $entity          = "";
    private $entityClassName = "";
    private $errorText       = "";

    /**
     * AbstractRepository constructor.
     * @param AbstractEntities $entities
     * @param $errorText
     */
    public function __construct(AbstractEntities $entities, $errorText)
    {
        $this->entity          = $entities;
        $this->entityClassName = get_class($entities);
        $this->errorText       = $errorText;
    }

    abstract function get(AbstractResponse $responseContract, $filter = [], $sort = [], $search = '');

    abstract protected function basicFilterSearchSort(QueryBuilder &$qb, $filter = [], $sort = [], $search = '');

    /**
     * @return AbstractEntities|string
     */
    public function getEntityModel()
    {
        return $this->entity;
    }

    public function filterAllowedInput(array $inputUser = [])
    {
        return array_intersect_key($inputUser, $this->entity->getRule());
    }

    /**
     * @param $id
     * @param array $filter
     * @param bool $toArray
     * @param string $include
     * @param bool $interrupt
     * @return mixed
     */
    public function show($id, $filter = [], $toArray = true, $include = '', $interrupt = true)
    {
        $filter["id"] = $id;
        return $this->showByBasicFilter($filter, $toArray, $include, $interrupt);
    }

    /**
     * @param $hashId
     * @param array $filter
     * @param bool $toArray
     * @param string $include
     * @param bool $interrupt
     * @return mixed
     */
    public function showByHashId($hashId, $filter = [], $toArray = true, $include = "", $interrupt = true)
    {
        $filter["hash_id"] = $hashId;
        return $this->showByBasicFilter($filter, $toArray, $include, $interrupt);
    }

    /**
     * @param $filter
     * @param $toArray
     * @param $include
     * @param $interrupt
     * @return mixed
     * @throws CustomMessagesException
     */
    public function showByBasicFilterContract($filter, $toArray, $include, $interrupt)
    {
        $entity = $this->em()->getRepository($this->entityClassName)->findOneBy($filter);

        if (!$entity && $interrupt) {
            customException(trans($this->errorText . " not found"), false, 404);
        }

        return toArrayOrNot($entity, $toArray, $include);
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
        $entity = clone $this->getEntityModel();
        return $this->updateEntity($entity, $data, $toArray);
    }

    /**
     * @param array $filter
     * @param $toArray
     * @param $include
     * @param $interrupt
     * @return mixed
     */
    abstract function showByBasicFilter(array $filter, $toArray, $include, $interrupt);


    /**
     * @param AbstractEntities $entity
     * @param array $data
     * @param string $toArray
     * @param string $include
     * @return mixed
     * @throws CustomMessagesException
     */
    protected function updateEntity(AbstractEntities $entity, array $data, $toArray = "default", $include = "")
    {
        $entity->setParameterFromArray($data);
        $entity->save();
        return toArrayOrNot($entity, $toArray, $include);
    }

    /**
     * @param AbstractEntities|null $entity
     * @return array|Translator|null|string
     */
    protected function deleteEntity(AbstractEntities $entity = null)
    {
        $this->em()->remove($entity);
        $this->em()->flush();

        return trans("messages.remove_data_succeed");
    }
}
