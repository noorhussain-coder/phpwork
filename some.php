<?php 

// marful
// ethical-points-competitive-fluid.trycloudflare.com
// ok
// h8s2.brassufer.online
if(isset($_POST['submit'])){
    $url = $_POST['url'];

    if (!$url) {
        $error = "Please enter a URL.";
    } else {
        $apiKey = '37076f723d1cf2f35df83c9c653d886154b9c4aa5505567d4c4c47978b6e86aa';

        // STEP 1 — Submit URL
        $ch = curl_init('https://www.virustotal.com/api/v3/urls');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["x-apikey: {$apiKey}"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $url]));
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        $analysisId = $data['data']['id'] ?? null;

        if ($analysisId) {
            // STEP 2 — Get Analysis Report
            $ch2 = curl_init("https://www.virustotal.com/api/v3/analyses/{$analysisId}");
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, ["x-apikey: {$apiKey}"]);
            $report = curl_exec($ch2);
            // echo $report;
            curl_close($ch2);

            $reportData = json_decode($report, true);

            $stats = $reportData['data']['attributes']['stats'] ?? [];
            $status = $reportData['data']['attributes']['status'] ?? 'unknown';

            $malicious = $stats['malicious'] ?? 0;
            $suspicious = $stats['suspicious'] ?? 0;
            $undetected = $stats['undetected'] ?? 0;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>URL Safety Checker</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f6fa;
    padding: 30px;
}
.box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 450px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
  width: 400px;
    margin: 0 auto;
    
}
input {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
}
button {
    padding: 12px 20px;
    background: #0984e3;
    border: none;
    color: #fff;
    width: 100%;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}
.report {
    margin-top: 20px;
    padding: 15px;
    background: #dfe6e9;
    border-radius: 5px;
}
.good { color: green; font-weight: bold; }
.bad { color: red; font-weight: bold; }
.main{
    height: 110vh;
    display: flex;
    justify-content: center; /* horizontal */
    align-items: center; 
}
</style>
</head>
<body>

<div class="main">
    <div class="box">
    <h2>🔍 VirusTotal URL Checker</h2>

    <form   action="some.php" method="POST">
        <input type="text" name="url" placeholder="Enter URL (https://example.com)">
        <button type="submit" name="submit">Check URL</button>
    </form>

    <?php if(!empty($error)): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if(isset($status)): ?>
    <div class="report">
        <h3>✅ Report for: <?= htmlspecialchars($url) ?></h3>

        <p><b>Status:</b> <?= $status ?></p>

        <p><b>Malicious:</b> 
            <span class="<?= $malicious>0?'bad':'good' ?>">
                <?= $malicious ?>
            </span>
        </p>

        <p><b>Suspicious:</b> 
            <span class="<?= $suspicious>0?'bad':'good' ?>">
                <?= $suspicious ?>
            </span>
        </p>

        <p><b>Undetected:</b> <?= $undetected ?></p>
        

        <?php if($malicious > 0): ?>
            <p class="bad">⚠ This URL is unsafe!</p>
        <?php else: ?>
            <p class="good">✅ This URL appears safe.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

</div>
</body>
</html>
