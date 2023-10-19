<?php
$ftpServer = ''; // FTP 서버 주소
$ftpUsername = ''; // FTP 계정 사용자명
$ftpPassword = ''; // FTP 계정 비밀번호
$remoteFolder = 'Picture'; // 패스워드 틀렸을 때 사진 폴더 이름
$remoteFolder2 = 'facePicture'; // 사용 등록자 폴더 이름

// FTP 서버에 접속
$ftpConnection = ftp_connect($ftpServer);
ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
?>