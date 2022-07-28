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
  noAccessForAdmin();
  noAccessIfNotLoggedIn();

?>
<div class="container">
 	<h1 align=center>Stores Management Panel</h1>
  
  <?php 
    if(isset($_POST['bnumber'])){
      $i = add_drug_to_store($_POST['bnumber'],$_POST['drug_name'],$_POST['dunits']);
    }
    if(isset($_POST['skemail'])){
      $i = register($_POST['skemail'],$_POST['skpassword'],$_POST['skfullname'],'non',"storekeepers");
    }
    if(isset($_POST['aemail'])){
      $i = register($_POST['aemail'],$_POST['apassword'],$_POST['afullname'],'non',"receptionists");
    }
    
  ?>


<div class="col col-xl-6 col-sm-6" id="register1">
    <form method="post" action="storekeeper.php">
      <h2>Add a Drug To Store</h2>
      <div class="form-group">
          <label for="usr">batch ID</label>
          <input type="text" class="form-control" name="bnumber" required>
        </div>
      <div class="form-group">
              <label for="usr">Drug</label>
              <select required value=1 name="drug_name">
                <option value="Coartem987" class="option">Coartem 871</option>
                <option value="Coartem985" class="option">Coartem 985</option>
                <option value="Coartem988" class="option">Coartem 988</option>
                <option value="Painex" class="option">Painex</option>
                <option value="PanadolExtra" class="option">Panadol Extra</option>
         </select>
            </div>
		<div class="form-group">
          <label for="usr">Units</label>
          <input type="number" class="form-control" name="dunits" required>
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Add">
          <input type="reset" name="" class="btn btn-danger"></button>
        </div>
    </form>
          
    <form method="post" action="store_keeper.php">
      <h2>Drugs Sent To Dispensary</h2>
      <div class="form-group">
          <label for="usr">Request ID</label>
          <input type="number" class="form-control" name="drugunits" required>
        </div>
        <div class="form-group">
              <label for="usr">Drug</label>
              <select required value=1 name="drugname">
				<option value="Painex" class="option">Painex</option>
                <option value="Coartem987" class="option">Coartem 987</option>
                <option value="Coartem985" class="option">Coartem 985</option>
                <option value="Coartem988" class="option">Coartem 988</option>
                <option value="PanadolExtra" class="option">Panadol Extra</option>
         </select>
            </div>
		<div class="form-group">
          <label for="usr">Units:</label>
          <input type="number" class="form-control" name="drugunits" required>
        </div>
        
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Add Record">
          <input type="reset" name="" class="btn btn-danger"></button>
        </div>
    </form>
</div>

<div class="col col-xl-6 col-sm-6 " id="register1">
    <form method="post" action="sys_admin.php">
      <h2>Drug Requests From Dispensary</h2>
      
        
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Mark Sent">
        </div>
    </form>
  </div>
</div>
<?php include("footer.php"); ?>


