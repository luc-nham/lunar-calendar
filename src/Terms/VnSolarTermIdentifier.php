<?php

namespace LucNham\LunarCalendar\Terms;

use LucNham\LunarCalendar\Attributes\SolarTermAttribute;

#[SolarTermAttribute(
    key: 'lap_xuan',
    name: 'Lập Xuân',
    position: 0,
    ls: 315.0
)]
#[SolarTermAttribute(
    key: 'vu_thuy',
    name: 'Vũ Thủy',
    position: 1,
    ls: 330.0
)]
#[SolarTermAttribute(
    key: 'kinh_trap',
    name: 'Kinh Trập',
    position: 2,
    ls: 345.0
)]
#[SolarTermAttribute(
    key: 'xuan_phan',
    name: 'Xuân Phân',
    position: 3,
    ls: 0.0
)]
#[SolarTermAttribute(
    key: 'thanh_minh',
    name: 'Thanh Minh',
    position: 4,
    ls: 15.0
)]
#[SolarTermAttribute(
    key: 'coc_vu',
    name: 'Cốc Vũ',
    position: 5,
    ls: 30.0
)]
#[SolarTermAttribute(
    key: 'lap_ha',
    name: 'Lập Hạ',
    position: 6,
    ls: 45.0
)]
#[SolarTermAttribute(
    key: 'tieu_man',
    name: 'Tiểu Mãn',
    position: 7,
    ls: 60.0
)]
#[SolarTermAttribute(
    key: 'mang_chung',
    name: 'Mang Chủng',
    position: 8,
    ls: 75.0
)]
#[SolarTermAttribute(
    key: 'ha_chi',
    name: 'Hạ Chí',
    position: 9,
    ls: 90.0
)]
#[SolarTermAttribute(
    key: 'tieu_thu',
    name: 'Tiểu Thử',
    position: 10,
    ls: 105.0
)]
#[SolarTermAttribute(
    key: 'dai_thu',
    name: 'Đại Thử',
    position: 11,
    ls: 120.0
)]
#[SolarTermAttribute(
    key: 'lap_thu',
    name: 'Lập Thu',
    position: 12,
    ls: 135.0
)]
#[SolarTermAttribute(
    key: 'xu_thu',
    name: 'Xử Thử',
    position: 13,
    ls: 150.0
)]
#[SolarTermAttribute(
    key: 'bach_lo',
    name: 'Bạch Lộ',
    position: 14,
    ls: 165.0
)]
#[SolarTermAttribute(
    key: 'thu_phan',
    name: 'Thu Phân',
    position: 15,
    ls: 180.0
)]
#[SolarTermAttribute(
    key: 'han_lo',
    name: 'Hàn Lộ',
    position: 16,
    ls: 195.0
)]
#[SolarTermAttribute(
    key: 'suong_giang',
    name: "Sương Giáng",
    position: 17,
    ls: 210.0
)]
#[SolarTermAttribute(
    key: 'lap_dong',
    name: 'Lập Đông',
    position: 18,
    ls: 225.0
)]
#[SolarTermAttribute(
    key: 'tieu_tuyet',
    name: 'Tiểu Tuyết',
    position: 19,
    ls: 240.0
)]
#[SolarTermAttribute(
    key: 'dai_tuyet',
    name: 'Đại Tuyết',
    position: 20,
    ls: 255.0
)]
#[SolarTermAttribute(
    key: 'dong_chi',
    name: 'Đông Chí',
    position: 21,
    ls: 270.0
)]
#[SolarTermAttribute(
    key: 'tieu_han',
    name: 'Tiểu Hàn',
    position: 22,
    ls: 285.0
)]
#[SolarTermAttribute(
    key: 'dai_han',
    name: 'Đại Hàn',
    position: 23,
    ls: 300.0
)]
readonly class VnSolarTermIdentifier extends SolarTermIdentifier {}
