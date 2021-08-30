<?php
session_start();
include 'connection.php';

//print_r($_SESSION);
//session_destroy();

if(isset($_SESSION['userId']))
{
    $u = new User();
    $loggedIn = $u->find_user_rights($_SESSION['userId'])->usersRights;
}else
    $loggedIn = 1;


if($loggedIn == 0)
    header("Location: adminIndex.php");

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

<?php
//print_r($_SESSION);
include 'header.php';

$re = new RealEstate();
$result = $re->find_all();
?>

<h3 style="text-align:center; margin-top:2%;margin-bottom:2%;">
    Real Deal
</h3>


<?php
if($loggedIn == 1)
{
?>
<div style="display:flex;align-items:flex-end;justify-content:flex-end;margin-bottom:1%;width:98%;">
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addnew">
        Add new listing
    </button>

<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="addnewTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addnewTitle">Add new</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form style="width:100%" onsubmit="return false" enctype="multipart/form-data" method="post" id="newRealEstateForm">
            <div style="display:flex;align-items:flex-start;justify-content:flex-start;flex-direction:column">
                <label for="addnew_address">
                    Address
                </label>
                <input type="text" placeholder="Address" id="addnew_address" name="Address" class="inp"><br>

                <label for="addnew_cost">
                    Cost
                </label>
                <input type="text" placeholder="Cost" id="addnew_cost" name="Cost" class="inp"><br>

                <label for="bedrooms">
                    Number of bedrooms
                </label>
                <input type="number" class="inp"id="bedrooms" name="Bedrooms" min="1" ><br>

                <label for="bathrooms">
                    Number of bathrooms
                </label>
                <input type="number" class="inp"id="bathrooms" name="Bathrooms" min="1" ><br>

                <label for="Quadratmeter">
                    Area
                </label>
                <input type="number" class="inp"id="Quadratmeter" name="Quadratmeter" min="30" ><br>

                <label for="images">
                    Add Photos
                </label>
                <input type="file" id="images" name="pics[]" multiple="multiple"><br><br>

                <input type="hidden" name="key" value="addnew_realestate">
                <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['userId']; ?>">

                <div class="fullWidth AlignFlexEnd">
                    <button type="button" class="btn btn-primary btn-sm" onclick="save()">Save</button>
                </div>
            </div>
        </form>
        </div>
    </div>
  </div>
</div>
</div>
<?php
}
?>

<table class="table table-hover" style="width:95%;margin-left:2%;">
    <thead>
        <tr>
            <th>
                Id
            </th>
            <th>
                Address
            </th>
            <th>
                Cost
            </th>
            <th>
                View Details
            </th>
        </tr>
    </thead>
    <?php
        foreach($result as $r)
        {
            if($r->Active == 1)
            {
                echo '<tr>';
                    echo'<td>';
                        echo $r->realEstateId;
                    echo'</td>';
                    echo'<td>';
                        echo $r->Address;
                    echo'</td>';
                    echo'<td>';
                        echo $r->Cost;
                    echo'</td>';
                    echo '<td>';
                        echo '<a href="realEstateDetails.php?i='.$r->realEstateId.'">';
                            echo 'View Details';
                        echo '</a>';
                    echo '</td>';
                echo '<tr>';
            }
        }
    ?>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
function save(){

    let fm = new FormData(document.getElementById('newRealEstateForm'));

    $.ajax({
        type: 'post',
        url: 'process/process.php',
        data: fm,
        cache: false,
        contentType: false,
        processData: false
    }).done( (resp) => {
        console.log(resp);
    }).fail( (resp) => {
        console.log('failed');
    });

}
</script>   

