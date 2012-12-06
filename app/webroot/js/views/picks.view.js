var PicksView = Backbone.View.extend({

    show_male:true,
    show_female:true,
    loading_timeouts:[],

    initialize:function(){
        this.get_facebook_friends();
        this.subscribe();
    },

    events:{
        'keyup #picks-quick-search':'quick_search',
        'click #friends .friend':'pick',
        'click #guy_toggle':'toggle_guys',
        'click #girl_toggle':'toggle_girls',
        'click .icon-unlocked' : 'lock_pick'
    },


    toggle_guys:function(){
        this.show_male = this.show_male ? false : true;
        this.$('#picks-quick-search').keyup();
    },


    toggle_girls:function(){
        this.show_female = this.show_female ? false : true;
        this.$('#picks-quick-search').keyup();
    },


    subscribe:function(){
        var view = this;
        $.subscribe('user-pick-count-change',function(e,picks_remaining){
            view.$('.picks_remaining').html(picks_remaining);
        }); 
    },

    get_facebook_friends:function(){
        // Additional initialization code such as adding Event Listeners goes here
        var view = this;
        $.subscribe('facebook-ready',function(){
            FB.getLoginStatus(function(response){
                FB.api('/me?fields=id,name',function(current_fb_user){
                    $('#picks-profile').prepend(Mustache.to_html(view.profile_template,current_fb_user));
                    FB.api('/'+ current_fb_user.id+'/friends?fields=installed,name,first_name,gender,picture,relationship_status',function(response){
                        var length = response.data.length;
                        var friends = response.data;
                        this.$('.friend-count').html(response.data.length);
                        var friend_template = '<div class="tile small-tile friend" style="display:none">\
                        <div style="width: 120px; height: 120px; overflow: hidden; display: inline-block;border: 3px solid white;">\
                        <img src="https://graph.facebook.com/{{id}}/picture?return_ssl_resources=1.&type=large" width=120 alt="charlie" class="place-left" style="width: 121px;margin:0;min-width: 120px; min-height: 120px;" />\
                        <div class="lock" style="position:absolute;top:0px;left:0px;width:100%;height:100%;background:rgba(255,255,255,0.2)">\
                        <i class="icon-unlocked lock-icon" style="font-size: 36px;position: absolute;top: 12px;left: 10px;"></i>\
                        </div>\
                        </div>\
                        <div class="overlay">{{name}}</div>\
                        </div>';  

                        var $friends = $('#friends');

                        //Store Friends List In Dom.
                        view.$el.data('friends',response.data);

                        //Fade In Each Friend
                        $.each(response.data,function(i,contact){

                            var friend = friends[i];
                            var $friend_html = $(Mustache.to_html(friend_template,friend)); 
                            $friends.append($friend_html);

                            $friend_html.data('fb_info',contact);

                            view.loading_timeouts[i] = setTimeout(function(){
                                $friend_html.fadeIn();
                                delete view.loading_timeouts[i];
                            },20*i);  
                        });
                        
                        $('#choose_blurb').find('.text').html('Choose up to 3 friends to be <strong>more</strong> than friends with');  
                        
                    });
                });
            });
        });

    },

    clear_loading_timeouts:function(){
        var loading_timeouts = this.loading_timeout;
        var view = this;
        $.each(view.loading_timeouts,function(key,timeout){
            clearTimeout(timeout);
        });
    },


    profile_template:'<div class="tile-content" style="width:100%">\
    <img src="http://graph.facebook.com/{{id}}/picture?type=large" width="95" height="95" alt="charlie" class="place-right" style="border: 3px solid white;">\
    <h4 style="margin-bottom: 5px;">{{name}} (you)</h4>\
    <p>BIO Goes Here.</p>\
    <h5></h5>\
    <p></p>\
    </div>\
    <div class="brand">\
    <span class="name">{{name}}</span>\
    </div>',

    quick_search:function(e){
        $("#loading_overlay").show();
        var view = this;

        if(typeof(this.quick_search_delay !== 'undefined')){
            clearTimeout(this.quick_search_delay);         
        }
        view.clear_loading_timeouts();
        this.quick_search_delay = setTimeout(function(){

            var $input = $(e.target);
            var friend_list = view.$el.data('friends');

            var name_search = $input.val();
            var $container = $('#friends');

            var $items = $container.find('.friend');
            $items.hide();

            var $found = [];
            $.each(friend_list,function(index,contact){
                var name = contact.name;
                console.log('show_'+contact.gender);
                console.log(view['show_'+contact.gender]);
                if(view['show_'+contact.gender] == true){
                    var search = __highlight(name,name_search);
                    if(search.match){
                        var $item = $($items[index]);
                        $item.find('.overlay').html(search.name); 
                        $found.push($item);                           
                    }
                }
            });

            $.each($found,function(index,$found_friend){
                view.loading_timeouts[index] = setTimeout(function(){
                    $found_friend.fadeIn();
                },20*index);   
            })

            $('#loading_overlay').hide();

            function __highlight(s, t) {
                var matcher = new RegExp("("+t+")", "ig" );     
                var response = {}
                response.match = matcher.test(s);
                response.name = s.replace(matcher, "<strong style='font-size:1.2em'>$1</strong>");
                return response;
            }
        },500);        

    },

    lock_pick:function(e){

        var $friend = $(e.target).closest('.friend');

        var friend_info = $friend.data('fb_info');

        var request = $.ajax({
            url:'/picks/create_pick.json',
            type:'POST',
            dataType:'json',
            data:{
                fb_id : friend_info.id,  
                gender : friend_info.gender,
                name : friend_info.name
            }
        });

        request.done(function(response){
            console.log(response);
            $friend.find('.lock-icon').removeClass().addClass('icon-locked lock-icon');
        });

        request.error(function(){
            alert("There was an error on the server");
        });

    },

    pick:function(e){
        var pick_count = MTFM_User.get('pick_count');


        if(pick_count > 0){
            var $friend = $(e.target).closest('.friend');

            if($friend.hasClass('selected')){   
                return;
            }
            var friend_info = $friend.data('fb_info');
            
            pick_count--;
            MTFM_User.set({'pick_count':pick_count});
            $friend.addClass('selected');
            var $friend_clone = $friend.clone();
            $('#choose_blurb').fadeOut(250);
            $('#your_picks').animate({height:161},250,function(){
                $friend_clone.hide();
                $friend_clone.addClass('picked');
                $friend_clone.data('fb_info',friend_info);
                $('#friend-picks').prepend($friend_clone);
                $friend_clone.fadeIn();
            })
        } else {
            alert("No More Picks Remaining (purchase dialog would go here)");
        }
    }

});


var PicksViewInstance = new PicksView({el:'#page_content'});
