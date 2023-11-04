<?php
$ftpServer = ''; // FTP 서버 주소
$ftpUsername = ''; // FTP 계정 사용자명
$ftpPassword = ''; // FTP 계정 비밀번호
$remoteFolder1 = 'doorLockManagement';
$remoteFolder2 = 'facePicture';
$remoteFolder3 = 'Picture';

// FTP 서버에 접속
$ftpConnection = ftp_connect($ftpServer);
ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
?>