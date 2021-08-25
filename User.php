<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //update user
        include 'DatabaseConfig.php';
        $conn = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);        
        
        //read JSON from client
        $json = file_get_contents('php://input', true);
        $obj = json_decode($json);

        //get JSON object
        $user_id = $obj->id;
        $fullname = $obj->user_fullname;
        $email = $obj->user_email;
        $password = $obj->user_password;

        $query_update= "update user_detail set user_fullname = '$fullname',
                                               user_email = '$email',
                                               user_password = '$password'
                                           where id = '$user_id'";

        $query = mysqli_query($conn, $query_update);
        $check = mysqli_affected_rows($conn);
        $json_array = array();
        $response = "";

        if ($check > 0) {
            $response = array(
                'code' => 200,
                'status' => 'Data sudah diperbaharui!'
            );
        } else {
            $response = array(
                'code' => 400,
                'status' => 'Gagal diperbaharui!'
            );
        }

        print(json_encode($response));
        mysqli_close($conn);

    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //select spesific user
        include 'DatabaseConfig.php';
        $conn = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

        $user_id = $_GET['id'];

        $query_update = "select * from user_detail where id = '$user_id'";
        $result = mysqli_fetch_array(mysqli_query($conn, $query_update)); 
        $json_array = array();
        $response = "";

        if (isset($result)) {
            $data = mysqli_query($conn, $query_update);
            while ($row = mysqli_fetch_assoc($data)) {
                $json_array = $row;
            }
            $response = array(
                'code' => 200,
                'status' => 'Sukses',
                'user_list' => $json_array
            );
        } else {
            $response = array(
                'code' => 404,
                'status' => 'Data tidak ditemukan!',
                'user_list' => $json_array
            );
        }
        print(json_encode($response));
        mysqli_close($conn);

    } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        //delete spesific user
        include 'DatabaseConfig.php';
        $conn = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

        $user_id = $_GET['id'];

        $query_delete = "delete from user_detail where id = '$user_id'";
        $result = mysqli_query($conn, $query_delete);
        $check = mysqli_affected_rows($conn);
        $json_array = array();
        $response = "";

        if ($check > 0) {
            $response = array(
                'code' => 200,
                'status' => 'Data terhapus!'
            );
        } else {
            $response = array(
                'code' => 404,
                'status' => 'Gagal dihapus!'
            );
        }
        print(json_encode($response));
        mysqli_close($conn);
    }
?>