<?php

require_once '../include/common.php';
require_once '../include/token.php';
// require_once '../include/protect_json.php';


// isMissingOrEmpty(...) is in common.php
$errors = [ isMissingOrEmpty ('username'), 
            isMissingOrEmpty ('password') ];
$errors = []; //array_filter($errors);


if (!isEmpty($errors)) {
    $result = [
        "status" => "error",
        "messages" => array_values( $errors)
        ];
}
else{
    $username = $_POST['username'];
    $password = $_POST['password'];


# complete authenticate API
# Authentication use $_POST to prevent others from seeing your information.
    $dao = new StudentDAO();

    # check if username and password are right. generate a token and return it in proper json format
    # after you are sure that the $username and $password are correct, you can do 
    if ($username !== "Admin")
    {
        if($is_student = $dao->retrieve($username)){
            if($is_student->authenticate($password)){
                $token = generate_token($username);  
                $result = [
                    "status" => "success",
                    "token" => $token
                ];
            }
            else{
                $errors[] = 'Invalid Password';
            }
        }
        else{
            $errors[] = 'Invalid Username';
        }
    }

    else
    {
        $user = new Student();
        $token = generate_token($username); 
        if ( $user->adminLogin($password) ) {
            $result = [
                "status" => "success",
                "token" => $token
            ];

        } else {
            $errors[] = 'Invalid Password';
        }
    }
    if (count($errors) != 0){
        $result = [
            "status" => "error",
            "messages" => $errors
        ];
    }
    # generate a secret token for the user based on their username
    # return the token to the user via JSON (Not plainly just return it)
	# return error message if something went wrong
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);

?>