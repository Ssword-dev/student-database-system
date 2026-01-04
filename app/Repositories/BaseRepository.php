<?php
namespace App\Repositories;

use App\Database;
use App\Traits\Singleton;

abstract class BaseRepository
{
    use Singleton;
    protected ?Database $db = null;

    /**
     * Gets the singleton instance and initializes properties
     */

    protected static function initInstance(BaseRepository $instance): void
    {
        $instance->db = Database::getInstance();
    }
}