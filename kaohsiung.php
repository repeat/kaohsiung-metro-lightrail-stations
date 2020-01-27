<?php
$area_name = 'kaohsiung';

// use wikipedia colors
$colors = [
    'R' => '#e20b65',
    'O' => '#faa73f',
];

$lines = file($area_name . '.csv');
foreach ($lines as $line) {
    $stations[] = str_getcsv($line);
}

// remove column name
unset($stations[0]);

$geometry = [
    'type' => 'Point'
];

foreach ($stations as $station) {
    list($id, $name, $line, $zipcode, $address, $lat, $lon, $future) = $station;
    $line_code = $id[0];

    $geometry['coordinates'] = [(float) $lon, (float) $lat];
    $properties = [
        '編號' => (string) $id,
        '站名' => $name,
        '線名' => $line,
        '郵遞區號' => (int) $zipcode,
        '地址' => $address,
        '緯度' => (float) $lat,
        '經度' => (float) $lon,
        // https://help.github.com/en/github/managing-files-in-a-repository/mapping-geojson-files-on-github#styling-features
        'marker-size' => 'medium',
        'marker-symbol' => 'rail-metro',
        'marker-color' => $colors[$line_code],
    ];

    $feature = [
        'type' => 'Feature',
        'geometry' => $geometry,
        'properties' => $properties
    ];

    $features[] = $feature;
}

$geojson = [
    'type' => 'FeatureCollection',
    'features' => $features
];

$handle = fopen($area_name . '.geojson', 'w+');
fwrite($handle, json_encode($geojson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
fclose($handle);
