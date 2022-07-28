<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<?php 
  include("header.php");
  include("library.php");

  noAccessForReceptionist();
  noAccessForDoctor();
  noAccessForStoreKeeper();
  noAccessIfNotLoggedIn();

?>
<div class="container">
 	<h1 align=center>System Admin Control Panel</h1>
  
  <?php 
    if(isset($_POST['demail'])){
      $i = register($_POST['demail'],$_POST['dpassword'],$_POST['dfullname'],$_POST['dSpecialist'],"doctors");
    }
    if(isset($_POST['skemail'])){
      $i = register($_POST['skemail'],$_POST['skpassword'],$_POST['skfullname'],'non',"storekeepers");
    }
    if(isset($_POST['aemail'])){
      $i = register($_POST['aemail'],$_POST['apassword'],$_POST['afullname'],'non',"receptionists");
    }
    if(isset($_POST['DrDelEmail'])){
      $i = delete("doctors",$_POST['DrDelEmail']);
    }
    if(isset($_POST['ClDelEmail'])){
      $i = delete("receptionists",$_POST['ClDelEmail']);
    }
    
  ?>


<div class="col col-xl-6 col-sm-6" id="register1">
    <form method="post" action="sys_admin.php">
      <h2>Add a Receptionist</h2>
        <div class="form-group">
          <label for="usr">Full Name:</label>
          <input type="text" class="form-control" name="afullname" required>
        </div>
        
        <div class="form-group">
          <label for="usr">Email:</label>
          <input type="email" class="form-control" name="aemail" required>
        </div>
            
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" class="form-control"  name="apassword" required>
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Register">
          <input type="reset" name="" class="btn btn-danger"></button>
        </div>
    </form>
      <hr>
                  <form method="post" action="sys_admin.php">

      <div class="form-group">
                <h2>Delete Receptionists</h2>
            <select class='form-control' required value=1 name="ClDelEmail">
            <?php 
                $result = getListOfEmails('receptionists');

                if(is_bool($result)){
                  echo "No receptionists found in database";
                }else{
                  while($row = $result->fetch_array())
                  {
                    echo "<option value='" . $row['email'] . "'>" . $row['email'] . "</option>";
                  }
                }

            ?>
            </select>
            </div>
            <div class="form-group">

            <input type="submit" class="btn btn-primary" style="padding: 10px;" value="Delete">
            </div>
          </form>
          
    <form method="post" action="sys_admin.php">
      <h2>Add a Store Keeper</h2>
        <div class="form-group">
          <label for="usr">Full Name:</label>
          <input type="text" class="form-control" name="skfullname" required>
        </div>
        
        <div class="form-group">
          <label for="usr">Email:</label>
          <input type="email" class="form-control" name="skemail" required>
        </div>
            
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" class="form-control"  name="skpassword" required>
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Register">
          <input type="reset" name="" class="btn btn-danger"></button>
        </div>
    </form>
      <hr>
                  <form method="post" action="sys_admin.php">

      <div class="form-group">
                <h2>Delete Store Keeper</h2>
            <select class='form-control' required value=1 name="DeleteStoreKeeper">
            <?php 
                $result = getListOfEmails('storekeepers');

                if(is_bool($result)){
                  echo "No store keepers found in database";
                }else{
                  while($row = $result->fetch_array())
                  {
                    echo "<option value='" . $row['email'] . "'>" . $row['email'] . "</option>";
                  }
                }

            ?>
            </select>
            </div>
            <div class="form-group">

            <input type="submit" class="btn btn-primary" style="padding: 10px;" value="Delete">
            </div>
          </form>
</div>

<div class="col col-xl-6 col-sm-6 " id="register1">
    <form method="post" action="sys_admin.php">
      <h2>Add a Doctor</h2>
        <div class="form-group">
          <label for="usr">Full Name:</label>
          <input type="text" class="form-control" name="dfullname" required>
        </div>
        
        <div class="form-group">
          <label for="usr">Email:</label>
          <input type="email" class="form-control" name="demail" required>
        </div>
            
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" class="form-control"  name="dpassword" required>
        </div>

        <div class="form-group">
          <label for="pwd">Speciality:</label>
            <select class='form-control' required value=1 name="dSpecialist">
              <option value="Audiologist" class="option">Audiologist - Ear Expert</option>
              <option value="Allergist" class="option">Allergist - Allergy Expert</option>
              <option value="Anesthesiologist" class="option">Anesthesiologist - Anesthetic Expert</option>
              <option value="Cardiologist" class="option">Cardiologist - Heart Expert</option>
              <option value="Dentist" class="option">Dentist - Oral Care Expert</option>
              <option value="Dermatologist" class="option">Dermatologist - Skin Expert</option>
              <option value="Endocrinologist" class="option">Endocrinologist - Endocrine Expert</option>
            </select>
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Register">
          <input type="reset" name="" class="btn btn-danger"></button>
        </div>
    </form>


        <hr>
                    <form method="post" action="sys_admin.php">

        <div class="form-group">
                <h2>Delete Doctor</h2>
            <select class='form-control' required value=1 name="DrDelEmail">

            <?php 
                $result = getListOfEmails('doctors');

                if(is_bool($result)){
                  echo "No doctors found in database";
                }else{
                  while($row = $result->fetch_array())
                  {
                    echo "<option value='" . $row['email'] . "'>" . $row['email'] . "</option>";
                  }
                  echo '&emsp;';

                }

            ?>
            </select></div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Delete">
            </div>
          </form>
        </div>
    </form>
  </div>
</div>


</div>
<div class="col col-xl-6 col-sm-6 " id="register1">
</div>
<div class="col col-xl-6 col-sm-6 " id="register1">
</div>
<?php include("footer.php"); ?>


