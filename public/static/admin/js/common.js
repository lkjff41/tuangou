/*页面 全屏-添加*/
function o2o_edit(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

/*添加或者编辑缩小的屏幕*/
function o2o_s_edit(title,url,w,h){
    layer_show(title,url,w,h);
}
/*-删除*/
function o2o_del(id,url){

    layer.confirm('确认要删除吗？',function(index){
        window.location.href=url;
    });
}

$('.sort input').blur(function () {
    var id = $(this).attr('attr-id');
    var sort = $(this).val();
    var postData = {
        'id' : id,
        'sort' : sort,
    };
    var url = SCOPE.sort_url;
    // 抛送http
    $.post(url,postData,function (result) {
        if (result.code==1){
            location.href=result.data
        }else{
            alert(result.msg)
        }
    },"json")

});

// 城市二级内容
$('.cityId').change(function () {
    city_id = $(this).val();
    url = SCOPE.city_url;
    postData = {'id':city_id};
    $.post(url,postData,function (result) {
        if (result.status==1) {
            data=result.data;
            city_html='';
            $(data).each(function (i) {
                city_html += '<option value="'+ this.id +'">' + this.name + '</option>';
            });
            $('.se_city_id').html(city_html);

            } else {
            alert(result.message);
            return;
        }
    },"json")
});

// 分类二级内容
$('.categoryId').change(function () {
    cate_id = $(this).val();
    url = SCOPE.cate_url;
    postData = {'id':cate_id};
    $.post(url,postData,function (result) {
        if (result.status==1) {
            data=result.data;
            cate_html='';
            $(data).each(function (i) {
                cate_html += '<input type="checkbox" name="se_category_id[]" id="checkbox-moban" value="'+ this.id +'">'+this.name;
                cate_html += '<label for="checkbox-moban">&nbsp;</label>';
            });
            $('.se_category_id').html(cate_html);

        } else {
            alert(result.message);
            return;
        }
    },"json")
});

// 地图标注
$('.maptag').click(function () {
    dizhi = $('#dizhi').val();
    url = SCOPE.maptag_url;
    postDate = {'address':dizhi};
    $.post(url,postDate,function (result) {
        if (result.status!=1){
            html='';
            html+='地址错误或者模糊';
            $('#addinfo').css("color","red").html(html);
        }else{
            html='';
            html+='地址正确';
            $('#addinfo').css("color","green").html(html);
        }

    },"json")
});

//失去焦点检测账号有没有用过
$('#username').blur(function () {

    var username = $('#username').val();
    var postData = {
        'username' : username,
    };
    var url = SCOPE.cfname_url;
    // 抛送http
    $.post(url,postData,function (result) {
        if (result.status==1){
            html='';
            html+=result.message;
            $('#cfname').css("color","green").html(html);
        }else{
            html='';
            html+=result.message;
            $('#cfname').css("color","red").html(html);
        }
    },"json")

});

$('#sepassword').blur(function () {
    var password = $('#password').val();
    var sepassword = $('#sepassword').val();
    var postData = {
        'password' : password,
        'sepassword' : sepassword,
    };
    var url = SCOPE.repassword_url;
    // 抛送http
    $.post(url,postData,function (result) {
        if (result.status==1){
            html='';
            html+=result.message;
            $('#cfpassword').css("color","green").html(html);
        }else{
            html='';
            html+=result.message;
            $('#cfpassword').css("color","red").html(html);
        }
    },"json")

});




//失去焦点检查验证码
// $('#captcha').blur(function () {
//
//     var captcha = $('#captcha').val();
//     var postData = {
//         'captcha' : captcha,
//     };
//     var url = SCOPE.checkcaptcha_url;
//     // 抛送http
//     $.post(url,postData,function (result) {
//         if (result.status==1){
//             html='';
//             html+=result.message;
//             $('#checkcaptcha').css("color","green").html(html);
//         }else{
//             html='';
//             html+=result.message;
//             $('#checkcaptcha').css("color","red").html(html);
//         }
//     },"json")

// });

// //搜索ajax
// $('.btn-success').click(function () {
//     var cityid = $('#city_id').val();
//     var cateid = $('#category_id').val();
//     var starttime = $('.start_time').val();
//     var endtime = $('.end_time').val();
//     var name = $('#name').val();
//     var postData = {
//         'cityid' : cityid,
//         'cateid' : cateid,
//         'starttime' : starttime,
//         'endtime' : endtime,
//         'name' : name,
//     };
//     var url = SCOPE.search_url;
//     $.post(url,postData,function (result) {
//         if (result.status==1){
//
//         }
//     },"json")
// });


//时间插件
function selecttime(flag){
    if(flag==1){
        var endTime = $("#countTimeend").val();
        if(endTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }else{
        var startTime = $("#countTimestart").val();
        if(startTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }
}

