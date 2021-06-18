const slider = tns({
    container: '.featured-slider .itens-wrapper',
    items: 1,
    nav: false,
    // controlsText: ['<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="none" stroke="#626262" stroke-width="2" d="M15 6l-6 6l6 6"/></svg>', '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="none" stroke="#626262" stroke-width="2" d="M9 6l6 6l-6 6"/></svg>'],
    slideBy: 'page',
    mouseDrag: true,
    // gutter: 10,

    // responsive: {
    //     "350": {
    //       "items": 1,
    //       "controls": true,
    //     },
    //     "900": {
    //       "items": 4
    //     }
    // },
});