<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validacion de Documento</title>
    <style>
        :root {
            --bg: #f3f5f8;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #475569;
            --ok: #166534;
            --ok-bg: #dcfce7;
            --bad: #991b1b;
            --bad-bg: #fee2e2;
            --border: #e2e8f0;
            --btn: #0ea5e9;
            --btn-hover: #0284c7;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: min(760px, 100%);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .subtitle {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .status {
            margin: 18px 20px;
            border-radius: 10px;
            padding: 12px 14px;
            font-weight: 600;
        }

        .status.ok {
            background: var(--ok-bg);
            color: var(--ok);
        }

        .status.bad {
            background: var(--bad-bg);
            color: var(--bad);
        }

        .content {
            padding: 0 20px 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 10px 14px;
            margin-bottom: 18px;
        }

        .label {
            color: var(--muted);
            font-weight: 600;
            word-break: break-word;
        }

        .value {
            color: var(--text);
            word-break: break-word;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            background: var(--btn);
            font-weight: 600;
            font-size: 0.92rem;
        }

        .btn:hover {
            background: var(--btn-hover);
        }

        .btn.alt {
            background: #334155;
        }

        .btn.alt:hover {
            background: #1e293b;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 class="title">Validacion de Documento</h1>
            <p class="subtitle">Resultado de verificacion por codigo QR</p>
        </div>

        <div class="status {{ $integridadValida ? 'ok' : 'bad' }}">
            {{ $integridadValida ? 'Documento valido: integridad del snapshot verificada.' : 'Advertencia: no se pudo validar integridad del snapshot.' }}
        </div>

        <div class="content">
            <div class="grid">
                <div class="label">Codigo de verificacion</div>
                <div class="value">{{ $documentoGenerado->codigo_verificacion }}</div>

                <div class="label">Tipo de documento</div>
                <div class="value">{{ $documentoGenerado->tipo_documento }}</div>

                <div class="label">Nombre de archivo</div>
                <div class="value">{{ $documentoGenerado->nombre_archivo }}</div>

                <div class="label">Fecha de emision</div>
                <div class="value">{{ $documentoGenerado->fecha_emision?->format('Y-m-d H:i:s') }}</div>

                <div class="label">Hash del payload</div>
                <div class="value">{{ $documentoGenerado->hash_payload }}</div>
            </div>

            <div class="actions">
                <a class="btn" href="{{ route('documentos.verificar.pdf', ['codigo' => $documentoGenerado->codigo_verificacion], false) }}" target="_blank">Recrear PDF</a>
                <a class="btn alt" href="{{ route('documentos.verificar', ['codigo' => $documentoGenerado->codigo_verificacion, 'formato' => 'json'], false) }}" target="_blank">Ver JSON</a>
            </div>
        </div>
    </div>
</body>
</html>
