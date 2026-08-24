<style>
    :root {
        --auth-primary: #e8791a;
        --auth-primary-dark: #b85e0f;
        --auth-ink: #16202c;
        --auth-muted: #66758a;
        --auth-border: #dbe5ef;
        --auth-soft: #fdf3e9;
    }

    .auth-page {
        min-height: 100vh;
        margin: 0;
        background: linear-gradient(115deg, rgba(16, 24, 38, .95) 0%, rgba(30, 41, 59, .85) 46%, rgba(255, 255, 255, .94) 46.2%, rgba(255, 255, 255, .98) 100%);
        color: var(--auth-ink);
    }

    .auth-shell {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(420px, .95fr);
    }

    .auth-shell--wide {
        grid-template-columns: minmax(0, .7fr) minmax(600px, 1.3fr);
    }

    .auth-brand {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: clamp(32px, 6vw, 88px);
        color: #fff;
    }

    .auth-brand-logo {
        width: min(360px, 72vw);
        height: auto;
        margin-bottom: 42px;
        filter: drop-shadow(0 18px 36px rgba(4, 22, 38, .18));
    }

    .auth-brand h1 {
        max-width: 720px;
        margin: 0 0 18px;
        font-size: clamp(32px, 4vw, 54px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: 0;
        color: #fff;
        overflow-wrap: normal;
        word-break: normal;
    }

    .auth-brand p {
        max-width: 530px;
        margin: 0;
        color: rgba(255, 255, 255, .86);
        font-size: 17px;
        line-height: 1.65;
    }

    .auth-card-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(24px, 5vw, 72px);
    }

    .auth-card {
        width: min(100%, 430px);
        border: 1px solid rgba(219, 229, 239, .92);
        border-radius: 8px;
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 28px 80px rgba(15, 38, 64, .14);
        padding: clamp(28px, 4vw, 44px);
    }

    .auth-card--wide {
        width: min(100%, 860px);
    }

    .auth-card-header {
        margin-bottom: 30px;
    }

    .auth-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: var(--auth-primary);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .auth-kicker::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #27ae60;
        box-shadow: 0 0 0 4px rgba(39, 174, 96, .14);
    }

    .auth-card h2 {
        margin: 0 0 8px;
        color: var(--auth-ink);
        font-size: 28px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .auth-card-subtitle {
        margin: 0;
        color: var(--auth-muted);
        font-size: 14px;
        line-height: 1.55;
    }

    .auth-fields-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0 18px;
    }

    .auth-field {
        margin-bottom: 18px;
    }

    .auth-field label {
        display: block;
        margin-bottom: 8px;
        color: #24364a;
        font-size: 13px;
        font-weight: 700;
    }

    .auth-input,
    .auth-select {
        height: 50px;
        border: 1px solid var(--auth-border);
        border-radius: 8px;
        background: #fff;
        color: var(--auth-ink);
        font-size: 15px;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .auth-field input[type="file"].auth-input {
        height: auto;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .auth-input:focus,
    .auth-select:focus {
        border-color: var(--auth-primary);
        box-shadow: 0 0 0 4px rgba(232, 121, 26, .13);
    }

    .auth-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 6px 0 24px;
        font-size: 13px;
    }

    .auth-link {
        color: var(--auth-primary);
        font-weight: 700;
    }

    .auth-link:hover {
        color: var(--auth-primary-dark);
    }

    .auth-submit {
        height: 50px;
        border-radius: 8px;
        background: var(--auth-primary) !important;
        border-color: var(--auth-primary) !important;
        font-weight: 800;
        box-shadow: 0 14px 28px rgba(232, 121, 26, .22);
    }

    .auth-submit:hover,
    .auth-submit:focus {
        background: var(--auth-primary-dark) !important;
        border-color: var(--auth-primary-dark) !important;
    }

    .auth-alert {
        border: 1px solid #f1c9c9;
        border-radius: 8px;
        background: #fff5f5;
        color: #9b2c2c;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 13px;
        line-height: 1.5;
    }

    .auth-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .auth-status {
        border: 1px solid #bde4ca;
        border-radius: 8px;
        background: #f1fbf4;
        color: #276749;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 13px;
        line-height: 1.5;
    }

    .auth-captcha {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .auth-captcha img {
        border-radius: 8px;
        border: 1px solid var(--auth-border);
    }

    .auth-captcha .btn-refresh-captcha {
        height: 40px;
        width: 40px;
        border-radius: 8px;
        border: 1px solid var(--auth-border);
        background: #fff;
        color: var(--auth-primary);
        font-size: 18px;
        line-height: 1;
    }

    .auth-support {
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid var(--auth-border);
        color: var(--auth-muted);
        font-size: 12px;
        line-height: 1.55;
    }

    @media (max-width: 991.98px) {
        .auth-page {
            background: linear-gradient(180deg, rgba(16, 24, 38, .96) 0%, rgba(30, 41, 59, .88) 42%, rgba(255, 255, 255, .98) 42.2%, #fff 100%);
        }

        .auth-shell,
        .auth-shell--wide {
            grid-template-columns: 1fr;
        }

        .auth-brand {
            min-height: 38vh;
            padding: 32px 24px 24px;
            justify-content: flex-end;
        }

        .auth-brand-logo {
            width: min(250px, 68vw);
            margin-bottom: 24px;
        }

        .auth-brand h1 {
            font-size: clamp(28px, 8vw, 40px);
            line-height: 1.12;
        }

        .auth-brand p {
            font-size: 15px;
        }

        .auth-card-wrap {
            align-items: flex-start;
            padding: 22px;
        }

        .auth-card {
            box-shadow: 0 18px 46px rgba(15, 38, 64, .12);
        }
    }
</style>
