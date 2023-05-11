# Làm việc với Hệ thống Can Chi
Ngày tháng Âm lịch luôn song hành với Hệ thống Can Chi, không chỉ để tra cứu thời gian mà còn được sử dụng cho nhiều mục đích khác nhau, phổ biến nhất là trong các môn có tính chất chiêm đoán trong văn hóa Phương Đông.

Lớp `LunarSexagenary` được thiết kế để có thể tương tác với hệ thống 10 Can và 12 Chi.

## Khởi tạo hệ thống Can Chi
Lớp `LunarSexagenary` yêu cầu tiêm phụ thuộc một đối tượng `LunarDateTime`, nó sẽ sử dụng các dữ liệu thời gian Âm lịch để chuyển đổi thành các mốc Can Chi tương ứng.

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

$lunar = new LunarDateTime('2023-04-30 13:00 +07:00');
$sexagenary = new LunarSexagenary($lunar);
```

## Định dạng đầu ra
Bảng dưới đây lệt kê các ký tự đại diện được sử dụng riêng lẻ hoặc kết hợp để định dạng các đối tượng Can Chi.

| Ký tự đơn | Ký tự kết hợp |               Mô tả               |
| --------- | ------------- | --------------------------------- |
|     D     |      %D       |           Can của ngày            |
|     d     |      %d       |           Chi của ngày            |
|     M     |      %M       |           Can của tháng           |
|     m     |      %m       |           Chi của tháng           |
|     Y     |      %Y       |           Can của năm             |
|     y     |      %y       |           Chi của năm             |
|     H     |      %H       |           Can của giờ             |
|     h     |      %h       |           Chi của giờ             |
|     N     |      %N       | Can của giờ Tý (bắt đầu ngày mới) |
|           |      D+       |       Tương đương `%D %d`         |
|           |      M+       |       Tương đương `%M %m`         |
|           |      Y+       |       Tương đương `%Y %y`         |
|           |      H+       |       Tương đương `%H %h`         |

Sử dụng Ký tự đơn khi bạn chỉ muốn truy xuất giá trị của một đối tượng duy nhất, chẳng hạn Can ngày hoặc Can tháng; sử dụng Ký tự kết hợp khi bạn muốn định dạng một liên kết các đối tượng với nhau.

> *Phiên bản 2.0.0 chỉ hỗ trợ định dạng Can Chi bằng tiếng Việt. Ví dụ: Giáp, Ất, Dần Mão...*

### Truy xuất giá trị đơn lẻ
Trường hợp bạn chỉ cần định dạng một đối tượng Can hoặc Chi duy nhất, sử dụng Ký tự đơn là phương pháp nhanh chóng và dễ dàng nhất để đạt được giá trị mong muốn. Chẳng hạn thay vì `'%D'` hãy sử dụng `'D'`. Mặc dù cả 2 cách đều cho ra kết quả giống nhau, nhưng thời gian thực thi sẽ khác nhau.

```php
// Can ngày hôm nay là Bính
echo 'Can ngày hôm nay là ' . $sexagenary->format('D');

// Chi ngày hôm nay là Ngọ
echo 'Chi ngày hôm nay là' . $sexagenary->format('d');
```

### Định dạng kết hợp
Lớp `LunarSexagenary` hỗ trợ định dạng kết hợp một nhóm các đối tượng với nhau. Khi đó, hãy sử dụng các ký tự định dạng có tiền tố `%` hoặc hậu tố `+`. Lưu ý trong trường hợp này, ký tự đơn sẽ không hoạt động.

```php
// Ngày Ngọ, tháng Tị, năm Quý Mão
echo $sexagenary->format('Ngày %d, tháng %m, năm %Y %y');

// ngày Bính Ngọ, tháng Định Tị, năm Quý Mão, giờ Kỷ Mùi
echo $sexagenary->format('ngày D+, tháng M+, năm Y+, giờ H+');
```