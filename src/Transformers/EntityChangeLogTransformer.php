<?php

namespace Nsingularity\GeneralModul\Foundation\Transformers\V1;

namespace Nsingularity\GeneralModul\Foundation\Transformers;

use Nsingularity\GeneralModul\Foundation\Entities\EntityChangeLog;

trait EntityChangeLogTransformer
{
    use ParentTransformer;

    /**
     * @param EntityChangeLog $entity
     * @return array
     */
    public function transformerDefault(EntityChangeLog $entity)
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
