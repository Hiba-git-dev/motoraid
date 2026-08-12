<?php

namespace App\Services;

use App\Models\Trip;

class GpxService
{
    /**
     * Génère une chaîne XML au format GPX à partir d'un Trip
     */
    public function generateGpx(Trip $trip): string
    {
        $trip->load('routePoints');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<gpx version="1.1" creator="MotoRaid" xmlns="http://www.topografix.com/GPX/1/1">' . PHP_EOL;
        $xml .= '  <metadata><name>' . htmlspecialchars($trip->title) . '</name></metadata>' . PHP_EOL;
        $xml .= '  <trk><name>' . htmlspecialchars($trip->title) . '</name><trkseg>' . PHP_EOL;

        foreach ($trip->routePoints as $point) {
            $xml .= '    <trkpt lat="' . $point->latitude . '" lon="' . $point->longitude . '">';
            if ($point->elevation) {
                $xml .= '<ele>' . $point->elevation . '</ele>';
            }
            $xml .= '</trkpt>' . PHP_EOL;
        }

        $xml .= '  </trkseg></trk>' . PHP_EOL;
        $xml .= '</gpx>';

        return $xml;
    }
}