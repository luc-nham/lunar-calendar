# Hệ thống Can Chi trong Âm lịch
Để biểu diễn các giá trị Thiên can và Địa chi trong Âm lịch, chúng ta sẽ sử dụng lớp `LunarSexagenary`. Lớp này được thiết kế để có khả năng sinh ra các dữ liệu về Can Chi của năm tháng ngày giờ Âm lịch thông quá phương thức `getTerm()`, và khả năng định dạng dữ liệu thông qua phương phức `format()`.

## Khởi tạo đối tượng
`LunarSexagenary` được kế thừa từ `LunarDateTime`, do vậy cách thức khởi tạo đối tượng và sử dụng cũng tương tự như lớp cha. Xem lại [Biểu diễn thời gian Âm lịch](/docs/Vietnamese/02-ngay-thang-nam-am-lich.md)

```php
<?php

use LunarCalendar\LunarSexagenary;

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/vendor/autoload.php';

// Khởi tạo đối tượng với mốc thời gian 'hiện tại'
$sexagenary = new LunarSexagenary();

// Khởi tạo đối tượng với mốc thời gian tùy chỉnh, ví dụ ngày 17 tháng 3 năm 2016, lúc 10 giờ sáng (dương lịch)
//$sexagenary = new LunarSexagenary('3/17/2016 10:00:00');

var_dump($sexagenary);
```



