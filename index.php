<?php
require 'emp.class.php';
require 'connection_db.php';

if(isset($_POST['submit'])){
   $emp_name=filter_input(INPUT_POST,'emp-name',FILTER_SANITIZE_STRING);
   $age=filter_input(INPUT_POST,'age',FILTER_SANITIZE_NUMBER_INT);
   $address=filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING);
   $salary=filter_input(INPUT_POST,'salary',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $tax=filter_input(INPUT_POST,'tax',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $employee=new employees();
   $employee->name=$emp_name;
   $employee->address=$address;
   $inserstmt="insert into employees values(Null,'$emp_name','$age','$address','$tax','$salary')";
  if($connection_db->exec($inserstmt)){
    $message="Employee $employee->name  inserted successfuly";
  }
  else {$message="Error inserting Employee $employee->name ";$error=true;};
};

$selectstmt=$connection_db->query('select * from employees');
$Alldata=$selectstmt->fetchAll(PDO::FETCH_CLASS,'employees');
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
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name="emp-name" required>
   
  </div>
  <div class="form-group">
    <label for="exampleInputage">Age</label>
    <input type="number" min="22" max="60" class="form-control" id="exampleInputage" aria-describedby="emailHelp" placeholder="Enter Age" name="age" required>
   
  </div>
  <div class="form-group">
    <label for="exampleInputaddress">Address</label>
    <input type="text" class="form-control" id="exampleInputaddress" aria-describedby="emailHelp" placeholder="Enter Address" name="address">
   
  </div>
  <div class="form-group">
    <label for="exampleInputsalary">Salalry</label>
    <input type="number" min="1500" max="9000" step="0.01" class="form-control" id="exampleInputsalary" aria-describedby="emailHelp" placeholder="Enter Salary" name="salary" required>
   
  </div>
  <div class="form-group">
    <label for="exampleInputtax">tax (%)</label>
    <input type="number" min="1" max="5" step="0.01" class="form-control" id="exampleInputtax" aria-describedby="emailHelp" placeholder="Enter Name" name="tax" required>
   
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
      <td>$employee->salary</td>
      <td>$employee->tax</td>
    </tr>
    heredoc;

    }
}else echo "<td colspan='6'>Sorry no Employee inserted </td>"
    ?>
    
  </tbody>
</table>
</div>
</div>

</body>
</html>











