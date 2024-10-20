<?php
header('Content-Type: application/json');

class PriceController {
    private $connCtrl;

    
    public function __construct($conn) {
        $this->connCtrl = $conn; // Menginisialisasi koneksi
    }
    
    public function insertPrice($timesId,  $harga, $diskon, $total, $hargaMember, $diskonMember, $totalMember) {
       $res = false;

       if($this->insertNormal($timesId, $harga, $diskon, $total)){
            if($this->insertMember($timesId, $hargaMember, $diskonMember, $totalMember)){
                $res = true;
            }
       }

        return  $res;
            
    }

    private function insertNormal($timesId, $harga, $diskon, $total){
        $res = false;
               // Prepare and execute SQL statement
                $insertQuery = 'INSERT INTO normal_price (times_id, harga, diskon, total ) VALUES (?, ?, ?, ?)';
                if ($stmt = $this->connCtrl->prepare($insertQuery)) {
                    $stmt->bind_param('iiii', $timesId, $harga, $diskon, $total);
                    if ($stmt->execute()) {
                     $res = true;


                    } 
                    $stmt->close();
                } 
                return $res;
    }
    private function insertMember($timesId, $hargaMember, $diskonMember, $totalMember){
        $res = false;
               // Prepare and execute SQL statement
                $insertQuery = 'INSERT INTO member_price (times_id, harga, diskon, total ) VALUES (?, ?, ?, ?)';
                if ($stmt = $this->connCtrl->prepare($insertQuery)) {
                    $stmt->bind_param('iiii', $timesId, $hargaMember, $diskonMember, $totalMember);
                    if ($stmt->execute()) {
                     $res = true;
                    } 
                    $stmt->close();
                } 
                return $res;
    }
}


?>
