<?php
    session_start();
    include 'connection.php';

    if(isset($_SESSION['userId']))
    {
        $u = new User();
        $userData = $u->find_by_id($_SESSION['userId']);
    }else
        header("Location: index.php");

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
<body>
<?php
include 'header.php';

//print_r($userData);
?>

<h3 class="PageTitleH3">
    Hello <?php echo $userData->usersName; ?>
</h3>

<div class="myPage__spaceBetweenWraper">
    <div class="fullWidth">

        <p><b>Personal Data</b></p>

        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sendMessage">
            Request new password
        </button><br><br>
              
        <div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="sendMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Request new password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form onsubmit="return false" id="newPassword" method="post" class="fillWidth">
                            <label for="oldPW">
                                Old Password
                            </label>
                            <input type="password" class="inp" name="password" id="oldPW">


                            <input type="hidden" name="key" value="resetPW">
                            <input type="hidden" name="usersId" value="<?php echo $_SESSION['userId']; ?>">
                            <button type="button" class="btn btn-success btn-sm" onclick="createNewPW()">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" onsubmit="return false" class="fullWidth" id="mainForm"> 

            <input type="hidden" name="key"         value="userSelfEdit">
            <input type="hidden" name="usersId"     value="<?php echo $userData->usersId;?>">
            <input type="hidden" name="usersRights" value="<?php echo $userData->usersRights; ?>">
            <input type="hidden" name="usersActive" value="1">

            <label for="name">
                Full Name
            </label><br>
            <input type="text" id="name" value="<?php echo $userData->usersName?>" name="usersName" class="inp"><br><br>

            <label for="mail">
                E-Mail
            </label><br>
            <input type="email" id="mail" value="<?php echo $userData->usersEMailAdress?>" name="usersEMailAdress" class="inp"><br><br>

            <label for="tel">
                Telephone number
            </label><br>
            <input type="text" id="tel" value="<?php echo $userData->usersTelNum?>" name="usersTelNum" class="inp"><br><br>

            <label for="bday">
                Birth Day
            </label><br>
            <input type="date" id="bday" value="<?php echo date('Y-m-d', strtotime($userData->usersBirthDay)); ?>" name="usersBirthDay" class="inp"><br><br>

            <div class="AlignFlexEnd fullWidth">
                <button type="button" class="btn btn-sm btn-success" id="save" onclick="updateUser()">
                    Save
                </button>
            </div>
        </form>
    </div>
  <!--  <div class="myPage__contentWrapper">
        <p><b>Website Data</b></p>


         ADD MORE CONTENT
            LIST ACTIVE LISTINGS 
            ACTIVE LISTINGS #
            INACTIVE LISTINGS #
            PENDING LISTINGS  # BE CREATIVE
    </div>-->
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>

    function createNewPW()
    {
        let fd = new FormData(document.getElementById('newPassword'));

        if(confirm('Are you sure you want to reset your password? Password reset link will be sent to you in email!'))
        {
            $.ajax({
                type: 'post',
                url: 'process/process.php',
                data: fd,
                cache: false,
                contentType: false,
                processData: false
            }).done((resp) => {
                alert(resp);
            })
        }

    }

    function updateUser()
    {
        const fm = new FormData(document.getElementById("mainForm"));
        console.log(fm);

        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data: fm,
            cache: false,
            contentType: false,
            processData: false
        }).done((resp) => {
            console.log(resp);
        }).fail((f) => {
            alert("failed:" +f);
        })
    }
</script>    