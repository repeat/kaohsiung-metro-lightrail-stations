<?php

$line_files = [
    __DIR__ . '/../ref/line-KRTC.json',
    __DIR__ . '/../ref/line-KLRT.json',
];
$line_sources = [];

foreach ($line_files as $line_file) {
    $line_system = json_decode(file_get_contents($line_file, true), true);
    foreach ($line_system as $line_source) {
        $line_sources[$line_source['LineID']] = $line_source;
    }
}

$station_files = [
    __DIR__ . '/../ref/station-KRTC.json',
    __DIR__ . '/../ref/station-KLRT.json',
];
$station_sources = [];

foreach ($station_files as $station_file) {
    $station_system = json_decode(file_get_contents($station_file, true), true);
    foreach ($station_system as $station_source) {
        $station_sources[$station_source['StationID']] = $station_source;
    }
}

// 整理資料

$geometry = [
    'type' => 'Point'
];
$features = [];

foreach ($station_sources as $station) {
    list(
        'StationUID' => $station_uid,
        'StationID' => $station_id,
        'StationPosition' => $position,
        'StationName' => $station_name,
    ) = $station;

    $address = $station['StationAddress'] ?? '';

    /**
     * Red: R, RK
     * Orange: O, OT
     * Circular: C
     */
    $line_id = $station_id[0];
    $line_name = $line_sources[$line_id]['LineName']['Zh_tw'];

    list(
        'En' => $station_name_en,
        'Zh_tw' => $station_name_tw,
    ) = $station_name;

    list(
        'GeoHash' => $geohash,
        'PositionLat' => $lat,
        'PositionLon' => $lon,
    ) = $position;

    $geometry['coordinates'] = [(float) $lon, (float) $lat];

    $properties = [
        '車站編號' => $station_id,
        '中文站名' => $station_name_tw,
        '英譯站名' => $station_name_en,
        '路線編號' => $line_id,
        '路線名' => $line_name,
        '地址' => $address,
        '緯度' => (float) $lat,
        '經度' => (float) $lon,
    ];

    $feature = [
        'type' => 'Feature',
        'geometry' => $geometry,
        'properties' => $properties,
    ];

    $features[] = $feature;
}

$geojson = [
    'type' => 'FeatureCollection',
    'features' => $features,
];

$handle = fopen(__DIR__ . '/../dist/kaohsiung-metro-lightrail.geojson', 'w+');
fwrite($handle, json_encode($geojson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
fclose($handle);
