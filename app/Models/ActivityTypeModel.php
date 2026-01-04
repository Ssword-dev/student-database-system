<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class ActivityTypeModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('class_id')]
    public $classId;

    #[ModelFieldAttribute('created_by')]
    public $createdBy;

    #[ModelFieldAttribute('name')]
    public $name;

    #[ModelFieldAttribute('weight')]
    public $weight;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;
}