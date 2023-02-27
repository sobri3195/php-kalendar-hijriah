<?php

// fungsi untuk mengonversi tanggal Gregorian menjadi Hijriyah
function gregorian_to_hijri($date)
{
    $date = explode('-', $date);
    $year = (int)$date[0];
    $month = (int)$date[1];
    $day = (int)$date[2];

    // konversi tanggal Gregorian menjadi Julian Day
    $jd = JulianToJD($month, $day, $year);

    // menghitung tanggal Hijriyah dari Julian Day
    $hijri = JDToIslamic($jd);

    // format tanggal Hijriyah sebagai string
    $hijri_date = IslamicDate('l, d F Y', $hijri[0], $hijri[1], $hijri[2]);

    return $hijri_date;
}

// mencetak kalender Hijriyah untuk bulan ini
$month = date('m');
$year = date('Y');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// mencetak judul kalender
echo '<h2> Kalender Hijriyah </h2>';

// mencetak tabel untuk kalender
echo '<table>';

// mencetak header untuk tabel kalender
echo '<tr>';
echo '<th> Minggu </th>';
echo '<th> Senin </th>';
echo '<th> Selasa </th>';
echo '<th> Rabu </th>';
echo '<th> Kamis </th>';
echo '<th> Jumat </th>';
echo '<th> Sabtu </th>';
echo '</tr>';

// mencetak baris untuk setiap minggu
for ($week = 1; $week <= 6; $week++) {
    echo '<tr>';

    // mencetak kolom untuk setiap hari dalam minggu
    for ($day = 1; $day <= 7; $day++) {
        // menghitung tanggal Gregorian untuk hari ini
        $gregorian_date = date('Y-m-d', strtotime($year . '-' . $month . '-01 +' . (($week - 1) * 7 + $day - date('w', strtotime($year . '-' . $month . '-01'))) . ' days'));

        // menghitung tanggal Hijriyah untuk hari ini
        $hijri_date = gregorian_to_hijri($gregorian_date);

        // menandai hari ini jika sama dengan tanggal saat ini
        $is_today = ($gregorian_date == date('Y-m-d'));

        // mencetak kolom untuk hari ini
        echo '<td' . ($is_today ? ' style="background-color: yellow;"' : '') . '>';
        echo '<div>' . date('d', strtotime($gregorian_date)) . '</div>';
        echo '<div>' . $hijri_date . '</div>';
        echo '</td>';
    }

    echo '</tr>';
}

echo '</table>';
