<?php
require 'config.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(); 

$app->post('/login','login');
$app->post('/menu','menu');
$app->post('/tambah-menu','tambahMenu');
$app->post('/edit-menu','editMenu');
$app->post('/order-menu','orderMenu');
$app->post('/data-user','dataUser');
$app->post('/favorit','favorit');
//$app->post('/hapus-menu','hapusMenu');
//Kategori
$app->post('/kategori','kategori');
//Tickets
// $app->post('/tickets','tickets');
$app->post('/new-tickets','newTickets');
$app->post('/tambah-tickets','tambahTickets');
$app->post('/edit-tickets','editTickets');
$app->post('/kategori-tickets','kategoriTickets');
//Customers
$app->post('/login-cust','loginCust');
$app->post('/data-customers','dataCustomers');//terbaru
$app->post('/edit-customers','editCustomers');//terbaru 
$app->post('/registration-customers','registrationCustomers');//terbaru
//Technisi
$app->post('/login-tech','loginTech');
$app->post('/technisi','technisi');//terbaru 
$app->post('/edit-technisi','editTechnisi');//terbaru 
//Banners
$app->post('/banners','Banners');
//Signup
$app->post('/signup','signup');
//Message
$app->post('/message','message');
$app->run();
///=====================================================================================
function signup() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $telp=$data->telp;
    $email=$data->email;
    $password=md5($data->password);
    $first_name=$data->first_name;
    $last_name=$data->last_name;
    $status=$data->status;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            if($status == 'customer'){
                $sql1 = "SELECT email_cust FROM customers WHERE (email_cust=:email)";
            } else {
                $sql1 = "SELECT email_tech FROM technisi WHERE (email_tech=:email)";
            }
            $stmt1 = $db->prepare($sql1);//var_dump($stmt1);exit();
            $stmt1->bindParam("email", $email, PDO::PARAM_STR);
            $stmt1->execute();
            $mainCount=$stmt1->rowCount();
            if($mainCount) {
                echo '{"message":  "Email sudah terdaftar, tolong gunakan email lain!", "status":{ "code": 400}}';
                exit();
            } 
            if($status == 'customer'){
                $sql = "INSERT INTO customers (telp_cust, email_cust, password, nama_cust, nama_akhir, status) VALUES (:telp, :email, :password, :first_name, :last_name, 'admin')";   
                //$sql = "INSERT INTO customers ( email_cust, password) VALUES (:email, :password)";   
            
            } else {
                $sql = "INSERT INTO technisi (telp_tech, email_tech, password, nama_tech, nama_akhir, status) VALUES (:telp, :email, :password, :first_name, :last_name, 'user')";
                //$sql = "INSERT INTO technisi (email_tech, password) VALUES (:email, :password)";
            
            }
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("telp", $telp, PDO::PARAM_STR); 
            $stmt->bindParam("email", $email, PDO::PARAM_STR);  
            $stmt->bindParam("password", $password, PDO::PARAM_STR); 
            $stmt->bindParam("first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindParam("last_name", $last_name, PDO::PARAM_STR);   
            $data = $stmt->execute();
            header('Content-Type: application/json');
            if($data){
                 echo '{"message":  "Registrasi Berhasil", "status":{ "code": 200}}';
             } else {
                echo '{"error":{"message":"Registrasi gagal, tolong periksa kembali data anda!"}, "status":{ "code": 400}}';
             } 
        
    }
    else {
            echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
        }
    }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
}
// Fungsi untuk login
function login() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $username=$data->username;
    $password=$data->password;
    try {
        if($require_code == $require_code_data) {
        $db = getDB();
        $userData ='';
        $sql = "SELECT id_user, username, nama_lengkap, status FROM user WHERE (username=:username AND password=:password)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $data->username, PDO::PARAM_STR);
        $password=md5($data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        if(!empty($userData))
        {
            $id_user=$userData->id_user;
            $userData->token = apiToken($id_user);
        }
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"user": ' .$userData . ', "status":{ "code": 200}}';
            } else {
               echo '{"error":{"message":"Kesalahan pada username atau password"}, "status":{ "code": 400}}';
            } 
    }
    else {
            echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
        }
    }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
}

