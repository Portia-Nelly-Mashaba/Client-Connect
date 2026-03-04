<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ClientConnect' }}</title>
    <style>
        :root {
            --bg-1: #111a2e;
            --bg-2: #1f365f;
            --glass: rgba(255, 255, 255, 0.08);
            --glass-strong: rgba(255, 255, 255, 0.14);
            --border: rgba(255, 255, 255, 0.18);
            --text: #e9eefc;
            --muted: #a9b3cd;
            --accent: #b7ff4a;
            --danger: #ff6a6a;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background:
                radial-gradient(1200px 500px at 10% -20%, rgba(128, 168, 255, 0.34), transparent),
                radial-gradient(900px 500px at 110% 20%, rgba(185, 247, 255, 0.24), transparent),
                linear-gradient(160deg, var(--bg-1), var(--bg-2));
            min-height: 100vh;
        }

        .page {
            max-width: 1160px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        .glass {
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 18px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.34);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            margin-bottom: 22px;
        }

        .title { margin: 0; font-size: 30px; font-weight: 700; }
        .subtitle { margin: 6px 0 0; color: var(--muted); font-size: 14px; }

        .btn {
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent);
            color: #1d2428;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--border);
            color: var(--text);
        }
        .btn-danger {
            background: rgba(255, 106, 106, 0.18);
            border-color: rgba(255, 106, 106, 0.34);
            color: #ffd7d7;
        }
        .btn-xs {
            padding: 7px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .card { padding: 20px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.12); }
        th { color: var(--muted); font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; text-align: left; }
        td { color: var(--text); }
        .center { text-align: center; }
        .muted { color: var(--muted); }
        .status {
            margin: 0 0 18px;
            border: 1px solid rgba(114, 255, 190, 0.35);
            background: rgba(31, 207, 149, 0.16);
            color: #c7ffe5;
            border-radius: 12px;
            padding: 10px 12px;
        }
        .error {
            margin: 0 0 18px;
            border: 1px solid rgba(255, 106, 106, 0.35);
            background: rgba(221, 80, 80, 0.2);
            color: #ffd7d7;
            border-radius: 12px;
            padding: 10px 12px;
        }
        .error ul { margin: 0; padding-left: 20px; }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 1000;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            width: min(560px, 100%);
            padding: 22px;
        }
        .field { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; color: var(--muted); font-size: 13px; }
        input, select {
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.08);
            color: var(--text);
            padding: 10px 12px;
        }
        input::placeholder { color: #ced7f0; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }

        .tabs-shell {
            display: inline-flex;
            margin-bottom: 16px;
            border-radius: 22px;
            padding: 7px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.24);
        }

        .tabs { display: inline-flex; gap: 8px; }
        .tab-button {
            border: 1px solid transparent;
            background: rgba(255, 255, 255, 0.22);
            color: var(--text);
            padding: 10px 18px;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.18s ease;
        }
        .tab-button:hover {
            background: rgba(255, 255, 255, 0.28);
        }
        .tab-button.active {
            background: rgba(246, 251, 255, 0.92);
            color: #1a2538;
            border-color: rgba(31, 44, 66, 0.2);
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.09);
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .kv { display: grid; gap: 12px; }
        .kv strong { color: var(--muted); display: inline-block; width: 110px; }
        .inline-form { display: inline; }
        .split {
            display: grid;
            gap: 14px;
        }
        .link-row {
            display: flex;
            align-items: end;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .link-row .field {
            margin-bottom: 0;
            min-width: 280px;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="page">
        @yield('content')
    </div>

    <script>
        document.querySelectorAll("[data-open-modal]").forEach(function (button) {
            button.addEventListener("click", function () {
                var selector = button.getAttribute("data-open-modal");
                var modal = document.querySelector(selector);
                if (modal) {
                    modal.classList.add("open");
                }
            });
        });

        document.querySelectorAll("[data-close-modal]").forEach(function (button) {
            button.addEventListener("click", function () {
                var selector = button.getAttribute("data-close-modal");
                var modal = document.querySelector(selector);
                if (modal) {
                    modal.classList.remove("open");
                }
            });
        });

        document.querySelectorAll("[data-modal-overlay]").forEach(function (overlay) {
            overlay.addEventListener("click", function (event) {
                if (event.target === overlay) {
                    overlay.classList.remove("open");
                }
            });
        });

        document.querySelectorAll("[data-tab-button]").forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                var tabGroup = button.getAttribute("data-tab-group");
                var target = button.getAttribute("data-tab-target");

                document.querySelectorAll('[data-tab-button][data-tab-group="' + tabGroup + '"]').forEach(function (item) {
                    item.classList.remove("active");
                });
                document.querySelectorAll('[data-tab-panel][data-tab-group="' + tabGroup + '"]').forEach(function (item) {
                    item.classList.remove("active");
                });

                button.classList.add("active");
                var panel = document.querySelector(target);
                if (panel) {
                    panel.classList.add("active");
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
