// $(function() {
//     $("#file_upload").uploadify({
//         'swf'             : SCOPE.uploadify_swf,
//         'uploader'        : SCOPE.image_upload,
//         'buttonText'      : '图片上传',
//         'fileTypeDesc' : 'Image Files',
//         'fileObjName'  : 'file',
//         'fileTypeExts' : '*.gif; *.jpg; *.png',
//         'onUploadSuccess' : function(file, data, response) {
//             console.log(file);
//             console.log(data);
//             console.log(response);
//             if (response){
//                 var obj = JSON.parse(data);
//                 $('#upload_org_code_img').attr('src',obj.data);
//                 $('#file_upload_image').attr('value',obj.data);
//                 $('#upload_org_code_img').show();
//             }
//         }
//     });
// });
$('#upload').Huploadify({
    auto:true,
    fileTypeExts:'*.jpg;*.png;',
    multi:true,
    formData:{key:123456,key2:'vvvv'},
    fileSizeLimit:1024,
    showUploadedPercent:true,
    showUploadedSize:true,
    removeTimeout:9999999,
    uploader:SCOPE.image_upload,
    onUploadStart:function(){
        console.log('开始上传');
    },
    onInit:function(){
        console.log('初始化');
    },
    onUploadComplete:function(){
        console.log('上传完成');
    },
    onCancel:function(file){
        console.log(file);
    },
    onUploadSuccess : function(file, data, response) {
        console.log(file);
        console.log(data);
        // console.log(response);

            var obj = JSON.parse(data);
            $('#upload_org_code_img').attr('src',obj.data);
            $('#file_upload_image').attr('value',obj.data);
            $('#upload_org_code_img').show();

    }

});
// $(function() {
//     $("#file_upload_other").uploadify({
//         'swf'             : SCOPE.uploadify_swf,
//         'uploader'        : SCOPE.image_upload,
//         'buttonText'      : '图片上传',
//         'fileTypeDesc' : 'Image Files',
//         'fileObjName'  : 'file',
//         'fileTypeExts' : '*.gif; *.jpg; *.png',
//         'onUploadSuccess' : function(file, data, response) {
//             console.log(file);
//             console.log(data);
//             console.log(response);
//             if (response){
//                 var obj = JSON.parse(data);
//                 $('#upload_org_code_img_other').attr('src',obj.data);
//                 $('#file_upload_image_other').attr('value',obj.data);
//                 $('#upload_org_code_img_other').show();
//             }
//         }
//     });
// });
// $(function() {
$('#upload_other').Huploadify({
    auto:true,
    fileTypeExts:'*.jpg;*.png;',
    multi:true,
    formData:{key:123456,key2:'vvvv'},
    fileSizeLimit:1024,
    showUploadedPercent:true,
    showUploadedSize:true,
    removeTimeout:9999999,
    uploader:SCOPE.image_upload,
    onUploadStart:function(){
        console.log('开始上传');
    },
    onInit:function(){
        console.log('初始化');
    },
    onUploadComplete:function(){
        console.log('上传完成');
    },
    onCancel:function(file){
        console.log(file);
    },
    onUploadSuccess : function(file, data, response) {
        console.log(file);
        console.log(data);
        // console.log(response);

        var obj = JSON.parse(data);
        $('#upload_org_code_img_other').attr('src',obj.data);
        $('#file_upload_image_other').attr('value',obj.data);
        $('#upload_org_code_img_other').show();

    }

});
// });