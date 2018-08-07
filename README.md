# Simple-Crud-using-PHP-PDO-Mysql
 PHP PDO CRUD Using MySQL
We are going to discuss PHP PDO CRUD Using MySQL Tutorial. At the end of this article, we will be able to make a CRUD module in PDO .

    PDO = PHP Data Objects. This is a PHP extension that defines a consistent and lightweight interface for accessing databases.
    CRUD = Create/Read/Update/Delete. It means, any basic application that has ability to for creating, deleting, updating and reading the records from specific database, we will call it CRUD.

Contents for PHP PDO CRUD

    Creating a database having a table
    Establishing the database connection
    Creating and Reading records
    Edit records
    Delete a record

1.Creating a database having a table

We will create a database with name crud_app in MySQL with the following table. This is a dummy data but you can use your data after the tutorial for your personal use.

CREATE TABLE `crud` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




2.Establishing the database connection

connect.php

follow this code for php pdo mysql connection
 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud_app";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>

3.Creating and displaying Records

Now, we will create a page index.php for adding the new records. Here is the the code

index.php

<?php 
include("connect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Crud App</title>
</head>

<body>
<h2>Create</h2>
<form action="create.php" method="post" enctype="application/x-www-form-urlencoded">
<label>Name</label>
<input type="text" name="name" required="required" /><br /><br />
<label>Mobile</label>
<input type="text" name="mobile" required="required" /><br /><br />

<label>Email</label>
<input type="email" name="email" required="required" /><br /><br />

<input type="submit" name="submit" required="required" value="submit" />
</form>
<table border="1" width="300px">
<tr>
<th>S.no</th>
<th>Name</th>
<th>Email</th>
<th>Mobile</th>
<th>Action</th>
</tr>
<?php 
$get_datas = $conn->prepare("select * from crud");
$get_datas->execute();
if($get_datas->rowCount()>0){
$i=1;
while($res=$get_datas->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $res['name']; ?></td>
<td><?php echo $res['mobile']; ?></td>
<td><?php echo $res['email']; ?></td>
<td><a href="edit.php?id=<?php echo $res['id'];?>">Edit</a><br /><a href="delete.php?id=<?php echo $res['id'];?>">Delete</a></td>
</tr>
<?php } }else{
echo "<tr><td colspan='5'>Records not found</td></tr>";
} ?>
</table>
<script type="text/javascript">
<?php if($_GET['message']){ ?>
alert('<?php echo $_GET['message'];?>');
<?php } ?>
</script>
</body>
</html>

create.php

contains mysql insert query.
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
4.Edit Records:

contains selected record based on id.

edit.php
<?php 
include("connect.php");
$id = $_GET['id'];
$get_data = $conn->prepare("select * from crud where id = :id");
$get_data->bindParam(":id",$id);
$get_data->execute();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Crud App</title>
</head>

<body>
<h2>Edit</h2>
<?php if($get_data->rowCount()>0){ 
$result = $get_data->fetch(PDO::FETCH_ASSOC);
?>
<form action="update.php?id=<?php echo $id; ?>" method="post" enctype="application/x-www-form-urlencoded">
<label>Name</label>
<input type="text" name="name" required="required" value="<?php  if(isset($result['name'])){ echo $result['name']; } ?>" /><br /><br />
<label>Mobile</label>
<input type="text" name="mobile" required="required" value="<?php  if(isset($result['mobile'])){ echo $result['mobile']; } ?>" /><br /><br />

<label>Email</label>
<input type="email" name="email" required="required" value="<?php  if(isset($result['email'])){ echo $result['email']; } ?>" /><br /><br />

<input type="submit" name="submit" required="required" value="submit" />
</form>
<?php } else { 
echo "Invalid Request";
} ?>
<script type="text/javascript">
<?php if($_GET['message']){ ?>
alert('<?php echo $_GET['message'];?>');
<?php } ?>
</script>
</body>
</html>

update.php
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
5.Delete a record:

delete.php contains delete query with db validations.
<?php
include("connect.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $checkid = $conn->prepare("select * from crud where id = '" . $id . "'"); // to check id
    $checkid->execute();
    if ($checkid->rowCount() > 0) {
		$insert_query = $conn->prepare("delete from crud where id = :id"); //to insert data
        try {
            $conn->beginTransaction();
			$insert_query->bindParam(":id", $id);
            $count = $insert_query->execute();
			
            if ($count> 0) {
                header("Location: index.php?message=Record has been Deleted successfully"); //success data insertion
            } else {
                header("Location: index.php?message=Failed to Delete"); //failure data insertion
            }
            $conn->commit();
        }
        catch (PDOExecption $e) {
            $dbh->rollback();
            print "Error!: " . $conn->getMessage() . "</br>"; //exception
        }
        
    } else {
        header("Location: index.php?message=Invalid Request");
    }
}
?>
