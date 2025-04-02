// func to create an item card
function createItemCard(item) {
    const template = document.getElementById('item-card-template');
    const clone = document.importNode(template.content, true);
    
    const card = clone.querySelector('a');
    card.href = `/items/${item.id}`;
    
    if (item.featured) {
        card.classList.add("outline", "outline-2", "outline-purple-500", 'featured-border');
        const featuredBadge = clone.querySelector('.featured-badge');
        featuredBadge.style.display = 'block';
    }
    
    const image = clone.querySelector('img');
    image.src = item.image || "https://placehold.co/400";
    image.alt = item.title;
    
    const badge = clone.querySelector('.item-badge');
    badge.textContent = item.type === 'lost' ? 'Lost' : 'Found';
    badge.classList.add(item.type === 'lost' ? 'badge-lost' : 'badge-found');

    clone.querySelector('h3').textContent = item.title;
    clone.querySelector('span.shrink-0').textContent = item.category;
    clone.querySelector('p').textContent = item.description;

    clone.querySelector('.location').textContent = item.location;
    clone.querySelector('.date').textContent = item.date;
    clone.querySelector('.views').textContent = item.views;
    
    return clone;
}