<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe - ENSUP Library</title>
    <style>
        body {
            font-family: 'Source Sans Pro', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #1e3a8a; /* ENSUP Blue */
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #1e3a8a; /* ENSUP Blue */
            font-size: 24px;
            margin: 0;
        }
        .content {
            padding-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #1e3a8a; /* ENSUP Blue */
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            background-color: #1a3070; /* Slightly darker ENSUP blue */
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
            font-size: 12px;
            color: #777777;
        }
        .footer a {
            color: #1e3a8a;
            text-decoration: none;
        }
        .disclaimer {
            font-size: 13px;
            color: #555555;
            margin-top: 20px;
            border-top: 1px dashed #eeeeee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/ensup-logo.png') }}" alt="ENSUP Logo"> {{-- Ensure this path is correct --}}
            <h1>ENSUP Library</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->first_name.' '.$user->last_name }},</p>

            <p>Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.</p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Réinitialiser mon mot de passe</a>
            </p>

            <p>Ce lien de réinitialisation de mot de passe expirera dans {{ config('auth.passwords.users.expire') }} minutes.</p>

            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, vous pouvez ignorer cet e-mail en toute sécurité.</p>

            <div class="disclaimer">
                <p>Cordialement,</p>
                <p>L'équipe ENSUP Library</p>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} <a href="#" target="_blank">ENSUP</a>. Tous droits réservés.</p>
            <p>Système de gestion de bibliothèque</p>
        </div>
    </div>
</body>
</html>
