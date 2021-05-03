<?php

$options = [
    'cost' => 12,
];
$p = password_hash("rasmuslerdorf", PASSWORD_BCRYPT, $options);
echo $p;

//sleep(5);

$hash = "$2y$10$pTt2yMWDZ1RSiav0WmTuGeT.JNZUWwNb6vzmIV3lRh5wZfLuSut0u";
var_dump(password_verify("rasmuslerdorf", $p));