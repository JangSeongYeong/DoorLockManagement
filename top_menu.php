<?php
  include 'CI/db_conn_web.php';
  
  //db에 데이터 삽입하는 코드
  function dbInsertData($connection, $table, $field, $value) {
    $insertSql = "INSERT INTO $table ($field) VALUES (:value)";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bindParam(':value', $value);
    $insertStmt->execute();
  }

  //POST 데이터를 받았을 때 동작
  if (isset($_POST['LOCK_ON'])) {
    // error 테이블 errorcode 컬럼에 0 삽입(비밀번호 비교 활성화)
    dbInsertData($connection, 'error', 'errorcode', 0);
    echo "<script>alert('비밀번호 입력이 활성화되었습니다.');</script>";
  } else if (isset($_POST['YES'])) {
    // camera 테이블 tp 컬럼에 1 삽입(사진촬영 활성화)
    echo "<script>alert('사진 촬영을 시작합니다.');</script>";
    dbInsertData($connection, 'camera', 'tp', 1);
  }
?>

<!DOCTYPE html>
<head>
    <style>
        .button-contain {
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .button-contain button {
        max-width: 500px;
        padding: 17px;
        height: 40px;
        margin: 0px;
        border-radius: 0px;
        background: #444;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
        line-height: 0px;
        }
    </style>    
</head>
<body>
    <div class="button-contain">
      <button type="button" onclick="redirectToPage('log')">작동 내역</button>
      <button type="button" onclick="adminSwitch()">사용자 변경</button>
      <button type="button" onclick="redirectToPage('check_person')">사용자 확인</button>
      <button type="button" onclick="submitForm('LOCK_ON')">잠금 해제</button>
      <button type="button" onclick="redirectToPage('image')">침입자 확인</button>
    </div>

    <script>
        // 새로고침으로 양식 재제출을 방지하는 코드
        if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
        }
        // submit하게 만드는 함수
        function submitForm(action) {
          // 폼 지정 (폼 이름이 다르면 제출이 안됨, 변수화 추천)
          const form = document.getElementById('myForm');
          const input = document.createElement('input');

          input.setAttribute('type', 'hidden');
          input.setAttribute('name', action); // 매개변수값이 submit됩니다.
          form.appendChild(input);
          form.submit();
        }
        // 사용자 변경하기 전에 재확인하는 함수 
        function adminSwitch() {
          confirm("인원을 수정하시겠습니까?") ? submitForm('YES') : alert('취소합니다.');
        }
        // 지정 페이지로 이동하는 함수
        function redirectToPage(data) {
          window.location.href = data + ".php";
        }
    </script>
</body>
</html>
