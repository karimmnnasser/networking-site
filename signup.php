<?php

    session_start();
    include "init.php";
    $No_navbar = " ";

    // if isset session enter to index
    if (isset($_session['email']) AND isset($session['pass'])) {
      header('location:index.php');
      exit();	
    }

    // Check If User Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

      if (isset($_POST['login']) == 'submit') {

        $email	 	  = $_POST['email'];
        $password	  = $_POST['pass'];
        $hashedpass = sha1($password);
      
      $stmt = $con->prepare("SELECT 
                    email , password 
                   FROM 
                       users 
                   WHERE 
                       email = ? 
                   AND 
                    password = ? ");
                       
      $stmt->execute(array($email,$hashedpass));
      $count= $stmt->rowCount();
  
      if($count > 0 ) {
  
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $hashedpass;
  
        header('location: index.php');
        exit();
      }
    }



      $formErrors = array(); // array for errors

      if(isset($_POST['check']) == 'submit') {
       
       $email= $_SESSION['email'];
       $code_user = $_POST['codeForm'];

       $stmt = $con->prepare('SELECT * FROM Temporary_code WHERE Email = ?
       ORDER BY id DESC LIMIT 1');

       $stmt->execute(array($email));

       $fetch = $stmt->fetchAll();

       foreach($fetch AS $f){
         $code = $f['Temporary_code'];
        }

       if($code_user == $code ){

         $stmt = $con->prepare('INSERT INTO users (first_name, last_name, email, password, re_password, gender)
        VALUES(:fr, :la, :em, :pa, :re_pass, :gen)');
        $stmt->execute(array(
        ':fr' =>$_SESSION['first'],
        ':la' =>$_SESSION['last'] ,
        ':em' => $_SESSION['email'],
        ':pa' => $_SESSION['hashedpass'],
        ':re_pass' => $_SESSION['hashedre_pass'],
        ':gen' => $_SESSION['gender']
        ));

        header('location:index.php');
        exit();

       } else {

        $formErrors["code_error"] = 'this code is not true !';

    }
  }
}

      if(isset($_POST['signup']) == 'submit' ) { 

            $first          = $_POST['first'];
            $last           = $_POST['last'];
            $email          = $_POST['email'];
            $pass           = $_POST['pass'];
            $hashedpass     = sha1($pass);
            $re_pass        = $_POST['re_pass'];
            $hashedre_pass  = sha1($re_pass);
            $gender         = $_POST['gender'];
              
            $_SESSION['first'] = $first;
            $_SESSION['last'] = $last;
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $pass;
            $_SESSION['hashedpass'] = $hashedpass;
            $_SESSION['re_pass'] = $re_pass;
            $_SESSION['hashedre_pass'] = $hashedre_pass;
            $_SESSION['gender'] = $gender;
  
            

            if (isset($first)) {
            $filter = filter_var($first,FILTER_SANITIZE_STRING);
            if(strlen($filter) < 4 ){
                $formErrors["firstErrors"] = 'First Name Must Be Larger Than 4 Characters';
            }}

            if(isset($last)){
            $filter = filter_var($last,FILTER_SANITIZE_STRING);
            if(strlen($filter) < 4 ){
                $formErrors["lastErrors"] = 'Your Last Name is smaller Than 4 Characters !';
            }}


            if(isset($email)){
            $filteremail = filter_var($email,FILTER_SANITIZE_EMAIL);
            if($filteremail != true ){
                $formErrors["emailErrors"] = 'This Email Is Not Valid';
            }}

            if (isset($pass) && isset($re_pass)){
            if(empty($pass)){
                $formErrors["passErrors"] = 'Sorry Password Is Not Match';
            }

            if (sha1($pass) !== sha1($re_pass)){
                $formErrors["match_passErrors"] = 'this password is not matched !';
            }}

            if(isset($gender)){
                if(empty($gender)){
                    $formErrors["genderErrors"] = 'Input Gender Is Required';
                }
            }

            // if is not any problem.
            if (empty($formErrors)){
              echo "form error is empty";
              // Check If The email Exist In Database.
              $stmt = $con->prepare('SELECT email FROM users WHERE email =? ');
              $stmt->execute(array($email));
              $count = $stmt->rowCount();
              
              // email is exist in database
              if($count == 1){
                echo "all is true";
              $formErrors["email_her_Errors"] = 'Sorry This E-mail Is Exists';
              
              // email is not exist in database.
              } elseif ($count == 0) {
          
                $codeForm = rand(10000,100000);

                //the subject
                $sub = "Activate Email";
                //the message
                $msg = "This is  The Activation Number :  " . $codeForm;
                //recipient email here
                $rec =  $email ;
                //send email
                mail($rec,$sub,$msg);


                $stmt = $con->prepare('INSERT INTO Temporary_code (Email, Temporary_code)
                                       VALUES(:Email, :Temporary_code)');
                      $stmt->execute(array(
                      ':Email' =>$email,
                      ':Temporary_code' =>$codeForm 
                      ));
               
                    
                  $email= $_POST['email'];
                  // $code_user = $_POST['codeForm'];

                  $stmt = $con->prepare('SELECT * FROM Temporary_code 
                                          WHERE
                                               Email= ? 
                                          AND 
                                               Temporary_code = ?');
                  $stmt->execute(array($email, $codeForm ));
                  $rowCount = $stmt->rowCount();

                  if($rowCount > 0){

                    echo 'hello your code is true';
                  }else{
                    echo " can not sent any code";
                  }
          } 
        } 
     } 
     /* ----- end form signup  ------- */

   
?>






  <!-- Start Check E-mail -->
         <form class="col-4 check-email" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="form-group">
                <label> Enter number </label>
                <input type="text" name="codeForm" class="form-control">
                <?php
                  if(isset($formErrors["code_error"])){
                      echo '<div class="alert_formErrors">'.$formErrors["code_error"].'</div>';
                    }
                ?>           
            </div>
            <button type="submit" name="check" class="btn btn-check btn-block btn-lg">Submit</button>
        </form>


     <div class="col-lg-4 col-md-6 col-sm-7 toggle-sign-login">

        <div class="row toggle-edit">
          <button id="signup" class=" col-6 btn btn-lg edit-inner-toggle active">Sign Up</button>
          <button id="login"  class=" col-6 btn btn-lg edit-inner-toggle">Login</button>
        </div>

        <!-- Start form login  -->
        <form id="login-content" class='col-12 login' action = '<?php echo $_SERVER['PHP_SELF'] ?>' method="POST">
         <!-- start email  -->
           <div class="form-group col-12">
                <input type="email" name="email" class="form-control" placeholder="E-Mail" required="required">
            </div>
            <!-- end email  -->


          <!-- start password -->
            <div class="form-group col-12">
                <input type="password" name="pass" class="form-control" placeholder="Password"autocomplete="off" required="required">

                <!-- <label class="label labels">Password <span><a href="">Forget?</a></span></label> -->
            </div>
          <!-- end password -->

          <!-- Start Remember me -->
          <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="bob">
          <label class="form-check-label" for="bob">Remember me</label>
          </div>
          <!-- End Remember me -->

          <input class='btn form-control btn-dark btn-lg btn-login' type="submit" name="login" value='Sign In'>

      </form>
   <!-- End form login  -->





    <!-- sign up page -->
    <form class="form-signup " action="<?php echo $_SERVER['PHP_SELF'] ?>"  method="POST">

        <div class="form-row"> 
              <!-- start first name  -->
              <div class="col-6 form-group">
                  <input type="text" class="form-control" name="first" placeholder="First Name" autocomplete="off" required="required">
          
              </div>
              <!-- end first name  -->

              <!-- start last name  -->
              <div class="col-6 form-group">
                  <input type="text" name="last" class="form-control" placeholder="Last Name" autocomplete="off" required="required">
              
              </div>
              <!-- end last name  -->

              <div class="col-12 ">
                  <?php
                  if(isset($formErrors["lastErrors"])){
                      echo '<div class="alert_formErrors">'.$formErrors["lastErrors"].'</div>';
                      
                      if(isset($formErrors["firstErrors"])){
                          echo '<div class="alert_formErrors">'.$formErrors["firstErrors"].'</div>';
                        }
                  }?>
              </div>

              <!-- start email  -->
            <div class="form-group col-12">
                <input type="email" name="email" class="form-control" placeholder="E-Mail">
                <?php
                if(isset($formErrors["emailErrors"])){
                    echo ' <div class="alert_formErrors">' . $formErrors["emailErrors"].'</div>';

                  }elseif (isset($formErrors["email_her_Errors"])){
                    echo ' <div class="alert_formErrors">' . $formErrors["email_her_Errors"] .'</div>';
                  }
                  ?>
            </div>
            <!-- end email  -->



            <!-- start password -->
            <div class="form-group col-12">
                <input type="password" name="pass" class="form-control" placeholder="Password"autocomplete="off" required="required">
            </div>
            <div class="form-group col-12">
                <input type="password" name="re_pass" class="form-control" placeholder="Re - Password"autocomplete="off" required="required">
                <?php
                if(isset($formErrors["passErrors"])){
                    echo ' <div class="alert_formErrors">' . $formErrors["passErrors"] . '</div>';

                  }elseif(isset($formErrors["match_passErrors"])){
                    echo ' <div class="alert_formErrors">' . $formErrors["match_passErrors"] . '</div>';
                  }?>
            </div>
            <!-- end password -->

              <!-- start gender -->
              <div class="col-12">  <!-- mail -->
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="gender" value="male">
                    <label class="custom-control-label" for="defaultGroupExample1">Male</label>
                  </div>
              
                  
                  <div class="custom-control custom-radio custom-control-inline"> <!-- female -->
                    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="gender" value="female">
                    <label class="custom-control-label" for="defaultGroupExample2">Female</label>
                  </div>
                  <?php
                  if(isset($formErrors["genderErrors"])){
                      echo ' <div class="alert_formErrors">' . $formErrors["genderErrors"] . '</div>';
                    }
                    ?>
              </div>
            <!-- end gender -->


              <button type="submit" name="signup" class="btn btn-block btn-sign mt-4 btn-lg"> Create Account </button>
        </div>  <!-- ./ form row -->
      </form>
   </div>

<!-- particles.js container -->
<div id="particles-js"></div>
<div class="background-color">


<h1 class="animation-text"> welcome <span> in social media </span> </h1>

</div>

<!-- particles.js lib - https://github.com/VincentGarreau/particles.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<?php include $temp . "footer.php"; ?>
