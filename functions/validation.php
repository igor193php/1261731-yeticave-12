<?php
require_once('constants.php');

/**
 * Проверка даты введеной пользователем в форме, что дата не меньше текущей даты.
 *
 * @param string $date Дата введенная пользователем
 * @return string|null
 */
function compareDates(string $date)
{
    $current_date = date(DATE);
    $format_to_check = DATE;

    $dateTimeObj = date_create_from_format($format_to_check, $_POST[$date]);


    if ($_POST[$date] <= $current_date || $dateTimeObj == false) {
        return "Введите дату больше текущей даты в формате ГГГГ-ММ-ДД";
    }
}


/**
 * @param $required_fields
 * @return array
 * передаем массив с именами полей формы
 * парсим его и проверяем POST элементы на заолненность
 * если POST пуст то записываем в массив поле с заданным тестом
 * возвращаем массив ошибок [название поля => суть ошибки]
 */
function isEmpty($required_fields)
{
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }
    return $errors = array_filter($errors);
}


/**
 * Проверка на корректное заполнение поля категории лота в форме добавления лота
 *
 * @param string $name
 * @return string
 */
function validateCategory(string $name)
{
    if ($_POST[$name] === 'Выберите категорию') {
        return "Не выбрана категория";
    }
}

/**
 * Проверка формата файла на соответсвие формату jpg, jpeg, png
 *
 * @param $name
 * @return string
 */
function validateFiles($name)
{
    if (!empty($_FILES[$name]['name'])) {
        $file_tmp = $_FILES[$name]['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $png = "image/png";
        $jpeg = "image/jpeg";


        if (strcmp($file_type, $png) == 1 || strcmp($file_type, $jpeg) == 1) {

            return "Загрузите картинку (графический файл) в формате: jpg, jpeg, png";
        }

    }
}

/**
 * Проверка поля ставки в форме добавления лота на положителльное число
 *
 * @param $name
 * @return string
 */
function validateLotRate($name)
{
    if ($_POST[$name] <= 0) {
        return "Введите число больше ноля";
    }
}

/**
 * Проверка поля шаг ставки в форме добавления лота на положителльное число и то что оно целое
 *
 * @param $name
 * @return string
 */
function validateLotStep($name)
{
    $name = $_POST[$name];
    $point = '.';
    if (!is_numeric($name) || strpos($name, $point) || $name <= 0) {
        return "Введите цело положительное чило";
    }
}

/**
 * Проверка поля почты в форме добавления нового ользователя на уникальность
 *
 * @param $name
 * @return string
 */
function validateFormatEmail($name)
{
    $name = $_POST[$name];
    $items = queryResult(
        connectToDatabase(),
        "SELECT email FROM Users WHERE email LIKE '" . $name . "'");

    if (!empty($items)) {
        return "Email уже занят";
    }

}

/**
 * Валидация информации о пользователе при его логировании
 *
 * @param $email
 * @param $password
 * @return array
 */
function checkUser($email, $password)
{
    $errors = [];
    $email = $_POST[$email];


    $sql_hash = "SELECT password FROM Users WHERE email='$email'";
    $hash_from_db = queryResult(connectToDatabase(), $sql_hash);
    $hash_from_db = $hash_from_db[0][$password];

    if (empty($hash_from_db)) {
        $errors['email'] = "Email не верен";
    }

    if (!password_verify($_POST[$password], $hash_from_db)) {
        $errors['password'] = "Не корректный пароль";
    }

    return $errors;
}

/**
 * Сравнение ставки пользователя с поседней ставкой
 *
 * @param $id_lot
 * @param $cost
 * @return array
 */
function validateCost(int $id_lot, string $cost)
{
    $errors = null;
    $last_cost_lot = getCurrentCost($id_lot);
    $step_cost_lot = getStepCostLots($id_lot);
    $control_cost = $last_cost_lot[0]['cost'] + $step_cost_lot[0]['step_cost'];
    $cost_from_user = $_POST[$cost];
    if($cost_from_user <= 0) {
        $errors = "Не корректная цена";
    } elseif ($cost_from_user < $control_cost)  {
        $errors = "Введена цена ниже текущей";
    }

    return $errors;
}
