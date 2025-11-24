<?php
class Tugas{
    private $conn;
    private $terdaftarObj;
    function __construct($db, $terdaftar){
        $this->conn = $db->conn;
        $this->terdaftarObj = $terdaftar;
    }

    public function allTugas(){
        $query = $this->conn->query("SELECT * FROM tb_tugas");
        $count = $query->num_rows;
        $data = [];
        while($row = $query->fetch_assoc()){
            $data[] = $row;
        }
        return [
            "count"=> $count,
            "data" => $data
        ];
    }

    public function tugasTerdaftar($id_mhs){
        $dataTerdaftar = $this->terdaftarObj->mhsTerdaftar($id_mhs);
        if ($dataTerdaftar["count"] > 0) {
            $tugas = [];
            foreach ($dataTerdaftar["data"] as $row) {
                $id_tugas = $row["id_tugas"];
                $status = $row["status"];
                $query = $this->conn->prepare("SELECT * FROM tb_tugas WHERE id = ?");
                $query->bind_param("i", $id_tugas);
                $query->execute();
                $result = $query->get_result();
        
                if ($detail = $result->fetch_assoc()) {
                    $detail["status"] = $status;
                    $tugas[] = $detail;
                }
            }
            return [
                "count" => count($tugas),
                "data" => $tugas,
                "status" => $status
            ];
        }else{
            return [
                "count" => 0,
                "data" => []
            ];
        }
    }
    public function selesaikanTugas($id_mhs, $id_tugas){
            $tugas_terdaftar = $this->tugasTerdaftar($id_mhs);
            if ($tugas_terdaftar["status"] == "belum") {
                $query = $this->conn->prepare("UPDATE tb_mhs_terdaftar SET status = 'selesai' WHERE id_user = ? AND id_tugas = ?");
                $query->bind_param("ii", $id_mhs, $id_tugas);
                $query->execute();
                return [
                    "message" => "Tugas Selesai",
                ];
            }else{
                return [
                    "message" => "Tugas Sudah Diselesaikan Sebelumnya",
                ];
            }
        }
}
?>