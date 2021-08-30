<?php
session_start();
include 'connection.php';

//$_SESSION['userId];

$u  = new User();
$mess = new Message();

$messages = $mess->get_my_messages($_SESSION['userId']);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Real Deal Real Estate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .inp {
            border:1px solid #c4c4c4;
            outline:0;
            width:90%;
            border-radius:8px;
            transition: all 0.3s ease;
            margin-bottom: 1%;
            margin-left:1%;
        }
        .inp:focus{
            border: 1px solid #42ff75;
            transform: scale(1.02);

        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<h3 style="text-align:center;margin-top:1%;margin-bottom:2%;">
    My messages
</h3>

<div style="display:flex;align-items:center;justify-content:center;width:95%;margin-left:2%;padding: 1%;border: 1px solid black; border-radius 8px;padding-bottom:1%;">
    <div style="width:25%;height:60vh;overflow: auto; border-right: 1px solid black">
        <?php
        foreach($messages  as $m)
        {
            if($m->receiver == $_SESSION['userId'])
                $sender = $u->find_by_id($m->sender);
            else
                $sender = $u->find_by_id($m->receiver);

            echo '
                        <div style="border:1px solid #c4c4c4;margin-right:5px;padding: 2% 1% 2% 1%;cursor:pointer;"
                         onclick="loadMessage(\''.$_SESSION['userId'].'\',\''.$m->sender.'\',\''.$m->receiver.'\',\''.$sender->usersName.'\',\''.$sender->usersId.'\')">';

            if($m->seen == 0 && $m->receiver == $_SESSION['userId'])
            {
                echo '<span style="color:red">';
                echo $sender->usersName;
                echo '</span>';
            }else
                echo $sender->usersName;

            echo '<br>';
            echo substr($m->messageText,0,10) . '...';

            echo '</div><br>';
        }
        ?>
    </div>
    <div style="width:75%;padding-left:2%;position:relative;height:100%;" >
        <h3 style="text-align:center" id="conv_partner">
        </h3>
        <div id="messageContent" style="margin-bottom:1%;height:50vh;overflow:auto;top:0;">

        </div>

        <div style="position:absolute;display:flex;align-items:center:justify-content:space-between;width:100%;margin-bottom:1%;transform:translateY(-50%);">
            <div style="width:90%">
                <input type="text" style="width:100%;height:100%;" id="newMessage">
                <input type="hidden" value="" id="rec">
            </div>
            <div style="display:flex;align-items:flex-end;justify-content:flex-end;">
                <button class="btn btn-success" style="margin-right:50%;" onclick="sendMessage()">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>

    const LoggedInuser = '<?php echo $_SESSION['userId']?>';

    console.log(LoggedInuser);

    function sendMessage()
    {
        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data: {
                key: 'sendMessage',
                sender: LoggedInuser,
                receiver: document.getElementById('rec').value,
                text: document.getElementById('newMessage').value
            },success:function(resp){
                let div = document.createElement('div');
                div.style.display = 'flex';
                div.style.width = "100%";
                div.style.marginBottom = "1%";

                div.style.alignItems = 'flex-end';
                div.style.justifyContent = 'flex-end';
                let textDiv = document.createElement('div');
                textDiv.style.borderRadius = '8px';
                textDiv.style.background = "darkBlue";
                textDiv.style.padding = "8px";
                textDiv.style.color = "white";
                textDiv.innerHTML = document.getElementById('newMessage').value;

                div.appendChild(textDiv);

                document.getElementById('messageContent').appendChild(div);
                document.getElementById('newMessage').value = "";
            }

        })
    }

    function loadMessage(user,sender,receiver,partner,recId)
    {
        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data: {
                key: 'loadMessage',
                sen: sender,
                rec: receiver,
                myUser: user

            }, success: function(resp){
                console.log(resp);

                document.getElementById('messageContent').innerHTML = '';
                document.getElementById('conv_partner').innerHTML = partner;

                /*if(sender == user)
                {
                    document.getElementById('rec').value = sender;
                }else
                {
                    document.getElementById('rec').value = receiver;
                }*/
                document.getElementById('rec').value = recId;

                resp = JSON.parse(resp);

                console.log(resp);

                let text = "",str = "";

                resp.forEach( r => {

                    let div = document.createElement('div');
                    div.style.display = 'flex';
                    div.style.width = "100%";
                    //div.classList.add("fullWidth");
                    div.style.marginBottom = "1%";
                    if(r['sender'] == user)
                    {
                        div.style.alignItems = 'flex-end';
                        div.style.justifyContent = 'flex-end';
                        //div.classList.add("AlignFlexEnd");
                    }else
                    {
                        div.style.alignItems = 'flex-start';
                        div.style.justifyContent = 'flex-start';
                        //div.classList.add("AlignFlexStart");
                    }

                    let textDiv = document.createElement('div');
                    textDiv.style.borderRadius = '8px';
                    textDiv.style.background = "darkBlue";
                    textDiv.style.padding = "8px";
                    textDiv.style.color = "white";
                    textDiv.innerHTML = r['messageText'];

                    div.appendChild(textDiv);

                    document.getElementById('messageContent').appendChild(div);

                });

            }
        })
    }
</script>    