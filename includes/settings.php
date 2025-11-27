<?php
// Pastikan koneksi database sudah ada ($conn)
// Fungsi untuk mengambil semua setting ke dalam array
$site_settings = [];
if (isset($conn)) {
    $sql_sett = "SELECT setting_key, setting_value FROM site_settings";
    $res_sett = $conn->query($sql_sett);
    if ($res_sett) {
        while ($row = $res_sett->fetch_assoc()) {
            $site_settings[$row['setting_key']] = $row['setting_value'];
        }
    }
}

// Fungsi helper aman untuk mengambil value (jika key tidak ada, return string kosong/default)
function get_setting($key, $default = '') {
    global $site_settings;
    return isset($site_settings[$key]) ? $site_settings[$key] : $default;
}
?>