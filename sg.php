<pre>
<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/User.class.php');

try{
    $user = new User('sibidharan@icloud.com');
    echo $user->getUsername();
} catch(Exception $e){
    echo $e->getMessage();
}


?>
</pre>