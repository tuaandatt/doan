**Báo cáo Phân tích Lý thuyết về Path Traversal và File Upload Vulnerability**

**1. Path Traversal Vulnerability**

Mô tả

Path Traversal (Directory Traversal) là một lỗ hổng bảo mật cho phép kẻ tấn công truy cập vào các tệp hoặc thư mục nằm ngoài thư mục gốc dự kiến của ứng dụng web. Lỗ hổng này thường xảy ra khi ứng dụng xử lý không đúng cách các tham số đầu vào từ phía người dùng, đặc biệt là tên tệp hoặc đường dẫn tệp.

Cơ chế khai thác

Kẻ tấn công lợi dụng các ký tự đặc biệt như ../ hoặc ..\\ để điều hướng đến thư mục cha, từ đó truy xuất các tập tin nhạy cảm như:

../../../../etc/passwd       (Unix/Linux)

..\\..\\..\\windows\\win.ini (Windows)

Ví dụ:

URL dễ bị tấn công:

http://example.com/download?file=report.pdf

Kẻ tấn công thay đổi giá trị:

http://example.com/download?file=../../../../etc/passwd

Nếu ứng dụng không kiểm tra kỹ đầu vào, nó sẽ trả về nội dung file /etc/passwd.

Demo: 

Tiến hành kiểm thử mục tiêu

