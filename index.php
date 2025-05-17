<?php

$valid_user = "admin";
$valid_pass = "admin01";

function input($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

function generateVNPhonePassword() {
    $prefixes = ['03', '09'];
    $prefix = $prefixes[array_rand($prefixes)];

    $phone = $prefix;
    for ($i = 0; $i < 9; $i++) { 
        $phone .= rand(0, 9);
    }

    return $phone;
}
function showProgressBar($total = 20, $delay = 50000) {
    for ($i = 0; $i <= $total; $i++) {
        $percent = intval(($i / $total) * 100);
        $bar = str_repeat("█", $i) . str_repeat(" ", $total - $i);
        echo "\r🔍 Cracking: [{$bar}] {$percent}%";
        usleep($delay); // micro giây (1000000 = 1s)
    }
    echo "\n";
}
function showBruteForceAnimation($length = 8, $duration = 2) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $start = microtime(true);

    while ((microtime(true) - $start) < $duration) {
        $fake = '';
        for ($i = 0; $i < $length; $i++) {
            $fake .= $chars[rand(0, strlen($chars) - 1)];
        }
        echo "\r🔐 Đang tìm: $fake ";
        usleep(50000); 
    }
    echo "\n";
}
function inputHidden($prompt = "Mật khẩu: ") {
    if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        // Trên Windows thì PHP không hỗ trợ ẩn trực tiếp. Gợi ý: dùng PowerShell hoặc bỏ qua
        echo $prompt;
        return trim(fgets(STDIN));
    } else {
        // Trên Unix/Linux/Termux/macOS
        echo $prompt;
        system('stty -echo');        // Tắt echo
        $input = trim(fgets(STDIN));
        system('stty echo');         // Bật lại echo
        echo "\n";
        return $input;
    }
}

echo "==== ĐĂNG NHẬP ====\n";
$username = input("Tên đăng nhập: ");
$password = inputHidden("Mật khẩu: ");

if ($username !== $valid_user || $password !== $valid_pass) {
    echo "\033[1;31m❌ Sai tài khoản hoặc mật khẩu.\033[0m\n";
    exit;
}
if ($username !== $valid_user || $password !== $valid_pass) {
    echo "❌ Sai tài khoản hoặc mật khẩu.\n";
    exit;
}

echo "\n✅ Đăng nhập thành công!\n\n";

while (true) {
    echo "=============================\n";
    echo "📡 DANH SÁCH HOTSPOT WIFI\n";
    echo "=============================\n";
    echo "1. WIFI INFORMATION\n";
    echo "2. CRACK PASSWORD\n";
    echo "3. LIST PASSWORD WIFI\n";
    echo "5. Làm sạch các thông tin wifi đã lưu\n";
    echo "Chọn: ";

    $choice = trim(fgets(STDIN));
    echo "\n";

    switch ($choice) {
        case '1':
            while (true) {
                echo "WIFI INFORMATION:\n";
                echo "- SSID: Lau3\n";
                echo "- BSSID: 90-0F-0C-26-96-17\n";
                echo "- Tín hiệu: -50 dBm\n";
                echo "- Channel: 6\n";
                echo "- Password: 01062017\n";
                echo "- IPv4 Address: 192.168.110.11\n";
                echo "- Link speed: 144/144 (Mbps)\n";
                echo "\nNhấn 0 để quay lại menu chính: ";
                $exit = trim(fgets(STDIN));
                if ($exit === '0') break;
            }
            break;

        case '2':
            while (true) {
                echo "🔐 CRACK PASSWORD:\n";
                echo "Nhập tên wifi (hoặc 0 để quay lại): ";
                $ssid = trim(fgets(STDIN));
                if ($ssid === '0') break;

                echo "Đang trong quá trình rà soát mật khẩu...\n";
                echo "\033[1;32m✅ Thành công!\033[0m\n";
                showProgressBar();
                showBruteForceAnimation();
                sleep(2);
                $fake_pass = generateVNPhonePassword();
                echo "✅ Đã tìm thấy mật khẩu cho $ssid: $fake_pass\n";
                $list = [];
                if (file_exists("wifi_pass.json")) {
                    $list = json_decode(file_get_contents("wifi_pass.json"), true);
                }
                $list[] = ['ssid' => $ssid, 'password' => $fake_pass];
                file_put_contents("wifi_pass.json", json_encode($list));
                echo "\n";
            }
            break;

        case '3':
            while (true) {
                echo "📜 LIST PASSWORD WIFI:\n";
                if (file_exists("wifi_pass.json")) {
                    $list = json_decode(file_get_contents("wifi_pass.json"), true);
                    foreach ($list as $i => $item) {
                        echo ($i + 1) . ". SSID: " . $item['ssid'] . " | Password: " . $item['password'] . "\n";
                    }
                } else {
                    echo "⚠️  Chưa có dữ liệu.\n";
                }
                echo "\nNhấn 0 để quay lại menu chính: ";
                $exit = trim(fgets(STDIN));
                if ($exit === '0') break;
            }
            break;
        case '4':
            echo "❗ Bạn có chắc chắn muốn xoá toàn bộ dữ liệu? (y/n): ";
            $confirm = trim(fgets(STDIN));
            if (strtolower($confirm) === 'y') {
                file_put_contents("wifi_pass.json", "[]");
                echo "✅ Đã xoá toàn bộ dữ liệu.\n";
            } else {
                echo "❎ Huỷ xoá dữ liệu.\n";
            }
            break;
        case '5':
            echo "👋 Tạm biệt!\n";
            exit;

        default:
            echo "❌ Lựa chọn không hợp lệ.\n";
    }
    echo "\nNhấn Enter để tiếp tục...";
    fgets(STDIN);

    if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        system('cls');
    } else {
        system('clear');
    }
}
