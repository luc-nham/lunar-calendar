# Tổng quan về thư viện Lunar-Calendar

- **[Cài đặt](#cài-đặt)**
- **[Khái niệm cốt lõi](#khái-niệm-cốt-lõi)**
- **[Tham khảo](#tham-khảo)**


## Cài đặt
Tải xuống thư viện tại địa chỉ https://github.com/vantran445/lunar-calendar.git
Thư viện cũng có sẵn trên [packagist.org](https://packagist.org/packages/lunar-calendar/lunar-calendar), cài đặt phiên bản mới nhất thông qua dòng lệnh hoặc nhúng vào tệp composer.json của bạn.
```
$ composer require monolog/monolog
```

## Khái niệm cốt lõi
Thư viện này cho phép bạn chuyển đổi từ Dương lịch (lịch Gregorian) sang Âm dương lịch nhanh chóng, bao gồm nhiều yếu tố lịch pháp cần thiết:
- [Biểu diễn Ngày tháng Âm lịch](/docs/Vietnamese/02-bieu-dien-ngay-thang-am-lich.md)
- [Hệ thống Thiên can và Địa chi](/docs/Vietnamese/03-he-thong-thien-can-dia-chi.md)
- [Hệ thống 24 Tiết khí](/docs/Vietnamese/04-he-thong-24-tiet-khi.md)

Ngoài việc chuyển đổi dữ liệu, thư viện còn hỗ trợ người dùng lấy dữ liệu thuận tiện và nhanh chóng thông qua khả năng định dạng trước. Để đi vào chi tiết, hãy xem các hướng dẫn khác trong bộ tài liệu này.

## Tham khảo
Thư viện Lunar-Calendar được tham khảo giải thuật từ các nguồn sau đây:
- https://www.informatik.uni-leipzig.de/~duc/amlich/calrules.html
- http://tutorialspots.com/php-some-method-of-determining-the-suns-longitude-part-2-2479.html