<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<style>
    * {
        padding: 0;
        margin: 0;
    }
</style>
<body>
<div>
    <table>
        <tr>
            <td colspan="2">{{ $data['title'] }}</td>
        </tr>
        <tr>
            <td colspan="2">env 配置相关信息</td>
        </tr>
        <tr>
            <td>所使用的文件</td>
            <td>{{ $data['info']['env']['file'] }}</td>
        </tr>
        @foreach($data['info']['env']['app'] as $key=>$value)
            <tr>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($data['info']['env']['log'] as $key=>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        @foreach($data['info']['env']['db'] as $key=>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        @foreach($data['info']['env']['redis'] as $key=>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2">config 配置相关信息</td>
        </tr>
        @foreach($data['info']['config']['app'] as $key=>$value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
