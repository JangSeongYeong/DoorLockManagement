<?php
  /*---------------------------------------------------------
  db설명
  <테이블 1>
    users - 회원 로그인 정보
  <컬럼>
    id(인덱스), user_name(아이디), password(비밀번호), name(사용자명)


  <테이블 2>
    actionRecoding - 작동내역 
  <컬럼>
    date(insert 시간), user_name(아이디-식별용), word(작동내역)


  <테이블 3>
    userManagement - 회원 개인정보 관리 
  <컬럼>
    user_name(아이디-식별용), pw(도어락 비밀번호), tp(사진 촬영 승인 변수), errorcode(대조 승인 변수), SL(보안 레벨)
    tp, errorcede -> 0이 기본 값
    tp -> 1이 되면 사용자 사진 촬영 시작
    errorcode -> 1이 되면 현재 비밀번호와 입력 비밀번호를 대조를 불가능하게 함
  ---------------------------------------------------------*/
  
  include 'CI/db_conn_web.php';

  //db Insert
  function dbInsertData($connection, $table, $field, $value) {
    // SQL INSERT 문 작성
    $insertSql = "INSERT INTO $table ($field) VALUES (:value)";
    
    // SQL 문을 데이터베이스에 바인딩 및 실행
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bindParam(':value', $value);
    $insertStmt->execute();
  }

  //db Update
  function dbUpdateData($connection, $table ,$field, $value) {
    // 세션에서 사용자 이름 가져오기
    $user_name = $_SESSION['user_name'];

    // SQL UPDATE 문 작성
    $updateSql = "UPDATE $table SET $field = :value WHERE user_name = :user_name";

    // SQL 문을 데이터베이스에 바인딩 및 실행
    $updateStmt = $connection->prepare($updateSql);
    $updateStmt->bindParam(':value', $value);
    $updateStmt->bindParam(':user_name', $user_name);
    $updateStmt->execute();
  }

  //db select

?>