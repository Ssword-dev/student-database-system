<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class ScoreModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('activity_id')]
    public $activityId;

    #[ModelFieldAttribute('student_id')]
    public $studentId;

    #[ModelFieldAttribute('created_by')]
    public $createdBy;

    #[ModelFieldAttribute('score')]
    public $score;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;
}