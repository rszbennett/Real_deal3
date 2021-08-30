<?php 
session_start();
//print_r($_SESSION);
include 'connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .inp {
            border:0;
            outline:0;
            width:100%;
            border-radius:8px;
            transition: all 0.3s ease;
            margin-bottom: 1%
        }
        .inp:focus{
            border: 1px solid #42ff75;
            transform: scale(1.02);

        }
    </style>
</head>
<body style="background: -webkit-gradient(linear, left bottom, right top, from(#1f4037), to(#99f2c8)) fixed;">

<div style="width:50%;height:auto;margin: 5% auto 5% auto; background: #ededed; border-radius:8px; border-1px solid #c4c4c4;padding:2%" id="login">
    <div style="display:flex;align-items:center;justify-content:center">
        <h4>
            Log In
        </h4>
    </div>
    <div style="display:flex;align-items:center;justify-content:center;flex-direction: column;">
       <!-- <form style="width:90%" method="post"> -->
            <input type="text"     class="inp" name="email" id="email" placeholder="E-Mail"   spellcheck="false" autocomplete="false" required>
            <input type="password" class="inp" name="pw"    id="pw"    placeholder="Password" spellcheck="false" autocomplete="false" required>
            
            <div style="display:flex;align-items:center;justify-content:space-between">
                <button type="button" onclick="switch_login()" class="btn btn-sm btn-secondary">
                    Register
                </button>
                <button type="submit" class="btn btn-success btn-sm" name="login" onclick="login()">Login</button>
            </div> 
    
    </div>
</div>

<div style="width:50%;height:auto;margin: 5% auto 5% auto; background: #ededed; border-radius:8px; border-1px solid #c4c4c4;padding:2%;display:none;" id="register">
    <div style="display:flex;align-items:center;justify-content:center">
        <h4>
            Register
        </h4>
    </div>
    <div style="display:flex;align-items:center;justify-content:center">
        <form action="login.php" type="post" style="width:90%;">
            
            <input type="text"  class="inp" name="name"      id="name"     placeholder="Full Name" spellcheck="false" autocomplete="false" required>
            <input type="email" class="inp" name="reg_email" id="reg_mail" placeholder="E-Mail"    spellcheck="false" autocomplete="false" required><br>

            <label for="birthDay">
                Birth Day
            </label>
            <input type="date"  class="inp" name="birthday"  id="birthDay" required>

            <input type="text" class="inp" name="telNum" id="telnum" placeholder="Telephone number" spellcheck="false" autocomplete="false" required>

            <input type="password" class="inp" name="pwd"   id="pwd"   placeholder="Password"               spellcheck="false" autocomplete="false" required>
            <input type="password" class="inp" name="pwd_2" id="pwd_2" placeholder="Password One More Time" spellcheck="false" autocomplete="false" required>

            <div style="display:flex;align-items:center;justify-content:space-between">
                <button type="button" onclick="switch_login()" class="btn btn-sm btn-secondary">
                    Log In
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="save()">Save</button>
            </div> 
        </form>
    </div>
</div>


</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    function save(){
        const name  = document.getElementById('name').value;
        const email = document.getElementById('reg_mail').value;
        const pw1   = document.getElementById('pwd').value;
        const pw2   = document.getElementById('pwd_2').value;
        const birthday = document.getElementById('birthDay').value;
        const telnum = document.getElementById('telnum').value;
        
        if(document.getElementById('pwd').value !== document.getElementById('pwd_2').value)
        {
            alert('Passwords dont match, try again!');
            return 0;
        }

        if(name == '' || email == '')
        {
            alert('Missing fields!');
            return 0;
        }
        
        $.ajax({
            method: 'post',
            url: 'process/process.php',
            data:{
                key: 'loginsave',
                name: name,
                mail: email,
                bday: birthday,
                pw: pw2,
                telnum: telnum
            },success: function(resp) {

                //console.log(resp);
                alert('Please log in after the page reload!');
                window.location.href = window.location.href;
            }
        });
    }

    function login(){
        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data:{
                key: 'login',
                email: document.getElementById('email').value,
                pw: document.getElementById('pw').value
            },success: function(resp){
                //console.error(resp);
                if(resp == 'failed')
                    alert('False, pls try again');
                else
                    window.location.href = "index.php";
            }
        })
    }

    function switch_login(){ 
        //************************** */
        //switch between login and 
        //register form by hiding
        //************************** */

        if(document.getElementById('login').style.display !== 'none')
        {
            document.getElementById('login').style.display    = 'none';
            document.getElementById('register').style.display = 'block';
            
        }else
        {
            document.getElementById('login').style.display    = 'block';
            document.getElementById('register').style.display = 'none';
            
        }
    }
</script>