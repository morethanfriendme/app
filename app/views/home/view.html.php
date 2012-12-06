<?php
    $this->scripts('<script src="/js/views/picks.view.js"></script>');
?>

<div class="tile double" id="picks-profile" style="width:100%;margin-bottom:0px">
    <div class="tile-status">
        <div class="badge newMessage"></div>
    </div>
</div>


<ul class="accordion dark span10" data-role="accordion" style="width:100%">
    <li class="active" id="your_picks">
        <a href="#">Your Picks ( <span class="picks_remaining">3</span> Remaining )</a>
        <div style="overflow: hidden;border:none " id="choose_blurb">               
            <h2 style="color:#FFF" class="text" >Loading Facebook Friends...</h2>
        </div> 
        <div id="friend-picks" class="image-collection" style="border:none;padding:0;margin:0">
          <!--   <div class="tile small-tile friend" style="">
                <div style="width: 120px; height: 120px; overflow: hidden; display: inline-block;border: 3px solid white;">
                    <img src="https://graph.facebook.com/21003168/picture?return_ssl_resources=1.&amp;type=large" width="120" alt="charlie" class="place-left" style="width: 121px;margin:0;min-width: 120px; min-height: 120px;">   
                </div>  
                <div class="overlay">David Houghton</div>          
            </div>         -->
        </div>

    </li>
    <li class="active" style="position:relative">
        <a href="#">Your Friends (<span class="friend-count">loading</span>)</a>
        <div class="input-control text" style="color:#FFF;border: none;margin: 0;padding: 0;width: 260px;position: absolute;right: 13px;top: 1px;">
            <input type="text" placeholder="Quick Search" id="picks-quick-search" style="color:#FFF;border: 2px black solid;background: #333;position: relative;margin-right: 0;">
        </div>

        <label class="switch guys" style="color: #FFF;position: absolute;right: 435px;top: 1px;">
            <input type="checkbox" id="guy_toggle" checked="checked">
            <span>Show Guys</span>
        </label>

        <label class="switch girls" style="color: #FFF;position: absolute;right: 285px;top: 1px;">
            <input type="checkbox" id="girl_toggle" checked="checked">
            <span>Show Girls</span>
        </label>
        <div style="border: none;position: absolute;background: white;height: 100%;width: 100%;z-index: 2;top: 28px;opacity: 0.6;display:none;min-height: 500px;" id="loading_overlay"></div>
        <div style="overflow: hidden;border:none;padding:0;margin:0" id="friends" class="image-collection">

        </div>
    </li>
</ul>

