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
        $tugas_terdaftar = $this->terdaftarObj->mhsTerdaftar($id_mhs);
        if ($tugas_terdaftar["count"] > 0) {
            $tugas = [];
            foreach ($tugas_terdaftar["data"] as $row) {
                $id_tugas = $row["id_tugas"];
                $status = $row["status"];
                $status_tugas = $row["status_tugas"];
                $query = $this->conn->prepare("SELECT * FROM tb_tugas WHERE id = ?");
                $query->bind_param("i", $id_tugas);
                $query->execute();
                $result = $query->get_result();
        
                if ($detail = $result->fetch_assoc()) {
                    $detail["status"] = $status;
                    $detail["status_tugas"] = $status_tugas;
                    $tugas[] = $detail;
                }
            }
            return [
                "count" => count($tugas),
                "data" => $tugas,
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

    // history Tugas ACC
    public function historyTugas($id_mhs){
        // ambil semua tugas yg terdaftar milik mahasiswa
        $tugas_terdaftar = $this->tugasTerdaftar($id_mhs);

        $tugas_acc = [];
        if ($tugas_terdaftar["count"] > 0) {
            foreach ($tugas_terdaftar["data"] as $tugas) {

                // cukup filter yang status_tugas = acc
                if ($tugas["status_tugas"] === "acc") {
                    $tugas_acc[] = $tugas;
                }
            }
        }

        return [
            "count" => count($tugas_acc),
            "data"  => $tugas_acc
        ];
    }

    // Lanjuts Besok
    public function kurangiJamKompen($id_mhs){
        $tugas_terdaftar = $this->tugasTerdaftar($id_mhs);
        $status_tugas = $tugas_terdaftar["status_tugas"];
        $jam = $tugas_terdaftar["data"]["jumlah_jam"];
        // Update Session Jam Kompen
        // $_SESSION["data"]["jam_kompen"] -= $jam;
        // pastikan tidak minus
        if ($_SESSION["data"]["jam_kompen"] < 0) {
            $_SESSION["data"]["jam_kompen"] = 0;
        }
        // Kurangi jam_kompen pada tabel user
        $query = $this->conn->prepare("UPDATE tb_mahasiswa SET jam_kompen = jam_kompen - ? WHERE id = ?");
        $query->bind_param("ii", $jam, $id_mhs);

        return $query->execute();
    }
}
?>