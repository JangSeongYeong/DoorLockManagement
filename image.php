<?php
  include 'CI/db_conn_web.php';

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    
  }else {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
    <title>이미지 목록</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/styleImage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <form>
        <div class='h2-container'>
            <h2>침입자 확인</h2>
        </div>
        <button id="homeBtn" type="button" onclick="redirectToPage('home')">
                <i class="fas fa-house"></i>
        </button>
        
        <?php
            include 'CI/ftp_conn.php'; //FTP접속

            // 원격 폴더에서 파일 목록 얻기
            $remoteFiles = ftp_nlist($ftpConnection, $remoteFolder1 . '/' . $_SESSION['user_name'] . '/' . $remoteFolder3 );

            // 파일 정보 배열 초기화
            $fileInfoArray = array();

            // 파일 정보 얻기
            foreach ($remoteFiles as $file) {
                $fileInfo = array();
                $fileInfo['name'] = basename($file);
                $fileInfo['path'] = 'https://' . $ftpServer . '/' . $remoteFolder1 . '/' . $_SESSION['user_name'] . '/' . $remoteFolder3 . '/' . $fileInfo['name'];
                $fileInfoArray[] = $fileInfo;
            }

            // 파일명을 기준으로 내림차순 정렬
            usort($fileInfoArray, function ($a, $b) {
                return strcmp($b['name'], $a['name']);
            });

            $counter = 0;
            foreach ($fileInfoArray as $fileInfo) {
                $imagePath = $fileInfo['path'];
                $dateTime = substr($fileInfo['name'], 6, 15); // 파일 이름에서 날짜와 시간 추출
                $date = substr($dateTime, 0, 4) . '년 ' . substr($dateTime, 4, 2) . '월 ' . substr($dateTime, 6, 2) . '일';
                $time = substr($dateTime, 9, 2) . '시 ' . substr($dateTime, 11, 2) . '분 ' . substr($dateTime, 13, 2) . '초';
                
                $imageId = 'img' . $counter; // 이미지 id 생성

                echo "<div id='$imageId' class='image-container";
                 // 4개 이상의 이미지를 숨김
                if ($counter >= 4) {
                    echo " hidden";
                }
                echo "'>";
                echo "<img src='$imagePath' alt='$date $time'>";
                echo "<p>$date $time</p>";
                echo "</div>";
                $counter++;
            }
        
            // FTP 접속 종료
            ftp_close($ftpConnection);
        ?>
        <div id="moreView">
            <br><br><br>
            <button type="button" id="moreViewBtn" onclick="imgHiddenOff()">사진 더 보기</button>
        </div>
    </form>
    <script src="js/common_function.js"></script>
    <script>
        // 더 보기를 누름으로써 hidden되어있는 사진들 보이게
        function imgHiddenOff(){
            let count = 4;
            for(count; (count<=<?php echo $counter ?>-3); count++){
                removeHidden('img' + count);
            }
            addHidden('moreView');
        }
    </script>
</body>
</html>
