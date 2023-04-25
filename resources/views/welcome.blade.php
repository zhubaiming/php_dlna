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
        <tr>
            <td colspan="2">config 配置相关信息</td>
        </tr>
    </table>
</div>
</body>
</html>
