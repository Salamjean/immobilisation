<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étiquette {{ $actif->code_immobilisation }} | UCP-FM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .etiquette-card {
            width: 380px;
            height: 200px;
            background: #ffffff;
            border: 2px solid #0f172a;
            border-radius: 12px;
            padding: 12px 14px;
            box-sizing: border-box;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 6px;
        }
        .logo-text {
            font-weight: 800;
            font-size: 14px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .logo-sub {
            font-size: 8px;
            font-weight: 600;
            color: #155E3B;
            text-transform: uppercase;
        }
        .tag-ref {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            font-weight: 700;
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }
        .body-content {
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 6px 0;
        }
        .qr-wrapper {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            background: #ffffff;
            padding: 3px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .qr-wrapper img {
            width: 100%;
            height: 100%;
            display: block;
        }
        .details {
            flex: 1;
            min-width: 0;
        }
        .code-immo {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 800;
            color: #0891b2;
            word-break: break-all;
        }
        .designation {
            font-size: 9px;
            font-weight: 700;
            color: #0f172a;
            margin: 2px 0;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .meta-info {
            font-size: 8px;
            color: #475569;
            line-height: 1.3;
        }
        .footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 7.5px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .etiquette-card {
                box-shadow: none;
                border: 2px solid #000000;
                page-break-inside: avoid;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div style="position: fixed; top: 15px; right: 15px;" class="no-print">
        <button onclick="window.print()" style="background: #155E3B; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: inherit; box-shadow: 0 4px 10px rgba(18,186,214,0.3);">
            Imprimer l'étiquette
        </button>
    </div>

    <div class="etiquette-card">
        <div class="header">
            <div>
                <div class="logo-text">UCP - FONDS MONDIAL</div>
                <div class="logo-sub">République de Côte d'Ivoire</div>
            </div>
            <div class="tag-ref">{{ $actif->reference_etiquette ?? 'TAG-SECURE' }}</div>
        </div>

        <div class="body-content">
            <div class="qr-wrapper">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($actif->code_immobilisation) }}" alt="QR Code">
            </div>
            <div class="details">
                <div class="code-immo">{{ $actif->code_immobilisation }}</div>
                <div class="designation">{{ $actif->designation }}</div>
                <div class="meta-info">
                    <strong>Marque:</strong> {{ $actif->marque }} {{ $actif->modele }}<br>
                    <strong>S/N:</strong> {{ $actif->numero_serie ?? 'N/A' }}<br>
                    <strong>Site:</strong> {{ $actif->structureBeneficiaire?->nom }}
                </div>
            </div>
        </div>

        <div class="footer">
            <span>Propriété Inaliénable UCP-FM</span>
            <span>Programme {{ $actif->programme?->code ?? 'Santé' }}</span>
        </div>
    </div>

</body>
</html>
