<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Installer</title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery');  ?>
    <?php Yii::app()->clientScript->registerScriptFile($this->assets . '/js/install.js', CClientScript::POS_END);  ?>
    <?php Yii::app()->clientScript->registerCssFile( $this->assets . '/css/install.css');  ?>
</head>
<body>
    <?php echo $content;?>
</body>
</html>