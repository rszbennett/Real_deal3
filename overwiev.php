<?php
session_start();
//if(isset($_SESSION['username']))
    //echo 'Logged In!';

$id        = $_GET['i'];

$file      = file_get_contents('data/screenings.json');
$screens   = json_decode($file);
$file      = '';
$logged_in = '';
$curr      = '';

if(isset($_SESSION['username']))
{
    $file  = file_get_contents('data/users.json');
    $users = json_decode($file);
    $file  = '';
    
    foreach($users as $u)
    {
        if($u->email == $_SESSION['username'])
            $logged_in = $u;
    }
}

foreach($screens as $s)
{
    if($s->id == $id)
    {
        $curr = $s;
        break;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Mozi pont hu</title>
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
            margin-bottom: 2%;
            border: 1px solid #c4c4c4;
        }
        .inp:focus{
            border: 1px solid #42ff75;
            transform: scale(1.02);

        }
    </style>
</head>
<body style="background: -webkit-gradient(linear, left bottom, right top, from(#11998e), to(#38ef7d)) fixed;">

    <?php include 'header.php'; ?>

    <div style="width:80%;margin:3% auto 2% auto; border-radius: 12px; border: 1px solid #c4c4c4;background-color:white;box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
        <div style="display:flex;justify-content:center;align-items:center;margin-top:1%">
            <h3>
                <i>
                    <?php echo $curr->title; ?>
                </i>
            </h3>
        </div>

        <div style="display:flex;justify-content:center;align-items:flex-start;">
            <p style="width:90%;">
                <b>Short Description</b>
                <br>
                <i>
                    <?php echo $curr->desc; ?>
                </i>
            </p>
        </div>

        <div style="display:flex;justify-content:center;align-items:center">
            <table class="table table-hover" style="width:90%;margin: 1% auto 2% auto;">
                <thead>
                    <th>
                        Time
                    </th>
                    <th>
                        Language
                    </th>
                    <th>
                        Room
                    </th>
                    <th>
                        Reserved
                    </th>
                    <th>
                        Price
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo date('Y.m.d H:i', strtotime($curr->time)); ?>
                        </td>
                        <td>
                            <?php echo $curr->language; ?>
                        </td>
                        <td>
                            <?php echo $curr->room; ?>
                        </td>
                        <td>
                            <?php echo $curr->reserved . '/' . $curr->places; ?>
                        </td>
                        <td>
                            <?php echo $curr->price; ?> &euro;
                        </td>
                </tbody>
            </table>
        </div>
        <div style="display:flex;align-items:flex-end;justify-content:flex-end;width:95%;margin-bottom:2%">
            <?php 
                if($curr->reserved < $curr->places && strtotime("now") < strtotime($curr->time . '-1 hours'))
                    echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#buyTicket">
                        Buy Ticket
                    </button>';
                else
                    echo '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#buyTicket" style="cursor:not-allowed" disabled>
                        Buy Ticket
                    </button>';
            ?>
            <div class="modal fade" id="buyTicket" tabindex="-1" role="dialog" aria-labelledby="buyTicketTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="buyTicketTitle">Buy Ticket</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>
                    <div class="modal-body">
                        <form action="verify.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="text"  class="inp" id="name" name="name" placeholder="Full Name" spellcheck="false" autocomplete="false" required="required" <?php if($logged_in !== '') echo 'value="'.$logged_in->name.'"'; ?>>
                        <input type="email" class="inp" id="mail" name="mail" placeholder="E-Mail"    spellcheck="false" autocomplete="false" required="required" <?php if($logged_in !== '') echo 'value="'.$logged_in->email.'"'; ?>>
                        <label for="amount">
                            How many tickets?
                        </label>
                        <select name="amount" id="amount" >
                            <?php
                                for($i = 1; $i <= $curr->places - $curr->reserved; $i++)
                                {
                                    echo '<option value="'.$i.'">';
                                        echo $i;
                                    echo '</option>';
                                }
                            ?>
                        </select><br>
                        <input type="checkbox" required="required" id="accept_this">
                        <label for="accept_this">
                            FOGADD EL LECCCCIVES
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"                               integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="                     crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>




</script>