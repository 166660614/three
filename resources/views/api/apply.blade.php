<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{URL::asset('/css/app.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{URL::asset('/css/bootstrap.min.css')}}">
</head>
<body>
<form enctype="multipart/form-data" action="/apply/do" method="post">
    <div class="form-group">
        <label for="exampleInputName">姓名</label>
        <input type="text" class="form-control" name="api_name" placeholder="输入姓名">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail">身份证号</label>
        <input type="text" class="form-control" name="api_idcard" placeholder="输入身份证号">
    </div>
    <div class="form-group">
        <label for="exampleInputFile">身份证照片</label>
        <input type="file" name="api_picture">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">接口用途</label>
        <textarea class="form-control" rows="8" name="api_use"></textarea>
    </div>
    <input type="submit" value="申请">
</form>
</body>
{{--
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="{{URL::asset('/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
<script type="text/javascript">
    $(function(){
        $('#apply').click(function (e) {
            e.preventDefault();
            var api_name=$('#api_name').val();
            var api_idcard=$('#api_idcard').val();
            var api_picture=$('#api_picture');
            var api_use=$('#api_use').val();
            alert(api_use)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'/apply/do',
                type:'post',
                data:{api_name:api_name,api_idcard:api_idcard,api_picture:api_picture,api_use:api_use},
                dataType:'json',
                success:function (res) {
                   alert(res)
                }
            })
        })
    })
</script>--}}
