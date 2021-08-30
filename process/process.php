<?php
session_start();
include '../connection.php';
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpMailer/Exception.php';
require '../phpMailer/PHPMailer.php';
require '../phpMailer/src/SMTP.php';
*/
if(isset($_POST['key']) && $_POST['key'] == 'loginsave')
{
    /*
    * key: 'loginsave',
    * name: name,
    * mail: email,
    * bday: birthday
    * pw: pw1
    */

    $options = [
        'cost' => 15
    ];
    
    $args['usersName']        = $_POST['name'];
    $args['usersEMailAdress'] = $_POST['mail'];
    $args['usersBirthDay']    = $_POST['bday'];
    $args['usersRights']      = 1;
    $args['usersActive']      = 1;
    $args['usersPassword']    = password_hash($_POST['pw'], PASSWORD_BCRYPT, $options);
    $args['usersTelNum']      = $_POST['telnum'];

    $u = new User($args);

    $u->create();

 /*   
    $email = $u->usersEMailAdress;
    $mailQ = new PHPMailer(true);
    $mailQ->CharSet = 'utf-8';   
    $mailQ->IsSMTP();
    $mailQ->Host = "213.182.224.25";
    $mailQ->SMTPAuth = true;
    $mailQ->SMTPSecure = false;
    $mailQ->SMTPAutoTLS = false;
    $mailQ->SMTPDebug = 4;

    $mailQ->Username ='';
    $mailQ->Password ='';

    $mailQ->From="";
    $mailQ->FromName="";
    $mailQ->Sender="";

    $mailQ->AddAddress($email);
    $mailQ->Subject = "";
    //$mailQ->addEmbeddedImage('../photos/logomin.png', 'logo');

    $mailQ->Body ="";
                
    $mailQ->isHTML(false);

    echo $mailQ->send();
*/

}

if(isset($_POST['key']) && $_POST['key'] == 'login')
{

    $user = new User();

    $res = $user->checkLogin($_POST['email'],$_POST['pw']);

    print_r($res);

    if(strpos($res,'Could not log in'))
    {
        echo $res;
    }
    else
    {
        $_SESSION['userId'] = $res;
        echo 'done';
    }
}

if(isset($_POST['key']) && $_POST['key'] == 'addnew_realestate')
{

    $args['Address']     = $_POST['Address'];
    $args['Cost']        = $_POST['Cost'];
    $args['Bedrooms']    = $_POST['Bedrooms'];
    $args['Bathrooms']   = $_POST['Bathrooms'];
    $args['SquareMeter'] = $_POST['Quadratmeter'];
    $args['CreatedAt']   = date('Y-m-d H:i:s');
    $args['CreatedBy']   = $_POST['CreatedBy'];

    $re = new RealEstate($args);

    $re->create();

    for($i = 0; $i < count($_FILES['pics']['name']); $i++)
    {
        $extension = explode('.',$_FILES['pics']['name'][$i]);
        $cnt = count($extension);
        $extension = $extension[$cnt - 1];
        

        $path = $_SERVER['DOCUMENT_ROOT'] . "/Szakali_Istvan/media/realEstatePhotos/" . $re->realEstateId . '_' . $i . '.' . $extension;
        
        move_uploaded_file($_FILES['pics']['tmp_name'][$i],$path);
    }

    if($re->realEstateId)
        echo 'done';
}

if(isset($_POST['key']) && $_POST['key'] == 'editRealEstateDetails')
{
    /*
     key: 'editRealEstateDetails',
                    id: '<?php echo $_GET['i']; ?>',
                    address: document.getElementById('address').value,
                    cost: document.getElementById('cost').value,
                    date: '<?php echo $selected->CreatedAt; ?>',
                    creator: '<?php echo $selected->CreatedBy; ?>',
                    active: '<?php echo $selected->Active; ?>',
                    request: '<?php echo $selected->Request; ?>'
    */

    $args['realEstateId'] = $_POST['id'];
    $args['Address'] = $_POST['address'];
    $args['Cost'] = $_POST['cost'];
    $args['CreatedBy'] = $_POST['creator'];
    $args['Active'] = $_POST['active'];
    $args['Request'] = $_POST['request'];

    $re = new RealEstate($args);

    $done = $re->update();

    echo $done;
}

if(isset($_POST['key']) && $_POST['key'] == 'deleteListing')
{
    $re = new RealEstate();

    $real = $re->find_by_id($_POST['id']);

    $real->delete();
}

