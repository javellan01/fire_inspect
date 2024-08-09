<?php
//header("Location: login.php"); 
//exit();
/** 
 *
 * @created      13.07.2023
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2023 smiley
 * @license      MIT
 */

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require("./DB/conn.php");
require_once 'vendor/autoload.php';

$options = new QROptions;

$options->outputType          = QROutputInterface::MARKUP_SVG;
$options->outputBase64        = true;
$options->svgAddXmlHeader     = false;
$options->connectPaths        = true;
$options->drawCircularModules = false;
$options->drawLightModules    = true;
$options->addLogoSpace        = false;
$options->eccLevel            = EccLevel::M;
$options->logoSpaceWidth      = 11;
$options->moduleValues        = [
	// finder
	QRMatrix::M_FINDER_DARK      => '#A02128', // dark (true)
	QRMatrix::M_FINDER           => '#fff', // light (false)
	QRMatrix::M_FINDER_DOT       => '#154889',
	QRMatrix::M_FINDER_DOT_LIGHT => '#fff',
	// alignment
	QRMatrix::M_ALIGNMENT_DARK   => '#A02128',
	QRMatrix::M_ALIGNMENT        => '#fff',
	// timing
	QRMatrix::M_TIMING_DARK      => '#154889',
	QRMatrix::M_TIMING           => '#fff',
	// format
	QRMatrix::M_FORMAT_DARK      => '#154889',
	QRMatrix::M_FORMAT           => '#fff',
	// version
	QRMatrix::M_VERSION_DARK     => '#154889',
	QRMatrix::M_VERSION          => '#fff',
	// data
	QRMatrix::M_DATA_DARK        => '#154889',
	QRMatrix::M_DATA             => '#fff',
	// darkmodule
	QRMatrix::M_DARKMODULE_LIGHT => '#fff',
	QRMatrix::M_DARKMODULE       => '#154889',
	// separator
	QRMatrix::M_SEPARATOR_DARK   => '#A02128',
	QRMatrix::M_SEPARATOR        => '#fff',
	// quietzone
	QRMatrix::M_QUIETZONE_DARK   => '#154889',
	QRMatrix::M_QUIETZONE        => '#fff',
	// logo space
	QRMatrix::M_LOGO_DARK        => '#A02128',
	QRMatrix::M_LOGO             => '#fff',
];

function loadUuid($conn){

    $stmt = $conn->prepare("SELECT id_serie, tx_tipo, tx_capacidade, bool_carreta, uuid,
                             cs_estado FROM extintores");
    $stmt->execute();   
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $quantity = count($data);
    $url="https://bombeiros.firesystems-am.com.br/qrExtintor.php?q=";
    for( $i = 0; $i < $quantity; $i++){
        switch($data[$i]['tx_tipo']){
            case "AP": 
                $data[$i]['tx_tipo'] = 'Água Pressurizada';
                $data[$i]['cs_checkbox'] = 0;
                break;
            case "ESP. MEC.": 
                $data[$i]['tx_tipo'] = 'Espuma Mecânica';
                $data[$i]['cs_checkbox'] = 1;
                break;
            case "ABC": 
                $data[$i]['tx_tipo'] = 'Pó Químico ABC';
                $data[$i]['cs_checkbox'] = 2;
                break;
            case "BC": 
                $data[$i]['tx_tipo'] = 'Pó Químico BC';
                $data[$i]['cs_checkbox'] = 3;
                break;
            case "Co2": 
                $data[$i]['tx_tipo'] = 'CO2';
                $data[$i]['cs_checkbox'] = 4;
                break;
        }
    
        switch($data[$i]['bool_carreta']){
            case 1: 
                $data[$i]['bool_carreta'] = 'Carreta ';
                break;
            default: 
                $data[$i]['bool_carreta'] = 'Extintor Portátil ';
                break;
        }

        $data[$i]['uuid'] = $url.$data[$i]['uuid'];
    }

    return $data;

}

$extintores = loadUuid($conn);
$quantity = count($extintores);

echo "FireSystems database foram encontrados: ".$quantity." extintores.<br>";

$svg_buffer = [];

for( $index = 0; $index < $quantity; $index++){

    $qrcode = (new QRCode($options))->addByteSegment($extintores[$index]['uuid']);
    $matrix = $qrcode->getQRMatrix();

    $out_normal   = $qrcode->renderMatrix($matrix);

    $svg_buffer[$index] = $out_normal;
}
// dump the output
header('Content-type: text/html');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>FireSystems QRCodes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100..700;1,100..700&amp;display=swap" rel="stylesheet">
	<style>
        #refl{
			width: 250px;
			margin: 0.3rem auto;
		}
        .basic-grid {
            margin: auto;
            max-width: 1200px;
            display: grid;
            gap: 0.3rem;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
         }
         .qr-area{
            font-family: Fira Sans;
            font-weight: 400; 
            margin: auto;
            padding: 10px;
            width: 75%;
            border: solid 3px #154889; 
            border-radius: 20px;
            
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80%;
            }
	</style>
</head>
<body>
    <div class='basic-grid'>
    <?php for( $index = 0; $index < 20; $index++){
        echo '<div id="refl" class="qr-area"><span>';
        echo $extintores[$index]['bool_carreta'].$extintores[$index]['tx_tipo']." : ".$extintores[$index]['uuid'];
        echo '</span><img src="'.$svg_buffer[$index].'"/><span><img src="https://firesystems-am.com.br/wp-content/uploads/2020/06/FIRE-LOGO.png" alt="FIRE-AM" width="202" height="68"></img></span></div>';
    }
        ?>
        
    </div>
</body>
</html>