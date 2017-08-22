function dragoncrop(c,containers){

				$(containers+' #x').val(c.x);
				$(containers+' #y').val(c.y);
				$(containers+' #w').val(c.w);
				$(containers+' #h').val(c.h);
				
				$(".jcrop-holder img").draggable({ axis: "y" });
				$(".jcrop-tracker").attr('style','cursor: default; position: absolute; z-index: -1');
					
				$( ".jcrop-holder img" ).on( "drag", function( event, ui ) {
					//$(containers+' #x').val(parseInt(Math.abs(ui.position.left),10));
					$(containers+' #y').val(parseInt(Math.abs(ui.position.top),10));
				});
					
		}
	
function cropperHelper(opt){
					
					var imageForm = opt.imageForm;
					var imageThumbForm = opt.imageThumbForm;
					var previewPhotoProfile = opt.previewPhotoProfile;
					var photoProfile = opt.photoProfile;
					var imageFilename = opt.imageFilename;
					var smallthumb = opt.smallthumb;
					var imageCropPath = opt.imageCropPath;
					var msgNotif = opt.msgNotif;
					var saveButton = opt.saveButton;			
					var tempstuck = false;			
					var autoSize = opt.autoSize;						
							
					$(previewPhotoProfile+' #preview').Jcrop({
						onSelect: updateCoordsProfile,
						setSelect:   autoSize
					});					
					
					function getImages(data,prev,original,sendImage){
									initImage();							
   
									$(previewPhotoProfile).html("<img id='preview' src='' />");
								
									$(previewPhotoProfile+" #preview").attr('src',basedomain+imageCropPath+original+data);
									
									$(imageFilename).val(original+data);
									if (sendImage) {
										$(photoProfile+" a img").attr("src",basedomain+imageCropPath+prev+data);
										if(smallthumb) $(smallthumb+" a img").attr("src",basedomain+imageCropPath+prev+data);
									}

									$(previewPhotoProfile+" #preview").Jcrop({
												onSelect: updateCoordsProfile,
												setSelect:   autoSize
									});			

								$(saveButton).show();									
					}
					
					function getNotification(msgNotif){
						$("#msg_uploadprofile").html("<br><h4>"+msgNotif+"</h4>");
						$(previewPhotoProfile+" #preview").hide();
					}
					
					function initImage(){
						$(previewPhotoProfile).html("");
						$("#msg_uploadprofile").html("");
						$(previewPhotoProfile).html("<img id='preview' src='' />");
						$(previewPhotoProfile+" #preview").attr('src',basedomain+'assets/images/loader.gif');
						$(saveButton).hide();
					}
					
					$(document).on('change',imageForm,function(){
						initImage();
						$(this).submit();
					});
					
					var updateoptions = {
						dataType:  'json', 
						success : function(data) {									
							if(data) getImages(data,"","",false);
							else getNotification(msgNotif);				
						}
					};					
					
					$(imageForm).ajaxForm(updateoptions);
					
					var optionsForThumb = {
							dataType:  'json',
							beforeSubmit: function(data) { 
								initImage();		
							},
							success:    function(data) { 
							
								if(data) {
									getImages(data,"prev_","original_",true);
									tempstuck = true;
								}else {
									getImages(data);
									return false;
								}
					
						} 
					};
					
					$(imageThumbForm).ajaxForm(optionsForThumb);
					
					
				
				function updateCoordsProfile(c)
				{
					dragoncrop(c,imageThumbForm);				
					
				};
				
		
			}
			
			