# Hệ thống 24 Tiết khí
24 Tiết khí là một hệ thống quan trọng trong Âm dương lịch, cũng như đối với nhiều bộ môn chiêm đoán như Lục Nhâm hoặc Kỳ Môn... Để tính toán hệ thống này, chúng ta sẽ sử dụng lớp `SolarTerm`. Cần lưu ý, Hệ thống 24 tiết khí được tính toán dựa trên Dương lịch chứ không phải Âm lịch, tức sử dụng quỹ đạo của Trái Đất quanh Mặt Trời mà không liên quan đến quỹ đạo của Mặt Trăng quanh Trái Đất.

## Khởi tạo đối tượng
```php
<?php

use LunarCalendar\SolarTerm;

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Khởi tạo đối tượng tiết khí với mốc thời gian 'hiện tại'
$solarTerm = new SolarTerm();

// Khởi tạo tiết khí tại một thời điểm cụ thể, ví dụ ngày 25 tháng 6 năm 2021, Dương lịch
//$solarTerm = new SolarTerm('25/6/2021');

var_dump($solarTerm);
```

## Truy xuất dữ liệu Tiết khí
Để lấy thông tin về Tiết khí từ đối tượng đã khởi tạo, chúng ta sử dụng phương thức `getTerm()`. Phương thức này trả về một đối tượng giúp xác định Tiết khí:
```php
<?php

use LunarCalendar\SolarTerm;

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Khởi tạo đối tượng tiết khí với mốc thời gian 'hiện tại'
$solarTerm = new SolarTerm();

// Truy xuất tiết khí
$term = $solarTerm->getTerm();
var_dump($term);

// Truy xuất các thuộc tính
echo $term->getOffset();
echo '<br />';

echo $term->getKey();
echo '<br />';

echo $term->getLabel();
echo '<br />';
```
Bạn có thể nhận được đầu ra tương tự như sau:
```
C:\Users\caova\xampp\htdocs\lunar-calendar\index.php:19:
object(LunarCalendar\Formatter\SolarTermFormatter)[2]
  protected 'offset' => int 11
  protected 'key' => string 'bach_lo' (length=7)
  protected 'label' => string 'Bạch Lộ' (length=11)

11
bach_lo
Bạch Lộ
```
