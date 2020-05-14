<?php
session_start();

$title = "Главная";
$is_auth = rand(0, 1);
$user_name = $_SESSION['user']; // укажите здесь ваше имя

require_once('functions/connect_to_db.php');
require_once('functions/query_result.php');
require_once('functions/cost.php');
require_once('functions/include_template.php');
require_once('functions/count_time.php');


$db_connection = connectToDatabase();

$sql_lots = "SELECT Categories.name AS category, Lots.id, Lots.name, cost_start, photo, date_finished AS expiration_time FROM Lots
    INNER JOIN Categories ON Lots.category_id=Categories.id
    LEFT JOIN Rates ON Rates.lot_id=Lots.id
    ORDER BY Lots.id DESC LIMIT 6";
$lots = queryResult($db_connection, $sql_lots);

$sql_categories = "SELECT name, symbol_code FROM Categories ORDER BY id ASC";
$categories = queryResult($db_connection, $sql_categories);


$menu_category = includeTemplate('menu_index.php', ['categories' => $categories]);
$page_content = includeTemplate('main.php', ['lots' => $lots, 'menu_category' => $menu_category]);
$head = includeTemplate('head_lot_index.php');
$layout_content = includeTemplate('layout.php', [
    'head' => $head,
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories
]);


print($layout_content);

