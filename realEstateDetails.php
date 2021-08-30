<?php
session_start();
include 'connection.php';

$re = new RealEstate();
$u  = new User();

if(isset($_SESSION['userId']))
{
    $loggedInRights = $u->find_user_rights($_SESSION['userId'])->usersRights;
    $loggedInId = $_SESSION['userId'];
}
else
{
    $loggedInRights = 1;
    $loggedInId = "unknown";
}

$selected = $re->find_by_id($_GET['i']);

$creator = $u->find_by_id($selected->CreatedBy);

//print_r($selected);

$path = "media/realEstatePhotos/".$_GET['i'] . "_";

$images = glob($path."*.*");


?>

<!DOCTYPE html>
<html>
<head>
    <title>Real Deal Real Estate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <style>

    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <h3 style="text-align:center;margin-top:1%;margin-bottom:2%">
         Realestate deatils ID: <?php echo $_GET['i']; ?>
    </h3>
    <?php
        if($loggedInId !== 'unknown')
        {
            echo '<div style="display:flex;justify-content:space-between;width:90%;margin-left:5%;">';
                echo '<div style="display:flex;align-items:flex-start;justify-content:flex-start;width:50%">';

                    echo '<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#sendMessage">
                            Send message to the owner
                        </button>
              
                        <div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="sendMessageTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Send message to the owner</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="text">
                                            Message Text
                                        </label><br>
                                        <textarea id="messageText" rows="4" cols="60"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="send()">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                echo '</div>';

                if($loggedInRights == 0 || $loggedInId == $selected->CreatedBy)
                {
                    echo '<div style="display:flex;align-items:flex-end;justify-content:flex-end;width:50%;margin-bottom:1%;">
                        <div style="display:flex;align-items:center;justify-content:space-between;width:10vw">
                        <button type="button" class="btn btn-sm btn-primary" onclick="switchContent(this)">Edit Content</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteListing()">
                            Delete
                        </button>
                        </div>
                    </div>';
                }

            echo '</div>';
        }  
    ?>

    <div style="display:flex;align-items:flex-start;justify-content:flex-start;flex-direction: column;margin-left: 5%;" id="showDetails">
        <div stlye="width:90%;">
            Created Date: <?php echo date('Y.m.d. H:i:s', strtotime($selected->CreatedAt)); ?>
        </div>    
        <div style="width:90%;;">
            Created By: <?php echo $creator->usersName; ?>
        </div>
        <div stlye="width:90%;">
            Address: <?php echo $selected->Address; ?>
        </div>
        <div stlye="width:90%;">
            Cost: <?php echo $selected->Cost; ?>
        </div>
        <div style="width:90%">
            Area: <?php echo $selected->SquareMeter; ?> m<sup>2</sup>
        </div>
        <div style="width:90%">
            Bedrooms: <?php echo $selected->Bedrooms; ?>
        </div>
        <div style="width:90%">
            Bathrooms: <?php echo $selected->Bathrooms; ?>
        </div>
        <div style="width:90%;">
            <?php
                foreach($images as $i)
                {
                    echo '<div style="position:relative;width:20%;height:15%;">';
                    echo "<img src=".$i." width='100%' height='100%' ><br>";
                    echo '</div>';
                }
            ?>
        </div>
    </div>

    <div id="editDetails" style="display:none;margin-left:5%;align-items:flex-start;justify-content:flex-start;flex-direction:column;width:90%;">

        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newphoto">
            Add new photos
        </button>

        <div class="modal fade" id="newphoto"" tabindex="-1" role="dialog" aria-labelledby="newphoto"Title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">add new photos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form style="width:100%" method="post" onsubmit="return false" id="yes1" enctype="multipart/form-data"> 

                            <input type="hidden" name="key" value="uploadPhotos">
                            <input type="hidden" name="listingId" value="<?php echo $_GET['i']; ?>">

                            <input type="file" name="photos[]" multiple="multiple">
                            <br><br>
                            
                            <div style="display:flex;align-items:flex-end;justify-content:flex-end">
                                <button type="submit" class="btn btn-success btn-sm" name="login" onclick="savePhotos()">Save</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <label for="date">
            Created Date
        </label>
        <input type="text" id="date" class="inp" value="<?php echo date('Y.m.d H:i:s', strtotime($selected->CreatedAt)); ?>" disabled>
        
        <label for="creator">
            Creator
        </label>
        <input type="text" id="creator" class="inp" value="<?php echo $creator->usersName?>" disabled>

        <label for="address">
            Adress
        </label>
        <input class="inp" type="text" id="address" value="<?php echo $selected->Address; ?>" ><br>
        
        <label for="cost">
            Cost
        </label>
        <input class="inp" type="text" id="cost" value="<?php echo $selected->Cost; ?>" ><br>

        <div style="display:flex;align-items:flex-end;justify-content:flex-end;">
            <button class="btn btn-success btn-sm" onclick="save()">
                Save Changes
            </button>
        </div>

        <div style="width:90%;">
            <?php
                foreach($images as $i)
                {
                    echo '<div style="position:relative;width:20%;height:15%;">';
                    echo '<i class="fas fa-times-circle" style="color:red;position:absolute;top:0;right:0;cursor:pointer" onclick="deletePic(\''.$i.'\')"></i>';
                    echo "<img src=".$i." width='100%' height='100%' ><br>";
                    echo '</div>';
                }
            ?>
        </div>

        
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    function switchContent(element)
    {
        if(element.innerHTML == 'Edit Content')
        {
            document.getElementById('showDetails').style.display = 'none';
            document.getElementById('editDetails').style.display = 'flex';
            element.innerHTML = 'Show Details';

        }else
        {
            document.getElementById('showDetails').style.display = 'flex';
            document.getElementById('editDetails').style.display = 'none';
            element.innerHTML = 'Edit Content';
        }
    }

    function savePhotos(){

        let fd = new FormData(document.getElementById('yes1'));

        $.ajax({
            type: 'post',
            url: 'process/process.php',
            data: fd,
            cache:false,
            contentType: false,
            processData: false
        }).done((r) => {
            window.location.href = window.location.href;
        })

    }

    function deleteListing()
    {
        if(confirm('Are you sure?'))
        {
            $.ajax({
                type: 'post',
                url: 'process/process.php',
                data: {
                    key: 'deleteListing',
                    id: '<?php echo $_GET['i']?>; '
                },success:function(resp){
                    window.location.href = 'index.php';
                }
                
            })
        }
    }

    function save()
    {
        if(document.getElementById('address').value == '' || document.getElementById('cost').value == '')
        {
            alert('missing fields');
            return 0;
        }

        if(confirm('Are You sure, you want to save changes?'))
        {
            $.ajax({
                type: 'post',
                url: 'process/process.php',
                data: {
                    key: 'editRealEstateDetails',
                    id: '<?php echo $_GET['i']; ?>',
                    address: document.getElementById('address').value,
                    cost: document.getElementById('cost').value,
                    date: '<?php echo $selected->CreatedAt; ?>',
                    creator: '<?php echo $selected->CreatedBy; ?>',
                    active: '<?php echo $selected->Active; ?>',
                    request: '<?php echo $selected->Request; ?>'
                }, success: function(resp){
                    //console.error(resp);
                    window.location.href = window.location.href;
                }
            })
        }
    }

    function send()
    {
        if(confirm('Are You sure?'))
        {
           console.error(document.getElementById('messageText').value);

            $.ajax({
                type: 'post',
                url: 'process/process.php',
                data: {
                    key: 'sendMessage',
                    sender: '<?php echo $loggedInId; ?>',
                    receiver: '<?php echo $selected->CreatedBy; ?>',
                    text: document.getElementById('messageText').value
                }, success: function(resp){
                    console.error(resp);
                }
            });

        }
    }

    function deletePic(element)
    {
        if(confirm('Are you sure you want to delete this photo?'))
        {
            $.ajax({
                type: 'post',
                url: 'process/process.php',
                data: {
                    key: 'deletePhoto',
                    elem: element
                }
            }).done((resp) => {
                window.location.href = window.location.href;
            })
        }
    }
</script>