// Fungsi untuk mendapatkan menu
function menu(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT mn.id_menu, mn.nama, mn.harga, mn.foto_menu, mn.keterangan, mn.id_kategori, kt.nama_kategori
                    FROM kategori AS kt LEFT JOIN menu AS mn ON kt.id_kategori = mn.id_kategori WHERE kt.id_kategori = :id_kategori ORDER BY mn.id_menu DESC";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_menu']= $d->id_menu;
                $json2['nama']= $d->nama;
                $json2['harga']= $d->harga;
                $json2['keterangan']= $d->keterangan;
                $json2['foto_menu']= $d->foto_menu;
                $json2['id_kategori']= $d->id_kategori;
                $json2['nama_kategori']= $d->nama_kategori;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk menambahkan data menu
function tambahMenu(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $nama=$data->nama;
    $harga=$data->harga;
    $keterangan=$data->keterangan;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "INSERT INTO menu (nama, harga, keterangan, id_kategori) VALUES (:nama, :harga, :keterangan, :id_kategori)";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("nama", $nama, PDO::PARAM_STR);  
            $stmt->bindParam("harga", $harga, PDO::PARAM_STR);  
            $stmt->bindParam("keterangan", $keterangan, PDO::PARAM_STR);  
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $sql1 = "SELECT nama FROM menu ORDER BY id_menu DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['nama']= $d->nama;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk edit data menu
function editMenu(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_menu=$data->id_menu;
    $nama=$data->nama;
    $harga=$data->harga;
    $keterangan=$data->keterangan;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "UPDATE menu SET 
                    nama = :nama, harga = :harga, keterangan = :keterangan, id_kategori = :id_kategori
                    WHERE id_menu = :id_menu";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_menu", $id_menu, PDO::PARAM_STR);  
            $stmt->bindParam("nama", $nama, PDO::PARAM_STR);  
            $stmt->bindParam("harga", $harga, PDO::PARAM_STR);  
            $stmt->bindParam("keterangan", $keterangan, PDO::PARAM_STR);  
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $sql1 = "SELECT nama FROM menu WHERE id_menu = :id_menu";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->bindParam("id_menu", $id_menu, PDO::PARAM_STR);  
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['nama']= $d->nama;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk mendapatkan kategori
function kategori(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT id_kategori, nama_kategori FROM kategori";
            $stmt = $db->prepare($sql); 
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_kategori']= $d->id_kategori;
                $json2['nama_kategori']= $d->nama_kategori;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk melakukan order menu
function orderMenu(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    //parameter untuk menambahkan data kedalam table order_menu
    $id_user=$data->id_user;
    $status = "menunggu";
    date_default_timezone_set('Asia/Jakarta');
    $datetime = date('Y-m-d H:i:s');
    //parameter untuk menambahkan data kedalam table data_order
    $id_menu=$data->id_menu;
    $kuantitas=$data->kuantitas;
    $total_harga=$data->total_harga;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            
            $sql = "INSERT INTO order_menu (id_user, status, datetime) VALUES (:id_user, :status, :datetime)";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_user", $id_user, PDO::PARAM_STR);  
            $stmt->bindParam("status", $status, PDO::PARAM_STR);  
            $stmt->bindParam("datetime", $datetime, PDO::PARAM_STR);  
            $stmt->execute();
            
            $sql1 = "SELECT id_order_menu FROM order_menu WHERE id_user = :id_user ORDER BY id_order_menu DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->bindParam("id_user", $id_user, PDO::PARAM_STR);  
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
            $id_order_menu = $data[0]->id_order_menu;

            $sql2 = "INSERT INTO data_order (id_order_menu, id_menu, kuantitas, total_harga) VALUES (:id_order_menu, :id_menu, :kuantitas, :total_harga)";
            $stmt2 = $db->prepare($sql2); 
            $stmt2->bindParam("id_order_menu", $id_order_menu, PDO::PARAM_STR);  
            $stmt2->bindParam("id_menu", $id_menu, PDO::PARAM_STR);  
            $stmt2->bindParam("kuantitas", $kuantitas, PDO::PARAM_STR);  
            $stmt2->bindParam("total_harga", $total_harga, PDO::PARAM_STR);  
            $stmt2->execute();

            $stmt3 = $handler->prepare("SELECT SUM(total_harga) AS total_bayar FROM data_order WHERE id_user = :id_user AND id_order_menu = :id_order_menu");
            $stmt3->bindParam("id_user", $id_user, PDO::PARAM_STR); 
            $stmt3->bindParam("id_order_menu", $id_order_menu, PDO::PARAM_STR); 
            $stmt3->execute();
            $jumlah_pembayran = $handler->fetchAll(PDO::FETCH_OBJ);
            $total = $jumlah_pembayran->total_bayar;

        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);

        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk mendapatkan data user
function dataUser(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT id_user, username, nama_lengkap, status FROM user WHERE status = 'user'";
            $stmt = $db->prepare($sql); 
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_user']= $d->id_user;
                $json2['username']= $d->username;
                $json2['nama_lengkap']= $d->nama_lengkap;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk mendapatkan menu favorit
function favorit(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
        
            $sql = "SELECT mn.id_menu, mn.nama, mn.harga, mn.keterangan, mn.foto_menu 
                    FROM menu AS mn LEFT JOIN menu_favorit AS mf ON mn.id_menu = mf.id_menu WHERE mf.status = 'aktif'";
            $stmt = $db->prepare($sql); 
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                $json = array();
                $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_menu']= $d->id_menu;
                $json2['nama']= $d->nama;
                $json2['harga']= $d->harga;
                $json2['keterangan']= $d->keterangan;
                $json2['foto_menu']= $d->foto_menu;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Fungsi untuk mendapatkan menu
function tickets(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT tc.id_tickets, tc.address, tc.lokasi, tc.no_rumah, tc.masalah, tc.desk_masalah, tc.id_kategori, kt.nama_kategori
            FROM kategori_tickets AS kt LEFT JOIN tickets AS tc ON kt.id_kategori_tickets = tc.id_kategori WHERE kt.id_kategori_tickets = :id_kategori ORDER BY tc.id_tickets DESC";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_tickets']= $d->id_tickets;
                $json2['address']= $d->address;
                $json2['lokasi']= $d->lokasi;
                $json2['no_rumah']= $d->no_rumah;
                $json2['masalah']= $d->masalah;
                $json2['desk_masalah']= $d->desk_masalah;
                $json2['id_kategori']= $d->id_kategori;
                $json2['nama_kategori']= $d->nama_kategori;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
//fungsi untuk melihat tickets berdasarkan ID
function newTickets(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_cust=$data->id_cust;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT tc.id_tickets, tc.address, tc.lokasi, tc.no_rumah, tc.masalah, tc.desk_masalah, tc.id_kategori, c.id_cust, c.nama_cust
            FROM kategori AS kt LEFT JOIN tickets AS tc ON kt.id_kategori = tc.id_kategori 
            LEFT JOIN customers AS c ON tc.cust_id = c.id_cust
            WHERE tc.cust_id = :id_cust ORDER BY tc.id_tickets DESC";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_cust", $id_cust, PDO::PARAM_STR);  
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_tickets']= $d->id_tickets;
                $json2['lokasi']= $d->lokasi;
                $json2['no_rumah']= $d->no_rumah;
                $json2['masalah']= $d->masalah;
                $json2['desk_masalah']= $d->desk_masalah;
                $json2['id_kategori']= $d->id_kategori;
                $json2['id_cust']= $d->id_cust;
                $json2['nama_cust']= $d->nama_cust;
                
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk menambahkan data menu
function tambahTickets(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $lokasi=$data->lokasi;
    $no_rumah=$data->no_rumah;
    $masalah=$data->masalah;
    $desk_masalah=$data->desk_masalah;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "INSERT INTO tickets (lokasi, no_rumah, masalah, desk_masalah, id_kategori) VALUES (:lokasi, :no_rumah, :masalah, :desk_masalah, :id_kategori)";
            $stmt = $db->prepare($sql);  
            $stmt->bindParam("lokasi", $lokasi, PDO::PARAM_STR);  
            $stmt->bindParam("no_rumah", $no_rumah, PDO::PARAM_STR); 
            $stmt->bindParam("masalah", $masalah, PDO::PARAM_STR);  
            $stmt->bindParam("desk_masalah", $desk_masalah, PDO::PARAM_STR);  
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $sql1 = "SELECT lokasi FROM tickets ORDER BY id_tickets DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['lokasi']= $d->lokasi;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Fungsi untuk edit data menu
function editTickets(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_tickets=$data->id_tickets;
    $lokasi=$data->lokasi;
    $no_rumah=$data->no_rumah;
    $masalah=$data->masalah;
    $desk_masalah=$data->desk_masalah;    
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "UPDATE tickets SET 
                    lokasi = :lokasi, no_rumah = :no_rumah, masalah = :masalah, desk_masalah = :desk_masalah, id_kategori = :id_kategori
                    WHERE id_tickets = :id_tickets";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_tickets", $id_tickets, PDO::PARAM_STR); 
            $stmt->bindParam("lokasi", $lokasi, PDO::PARAM_STR);  
            $stmt->bindParam("no_rumah", $no_rumah, PDO::PARAM_STR);
            $stmt->bindParam("masalah", $masalah, PDO::PARAM_STR);  
            $stmt->bindParam("desk_masalah", $desk_masalah, PDO::PARAM_STR);
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $sql1 = "SELECT lokasi FROM tickets WHERE id_tickets = :id_tickets";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->bindParam("id_tickets", $id_tickets, PDO::PARAM_STR);  
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['lokasi']= $d->lokasi;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk mendapatkan kategori
function kategoriTickets(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT id_kategori_tickets, nama_kategori FROM kategori_tickets";
            $stmt = $db->prepare($sql); 
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_kategori_tickets']= $d->id_kategori_tickets;
                $json2['nama_kategori']= $d->nama_kategori;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function loginCust() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $email_cust=$data->email_cust;
    $password=$data->password;
    try {
        if($require_code == $require_code_data) {
        $db = getDB();
        $userData ='';
        $sql = "SELECT id_cust, email_cust, nama_cust, status FROM customers WHERE (email_cust=:email_cust AND password=:password)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email_cust", $data->email_cust, PDO::PARAM_STR);
        $password=md5($data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        if(!empty($userData))
        {
            $id_cust=$userData->id_cust;
            $userData->token = apiToken($id_cust);
        }
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"user": ' .$userData . ', "status":{ "code": 200}}';
            } else {
               echo '{"error":{"message":"Kesalahan pada email atau password"}, "status":{ "code": 400}}';
            } 
    }
    else {
            echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
        }
    }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
}

// Fungsi untuk edit data menu
function editCustomers(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_cust=$data->id_cust;
    $nama_cust=$data->nama_cust;
    $nama_akhir=$data->nama_akhir;
    $tahun_lahir=$data->tahun_lahir;
    $telp_cust=$data->telp_cust;
    $home_address=$data->home_address;
    $home_no=$data->home_no;
    $office_address=$data->office_address;
    $office_no=$data->office_no;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "UPDATE customers SET 
                    id_cust = :id_cust, nama_cust = :nama_cust, nama_akhir = :nama_akhir, tahun_lahir = :tahun_lahir, telp_cust = :telp_cust, home_address = :home_address, home_no = :home_no, office_address = :office_address, office_no = :office_no
                    WHERE id_cust = :id_cust";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_cust", $id_cust, PDO::PARAM_STR);  
            $stmt->bindParam("nama_cust", $nama_cust, PDO::PARAM_STR);
            $stmt->bindParam("nama_akhir", $nama_akhir, PDO::PARAM_STR);
            $stmt->bindParam("tahun_lahir", $tahun_lahir, PDO::PARAM_STR);  
            $stmt->bindParam("telp_cust", $telp_cust, PDO::PARAM_STR);
            $stmt->bindParam("home_address", $home_address, PDO::PARAM_STR);  
            $stmt->bindParam("home_no", $home_no, PDO::PARAM_STR);
            $stmt->bindParam("office_address", $office_address, PDO::PARAM_STR);
            $stmt->bindParam("office_no", $office_no, PDO::PARAM_STR);   
            $stmt->execute();
            $sql1 = "SELECT nama_cust FROM customers WHERE id_cust = :id_cust";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->bindParam("id_cust", $id_cust, PDO::PARAM_STR);  
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['nama_cust']= $d->nama_cust;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Fungsi untuk mendapatkan data user
function dataCustomers(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT id_cust, nama_cust, email_cust, tahun_lahir, telp_cust, home_address, home_no, office_address, office_no, status FROM customers WHERE status = 'admin'";
            $stmt = $db->prepare($sql); 
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_cust']= $d->id_cust;
                $json2['nama_cust']= $d->nama_cust;
                $json2['email_cust']= $d->email_cust;
                $json2['tahun_lahir']= $d->tahun_lahir;
                $json2['telp_cust']= $d->telp_cust;
                $json2['home_address']= $d->home_address;
                $json2['home_no']= $d->home_no;
                $json2['office_address']= $d->office_address;
                $json2['office_no']= $d->office_no;

            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// Fungsi untuk login Technisi
function loginTech() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $email_tech=$data->email_tech;
    $password=$data->password;
    try {
        if($require_code == $require_code_data) {
        $db = getDB();
        $userData ='';
        $sql = "SELECT id_tech, email_tech, nama_tech, status FROM technisi WHERE (email_tech=:email_tech AND password=:password)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email_tech", $data->email_tech, PDO::PARAM_STR);
        $password=md5($data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        if(!empty($userData))
        {
            $id_tech=$userData->id_tech;
            $userData->token = apiToken($id_tech);
        }
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"user": ' .$userData . ', "status":{ "code": 200}}';
            } else {
               echo '{"error":{"message":"Kesalahan pada email atau password"}, "status":{ "code": 400}}';
            } 
    }
    else {
            echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
        }
    }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
}
// Fungsi untuk mendapatkan data registrasi
function registrationCustomers(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $telp_cust=$data->telp_cust;
    $email_cust=$data->email_cust;
    $password=$data->password;
    $status=$data->status;
    try {
                $data = '';
                $db = getDB();
                $sql = "INSERT INTO customers (telp_cust, email_cust, password, status ) VALUES (:telp_cust, :email_cust, :password, :status )";
                $stmt = $db->prepare($sql); 
                $stmt->bindParam("telp_cust", $telp_cust, PDO::PARAM_STR);  
                $stmt->bindParam("email_cust", $email_cust, PDO::PARAM_STR);  
                $stmt->bindParam("password", $password, PDO::PARAM_STR);  
                $stmt->bindParam("status", $status, PDO::PARAM_STR);  
                $stmt->execute();
                $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                        $json = array();
                        $json['data'] = array();
                foreach($data as $d){
                    $json2 = array();
                    $json2['email_cust']= $d->email_cust;
                array_push($json['data'], $json2);
                }
            $db = null;
            header('Content-Type: application/json');
            if($data) {
                $json['status']= '200';
                echo json_encode($json);
            } else {  
                $json['status']= '404';
                echo json_encode($json);
            }

        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    };
}


// Fungsi untuk mendapatkan data Technisi
function Technisi(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_kategori=$data->id_kategori;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT te.id_tech, te.nama_tech, te.email_tech, te.telp_tech, te.pengalaman, te.harga, te.work_address, te.desk_tech, te.id_kategori, kt.nama_kategori 
                    FROM kategori AS kt LEFT JOIN technisi AS te ON kt.id_kategori = te.id_kategori WHERE kt.id_kategori = :id_kategori ORDER BY te.id_tech DESC";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_kategori", $id_kategori, PDO::PARAM_STR);  
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_tech']= $d->id_tech;
                $json2['nama_tech']= $d->nama_tech;
                $json2['email_tech']= $d->email_tech;
                $json2['telp_tech']= $d->telp_tech;
                $json2['pengalaman']= $d->pengalaman;
                $json2['harga']= $d->harga;
                $json2['work_address']= $d->work_address;
                $json2['desk_tech']= $d->desk_tech;
                $json2['id_kategori']= $d->id_kategori;
                $json2['nama_kategori']= $d->nama_kategori;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function editTechnisi(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    $id_tech=$data->id_tech;
    $nama_tech=$data->nama_tech;
    $nama_akhir=$data->nama_akhir;
    $tahun_lahir=$data->tahun_lahir;
    $telp_tech=$data->telp_tech;
    $pengalaman=$data->pengalaman;
    $harga=$data->harga;
    $desk_tech=$data->desk_tech;
    $work_address=$data->work_address;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "UPDATE technisi SET 
                    id_tech = :id_tech, nama_tech = :nama_tech, nama_akhir = :nama_akhir, tahun_lahir = :tahun_lahir, pengalaman = :pengalaman, harga = :harga, telp_tech = :telp_tech, desk_tech = :desk_tech, work_address = :work_address
                    WHERE id_tech = :id_tech";
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_tech", $id_tech, PDO::PARAM_STR);  
            $stmt->bindParam("nama_tech", $nama_tech, PDO::PARAM_STR);
            $stmt->bindParam("nama_akhir", $nama_akhir, PDO::PARAM_STR);
            $stmt->bindParam("tahun_lahir", $tahun_lahir, PDO::PARAM_STR);  
            $stmt->bindParam("telp_tech", $telp_tech, PDO::PARAM_STR);             
            $stmt->bindParam("pengalaman", $pengalaman, PDO::PARAM_STR);
            $stmt->bindParam("harga", $harga, PDO::PARAM_STR);
            $stmt->bindParam("desk_tech", $desk_tech, PDO::PARAM_STR);
            $stmt->bindParam("work_address", $work_address, PDO::PARAM_STR);   
            $stmt->execute();
            $sql1 = "SELECT nama_tech FROM technisi WHERE id_tech = :id_tech";
            $stmt1 = $db->prepare($sql1); 
            $stmt1->bindParam("id_tech", $id_tech, PDO::PARAM_STR);  
            $stmt1->execute();
            $data = $stmt1->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['nama_tech']= $d->nama_tech;
            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


// Fungsi untuk mendapatkan data user
function Banners(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $require_code_data = 'pm-fmb-apps';
    $require_code=$data->require_code;
    try {
        if($require_code == $require_code_data) {
            $data = '';
            $db = getDB();
            $sql = "SELECT id_banners, nama_banners, imageBanners, url FROM banners WHERE id_banners = :id_banners";
            $stmt = $db->prepare($sql); 
            $stmt = bindParam("id_banners", $id_banners, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                    $json = array();
                    $json['data'] = array();
            foreach($data as $d){
                $json2 = array();
                $json2['id_banners']= $d->id_banners;
                $json2['nama_banners']= $d->nama_banners;
                $json2['imageBanners']= $d->imageBanners;
                $json2['url']= $d->url;

            array_push($json['data'], $json2);
            }
        $db = null;
        header('Content-Type: application/json');
        if($data) {
            $json['status']= '200';
            echo json_encode($json);
        } else {  
            $json['status']= '404';
            echo json_encode($json);
        }
        }
        else {
            $json['status']= 'FORBIDDEN ACCESS';
            echo json_encode($json);
        }
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
// Fungsi untuk message
// function message() {
//     $request = \Slim\Slim::getInstance()->request();
//     $data = json_decode($request->getBody());
//     $require_code_data = 'pm-fmb-apps';
//     $require_code=$data->require_code;
//     $kategori=$data->kategori;
//     try {
//         if($require_code == $require_code_data) {
//             $datax = '';
//             $db = getDB();
//             $sql1 = "SELECT * FROM message WHERE kategori=:kategori";
//             $stmt1 = $db->prepare($sql1);//var_dump($stmt1);exit();
//             $stmt1->bindParam("kategori", $kategori, PDO::PARAM_STR);
//             $stmt1->execute();
//             $datax = $stmt1->fetchAll(PDO::FETCH_OBJ);
//             header('Content-Type: application/json');
//             if($datax){
//                 $datax = json_encode($datax);
//                 //var_dump($datax);exit();
//                 echo '{"data":  '.$datax.', "status":{ "code": 200}}';
//              } else {
//                 echo '{"error":{"message":"Data tidak ditemukan!"}, "status":{ "code": 400}}';
//              } 
        
//     }
//     else {
//             echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
//         }
//     }
//         catch(PDOException $e) {
//             echo '{"error":{"text":'. $e->getMessage() .'}}';
//         }
//     }

    function message() {
        $request = \Slim\Slim::getInstance()->request();
        $data = json_decode($request->getBody());
        $require_code_data = 'pm-fmb-apps';
        $require_code=$data->require_code;
        $id_kategori=$data->id_kategori;
        try {
            if($require_code == $require_code_data) {
                $datax = '';
                $db = getDB();
                $sql1 = "SELECT * FROM message WHERE id_kategori=:id_kategori";
                $stmt1 = $db->prepare($sql1);//var_dump($stmt1);exit();
                $stmt1->bindParam("id_kategori", $id_kategori, PDO::PARAM_INT);
                $stmt1->execute();
                $datax = $stmt1->fetchAll(PDO::FETCH_OBJ);
                header('Content-Type: application/json');
                if($datax){
                    $datax = json_encode($datax);
                    echo '{"data":  '.$datax.', "status":{ "code": 200}}';
                 } else {
                    echo '{"error":{"message":"Data tidak ditemukan!"}, "status":{ "code": 400}}';
                 } 
            
        }
        else {
                echo '{"error":{"message":"Anda tidak memiliki izin untuk mengakses login"}}';
            }
        }
            catch(PDOException $e) {
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
    }
?>
