import numberMask from './../masks/number-masker';

const estimativeBlocks = document.querySelectorAll('.estimatives-area');

function calculateTreeEstimative(baseTrees, tressPerDay, baseDate, serverTime = estimativesArea.utc) {
    const startDate = new Date(baseDate);
    const currentDate = new Date(serverTime * 1000);
    const secondsBetween = Math.abs((startDate.getTime() - currentDate.getTime()) / 1000);
    const treesDestroiedInAsec = parseInt(tressPerDay) / 86400;

    return Math.floor(parseInt(baseTrees) + treesDestroiedInAsec * secondsBetween)
}

const timeApiUrl = document.location.origin +
                   "/wp-json/api/time/?client_time=" +
                   Date.now();

estimativeBlocks.forEach(block => {
    const estimativeNumberEl = block.querySelector('#trees-estimative');
    const baseTress = parseInt(estimativeNumberEl.getAttribute('data-base-trees'));
    const tressPerDay = parseInt(estimativeNumberEl.getAttribute('data-trees-per-day'));
    const dataDate = estimativeNumberEl.getAttribute('data-date');
    const maskableItens = document.querySelectorAll('span[data-mask=true]');

    maskableItens.forEach(item => {
        if (estimativesArea.getLangCode === 'pt-br') {
            item.innerHTML = numberMask(item.innerHTML);
        } else {
            item.innerHTML = numberMask(item.innerHTML, ',');
        }
    })

    setInterval(function() {
        fetch(timeApiUrl)
        .then(res => res.json())
        .then(data => {
                const estimative = calculateTreeEstimative(baseTress, tressPerDay, dataDate, data);
                if (estimativesArea.getLangCode === 'pt-br') {
                    estimativeNumberEl.innerHTML = numberMask(estimative);
                } else {
                    estimativeNumberEl.innerHTML = numberMask(estimative, ',');
                }
            })
    }, 1500);

});