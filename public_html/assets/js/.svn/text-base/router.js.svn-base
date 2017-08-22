//Backbone Router
var Router = Backbone.Router.extend({
	routes: {
		"detail/:mod/:id" : "contentDetail"
	},
	contentDetail: function(mod, id){
		$.post("index.php?page="+mod+"&act=detail&id="+id, function(data) {
			 console.log(data);
		});
	}
});

var app_router = new Router;
Backbone.history.start();