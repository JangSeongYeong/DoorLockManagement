<?php
  include 'dbControl.php';

  if (isset($_POST['lockOn'])) {
    // 비밀번호 비교 재활성화
    // userManagement(table) -> user_name(아이디)의 errorcode 컬럼 값을 0으로 수정
    dbUpdateData($connection, $table='userManagement', $field='errorcode', $value='0');
    echo "<script>alert('비밀번호 입력이 활성화되었습니다.');</script>";
    echo "<script>window.history.back();</script>"; // 이전 페이지로 돌아가기
  }
?>