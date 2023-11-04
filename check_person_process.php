<?php
    include 'CI/db_conn_web.php';
    include 'dbControl.php';

    function getSL($connection, $user_name) {
        $slsql = "SELECT SL FROM userManagement WHERE user_name = :user_name";
        
        $stmt = $connection->prepare($slsql);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
        return $result['SL'];
    }

    if (isset($_POST['securityLevel'])) {
        $_SESSION['sl_alert_shown'] = true;

        $SL = getSL($connection, $_SESSION['user_name']);
        if($SL == '2') {
            dbUpdateData($connection, 'userManagement', 'SL', '1');
            header("Location: check_person.php?sc_change_SL1");
            exit();
        } else if ($SL == '1') {
            dbUpdateData($connection, 'userManagement', 'SL', '2');
            header("Location: check_person.php?sc_change_SL2");
            exit();
        } else {
            header("Location: check_person.php?fa_change_SL");
            exit();
        }       
    }

    if (isset($_POST['YES'])) {
        $_SESSION['sl_alert_shown'] = true;
        
        // 사진촬영을 통한 사용자 변경 활성화
        // userManagement(table) -> user_name(아이디)의 tp 컬럼 값을 1로 수정
        dbUpdateData($connection, $table='userManagement', $field='tp', $value='1');
        header("Location: check_person.php?sc_change_tp");
        exit();
    }

    if (isset($_POST['set_us_password'])) {
        $_SESSION['sl_alert_shown'] = true;

        $new_password = $_POST['new_us_password'];
        dbUpdateData($connection, 'users','password', $new_password);
        header("Location: check_person.php?sc_change_userpw");
        exit();
    }
?>