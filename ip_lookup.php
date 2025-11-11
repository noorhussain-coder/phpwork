<?php
// ip_lookup.php
// Simple IP Geolocation + ISP lookup using ipinfo.io API

$API_TOKEN2 = "curl https://api.ipinfo.io/lite/8.8.8.8?token=cf2feb5555f627"; // 🔑 Replace with your ipinfo.io token
$API_TOKEN = "cf2feb5555f627"; // 🔑 Replace with your ipinfo.io token
// $API_TOKEN = "YOUR_IPINFO_TOKEN"; // 🔑 Replace with your ipinfo.io token

function get_ip_info($ip, $token) {
    $endpoint = "https://ipinfo.io/{$ip}/json";
    if ($token) $endpoint .= "?token=" . urlencode($token);

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) return ['error' => "cURL error: $err"];
    if ($http != 200) return ['error' => "HTTP $http from ipinfo.io"];

    $data = json_decode($resp, true);
    if (!$data) return ['error' => 'Invalid JSON received.'];

    return $data;
}

$result = null;
$error = null;
$ip = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = trim($_POST['ip'] ?? '');
    if ($ip === '') {
        $error = "Please enter an IP address.";
    } elseif (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $error = "Invalid IP address format.";
    } else {
        $result = get_ip_info($ip, $API_TOKEN);
        if (isset($result['error'])) {
            $error = $result['error'];
            $result = null;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IP Lookup Tool (PHP)</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="./style.css">
<style>
body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f8fafc; color:#111; padding:24px; }
.card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); padding:20px; max-width:750px; margin:0 auto; }
h1 { font-size:20px; margin-bottom:8px; }
input[type="text"] { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:16px; margin-bottom:12px; }
button { padding:10px 18px; background:#2563eb; color:#fff; border:0; border-radius:8px; cursor:pointer; font-size:15px; }
.error { background:#fee2e2; color:#7f1d1d; padding:10px; border-radius:8px; margin-bottom:10px; }
table { width:100%; border-collapse:collapse; margin-top:8px; font-size:15px; }
th, td { text-align:left; padding:8px; border-bottom:1px solid #f1f5f9; }
.muted { color:#64748b; font-size:14px; }
pre { background:#0f172a; color:#e2e8f0; padding:10px; border-radius:8px; overflow:auto; font-size:13px; }
.good { background:#ecfdf5; color:#065f46; padding:6px 10px; border-radius:8px; display:inline-block; }
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
    <h1>🌍 IP Address Lookup Tool</h1>
    <p class="muted">Enter an IP address to get its geolocation, ISP, and other details using <strong>ipinfo.io</strong>.</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="ip" placeholder="e.g. 8.8.8.8" value="<?= htmlspecialchars($ip) ?>" required>
        <button type="submit">Lookup</button>
    </form>

    <?php if ($result): ?>
        <h3>Results for <?= htmlspecialchars($ip) ?>:</h3>
        <table>
            <tr><th>IP</th><td><?= htmlspecialchars($result['ip'] ?? '-') ?></td></tr>
            <tr><th>Hostname</th><td><?= htmlspecialchars($result['hostname'] ?? '-') ?></td></tr>
            <tr><th>City</th><td><?= htmlspecialchars($result['city'] ?? '-') ?></td></tr>
            <tr><th>Region</th><td><?= htmlspecialchars($result['region'] ?? '-') ?></td></tr>
            <tr><th>Country</th><td><?= htmlspecialchars($result['country'] ?? '-') ?></td></tr>
            <tr><th>Organization (ISP)</th><td><?= htmlspecialchars($result['org'] ?? '-') ?></td></tr>
            <tr><th>Location</th><td><?= htmlspecialchars($result['loc'] ?? '-') ?></td></tr>
            <tr><th>Postal</th><td><?= htmlspecialchars($result['postal'] ?? '-') ?></td></tr>
            <tr><th>Timezone</th><td><?= htmlspecialchars($result['timezone'] ?? '-') ?></td></tr>
        </table>

        <?php if (!empty($result['loc'])): 
            [$lat, $lon] = explode(',', $result['loc']);
        ?>
        <h3 style="margin-top:16px;">🗺 Map Preview:</h3>
        <iframe
            width="100%"
            height="300"
            frameborder="0"
            style="border-radius:8px"
            src="https://maps.google.com/maps?q=<?= urlencode($lat) ?>,<?= urlencode($lon) ?>&z=10&output=embed">
        </iframe>
        <?php endif; ?>

        <details style="margin-top:12px;">
            <summary class="muted">Show raw JSON response</summary>
            <pre><?= htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
        </details>
    <?php endif; ?>

    <hr style="margin-top:20px;">
    <p class="muted">Note: Data powered by <a href="https://ipinfo.io" target="_blank">ipinfo.io</a>.  
    Free plan allows limited daily lookups — sign up to get your token.</p>
</div>
</body>
</html>
