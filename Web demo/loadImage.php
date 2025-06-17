<?php  
// Lấy tên file từ tham số GET và loại bỏ các ký tự không hợp lệ
$file_name = basename($_GET['file_name']); // basename() chỉ trả về tên file mà không có phần đường dẫn

// Đường dẫn thư mục hình ảnh
$file_path = '/var/www/html/images/' . $file_name; 

// Kiểm tra xem file có tồn tại và là file thực sự không (đảm bảo không phải là thư mục hoặc liên kết tượng trưng)
if (file_exists($file_path) && is_file($file_path)) {
    header('Content-Type: image/png');
    readfile($file_path);
}
else { 
    // Trường hợp file không tồn tại
    echo "404 Not Found";
}
?>
