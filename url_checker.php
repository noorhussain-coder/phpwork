<?php
// https://urlhaus.abuse.ch/browse/

// api/virustotal_check_url.php
// POST: url
// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
// }
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
// }
if(isset($_POST['submit'])){
//  header('Content-Type: application/json');

// $url = $_POST['url'] ?? '';
$url = $_POST['url'] ;
if (!$url) { http_response_code(400); echo json_encode(['error'=>'Missing url']); exit; }

// Load API key from env or config (do NOT commit)
// $apiKey = getenv('VT_API_KEY'); // set in server env or use a config file outside webroot
$apiKey = '37076f723d1cf2f35df83c9c653d886154b9c4aa5505567d4c4c47978b6e86aa'; // set in server env or use a config file outside webroot

// 1) Submit URL
$ch = curl_init('https://www.virustotal.com/api/v3/urls');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["x-apikey: {$apiKey}"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $url]));
$response = curl_exec($ch);
echo $response;
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($httpcode != 200 && $httpcode != 201) {
    http_response_code($httpcode ?: 500);
    echo json_encode(['error'=>'VirusTotal API error','details'=>$response]);
    exit;
}

$data = json_decode($response, true);
// API returns data with an analysis id: data['data']['id']
$analysisId = $data['data']['id'] ?? null;
if (!$analysisId) {
    echo json_encode(['error'=>'No analysis id returned','raw'=>$data]); exit;
}

// 2) Retrieve analysis (you might want to poll until status is finished)
$ch2 = curl_init("https://www.virustotal.com/api/v3/analyses/{$analysisId}");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, ["x-apikey: {$apiKey}"]);
$report = curl_exec($ch2);
$reportCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

http_response_code($reportCode ?: 200);
echo '<pre>';
print_r($report);


}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>url checker</h2>
   <form id="vtForm"  action="url_checker.php" method="POST"    >
  <input id="vtUrl" name="url" placeholder="https://example.com">
  <button name="submit" type="submit">Check URL</button>
</form>
</body>
</html>