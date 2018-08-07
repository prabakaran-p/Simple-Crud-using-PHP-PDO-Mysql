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