<?php
class Terdaftar{
    private $conn;
    function __construct($db){
        $this->conn = $db->conn;
    }
    
    public function mhsTerdaftar($id){
        $query = $this->conn->prepare("SELECT * FROM tb_mhs_terdaftar WHERE id_user = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        $count = $result->num_rows;
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return [
            "count"=> $count,
            "data"=> $data
        ];
    }

    public function terdaftar($id_user, $id_tugas){
        $query = $this->conn->prepare("SELECT * FROM tb_mhs_terdaftar WHERE id_user = ? AND id_tugas = ?");
        $query->bind_param("ii", $id_user, $id_tugas);
        $query->execute();
        $result = $query->get_result();
        $count = $result->num_rows;
        $row = $result->fetch_assoc();
        return [
            "count"=> $count,
            "data"=> $row
        ];
    }
    
    public function countTerdaftar($id_tugas){
        $query = $this->conn->prepare("SELECT * FROM tb_mhs_terdaftar WHERE id_tugas = ?");
        $query->bind_param("i", $id_tugas);
        $query->execute();
        $result = $query->get_result();
        $count = $result->num_rows;
        return [
            "count"=> $count,
        ];
    }

public function daftarKegiatan($id_user, $id_tugas){
    $row = $this->terdaftar($id_user, $id_tugas);
    if ($row['count'] == 0) {
        $query = $this->conn->prepare("INSERT INTO tb_mhs_terdaftar (id_user, id_tugas) VALUES(?, ?)");
        $query->bind_param("ii", $id_user, $id_tugas);
        $query->execute();
        return [
            "message" => "Berhasil Mendaftar Kegiatan",
            "status" => true
        ];
    } else {
        return [
            "message" => "Anda Sudah Mendaftar Kegiatan",
            "status" => false
        ];
    }
}
}

?>