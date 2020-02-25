<?php
    include "init.php";
    $Nonavbar = " ";

// Check If User Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $first = $_POST['first'];
            $last = $_POST['last'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $hashedpass = sha1($pass);
            $re_pass = $_POST['re_pass'];
            $hashedre_pass = sha1($re_pass);
            $gender = $_POST['gender'];
            $country = $_POST['country'];


            $formErrors = array(); //array for errors

            // vaidaiton input  from login

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
                $formErrors["passErrors"] = 'your password is empty';
            }

            if (sha1($pass) !== sha1($re_pass)){
                $formErrors["match_passErrors"] = 'this password is not matched !';
            }}
           
            if(isset($gender)){
                if(empty($gender)){
                    $formErrors["genderErrors"] = 'input gender is requred';
                }
            } 

            if(isset($country)){
                if(empty($country)){
                    $formErrors["coutryErrors"] =  'countyt is empty !';
            }}


            if (empty($formErrors)){

               // Check If The email Exist In Database
               $stmt = $con->prepare('SELECT email FROM users WHERE email =? ');
               $stmt->execute(array($email));
               $count = $stmt->rowCount();

               if($count == 1){

               $formErrors["email_her_Errors"] = 'sorry this email is her , try another email';

               } else {

               $stmt = $con->prepare('INSERT INTO users (first_name, last_name, email, password, re_password, gender, country)
                                       VALUES(:fr, :la, :em, :pa, :re_pass, :gen, :co )');
               $stmt->execute(array(
                   ':fr' =>$first ,
                   ':la' =>$last ,
                   ':em' => $email,
                   ':pa' => $hashedpass,
                   ':re_pass' => $hashedre_pass,
                   ':gen' => $gender,
                   ':co' => $country
               ));

               echo ' <div class="alert alert-success"> success </div>';

           } 
        }
    }
    
?>

<div class="forms">
  <div class="container">
    <div class="bt">
        <div class="duble row">
          <button class=" col-6 btn btn-outline-dark btn-lg active" data-class="signup">Sign Up</button>
          <button class=" col-6 btn btn-outline-dark btn-lg" data-class="Log">Login</button>
        </div>
    </div>
    <form class="col-lg-5 col-md-8 col-sm-9 col-xs-12 signup" action="<?php echo $_SERVER['PHP_SELF'] ?>"  method="post" name="signup">
        <div class="padd">
          <div class="row first_last">
              <div class="col">
                  <input type="text" class="form-control" name="first" placeholder="First Name" autocomplete="off" required="required">
                   
                  <?php
                  if(isset($formErrors["firstErrors"])){
                      echo '<div class="alert_formErrors">'.$formErrors["firstErrors"].'</div>';
                    }
                    ?>

              </div>
              <div class="col">
                  <input type="text" name="last" class="form-control" placeholder="Last Name" autocomplete="off" required="required">

                  <?php
                  if(isset($formErrors["lastErrors"])){
                      echo '<div class="alert_formErrors">'.$formErrors["lastErrors"].'</div>';
                   }?>

              </div>
          </div>

        <div class="form-row">
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
        </div>

        <div class="form-row">
            <div class="form-group col-12">
                <input type="text" name="country" placeholder="Country" class="form-control">
                <?php
                if(isset($formErrors["coutryErrors"])){
                    echo '<div class="alert_formErrors">' . $formErrors["coutryErrors"] . '</div>';
                  }?>

              </div>
              <div class="type col-12">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="gender" value="male">
                    <label class="custom-control-label" for="defaultGroupExample1">Male</label>
                  </div>

                  <!-- Group of default radios - option 2 -->
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="gender" value="female">
                    <label class="custom-control-label" for="defaultGroupExample2">Female</label>
                  </div>
                  <?php
                  if(isset($formErrors["genderErrors"])){
                      echo ' <div class="alert_formErrors">' . $formErrors["genderErrors"] . '</div>';
                    }
                    ?>
              </div>
              <button type="submit" name="submit" class="register col-4- btn btn-dark btn-block mt-4"> Create Account </button>
            </div>
         </div>   <!-- container-fluid -->
      </form>


   <form class="Log col-lg-5 col-md-8 col-sm-9 col-xs-12">
      <div class="login">
        <div class="outer-login">
          <div class="signin-social">
            <span class="btn btn-primary "><a href="https://www.facebook.com/">Facebook</a></span>
            <span class="btn btn-default"><a href="https://www.google.com/">Google</a></span>
          </div>

        </div>
        <div class="inner-login">
        <form action = 'login.php' method="POST">   <!--Alert !!  Put The Method In ACTION  -->
          <label class="label labels">Email</label>
          <input  class='form-control field' type="text" name="email" autocomplete="off" required="required">
        
          <label class="label labels">Password <span><a href="">Forget?</a></span></label>
          <input class='form-control field'type="password" name="pass" autocomplete="off" required="required">
        
          <input class='form-control btn btn-primary button' type="submit" name="signin"value='Sign In'>
        </form>
        
        <p>Not a member?<a href="signup.php">Signupnow</a></p>

        <!-- signin.php is the page where we will make a register -->
      </div>
  </div>
  </form>


    <!-- picture aside form login -->
    <div class="content">
        <div class="imgs">
            <img src="layout/imgs/testing.svg">
        </div>
        <div class="text">
        </div>
    </div>        
  </div>
</div>



<?php include $temp . "footer.php"; ?>
