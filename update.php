<?php
include("connect.php");
$id = $_GET['id'];
$name   = "";
$email  = "";
$mobile = "";
if (isset($_POST['submit'])) {
    $name         = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // to filter string
    $email        = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // to filter email
    $mobile       = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT); // to filter number
    $check_mobile = $conn->prepare("select * from crud where mobile = '" . $mobile . "' and id not in ('".$id."')"); // to check duplicate
    $check_mobile->execute();
    if ($check_mobile->rowCount() > 0) {
        header("Location: index.php?message=Duplicate entry");
    } else {
        $insert_query = $conn->prepare("update crud set name = :name, email=:email, mobile=:mobile where id = :id"); //to insert data
        try {
            $conn->beginTransaction();
            $insert_query->bindParam(":name", $name);
            $insert_query->bindParam(":email", $email);
            $insert_query->bindParam(":mobile", $mobile);
			$insert_query->bindParam(":id", $id);
            $count = $insert_query->execute();
			
            if ($count> 0) {
                header("Location: edit.php?id=$id&message=Record has been updated successfully"); //success data insertion
            } else {
                header("Location: index.php?id=$id&message=Failed to update"); //failure data insertion
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