if(isset($_POST['key']) && $_POST['key'] == 'deactivateUser')
{
    $u = new User();

    $user = $u->find_by_id($_POST['id']);

    $user->usersActive = 0;

    $user->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'activateUser')
{
    $u = new User();

    $user = $u->find_by_id($_POST['id']);

    $user->usersActive = 1;

    $user->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'deactivateListing')
{
    $re = new RealEstate();

    $real = $re->find_by_id($_POST['id']);

    $real->Active = 0;

    $real->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'activateListing')
{
    $re = new RealEstate();

    $real = $re->find_by_id($_POST['id']);

    $real->Active = 1;

    $real->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'acceptListing')
{
    $re = new RealEstate();

    $real = $re->find_by_id($_POST['id']);

    $real->Request = 0;
    $real->Active = 1;

    $real->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'sendMessage')
{
    /*key: 'sendMessage',
    sender: '<?php echo $loggedInId; ?>',
    receiver: '<?php echo $selected->CreatedBy; ?>',
    text: document.getElementById('messageText').value*/

    $args['sender'] = $_POST['sender'];
    $args['receiver'] = $_POST['receiver'];
    $args['messageText'] = $_POST['text'];
    $args['seen'] = 0;

    $me = new Message($args);

    echo $me->create();

}

if(isset($_POST['key']) && $_POST['key'] == 'loadMessage')
{
    $m = new Message();

    $messages = $m->get_messages_between_users($_POST['sen'],$_POST['rec']);


    foreach($messages as $me)
    {
        $curr = $m->find_by_id($me->messagesId);

        if($curr[0]->receiver == $_POST['myUser'])
        {
            $curr = $curr[0];
            
            //print_r($curr);

            $curr->seen = 1;

            $curr->update();
        }
    }

    echo json_encode($messages);
    
}

if(isset($_POST['key']) && $_POST['key'] == "userSelfEdit")
{
    //print_r($_POST);

    $u = new User();

    $oldData = $u->find_by_id($_POST['usersId']);

    $args['usersName']        = $_POST['usersName'];
    $args['usersEMailAdress'] = $_POST['usersEMailAdress'];
    $args['usersBirthDay']    = $_POST['usersBirthDay'];
    $args['usersRights']      = $_POST['usersRights'];
    $args['usersTelNum']      = $_POST['usersTelNum'];
    $args['usersActive']      = 1;
    $args['usersPassword']    = $oldData->usersPassword;

    $userNew = new User($args);

    $userNew->usersId = $_POST['usersId'];

    //print_r($userNew);

    echo $userNew->update();
}

if(isset($_POST['key']) && $_POST['key'] == 'deletePhoto')
{
    unlink("../".$_POST['elem']);
}

if(isset($_POST['key']) && $_POST['key'] == 'resetPW')
{

    $user = new User();

    $res = $user->check_pw_for_reset($_POST['usersId'],$_POST['password']);

    if($res == 'Wrong Password')
    {
        echo 'Wrong password pls try again';
        return 0;
    }

    //***************************************************************************************************/
    //***********EZT KULDJED EMAILBEN ES A LINKET IRD AT AMIKOR MEGLESZ A GITHUBOS LINK ****************/
    //*************************************************************************************************/
/*
    $mailBody = "
        <html>
            <body>
                <h5>
                    To reset your password click <a href='YOURWEBSITE.SS/resetPassword.php?i=".$_POST['usersId']."'><b> HERE </b></a>.
                </h5>
            </body>
        </html>
    ";

    if($mail->send())
    {
        echo 'E-Mail sent!';
    }
*/
}

if(isset($_POST['key']) && $_POST['key'] == "resetPasswordPage")
{
    if($_POST['pw'] !== $_POST['pw2'])
    {
        echo 'Passwords do not match';
        return 0;
    }

    $u = new User();

    $use = $u->find_by_id($_POST['userId']);

    $options = [
        'cost' => 15
    ];

    $use->usersPassword = password_hash($_POST['pw'], PASSWORD_BCRYPT, $options);

    if($use->update())
        echo 'done';


}

if(isset($_POST['key']) && $_POST['key'] == 'uploadPhotos')
{
    $path = "../media/realEstatePhotos/".$_POST['listingId'] . "_";
    $images = glob($path."*.*");


    $cnt = count($images);

    $last = $images[$cnt - 1];

    $last = explode('_',$last);
    $last = explode('.',$last[1]);
    $last = $last[0] + 1;

    for($i = 0; $i < count($_FILES['photos']['name']); $i++)
    {
        $extension = explode('.',$_FILES['photos']['name'][$i]);
        $cnt = count($extension);
        $extension = $extension[$cnt - 1];
        

        $path = $_SERVER['DOCUMENT_ROOT'] . "/Szakali_Istvan/media/realEstatePhotos/" . $_POST['listingId'] . '_' . $last + $i . '.' . $extension;
        
        move_uploaded_file($_FILES['photos']['tmp_name'][$i],$path);
    }
}