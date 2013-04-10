<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $message ?></title>
<style type="text/css">
body, div, h1, h2, p, pre {
    margin: 0;
    padding: 0;
}
body {
    font-size: 12px;
    color: #363636;
}
body, pre, code {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif, "\5fae\8f6f\96c5\9ed1", "\5b8b\4f53";
}
h1 {
    font-size: 36px;
    font-weight: bold;
}
h2 {
    font-size: 20px;
    margin: 20px 0 0;
}
pre {
    line-height: 18px;
}

.error-text {
    color: #b94a48;
}
.error-container {
    padding: 15px 20px 20px 20px;
    clear: both;
}
</style>
<!--<link href="http://php/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">-->
</head>
<body>
<div class="error-container">
    <h1><?php echo $message ?></h1>
    <?php if ($debug) : ?>
    <h2>Trace</h2>
    <p class="error-text"><?php echo $stackInfo ?></p>
    <p>
        <pre><code><?php echo $trace ?></code></pre>
    </p>
    <h2>File</h2>
    <p class="error-text"><?php echo $file ?> modified at <?php echo $mtime ?></p>
    <p>
        <pre><?php echo $fileInfo ?></pre>
    </p>
    <?php else : ?>
    <p>Unfortunately, an error occurred. Please try again later.</p>
    <?php endif ?>
</div>
</body>
</html>