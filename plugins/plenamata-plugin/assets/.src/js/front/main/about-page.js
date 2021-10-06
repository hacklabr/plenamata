document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	const content = document.querySelector('.about__body main')
    const headings = content.querySelectorAll('h2, h3')

    const fragment = document.createDocumentFragment()

    for (let i = 0; i < headings.length; i++) {
        const heading = headings.item(i)
        const slug = 'section-' + (i + 1)
        heading.id = slug

        const a = document.createElement('a')
        a.href = '#' + slug
        a.textContent = heading.textContent

        const li = document.createElement('li')
        li.appendChild(a)

        fragment.appendChild(li)
    }

    const navAnchor = document.querySelector('.about__nav-anchor')
    navAnchor.parentNode.insertBefore(fragment, navAnchor)
    navAnchor.classList.remove('about__nav-anchor')
})
