tinyMCEPopup.requireLangPack();

var SimpleBrowser = {
	init : function() {
		//var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		//f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		//f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');
		//alert(tinyMCEPopup.getWindowArg('plugin_url'));
	},

	insert : function(f) {
		// Insert the contents from the input into the document
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, "<img src='../"+this.dir_path+f+"'/>");
		tinyMCEPopup.close();
	},
	setPath : function(p){
		this.dir_path = p;	
	}
	
	
};

tinyMCEPopup.onInit.add(SimpleBrowser.init, SimpleBrowser);
