<?php

    namespace app\models;

    use \facebook\Facebook;

    class Users extends \lithium\data\Model {

        public static $ApplicationUser = null;
        public static $facebook = null;

        public static function setApplicationUser($user){
            Users::$ApplicationUser = $user;
        }


        public static function sendNotification($fb_id,$notification_message,$notification_app_link){



            $app_id = "432135573474850";
            $app_secret = "f0a937b609df17bb13c09d2a29522476";



            $facebook = new Facebook(array(
            'appId'  => $app_id,
            'secret' => $app_secret,
            ));
    
            try {
                // Try send this user a notification
                $fb_response = $facebook->api('/' . $fb_id . '/notifications', 'POST',
                array(
                'access_token' => $facebook->getAppId() . '|' . $facebook->getApiSecret(), // access_token is a combination of the AppID & AppSecret combined
                'href' => $notification_app_link, // Link within your Facebook App to be displayed when a user click on the notification
                'template' => $notification_message, // Message to be displayed within the notification
                )
                );
                if (!$fb_response['success']) {
                    // Notification failed to send
                   // echo '<p><strong>Failed to send notification</strong></p>'."\n";
                   // echo '<p><pre>' . print_r($fb_response, true) . '</pre></p>'."\n";
                } else {
                    // Success!
                    //echo '<p>Your notification was sent successfully</p>'."\n";
                }

            } catch (FacebookApiException $e) {
                // Notification failed to send
            //    echo '<p><pre>' . print_r($e, true) . '</pre></p>';
                $user = NULL;
            }            

        }

    }

?>