
<?php
// Kết nối với database
$conn = new mysqli("localhost", "username", "password", "database_name");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy các tham số từ yêu cầu AJAX
$collections = explode(",", $_POST['collections']);
$colors = explode(",", $_POST['colors']);

// Tạo câu truy vấn SQL
$sql = "SELECT p.ID, p.Name, p.Price, p.Description, p.Image 
        FROM Product p
        JOIN Collection c ON p.Collection_ID = c.Collect_ID
        JOIN Color co ON p.Color_ID = co.Color_ID
        WHERE c.Collect_ID IN (" . implode(",", $collections) . ")
        AND co.Color_ID IN (" . implode(",", $colors) . ")";

// Thực hiện truy vấn và lấy kết quả
$result = $conn->query($sql);
$products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Trả về kết quả dưới dạng JSON
echo json_encode($products);

// Đóng kết nối
$conn->close();
?>
