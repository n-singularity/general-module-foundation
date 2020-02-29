<?php

namespace Nsingularity\GeneralModule\Foundation\Transformers\V1;

namespace Nsingularity\GeneralModule\Foundation\Transformers;

use Nsingularity\GeneralModule\Foundation\Entities\GeneralEntityChangeLog;

trait EntityChangeLogTransformer
{
    use ParentTransformer;

    /**
     * @param GeneralEntityChangeLog $entity
     * @return array
     */
    public function transformerDefault(GeneralEntityChangeLog $entity)
    {
        return [
            "id"         => $entity->getId(),
            "ref_table"  => $entity->getRefTable(),
            "ref_id"     => $entity->getRefId(),
            "change"     => json_decode($entity->getDiff()),
            "created_at" => $entity->formatDateOrNull($entity->getCreatedAt(), "c"),
        ];
    }
}
