**Báo cáo Phân tích Lý thuyết về Path Traversal và File Upload Vulnerability**
**1. Path Traversal Vulnerability**
🔍 Mô tả
Path Traversal (Directory Traversal) là một lỗ hổng bảo mật cho phép kẻ tấn công truy cập vào các tệp hoặc thư mục nằm ngoài thư mục gốc dự kiến của ứng dụng web. Lỗ hổng này thường xảy ra khi ứng dụng xử lý không đúng cách các tham số đầu vào từ phía người dùng, đặc biệt là tên tệp hoặc đường dẫn tệp.

📌 Cơ chế khai thác
Kẻ tấn công lợi dụng các ký tự đặc biệt như ../ hoặc ..\\ để điều hướng đến thư mục cha, từ đó truy xuất các tập tin nhạy cảm như:

../../../../etc/passwd       (Unix/Linux)
..\\..\\..\\windows\\win.ini (Windows)
🧪 Ví dụ đơn giản
URL dễ bị tấn công:

http://example.com/download?file=report.pdf
Kẻ tấn công thay đổi giá trị:

http://example.com/download?file=../../../../etc/passwd
Nếu ứng dụng không kiểm tra kỹ đầu vào, nó sẽ trả về nội dung file /etc/passwd.

**2. File Upload Vulnerability**
🔍 Mô tả
Lỗ hổng File Upload cho phép người dùng tải lên các tệp độc hại như shell web, mã thực thi,... lên máy chủ nếu không được kiểm soát đúng cách. Nếu không có kiểm tra định dạng, nội dung, hoặc quyền, tệp có thể được thực thi trên máy chủ, dẫn đến chiếm quyền kiểm soát.

🧨 Các hình thức tấn công phổ biến:

Tải lên Web Shell: file .php, .asp, .jsp,... chứa mã độc.

Tấn công kết hợp: Kết hợp file upload với Path Traversal hoặc LFI để thực thi mã.

Bypass MIME-type và Extension: thay đổi phần mở rộng hoặc sử dụng double extensions như shell.php.jpg.
