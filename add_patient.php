<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
?>
<link href="css/bootstrap.min.css" rel="stylesheet">
 <?php
  include("header.php");
  include("library.php");
  noAccessForAdmin();
  noAccessForStoreKeeper();
  noAccessIfNotLoggedIn();
  if($_SESSION["user-type"] != 'storekeeper'){
    include("nav-bar.php");
  }
?>
<div class="container">
  <h2>Hello, <?php echo $_SESSION["fullname"];?>!</h2>
            <h3>Enter Patient Information</h3>
            <?php
                  if(isset($_POST['apfullname'])){
                    $i = enter_patient_info($_POST['apfullname'],$_POST['apAge'],$_POST['apweight'],$_POST['apphone_no'],$_POST['apaddress']);
                    appointment_booking($i, $_POST['apSpecialist'], $_POST['apCondition']);
                    unset($_POST['apfullname']); //unset all post variables
                    if (isset($_POST['apfullname'])){
                      echo '<script type="text/javascript">location.reload();</script>';
                    }

                  }
            ?>
            <form action="add_patient.php" method="POST">

            <div class="form-group" >
              <label for="usr">Full Name:</label>
              <input type="text" class="form-control" id="usr" name="apfullname" required>
            </div>

            <div class="form-group">
              <label for="pwd">Age: (in years)</label>
              <input type="number" class="form-control" id="pwd" name="apAge" min="1" max="200" required>
            </div>
            <div class="form-group">
              <label for="pwd">Weight (in kgs):</label>
              <input type="tel" class="form-control" id="pwd" name="apweight" min="1" max="300" required>
            </div>
            <div class="form-group">
              <label for="pwd">Phone No:</label>
              <input type="tel" class="form-control" id="pwd" name="apphone_no" required>
            </div>
            <div class="form-group">
              <label for="pwd">Address:</label>
              <textarea class="form-control" id="pwd" name="apaddress" required></textarea>
            </div>

            <div class="form-group">
              <label for="pwd">Doctor Needed:</label>
              <select required value=1 name="apSpecialist">
                <option value="Audiologist" class="option">Audiologist</option>
                <option value="Allergist" class="option">Allergist</option>
                <option value="Anesthesiologist" class="option">Anesthesiologist</option>
                <option value="Cardiologist" class="option">Cardiologist</option>
                <option value="Dentist" class="option">Dentist</option>
                <option value="Dermatologist" class="option">Dermatologist</option>
                <option value="Endocrinologist" class="option">Endocrinologist</option>
              </select>
            </div>

            <div class="form-group">
              <label for="pwd">Medical Condition,Sings and Symptoms</label>
              <textarea class="form-control" id="pwd" name="apCondition" required></textarea>
            </div>

            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Save Patient Info">
              <input type="reset" name="" class="btn btn-danger" value="Clear Form">
            </div>
          </form>
</div>
<?php
  include("footer.php");
?>
