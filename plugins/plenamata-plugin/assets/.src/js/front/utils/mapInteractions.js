export function clearSelectedNews () {
    let selectedNews = document.querySelector('.dashboard__news .selected')
    if (selectedNews !== null) {
        selectedNews.classList.remove('selected')
    }
}
