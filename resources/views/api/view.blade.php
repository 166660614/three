<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
            <td>申请人姓名</td>
            <td>身份证号</td>
            <td>身份证照片</td>
            <td>申请理由</td>
            <td>操作</td>
        </tr>
        @foreach($apply_info as $v)
        <tr apply_id="{{$v['apply_name']}}">
            <td>{{$v['apply_name']}}</td>
            <td>{{$v['apply_idcard']}}</td>
            <td><img src="/../storage/app/{{$v['apply_name']}}" alt=""></td>
            <td>{{$v['apply_use']}}</td>
            <td>
                @if($v['apply_status']==0)
                <a href="/apply/pass/{{$v['apply_id']}}">通过</a>
                <a href="/apply/refuse/{{$v['apply_id']}}">驳回</a>
                @elseif($v['apply_status']==1)
                 已通过
                @else
                    已驳回
                @endif
            </td>
        </tr>
            @endforeach
    </table>
</body>
</html>