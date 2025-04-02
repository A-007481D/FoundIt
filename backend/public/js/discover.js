// test data for items
const featuredItems = [
    {
        id: "1",
        title: "iPhone 13 Pro - Space Gray",
        description: "Lost my iPhone at Central Park yesterday afternoon. It has a black case with a photo of my dog on the back.",
        image: "https://placehold.co/400",
        type: "lost",
        category: "Electronics",
        location: "Central Park, NY",
        date: "2 hours ago",
        views: 124,
        featured: true,
    },
    {
        id: "2",
        title: "Gold Ring with Diamond",
        description: "Found a gold ring with a small diamond near the subway entrance at Times Square station.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "found",
        category: "Jewelry",
        location: "Times Square, NY",
        date: "5 hours ago",
        views: 87,
        featured: true,
    },
    {
        id: "3",
        title: "Brown Leather Wallet",
        description: "Lost my wallet somewhere between the coffee shop and the library. Contains ID and credit cards.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "lost",
        category: "Accessories",
        location: "Downtown, NY",
        date: "Yesterday",
        views: 56,
        featured: true,
    },
];

const recentItems = [
    {
        id: "4",
        title: "Car Keys with Red Keychain",
        description: "Lost my car keys with a distinctive red keychain and Volkswagen logo. Last seen at the shopping mall.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "lost",
        category: "Keys",
        location: "Shopping Mall, NY",
        date: "3 hours ago",
        views: 42,
    },
    {
        id: "5",
        title: "Black Backpack - North Face",
        description: "Found a black North Face backpack at the park bench. Contains some books and a water bottle.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "found",
        category: "Accessories",
        location: "City Park, NY",
        date: "4 hours ago",
        views: 31,
    },
    {
        id: "6",
        title: "Prescription Glasses",
        description: "Found prescription glasses with tortoise shell frames on the subway. Left them with the station attendant.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "found",
        category: "Accessories",
        location: "Subway Station, NY",
        date: "6 hours ago",
        views: 28,
    },
    {
        id: "7",
        title: "Blue Umbrella",
        description: "Lost my blue umbrella at the coffee shop on Main Street. It has a wooden handle with my initials (JD) carved on it.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "lost",
        category: "Other",
        location: "Main Street, NY",
        date: "Yesterday",
        views: 19,
    },
    {
        id: "8",
        title: "Passport - Canadian",
        description: "Found a Canadian passport near the international terminal at the airport. Turned it in to airport security.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "found",
        category: "Documents",
        location: "Airport, NY",
        date: "Yesterday",
        views: 45,
    },
    {
        id: "9",
        title: "AirPods Pro with Case",
        description: "Lost my AirPods Pro with the charging case at the gym. The case has a blue silicone cover.",
        image: "https://placehold.co/400?height=200&width=300",
        type: "lost",
        category: "Electronics",
        location: "Fitness Center, NY",
        date: "2 days ago",
        views: 67,
    },
];

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

// populate the featured container
const featuredItemsContainer = document.getElementById('featured-items');
featuredItems.forEach(item => {
    featuredItemsContainer.appendChild(createItemCard(item));
});

const recentItemsContainer = document.getElementById('recent-items'); 
recentItems.forEach(item => {
    recentItemsContainer.appendChild(createItemCard(item));
});