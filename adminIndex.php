<?php
session_start();
include 'connection.php';
$u  = new User();
$re = new RealEstate();

if(isset($_SESSION['userId']))
{
    
    $loggedIn = $u->find_user_rights($_SESSION['userId'])->usersRights;

}else{
    Header("Location: index.php");
}


$allUsers = $u->find_all();
$allListings = $re->find_all();

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
        Admin Page
    </h3>

    <div style="margin-left:2%;width:60%;display:flex-align-items:center;justify-content:space-between;margin-bottom:2%;">
        <button class="btn btn-sm btn-primary" onclick="changeTo('allListing')">
            Show active listings
        </button>
        <button class="btn btn-sm btn-primary" onclick="changeTo('notActive')">
            Show not active listings
        </button>
        <button class="btn btn-sm btn-primary" onclick="changeTo('pending')">
            Show Pending Listings
        </button>
        <button class="btn btn-sm btn-primary" onclick="changeTo('usersActive')">
            Show Active Users
        </button>
        <button class="btn btn-sm btn-primary" onclick="changeTo('usersDeAct')">
            Show Deactivated Users
        </button>
    </div>

    <div style="margin-left:2%;width:95%;" id="allListing">
        <table class="table table-hover table-striped" style="width:100%;">
            <thead>
                <tr>
                    <th>
                        Address
                    </th>
                    <th>
                        Cost
                    </th>
                    <th>
                        Request
                    </th>  
                    <th>
                        Created Date
                    </th>
                    <th>
                        Active
                    </th>
                    <th>
                        Details
                    </th>
                </tr>
            </thead>  
            <tbody>
                <?php
                    foreach($allListings as $a)
                    {
                        if($a->Active == 1)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $a->Address;
                                echo '</td>';
                                echo '<td>';
                                    echo $a->Cost;
                                echo '</td>';
                                echo '<td>';
                                    if($a->Request == 1)
                                        echo 'Pending';
                                    else
                                        echo 'Accepted';
                                echo '</td>';
                                echo '<td>';
                                    echo date('Y-m-d H:i:s', strtotime($a->CreatedAt));
                                echo '</td>';
                                echo '<td>';
                                    echo '<button class="btn btn-sm btn-danger" onclick="activateListing(\''.$a->realEstateId.'\',\'deactivateListing\')">';
                                        echo 'Deactivate';
                                    echo '</button>';
                                echo '</td>';
                                echo '<td>';
                                    echo '<a href="realEstateDetails.php?i='.$a->realEstateId.'">Details</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
        </table>   
    </div>

    <div style="margin-left:2%;width:95%;display:none" id="notActive">
        <table style="width:100%;" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>
                        Address
                    </th>
                    <th>
                        Cost
                    </th>
                    <th>
                        Requested
                    </th>
                    <th>
                        Created Date
                    </th>
                    <th>
                        Active
                    </th>
                    <th>
                        Details
                    </th>
                </tr>
            </thead>  
            <tbody>
                <?php
                    foreach($allListings as $a)
                    {
                        if($a->Active == 0)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $a->Address;
                                echo '</td>';
                                echo '<td>';
                                    echo $a->Cost;
                                echo '</td>';
                                echo '<td>';
                                    if($a->Request == 1)
                                        echo 'Pending';
                                    else
                                        echo 'Accepted';
                                echo '</td>';
                                echo '<td>';
                                    echo date('Y.m.d H:i:s', strtotime($a->CreatedAt));
                                echo '</td>';
                                echo '<td>';
                                    echo '<button class="btn btn-sm btn-success" onclick="activateListing(\''.$a->realEstateId.'\',\'activateListing\')">';
                                        echo 'Activate';
                                    echo '</button>';
                                echo '</td>';
                                echo '<td>';
                                    echo '<a href="realEstateDetails.php?i='.$a->realEstateId.'">Details</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
        </table>   
    </div>

    <div style="margin-left:2%;width:95%;display:none" id="pending">
        <table class="table table-hover table-striped" style="width:100%;">
            <thead>
                <tr>
                    <th>
                        Address
                    </th>
                    <th>
                        Cost
                    </th>
                    <th>
                        Active
                    </th>
                    <th>
                        Requested
                    </th>
                    <th>
                        Created Date
                    </th>
                    <th>
                        Details
                    </th>
                    <th>
                        Accept Listing
                    </th>
                </tr>
            </thead>  
            <tbody>
                <?php
                    foreach($allListings as $a)
                    {
                        if($a->Request == 1)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $a->Address;
                                echo '</td>';
                                echo '<td>';
                                    echo $a->Cost;
                                echo '</td>';
                                echo '<td>';
                                    if($a->Active == 1)
                                        echo 'yes';
                                    else
                                        echo 'no';
                                echo '</td>';
                                echo '<td>';
                                    if($a->Request == 1)
                                        echo 'Pending';
                                    else
                                        echo 'Accepted';
                                echo '</td>';
                                echo '<td>';
                                    echo date('Y.m.d H:i:s', strtotime($a->CreatedAt));
                                echo '</td>';
                                echo '<td>';
                                    echo '<a href="realEstateDetails.php?i='.$a->realEstateId.'">Details</a>';
                                echo '</td>';
                                echo '<td>';
                                    echo '<button class="btn btn-sm btn-success" onclick="activateListing(\''.$a->realEstateId.'\',\'accept\')">';
                                        echo 'Accept';
                                    echo '</button>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
        </table>   
    </div>

    <div style="margin-left:2%;width:95%;display:none" id="usersActive">
        <table class="table table-hover table-striped" style="width:100%;">
            <thead>
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Rights
                    </th>
                    <th style="text-align:right">
                        Deactivate
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($allUsers as $a)
                    {
                        if($a->usersActive == 1)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $a->usersName;
                                echo '</td>';
                                echo '<td>';
                                    echo $a->usersEMailAdress;
                                echo '</td>';
                                echo '<td>';
                                    echo '
                                    <select onchange="changeRights('.$a->usersId.')">
                                        <option value="0"';
                                        if($a->usersRights == 0)
                                            echo 'selected';
                                        echo'>
                                            Admin
                                        </option>

                                        <option value="1"';
                                        if($a->usersRights == 1)
                                            echo 'selected';
                                        echo '>
                                            User
                                        </option>
                                    </select>
                                    ';
                                echo '</td>';
                                echo '<td style="text-align:right">';
                                    echo '<button class="btn btn-sm btn-danger" onclick="activateUser(\''.$a->usersId.'\',\'deactivate\')">';
                                        echo 'Deactivate';
                                    echo '</button>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>   
        </table>    
    </div>

    <div style="margin-left:2%;width:95%;display:none" id="usersDeAct">
        <table class="table table-hover table-striped" style="width:100%;">
            <thead>
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Rights
                    </th>
                    <th style="text-align:right">
                        Activate
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($allUsers as $a)
                    {
                        if($a->usersActive == 0)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $a->usersName;
                                echo '</td>';
                                echo '<td>';
                                    echo $a->usersEMailAdress;
                                echo '</td>';
                                echo '<td>';
                                    echo '
                                    <select onchange="changeRights('.$a->usersId.')">
                                        <option value="0"';
                                        if($a->usersRights == 0)
                                            echo 'selected';
                                        echo'>
                                            Admin
                                        </option>

                                        <option value="1"';
                                        if($a->usersRights == 1)
                                            echo 'selected';
                                        echo '>
                                            User
                                        </option>
                                    </select>
                                    ';
                                echo '</td>';
                                echo '<td style="text-align:right">';
                                    echo '<button class="btn btn-sm btn-success" onclick="activateUser(\''.$a->usersId.'\',\'activate\')">';
                                        echo 'Activate';
                                    echo '</button>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>   
        </table>   
    </div>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    function changeTo(element)
    {
        if(element == 'allListing')
            document.getElementById('allListing').style.display = 'block';
        else
            document.getElementById('allListing').style.display = 'none';

            if(element == 'pending')
            document.getElementById('pending').style.display = 'block';
        else
            document.getElementById('pending').style.display = 'none';
            
        if(element == 'notActive')
            document.getElementById('notActive').style.display = 'block';
        else
            document.getElementById('notActive').style.display = 'none';
            
        if(element == 'usersActive')
            document.getElementById('usersActive').style.display = 'block';
        else
            document.getElementById('usersActive').style.display = 'none';

        if(element == 'usersDeAct')
            document.getElementById('usersDeAct').style.display = 'block';
        else
            document.getElementById('usersDeAct').style.display = 'none';
    }

    function activateUser(userid,action)
    {
        if(action == 'deactivate')
        {
            if(confirm('Are You sure?'))
            {
                $.ajax({
                    type: 'post',
                    url: 'process/process.php',
                    data: {
                        key: 'deactivateUser',
                        id: userid
                    }, success: function(resp){
                        window.location.href = window.location.href;
                    }
                });
            }

        }else{
            if(confirm('Are You sure?'))
            {
                $.ajax({
                    type: 'post',
                    url: 'process/process.php',
                    data: {
                        key: 'activateUser',
                        id: userid
                    }, success: function(resp){
                        window.location.href = window.location.href;
                    }
                });
            }
        }
    }

    function activateListing(listId,action)
    {
        if(action == 'deactivateListing')
        {
            if(confirm('Are you sure?'))
            {
                $.ajax({
                    type: 'post',
                    url: 'process/process.php',
                    data: {
                        key: 'deactivateListing',
                        id: listId
                    },success: function(resp){
                        window.location.href = window.location.href;
                    }
                });
            }

        }else if(action == 'activateListing'){
            if(confirm('Are you sure?'))
            {
                $.ajax({
                    type: 'post',
                    url: 'process/process.php',
                    data: {
                        key: 'activateListing',
                        id: listId
                    },success: function(resp){
                        window.location.href = window.location.href;
                    }
                });
            }
        }else if(action == 'accept'){
            if(confirm('Are You sure?'))
            {
                $.ajax({
                    type: 'post',
                    url: 'process/process.php',
                    data: {
                        key: 'acceptListing',
                        id: listId
                    },success: function(resp){
                        window.location.href = window.location.href;
                    }
                });
            }
        }
    }

</script>    