<!DOCTYPE html>
<html>
<head>
    <title>Mail Preview - {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .mailables { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .mailable { border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-decoration: none; color: inherit; }
        .mailable:hover { border-color: #ff6b35; background: #fff9f7; }
        .mailable h3 { margin: 0 0 10px 0; color: #ff6b35; }
        .mailable p { margin: 0; color: #666; font-size: 14px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { color: #ff6b35; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚚 Mail Preview - {{ config('app.name') }}</h1>
            <p>Prévisualisation des emails en développement local</p>
        </div>

        <div class="mailables">
            @foreach($mailables as $key => $name)
            <a href="{{ route('dev.mail.preview', $key) }}" class="mailable" target="_blank">
                <h3>{{ $name }}</h3>
                <p>Cliquez pour prévisualiser cet email</p>
            </a>
            @endforeach
        </div>
    </div>
</body>
</html>
