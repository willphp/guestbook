var he = HE.getEditor('editor',{
	  autoHeight : true,
	  uploadPhoto : true,
	  uploadPhotoHandler : base_url + '/api/editor_upload',
	  uploadPhotoSize : 1048576, //1M 
	  uploadPhotoType : 'gif,png,jpg,jpeg',
	  uploadPhotoSizeError : '不能上传大于1MB的图片',
	  uploadPhotoTypeError : '只能上传gif,png,jpg,jpeg格式的图片',
	  skin : 'willphp',
	  item : ['html','|','bold','underline','paragraph','color','backColor','|','link','unlink','|','textBlock','code','|','image','expression','|','orderedList','unorderedList','|','undo','redo','selectAll','removeFormat','trash']
});
$('#submit').click(function(){
	he.sync();
});