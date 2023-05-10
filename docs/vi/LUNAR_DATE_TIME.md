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

## Xử lý đầu vào
### Đầu vào là thời gian Âm lịch
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

### Xử lý đầu vào là tháng nhuận
Không giống như Dương lịch, tháng Nhuận trong Âm lịch khá phức tạp và không cố định vào tháng 02. Để xử lý thời gian Âm lịch trong tháng nhuận, có 2 giải pháp như sau:
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

### Khớp dữ liệu bị sai
Tất nhiên, nếu lớp `VanTran\LunarCalendar\LunarDateTime` chỉ nhận 1 đầu vào và định dạng lại đầu ra tùy chỉnh thì nó không mang nhiều ý nghĩa lắm. Điểm thú vị là nó có thể nhận biết được thời gian đầu vào bị sai và tự động sửa chữa lại cho đúng. Một số trường hợp thường xảy ra:
- Bạn đang ở ngày 29 của một tháng, nhưng bạn không biết chính xác ngày mai là ngày 30 hay mùng 01 của tháng kế tiếp.
- Năm nay nhuận tháng 8, nhưng bạn nhớ lầm là tháng 5

Cụ thể hơn, hãy xem xét ví dụ sau: