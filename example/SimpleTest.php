<?php
require '../vendor/autoload.php';

use Acme\SimpleTest;

$test = new SimpleTest();
$file = $_REQUEST['filetovalidate'];
$validate = $test->process($file, 'Excel2007');
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Avana Test #3 :</title>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>

    <body>

        <div class='container'>
            <h1>Avana Test #3</h1>
            <div class='panel panel-default'>
                <div class='panel-heading'>File Validation Option</div>
                <div class='panel-body'>
                    <form method="get" action=''>
                        <label class="radio-inline">
                            <input type='radio' name='filetovalidate' value='Type_A.xlsx' />
                            Type_A.xlsx</label>

                        <label class="radio-inline">
                            <input type='radio' name='filetovalidate' value='Type_B.xlsx' />
                            Type_B.xlsx</label>

                        <label class="radio-inline">
                            <input type='radio' name='filetovalidate' value='Type_C.php' />
                            Type_C.php</label>

                        <button type='submit' class="btn btn-primary'">Validate</button>
                    </form>
                </div>
            </div>

            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Row</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($validate as $k => $msg): ?>
                        <tr>
                            <td><?php echo ($k + 1); ?></td>
                            <td><?php echo $msg; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script
            src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
            integrity="sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc="
        crossorigin="anonymous"></script>
        <script>
            $(function () {
                var current_file = '<?php echo $_REQUEST['filetovalidate']; ?>';
                $('input:radio[name="filetovalidate"]').filter('[value="'+current_file+'"]').attr('checked', true);
            });
        </script>
    </body>
</html>