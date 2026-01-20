<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DbTest extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        if ($db->connect()) {
            echo "Database Connected Successfully ✅";
        } else {
            echo "Database Connection Failed ❌";
        }
    }
}
