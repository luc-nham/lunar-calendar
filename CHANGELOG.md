# Changelog

## v0.0.3

### Các bổ sung hoặc sửa đổi
- Cập nhật thư viện `vantran/lunar-calendar-cli` 0.0.2 -> 0.0.3
- Thêm giao diện `VanTran\LunarCalendar\Lunar\LunarInputInterface` giúp xác định một đối tượng triển khai có thể truy xuất các giá trị thời gian Âm lịch.
- Thêm lớp `VanTran\LunarCalendar\Lunar\LunarParser::class` hỗ trợ phân tích cú pháp chuỗi thời gian Âm lịch

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