<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Abstracts;

use App\Entities\EntityChangeLog;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping AS ORM;
use ReflectionException;

abstract class AbstractEntities extends AbstractEntitiesSupport
{
    const ARRAY_DEFAULT = "ARRAY_DEFAULT";

    /** @var self */
    protected $original;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $hash_id;

    /** @ORM\PostLoad() */
    public function doOtherStuffOnPostLoad(): void
    {
        $this->original = clone $this;
    }

    /**
     * @var array
     */
    protected $rule;

    public function getTableName(): string
    {
        return $this->em()->getClassMetadata(get_class($this))->getTableName();
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHashId(): string
    {
        return $this->hash_id;
    }

    /**
     * @param array|null $only
     * @return array
     */
    static public function getRule(array $only = null): array
    {
        $rule = static::rule();

        if ($only && is_array($only)) {
            $rule = array_intersect_key($rule, array_flip($only));
        }

        return $rule;
    }

    /**
     * @param array $except
     * @return array
     */
    static public function getRuleExcept(array $except): array
    {
        $rule = static::rule();

        if ($except && is_array($except)) {
            $rule = array_diff_key($rule, array_flip($except));
        }

        return $rule;
    }

    /**
     * @param $entityClassName
     * @param int $digit
     */
    protected function generateHashId($entityClassName, $digit = 8): string
    {
        if (!$this->hash_id) {
            $this->hash_id = createHashId($entityClassName, $digit);
        }
    }

    abstract public function rule(): array;

    abstract public function toArray($arrayType, $include = ''): array;

    abstract public function generateTransformer($arrayType, $include): array;

    abstract public function getParent(): ?AbstractEntities;

    /**
     * @return EntityManagerInterface
     */
    protected function em()
    {
        return app(EntityManagerInterface::class);
    }

    /**
     * @return $this
     * @throws CustomMessagesException
     * @throws ReflectionException
     */
    public function save()
    {
        $data = $this->toArray("default");

        //remove XSS and disallowed html tag
        foreach ($data as $key => $value) {
            $data[$key] = coreHtmlTagAllow(antiXss($value));
        }

        $this->setParameterFromArrayIgnoreError($data);
        $rule = $this->getRule() ? $this->getRule() : [];

        customValidation($data, $rule);

        $this->saveIgnoreRule();

        return $this;
    }

    /**
     * @return $this
     */
    public function saveIgnoreRule()
    {
        $this->em()->persist($this);
        $this->em()->flush();

        $this->saveLog($this->getDiffWithOriginal());

        return $this;
    }

    public function refresh()
    {
        $this->em()->refresh($this);
    }

    /**
     * @return $this|object
     */
    public function softRefresh()
    {
        $this->em()->refresh($this);
        return $this->em()->getRepository(get_class($this))->find($this->getId());
    }

    public function remove()
    {
        $this->em()->remove($this);
        $this->em()->flush();

        $this->saveLog($this->getOriginal()->toArray('for_log'));

        return true;
    }

    public function getOriginal()
    {
        return $this->original;
    }

    public function getDiffWithOriginal()
    {
        return array_diff_assoc($this->toArray('for_log'), $this->getOriginal() ? $this->getOriginal()->toArray('for_log') : []);
    }

    private function saveLog($diff)
    {
        $changeLog = new EntityChangeLog();
        $changeLog->setUser(user());
        $changeLog->setRefTable($this->getTableName());
        $changeLog->setRefId($this->getId());
        $changeLog->setParent($this->getParent());
        $changeLog->setDiff(json_encode($diff));
        $this->em()->persist($changeLog);
        $this->em()->flush();
    }
}
