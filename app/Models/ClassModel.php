<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class ClassModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('teacher_id')]
    public $teacherId;

    #[ModelFieldAttribute('created_by')]
    public $createdBy;

    #[ModelFieldAttribute('name')]
    public $name;

    #[ModelFieldAttribute('course_name')]
    public $courseName;

    #[ModelFieldAttribute('school_year')]
    public $schoolYear;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;

    #[ModelFieldAttribute('updated_at')]
    public $updatedAt;
}