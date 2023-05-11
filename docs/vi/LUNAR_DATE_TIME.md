# Xử lý ngày tháng Âm lịch
Lớp `VanTran\LunarCalendar\LunarDateTime` cung cấp khả năng xử lý ngày tháng Âm lịch đầu vào và hỗ trợ định dạng đầu ra. Hình thức hoạt động tương đối giống với lớp cơ sở `DateTime` của PHP.

## Định dạng hỗ trợ
Trước khi đi vào xử lý ngày tháng, hãy tìm hiểu về các định dạng thời gian được hỗ trợ. Bảng sau đây liệt kê các ký tự trong bảng chữ cái tương ứng với thời gian Âm lịch:

| Ký tự| Âm lịch tương ứng |
| -----| ------------- |
| d    | Ngày trong tháng, có số 0 đứng trước. vd: 01, 09, 15|
| j    | Ngày trong tháng, không có số 0 đứng trước. vd: 1, 9, 15|
| L    | Biểu thị số của tháng, có số 0 đứng trước, không có hiệu ứng nào đối với tháng nhuận, vd: 02, 05, 12|
| l    | Biểu thị số của tháng, không có số 0 đứng trước, không có hiệu ứng nào đối với tháng nhuận, vd: 2, 5, 12|
| n    | Biểu thị số của tháng, không có số 0 đứng trước, khi tháng nhuận sẽ có thêm hậu tố `+`, vd 3, 5+ 11+|
| m    | Biểu thị số của tháng, có số 0 đứng trước, khi tháng nhuận sẽ có thêm hậu tố `+`, vd 03, 05+ 11+|
| Y    | Biểu thị số năm gồm 4 chữ số, vd 1999, 2023...|
| t    | Biểu thị tổng số ngày trong tháng, đối với tháng đủ là 30, tháng thiếu là 29 ngày|
| G    | Định dạng 24 giờ không có số 0 đứng đầu. Vd: 1, 8, 10, 19, 23 |
| g    | Định dạng 12 giờ không có số 0 đứng đầu. Vd: 1, 8, 10, 7, 11 |
| H    | Định dạng 24 giờ có số 0 đứng đầu. Vd: 01, 08, 10, 19, 23 |
| h    | Định dạng 12 giờ có số 0 đứng đầu. Vd: 01, 08, 10, 07, 11 |
| i    | Định dạng phút có số 0 đứng đầu |
| s    | Định dạng giây có số 0 đứng đầu |
| a    | Chữ in thường trong tiếng Anh phân biệt buổi sáng và buổi chiều: am - pm |
| A    | Chữ in HOA trong tiếng Anh phân biệt buổi sáng và buổi chiều: AM - PM |
| P    | Chênh lệch giờ địa phương với giờ Greenwich (GMT) với dấu hai chấm giữa giờ và phút (+07:00) |
| U    | Số giây kể từ Unix Epoch (ngày 1 tháng 1 năm 1970 00:00:00 GMT) |
| Z    | Độ lệch múi giờ, tính bằng giây |
| C    | Chuỗi `(+)` xác định tháng nhuận - kết hợp khi sử dụng 'l' hoặc 'L' thay vì 'm' hoặc 'n' |

## Khởi tạo thời gian Âm lịch
Hàm tạo của lớp `VanTran\LunarCalendar\LunarDateTime` có thể phân tích cú pháp 1 chuỗi thời gian Âm lịch đầu vào và chuyển đổi nó thành thời gian chính xác. Danh sách một số định dạng phổ biến nhất được hỗ trợ:

| Thời gian đầu vào         | Định dạng được hỗ trợ    |
| ------------------------- | ------------------------ |
|20/10/2020                 | d/m/Y                    |
|20-10-2020                 | d/m/Y                    |
|2020-10-20                 | Y-m-d                    |
|20/10+/2020                | d/m/Y                    |
|20/10/2020 (+)             | d/m/Y C                  |
|20/10/2020 00:00           | d/m/Y H:i                |
|20/10/2020 00:00 +07:00    | d/m/Y H:i P              |

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

$format = '20/10/2020 20:30:00 +0700';
$lunar = new LunarDateTime($format);

