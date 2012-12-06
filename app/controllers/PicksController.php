<?php
    /**
    * Lithium Boilerplate: the most rad php framework (boilerplate)
    *
    * @copyright     Copyright 2012, Darkroast.net
    * @license       http://opensource.org/licenses/bsd-license.php The BSD License
    */

    namespace app\controllers;
    use app\models\Users;
    use app\models\Picks;

    class PicksController extends \lithium\action\Controller {


        /**
        * Lock in a pick
        * 
        */
        public function create_pick() {

            $result = -1;

            $msg = 'Params missing';            
            if(isset($_POST['fb_id'])){
                $logged_in_user   = Users::$ApplicationUser;
                $logged_in_user_fbid = $logged_in_user->fb_id;

                $pick_fb_id = (string) $_POST['fb_id'];
                $pick_name = $_POST['name'];
                $pick_gender = $_POST['gender'];


                $result = -2;
                $msg = 'No more picks remaining';
                if($logged_in_user->picks_left > 1){ 

                    //Check if pick already exists
                    $pick = Picks::find('first', array(
                    'conditions' => array(
                    'u_id' => (string) $logged_in_user_fbid,
                    'fb_id' => (string) $pick_fb_id
                    )));

                    $result = '-3';
                    $msg = 'Pick already exists';

                    if($pick == NULL){
                        Picks::createPick($logged_in_user_fbid,$pick_fb_id,$pick_name,$pick_gender); 

                        $mutual_pick = Picks::find('first',array(
                        'conditions' => array(
                        'u_id' => (string) $pick_fb_id,
                        'fb_id' => (string) $logged_in_user_fbid
                        )
                        ));

                        if($mutual_pick == null){

                            $notification_message = "Someone you know wants to be MORE THAN FRIENDS with you";
                            Users::sendNotification($pick_fb_id, $notification_message, '');

                            $notification_message_for_this_user =  "You might get lucky! An anonymous notification has been sent to @[".$pick_fb_id."] letting them know that someone wants to be MORE THAN FRIENDS.";
                            Users::sendNotification($logged_in_user_fbid, $notification_message_for_this_user, '');

                        } else {

                            $notification_message = "There has been a match! Looks like you and @[".$logged_in_user_fbid."] want to be more than friends";
                            Users::sendNotification($pick_fb_id, $notification_message, '');

                            $notification_message_for_this_user = "There has been a match! Looks like you and @[".$pick_fb_id."] want to be more than friends";
                            Users::sendNotification($logged_in_user_fbid, $notification_message_for_this_user, '');

                            Picks::remove(array(
                            'u_id' => (string) $logged_in_user_fbid,
                            'fb_id'=> (string) $pick_fb_id
                            ));

                            Picks::remove(array(
                            'fb_id' => (string) $logged_in_user_fbid,
                            'u_id'=> (string) $pick_fb_id
                            ));                        

                        }

                        $result = TRUE;
                        $msg = 'Success';                   
                    }
                }
            }

            return compact('result','msg');
        }



        /**
        * Create a pick
        * 
        */
        public function delete_pick() {

            $result = false;

            $msg = 'Params missing';
            $result = -1;
            if(isset($_POST['fb_id'])){
                $logged_in_user_id = Users::$ApplicationUser->fb_id;
                $pick_fb_id = $_POST['fb_id'];

                $pick = Picks::find('first',array(
                'conditions' => array(
                'u_id' => (string) $logged_in_user_id,
                'fb_id'=> (string) $pick_fb_id   
                )
                ));

                $msg = 'Pick does not exist';
                $result = -2;
                if($pick != null){

                    Picks::remove(array(
                    'u_id' => (string) $logged_in_user_id,
                    'fb_id'=> (string) $pick_fb_id
                    ));

                    $result = TRUE;
                    $msg = 'Success';

                }                
            }

            return compact('result','msg');

        }
    }

?>