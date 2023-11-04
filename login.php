<?php
include 'CI/db_conn_web.php';

try {
    // 입력 데이터 보안 강화 함수
    function validate($data) {
        $data = trim($data); // 입력 데이터의 양끝 공백 제거
        $data = stripslashes($data); // 입력 데이터의 '\'삭제
        $data = htmlspecialchars($data); // 입력 데이터의 HTML 특수 문자를 HTML 엔티티로 변환
        return $data;
    }

    if (isset($_POST['uname']) && isset($_POST['password'])) {
        $id = validate($_POST['uname']);
        $password = validate($_POST['password']);

        if (empty($id)) {
            header("Location: index.php?error=아이디를 작성해 주세요.");
            exit();
        } else if (empty($password)) {
            header("Location: index.php?error=비밀번호를 작성해 주세요.");
            exit();
        } else {
            $sql = "SELECT * FROM users WHERE user_name = :uname AND password = :pass";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':uname', $id);
            $stmt->bindParam(':pass', $password);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                if ($_SESSION['user_name'] === 'admin') {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: home.php");
                    exit();
                }
            } else {
                header("Location: index.php?error=아이디 또는 비밀번호가 올바르지 않습니다.");
                exit();
            }
        }
    } else {
        header("Location: index.php?error");
        exit();
    }
} catch (PDOException $e) {
    header("Location: index.php?error=데이터베이스 오류: " . $e->getMessage());
    exit();
}
?>
