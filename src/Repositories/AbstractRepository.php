<?php

namespace Nsingularity\GeneralModule\Foundation\Repositories;

use App\Http\Responser\AbstractResponse;
use Illuminate\Contracts\Translation\Translator;
use Nsingularity\GeneralModule\Foundation\Entities\AbstractEntities;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
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

    abstract function get(AbstractResponse $responseContract, $criteria = [], $sort = [], $search = '');

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
     * @param array $criteria
     * @param bool $toArray
     * @param string $include
     * @param bool $interrupt
     * @return mixed
     */
    public function show($id, $criteria = [], $toArray = true, $include = '', $interrupt = true)
    {
        $criteria["id"] = $id;

        return $this->showByBasicCriteria($criteria, $toArray, $include, $interrupt);
    }

    /**
     * @param $hashId
     * @param array $criteria
     * @param bool $toArray
     * @param string $include
     * @param bool $interrupt
     * @return AbstractEntities|null
     */
    public function showByHashId($hashId, $criteria = [], $toArray = true, $include = "", $interrupt = true)
    {
        $criteria["hash_id"] = $hashId;

        return $this->showByBasicCriteria($criteria, $toArray, $include, $interrupt);
    }

    /**
     * @param $criteria
     * @param $toArray
     * @param $include
     * @param $interrupt
     * @return mixed
     * @throws CustomMessagesException
     */
    public function showByBasicCriteriaContract($criteria, $toArray, $include, $interrupt)
    {
        $entity = $this->em()->getRepository($this->entityClassName)->findOneBy($criteria);

        if (!$entity && $interrupt) {
            customException(trans($this->errorText . " not found"), false, 404);
        }

        return toArrayOrNot($entity, $toArray, $include);
    }

    abstract function store(array $input, $toArray = "default");

    /**
     * @param array $criteria
     * @param $toArray
     * @param $include
     * @param $interrupt
     * @return mixed
     */
    abstract function showByBasicCriteria(array $criteria, $toArray, $include, $interrupt);


    /**
     * @param AbstractEntities $entity
     * @param array $data
     * @param string $toArray
     * @param $include
     * @return mixed
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    protected function updateEntity(AbstractEntities $entity, array $data, $toArray = "default", $include="")
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
