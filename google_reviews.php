<?php
// ===== CONFIG =====
$API_KEY  = "AIzaSyAbWZmK4BMF0nc2Aya9wAFdRHWUeWHa0fg";
$PLACE_ID = "ChIJ6ey7ciCXyzsRvSmL1fKnx8s";

// cache settings
$cacheFile = __DIR__ . '/cache/google_reviews.json';
$cacheTTL  = 60 * 60 * 24; // 24 hours

// ===== LOAD FROM CACHE IF VALID =====
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
    $data = json_decode(file_get_contents($cacheFile), true);
} else {
    $url = "https://maps.googleapis.com/maps/api/place/details/json"
         . "?place_id={$PLACE_ID}"
         . "&fields=name,rating,reviews"
         . "&key={$API_KEY}";

    $response = file_get_contents($url);
    if ($response === false) {
        $data = [];
    } else {
        file_put_contents($cacheFile, $response);
        $data = json_decode($response, true);
    }
}

// ===== EXTRACT DATA =====
$rating  = $data['result']['rating'] ?? null;
$reviews = $data['result']['reviews'] ?? [];
