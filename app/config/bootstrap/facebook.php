<?php


    use app\models\Users;
    use facebook\Facebook;
  
    $canvas_page = "https://apps.facebook.com/morethanfriendme/";
   // $canvas_page = "http://lithium.darkroast.net"
    if(isset($_REQUEST['hack'])){
        echo("<script> top.location.href='" . $canvas_page . "'</script>");
        return;
    }  

    $app_id = "432135573474850";
    $app_secret = "f0a937b609df17bb13c09d2a29522476";


    $facebook = new Facebook(array(
    'appId'  => $app_id,
    'secret' => $app_secret,
    ));

    $user_id = $facebook->getUser();

    if($user_id){

        $user = Users::find('first',array(
        'conditions'=>array('fb_id'=> (string) $user_id )
        )
        );

        if($user === null){
            $new_user = Users::create();
            $new_user->fb_id = (string) $user_id;
            $new_user->picks_left = 3;
            $new_user->save(); 
            $user = $new_user;
        }  

        Users::setApplicationUser($user);     

    } else {
        $auth_url = "https://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . urlencode($canvas_page); 
        echo("<script> top.location.href='" . $auth_url . "'</script>");
    }

?>