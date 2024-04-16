<?php
include_once("database/config.php");

$conn = dbConnect("indonesian_regional_db");
$query = "SELECT * FROM provinces";

$result = mysqli_query($conn, $query);

if($result) {
    while($temp = mysqli_fetch_assoc($result)) {
        $datas[] = [
            "province_id" => $temp["id"],
            "province_name" => $temp["name"],
        ];
    }
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($datas);
