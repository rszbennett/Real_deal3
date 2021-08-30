<?php
    session_start();
    include 'connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Real Deal Real Estate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body style="background: -webkit-gradient(linear, left bottom, right top, from(#1f4037), to(#99f2c8)) fixed;">

<div style="width:50%;height:auto;margin: 5% auto 5% auto; background: #ededed; border-radius:8px; border-1px solid #c4c4c4;padding:2%" id="login">
    <div style="display:flex;align-items:center;justify-content:center">
        <h4>
            Reset Password
        </h4>
    </div>
    <div style="display:flex;align-items:center;justify-content:center;flex-direction: column;">
        <form style="width:100%" method="post" onsubmit="return false" id="yes1"> 

            <input type="hidden" name="key" value="resetPasswordPage">
            <input type="hidden" name="userId" value="<?php echo $_GET['i']; ?>">
            <input type="password" class="inp" name="pw1" id="pw2"   placeholder="Password"        spellcheck="false" autocomplete="false" required="required">
            <input type="password" class="inp" name="pw2" id="pw"    placeholder="Repeat Password" spellcheck="false" autocomplete="false" required="required">
            
            <div style="display:flex;align-items:center;justify-content:space-between">
                <button type="submit" class="btn btn-success btn-sm" name="login" onclick="save()">Login</button>
            </div> 
        </form>
    </div>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    function save(){

        let fd = new FormData(document.getElementById('yes1'));

        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data: fd,
            cache: false,
            contentType: false,
            processData: false
        }).done((r) => {
            if(r !== 'done')
            {
                alert(r);
                return 0;

            }else
            {
               alert("Password has been reset. Please log in.") ;
               window.location.href = "login.php";
            }
            
        })
        
    }
</script>    