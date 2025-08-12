<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use mysqli;

class AparaDbService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            config('database.connections.apara.host'),
            config('database.connections.apara.username'),
            config('database.connections.apara.password'),
            config('database.connections.apara.database')
        );

        if ($this->connection->connect_error) {
            throw new \Exception("APARA DB Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getApplicationData($applicationNo)
    {
        $stmt = $this->connection->prepare("
            SELECT 
                a.application_no,
                a.customer_name,
                a.customer_address,
                a.id_no,
                b.bank_name as applicant_bank_name,
                b.branch_name
            FROM application a
            JOIN bank b ON a.bank_id = b.bank_id
            WHERE a.application_no = ?
        ");
        
        $stmt->bind_param("s", $applicationNo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}