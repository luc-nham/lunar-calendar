# Hệ thống Can Chi trong Âm lịch
Để biểu diễn các giá trị Thiên can và Địa chi trong Âm lịch, chúng ta sẽ sử dụng lớp `LunarSexagenary`. Lớp này được thiết kế để có khả năng sinh ra các dữ liệu về Can Chi của năm tháng ngày giờ Âm lịch.

## Khởi tạo đối tượng
`LunarSexagenary` được kế thừa từ `LunarDateTime`, do vậy cách thức khởi tạo đối tượng và sử dụng cũng tương tự như lớp cha. Xem lại [Biểu diễn thời gian Âm lịch](/docs/Vietnamese/02-ngay-thang-nam-am-lich.md)

```php
<?php

use LunarCalendar\LunarSexagenary;

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Khởi tạo đối tượng với mốc thời gian 'hiện tại'
$sexagenary = new LunarSexagenary();

// Khởi tạo đối tượng với mốc thời gian tùy chỉnh, ví dụ ngày 17 tháng 3 năm 2016, lúc 10 giờ sáng (dương lịch)
//$sexagenary = new LunarSexagenary('3/17/2016 10:00:00');

var_dump($sexagenary);
```
## Lấy dữ liệu Can Chi
Để lấy dữ liệu về một Thiên can hay Địa chi cụ thể, sử dụng phương thức `getTerm()`. Phương thức này chấp nhận một tham là một ký tự đại diện cho dữ liệu muốn lấy như mô tả sau đây:
- `D`: Lấy thiên can của ngày (tương ứng với mốc thời gian khi khởi tạo đối tượng)
- `d`: Lấy địa chi của ngày
- `M`: Lấy thiên can của tháng
- `m`: Lấy địa chi của tháng
- `Y`: Lấy thiên can của năm
- `y`: Lấy địa chi của năm
- `H`: Lấy thiên can của giờ
- `h`: Lấy địa chi của giờ
- `N`: Lấy thiên can của giờ Tý (giờ bắt đầu ngày mới)

```php
<?php

// Lấy thiên can của ngày
$dayHeavenlyStem = $sexagenary->getTerm('D'); 
var_dump($dayHeavenlyStem);

// Đầu ra tương tự
// C:\Users\caova\xampp\htdocs\lunar-calendar\index.php:17:
// object(LunarCalendar\Formatter\LunarHeavenlyStemFormatter)[5]
//   protected 'offset' => int 4
//   protected 'key' => string 'mau' (length=3)
//   protected 'label' => string 'Mậu' (length=5)

// Hiển thị nhãn của can ngày
echo $dayHeavenlyStem->getLabel();
```
Thực hiện tương tự như ví dụ trên, bạn có lấy được tất cả dữ liệu về hệ thống thiên can địa chi của năm tháng ngày giờ. Mỗi đối tượng trả về bao gồm 3 thuộc tính: `offset`, `key` và `label`:
- `offset`: là vị trí nhận biết của đối tượng trong nhóm đối tượng. Ví dụ trong 10 can từ Giáp đến Quý, thì Giáp được đánh số đầu tiên có offset là 0, đếm thuận đến Quý thì có offset là 9. Trong nhóm 12 địa chi thì Tý được đánh số 0, đếm thuận đến Hợi là 11. Để lấy giá trị của thuộc tính, sử dụng phương thức `getOffset()`.
- `key`: Được sử dụng tương tự như `offset`, dùng để nhận biết đối tượng, nhưng dùng chuỗi định danh thay vì số. Thuộc tính này chủ yếu nhằm hỗ trợ cho kỹ thuật phát triển. Ví dụ, khi bạn mở rộng chương trình, bạn có thể muốn sử dụng phép so sánh: `if($term->getKey() == 'ty')` thay vì `if($term->getOffset() == 0)`. Để lấy giá trị thuộc tính, sử dụng `getKey()`, để đặt giá trị tùy chỉnh cho thuộc tính, sử dụng `setKey()`.
- `label`: Được sử dụng cho mục đích hiển thị ở định dạng người đọc. Để lấy giá trị thuộc tính, sử dụng `getLabel()`, để đặt giá trị cho thuộc tính, sử dụng `setLabel()`.
