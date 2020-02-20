<?php
    include "init.php";


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

<div class="container">
    
    <form class="col-6 signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" 
          method="post">

          <h2 class="text-center mb-5 mt-2"> Create your account </h2>

        <div class="row ">

            <div class="col">
                <label>First Name</label>
                <input type="text" class="form-control" name="first" placeholder="First name" autocomplete="off" required="required">
            </div>
            <div class="col">
                <label>Last Name</label>
                <input type="text" name="last" class="form-control" placeholder="Last name" autocomplete="off" required="required">
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-12">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="enter your email">
            </div>
            <div class="form-group col-12">
                <label>Password</label>
                <input type="password" name="pass" class="form-control" placeholder="enter your password"autocomplete="off" required="required">
            </div>
            <div class="form-group col-12">
                <label>Re-Password</label>
                <input type="password" name="re_pass" class="form-control" placeholder="enter your password"autocomplete="off" required="required">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12">
                <label>Country</label>
                <input type="text" name="country" class="form-control">
            </div>

            <div class="type col-12">

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="mail" class="custom-control-input" value="mail">
                    <label class="custom-control-label" for="customRadioInline1">male</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="female" class="custom-control-input" value="female">
                    <label class="custom-control-label" for="customRadioInline2">female</label>
                </div>
            </div>
            <button type="submit" class="register col-4- btn btn-dark btn-block mt-4"> Register </button>
            
    </form>
 </div>

<?php include $temp . "footer.php"; ?>
