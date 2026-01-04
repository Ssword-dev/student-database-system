<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class StudentModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('class_id')]
    public $classId;

    #[ModelFieldAttribute('created_by')]
    public $createdBy;

    #[ModelFieldAttribute('lrn')]
    public $lrn;

    #[ModelFieldAttribute('first_name')]
    public $firstName;

    #[ModelFieldAttribute('last_name')]
    public $lastName;

    #[ModelFieldAttribute('email')]
    public $email;

    #[ModelFieldAttribute('contact_number')]
    public $contactNumber;

    #[ModelFieldAttribute('guardian')]
    public $guardian;

    #[ModelFieldAttribute('guardian_contact_number')]
    public $guardianContactNumber;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;

    #[ModelFieldAttribute('updated_at')]
    public $updatedAt;
}