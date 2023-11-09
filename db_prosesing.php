<?php
include 'db_koneksi.php';

$all_hours = range(0, 23);

$query_today = "SELECT HOUR(time) AS hour, AVG(conc) AS today
               FROM `datapm25_new`
               WHERE DATE(time) = CURDATE() AND HOUR(time) >= 0 AND HOUR(time) <= 23
               GROUP BY HOUR(time)
               ORDER BY HOUR(time) ASC";

$result_today = mysqli_query($koneksi, $query_today);

$data_today = array();
while ($row = mysqli_fetch_array($result_today)) {
    $hour = $row['hour'];
    $today_conc = (float) $row['today'];

    $data_today[] = array("hour" => $hour, "today" => $today_conc);
}

$query_yesterday = "SELECT HOUR(time) AS hour, AVG(conc) AS yesterday
                  FROM `datapm25_new`
                  WHERE DATE(time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND HOUR(time) >= 0 AND HOUR(time) <= 23
                  GROUP BY HOUR(time)
                  ORDER BY HOUR(time) ASC";

$result_yesterday = mysqli_query($koneksi, $query_yesterday);

$data_yesterday = array();
while ($row = mysqli_fetch_array($result_yesterday)) {
    $hour = $row['hour'];
    $yesterday_conc = (float) $row['yesterday'];

    $data_yesterday[] = array("hour" => $hour, "yesterday" => $yesterday_conc);
}

mysqli_close($koneksi);

$combined_data = array();
foreach ($all_hours as $hour) {
    $today_conc = 0;
    $yesterday_conc = 0;

    foreach ($data_today as $item) {
        if ($item['hour'] == $hour) {
            $today_conc = $item['today'];
            break;
        }
    }

    foreach ($data_yesterday as $item) {
        if ($item['hour'] == $hour) {
            $yesterday_conc = $item['yesterday'];
            break;
        }
    }

    $combined_data[] = array("hour" => $hour, "today" => $today_conc, "yesterday" => $yesterday_conc);
}

header('Content-Type: application/json');
echo json_encode($combined_data);
?>