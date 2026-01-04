<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class ActivityModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('class_id')]
    public $classId;

    #[ModelFieldAttribute('type_id')]
    public $typeId;

    #[ModelFieldAttribute('created_by')]
    public $createdBy;

    #[ModelFieldAttribute('name')]
    public $name;

    #[ModelFieldAttribute('maximum_score')]
    public $maximumScore;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;

    #[ModelFieldAttribute('updated_at')]
    public $updatedAt;
}