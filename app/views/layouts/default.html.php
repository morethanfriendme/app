<!doctype html>
<html>
    <head>
        <?php echo $this->html->charset();?>
        <title>Application &gt; <?php echo $this->title(); ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="/js/base/pubsub.js"></script>
        <script src="/js/base/underscore-min.js"></script>        
        <script src="/js/base/backbone-min.js"></script>        
        <script src="/js/base/mustache.js"></script>                
        <script src="/js/models/user.model.js"></script>            
            
        <?php echo $this->html->style(array('debug', 'modern', 'layout')); ?>
        <?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
    </head>
    <body class="app">
        <div id="fb-root"></div>
        <div class="navigation-bar clearfix">
            <div class="navigation-bar-inner">

                <div class="brand">       
                    <img src="http://lithium.darkroast.net/img/MoreThanFriendMe_Logo.png" style="width: 25px;position: relative;top: 5px;margin-right: 8px;">
                    <span class="name">More Than Friend Me</span>
                </div>

                <ul class="place-right">
                    <li data-role="dropdown" class="sub-menu">
                        <a>Help</a>
                        <ul class="dropdown-menu place-right">
                            <li><a href="#">SubItem</a></li>
                            ...
                            <li><a href="#">SubItem</a></li>
                        </ul>
                    </li>
                </ul>

            </div>               
        </div>
        <div id="page_content">
            <?php echo $this->content(); ?>
        </div>
        <script>
            window.fbAsyncInit = function() {
                // init the FB JS SDK
                FB.init({
                    appId      : '432135573474850', // App ID from the App Dashboard
                    channelUrl : '//lithium.darkroast.net/channel.html', // Channel File for x-domain communication
                    status     : true, // check the login status upon init?
                    cookie     : true, // set sessions cookies to allow your server to access the session?
                    xfbml      : true  // parse XFBML tags on this page?
                });                    
                
                // Listen for Facebook login / logout events
                FB.Event.subscribe('auth.login', function (response) { window.location.reload(); });
                FB.Event.subscribe('auth.logout', function (response) { window.location.reload(); });

                $.publish('facebook-ready');
            };

            // Load the SDK's source Asynchronously
            (function(d){
                var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/en_US/all.js";
                ref.parentNode.insertBefore(js, ref);
            }(document));         
        </script>        
        <?php echo $this->scripts(); ?>                
    </body>
</html>