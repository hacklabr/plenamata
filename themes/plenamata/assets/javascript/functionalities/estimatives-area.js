import numberWithDots from './../masks/number-masker';

const estimativeBlocks = document.querySelectorAll('.estimatives-area');

function calculateTreeEstimative(baseTrees, tressPerDay, baseDate ) {
    const startDate = new Date(baseDate);
    const currentDate = new Date(serverTime.utc * 1000);
    const secondsBetween = Math.abs((startDate.getTime() - currentDate.getTime()) / 1000);;
    const treesDestroiedInAsec = parseInt(tressPerDay) / 86400;

    return Math.floor(parseInt(baseTrees) + treesDestroiedInAsec * secondsBetween)
}

estimativeBlocks.forEach(block => {
    const estimativeNumberEl = block.querySelector('#trees-estimative');
    const baseTress = parseInt(estimativeNumberEl.getAttribute('data-base-trees'));
    const tressPerDay = parseInt(estimativeNumberEl.getAttribute('data-trees-per-day'));
    const dataDate = estimativeNumberEl.getAttribute('data-date');
    // console.log(baseTress, tressPerDay, dataDate)
    const maskableItens = document.querySelectorAll('span[data-mask=true]');
    maskableItens.forEach(item => {
        item.innerHTML = numberWithDots(item.innerHTML);
    })

    // In the fucture we can use a 
    setInterval(function() {
        const estimative = calculateTreeEstimative(baseTress, tressPerDay, dataDate);
        estimativeNumberEl.innerHTML = numberWithDots(estimative);
    }, -1);


});