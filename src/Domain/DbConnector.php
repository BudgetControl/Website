<?php
namespace Mlab\BudetControl\Domain;

use Illuminate\Support\Facades\DB;

/**
 * @ Author: Marco De Felice
 * @ Create Time: 2024-02-01 19:38:05
 * @ Description: db connector generic class
 */

class DbConnector {

    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function connect()
    {
        $this->db->connection();
    }

}