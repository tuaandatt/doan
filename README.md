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

**2. File Upload Vulnerability**

Mô tả

Lỗ hổng File Upload cho phép người dùng tải lên các tệp độc hại như shell web, mã thực thi,... lên máy chủ nếu không được kiểm soát đúng cách. Nếu không có kiểm tra định dạng, nội dung, hoặc quyền, tệp có thể được thực thi trên máy chủ, dẫn đến chiếm quyền kiểm soát.

Các hình thức tấn công phổ biến:

Tải lên Web Shell: file .php, .asp, .jsp,... chứa mã độc.

Tấn công kết hợp: Kết hợp file upload với Path Traversal hoặc LFI để thực thi mã.

Demo: 

Target: 

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

