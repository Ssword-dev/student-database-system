<?php

namespace App\Models;

use App\Attributes\ModelFieldAttribute;

final class TeacherModel extends BaseModel
{
    #[ModelFieldAttribute('id')]
    public $id;

    #[ModelFieldAttribute('first_name')]
    public $firstName;

    #[ModelFieldAttribute('last_name')]
    public $lastName;

    #[ModelFieldAttribute('email')]
    public $email;

    #[ModelFieldAttribute('contact_number')]
    public $contactNumber;

    #[ModelFieldAttribute('address')]
    public $address;

    #[ModelFieldAttribute('password_hash')]
    public string $passwordHash;

    #[ModelFieldAttribute('created_at')]
    public $createdAt;

    #[ModelFieldAttribute('updated_at')]
    public $updatedAt;
}