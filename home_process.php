<!--home_process-->
<?php
    include 'dbControl.php';

    // 비밀번호 변경을 눌렀을 때 실행함수(랜덤 비밀번호 배치)
    if (isset($_POST['ranPassword'])) {
        // 4자리 숫자 비밀번호 생성
        $password = strval(rand(1000, 9999));
        // password 테이블 pw 컬럼에 비밀번호 삽입하는 코드
        dbUpdateData($connection, $table='userManagement', $field='pw', $value=$password);
        
        header("Location: home.php?sc_reset_password");
        exit();
    }

?>