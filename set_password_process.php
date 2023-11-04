<!--set_password_process-->
<?php
    include 'dbControl.php';
    // 비밀번호를 입력하고 저장하기를 눌렀을때 
    if (isset($_POST['set_password'])) {
        $new_password = $_POST['new_password'];
        dbUpdateData($connection, 'userManagement', 'pw', $new_password);
        header("Location: home.php");
        exit();
    }

?>