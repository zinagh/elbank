<?php

namespace App\Services;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;

class QrcodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($query, $rib)
    {
        $url = 'http://127.0.0.1:8000/EffectuerTransactionqr/';

        $path = dirname(__DIR__, 2).'/public/';

        // set qrcode
        $result = $this->builder
            ->data($url.$query.'/'.$rib)
//            ->data($rib)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
//            ->labelText("Scannez le et Obtenez votre RIB")
//            ->labelAlignment(new LabelAlignmentCenter())
//            ->labelMargin(new Margin(15, 5, 5, 5))
//            ->backgroundColor(new Color(255, 198, 80))

            ->build()
        ;

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile($path.'qr-code/'.$namePng);

        return $result->getDataUri();
    }
}