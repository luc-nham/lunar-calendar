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
Để lấy hoặc hiển thị dữ liệu, chúng ta sẽ sử dụng phương thức `format()`. Về cơ bản, phương thức này hỗ trợ việc định dạng đầu ra tương tự như lớp `DateTime`, bạn nên xem https://www.php.net/manual/en/datetime.format.php. Chuỗi định dạng truyền vào hỗ trợ các ký tự sau đây:
- `d`: Lấy ngày âm lịch có bao gồm số 0 phía trước, 01 đến 30
- `j`: Lấy ngày âm lịch không bao gồm số 0 phía trước, 1 - 30
- `J`: Lấy số ngày Julian tương ứng với thời điểm chuyển đổi. Cần lưu ý, bởi vì Âm lịch bắt đầu ngày mới vào lúc 23 giờ đêm chứ không phải 0 giờ như Dương lịch, do vậy nếu thời điểm chuyển đổi nằm trong khoảng 23 giờ, thì số ngày Julian sẽ được cộng thêm 1 ngày để khớp với thực tế.
- `m`: Lấy tháng âm lịch có kèm số 0 phía trước, 01 - 12
- `n`: Lấy tháng âm lịch không kèm số 0 phía trước, 1 - 12
- `Y`: Lấy năm âm lịch với định dạng 4 chữ số, vd: 1999, 2013...
- `y`: Lấy năm âm lịch với định dạng 2 chữ số sau cùng
- `l`: Trả về 1 nếu tháng âm lịch đó là tháng nhuận, 0 nếu không
- `L`: Trả về 1 nếu năm âm lịch là năm nhuận, 0 nếu không. Lưu ý, nếu tháng âm lịch là tháng nhuận thì năm đó là năm nhuận, nhưng nếu năm âm lịch nhuận thì chưa chắc tháng đó đã nhuận, do vậy `l` và `L` sẽ có giá trị khác nhau tùy thời điểm.

```php
<?php

// Chỉ lấy một giá trị ngày tháng hoặc năm cụ thể
echo $lunar->format('d');
echo '<br />';

echo $lunar->format('m');
echo '<br />';

echo $lunar->format('J');
echo '<br />';

// Lấy một chuỗi định dạng tùy chỉnh
echo $lunar->format('d/m/Y');
echo '<br />';

echo $lunar->format('d-m-Y');
echo '<br />';

echo $lunar->format('d.m.Y l L');
echo '<br />';

// Đầu ra tương tự như sau
// 30
// 05
// 2459051.7708333
// 30/05/2020
// 30-05-2020
// 30.05.2020 0 1
```
Trong trường hợp bạn muốn lấy hoặc hiển thị các dữ liệu thời gian Dương lịch tương ứng, bạn có thể bổ sung tham số thứ hai cho phương thức `format()`. Khi đó, việc tính toán sẽ được ủy quyền cho phương thức `format()` mặc định của lớp cha `DateTime`, xem https://www.php.net/manual/en/datetime.format.php:
```php
<?php

// Hằng số LunarDateTime::GREGORIAN_FORMAT xác định rằng bạn muốn ủy quyền việc định dạng đầu ra cho lớp cha - Dương lịch
// 
echo $lunar->format('d/m/Y H:i:s O', LunarDateTime::GREGORIAN_FORMAT);
//echo $lunar->format('d/m/Y H:i:s O', 1); // Cách viết ngắn gọn
echo '<br />';
```