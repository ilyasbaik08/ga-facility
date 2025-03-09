<?php
class Connection
{
    public function openConnection()
    {
        $hostname = "127.0.0.1";
        $username = "root";
        $password = "";
        $database = "ga_facility";

        $conn = new mysqli($hostname, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
