<?php
include_once("database/config.php");
$conn = dbConnect("indonesian_regional_db");
$province_id = $_GET['province_id'];
$query = "SELECT * FROM regencies WHERE province_id = $province_id";

$result = mysqli_query($conn, $query);

if($result) {
    while($temp = mysqli_fetch_assoc($result)) {
        $datas[] = [
            "regency_id" => $temp["id"],
            "regency_name" => $temp["name"],
        ];
    }
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($datas);
