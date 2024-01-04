<?php
    header("Content-Type: application/json");
    
    function GET_IMAGES() {
    
        $connection = new mysqli("localhost", "root", "", "face_detection_db");

        if($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $get_image_data_sql = $connection->query("SELECT * FROM face");
        
        if($get_image_data_sql->num_rows > 0) {
            $data = array();

            while ($row = $get_image_data_sql->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode($data);
        } else {
            echo json_encode(["MESSAGE"=>"DATA NOT FOUND"]);
        }

        $connection->close();
    }

    function POST_IMAGE($image_data) {

        $connection = new mysqli("localhost", "root", "", "face_detection_db");

        if($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        if($connection->query("INSERT INTO face (image_base64) VALUES ('$image_data')") == TRUE) {
            echo json_encode(["MESSAGE"=>"DATA SUCCESSFULLY UPLOADED"]);
        } else {
            echo json_encode(["ERROR"=>"ERROR: " . $connection->error]);
        }

        $connection->close();
    }

    switch($_SERVER['REQUEST_METHOD']) {
        case "GET":
            GET_IMAGES();
            break;
        case "POST":
            POST_IMAGE($_POST["upload_image"]);
            break;
        default:
            http_response_code(404);
            echo json_encode(["MESSAGE"=>"ONLY GET AND POST REQUESTS ALLOWED"]);
    }

?>