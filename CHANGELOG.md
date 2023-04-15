# Changelog

## v0.0.2

### Các bổ sung hoặc sửa đổi
- Thêm thư viện `vantran/lunar-calendar-cli` cho giao diện dòng lệnh, hỗ trợ tạo động các dữ liệu để kiểm tra
- Điều chỉnh một số lỗi khai báo composer.json
- Thêm lớp chuyển đôi một nhóm mốc ngày, tháng, năm dương lịch về mốc MJD
- Thêm các lớp xử lý Kinh độ Mặt trời (KDMT):
  - Lớp truy xuất dữ liệu
  - Lớp tính toán KDMT từ `MjdInterface`
  - Lớp tính toán KDMT từ nhóm ngày tháng dương lịch
  - Lớp tính toán KDMT từ đối tượng triển khai `DateTimeInterface`

## v0.0.1

### Các bổ sung hoặc sửa đổi
- Thêm lớp chuyển đổi tem thời gian Unix, đối tượng ngày tháng PHP thành số ngày MJD
- Thêm lớp chuyển đổi mốc ngày MJD thành điểm Sóc tương ứng của nó