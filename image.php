<!DOCTYPE html>
<html>
<head>
    <title>이미지 목록</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="css/styleImage.css">
    
    <script>
        function redirectToPage(data) {
            window.location.href = data + ".php";
        }
    </script>
</head>
<body>
    <form>
    <div class="button-container">
        <a href="home.php" class="ca">Home</a>
        <button type="button" onclick="redirectToPage('imageAll')">사진 모두 보기</button>
    </div>
    <?php
    include 'CI/ftp_conn.php'; //FTP접속

    // 원격 폴더에서 파일 목록 얻기
    $remoteFiles = ftp_nlist($ftpConnection, $remoteFolder);

    // 파일 정보 배열 초기화
    $fileInfoArray = array();

    // 파일 정보 얻기
    foreach ($remoteFiles as $file) {
        $fileInfo = array();
        $fileInfo['name'] = basename($file);
        $fileInfo['path'] = 'http://' . $ftpServer . '/' . $remoteFolder . '/' . $fileInfo['name'];
        $fileInfoArray[] = $fileInfo;
    }

    // 파일명을 기준으로 내림차순 정렬
    usort($fileInfoArray, function ($a, $b) {
        return strcmp($b['name'], $a['name']);
    });

    // 최신 5개 이미지 표시
    $counter = 0;
    foreach ($fileInfoArray as $fileInfo) {
        if ($counter >= 5) {
            break;
        }
        $imagePath = $fileInfo['path'];
        echo "<div class='image-container'>";
        echo "<img src='$imagePath' alt='$fileInfo[name]'>";
        $dateTime = substr($fileInfo['name'], 6, 15); // 파일 이름에서 날짜와 시간 추출
        $date = substr($dateTime, 0, 4) . '년 ' . substr($dateTime, 4, 2) . '월 ' . substr($dateTime, 6, 2) . '일';
        $time = substr($dateTime, 9, 2) . '시 ' . substr($dateTime, 11, 2) . '분 ' . substr($dateTime, 13, 2) . '초';
        echo "<div>$date $time</div>";
        echo "</div>";
        $counter++;
    }
  
    // FTP 접속 종료
    ftp_close($ftpConnection);
    ?>
    </form>
</body>
</html>
