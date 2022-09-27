<?php
include_once "utils.php";
$table = "task";
$data = new DB($table);
if (isset($_POST) && !empty($_POST)) {
    $data->save($_POST, $_POST["id"] ?? null);
}
$isEdit = false;
if (isset($_GET['action']) && !empty($_GET['action'])) {
    switch ($_GET["action"]) {
        case 'edit':
            $edit = $data->get($_GET["id"]);
            $isEdit = true;
            break;
        case 'edit-status':
            $status = $_GET["status"];
            $data->save(["status" => $status ? 0 : 1], $_GET["id"]);
            break;
        case 'delete':
            $data->delete($_GET["id"]);
            break;
        default:
            # code...
            break;
    }
}
try {
    $dataVal = $data->get();
} catch (\Throwable $e) {
    print_r($e->getMessage());
    echo "<h3>Please Create task table in your mysql database</h3>";
    $dataVal = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD(TODO)</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form method="post">
        <input type="text" value="<?= $isEdit ? $edit['title'] : null ?>" name="title" placeholder="title">
        <?php if ($isEdit) : ?>
            <input type="hidden" value="<?= $edit['id'] ?>" name="id">
        <?php endif; ?>
        <button type="submit">save</button>
    </form>
    <br>
    <table border="1">
        <tr>
            <th width="500px">Task</th>
            <th>Action</th>
        </tr>
        <?php foreach ($dataVal as $k => $v) { ?>
            <tr>
                <td>
                    <a style="color:<?= $v['status'] ? "green" : "red" ?>" href="?id=<?= $v['id'] ?>&action=edit-status&status=<?= $v['status'] ?>" tittle="Edit"> <?= $v['title'] ?></a>&nbsp;
                </td>
                <td>
                    <a href="?id=<?= $v['id'] ?>&action=edit" tittle="Edit">E</a>&nbsp;
                    <a href="?id=<?= $v['id'] ?>&action=delete" tittle="Delete">D</a>&nbsp;
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>