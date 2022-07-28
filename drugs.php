<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

?>
<link href="bootstrap.min.css" rel="stylesheet">

<?php 
  include("header.php");
  include("library.php");

  noAccessForStoreKeeper();
  noAccessForAdmin();
  noAccessForDoctor();

  include('nav-bar.php');
?>
<div class="container">
<h2>Update Drug Prescription Information</h2>
<p>Enter information below</p>
<table class="table table-striped">
<?php



  if(isset($_POST['drugs_given'])){
      $i = update_appointment_info($_POST['appointment_no'], 'drugs_given', $_POST['drugs_given'], $_POST['case']);
      if($i==1){
        echo "<script type='text/javascript'>window.location = 'all_appointments.php'</script>";
      }
  }

  if(isset($_GET['appointment_no'])){
    $appointment_no = $_GET['appointment_no'];
    $result = getAllPatientDetail($appointment_no);

	$row = $result->fetch_array();
  
    $link = "<tr><th>";
    $mid = "</th><td>";
    $endingTag = "</td></tr>";
    echo "<tr>";   // appointment_no, full_name, dob, weight, phone_no, address, blood_group, medical_condition

    echo "$link Appointment No $mid". $row['appointment_no'] . "$endingTag";
    echo "$link Full Name $mid" . $row['full_name'] . "$endingTag";
    echo "$link Age (in years) $mid" . $row['dob'] . "$endingTag";

    echo "$link Weight $mid" . $row['weight'] . "$endingTag";

    echo "$link Phone No $mid" . $row['phone_no'] . "$endingTag";

    echo "$link Address $mid" . $row['address'] . "$endingTag";

    echo "$link Medical Condition $mid" . $row['medical_condition'] . "$endingTag";

    echo "<form action='drugs.php' method='post'>";

    echo "$link Drugs Given $mid" . "

          <select required value=1 class ='form-control' name='drugs_given' style='width: 500;'>
                <option value='Panadol' class='option'>Panadol</option>
                <option value='Coartem' class='option'>Coartem</option>
                <option value='Painex' class='option'>Painex</option>
          </select>

    " . "$endingTag";
    
    echo "<input type='number' style='visibility: hidden; width; 1px;' name=\"appointment_no\" value =" . $appointment_no . ">";
    
    echo "$link Case Closed? $mid" . "

          <select required value=1 class ='form-control' name='case' style='width: 500;'>
                <option value='yes' class='option'>Yes</option>
                <option value='no' class='option'>No</option>
          </select>
            <br/>
          <input type='submit' class='btn btn-primary'></form>
    
    " . "$endingTag";

    echo "</tr>";
  
  }
?>
</table>

</div>


<?php include("footer.php"); ?>


