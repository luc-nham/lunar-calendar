# Làm việc với Hệ thống Tiết khí
Kể từ phiên bản `2.1.0`, lớp `SolarTerm` có sẵn để khởi tạo hệ thống Tiết khí. Trước khi đi vào ví dụ chi tiết, hãy tìm hiểu các mục (1, 2) để xem bảng liệt kê 24 tiết khí trong hệ thống, bao gồm số, ký tự đại diện, tên và góc Kinh độ Mặt trời tương ứng và các phương thức hỗ trợ các tính năng quan trọng.

## 1. Danh sách 12 Tiết và 12 Khí
|       Tên     |   Số  | Ký tự | Phân loại | Góc KDMT bắt đâu  |
| ------------- | ----- | ----- | --------- | ----------------- |
|   Xuân Phân   |   0   |   a   |   Z       |   0               |      
|   Cốc Vũ      |   1   |   b   |   J       |   15              | 
|   Thanh Minh  |   2   |   c   |   Z       |   30              | 
|   Lập Hạ      |   3   |   d   |   J       |   45              | 
|   Tiểu Mãn    |   4   |   e   |   Z       |   60              | 
|   Mang Chủng  |   5   |   f   |   J       |   75              | 
|   Hạ Chí      |   6   |   g   |   Z       |   90              | 
|   Tiểu Thử    |   7   |   h   |   J       |   105             |
|   Đại Thử     |   8   |   i   |   Z       |   120             |
|   Lập Thu     |   9   |   j   |   J       |   135             |       
|   Xử Thử      |   10  |   k   |   Z       |   150             |
|   Bạch Lộ     |   11  |   l   |   J       |   165             |
|   Thu Phân    |   12  |   m   |   Z       |   180             |
|   Hàn Lộ      |   13  |   n   |   J       |   195             |
|   Sương Giáng |   14  |   o   |   Z       |   210             |
|   Lập Đông    |   15  |   p   |   J       |   225             |
|   Tiểu Tuyết  |   16  |   q   |   Z       |   240             |
|   Đại Tuyết   |   17  |   r   |   J       |   255             |
|   Đông Chí    |   18  |   s   |   Z       |   270             |
|   Tiểu Hàn    |   19  |   t   |   J       |   285             |
|   Đại Hàn     |   20  |   u   |   Z       |   300             |
|   Lập Xuân    |   21  |   v   |   J       |   315             |
|   Vũ Thủy     |   22  |   w   |   Z       |   330             |
|   Kinh Trập   |   23  |   x   |   J       |   345             |

*Chú thích: Phân loại 'Z' tương ứng với Trung khí, 'J' tương ứng với Tiết*

## 2. Các phương thức

|    Tên phương thức        |             Mô tả                                                                 |
| ------------------------- | --------------------------------------------------------------------------------- |
| `__constructor() `        | Khởi tạo một đối tượng Tiết khí                                                   |
| `::now() `                | Khởi tạo 1 đối tượng từ thời điểm hiện tại                                        |
| `::createFromGregorian()` | Khởi tạo một đối tượng từ ngày tháng Dương lịch (Gregorian)                       |
| `getIndex()`              | Trả về số đại diện cho tiết khí                                                   |
| `getCharacter()`          | Trả về ký tự từ a - x (bảng chữ cái tiếng Anh) đại diện cho tiết khí              |
| `getType()`               | Trả về 'Z' hoặc 'J' giúp phân loại Tiết hoặc Khí                                  |
| `getLabel()`              | Trả về tên hiển thị của tiết khí                                                  |
| `getDegrees()`            | Trả về góc Kinh độ Mặt trời của tiết khí tương ứng với thời điểm tạo đối tượng    |
| `getMidnightDegrees()`    | Trả về góc Kinh độ Mặt trời tương ứng với thời điểm nửa đêm (00:00) cùng ngày     |
| `begin()`                 | Trả về một đối tượng mới với thông tin là vị trí bắt đầu của Tiết khí             |
| `next() `                 | Tìm các tiết khí tiếp theo (chưa đến), trả về đối tượng mới                       |
| `previous() `             | Tìm các tiết khí trước đó (đã qua), trả về đối tượng mới                          |

## 3. Khởi tạo đối tượng

```php
<?php

// Cấu hình 1 múi giờ bắt đầu (tùy chọn)

use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\SolarTerm;

$timezone = new DateTimeZone('UTC');

// Khởi tạo từ thời điểm 'hiện tại' từ phương thức tĩnh
$solarTermNow = SolarTerm::now($timezone);

// Khởi tạo từ một mốc Âm lịch, hoặc một triển khai của JulianDayNumberInterface bất kỳ
$lunar = new LunarDateTime('20-10-2023', $timezone);
$solarTermFromLunar = new SolarTerm($lunar);

// Khởi tạo từ một mốc Dương lịch
$solarTermFromGregorian = SolarTerm::createFromGregorian('01-09-1994', $timezone);

echo $solarTermNow->getIndex() . "\r\n";
echo $solarTermNow->getDegrees() . "\r\n";
echo $solarTermNow->getCharacter() . "\r\n";
echo $solarTermNow->getLabel() . "\r\n";
echo $solarTermNow->getJd() . "\r\n";
```

## 4. Tìm điểm bắt đầu Tiết Khí

```php
<?php

use VanTran\LunarCalendar\SolarTerm;

// Tìm điểm bắt đầu
$solarTerm = SolarTerm::now();
$begin = $solarTerm->begin();

echo $begin->getDegrees() . "\r\n";
echo $begin->getLabel() . "\r\n";
```

## 5. Tìm tiết khí tiếp theo (chưa đến)

```php
<?php

use VanTran\LunarCalendar\SolarTerm;

// Tìm tiết khí kế tiếp
$solarTerm = SolarTerm::now();
$next1Term = $solarTerm->next();

echo $next1Term->getDegrees() . "\r\n";
echo $next1Term->getLabel() . "\r\n";

// Tìm tiết khí thứ 2, 3, 4, 5,.., n) tiếp theo
$next3Term = $solarTerm->next(3);
echo $next3Term->getDegrees() . "\r\n";
echo $next3Term->getLabel() . "\r\n";
```

## 6. Tìm tiết khí trước đó (đã qua)

```php
<?php

use VanTran\LunarCalendar\SolarTerm;

// Tìm tiết khí kế tiếp
$solarTerm = SolarTerm::now();
$prev1Term = $solarTerm->previuos();

echo $prev1Term->getDegrees() . "\r\n";
echo $prev1Term->getLabel() . "\r\n";

// Tìm tiết khí thứ 2, 3, 4, 5,.., n) trước đó
$prev3Term = $solarTerm->previuos(3);
echo $prev3Term->getDegrees() . "\r\n";
echo $prev3Term->getLabel() . "\r\n";
```