<!DOCTYPE html>
<html>
<head>
    <title>가입하기</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="css/styleIndex.css">
</head>
<body>
    <form action="signup_check.php" method="post">
        <h2>가입하기</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <label>이름</label>
        <?php if (isset($_GET['name'])) { ?>
            <input  type="text" 
                    name="name"  
                    value="<?php echo $_GET['name']; ?>"><br>
        <?php }else{ ?>
            <input  type="text" 
                    name="name" 
                    placeholder=""
                    class='active-border'><br>
        
        <?php }?>
        
        <label>아이디</label>
        <?php if (isset($_GET['uname'])) { ?>
            <input  type="text" 
                    name="uname" 
                    placeholder="4~15글자로 작성" 
                    value="<?php echo $_GET['uname']; ?>"><br>
        <?php }else{ ?>
            <input  type="text" 
                    name="uname" 
                    placeholder="4~15글자로 작성"
                    class='active-border'><br>
        
        <?php }?>
    
        <label>비밀번호</label>
        <input  type="password" 
                name="password" 
                placeholder="영대소문자/숫자/특수문자 포함 6~15글자로 작성"
                autocomplete="off"
                class='active-border'><br>

        <label>비밀번호 재입력</label>
        <input  type="password" 
                name="re_password" 
                placeholder=""
                class='active-border'><br>
    
        <button type="submit">가입하기</button>
        <br><br><a href="index.php" class="ca">이미 계정이 있으신가요?</a>
    </form>
</body>
</html>
