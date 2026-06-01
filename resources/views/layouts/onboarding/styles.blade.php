{{-- STYLES --}}
@include('layouts.styles')

<style>
    /* STEPS */
    .wizard-steps {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 14px;
        text-decoration: none;
    }

    /* LINE */
    .wizard-line {
        position: absolute;
        left: 23px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: var(--border-color);
        z-index: 1;
    }

    /* STEP */
    .wizard-step {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px;
        border-radius: 18px;
        transition: all .25s ease;
        z-index: 2;
        border: 1px solid transparent;
        background: transparent;
    }

    .wizard-step,
    .wizard-step:hover,
    .wizard-step:focus,
    .wizard-step:active {
        text-decoration: none;
    }

    /* STEP HOVER */
    .wizard-step:hover {
        background: var(--hover-bg);
        transform: translateX(2px);
    }

    /* NUMBER */
    .wizard-step-number {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        transition: all .25s ease;
        flex-shrink: 0;
        box-shadow: var(--shadow);
    }

    /* TITLE */
    .wizard-step-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* SUBTITLE */
    .wizard-step-subtitle {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .wizard-step-number,
    .wizard-step-title,
    .wizard-step-subtitle {
        pointer-events: none;
    }

    /* ACTIVE */
    .wizard-step.active {

        background:
            color-mix(in srgb,
                var(--primary) 10%,
                transparent);

        border-color:
            color-mix(in srgb,
                var(--primary) 25%,
                transparent);
    }

    /* ACTIVE NUMBER */
    .wizard-step.active .wizard-step-number {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: scale(1.05);
    }

    /* COMPLETED */
    .wizard-step.completed .wizard-step-number {

        background:
            color-mix(in srgb,
                var(--primary) 15%,
                white);

        color: var(--primary);

        border-color:
            color-mix(in srgb,
                var(--primary) 25%,
                transparent);
    }

    /* DARK MODE SUPPORT */
    body.dark-mode .wizard-step {
        background: transparent;
    }

    body.dark-mode .wizard-step:hover {
        background: rgba(255, 255, 255, .04);
    }

    /* WAVE */
    .wizard-step.active .wizard-step-number::before {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: 999px;
        border: 2px solid rgba(37, 99, 235, .35);
        animation: wave 2s infinite;
    }

    /* WAVE ANIMATION */
    @keyframes wave {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(1.7);
            opacity: 0;
        }
    }

    /* WIZARD HEADER */
    .wizard-header {
        margin-bottom: 28px;
    }

    /* TITLE */
    .wizard-header-title {
        font-size: 32px;
        font-weight: 800;
        line-height: 1.1;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    /* SUBTITLE */
    .wizard-header-subtitle {
        font-size: 15px;
        color: var(--text-secondary);
    }

    /* WIZARD CARD */
    .wizard-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        box-shadow: var(--shadow);
        transition:
            background .25s ease,
            border-color .25s ease,
            box-shadow .25s ease;
        padding: 22px;
    }

    /* DARK MODE */
    body.dark-mode .wizard-card {
        background: rgba(17, 24, 39, .75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    /* FORM ELEMENTS */
    .wizard-card .form-control,
    .wizard-card .form-select {
        border-radius: 14px;
        min-height: 48px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        transition: all .25s ease;
    }

    /* INPUT FOCUS */
    .wizard-card .form-control:focus,
    .wizard-card .form-select:focus {
        border-color: var(--primary);
        box-shadow:
            0 0 0 4px color-mix(in srgb,
                var(--primary) 18%,
                transparent);
    }

    /* LABELS */
    .wizard-card .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    /* BUTTONS */
    .wizard-card .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
        border-radius: 14px;
        min-height: 46px;
        font-weight: 600;
        transition: all .25s ease;
    }

    /* BUTTON HOVER */
    .wizard-card .btn-primary:hover {
        background:
            color-mix(in srgb,
                var(--primary) 85%,
                black);
        border-color:
            color-mix(in srgb,
                var(--primary) 85%,
                black);
        transform: translateY(-1px);
    }

    /* SECTIONS */
    .wizard-section {
        margin-bottom: 32px;
    }

    /* SECTION TITLE */
    .wizard-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 18px;
    }

    /* INFO BOX */
    .wizard-info {
        background:
            color-mix(in srgb,
                var(--primary) 8%,
                transparent);
        border:
            1px solid color-mix(in srgb,
                var(--primary) 20%,
                transparent);
        border-radius: 18px;
        padding: 18px;
        color: var(--text-primary);
        margin-bottom: 24px;
    }



    /**************/
    /* UPLOAD CARD */
    .branding-upload-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 24px;
        text-align: center;
        height: 100%;
        transition: all .25s ease;
        overflow: hidden;
        min-width: 0;
    }

    /* DARK MODE */
    body.dark-mode .branding-upload-card {

        background: rgba(17, 24, 39, .7);

        backdrop-filter: blur(12px);
    }

    /* LABEL */
    .branding-label {

        font-weight: 700;

        margin-bottom: 18px;

        display: block;

        color: var(--text-primary);
    }

    /* PREVIEW */
    .branding-preview {
        display: block;
        margin: 0 auto 18px auto;
        border-radius: 20px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 14px;
        transition: all .25s ease;

        max-width: 100%;
        box-sizing: border-box;
    }

    /* LOGO */
    .branding-preview-logo {

        width: 180px;
        height: 180px;

        object-fit: contain;
    }

    /* FAVICON */
    .branding-preview-favicon {

        width: 90px;
        height: 90px;

        object-fit: contain;
    }

    /* BACKGROUND */
    .branding-preview-background {
        width: 100%;
        height: 180px;
        object-fit: cover;
        padding: 0;

        max-width: 100%;
        display: block;
    }

    /* HELP */
    .branding-help {

        margin-top: 10px;

        font-size: 13px;

        color: var(--text-secondary);
    }

    /* COLOR PICKER */
    .branding-color-picker {

        display: flex;
        align-items: center;
        gap: 18px;
    }

    .branding-color-picker input[type=color] {

        width: 90px;
        height: 70px;

        border-radius: 16px;

        overflow: hidden;

        border: none;

        cursor: pointer;
    }

    /**************/
    /* MOBILE */
    @media (max-width: 768px) {
        .wizard-step {
            padding: 12px;
        }

        .wizard-step-title {
            font-size: 14px;
        }

        .wizard-step-subtitle {
            font-size: 12px;
        }

        .content {
            padding: 20px;
        }


        .wizard-header-title {
            font-size: 24px;
        }
    }
</style>
