<?php
    if (!isset($_SESSION)) {
        session_start();
    }
?>

<?php
    $servername='localhost';
    $username='root';
    $password='devpass';
    $connection = new mysqli($servername, $username, $password, 'ihospitali');

    $error_flag = 0;
    $result;
    if ($connection->connect_error) {
        die('connection failed: '.$connection->connect_error);
    }

    function secure($unsafe_data)
    {
        return htmlentities($unsafe_data);
    }

    function login($email_id_unsafe, $password_unsafe, $table = 'storekeepers')
    {
        global $connection;

        $email_id = secure($email_id_unsafe);
        $password = secure($password_unsafe);

        $sql = "SELECT COUNT(*) FROM $table WHERE email = '$email_id' AND password = '$password';";

        $result = $connection->query($sql);

        $num_rows = (int) $result->fetch_array()['0'];

        if ($num_rows > 1) {
            //send email to sysadmin that site has been hacked
              return 0;
        } elseif ($num_rows == 0) {
            echo status('no-match');

            return 0;
        } else {
            $_SESSION['username'] = $email_id;

            if ($table == 'admin') {
                $_SESSION['user-type'] = 'admin';
            }

            if ($table == 'storekeepers' || $table == 'doctors' || $table == 'receptionists') {
                $sql = "SELECT fullname FROM $table WHERE email = '$email_id' AND password = '$password';";

                $result = $connection->query($sql);

                $fullname = $result->fetch_array()['fullname'];
                $_SESSION['fullname'] = $fullname;
                if ($table == 'storekeepers') {
                    $_SESSION['user-type'] = 'storekeeper';
                } elseif ($table == 'receptionists') {
                    $_SESSION['user-type'] = 'receptionist';
                } else {
                    $_SESSION['user-type'] = 'doctor';

                    $sqldoc = "SELECT speciality FROM $table WHERE email = '$email_id' AND password = '$password';";
                    $resultdoc = $connection->query($sqldoc);
                    $speciality = $resultdoc->fetch_array()['speciality'];
                    $_SESSION['speciality'] = $speciality;
                }
            }

            return 1;
        }
    }

    function register($email_id_unsafe, $password_unsafe, $full_name_unsafe, $speciality_unsafe = 'doctor', $table = 'storekeepers')
    {
        global $connection,$error_flag;

        $email = secure($email_id_unsafe);
        $password = secure($password_unsafe);
        $speciality = secure($speciality_unsafe);
        $fullname = ucfirst(secure($full_name_unsafe));

        $sql;

        switch ($table) {
            case 'storekeepers':
                $sql = "INSERT INTO $table VALUES ('$email', '$password', '$fullname');";
                break;
            case 'doctors':
                $sql = "INSERT INTO $table VALUES ('$email', '$password', '$fullname','$speciality');";
                break;
            case 'receptionists':
                $sql = "INSERT INTO $table VALUES ('$email', '$password', '$fullname');";
                break;
            default:
                // code...
                break;
        }

        if ($connection->query($sql) === true) {
            echo status('record-success');
            if ($table == 'storekeepers' && $error_flag == 0) {
                return login($email, $password);
            }
        } else {
            echo status('record-fail');
        }
    }

    function status($type, $data = 0)
    {
        $success = "<div class='alert alert-success'> <strong>Done!</strong>";
        $fail = "<div class='alert alert-warning'><strong>Sorry!</strong>";
        $end = '</div>';

        switch ($type) {
            case 'record-success':
                return "$success New record created successfully! $end";
                break;
            case 'record-fail':
                return "$fail New record creation failed. $end";
                break;
            case 'record-dup':
                return "$fail Duplicate record exists. $end";
                break;
            case 'no-match':
                return "$fail Record did not match. $end";
                break;
            case 'con-failed':
                return "$fail connection Failed! $end";
                break;
            case 'appointment-success':
                return "$success Appointment booked successfully! Appointment no is $data $end";
                break;
            case 'appointment-fail':
                return "$fail Failed to book appointment! $end";
                break;
            case 'update-success':
                return "$success New record updated successfully! $end";
                break;
            case 'update-fail':
                return "$fail Failed to update data! $end";
                break;
            default:
                // code...
                break;
        }
    }

  function enter_patient_info($full_name_unsafe, $age_unsafe, $weight_unsafe, $phone_no_unsafe, $address_unsafe)
  {
      global $connection, $error_flag,$result;

      $full_name = ucfirst(secure($full_name_unsafe));
      $age = secure($age_unsafe);
      $weight = secure($weight_unsafe);
      $phone_no = secure($phone_no_unsafe);
      $address = secure($address_unsafe);

      $sql = "INSERT INTO `patient_info` VALUES (NULL, '$full_name', $age,$weight, '$phone_no','$address');";

      if ($connection->query($sql) === true) {
          echo status('record-success');

          return $connection->insert_id;
      } else {
          echo status('record-fail');

          return 0;
      }
  }

    function appointment_booking($patient_id_unsafe, $specialist_unsafe, $medical_condition_unsafe)
    {
        global $connection;
        $patient_id = secure($patient_id_unsafe);
        $specialist = secure($specialist_unsafe);
        $medical_condition = secure($medical_condition_unsafe);

        $sql = "INSERT INTO appointments VALUES (NULL, $patient_id, '$specialist', '$medical_condition', NULL, NULL, 'no')";

        if ($connection->query($sql) === true) {
            echo status('appointment-success', $connection->insert_id);
        } else {
            echo status('appointment-fail');
            echo 'Error: '.$sql.'<br>'.$connection->error;
        }
    }

    function update_appointment_info($appointment_no_unsafe, $column_name_unsafe, $data_unsafe, $case_unsafe='no')
    {
        global $connection;

        $sql;

        $appointment_no = (int) secure($appointment_no_unsafe);
        $column_name = secure($column_name_unsafe);
        $data = secure($data_unsafe);
        $case = secure($case_unsafe);

        if ($column_name == 'drugs_given') {
            $data = (int) $data;
            $sql = "UPDATE `appointments` SET `drugs_given` = '$data', `case_closed` = '$case' WHERE `appointment_no` = $appointment_no";
        } else {
            $sql = "UPDATE appointments SET $column_name = '$data' WHERE appointment_no = $appointment_no;";
        }
        echo $sql;
        if ($connection->query($sql) === true) {
            echo status('update-success');

            return 1;
        } else {
            echo status('update-fail');
            echo 'Error: '.$sql.'<br>'.$connection->error;

            return 0;
        }
    }

    function getPatientsFor($doctor)
    {
        global $connection;

        return $connection->query("SELECT appointment_no, full_name, medical_condition, doctors_prescription FROM patient_info, appointments where speciality='$doctor' AND patient_info.patient_id = appointments.patient_id");
    }

    function getAllAppointments()
    {
        global $connection;

        return $connection->query("SELECT appointment_no, full_name,speciality, case_closed, drugs_given FROM patient_info, appointments where patient_info.patient_id = appointments.patient_id");
    }

    function getAllPatientDetail($appointment_no)
    {
        global $connection;

        return $connection->query("SELECT appointment_no, full_name, dob, weight, phone_no, address, medical_condition FROM patient_info, appointments where appointment_no=$appointment_no AND patient_info.patient_id = appointments.patient_id;");
    }

    // function get_table($purpose, $data)
    // {
    //     global $connection;

    //     $sql;

    //     switch ($purpose) {
    //         case 'patient_information':
    //             $sql = 'SELECT * FROM patient_info AND (SELECT )';
    //             break;
    //         case 'doctor-home':
    //             $sql = '';

    //             $result = $connection->query($sql);

    //             echo "<table border='1'>
	// 			<tr>
	// 			<th>appointment_no</th>
	// 			<th>patient_name</th>
	// 			<th>age</th>
	// 			<th>appointment_time</th>
	// 			<th>medical_condition</th>
	// 			<th>option</th>
	// 			</tr>";

    //             while ($row = $result->fetch_array()) {
    //                 echo '<tr>';
    //                 echo '<td>'.$row['appointment_no'].'</td>';
    //                 echo '<td>'.$row['full_name'].'</td>';
    //                 echo '<td>'.$row['age'].'</td>';
    //                 echo '<td>'.$row['appointment_time'].'</td>';
    //                 echo '<td>'.$row['medical_condition'].'</td>';
    //                 echo "<td> <button class='btn btn-primary'> Open Case</button> <button class='btn btn-primary'> Close Case</button> </td>";
    //                 echo '</tr>';
    //             }
    //             echo '</table>';
    //             break;
    //         case 'all':
    //             $sql = 'SELECT * FROM patient_info AND (SELECT )';
    //             break;
    //         case 'patient_information':
    //             $sql = 'SELECT * FROM patient_info AND (SELECT )';
    //             break;
    //         default:
    //             // code...
    //             break;
    //     }
    // }

    function appointment_status($appointment_no_unsafe)
    {
        global $connection;

        $appointment_no = secure($appointment_no_unsafe);
        $i = 0;

        $result = $connection->query("SELECT doctors_prescription FROM appointments WHERE appointment_no=$appointment_no;");
        if ($result === false) {
            return 0;
        } else {
            ++$i;
        }

        $result = $connection->query("SELECT drugs_given FROM appointments WHERE appointment_no=$appointment_no;");
        if ($result->num_rows == 1) {
            ++$i;
        }

        return $i;
    }

