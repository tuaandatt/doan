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

![image](https://github.com/user-attachments/assets/8d631e3e-40da-4526-bc7b-dd4918ca0db7)

![image](https://github.com/user-attachments/assets/3e8da87f-e1e4-43d2-8745-a0d527e99904)

![image](https://github.com/user-attachments/assets/e760c51b-599a-47ce-9caa-8c14609e4e49)

Đưa gói tin bắt được vào Repeater để phân tích

![image](https://github.com/user-attachments/assets/61796ee8-2755-4ebb-bb7a-8ba7ee1d244e)

![image](https://github.com/user-attachments/assets/de0db44e-d662-4652-96e4-233d27271fc0)

![image](https://github.com/user-attachments/assets/e0390222-3ca7-4727-91e2-8146db8bf462)

Xóa toàn bộ nội dung file ảnh đã gửi

![image](https://github.com/user-attachments/assets/119c50ca-4ab6-409a-9705-f0e990f149a5)

Copy đoạn code dùng để điều khiển hệ thống thay thế nội dung file ảnh vừa xóa trong Repeater

![image](https://github.com/user-attachments/assets/fe585a0e-2bc3-4085-b63f-a2b1797f4f2d)

![image](https://github.com/user-attachments/assets/1929dd16-1682-4814-8f07-fafd366c36f6)

![image](https://github.com/user-attachments/assets/8d1b424e-ecf1-43ff-b0a4-0b616e80b431)

Sửa tên file thành tuandat.php

![image](https://github.com/user-attachments/assets/7ac47484-5e48-472c-806b-2ee380e0e2b3)

Kiểm thử với Netcat

![image](https://github.com/user-attachments/assets/022ab989-e682-4d4d-901b-7f48afc2833e)

Sử dụng công cụ DirSearch để tìm thư mục chứa file upload

![image](https://github.com/user-attachments/assets/386c73b0-5e40-42f9-9ee3-367965ef42a1)

Kiểm thử với /upload/kiemthu.php up lên ban đầu

![image](https://github.com/user-attachments/assets/b66e6ac7-7317-4878-863c-49b04f618622)

Phản hồi từ hệ thống khi nhập /upload/kiemthu.php

![image](https://github.com/user-attachments/assets/9b06c057-cb80-4b2d-8dbf-a31c9f1c78ee)

tiến hành sửa thành /upload/tuandat.php


![image](https://github.com/user-attachments/assets/fbe0de2b-df98-483a-84b9-30d2a66276f1)

Máy chủ Ubuntu Server đã bị chiếm quyền diều khiển


![image](https://github.com/user-attachments/assets/fcbd36cd-73fb-48d0-a14b-5b0a478469ea)
