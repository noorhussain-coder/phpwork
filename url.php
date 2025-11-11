<?php
// vt_url_scanner.php
// Put this file on your PHP webserver. Requires PHP + cURL.
// Set your VirusTotal API key below:
$VT_API_KEY = '37076f723d1cf2f35df83c9c653d886154b9c4aa5505567d4c4c47978b6e86aa';
$apiKey = '37076f723d1cf2f35df83c9c653d886154b9c4aa5505567d4c4c47978b6e86aa';

// Helper: validate URL
function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// Helper: base64url encode (no padding) as VirusTotal expects for /urls/{id}
function base64url_encode_no_padding($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Helper: simple cURL wrapper
function vt_request($method, $endpoint, $api_key, $postFields = null) {
    $ch = curl_init($endpoint);
    $headers = [
        "x-apikey: $api_key",
        "Accept: application/json"
    ];
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if (strtoupper($method) === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($postFields !== null) {
            // If associative array -> form-encoded; if string assume JSON
            if (is_array($postFields)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
        }
    }
    $resp = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return ['code' => $http_code, 'body' => $resp, 'error' => $err];
}

// UI / Handler
$errors = [];
$result = null;
$submitted_url = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_url = trim($_POST['url'] ?? '');
    if ($submitted_url === '') {
        $errors[] = 'Please enter a URL.';
    } elseif (!is_valid_url($submitted_url)) {
        $errors[] = 'Invalid URL format.';
    } else {
        // Compute URL id (base64url without padding)
        $url_id = base64url_encode_no_padding($submitted_url);

        // 1) Try to GET existing report: https://www.virustotal.com/api/v3/urls/{id}
        $get_endpoint = "https://www.virustotal.com/api/v3/urls/{$url_id}";
        $get_resp = vt_request('GET', $get_endpoint, $VT_API_KEY);

        if ($get_resp['code'] === 200) {
            $data = json_decode($get_resp['body'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // The report is usually in data.attributes.last_analysis_stats and last_analysis_results
                $attrs = $data['data']['attributes'] ?? null;
                $result = [
                    'found' => true,
                    'attrs' => $attrs,
                    'raw' => $data
                ];
            } else {
                $errors[] = 'Failed to parse VirusTotal response.';
            }
        } elseif ($get_resp['code'] === 404) {
            // No existing report — submit the URL for analysis
            $post_endpoint = "https://www.virustotal.com/api/v3/urls";
            $post_resp = vt_request('POST', $post_endpoint, $VT_API_KEY, ['url' => $submitted_url]);

            if ($post_resp['code'] === 200 || $post_resp['code'] === 201) {
                $post_data = json_decode($post_resp['body'], true);
                $result = [
                    'found' => false,
                    'submitted' => true,
                    'message' => 'No previous report found. URL was submitted to VirusTotal for analysis. Click Scan again to fetch results after analysis completes.',
                    'post_response' => $post_data
                ];
            } else {
                $errors[] = "Unable to submit URL for analysis. HTTP {$post_resp['code']}.";
                if ($post_resp['error']) $errors[] = "cURL error: {$post_resp['error']}";
                if ($post_resp['body']) $errors[] = "Response: {$post_resp['body']}";
            }
        } else {
            $errors[] = "VirusTotal request failed (HTTP {$get_resp['code']}).";
            if ($get_resp['error']) $errors[] = "cURL error: {$get_resp['error']}";
            if ($get_resp['body']) $errors[] = "Response: {$get_resp['body']}";
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>URL Safety Scanner — VirusTotal (PHP)</title>
  <link rel="stylesheet" href="./style.css">
<style>
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f5f7fb; color:#111; padding:24px; }
    .card { background:#fff; border-radius:12px; box-shadow: 0 6px 18px rgba(15,20,30,0.08); padding:20px; max-width:900px; margin:0 auto; }
    h1 { margin:0 0 12px 0; font-size:20px; }
    form { display:flex; gap:8px; margin-bottom:12px; }
    input[type="url"] { flex:1; padding:10px 12px; border-radius:8px; border:1px solid #dfe6ee; font-size:15px; }
    button { padding:10px 16px; border-radius:8px; border:0; background:#2563eb; color:white; font-weight:600; cursor:pointer; }
    .muted { color:#6b7280; font-size:13px; }
    pre { background:#0f1724; color:#e6eef8; padding:12px; border-radius:8px; overflow:auto; font-size:13px; }
    .stat { display:inline-block; margin-right:12px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:700; }
    .bad { background:#ffe9e9; color:#991b1b; }
    .good { background:#ecfdf5; color:#065f46; }
    .warn { background:#fff7ed; color:#92400e; }
    .errors { background:#fff0f6; color:#6b021f; padding:10px; border-radius:8px; margin-bottom:12px; }
    table { width:100%; border-collapse:collapse; margin-top:8px; font-size:14px; }
    th, td { text-align:left; padding:8px; border-bottom:1px solid #f1f5f9; }
    .small { font-size:13px; color:#475569; }
</style>
</head>
<body>

  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="#home">
        <div class="logo">CS</div>
        <div class="brand-text">
          <h1>Cyber Security Awareness Portal </h1>
          <p class="muted">Awareness Portal</p>

        </div>
      </a>
      <nav class="main-nav">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="#tools">Tools</a></li>
          <li><a href="#quiz">Quizzes</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>
<div class="card">
    <h2><a href="index.php">home</a></h2>
    <h1>URL Safety Scanner — VirusTotal</h1>
    <p class="muted">Enter a URL to check VirusTotal's verdict. Requires your VirusTotal API key set in the PHP file.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <strong>Errors:</strong>
            <ul>
            <?php foreach ($errors as $e): ?>
                <li><?=htmlspecialchars($e)?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="url" name="url" placeholder="https://example.com" value="<?=htmlspecialchars($submitted_url)?>" required />
        <button type="submit">Scan</button>
    </form>

    <?php if ($result !== null): ?>
        <?php if (!empty($result['found']) && $result['found'] === true): 
            $attrs = $result['attrs'];
            $stats = $attrs['last_analysis_stats'] ?? [];
            $results = $attrs['last_analysis_results'] ?? [];
        ?>
            <div>
                <div class="small">Scan results for: <strong><?=htmlspecialchars($submitted_url)?></strong></div>
                <div style="margin-top:10px;">
                    <span class="stat good">Harmless: <?=($stats['harmless'] ?? 0)?></span>
                    <span class="stat warn">Suspicious: <?=($stats['suspicious'] ?? 0)?></span>
                    <span class="stat bad">Malicious: <?=($stats['malicious'] ?? 0)?></span>
                    <span class="stat">Undetected: <?=($stats['undetected'] ?? 0)?></span>
                </div>

                <h3 style="margin-top:16px;margin-bottom:8px;">Engines that flagged this URL</h3>
                <?php
                    // collect only engines with category != harmless or with malicious score
                    $positives = [];
                    foreach ($results as $engine => $engData) {
                        $category = $engData['category'] ?? '';
                        if ($category !== 'harmless') {
                            $positives[$engine] = $engData;
                        }
                    }
                ?>
                <?php if (count($positives) === 0): ?>
                    <div class="small">No engines flagged this URL (or no recent detections).</div>
                <?php else: ?>
                    <table>
                        <thead><tr><th>Engine</th><th>Category</th><th>Result</th></tr></thead>
                        <tbody>
                        <?php foreach ($positives as $engine => $eng): ?>
                            <tr>
                                <td><?=htmlspecialchars($engine)?></td>
                                <td><?=htmlspecialchars($eng['category'] ?? '-') ?></td>
                                <td><?=htmlspecialchars($eng['result'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <details style="margin-top:12px;">
                    <summary class="small">Raw response (VirusTotal)</summary>
                    <pre><?=htmlspecialchars(json_encode($result['raw'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))?></pre>
                </details>
            </div>

        <?php elseif (!empty($result['submitted'])): ?>
            <div>
                <div class="small"><strong>Submitted for analysis</strong></div>
                <div class="muted" style="margin-top:8px;"><?=htmlspecialchars($result['message'])?></div>
                <details style="margin-top:12px;">
                    <summary class="small">Submission response</summary>
                    <pre><?=htmlspecialchars(json_encode($result['post_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))?></pre>
                </details>
            </div>
        <?php else: ?>
            <div class="small">No result to show.</div>
        <?php endif; ?>
    <?php endif; ?>

    <hr style="margin-top:18px;margin-bottom:12px;"/>
    <div class="small">Notes: This script uses VirusTotal v3 endpoints. Make sure your API key has the required permissions and you respect rate limits. The page tries to retrieve an existing report first; if none is found it will submit the URL for analysis and show the submission response.</div>
</div>
</body>
</html>
