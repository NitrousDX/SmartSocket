<?php
class Appliance {
    private $id;
    private $voltage;
    private $watts;
    private $current;
    private $amps;

    public function __construct($voltage, $watts, $current, $amps, $id = null) {
        $this->voltage = $voltage;
        $this->watts = $watts;
        $this->current = $current;
        $this->amps = $amps;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getVoltage() {
        return $this->voltage;
    }

    public function getWatts() {
        return $this->watts;
    }

    public function getCurrent() {
        return $this->current;
    }

    public function getAmps() {
        return $this->amps;
    }

    public function save($pdo) {
        $stmt = $pdo->prepare("INSERT INTO appliances (voltage, watts, current, amps) VALUES (:voltage, :watts, :current, :amps)");
        $stmt->bindParam(':voltage', $this->voltage);
        $stmt->bindParam(':watts', $this->watts);
        $stmt->bindParam(':current', $this->current);
        $stmt->bindParam(':amps', $this->amps);
        return $stmt->execute();
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM appliances WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM appliances");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>