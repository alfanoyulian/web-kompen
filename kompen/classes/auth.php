<?php
class Auth{
    private $conn;
    function __construct($db){
        $this->conn = $db->conn;
    }

    public function login($username, $password){
        $dosen = $this->checkUser("tb_dosen", "nip", $username, $password);
        if($dosen) {
            return [
                "role" => "dosen", 
                "location" => "dashboard_dsn.php", 
                "data" => $dosen,
                "message" => "Login Berhasil, Selamat Datang " . $dosen['nama_dsn']
             ];
        }
        $mahasiswa = $this->checkUser("tb_mahasiswa", "npm", $username, $password);
        if($mahasiswa){
            return [
                "role" => "mahasiswa",
                "location" => "dashboard_mhs.php",
                "data" => $mahasiswa,
                "message" => "Login Berhasil, Selamat Datang " . $mahasiswa['nama_mhs']
            ];
        } 
    }

    public function logout(){
        session_unset();
        $_SESSION = array();
         session_destroy();
         header("location: login.php");
    }

    private function checkUser($table, $field, $username, $password){
        $query = $this->conn->prepare("SELECT * FROM $table WHERE $field = ? AND password = ?");
        $query->bind_param("is", $username, $password);
        $query->execute();
        $result = $query->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }
}
?>