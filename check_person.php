<?php
  include 'top_menu.php'; // 상단 메뉴바 가져오기 ../

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    
  }else {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /*사용자 사진 스타일*/
        .image-container {
            text-align: center;
            margin-top: 20px;
        }

        .image-container img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            margin: 10px;
        }
    </style>
</head>
<body>
    <form id="myForm" method="POST">
        <?php
            // FTP 접속 데이터 가져오기
            include 'CI/ftp_conn.php';

            // FTP 속 폴더에서 파일 목록 얻기
            $remoteFiles = ftp_nlist($ftpConnection, $remoteFolder2);

            // 파일 정보 배열 초기화 (안정성 높이기 위해서 추가)
            $fileInfoArray = array();
            
            // 파일 정보 배열에 저장
            foreach ($remoteFiles as $file) {
                $fileInfo = array();
                $fileInfo['name'] = basename($file);
                $fileInfo['path'] = 'http://' . $ftpServer . '/' . $remoteFolder2 . '/' . $fileInfo['name'];
                $fileInfoArray[] = $fileInfo;
            }

            // 파일 정보 배열에서 'face8.jpg'만 출력되도록 설정
            foreach ($fileInfoArray as $fileInfo) {
                if ($fileInfo['name'] == 'face8.jpg') {
                    $imagePath = $fileInfo['path'];
                    echo "<div class='image-container'>";
                    echo "<img src='$imagePath' alt='$fileInfo[name]'>";
                    echo "</div>";
                }
            }
            // FTP 접속 종료
            ftp_close($ftpConnection);
        ?>
        <a href="home.php" class="ca">Home</a>
    </form>
</body>
</html>
