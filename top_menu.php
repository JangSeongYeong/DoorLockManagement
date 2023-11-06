<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 아이콘폰트를 사용하기 위한 link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/styleTopMenu.css">
</head>
<body>
    <form id = "menuForm" action="top_menu_process.php" method="POST">
        <div class="button-contain" id="buttonContainer" style="display: none;">
            <button type="button" name="home" onclick="redirectToPage('home')" style="display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-house" style="color: #ffffff;"></i>
            </button>
            <button type="button" onclick="redirectToPage('log')">작동 내역</button>
            <button type="button" onclick="redirectToPage('check_person')">사용자 확인</button>
            <button type="submit" onclick="revertMenuIcon()" name="lockOn">잠금 해제</button>
            <button type="button" onclick="redirectToPage('image')">침입자 확인</button>
        </div>
    </form>
    <script src="js/common_function.js"></script>
</body>
</html>
