<?php
require 'emp.class.php';
require 'connection_db.php';
echo "<pre>";
var_dump($_GET);
echo "</pre>";

if(isset($_GET['action'])&&$_GET['action']=='edit' &&isset($_GET['id'])){
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    if($id>0){
        $select='select * from employees where id=:id';
        $selestmt=$connection_db->prepare($select);
        if($selestmt->execute(array('id'=>$id))){
       $user= $selestmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'employees',array('name','age','address','tax','salary'));
       $user=array_shift($user);
      
        }


        
    }
}

/* delete user*/
if(isset($_GET['action'])&&$_GET['action']=='delete' &&isset($_GET['id'])){
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
  $delsql='delete from employees where id=:id';
  $delstmt=$connection_db->prepare($delsql);
  if($delstmt->execute(array(':id'=>$id))){
    $message="Employee deleted successfuly";
  };
}

/* inser user */

if(isset($_POST['submit'])){
   $emp_name=filter_input(INPUT_POST,'emp-name',FILTER_SANITIZE_STRING);
   $age=filter_input(INPUT_POST,'age',FILTER_SANITIZE_NUMBER_INT);
   $address=filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING);
   $salary=filter_input(INPUT_POST,'salary',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $tax=filter_input(INPUT_POST,'tax',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $employee=new employees($emp_name,$age,$tax,$salary);
   $employee->address=$address;
   if(isset($_GET['action'])&&$_GET['action']=='edit' &&isset($_GET['id'])){
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $inserstmt="update employees set name=:name,age=:age,address=:address,salary=:salary,tax=:tax where id=$id";
   }else{
    $inserstmt="insert into employees(name,age,address,tax,salary) values(:name,:age,:address,:tax,:salary)";

   }
  
   $stmt=$connection_db->prepare($inserstmt);
//    $stmt->bindParam(':name',$emp_name);
//    $stmt->bindParam(':age',$age);
//    $stmt->bindParam(':address',$address);
//    $stmt->bindParam(':tax',$tax);
//    $stmt->bindParam(':salary',$salary);
$exe=$stmt->execute(array(':name'=>$emp_name,':age'=>$age,':address'=>$address,':tax'=>$tax,':salary'=>$salary));
  if($exe){
    $message="Employee $employee->name  saved successfuly";
  }
  else {$message="Error inserting Employee $employee->name ";$error=true;};
  header('location:http://localhost/pdo');
  exit();
};
$_POST=array();

$selectstmt=$connection_db->query('select * from employees');
$Alldata=$selectstmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'employees',array('name','age','address','tax','salary'));
$Alldata=(is_array($Alldata)&&!empty($Alldata))?$Alldata:false;
// echo '<pre>';
// var_dump($Alldata);
// echo '</pre>';





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>database_connection</title>
    <!-- Include bootstrap-->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <!--Include css-->
    <link rel="stylesheet" href="style.css">
    <!-- Include fontawesome-->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</head>
<body class="bg-secondary">
    <div class="d-flex justify-content-around">
    <div class="w-50 p-5" >
    <form method="post">
    <fieldset>
        <legend>Employee_Information</legend>
    
  <?php if(isset($message)){?> 
    <div class=" done <?php isset($error)?'notdone':''; ?>"><?php echo $message?></div>
    <?php }?>
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name="emp-name" required value="<?= isset($user)? $user->name : '';?>">
   
  </div>
  <div class="form-group">
    <label for="exampleInputage">Age</label>
    <input type="number" min="22" max="60" class="form-control" id="exampleInputage" aria-describedby="emailHelp" placeholder="Enter Age" name="age" required  value="<?= isset($user)? $user->age : '';?>">
   
  </div>
  <div class="form-group">
    <label for="exampleInputaddress">Address</label>
    <input type="text" class="form-control" id="exampleInputaddress" aria-describedby="emailHelp" placeholder="Enter Address" name="address"  value="<?= isset($user)? $user->address : '';?>">
   
  </div>
  <div class="form-group">
    <label for="exampleInputsalary">Salalry</label>
    <input type="number" min="1500" max="9000" step="0.01" class="form-control" id="exampleInputsalary" aria-describedby="emailHelp" placeholder="Enter Salary" name="salary" required  value="<?= isset($user)? $user->salary : '';?>">
   
  </div>
  <div class="form-group">
    <label for="exampleInputtax">tax (%)</label>
    <input type="number" min="1" max="5" step="0.01" class="form-control" id="exampleInputtax" aria-describedby="emailHelp" placeholder="Enter Name" name="tax" required  value="<?= isset($user)? $user->tax : '';?>">
   
  </div>

  <button type="submit" class="btn btn-primary mt-3" name="submit" value="senddata">Submit</button>
</form>
</fieldset>
</div>
    

    <div class="w-50 p-4 mt-4">
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Age</th>
      <th scope="col">Address</th>
      <th scope="col">Salary</th>
      <th scope="col">Tax (%)</th>
      <th scope="col">Control</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $cnt=0;
    if($Alldata){
    foreach($Alldata as $employee){
        ++$cnt;
    echo <<<"heredoc"
    <tr>
      <th scope="row">$cnt</th>
      <td>$employee->name</td>
      <td>$employee->age</td>
      <td>$employee->address</td>
      <td>$employee->salary L.E</td>
      <td>$employee->tax</td>
      <td><a href="/pdo/?action=edit&id=$employee->id"><i class="fas fa fa-edit me-3"></a></i><a href="/pdo/?action=delete&id=$employee->id"  onclick="confirmdel()"><i class="fas fa fa-times"></a></i></td>
    </tr>
    heredoc;

    }
}else echo "<td colspan='6'>Sorry no Employee to list </td>"
    ?>
    
  </tbody>
</table>

</div>
</div>
<script src="script.js"></script>
</body>
</html>











