<?php
require_once __DIR__ . '/../conf/PersistentManager.php';

abstract class GenericDAO {
    protected $conn;

    public function __construct() {
        $this->conn = PersistentManager::getInstance()->get_connection();
    }
}
?>
