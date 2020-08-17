<?php

namespace Nsingularity\GeneralModule\Foundation\Entities\Abstracts;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;


abstract class AbstractEntitiesSupport
{
    public function setParameterFromArray($input)
    {

        foreach ($input as $key => $value) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = str_replace(" ", "", $key);

            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param $input
     * @throws ReflectionException
     */
    public function setParameterFromArrayIgnoreError($input)
    {
        foreach ($input as $key => $value) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = str_replace(" ", "", $key);

            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $reflex        = new ReflectionClass(get_class($this));
                $reflectMethod = $reflex->getMethod($method);

                if (isset($reflectMethod->getParameters()[0])) {
                    if ($reflectMethod->getParameters()[0]->getType() == gettype($value)) {
                        $this->$method($value);
                    }
                }
            }
        }
    }

    public function formatDateOrNull($dateTime, $format = "Y-m-d H:i:s")
    {
        /** @var DateTime $dateTime */
        return $dateTime ? $dateTime->format($format) : null;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function em()
    {
        return app(EntityManagerInterface::class);
    }
}
