<?php
namespace App\Attributes;

use \Attribute;

#[Attribute]
class ModelFieldAttribute
{
    public string $columnName;

    public function __construct(string $columnName)
    {
        $this->columnName = $columnName;
    }

    public function toArray(): array
    {
        return [
            'columnName' => $this->columnName,
        ];
    }
}