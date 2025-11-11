<?php
// Free Email Breach Checker using BreachDirectory public API
function breachdirectory_check($email) {
    $endpoint = "https://breachdirectory.org/api?func=auto&term=" . urlencode($email);

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Set a User-Agent to avoid 403
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Email Breach Checker');
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) return ['error' => "cURL error: $err"];
    if ($http !== 200) return ['error' => "HTTP $http: $resp"];

    // If response contains "No results", email is safe
    if (stripos($resp, 'No results') !== false) {
        return ['found' => false];
    }

    // Otherwise, return raw response in 'description'
    return ['found' => true, 'breaches' => [['description' => trim($resp)]]];
}

// Init
$result = null;
$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $check = breachdirectory_check($email);
        if (isset($check['error'])) {
            $error = $check['error'];
        } else {
            $result = $check;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Email Breach Detector (Free)</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="./style.css">
<style>
body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#f8fafc; color:#111; padding:24px; }
.card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); padding:20px; max-width:800px; margin:0 auto; }
h1 { font-size:20px; margin-bottom:8px; }
input[type="email"] { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:16px; margin-bottom:12px; }
button { padding:10px 18px; background:#2563eb; color:#fff; border:0; border-radius:8px; cursor:pointer; font-size:15px; }
.error { background:#fee2e2; color:#7f1d1d; padding:10px; border-radius:8px; margin-bottom:10px; }
.safe { background:#ecfdf5; color:#065f46; padding:8px 10px; border-radius:8px; display:inline-block; }
.bad { background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; display:inline-block; }
pre { background:#0f172a; color:#e2e8f0; padding:10px; border-radius:8px; overflow:auto; font-size:13px; }
.muted { color:#64748b; font-size:14px; }
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
<h1>📧 Email Breach Detector (Free)</h1>
<p class="muted">Check whether your email appears in public breach databases using <strong>BreachDirectory</strong>.</p>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="e.g. user@example.com" value="<?= htmlspecialchars($email) ?>" required>
    <button type="submit">Check Breaches</button>
</form>

<?php if ($result): ?>
    <?php if ($result['found'] === false): ?>
        <p><span class="safe">✅ No breaches found for <?= htmlspecialchars($email) ?>.</span></p>
    <?php elseif ($result['found'] === true): ?>
        <p><span class="bad">⚠️ Email <?= htmlspecialchars($email) ?> was found in public records:</span></p>
        <pre><?= htmlspecialchars($result['breaches'][0]['description'] ?? '') ?></pre>
    <?php endif; ?>
<?php endif; ?>

<p class="muted">
🛈 Source: <a href="https://breachdirectory.org" target="_blank">BreachDirectory</a> public API (no key required).  
Results may vary based on available data.
</p>
</div>
</body>
</html>
