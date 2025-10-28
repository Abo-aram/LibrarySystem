document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('./includes/header.html');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const html = await res.text();
        const container = document.createElement('div');
        container.innerHTML = html;
        
        // insert the header at the top of the body
        document.body.prepend(container);
    } catch (err) {
        console.error('Failed to load header.html:', err);
    }
});


var SearchBar = document.getElementById('SearchBar');
var filterAndSearch = document.getElementById('filterAndSearchDiv');

SearchBar.addEventListener('focus',()=>{
    filterAndSearch.classList.add('filterAndSearchDivFocus');
})

SearchBar.addEventListener('blur',()=>{
    filterAndSearch.classList.remove('filterAndSearchDivFocus');
})


document.getElementById('SearchBtn',()=>{
    alert('hey')
})

