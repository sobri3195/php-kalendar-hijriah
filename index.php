<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kalender Hijriyah</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            width: 100px;
            height: 100px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ccc;
        }
        .today {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
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

        // menentukan bulan dan tahun
        $month = isset($_GET['month']) ? $_GET['month'] : date('n');
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        // query untuk mengambil data kalender dari database
        $sql = "SELECT tanggal, hijriyah FROM kalender WHERE bulan = '$month' AND tahun = '$year'";
        $result = $conn->query($sql);

        // membuat tabel kalender
        echo "<table>";
        echo "<tr><th colspan='7'>" . date('F Y', strtotime($year . '-' . $month . '-01')) . "</th></tr>";
        echo "<tr><th>Senin</th><th>Selasa</th><th>Rabu</th><th>Kamis</th><th>Jumat</th><th>Sabtu</th><th>Minggu</th></tr>";

        // mengisi sel-sel tabel dengan tanggal dan tanggal Hijriyah
        $day_count = 1;
        $num_days = date('t', strtotime($year . '-' . $month . '-01'));
        $date_info = getdate(strtotime($year . '-' . $month . '-01'));
        $day_of_week = $date_info['wday'] == 0 ? 7 : $date_info['wday'];

        for ($row = 1; $row <= 6; $row++) {
            echo "<tr>";
            for ($col = 1; $col <= 7; $col++) {
                if ($day_count <= $num_days && ($row > 1 || $col >= $day_of_week)) {
                    $row_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_count));
                    $row_result = $result->fetch_assoc();
                    $hijri_date = $row_result['hijriyah'];

                    if ($row_date == date('Y-m-d')) {
                        echo "<td class='today'>$day_count<br>$hijri_date</td>";
                    } else {
                        echo "<td>$day_count<br>$hijri_date</td>";
                    }

                    $day_count++;
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
            if ($day_count > $num_days) {
                break;
            }
        }
        echo "</table>";

        $conn->close();
    ?>
</body>
</html>
