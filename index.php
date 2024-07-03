<?php
require_once 'vendor/autoload.php';

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivo CSV o TXT</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cargar Archivo CSV o TXT</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="archivoCSV">Seleccione el archivo CSV o TXT:</label>
                <input type="file" class="form-control-file" id="archivoCSV" name="archivoCSV" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Archivo</button>
        </form>

        <div class="mt-5">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivoCSV'])) {
                $archivo = $_FILES['archivoCSV'];
                $tipoArchivo = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

                if ($tipoArchivo != 'csv' && $tipoArchivo != 'txt') {
                    echo '<div class="alert alert-danger" role="alert">Error: El archivo debe ser un CSV o TXT.</div>';
                } else {
                    $csvData = file_get_contents($archivo['tmp_name']);

                    if (substr($csvData, 0, 3) == "\xEF\xBB\xBF") {
                        $csvData = substr($csvData, 3);
                    }

                    $rows = array_map(function ($row) {
                        return str_getcsv($row, ';');
                    }, explode("\n", trim($csvData)));

                    array_shift($rows);

                    foreach ($rows as $index => $row) {
                        if (!empty($row[0]) && !empty($row[1]) && !empty($row[2])) {
                            $Aprendiz = $row[0];
                            $Documento = $row[1];
                            $Correo = $row[2];
                            $carnetId = "carnet" . ($index + 1);

                            // Genera el código QR
                            $renderer = new ImageRenderer(
                                new RendererStyle(300),
                                new ImagickImageBackEnd()
                            );
                            $writer = new Writer($renderer);
                            $qrCode = $writer->writeString($Documento);

                            $dataUri = 'data:image/png;base64,' . base64_encode($qrCode);

                            echo "<div class='carnet d-flex justify-content-center align-items-center' style='height: 100vh;'>
                                    <div class='card' id='$carnetId' style='width: 28rem; height: 36rem; position: relative;'>
                                        <img src='s2.jpg' class='card-img-top img-fluid' alt='Imagen' style='object-fit: cover; width: 100%; height: 100%; min-height: 100%;'>
                                        <div class='card-body d-flex flex-column justify-content-center align-items-center' style='position: absolute; top: 36%; left: 50%; transform: translate(-50%, -50%);'>
                                            <img class='img-fluid' src='user.png' alt='' style='width: 180px;'>
                                            <h5 style='margin-top: 20px; text-align: center;'><span>$Aprendiz</span></h5>
                                            <p style='text-align: center;'><span>$Documento</span></p>
                                        </div>
                                        <img class='img-fluid' src='L.svg' alt='' style='position: absolute; top: -50px; left: -40px; width: 190px;'>
                                        <div style='position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); padding: 10px; background-color:  rgba(183, 249, 206, 0.3);'>
                                            <img class='img-fluid' src='$dataUri' alt='' style='width: 120px; height: 120px;'>
                                        </div>
                                        <form action='enviarCarnet.php' method='post'>
                                            <input type='hidden' name='documento' value='$Documento'>
                                            <input type='hidden' name='aprendiz' value='$Aprendiz'>
                                              <input type='hidden' name='Correo' value='$Correo'>
                                            <input type='hidden' name='dataUri' value='$dataUri'>
                                            <button type='submit' class='btn btn-success' style='position: absolute; bottom: 10px; right: 10px;'>Enviar Carnet</button>
                                        </form>
                                    </div>
                                </div>";
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
