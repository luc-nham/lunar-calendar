# Changelog

## v2.2.1

### Các bổ sung hoặc sửa đổi

- Sửa lỗi tính toán sai tổng số ngày trong tháng Âm lịch từ một mốc Âm lịch đầu vào
- Bổ sung comment giải thích cho mã.

## v2.2.0

### Các bổ sung hoặc sửa đổi

- Sửa lỗi tính toán sai tháng và năm khi chuyển đổi các năm Dương lịch sang Âm lịch có nhuận.

## v2.1.9

### Các bổ sung hoặc sửa đổi

- Sửa lỗi chuyển đổi Dương lịch sang Âm lịch ở mốc ngày 14 tháng 10 năm 2023, lúc 21 giờ, múi giờ GMT+7.

## v2.1.8

### Các bổ sung hoặc sửa đổi

- Hỗ trợ khởi tạo Âm lịch từ một đối tượng triển khai `DateTimeInterface`

## v2.1.7

### Các bổ sung hoặc sửa đổi

- Cập nhật tài liệu

## v2.1.6

### Các bổ sung hoặc sửa đổi

- Sửa lỗi ánh xạ sai khóa và nhãn tiết khí
- Cập nhật tài liệu

## v2.1.5

### Các bổ sung hoặc sửa đổi

- Thêm tính năng định dạng số tháng nhuận của năm nhuận

## v2.1.4

### Các bổ sung hoặc sửa đổi

- Thêm tính năng định dạng tuần Giáp thuộc hệ thống Can Chi

## v2.1.2

### Các bổ sung hoặc sửa đổi

- Cập nhật phiên bản PHP tối thiểu

## v2.1.1

### Các bổ sung hoặc sửa đổi

- Cập nhật, sửa chửa các comment bị thiếu hoặc lỗi

## v2.1.0

### Các bổ sung hoặc sửa đổi

- Tối ưu mã
- Thêm các lớp tính năng làm việc với hệ thống Tiết Khí

## v2.0.0

### Các bổ sung hoặc sửa đổi

- Tái cấu trúc mã
- Sửa một số lỗi quan trọng
- Bổ sung hệ thống Can Chi
- Bổ sung tài liệu

### Các thành phần xóa bỏ

- Các thành phần mã cũ được tái cấu trúc lại

## v1.0.3

### Các bổ sung hoặc sửa đổi

- Sửa lỗi định dạng số giờ Âm lịch

## v1.0.2

### Các bổ sung hoặc sửa đổi

- Sửa một số lỗi khi truy xuất giá trị MJD và độ lệch múi giờ của lớp `VanTran\LunarCalendar\LunarDateTime`

## v1.0.1

### Các bổ sung hoặc sửa đổi

- Bổ sung các tính năng cho lớp `VanTran\LunarCalendar\LunarDateTime` (chuyển đổi từ Dương lịch sang Âm lịch):
  - Tạo mới đối tượng từ chuỗi thời gian Dương lịch
  - Tạo mới đối tượng từ một đối tượng triển khai `\DateTimeInterface`

## v1.0.0

### Các bổ sung hoặc sửa đổi

- Thêm thành phần cốt lõi để lập Âm lịch
- Thêm lớp chính `VanTran\LunarCalendar\LunarDateTime`
