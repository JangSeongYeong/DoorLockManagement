<?php
  include 'CI/db_conn_web.php';
  include 'top_menu.php';

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    // 보안 레벨 변경 시, 사용자 변경 사진 촬영 전에 경고를 보여주는 코드  
    // sl_alrty_shown을 통해서 새로고침시 다시 경고를 띄우는 것을 방지
    if (isset($_GET['sc_change_SL1']) && $_SESSION['sl_alert_shown']) {
        echo "<script>alert('보안레벨이 1단계(비밀번호)로 활성화되었습니다.');</script>";
        $_SESSION['sl_alert_shown'] = false;
    } else if (isset($_GET['sc_change_SL2']) && $_SESSION['sl_alert_shown']) {
        echo "<script>alert('보안레벨이 2단계(얼굴인식+비밀번호)로 활성화되었습니다.');</script>";
        $_SESSION['sl_alert_shown'] = false;
    } else if (isset($_GET['sc_change_tp']) && $_SESSION['sl_alert_shown']) {
        echo "<script>alert('사용자 변경을 위한 사진 촬영 시작 (사진 반영은 일정 시간이 지난 후 새로고침을 부탁드립니다.)');</script>";
        $_SESSION['sl_alert_shown'] = false;
    } else if (isset($_GET['sc_change_userpw']) && $_SESSION['sl_alert_shown']) {
        echo "<script>alert('비밀번호 변경이 완료되었습니다.');</script>";
        $_SESSION['sl_alert_shown'] = false;
    }
  } else {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/styleCheckPerson.css">
</head>
<body>       
    <form id="myForm" action="check_person_process.php" method="POST">
        <div style="display: flex; align-items: center;">
            <input type="checkbox" id="menuicon">
            <label for="menuicon">
                <span></span>
                <span></span>
                <span></span>
            </label>
            &nbsp &nbsp &nbsp <p style="font-size:20px"><strong>사용자 확인</strong></p>
        </div>

        <!-- 이미지 출력 -->
        <?php
            // FTP 접속 데이터 가져오기
            include 'CI/ftp_conn.php';

            // FTP 속 폴더에서 파일 목록 얻기
            $remoteFiles = ftp_nlist($ftpConnection, $remoteFolder1 . '/' . $_SESSION['user_name'] . '/' . $remoteFolder2);
            
            // 파일 정보 배열 초기화
            $fileInfoArray = array();

            // 파일 정보 배열에 저장
            foreach ($remoteFiles as $file) {
                $fileInfo = array();
                $fileInfo['name'] = basename($file);
                //$remoteFolder1 = 'doorLockManagement', $remoteFolder2 = 'facePicture', $remoteFolder3 = 'Picture'
                $fileInfo['path'] = 'https://' . $ftpServer . '/' . $remoteFolder1 . '/' . $_SESSION['user_name'] . '/' . $remoteFolder2 . '/' . $fileInfo['name'];
                $fileInfoArray[] = $fileInfo; 
            }

            // 파일 정보 배열에서 'face8.jpg'만 출력되도록 설정
            foreach ($fileInfoArray as $fileInfo) {
                if ($fileInfo['name'] == 'face8.jpg') {
                    $imagePath = $fileInfo['path'];
                    break;
                }
            }
            
            // FTP 접속 종료
            ftp_close($ftpConnection);
        ?>
        <div class='image-container'>
            <img id='targetImg' onload="e => {e.handleTargetImgUrl()}" alt='user-image' src='<?php echo $imagePath?>'>
        </div> 

        <button type="button" onclick="userImgSwitch()">사용자 변경</button>
        <button type="submit" name="securityLevel">보안단계 설정</button>
        <button type="button" onclick="removeHidden('user_passwordC')">계정 비밀번호 변경</button>
    </form>
    
    <!-- 계정 비밀번호 변경 폼 -->
    <form id="user_passwordC" action="check_person_process.php" method="POST" class="hidden">
        <label>계정 비밀번호 설정</label>
        <input type="password" id="new_password" name="new_us_password" required pattern="\d{4}" placeholder="네 자리 숫자로 입력해 주세요."
         title="네 자리 숫자로 입력하세요">
        <br>
        <div class="container">
            <button type="button" onclick="addHidden('user_passwordC')">취소</button>
            <button type="submit" name="set_us_password">변경하기</button>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="js/cacheScript.js"></script>
</body>
</html>