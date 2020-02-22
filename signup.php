<?php
    include "init.php";
    $Nonavbar = " ";

// Check If User Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $first = $_POST['first'];
            $last = $_POST['last'];
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $hashedpass = sha1($pass);
            $pass_re = $_POST['re_pass'];
            $hashedre_pass = sha1($pass_re);
            $country = $_POST['country'];


            $formErrors =array();


            if (isset($first)) {
            $filter = filter_var($first,FILTER_SANITIZE_STRING);

            if(strlen($filter) < 4 ){

                $formErrors[] = 'First Name Must Be Larger Than 4 Characters';
            }
            } else {

            $formErrors[] = 'your First Name is Requred !';
            
            }


            if(isset($last)){
            $filter = filter_var($last,FILTER_SANITIZE_STRING);
            if(strlen($filter) < 4 ){
                $formErrors[] = 'Your Last Name is smaller Than 4 Characters !';
            }

            }else{
            $formErrors[] = 'your First Name is Requred ! !';
            }


            if(isset($email)){
            $filteremail = filter_var($email,FILTER_SANITIZE_EMAIL);

            if($filteremail != true ){
                $formErrors[] = 'This Email Is Not Valid';
            }
            }

            if (isset($pass) && isset($re_pass)){

            if(empty($pass)){
                $formErrors = 'your password is empty';
            }

            if (sha1($pass) !== sha1($re_pass)){

                $formErrors[] = 'this password is not matched !';
            }
            }


            if(empty($country)){

            $formErrors[] =  'countyt is empty !';

            }


            if (empty($formErrors)){

               // Check If The email Exist In Database

               $stmt = $con->prepare('SELECT email FROM users WHERE email =? ');
               $stmt->execute(array($email));
               $count = $stmt->rowCount();

               if($count == 1){

               $formErrors[] = 'sorry this email is her , try another email';

               } else {

               $stmt = $con->prepare('INSERT INTO users (first_name, last_name, email, password,re_password, country)
                                       VALUES(:fr, :la, :em, :pa, :re_pass, :co )');
               $stmt->execute(array(
                   ':fr' =>$first ,
                   ':la' =>$last ,
                   ':em' => $email,
                   ':pa' => $hashedpass,
                   ':re_pass' => $hashedre_pass,
                   ':co' => $country
               ));

               echo ' <div class="alert alert-success"> success </div>';

           } 
        }
    }



    if(!empty($formErrors)){

    foreach ($formErrors as $error) {

    echo ' <div class="alert alert-danger">' . $error . '</div>';
  }
}

?>


<div class="container-fluid">

    <div class="svg">
       <!-- <img src="layoutimgs/social.jpg" alt="this is image">-->
    </div>

    <form class="col-lg-5 col-sm-10 col-md-8 offset-lg-6 signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" 
          method="post">

          <div class="duble row">
          <button class=" col-6 btn btn-outline-dark btn-lg">Sign Up</button>
          <button class=" col-6 btn btn-outline-dark btn-lg">Login</button>
          </div>

        <div class="row first_last">
            <div class="col">
                
                <input type="text" class="form-control" name="first" placeholder="First Name" autocomplete="off" required="required">
            </div>
            <div class="col">
                <input type="text" name="last" class="form-control" placeholder="Last Name" autocomplete="off" required="required">
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-12">
                
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group col-12">
            
                <input type="password" name="pass" class="form-control" placeholder="Password"autocomplete="off" required="required">
            </div>
            <div class="form-group col-12">
                <input type="password" name="re_pass" class="form-control" placeholder="Re - Password"autocomplete="off" required="required">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12">
                <input type="text" name="country" placeholder="Country" class="form-control">
            </div>

            <div class="type col-12">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="groupOfDefaultRadios">
                  <label class="custom-control-label" for="defaultGroupExample1">Male</label>
                </div>

                <!-- Group of default radios - option 2 -->
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="groupOfDefaultRadios">
                  <label class="custom-control-label" for="defaultGroupExample2">Female</label>
                </div>
            </div>
            <button type="submit" class="register col-4- btn btn-dark btn-block mt-4"> Register </button>
            
    </form>
 </div>



<?php include $temp . "footer.php"; ?>
