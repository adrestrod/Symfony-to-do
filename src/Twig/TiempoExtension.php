<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TiempoExtension extends AbstractExtension
{

    const CONFIGURACION = [
        'formato' => 'd/m/Y H:m:s'
    ];

    public function getFilters(): array
    {
        return [
            new TwigFilter('tiempo', [$this, 'formatearTiempo']),
        ];
    }


    public function formatearTiempo($fecha, $configuracion = [])
    {
        $configuracion = array_merge(self::CONFIGURACION, $configuracion);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaFechaSeg = $fechaActual->getTimestamp() - $fecha->getTimestamp();

        if ($diferenciaFechaSeg < 60) {
            $fechaFormateada = 'Creado ahora mismo';
        } elseif($diferenciaFechaSeg < 3600) {
            $fechaFormateada = 'Creado recientemente';
        } elseif($diferenciaFechaSeg < 14400) {
            $fechaFormateada = 'Creado hace unas horas';
        }
        return $fechaFormateada;
    }
}
