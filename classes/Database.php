<?php

class Database {

    private $mysqli;

    public function __construct() {
        include('database_credentials.php'); // where the script starts
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Extra Error Printing
        $this->mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbdatabase);
        //$this->mysqli = new mysqli("localhost", "root", "", "example"); // XAMPP
    }

    public function query($query, $bparam = null, ...$params) {
        $stmt = $this->mysqli->prepare($query);

        if ($bparam != null)
            $stmt->bind_param($bparam, ...$params);

        if (!$stmt->execute()) {
            // execute failed
            return false;
        }

        // execute succeeded
        if (($res = $stmt->get_result()) !== false)
            return $res->fetch_all(MYSQLI_ASSOC);

        return true;
    }
}
