# Làm việc với Hệ thống Can Chi
Ngày tháng Âm lịch luôn song hành với Hệ thống Can Chi, không chỉ để tra cứu thời gian mà còn được sử dụng cho nhiều mục đích khác nhau, phổ biến nhất là trong các môn có tính chất chiêm đoán trong văn hóa Phương Đông.

Lớp `LunarSexagenary` được thiết kế để có thể tương tác với hệ thống 10 Can và 12 Chi.

## 1. Khởi tạo hệ thống Can Chi
Lớp `LunarSexagenary` yêu cầu tiêm phụ thuộc một đối tượng `LunarDateTime`, nó sẽ sử dụng các dữ liệu thời gian Âm lịch để chuyển đổi thành các mốc Can Chi tương ứng.

```php
<?php

use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

$lunar = new LunarDateTime('2023-04-30 13:00 +07:00');
$sexagenary = new LunarSexagenary($lunar);
```

## 2. Định dạng đầu ra
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
*Bảng 2.1*

Sử dụng Ký tự đơn khi bạn chỉ muốn truy xuất giá trị của một đối tượng duy nhất, chẳng hạn Can ngày hoặc Can tháng; sử dụng Ký tự kết hợp khi bạn muốn định dạng một liên kết các đối tượng với nhau.

> *Phiên bản 2.0.0 chỉ hỗ trợ định dạng Can Chi bằng tiếng Việt. Ví dụ: Giáp, Ất, Dần Mão...*

### 2.1 Truy xuất giá trị đơn lẻ
Trường hợp bạn chỉ cần định dạng một đối tượng Can hoặc Chi duy nhất, sử dụng Ký tự đơn là phương pháp nhanh chóng và dễ dàng nhất để đạt được giá trị mong muốn. Chẳng hạn thay vì `'%D'` hãy sử dụng `'D'`. Mặc dù cả 2 cách đều cho ra kết quả giống nhau, nhưng thời gian thực thi sẽ khác nhau.

```php
// Can ngày hôm nay là Bính
echo 'Can ngày hôm nay là ' . $sexagenary->format('D');

// Chi ngày hôm nay là Ngọ
echo 'Chi ngày hôm nay là' . $sexagenary->format('d');
```

### 2.2 Định dạng kết hợp
Lớp `LunarSexagenary` hỗ trợ định dạng kết hợp một nhóm các đối tượng với nhau. Khi đó, hãy sử dụng các ký tự định dạng có tiền tố `%` hoặc hậu tố `+`. Lưu ý trong trường hợp này, ký tự đơn sẽ không hoạt động.

```php
// Ngày Ngọ, tháng Tị, năm Quý Mão
echo $sexagenary->format('Ngày %d, tháng %m, năm %Y %y');

// ngày Bính Ngọ, tháng Định Tị, năm Quý Mão, giờ Kỷ Mùi
echo $sexagenary->format('ngày D+, tháng M+, năm Y+, giờ H+');
```

## 3. Khởi tạo đối tượng Can hoặc Chi
Nếu lớp `LunarSexagenary` chỉ có khả năng định dạng các nhãn / tên Can hoặc Chi thì nó sẽ gây ra nhiều bất tiện trong nhiều trường hợp. Chẳng hạn, bạn cần tạo một biểu mẫu lựa chọn Can Chi, hay một biểu thức so sánh, sẽ tốt hơn nếu chúng ta có thể sử dụng một số hoặc ký tự đại diện thay vì tên hiển thị của đối tượng:

```php

    // Sử dụng tên hiển thị, phương pháp này hoạt động, nhưng mã xấu và bất tiện
    $day = 'Giáp';

    if ($day == 'Giáp') {
        echo "Làm sao tối viết được tiếng Việt khi không có UNIKEY hay gì đó tương tự?";
        echo "Giáp là gì? Tôi không phải người Việt.";
        echo "Giáp? What dose it mean?";
    }

    // Sử dụng số đại diện. Thay vì cần một bộ gõ UNIKEY, ai cũng có thể viết mã. Chỉ cần nắm quy tắc đại diện, họ sẽ
    // hiểu 0 đại diện cho can đầu tiên, mà nó có thể là bất kỳ tên gọi nào của ngôn ngữ khác.
    $day = 0;

    if ($day === 0) {
        echo "Hôm này là ngày Giáp";
        echo "Today is day of Giap year of Monkey.";
    }

    // Sử dụng ký tự đại diện, tương tự như số đại diện
    $day = 'a';

    if ($day === 'a') {
        echo "Hôm này là ngày Giáp";
        echo "Today is day of Giap";
    }
```

Để lấy các giá trị số và ký tự đại diện, lớp `LunarSexagenary` cung cấp một phương thức `getTerm`. Với tham số duy nhất là ký tự định dạng đại diện (xem bảng 2.1), lưu ý, hàm chỉ hỗ trợ danh sách các ký tự đơn: D, d, m, m, Y, y, H, h, N.

Bảng dưới đây liệt kê các số và ký tự đại diện tương ứng với 10 Can và 12 Chi.

|  Số  | Ký tự |   Can  |   Chi   |
| ---- | ----- | ------ | ------- |
|   0  |   a   |  Giáp  |   Tý    |
|   1  |   b   |  Ất    |   Sửủ   |
|   2  |   c   |  Bính  |   Dần   |
|   3  |   d   |  Đinh  |   Mão   |
|   4  |   e   |  Mậu   |   Thìn  |
|   5  |   f   |  Kỷ    |   Tị    |
|   6  |   g   |  Canh  |   Ngọ   |
|   7  |   h   |  Tân   |   Mùi   |
|   8  |   i   |  Nhâm  |   Thân  |
|   9  |   j   |  Quý   |   Dậu   |
|  10  |   k   |        |   Tuất  |
|  11  |   l   |        |   Hợi   |

*Bảng 3.1*

```php
$dayStem = $sexagenary->getTerm('D');
$dayBranch = $sexagenary->getTerm('d');

// Can ngày Bính số đại diện là 2, ký tự đại diện là c
echo $dayStem->getIndex() . '| ' . $dayStem->getCharacter() . "\r\n";

// Chi ngày Ngọ có số đại diện là 6, ký tự đại diện là g
echo $dayBranch->getIndex() . '| ' . $dayBranch->getCharacter()  . "\r\n";

// Phân biệt 1 Can
echo $dayStem->getType(); // stem
echo "\r\n";

// Phân biệt 1 Chi
echo $dayBranch->getType(); // branch
```
## 4. Tùy chỉnh tên Can Chi

Trong phiên bản 2.0.0 chỉ hỗ trợ định dạng tên Can Chi bằng Tiếng Việt. Tuy nhiên, có nhiều cách để bạn có thể bạn hòan toàn có thể tùy chỉnh lại tên mong muốn. Một ví dụ đơn giản sử dụng số đại diện:

```php
// Tùy chỉnh tên hiển thị 12 Địa chi theo Tiếng Anh
$customBranchLabels = [
    'Rat',
    'Water buffalo',
    'Tiger',
    'Cat',
    'Dragon',
    'Snake',
    'Horse',
    'Goat',
    'Monkey',
    'Rooster',
    'Dog',
    'Pig'
];

// Chi của tháng
$monthBranch = $sexagenary->getTerm('m');
$index = $monthBranch->getIndex();

echo $customBranchLabels[$index]; // Snake
```