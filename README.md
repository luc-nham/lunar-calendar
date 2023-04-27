# lunar-calendar
Thư viện PHP Âm lịch

## Cài đặt
`composer require vantran/lunar-calendar`

## Giới thiệu
Sử dụng ngày tháng trong Âm lịch, hỗ trợ tối đa cho Âm lịch Việt Nam.

## Làm việc với thời gian Âm lịch
Để bắt đầu, hãy tạo một thời điểm Âm lịch bất kỳ:
```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

$lunar = new LunarDateTime('20/10/1990 20:19:00');
echo $lunar->format('d-m-Y h:ia');

// Đầu ra: 01-02-1990 8:19pm
```
Lớp `VanTran\LunarCalendar\LunarDateTime` được thiết kế tương đối giống với lớp xử lý ngày tháng `\DateTime`, bao gồm khả năng định dạng thời gian đầu ra và truy xuất các giá trị quan trọng trong việc lập lịch.

### Tạo thời điểm Âm lịch hiện tại
```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

/**
 * Lấy thời điểm Âm lịch hiện tại cho Âm lịch Việt Nam, có 3 phương pháp tùy chọn
 */
$lunar = new LunarDateTime();
echo $lunar->format('d/m/Y H:i:s');

$lunar = new LunarDateTime('now');
echo $lunar->format('d/m/Y H:i:s');

$lunar = LunarDateTime::now();
echo $lunar->format('d/m/Y H:i:s');

/**
 * Lấy thời điểm Âm lịch hiện tại ở một địa điểm có múi giờ khác với múi giờ Việt Nam,
 * chỉ cần truyền tham số thứ hai cho hàm tạo.
 */
$timezone = new DateTimeZone('UTC');

$lunar = new LunarDateTime('now', $timezone);
echo $lunar->format('d/m/Y H:i:s');

$lunar = LunarDateTime::now($timezone);
echo $lunar->format('d/m/Y H:i:s');
```

### Xử lý tháng nhuận đầu vào
Trường hợp thời điểm Âm lịch là tháng nhuận, có thể xử lý theo 2 cách

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

// Đặt dấu cộng phía sau tháng nhuận
$lunar = new LunarDateTime('10/02+/2023');

// Đặt chuối '(+)' trong chuỗi thời gian đầu vào, không yêu cầu vị trí cụ thể
$lunar = new LunarDateTime('2023-02-10 (+)');
```

### Chuyển đổi Âm lịch thành dương lịch
Lớp `VanTran\LunarCalendar\LunarDateTime` có sẵn một phương thức `toDateTime` để chuyển đổi thành một đối tượng `\DateTime\`. Khi thực hiện, múi giờ địa phương được đồng bộ.

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

$lunar = new LunarDateTime('20/10/2023');
$solar = $lunar->toDateTime();

echo $solar->format('Y-m-d H:i:s P'); // 2023-10-20 00:00:00 +0700
```