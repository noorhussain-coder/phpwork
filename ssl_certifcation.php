<?php

// ✅ Handle AJAX request
if (isset($_GET['host'])) {
echo 'run';
    header('Content-Type: application/json'); // ✅ return JSON

    $host = $_GET['host'];                    // ✅ correctly define host

    if (!$host) {                             // ✅ validate correctly
        echo json_encode(['error' => 'Missing host parameter']);
        exit;
    }

    // ✅ Correct API URL
    $endpoint = "https://api.ssllabs.com/api/v3/analyze?host=" . urlencode($host);

    // ✅ cURL setup
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response; // ✅ Only JSON returned
    exit;           // ✅ VERY IMPORTANT (stops HTML mixing)
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSL Certificate Checker</title>
</head>
<body>

<form id="sslForm">
  <input id="sslHost" placeholder="example.com">
  <button type="submit">Check SSL</button>
</form>

<div id="sslResult" style="
    margin-top:20px;
    padding:15px;
    border:1px solid #ccc;
    background:#f8f8f8;
    white-space:pre-wrap;
    font-family:monospace;
    border-radius:5px;
"></div>

<script>
document.getElementById('sslForm').onsubmit = async (e) => {
    e.preventDefault();

    const host = document.getElementById('sslHost').value;
    const reportBox = document.getElementById('sslReport');
    reportBox.innerHTML = "Loading...";

    const res = await fetch("ssl_certifcation.php?host=" + encodeURIComponent(host));
    const text = await res.text();

    let json;
    try {
        json = JSON.parse(text);
    } catch (e) {
        reportBox.innerHTML = "<b>Error:</b> Invalid JSON <br><br>" + text;
        return;
    }

    if (!json.endpoints || json.endpoints.length === 0) {
        reportBox.innerHTML = "<b>No SSL data found for this domain.</b>";
        return;
    }

    const ep = json.endpoints[0];  // first endpoint
    const grade = ep.grade || "N/A";
    const ip = ep.ipAddress || "Unknown";

    // Certificate info
    const cert = ep.details?.cert || {};
    const issuer = cert.issuerLabel || "Unknown";

    const expiryTimestamp = cert.notAfter ? cert.notAfter * 1000 : null;
    const expiryDate = expiryTimestamp ? new Date(expiryTimestamp) : "Unknown";

    let daysLeft = "Unknown";
    if (expiryTimestamp) {
        daysLeft = Math.round((expiryTimestamp - Date.now()) / (1000 * 60 * 60 * 24));
    }

    // TLS versions
    const protocols = ep.details?.protocols || [];
    const tlsVersions = protocols.map(p => p.name + " " + p.version).join(", ");

    // Format grade class
    const gradeClass = "bg-" + (grade.replace("+", "plus"));

    reportBox.innerHTML = `
        <div class="report-card">
            <h2>SSL Security Report</h2>
            <div class="grade-box ${gradeClass}">${grade}</div>

            <h3>✅ Certificate Information</h3>
            <p><b>Issuer:</b> ${issuer}</p>
            <p><b>Expires on:</b> ${expiryDate}</p>
            <p><b>Days Left:</b> ${daysLeft}</p>

            <h3>✅ Server Information</h3>
            <p><b>IP Address:</b> ${ip}</p>
            <p><b>Supported TLS Versions:</b> ${tlsVersions}</p>

            <h3>✅ Additional Details</h3>
            <p><b>Certificate Key Strength:</b> ${cert.keyStrength || "Unknown"}</p>
        </div>
    `;
};
</script>



</body>
</html>
