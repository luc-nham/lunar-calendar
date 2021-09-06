# Ngày tháng năm âm lịch
Hướng dẫn này cung cấp các cách thức để chuyển đổi một mốc thời gian Dương lịch cụ thể sang Âm lịch. Trước khi bắt đầu, bạn nên cân nhắc thiết lập múi giờ địa phương để đảm bảo nhận được kết quả mong muốn. Bạn luôn có thể khởi tạo một đối tượng Âm dương lịch bao gồm cả việc thiết lập múi giờ, tuy việc, cân nhắc thiết lập giá trị toàn cục như sau:
```php
<?php

// Giả sử tôi muốn nhận thời gian tại Việt Nam, có múi giờ UTC +7.
date_default_timezone_set('Asia/Ho_Chi_Minh');
```

Tiếp theo, chúng ta tiến hành chuyển đổi một mốc thời gian bằng cách khởi tạo đối tượng mới từ lớp `LunarDateTime`, lớp này được mở rộng từ lớp `DateTime` mặc định của PHP, do đó bạn cũng có thể sử dụng các hàm static của lớp `DateTime`. Tham khảo tài liệu https://www.php.net/manual/en/class.datetime.php để biết thêm.

```php
<?php

use DateTimeZone;
use LunarCalendar\LunarDateTime;

// Chuyển đổi thời gian ngay tại thời điểm khởi tạo đối tượng
$lunar = new LunarDateTime();

// Chuyển đổi thời gian ngay tại thời điểm khởi tạo có sử dụng chênh lệnh múi giờ
// $lunar = new LunarDateTime('now', new \DateTimeZone('+0700'));

// Chuyển đổi từ một mốc thời gian Dương lịch cụ thể, ví dụ vào ngày 20 tháng 7 năm 2020, lúc 18 giờ 30 phút
// $lunar = new LunarDateTime('07/20/2020 18:30:00');

var_dump($lunar);
```
Bạn có thể nhận được đầu ra tương tự như sau:
```php
C:\Users\caova\xampp\htdocs\lunar-calendar\index.php:13:
object(LunarCalendar\LunarDateTime)[3]
  protected 'lunar_date' => 
    object(LunarCalendar\Formatter\LunarDateTimeStorageFormatter)[4]
      protected 'datetime' => 
        array (size=9)
          'd' => int 11
          'm' => int 6
          'Y' => int 2021
          'H' => int 16
          'i' => int 0
          's' => int 30
          'o' => float 7
          'l' => int 0
          'j' => float 2459416.6670139
  public 'date' => string '2021-07-20 16:00:30.000000' (length=26)
  public 'timezone_type' => int 3
  public 'timezone' => string 'Asia/Ho_Chi_Minh' (length=16)
```

## Định dạng kết quả đầu ra
