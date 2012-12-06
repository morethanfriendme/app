<?php

    namespace app\models;
    
    class Picks extends \lithium\data\Model {
        
        
        /**
        * Get Users Picks
        *  
        * @param mixed $user_id
        */
        public static function getUsersPicks($user_id){
            $picks = Picks::find('all',array(
                'conditions'=>array('u_id'=> $user_id)
            ));
            
            return $picks;
        }
        
        
        
        /**
        * Find Mutual Pick
        * 
        * This looks up the reverse pick of a pick,
        * IE when i user picks someone, it looks up if they have a pick waiting for them
        * it (WE FOUND A MATCH)
        * 
        * @param mixed $user_id
        * @param mixed $fb_lover_id
        */
        public static function findMutualPick($user_id,$fb_lover_id){
            $pick = Picks::find('first', array(
                'conditions'=>array('u_id'=>$fb_lover_id,'fb_id'=>$user_id)
            ));
            
            return $pick;
        }
        
        
        
        
        
        /**
        * Create User Pick
        * 
        * @param mixed $fb_id
        * @param mixed $name
        * @param mixed $gender
        */
        public static function createPick($user_id, $fb_id,$name,$gender){
            
            $pick = Picks::create();
            $pick->u_id = $user_id;
            $pick->fb_id = $fb_id;
            $pick->name = $name;
            $pick->gender = $gender;
            $pick->unlock_ts =  strtotime('+1 week');
            $pick->save();
            return;
            
            //We need to Send a notification to the user via facebook;
        }
              
              
                
        /**
        * Can Delete Pick
        */
        public function canDelete($pick){

            $pick_unlock_date = $pick->unlock_ts;
            
            $can_delete = false;
            if($pick_unlock_date < time()){
                $can_delete = true;
            }
            
            return $can_delete;
        }
        
        
    }

?>