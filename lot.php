<?php
$is_auth = rand(0, 1);
$user_name = 'Igor'; // укажите здесь ваше имя
$id = $_GET['id'];


require_once('functions/connect_to_db.php');
require_once('functions/query_result.php');
require_once('functions/cost.php');
require_once('functions/include_template.php');
require_once('functions/count_time.php');

$db_connection = connect_to_db();

$sql_categories = "SELECT name, symbol_code FROM Categories ORDER BY id ASC";
$categories = query_result($db_connection, $sql_categories);
$sql_get_lot = "SELECT Categories.name AS category, Lots.id, Lots.name, cost_start, step_cost, detail, photo, cost, date_finished AS expiration_time FROM Lots 
    INNER JOIN Categories ON Lots.category_id=Categories.id 
    LEFT JOIN Rates ON Rates.lot_id=Lots.id WHERE Lots.id='$id'";
$item_lot = query_result($db_connection, $sql_get_lot);
$time_limited = count_time($item_lot[0]['expiration_time']);
$title = $item_lot[0]['name'];

$menu_lot = include_template('menu_lot.php', ['categories' => $categories]);

if (!isset($id) || empty($item_lot)) {
    $page_content = include_template('main_404.php', ['menu_lot' => $menu_lot]);
}
else {
    $page_content = include_template('main_lot.php', ['menu_lot' => $menu_lot, 'item_lot' => $item_lot, 'time_limited' => $time_limited]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories
]);


print($layout_content);