![image](https://github.com/user-attachments/assets/e534fed1-3dae-4b33-b641-d1303b95bbce)

![image](https://github.com/user-attachments/assets/65837ce2-1bae-44d1-92ec-dc97ea340107)

![image](https://github.com/user-attachments/assets/c8b8a15f-590b-4f35-b719-348d1e16f49c)

Sử dụng công cụ whatweb để xem thông tin hệ thống

![image](https://github.com/user-attachments/assets/e61d7b1d-444c-4b8e-83e6-faefc86967bc)

Sử dụng công cụ nmap để xem các dịch vụ và phiên bản hệ thống đang sử dụng

![image](https://github.com/user-attachments/assets/27042ce4-c0c1-48c8-83c8-9ceda5af5bb5)

Sử dụng công cụ Nuclei để tìm lỗi cấu hình và các lỗ hổng CVE ( nếu có ) trên hệ thống và không phát hiện lỗ hổng nào

![image](https://github.com/user-attachments/assets/34fa7145-f094-4c9e-9487-d35edba3e2ee)

Attacker bấm vào hình ảnh thì hiện lên 1 đường dẫn loadImage.php xử lý hình ảnh bên trong hệ thống

![image](https://github.com/user-attachments/assets/c0400a86-d20f-47ae-9741-a1bf4bb192d6)

Thấy khả nghi nên attacker đã vào Burp Suite Pro để quét lỗ hổng hệ thống

![image](https://github.com/user-attachments/assets/7333df60-0e95-4bb4-beb5-c85aeff64a5e)

vào Target tìm file xử lý ảnh /loadImage.php đưa vào Repeater để phân tích

![image](https://github.com/user-attachments/assets/669b7c73-62cb-4a4b-ba16-cb9dcb0a87b1)

![image](https://github.com/user-attachments/assets/0d93fb10-8852-43a9-b4d5-3c5651360bb7)

![image](https://github.com/user-attachments/assets/b6ffd845-4c35-42ed-9ee2-022336795b9e)

sửa đổi file_name=phoneA.jpg chứa ảnh điện thoại thành đoạn mã dính lỗ hổng Path traversal là ../../../../etc/passwd
Toàn bộ thông tin nhạy cảm bên trong hệ thống đã bị truy xuất

![image](https://github.com/user-attachments/assets/7a38bad7-e8af-4a91-92a6-349070f0d871)

**Nguyên nhân dẫn đến lỗ hổng trên:**

![image](https://github.com/user-attachments/assets/7cf8425b-e293-4e9d-9075-aa3bdce58b7e)

Đoạn code $file_name = $_GET['file_name']; => Giá trị này được lấy trực tiếp từ URL mà không kiểm tra hoặc lọc ghép file_name với đường dẫn cố định

$file_path = '/var/www/html/images/' . $file_name; => $file_path được ghép từ thư mục /var/www/html/images/ với $file_name.

Không có biện pháp kiểm tra để ngăn chặn việc sử dụng các chuỗi như ../ (Parent Directory Traversal).

if (file_exists($file_path)) {

Kiểm tra file có tồn tại hay không, nhưng không xác minh rằng file nằm trong thư mục hợp lệ (/var/www/html/images).

Hiển thị nội dung file nếu tồn tại

header('Content-Type: image/png'); readfile($file_path);

File được đọc và trả về cho người dùng mà không có bất kỳ kiểm tra bảo mật nào.

=> Tất cả các nguyên do trên đã dẫn đến lỗ hổng path traversal đây là một lỗ hổng bảo mật cho phép kẻ tấn công truy cập trái phép vào các file và thư mục bên ngoài phạm vi được chỉ định của ứng dụng web

**Giải pháp khắc phục:**

![image](https://github.com/user-attachments/assets/d4019988-8dd5-4ed4-888a-73a380686097)

Lấy tên file từ tham số GET và xử lý

$file_name = basename($_GET['file_name']);

Mục đích:
Lấy giá trị tham số file_name từ URL.

Dùng hàm basename() để loại bỏ các thành phần đường dẫn không hợp lệ (như ../) nhằm ngăn chặn lỗ hổng Path Traversal.

$file_path = '/var/www/html/images/' . $file_name;

Mục đích: Tạo đường dẫn đầy đủ đến file dựa trên thư mục mặc định /var/www/html/images/

Kiểm tra tính hợp lệ của file

if (file_exists($file_path) && is_file($file_path)) {


**2. File Upload Vulnerability**

Mô tả

Lỗ hổng File Upload cho phép người dùng tải lên các tệp độc hại như shell web, mã thực thi,... lên máy chủ nếu không được kiểm soát đúng cách. Nếu không có kiểm tra định dạng, nội dung, hoặc quyền, tệp có thể được thực thi trên máy chủ, dẫn đến chiếm quyền kiểm soát.

Các hình thức tấn công phổ biến:

Tải lên Web Shell: file .php, .asp, .jsp,... chứa mã độc.

Demo: 

Kiểm thử hệ thống mục phần bình luận có gửi được file .php hay không

![image](https://github.com/user-attachments/assets/392c901c-a8cc-49ed-8d3d-2b6f5b990027)

gửi 1 file kiemthu.php vào phần upload

![image](https://github.com/user-attachments/assets/71598d9a-3f7d-486f-886d-b6d893b90a78)

Gửi file kiemthu.php thành công

![image](https://github.com/user-attachments/assets/8d631e3e-40da-4526-bc7b-dd4918ca0db7)

Gửi lại hình ảnh bình thường với đuôi .jpg

![image](https://github.com/user-attachments/assets/3e8da87f-e1e4-43d2-8745-a0d527e99904)

Gửi file ảnh vào hệ thống

![image](https://github.com/user-attachments/assets/e760c51b-599a-47ce-9caa-8c14609e4e49)

Đưa gói tin bắt được vào Repeater để phân tích

![image](https://github.com/user-attachments/assets/7fbe8919-a5fc-4c38-81d0-660dc014a57c)

Xóa toàn bộ nội dung file ảnh đã gửi

![image](https://github.com/user-attachments/assets/698b4811-ac3c-4652-a9a2-ebb82624c17d)

Copy đoạn payload dùng để điều khiển hệ thống thay thế nội dung file ảnh vừa xóa trong Repeater

![image](https://github.com/user-attachments/assets/c808a8c3-c301-496a-b022-861107fbb661)

Thay đoạn code kiểm thử vào Repeater từ sửa đổi file ảnh

![image](https://github.com/user-attachments/assets/ac37a59d-56bf-4fc6-b691-b4a60cd7f78d)

Sửa đổi tên file ảnh .jpg thành tuandat.php

![image](https://github.com/user-attachments/assets/764a32fa-863e-408d-9006-f2ea036e48b8)

Sửa tên file thành tuandat.php

![image](https://github.com/user-attachments/assets/a29a886c-5c39-4ed9-bed5-38cff772cf1e)

Sử dụng lệnh nc -lvnp 1234 để lắng nghe kết nối từ pentester đến hệ thống

![image](https://github.com/user-attachments/assets/e54360a1-dd60-4beb-814f-16b770f416c9)

Sử dụng công cụ DirSearch để tìm thư mục chứa file upload

![image](https://github.com/user-attachments/assets/694aebcc-cc6b-4e85-9624-0036469d6695)

Kiểm thử với /upload/kiemthu.php up lên ban đầu
 
![image](https://github.com/user-attachments/assets/18037559-8974-41d2-bbf5-922b90f722f5)

Phản hồi từ hệ thống khi nhập /upload/kiemthu.php

![image](https://github.com/user-attachments/assets/a140fa98-e120-4933-baf8-cfc1afee7d8f)

Tiến hành sửa thành /upload/tuandat.php

![image](https://github.com/user-attachments/assets/555ff733-40c0-412a-bdfc-0b52d45ccd0a)

Máy chủ Ubuntu Server đã bị chiếm quyền diều khiển

![image](https://github.com/user-attachments/assets/5453029b-58ee-48ce-a484-3599693e315c)

![image](https://github.com/user-attachments/assets/7bcbf042-78c1-4c89-9176-d97980e27fa3)

Nguyên nhân dẫn đến lỗ hổng trên:

Không kiểm tra đúng loại tệp tải lên có thể dẫn đến việc tấn công với các tệp độc hại như .php hay .exe.

Không thiết lập giới hạn kích thước tệp tải lên có thể khiến hệ thống dễ bị tấn công với các tệp lớn hoặc có chứa mã độc.

![image](https://github.com/user-attachments/assets/b7bb7780-589e-46ff-80a4-aaa4b4215b9c)

Giải pháp khắc phục:

![image](https://github.com/user-attachments/assets/aafb824c-723a-42d1-ab24-0fd76971a58c)

![image](https://github.com/user-attachments/assets/dc58456d-03b3-4182-baa1-22171ef41684)

DEMO: d![image](https://github.com/user-attachments/assets/8d631e3e-40da-4526-bc7b-dd4918ca0db7)

Gửi lại hình ảnh bình thường với đuôi .jpg

![image](https://github.com/user-attachments/assets/3e8da87f-e1e4-43d2-8745-a0d527e99904)

Gửi file ảnh vào hệ thống

![image](https://github.com/user-attachments/assets/e760c51b-599a-47ce-9caa-8c14609e4e49)

Đưa gói tin bắt được vào Repeater để phân tích

![image](https://github.com/user-attachments/assets/7fbe8919-a5fc-4c38-81d0-660dc014a57c)

Xóa toàn bộ nội dung file ảnh đã gửi

![image](https://github.com/user-attachments/assets/698b4811-ac3c-4652-a9a2-ebb82624c17d)

Copy đoạn payload dùng để điều khiển hệ thống thay thế nội dung file ảnh vừa xóa trong Repeater

![image](https://github.com/user-attachments/assets/c808a8c3-c301-496a-b022-861107fbb661)

Thay đoạn code kiểm thử vào Repeater từ sửa đổi file ảnh

![image](https://github.com/user-attachments/assets/ac37a59d-56bf-4fc6-b691-b4a60cd7f78d)

Sửa đổi tên file ảnh .jpg thành tuandat.php

![image](https://github.com/user-attachments/assets/764a32fa-863e-408d-9006-f2ea036e48b8)

Sửa tên file thành tuandat.php

![image](https://github.com/user-attachments/assets/a29a886c-5c39-4ed9-bed5-38cff772cf1e)

Sử dụng lệnh nc -lvnp 1234 để lắng nghe kết nối từ pentester đến hệ thống

![image](https://github.com/user-attachments/assets/e54360a1-dd60-4beb-814f-16b770f416c9)

Sử dụng công cụ DirSearch để tìm thư mục chứa file upload

![image](https://github.com/user-attachments/assets/694aebcc-cc6b-4e85-9624-0036469d6695)

Kiểm thử với /upload/kiemthu.php up lên ban đầu
 
![image](https://github.com/user-attachments/assets/18037559-8974-41d2-bbf5-922b90f722f5)

Phản hồi từ hệ thống khi nhập /upload/kiemthu.php

![image](https://github.com/user-attachments/assets/a140fa98-e120-4933-baf8-cfc1afee7d8f)

Tiến hành sửa thành /upload/tuandat.php

![image](https://github.com/user-attachments/assets/555ff733-40c0-412a-bdfc-0b52d45ccd0a)

Máy chủ Ubuntu Server đã bị chiếm quyền diều khiển

![image](https://github.com/user-attachments/assets/5453029b-58ee-48ce-a484-3599693e315c)

![image](https://github.com/user-attachments/assets/7bcbf042-78c1-4c89-9176-d97980e27fa3)

Nguyên nhân dẫn đến lỗ hổng trên:

Không kiểm tra đúng loại tệp tải lên có thể dẫn đến việc tấn công với các tệp độc hại như .php hay .exe.

Không thiết lập giới hạn kích thước tệp tải lên có thể khiến hệ thống dễ bị tấn công với các tệp lớn hoặc có chứa mã độc.

![image](https://github.com/user-attachments/assets/b7bb7780-589e-46ff-80a4-aaa4b4215b9c)

Giải pháp khắc phục:

![image](https://github.com/user-attachments/assets/aafb824c-723a-42d1-ab24-0fd76971a58c)

![image](https://github.com/user-attachments/assets/dc58456d-03b3-4182-baa1-22171ef41684)

DEMO: https://drive.google.com/file/d/1Xnsp9llBvd6KD07x3Vdj-6b3ciH4n41m/view?usp=sharing
