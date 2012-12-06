var User = Backbone.Model.extend({
        
    initialize:function(){
        this.subscribe();
    },
    
    subscribe:function(){
        
        this.on('change:pick_count',function(model, pick_count){
            $.publish('user-pick-count-change',[pick_count]);
        });
        
    }
    
});

var MTFM_User = new User({
    pick_count:3
});
