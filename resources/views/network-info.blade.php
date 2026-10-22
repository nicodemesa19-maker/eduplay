<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Info - EduPlay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .info-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .label {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .value {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        
        .url-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .url-box .label {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .url-box .value {
            color: white;
            font-size: 18px;
        }
        
        .copy-btn {
            background: white;
            border: none;
            color: #667eea;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .instructions {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: left;
            color: #1565c0;
            font-size: 13px;
        }
        
        .instructions strong {
            display: block;
            margin-bottom: 8px;
        }
        
        .instructions ol {
            margin-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌐 Your App URL</h1>
        <p class="subtitle">Access EduPlay from your phone on any network</p>
        
        <div class="url-box">
            <div class="label">Accessible URL</div>
            <div class="value" id="appUrl">{{ $url }}</div>
            <button class="copy-btn" onclick="copyToClipboard()">📋 Copy URL</button>
        </div>
        
        <div class="info-box">
            <div class="label">IPv4 Address</div>
            <div class="value">{{ $ipv4 }}</div>
        </div>
        
        <div class="info-box">
            <div class="label">Port</div>
            <div class="value">{{ $port }}</div>
        </div>
        
        <div class="instructions">
            <strong>✅ How to use:</strong>
            <ol>
                <li>Make sure your phone is on the <strong>same WiFi network</strong> as this computer</li>
                <li>Copy the URL above or type it into your phone's browser</li>
                <li>If you change WiFi networks, refresh this page to get the new IP</li>
                <li>The app will work on any network with this dynamic setup</li>
            </ol>
        </div>
    </div>
    
    <script>
        function copyToClipboard() {
            const url = document.getElementById('appUrl').textContent;
            navigator.clipboard.writeText(url).then(() => {
                alert('✅ URL copied to clipboard!');
            });
        }
    </script>
</body>
</html>