function add_drug_to_store($bnumber_unsafe,$drug_name_unsafe, $dunits_unsafe)
  {
      global $connection, $error_flag,$result;

      $bnumber = secure($bnumber_unsafe);
      $drug_name = secure($drug_name_unsafe);
      $dunits = secure($dunits_unsafe);

      $sql = "INSERT INTO `drugs_in_store` VALUES ('$bnumber', '$drug_name', $dunits);";

      if ($connection->query($sql) === true) {
          echo status('record-success');

          return $connection->insert_id;
      } else {
          echo status('record-fail');

          return 0;
      }
  }

    function delete($table, $id_unsafe)
    {
        global $connection;

        $id = secure($id_unsafe);

        return $connection->query("DELETE FROM $table WHERE email='$id';");
    }

    function getListOfEmails($table)
    {
        global $connection;

        return $connection->query("SELECT email FROM $table;");
    }

    function noAccessForStoreKeeper()
    {
        if (isset($_SESSION['user-type'])) {
            if ($_SESSION['user-type'] == 'storekeeper') {
                echo '<script type="text/javascript">window.location = "storekeeper.php"</script>';
            }
        }
    }
    function noAccessForDoctor()
    {
        if (isset($_SESSION['user-type'])) {
            if ($_SESSION['user-type'] == 'doctor') {
                echo '<script type="text/javascript">window.location = "patient_info.php"</script>';
            }
        }
    }
    function noAccessForReceptionist()
    {
        if (isset($_SESSION['user-type'])) {
            if ($_SESSION['user-type'] == 'receptionist') {
                echo '<script type="text/javascript">window.location = "all_appointments.php"</script>';
            }
        }
    }

    function noAccessForAdmin()
    {
        if (isset($_SESSION['user-type'])) {
            if ($_SESSION['user-type'] == 'admin') {
                echo '<script type="text/javascript">window.location = "sys_admin.php"</script>';
            }
        }
    }

    function noAccessIfLoggedIn()
    {
        if (isset($_SESSION['user-type'])) {
            noAccessForStoreKeeper();
            noAccessForAdmin();
            noAccessForReceptionist();
            noAccessForDoctor();
        }
    }

    function noAccessIfNotLoggedIn()
    {
        if (!isset($_SESSION['user-type'])) {
            echo '<script type="text/javascript">window.location = "index.php"</script>';
        }
    }

?>
