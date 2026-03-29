<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$dataFile = 'nav_data.json';

function loadData() {
    global $dataFile;
    if (!file_exists($dataFile)) {
        $default = [
            "categories" => [["id"=>1,"name"=>"默认分类","icon"=>"📁","hidden"=>false]],
            "lines" => [],
            "notice_enabled" => true,
            "notice_title" => "📢 系统公告",
            "notice_text" => "欢迎使用闲时导航",
            "copyright_text" => "© 2026 闲时导航",
            "cardStyle" => "default",
            "cardIconCenter" => false,
            "maintainMode" => false,
            "mt_title" => "系统维护中",
            "mt_desc" => "升级维护中",
            "admin_pwd_hash" => md5("668899")
        ];
        file_put_contents($dataFile, json_encode($default, JSON_UNESCAPED_UNICODE));
    }
    return json_decode(file_get_contents($dataFile), true);
}

function saveData($data) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? '';
$data = loadData();

switch ($action) {
    case 'get':
        echo json_encode($data);
        break;
    case 'save':
        $post = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["error" => "数据格式错误"]);
            exit;
        }
        saveData(array_merge($data, $post));
        echo json_encode(["ok" => true]);
        break;
    default:
        echo json_encode(["error" => "无效操作"]);
}
?>
