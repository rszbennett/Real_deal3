<div style="display:flex;align-items:center;justify-content:space-around;width:100%;height:10%;background-color:#3b3935">
    <div style="width:33%;">
        <a href="index.php">
            <img src="media/logo.png" alt="logo">
        </a>
    </div>
    <?php
        if(isset($_SESSION['userId']))
        {
            $headerU = new Message();

            $newMessageCnt = $headerU->find_unseen_for_user($_SESSION['userId']);

            echo '<div style="width:33%;display:flex;align-items:flex-end;justify-content:flex-end;margin-right:2%">
                <div style="display:flex;align-items:center;justify-content:space-between;width:60%;">
                <a href="myPage.php" class="btn btn-sm btn-primary">
                    My Profile
                </a>
                <a href="myMessages.php" class="btn btn-sm btn-primary" style="position:relative">
                    My Messages';

            if($newMessageCnt > 0)
                echo '<i class="fas fa-envelope" style="position:absolute;top:0;right:0;color:red"></i>';

            echo '</a>
                <form method="post">
                    <button type="submit" name="logout" class="btn btn-primary btn-sm">
                        Log Out
                    </button>
                </form>
                </div>
                </div>
            ';
        }else
        {
            echo '<div style="width:33%;display:flex;align-items:flex-end;justify-content:flex-end;margin-right:2%">
                <a href="login.php" class="btn btn-primary btn-sm">
                    Log In/Register
                </a>
            </div>';
        }
    ?>
</div>
<script src="https://kit.fontawesome.com/5c94c1ef5f.js"
crossorigin="anonymous"></script>
<?php
    if(isset($_POST['logout']))
    {
        session_destroy();
        ?>
        <script>
            window.location.href = window.location.href;
        </script>
        <?php
    }
?>