<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <div class="title">
        DOCUMENTO {{ $document->code }}
    </div>

    <div class="box">

        <strong>Asunto:</strong>
        {{ $document->subject }}

        <br><br>

        <strong>Estado:</strong>
        {{ $document->status }}

        <br><br>

        <strong>Contenido:</strong>

        <br>

        {{ $document->content }}

    </div>

</body>

</html>
