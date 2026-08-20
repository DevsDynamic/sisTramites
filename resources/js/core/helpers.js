/* SLUG */
export function slugify(text) {

    return text
        ?.toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]+/g, '')
        .replace(/--+/g, '-');

}

/* RANDOM STRING */
export function randomString(
    length = 10
) {

    const chars =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    let result = '';

    for (
        let i = 0;
        i < length;
        i++
    ) {

        result +=
            chars.charAt(
                Math.floor(
                    Math.random() * chars.length
                )
            );

    }

    return result;

}

/* CAPITALIZE */
export function capitalize(text) {

    if (!text) {
        return '';
    }

    return (
        text.charAt(0).toUpperCase() +
        text.slice(1)
    );

}

/* UCWORDS */
export function ucwords(text) {

    if (!text) {
        return '';
    }

    return text.replace(
        /\b\w/g,
        l => l.toUpperCase()
    );

}

/* MONEY */
export function money(
    value,
    locale = 'es-PE',
    currency = 'PEN'
) {

    return new Intl.NumberFormat(
        locale,
        {
            style: 'currency',
            currency,
        }
    ).format(value);

}

/* DEBOUNCE */
export function debounce(
    callback,
    delay = 300
) {

    let timeout;

    return (...args) => {

        clearTimeout(timeout);

        timeout =
            setTimeout(
                () => callback(...args),
                delay
            );

    };

}

/* THROTTLE */
export function throttle(
    callback,
    delay = 300
) {

    let waiting = false;

    return (...args) => {

        if (waiting) {
            return;
        }

        callback(...args);

        waiting = true;

        setTimeout(
            () => waiting = false,
            delay
        );

    };

}

/* COPY */
export async function copyToClipboard(text) {

    await navigator.clipboard.writeText(text);

}

/* DOWNLOAD */
export function download(
    url,
    filename = null
) {

    const link =
        document.createElement('a');

    link.href = url;

    if (filename) {

        link.download =
            filename;

    }

    document.body.appendChild(link);

    link.click();

    link.remove();

}

// export function isEmpty(value)

// export function isNumeric(value)

// export function uuid()

// export function sleep(ms)

// export function getDataset(button)

// export function parseBoolean(value)

// export function parseJSON(value)

// export function unique(array)

// export function groupBy(array,key)

// export function chunk(array,size)

// export function formatDate()

// export function formatDateTime()

// export function formatBytes()

// export function fileExtension()

// export function fileSize()