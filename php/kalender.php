
<?php
// Set timezone ke Waktu Standar Arab Saudi
date_default_timezone_set('Asia/Riyadh');

// Tampilkan kalender hijri untuk bulan dan tahun tertentu dalam bentuk gambar
function show_hijri_calendar_image($month, $year) {
    // Konversi tanggal hijri ke format Gregorian
    $hijri_date = new DateTime();
    $hijri_date->setDate($year, $month, 1);
    $timestamp = $hijri_date->getTimestamp();
    $gregorian_date = date('Y-m-d', $timestamp);

    // Ambil informasi kalender hijri menggunakan API dari https://api.aladhan.com/v1/calendar
    $api_url = "https://api.aladhan.com/v1/calendar?latitude=21.422510&longitude=39.826168&method=2&month=$month&year=$year&adjustment=0";
    $json_data = file_get_contents($api_url);
    $calendar_data = json_decode($json_data, true);

    // Tampilkan kalender hijri dalam bentuk gambar
    $im = imagecreatetruecolor(700, 300);
    $bg_color = imagecolorallocate($im, 255, 255, 255);
    $text_color = imagecolorallocate($im, 0, 0, 0);
    imagefill($im, 0, 0, $bg_color);
    $font_path = "arial.ttf";
    imagettftext($im, 20, 0, 320, 30, $text_color, $font_path, "Kalender Hijri " . $calendar_data['data'][0]['date']['hijri']['month']['en'] . " " . $year);
    $y = 60;
    for ($i = 0; $i < 5; $i++) {
        $x = 50;
        for ($j = 0; $j < 7; $j++) {
            $day_count = $i * 7 + $j;
            if ($day_count >= $calendar_data['data'][0]['meta']['month']['total_days']) {
                break;
            }
            $hijri_day = $calendar_data['data'][$day_count]['date']['hijri']['day'];
            $hijri_month = $calendar_data['data'][$day_count]['date']['hijri']['month']['number'];
            $hijri_year = $calendar_data['data'][$day_count]['date']['hijri']['year'];
            if ($hijri_month != $month) {
                $text_color = imagecolorallocate($im, 128, 128, 128);
            } else {
                $text_color = imagecolorallocate($im, 0, 0, 0);
            }
            imagettftext($im, 20, 0, $x, $y, $text_color, $font_path, $hijri_day);
            $x += 90;
        }
        $y += 50;
    }
    header('Content-Type: image/png');
    imagepng($im);
    imagedestroy($im);
}

// Contoh penggunaan
show_hijri_calendar_image(5, 2023);
?>