echo $lunar->format('Y-m-d');   // Đầu ra: 2020-10-20
echo "\r\n";
echo $lunar->format('P');       // Đầu ra: +07:00
echo "\r\n";
echo $lunar->format('h:ia');    // Đầu ra: 08:30pm
```

## Xử lý tháng nhuận
Không giống như Dương lịch chỉ nhuận vào tháng 02, tháng Nhuận trong Âm lịch khá phức tạp và không cố định. Để xử lý thời gian Âm lịch trong tháng nhuận, có 2 giải pháp như sau:
- Đặt dấu '+' phía sau tháng nhuận, thích hợp khi sử dụng định dạng phân tách ngày tháng bằng dấu gạch chéo, vd 20/02+/2023. Nếu bạn ưa thích các loại dấu phân tách khác, hãy cân nhắc sử dụng giải pháp thứ 2.
- Đặt '(+)' ở phía sau chuỗi thời gian. Chẳng hạn: 20-02-2023 (+) sẽ dễ đọc hơn 20-02+-2023


```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

$lunar = new LunarDateTime('20/02+/2023 +07:00');

echo $lunar->format('d/m/Y');   // Đầu ra: 20/02+/2023
echo "\r\n";
echo $lunar->format('d-L-Y C');    // Đầu ra: 20-02-2023 (+)
echo "\r\n";

$output = $lunar->format('d-L-Y');

if ($lunar->format('C')) {
    $output .= ' nhuận';
}

echo $output; // Đầu ra: 20-02-2023 nhuận
```

## Khớp dữ liệu bị sai
Tất nhiên, nếu lớp `VanTran\LunarCalendar\LunarDateTime` chỉ nhận 1 đầu vào và định dạng lại đầu ra tùy chỉnh thì nó không mang nhiều ý nghĩa lắm. Điểm thú vị là nó có thể nhận biết được thời gian đầu vào bị sai và tự động sửa chữa lại cho đúng. Một số trường hợp thường xảy ra:
- Bạn đang ở ngày 29 của một tháng, nhưng bạn không biết chính xác ngày mai là ngày 30 hay mùng 01 của tháng kế tiếp.
- Năm nay nhuận tháng 8, nhưng bạn nhớ lầm là tháng 5

Cụ thể hơn, hãy xem xét ví dụ sau:

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

// Năm 2025 Âm lịch tháng 12 chỉ có 29 ngày
$lunar = new LunarDateTime('30/12/2025 +07:00');
echo $lunar->format('d/m/Y'); // 01/01/2026
```

## Tính ngày tháng Âm lịch từ Dương lịch
Thông thường, Dương lịch được sử dụng phổ biến và rộng rãi hơn trong mọi trường hợp. Lớp `LunarDateTime` cũng cung cấp các phương pháp để tính toán ngày tháng Âm lịch từ một mốc Dương lịch.

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

// Tìm ngày Âm lịch từ ngày 11 tháng 05 năm 2023 dương lịch
$timezone = new DateTimeZone('+0700');
$lunar = new LunarDateTime('2023-05-11', $timezone, LunarDateTime::GREGORIAN_INPUT);

echo $lunar->format('d/m/Y'); // 22/03/2023

// Phương thức tĩnh
$lunar = LunarDateTime::createFromGregorian('2023-05-11', $timezone);
echo $lunar->format('d/m/Y'); // 22/03/2023
```

## Xử lý múi giờ địa phương
Để lập lịch chính xác, múi giờ địa phương là một tham số trọng yếu. Đối với Âm lịch Việt Nam, múi giờ tiêu chuẩn là `UTC+07:00`. Đồng thời cũng có 1 múi giờ khác là `Asia/Ho_Chi_Minh`. Tuy nhiên, hãy lưu ý nếu bạn sử dụng múi giờ này vì nó bị ảnh hưởng bởi một số yếu tố địa chính trị. Chẳng hạn từ 15 tháng 3 năm 1945 – tháng 9 năm 1945, trong giai đoạn Nhật đô hộ Việt Nam, múi giờ khi đó bị áp đặt là `UTC+09:00`. 

Có 2 phương pháp để bổ sung múi giờ địa phương khi khởi tạo thời gian Âm lịch:
- Truyền trực tiếp tham số múi giờ được hỗ trợ vào chuỗi thời gian
- Khởi tạo và sử dụng đối tượng `DateTimeZone`

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;

// Truyền trực tiếp múi giờ địa phương (+0700)
$lunar = new LunarDateTime('25/02/2023 +0700');
echo $lunar->format('d/m/Y P'); // 25/02/2023 +07:00

$lunar = new LunarDateTime('25/02/2023 Asia/Ho_Chi_Minh');
echo $lunar->format('d/m/Y P'); // 25/02/2023 +07:00

// Khởi tạo đối tượng DateTimeZone
$timezone = new DateTimeZone('+0700');
$lunar = new LunarDateTime('25/02/2023', $timezone);
echo $lunar->format('d/m/Y P'); // 25/02/2023 +07:00
```
