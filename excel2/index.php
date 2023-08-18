<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Import Excel To MySQL</title>
</head>
<body>
<form class="" action="" method="post" enctype="multipart/form-data">
    <input type="file" name="excel" required value="">
    <button type="submit" name="import">Import</button>
</form>
<hr>
<table border="1">
    <tr>
        <td>#</td>
        <td>date</td>
        <td>ad_title</td>
        <td>status</td>
        <td>ad_id</td>
        <td>group_id</td>
        <td>impressions</td>
        <td>clicks</td>
        <td>spent</td>
        <td>reach</td>
    </tr>
    <?php
    $i = 1;
    $rows = mysqli_query($conn, "SELECT * FROM members");
    foreach ($rows as $row) :
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row["date"]; ?></td>
            <td><?php echo $row["ad_title"]; ?></td>
            <td><?php echo $row["status"]; ?></td>
            <td><?php echo $row["ad_id"]; ?></td>
            <td><?php echo $row["group_id"]; ?></td>
            <td><?php echo $row["impressions"]; ?></td>
            <td><?php echo $row["clicks"]; ?></td>
            <td><?php echo $row["spent"]; ?></td>
            <td><?php echo $row["reach"]; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
if (isset($_POST["import"])) {
    $fileName = $_FILES["excel"]["name"];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

    $targetDirectory = "uploads/" . $newFileName;
    move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

    require 'excelReader/excel_reader2.php';
    require 'excelReader/SpreadsheetReader.php';

    $reader = new SpreadsheetReader($targetDirectory);
    foreach ($reader as $key => $row) {
        $date = mysqli_real_escape_string($conn, $row[0]);
        $ad_title = mysqli_real_escape_string($conn, $row[1]);
        $status = mysqli_real_escape_string($conn, $row[2]);
        $ad_id = mysqli_real_escape_string($conn, $row[3]);
        $group_id = mysqli_real_escape_string($conn, $row[4]);
        $impressions = mysqli_real_escape_string($conn, $row[5]);
        $clicks = mysqli_real_escape_string($conn, $row[6]);
        $spent = mysqli_real_escape_string($conn, $row[7]);
        $reach = mysqli_real_escape_string($conn, $row[8]);

        mysqli_query($conn, "INSERT INTO members (date, ad_title, status, ad_id, group_id, impressions, clicks, spent, reach) 
                             VALUES ('$date', '$ad_title', '$status', '$ad_id', '$group_id', '$impressions', '$clicks', '$spent', '$reach')");
    }

    echo
    "
    <script>
    alert('Successfully Imported');
    document.location.href = '';
    </script>
    ";
}
?>
</body>
</html>
