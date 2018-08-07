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