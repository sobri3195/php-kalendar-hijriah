<?php
//koneksi ke database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

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
    $hijri_date = IslamicDate('Y-m-d', $hijri[0], $hijri[1], $hijri[2]);

    return $hijri_date;
}

// menyimpan data kalender dalam tabel database
$month = 2; // bulan (1-12)
$year = 2023; // tahun
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// simpan data kalender ke dalam tabel database
for ($day = 1; $day <= $days_in_month; $day++) {
    // hitung tanggal Gregorian untuk hari ini
    $gregorian_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day));

    // hitung tanggal Hijriyah untuk hari ini
    $hijri_date = gregorian_to_hijri($gregorian_date);

    // simpan data ke dalam tabel
    $sql = "INSERT INTO kalender (tanggal, bulan, tahun, hijriyah)
            VALUES ('$day', '$month', '$year', '$hijri_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Data kalender berhasil disimpan untuk tanggal $day<br>";
    } else {
        echo "Terjadi kesalahan saat menyimpan data kalender untuk tanggal $day: " . $conn->error;
    }
}

$conn->close();
?>
