<?php
include("connect.php");
$name   = "";
$email  = "";
$mobile = "";
if (isset($_POST['submit'])) {
    $name         = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // to filter string
    $email        = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // to filter email
    $mobile       = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT); // to filter number
    $check_mobile = $conn->prepare("select * from crud where mobile = '" . $mobile . "'"); // to check duplicate
    $check_mobile->execute();
    if ($check_mobile->rowCount() > 0) {
        header("Location: index.php?message=Duplicate entry");
    } else {
        $insert_query = $conn->prepare("insert into crud (name, email, mobile) values (:name,:email,:mobile)"); //to insert data
        try {
            $conn->beginTransaction();
            $insert_query->bindParam(":name", $name);
            $insert_query->bindParam(":email", $email);
            $insert_query->bindParam(":mobile", $mobile);
            $insert_query->execute();
            if ($conn->lastInsertId() > 0) {
                header("Location: index.php?message=Record has been inserted successfully"); //success data insertion
            } else {
                header("Location: index.php?message=Failed to insert"); //failure data insertion
            }
            $conn->commit();
        }
        catch (PDOExecption $e) {
            $dbh->rollback();
            print "Error!: " . $conn->getMessage() . "</br>"; //exception
        }
    }
}
?>