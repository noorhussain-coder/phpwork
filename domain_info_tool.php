<?php
// domain_info_tool.php
// WHOIS + DNS + SSL Expiry checker in PHP

// $WHOIS_API_KEY = "YOUR_WHOISXML_API_KEY_HERE"; // 🗝 Replace with your API key
// $WHOIS_API_KEY = "https://user.whoisxmlapi.com/user-service/api-key/generate?currentApiKey=at_sl2MHoZ5OuPqCSCPZkqzZ3DVB0T1u"; // 🗝 Replace with your API key
$WHOIS_API_KEY = "at_sl2MHoZ5OuPqCSCPZkqzZ3DVB0T1u"; // 🗝 Replace with your API key

function get_whois_data($domain, $apiKey) {
    $url = "https://www.whoisxmlapi.com/whoisserver/WhoisService?apiKey={$apiKey}&domainName=" . urlencode($domain) . "&outputFormat=JSON";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) return ['error' => "cURL error: $err"];
    if ($http != 200) return ['error' => "HTTP $http from WHOIS API"];

    $data = json_decode($resp, true);
    if (!$data) return ['error' => 'Invalid JSON received'];
    return $data;
}

function get_ssl_expiry($domain) {
    $orignal_parse = parse_url("https://{$domain}");
    $host = $orignal_parse['host'] ?? $domain;
    $context = stream_context_create(["ssl" => ["capture_peer_cert" => true, "verify_peer" => false, "verify_peer_name" => false]]);
    $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);
    if (!$client) return null;
    $params = stream_context_get_params($client);
    $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
    return date("Y-m-d H:i:s", $cert['validTo_time_t']);
}

function get_dns_records_simple($domain) {
    $records = [];
    $records['A'] = dns_get_record($domain, DNS_A);
    $records['MX'] = dns_get_record($domain, DNS_MX);
    $records['NS'] = dns_get_record($domain, DNS_NS);
    return $records;
}

$result = null;
$error = null;
$domain = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domain = trim($_POST['domain'] ?? '');
    if (!preg_match('/^([a-z0-9-]+\.)+[a-z]{2,}$/i', $domain)) {
        $error = "Please enter a valid domain (e.g. example.com)";
    } else {
        $whois = get_whois_data($domain, $WHOIS_API_KEY);
        if (isset($whois['error'])) {
            $error = $whois['error'];
        } else {
            $dns = get_dns_records_simple($domain);
            $ssl = get_ssl_expiry($domain);
            $result = [
                'whois' => $whois,
                'dns' => $dns,
                'ssl' => $ssl
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Domain Info Tool (WHOIS + DNS + SSL)</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="./style.css">
<style>
body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f8fafc; color:#111; padding:24px; }
.card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); padding:20px; max-width:900px; margin:0 auto; }
h1 { font-size:20px; margin-bottom:8px; }
input[type="text"] { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:16px; margin-bottom:12px; }
button { padding:10px 18px; background:#2563eb; color:#fff; border:0; border-radius:8px; cursor:pointer; font-size:15px; }
.error { background:#fee2e2; color:#7f1d1d; padding:10px; border-radius:8px; margin-bottom:10px; }
table { width:100%; border-collapse:collapse; margin-top:10px; font-size:14px; }
th, td { text-align:left; padding:8px; border-bottom:1px solid #f1f5f9; }
th { background:#f9fafb; }
.muted { color:#64748b; font-size:14px; }
.good { background:#ecfdf5; color:#065f46; padding:6px 10px; border-radius:8px; display:inline-block; }
.bad { background:#fee2e2; color:#991b1b; padding:6px 10px; border-radius:8px; display:inline-block; }
pre { background:#0f172a; color:#e2e8f0; padding:10px; border-radius:8px; overflow:auto; font-size:13px; }
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
    <h1>🌐 Domain Info Tool</h1>
    <p class="muted">Check WHOIS, DNS, and SSL certificate details of any domain using WhoisXML and built-in DNS tools.</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="domain" placeholder="e.g. example.com" value="<?= htmlspecialchars($domain) ?>" required>
        <button type="submit">Check Domain</button>
    </form>

    <?php if ($result): ?>
        <h3>🔎 WHOIS Information</h3>
        <?php
        $whois = $result['whois']['WhoisRecord'] ?? [];
        ?>
        <table>
            <tr><th>Domain</th><td><?= htmlspecialchars($whois['domainName'] ?? '-') ?></td></tr>
            <tr><th>Registrar</th><td><?= htmlspecialchars($whois['registrarName'] ?? '-') ?></td></tr>
            <tr><th>Organization</th><td><?= htmlspecialchars($whois['registryData']['registrant']['organization'] ?? '-') ?></td></tr>
            <tr><th>Creation Date</th><td><?= htmlspecialchars($whois['createdDateNormalized'] ?? '-') ?></td></tr>
            <tr><th>Expiry Date</th><td><?= htmlspecialchars($whois['expiresDateNormalized'] ?? '-') ?></td></tr>
            <tr><th>Status</th><td><?= htmlspecialchars(implode(', ', $whois['status'] ?? [])) ?></td></tr>
        </table>

        <h3>🧩 DNS Records</h3>
        <table>
            <tr><th>Type</th><th>Records</th></tr>
            <?php foreach ($result['dns'] as $type => $records): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($type) ?></strong></td>
                    <td>
                        <?php if (empty($records)): ?>
                            <span class="muted">No records found</span>
                        <?php else: ?>
                            <?php foreach ($records as $rec): ?>
                                <?= htmlspecialchars($rec['target'] ?? $rec['ip'] ?? '-') ?><br>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>🔒 SSL Certificate</h3>
        <?php if ($result['ssl']): ?>
            <p><span class="good">Valid until: <?= htmlspecialchars($result['ssl']) ?></span></p>
        <?php else: ?>
            <p><span class="bad">No SSL certificate found or connection failed.</span></p>
        <?php endif; ?>

        <details style="margin-top:10px;">
            <summary class="muted">Show raw WHOIS JSON</summary>
            <pre><?= htmlspecialchars(json_encode($result['whois'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
        </details>
    <?php endif; ?>

    <hr style="margin-top:20px;">
    <p class="muted">
        Data from <a href="https://whoisxmlapi.com" target="_blank">WhoisXML API</a>.  
        Includes live DNS lookup and SSL expiry scan.
    </p>
</div>
</body>
</html>
