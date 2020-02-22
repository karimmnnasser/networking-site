<?php
    include "init.php";
    $Nonavbar = " ";

// Check If User Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['submit'])){

            $first = $_POST['first'];
            $last = $_POST['last'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $hashedpass = sha1($pass);
            $re_pass = $_POST['re_pass'];
            $hashedre_pass = sha1($re_pass);
            $gender = $_POST['gender'];
            $country = $_POST['country'];


            $formErrors = array();


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
            } }
           
            if(isset($gender) AND empty($gender)){
                $formErrors["genderErrors"] = 'input gender is requred';
            }


            if(empty($country)){

            $formErrors["coutryErrors"] =  'countyt is empty !';

            }


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
}

/*
    if(!empty($formErrors)){

    foreach ($formErrors as $error) {

    echo ' <div class="alert alert-danger">' . $error . '</div>';
  }
}
*/
?>


<div class="container-fluid overlay">
    <form class="col-lg-5 col-sm-10 col-md-8 offset-lg-6 signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" 
          method="post" name="signup">

          <div class="duble row">
          <button class=" col-6 btn btn-outline-dark btn-lg">Sign Up</button>
          <button class=" col-6 btn btn-outline-dark btn-lg">Login</button>
          </div>

        <div class="row first_last">
            <div class="col">
                <input type="text" class="form-control" name="first" placeholder="First Name" autocomplete="off" required="required">
                 
                <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["firstErrors"] . '</div>';
                 }
            }
        ?>

            </div>
            <div class="col">
                <input type="text" name="last" class="form-control" placeholder="Last Name" autocomplete="off" required="required">
                <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["lastErrors"] . '</div>';
                 }
            }
        ?>
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-12">
                
                <input type="email" name="email" class="form-control" placeholder="E-Mail">
                <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["emailErrors"] . '</div>';
                 }
            }
        ?>
            </div>
            <div class="form-group col-12">
                <input type="password" name="pass" class="form-control" placeholder="Password"autocomplete="off" required="required">
            </div>
            <div class="form-group col-12">
                <input type="password" name="re_pass" class="form-control" placeholder="Re - Password"autocomplete="off" required="required">
                <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["passErrors"] . '<br>' . $error["match_passErrors"] . '</div>';
                 }
            }
        ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12">
                <input type="text" name="country" placeholder="Country" class="form-control">
                <?php
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["coutryErrors"] . '</div>';
                 }
            }
        ?>
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
                if(!empty($formErrors)){
                    foreach ($formErrors as $error => $value ) {
                    echo ' <div class="alert_formErrors">' . $error["genderErrors"] . '</div>';
                 }
            }
        ?>
            </div>
            <button type="submit" name="submit" class="register col-4- btn btn-dark btn-block mt-4"> Register </button>
            
    </form>
 </div>



<?php include $temp . "footer.php"; ?>
