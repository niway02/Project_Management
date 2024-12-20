<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    {!! str_replace('{COMPANY_LOGO}', '<img src="' . $logo_url . '" width="200px" alt="" />', $content) !!}
</body>

</html>
