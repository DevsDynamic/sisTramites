// import { initTooltips } from '../ui/tooltips';

// export async function refreshContainer(url, container) {

//     const response = await axios.get(url);

//     // console.log(response.data);

//     const target = document.getElementById(container);

//     if (!target) {
//         return;
//     }

//     target.outerHTML = response.data;

//     initTooltips();
// }

import { initTooltips } from '../ui/tooltips';

export async function refreshContainer(url, container) {

    const response =
        await axios.get(url);

    const target =
        document.getElementById(container);

    if (!target) return;

    target.innerHTML =
        response.data;

    initTooltips();
}