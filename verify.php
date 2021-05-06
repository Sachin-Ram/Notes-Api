<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/api/lib/Signup.class.php';

$token = $_GET['token'];
try{
    if(Signup::verifyAccount($token)){
        ?>
        <h1 style="color: green">Verified</h1>
        <?php
    } else {
        ?>
        <h1 style="color: red">Cannot Verify</h1>
        <?php
    }
}
catch(Exception $e){
    ?>
    <h1 style="color: orange">Already Verified</h1>
    <?php
}

