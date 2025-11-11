<?php
// ssl_checker.php
// SSL Certificate Checker using SSL Labs API (https://www.ssllabs.com/ssltest/)
// Save as ssl_checker.php on your PHP-enabled webserver.

define('MAX_POLLS', 20);      // how many times to poll before giving up
define('POLL_DELAY', 4);      // seconds between polls

function ssllabs_request($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // set a friendly user agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP SSL Checker (ssl_checker.php)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $code, 'body' => $resp, 'error' => $err];
}

$errors = [];
$result = null;
$domain = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');
    if ($domain === '') {
        $errors[] = 'Please enter a domain (example.com).';
    } else {
        // Normalize domain (remove scheme, path)
        $domain = preg_replace('#^https?://#i', '', $domain);
        $domain = trim(explode('/', $domain)[0]);

        if (!preg_match('/^([a-z0-9-]+\.)+[a-z]{2,}$/i', $domain)) {
            $errors[] = 'Invalid domain format. Example: example.com';
        } else {
            // Kick off an analysis request.
            // recommended options: publish=off (don't publish results), all=done (return details), fromCache=off to force new run (optional)
            // We'll request fromCache=on so if there is a cached result we get it quickly
            $base = 'https://api.ssllabs.com/api/v3/analyze';
            $params = http_build_query([
                'host' => $domain,
                'publish' => 'off',
                'all' => 'done',
                'fromCache' => 'on', // try cached first, avoids hitting rate limits
                'maxAge' => 24*60*60 // prefer results no older than 1 day
            ]);
            $analyzeUrl = $base . '?' . $params;

            $start = ssllabs_request($analyzeUrl);
            if ($start['error']) {
                $errors[] = 'Request error: ' . $start['error'];
            } else {
                $body = json_decode($start['body'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors[] = 'Invalid JSON returned by SSL Labs.';
                } else {
                    // The API returns a status field: DNS, IN_PROGRESS, READY, ERROR
                    $status = $body['status'] ?? null;
                    // If status is READY, we have results immediately
                    if ($status === 'READY') {
                        $result = $body;
                    } elseif ($status === 'ERROR') {
                        $errors[] = 'SSL Labs returned status ERROR. Message: ' . ($body['statusMessage'] ?? 'none');
                        // still present raw body to debug
                        $result = $body;
                    } else {
                        // Need to poll
                        $attempt = 0;
                        $final = null;
                        while ($attempt < MAX_POLLS) {
                            $attempt++;
                            // Wait a bit before polling (first wait shorter)
                            sleep(POLL_DELAY);
                            $pollResp = ssllabs_request($analyzeUrl);
                            if ($pollResp['error']) {
                                $errors[] = 'Polling error: ' . $pollResp['error'];
                                break;
                            }
                            $pollBody = json_decode($pollResp['body'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $errors[] = 'Invalid JSON while polling SSL Labs.';
                                break;
                            }
                            $status = $pollBody['status'] ?? null;
                            if ($status === 'READY') {
                                $final = $pollBody;
                                break;
                            } elseif ($status === 'ERROR') {
                                $errors[] = 'SSL Labs analysis finished with ERROR: ' . ($pollBody['statusMessage'] ?? 'no message');
                                $final = $pollBody;
                                break;
                            }
                            // else continue polling
                        }
                        if ($final !== null) {
                            $result = $final;
                        } else {
                            // timed out
                            $errors[] = 'Analysis did not complete within the polling limit. Try again later (or increase MAX_POLLS).';
                            // include last polled body if any
                            $result = $pollBody ?? null;
                        }
                    }
                }
            }
        }
    }
}
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>SSL Certificate Checker — SSL Labs (PHP)</title>
  <link rel="stylesheet" href="./style.css">
<style>
  body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f5f7fb; color:#111; padding:24px; }
  .card { background:#fff; max-width:900px; margin:0 auto; padding:20px; border-radius:12px; box-shadow:0 8px 26px rgba(11,15,30,0.06);}
  h1 { margin:0 0 10px; font-size:20px; }
  .muted { color:#6b7280; font-size:14px; }
  form { display:flex; gap:10px; margin-bottom:12px; align-items:center; }
  input[type=text] { flex:1; padding:10px 12px; border-radius:8px; border:1px solid #e6eef8; font-size:15px; }
  button { padding:10px 16px; border-radius:8px; border:0; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; }
  .errors { background:#fff0f6; color:#6b021f; padding:10px; border-radius:8px; margin-bottom:12px; }
  .stat { display:inline-block; margin-right:12px; padding:6px 10px; border-radius:999px; background:#eef2ff; color:#1e3a8a; font-weight:700; }
  .good { background:#ecfdf5; color:#065f46; }
  .bad { background:#fff1f2; color:#9f1239; }
  table { width:100%; border-collapse:collapse; margin-top:12px; font-size:14px; }
  th, td { text-align:left; padding:8px; border-bottom:1px solid #f1f5f9; }
  pre { background:#0b1220; color:#e6eef8; padding:12px; border-radius:8px; overflow:auto; font-size:13px; }
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
  <h1>🔒 SSL Certificate Checker — SSL Labs</h1>
  <p class="muted">Enter a domain (e.g. example.com). This tool uses the public SSL Labs API and polls until analysis is ready.</p>

  <?php if (!empty($errors)): ?>
    <div class="errors"><strong>Messages:</strong>
      <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="text" name="domain" placeholder="example.com" value="<?= htmlspecialchars($domain) ?>" required />
    <button type="submit">Check SSL</button>
  </form>

  <?php if ($result !== null): ?>
    <?php if (isset($result['status']) && $result['status'] === 'ERROR'): ?>
      <div class="errors"><strong>SSL Labs reported error:</strong> <?= htmlspecialchars($result['statusMessage'] ?? 'Unknown') ?></div>
    <?php endif; ?>

    <?php
      // SSL Labs returns either an object describing the host or an array — depending on API
      // The API returns a host object (with endpoints array). If the top-level is an array, take first element.
      $hostData = $result;
      if (is_array($result) && array_values($result) === $result && count($result) > 0) {
          // numeric-indexed array: take first
          $hostData = $result[0];
      }
      $endpoints = $hostData['endpoints'] ?? [];
    ?>

    <h3>Analysis status: <?= htmlspecialchars($hostData['status'] ?? 'unknown') ?></h3>

    <?php if (empty($endpoints)): ?>
      <p class="small">No endpoints returned. The host may not resolve or analysis failed. See raw JSON for details.</p>
    <?php else: ?>
      <h3>Endpoint Results</h3>
      <?php foreach ($endpoints as $ep): 
          $ip = $ep['ipAddress'] ?? '-';
          $grade = $ep['grade'] ?? '-';
          $serverName = $ep['serverName'] ?? ($ep['ipAddress'] ?? '-');
          $details = $ep['details'] ?? [];
          // certificate info is under details.cert
          $cert = $details['cert'] ?? [];
          $notBefore = $cert['notBefore'] ?? null;
          $notAfter = $cert['notAfter'] ?? null;
          $subject = $cert['subject'] ?? null;
          $issuerLabel = $cert['issuerLabel'] ?? null;
          // convert unix timestamps if present (SSL Labs sometimes gives epoch ms)
          if (is_numeric($notAfter)) {
              // SSL Labs tends to give integer unix seconds
              $expStr = date('Y-m-d H:i:s', (int)$notAfter);
          } else {
              $expStr = $notAfter ? htmlspecialchars($notAfter) : '-';
          }
      ?>
        <div style="margin-top:10px; padding:12px; border-radius:10px; background:#fbfdff; border:1px solid #eef6ff;">
          <strong>Server:</strong> <?= htmlspecialchars($serverName) ?> &nbsp;
          <span class="stat <?= ($grade === 'A' || $grade === 'A+' || $grade === 'A-') ? 'good' : ($grade === '-' ? '' : 'bad') ?>">Grade: <?= htmlspecialchars($grade) ?></span>
          <div style="margin-top:8px">
            <table>
              <tr><th>IP</th><td><?= htmlspecialchars($ip) ?></td></tr>
              <tr><th>Certificate Subject</th><td><?= htmlspecialchars($subject ?? '-') ?></td></tr>
              <tr><th>Issuer</th><td><?= htmlspecialchars($issuerLabel ?? ($cert['issuer'] ?? '-')) ?></td></tr>
              <tr><th>Valid From</th><td><?= $cert && !empty($cert['notBefore']) && is_numeric($cert['notBefore']) ? date('Y-m-d H:i:s',(int)$cert['notBefore']) : htmlspecialchars($cert['notBefore'] ?? '-') ?></td></tr>
              <tr><th>Valid Until</th><td><?= $expStr ?></td></tr>
              <tr><th>OCSP</th><td><?= htmlspecialchars($details['ocsp']['status'] ?? ($details['ocspResponse'] ?? '-')) ?></td></tr>
              <tr><th>HTTP/2</th><td><?= htmlspecialchars($ep['details']['http2'] ?? '-') ?></td></tr>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <details style="margin-top:12px">
      <summary class="small">Show raw SSL Labs JSON</summary>
      <pre><?= htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
    </details>
  <?php endif; ?>

  <hr style="margin-top:18px"/>
  <p class="small">Notes: This page uses the public SSL Labs API (https://api.ssllabs.com). Results may be cached by SSL Labs; to force a new scan set fromCache=off in the script (be mindful of rate limits). Increase MAX_POLLS or POLL_DELAY if analyses commonly take longer for your use.</p>
</div>
</body>
</html>
