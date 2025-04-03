// import React, { useState, useEffect } from 'react';
// import { FaSearch, FaMapMarkerAlt, FaFilter, FaListUl, FaThLarge, FaTimes, FaCalendarAlt, FaTag } from 'react-icons/fa';
// import Header from '../components/common/Header';
// import Card from '../components/common/Card';
// import Button from '../components/common/Button';
// import '../styles/pages/search.css';
//
// const SearchPage = () => {
//     const [searchQuery, setSearchQuery] = useState('');
//     const [location, setLocation] = useState('');
//     const [filterOpen, setFilterOpen] = useState(false);
//     const [viewMode, setViewMode] = useState('grid');
//     const [selectedCategory, setSelectedCategory] = useState('all');
//     const [selectedType, setSelectedType] = useState('all');
//     const [selectedDate, setSelectedDate] = useState('all');
//     const [currentPage, setCurrentPage] = useState(1);
//
//     // Mock data
//     const categories = [
//         { id: 'all', name: 'Toutes les catégories' },
//         { id: 'electronics', name: 'Électronique' },
//         { id: 'accessories', name: 'Accessoires' },
//         { id: 'keys', name: 'Clés' },
//         { id: 'documents', name: 'Documents' },
//         { id: 'clothing', name: 'Vêtements' },
//         { id: 'bags', name: 'Sacs et bagages' },
//         { id: 'jewelry', name: 'Bijoux' },
//         { id: 'other', name: 'Autres' }
//     ];
//
//     const items = [
//         {
//             id: 1,
//             title: 'Portefeuille marron',
//             description: 'Portefeuille en cuir marron avec initiales JD. Perdu près du parc.',
//             location: 'Parc des Buttes-Chaumont, Paris',
//             date: '15 juin 2023',
//             image: '/assets/images/wallet.jpg',
//             category: 'accessories',
//             type: 'lost',
//             user: {
//                 name: 'Jean Dupont',
//                 avatar: '/assets/images/avatar1.jpg'
//             }
//         },
//         {
//             id: 2,
//             title: 'Clés avec porte-clé étoile',
//             description: 'Trousseau de 3 clés avec un porte-clé en forme d\'étoile bleue.',
//             location: 'Métro Châtelet, Paris',
//             date: '14 juin 2023',
//             image: '/assets/images/keys.jpg',
//             category: 'keys',
//             type: 'found',
//             user: {
//                 name: 'Marie Dubois',
//                 avatar: '/assets/images/avatar2.jpg'
//             }
//         },
//         {
//             id: 3,
//             title: 'Lunettes de vue noires',
//             description: 'Lunettes de vue à monture noire de marque Ray-Ban. Perdues dans le centre commercial.',
//             location: 'Centre commercial Beaugrenelle, Paris',
//             date: '13 juin 2023',
//             image: '/assets/images/glasses.jpg',
//             category: 'accessories',
//             type: 'lost',
//             user: {
//                 name: 'Thomas Martin',
//                 avatar: '/assets/images/avatar3.jpg'
//             }
//         },
//         {
//             id: 4,
//             title: 'Téléphone Samsung',
//             description: 'Samsung Galaxy S21 noir avec coque transparente. Trouvé sur un banc.',
//             location: 'Gare de Lyon, Paris',
//             date: '12 juin 2023',
//             image: '/assets/images/phone.jpg',
//             category: 'electronics',
//             type: 'found',
//             user: {
//                 name: 'Sophie Leroy',
//                 avatar: '/assets/images/avatar4.jpg'
//             }
//         },
//         {
//             id: 5,
//             title: 'Sac à dos bleu',
//             description: 'Sac à dos Eastpak bleu marine avec un badge de l\'université sur le côté.',
//             location: 'Université Paris-Sorbonne, Paris',
//             date: '11 juin 2023',
//             image: '/assets/images/backpack.jpg',
//             category: 'bags',
//             type: 'lost',
//             user: {
//                 name: 'Lucas Bernard',
//                 avatar: '/assets/images/avatar5.jpg'
//             }
//         },
//         {
//             id: 6,
//             title: 'Montre en argent',
//             description: 'Montre en argent avec bracelet en cuir noir. Marque Fossil.',
//             location: 'Restaurant Le Petit Paris, Paris',
//             date: '10 juin 2023',
//             image: '/assets/images/watch.jpg',
//             category: 'jewelry',
//             type: 'found',
//             user: {
//                 name: 'Emma Petit',
//                 avatar: '/assets/images/avatar6.jpg'
//             }
//         }
//     ];
//
//     // Filter items based on search and filters
//     const filteredItems = items.filter(item => {
//         // Search query filter
//         const matchesSearch = searchQuery === '' ||
//             item.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
//             item.description.toLowerCase().includes(searchQuery.toLowerCase());
//
//         // Location filter
//         const matchesLocation = location === '' ||
//             item.location.toLowerCase().includes(location.toLowerCase());
//
//         // Category filter
//         const matchesCategory = selectedCategory === 'all' ||
//             item.category === selectedCategory;
//
//         // Type filter
//         const matchesType = selectedType === 'all' ||
//             item.type === selectedType;
//
//         // Date filter (simplified for demo)
//         const matchesDate = selectedDate === 'all';
//
//         return matchesSearch && matchesLocation && matchesCategory && matchesType && matchesDate;
//     });
//
//     // Handle search submit
//     const handleSearch = (e) => {
//         e.preventDefault();
//         // In a real app, this would trigger an API call
//         console.log('Searching for:', searchQuery, 'in', location);
//     };
//
//     // Reset filters
//     const resetFilters = () => {
//         setSelectedCategory('all');
//         setSelectedType('all');
//         setSelectedDate('all');
//     };
//
//     // Pagination
//     const itemsPerPage = 6;
//     const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
//     const paginatedItems = filteredItems.slice(
//         (currentPage - 1) * itemsPerPage,
//         currentPage * itemsPerPage
//     );
//
//
//
//     return (
//         <div className="search-page">
//             <Header
//                 title="Recherche"
//                 subtitle="Trouvez des objets perdus ou déclarés trouvés"
//             />
//
//             <div className="search-container">
//                 <form onSubmit={handleSearch} className="search-form">
//                     <div className="search-input-container">
//                         <FaSearch className="search-icon" />
//                         <input
//                             type="text"
//                             placeholder="Que cherchez-vous?"
//                             value={searchQuery}
//                             onChange={(e) => setSearchQuery(e.target.value)}
//                             className="search-input"
//                         />
//                     </div>
//                     <div className="location-input-container">
//                         <FaMapMarkerAlt className="location-icon" />
//                         <input
//                             type="text"
//                             placeholder="Localisation"
//                             value={location}
//                             onChange={(e) => setLocation(e.target.value)}
//                             className="location-input"
//                         />
//                     </div>
//                     <Button
//                         type="submit"
//                         className="search-button"
//                     >
//                         Rechercher
//                     </Button>
//                 </form>
//             </div>
//
//             <div className="search-results-container">
//                 <div className="search-sidebar">
//                     <div className="filter-header">
//                         <h3>Filtres</h3>
//                         {(selectedCategory !== 'all' || selectedType !== 'all' || selectedDate !== 'all') && (
//                             <button className="reset-filters" onClick={resetFilters}>
//                                 Réinitialiser
//                             </button>
//                         )}
//                     </div>
//
//                     <div className="filter-section">
//                         <h4 className="filter-title">
//                             <FaTag className="filter-icon" />
//                             Catégorie
//                         </h4>
//                         <ul className="filter-options">
//                             {categories.map(category => (
//                                 <li key={category.id}>
//                                     <label className="filter-option">
//                                         <input
//                                             type="radio"
//                                             name="category"
//                                             checked={selectedCategory === category.id}
//                                             onChange={() => setSelectedCategory(category.id)}
//                                         />
//                                         <span>{category.name}</span>
//                                         {category.id !== 'all' && (
//                                             <span className="count">
//                         {items.filter(item => item.category === category.id).length}
//                       </span>
//                                         )}
//                                     </label>
//                                 </li>
//                             ))}
//                         </ul>
//                     </div>
//
//                     <div className="filter-section">
//                         <h4 className="filter-title">Type</h4>
//                         <div className="filter-options">
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="type"
//                                     checked={selectedType === 'all'}
//                                     onChange={() => setSelectedType('all')}
//                                 />
//                                 <span>Tous</span>
//                             </label>
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="type"
//                                     checked={selectedType === 'lost'}
//                                     onChange={() => setSelectedType('lost')}
//                                 />
//                                 <span>Perdus</span>
//                                 <span className="count">
//                   {items.filter(item => item.type === 'lost').length}
//                 </span>
//                             </label>
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="type"
//                                     checked={selectedType === 'found'}
//                                     onChange={() => setSelectedType('found')}
//                                 />
//                                 <span>Trouvés</span>
//                                 <span className="count">
//                   {items.filter(item => item.type === 'found').length}
//                 </span>
//                             </label>
//                         </div>
//                     </div>
//
//                     <div className="filter-section">
//                         <h4 className="filter-title">
//                             <FaCalendarAlt className="filter-icon" />
//                             Date
//                         </h4>
//                         <div className="filter-options">
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="date"
//                                     checked={selectedDate === 'all'}
//                                     onChange={() => setSelectedDate('all')}
//                                 />
//                                 <span>Toutes les dates</span>
//                             </label>
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="date"
//                                     checked={selectedDate === 'today'}
//                                     onChange={() => setSelectedDate('today')}
//                                 />
//                                 <span>Aujourd'hui</span>
//                             </label>
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="date"
//                                     checked={selectedDate === 'week'}
//                                     onChange={() => setSelectedDate('week')}
//                                 />
//                                 <span>Cette semaine</span>
//                             </label>
//                             <label className="filter-option">
//                                 <input
//                                     type="radio"
//                                     name="date"
//                                     checked={selectedDate === 'month'}
//                                     onChange={() => setSelectedDate('month')}
//                                 />
//                                 <span>Ce mois-ci</span>
//                             </label>
//                         </div>
//                     </div>
//                 </div>
//
//                 <div className="search-results">
//                     <div className="results-header">
//                         <div className="results-count">
//                             {filteredItems.length} résultat{filteredItems.length !== 1 ? 's' : ''}
//                         </div>
//                         <div className="view-options">
//                             <button
//                                 className={`view-option ${viewMode === 'grid' ? 'active' : ''}`}
//                                 onClick={() => setViewMode('grid')}
//                                 aria-label="Vue en grille"
//                             >
//                                 <FaThLarge />
//                             </button>
//                             <button
//                                 className={`view-option ${viewMode === 'list' ? 'active' : ''}`}
//                                 onClick={() => setViewMode('list')}
//                                 aria-label="Vue en liste"
//                             >
//                                 <FaListUl />
//                             </button>
//                             <button
//                                 className="filter-toggle"
//                                 onClick={() => setFilterOpen(!filterOpen)}
//                                 aria-label="Filtres"
//                             >
//                                 <FaFilter />
//                                 <span>Filtres</span>
//                             </button>
//                         </div>
//                     </div>
//
//                     {filterOpen && (
//                         <div className="mobile-filters">
//                             <div className="mobile-filters-header">
//                                 <h3>Filtres</h3>
//                                 <button
//                                     className="close-filters"
//                                     onClick={() => setFilterOpen(false)}
//                                     aria-label="Fermer les filtres"
//                                 >
//                                     <FaTimes />
//                                 </button>
//                             </div>
//
//                             <div className="filter-section">
//                                 <h4 className="filter-title">Catégorie</h4>
//                                 <select
//                                     value={selectedCategory}
//                                     onChange={(e) => setSelectedCategory(e.target.value)}
//                                     className="filter-select"
//                                 >
//                                     {categories.map(category => (
//                                         <option key={category.id} value={category.id}>
//                                             {category.name}
//                                         </option>
//                                     ))}
//                                 </select>
//                             </div>
//
//                             <div className="filter-section">
//                                 <h4 className="filter-title">Type</h4>
//                                 <div className="filter-options">
//                                     <label className="filter-option">
//                                         <input
//                                             type="radio"
//                                             name="mobile-type"
//                                             checked={selectedType === 'all'}
//                                             onChange={() => setSelectedType('all')}
//                                         />
//                                         <span>Tous</span>
//                                     </label>
//                                     <label className="filter-option">
//                                         <input
//                                             type="radio"
//                                             name="mobile-type"
//                                             checked={selectedType === 'lost'}
//                                             onChange={() => setSelectedType('lost')}
//                                         />
//                                         <span>Perdus</span>
//                                     </label>
//                                     <label className="filter-option">
//                                         <input
//                                             type="radio"
//                                             name="mobile-type"
//                                             checked={selectedType === 'found'}
//                                             onChange={() => setSelectedType('found')}
//                                         />
//                                         <span>Trouvés</span>
//                                     </label>
//                                 </div>
//                             </div>
//
//                             <div className="filter-section">
//                                 <h4 className="filter-title">Date</h4>
//                                 <select
//                                     value={selectedDate}
//                                     onChange={(e) => setSelectedDate(e.target.value)}
//                                     className="filter-select"
//                                 >
//                                     <option value="all">Toutes les dates</option>
//                                     <option value="today">Aujourd'hui</option>
//                                     <option value="week">Cette semaine</option>
//                                     <option value="month">Ce mois-ci</option>
//                                 </select>
//                             </div>
//
//                             <div className="filter-actions">
//                                 <Button
//                                     onClick={() => {
//                                         resetFilters();
//                                         setFilterOpen(false);
//                                     }}
//                                     variant="outline"
//                                     className="reset-button"
//                                 >
//                                     Réinitialiser
//                                 </Button>
//                                 <Button
//                                     onClick={() => setFilterOpen(false)}
//                                     className="apply-button"
//                                 >
//                                     Appliquer
//                                 </Button>
//                             </div>
//                         </div>
//                     )}
//
//                     {filteredItems.length === 0 ? (
//                         <div className="no-results">
//                             <div className="no-results-icon">
//                                 <FaSearch />
//                             </div>
//                             <h3>Aucun résultat trouvé</h3>
//                             <p>Essayez de modifier vos critères de recherche ou de réinitialiser les filtres.</p>
//                             <Button
//                                 onClick={resetFilters}
//                                 variant="outline"
//                             >
//                                 Réinitialiser les filtres
//                             </Button>
//                         </div>
//                     ) : (
//                         <div className={`items-container ${viewMode}`}>
//                             {paginatedItems.map(item => (
//                                 <Card
//                                     key={item.id}
//                                     className={`item-card ${viewMode} ${item.type}`}
//                                     hoverable
//                                     onClick={() => window.location.href = `/item/${item.id}`}
//                                 >
//                                     <div className="item-image">
//                                         <img
//                                             src={item.image || "/placeholder.jpg"}
//                                             alt={item.title}
//                                             onError={(e) => {
//                                                 e.target.src = '/assets/images/placeholder.jpg';
//                                             }}
//                                         />
//                                         <span className={`item-badge ${item.type}`}>
//                       {item.type === 'lost' ? 'Perdu' : 'Trouvé'}
//                     </span>
//                                     </div>
//                                     <div className="item-content">
//                                         <h3 className="item-title">{item.title}</h3>
//                                         {viewMode === 'list' && (
//                                             <p className="item-description">{item.description}</p>
//                                         )}
//                                         <div className="item-details">
//                                             <div className="item-location">
//                                                 <FaMapMarkerAlt />
//                                                 <span>{item.location}</span>
//                                             </div>
//                                             <div className="item-date">
//                                                 <FaCalendarAlt />
//                                                 <span>{item.date}</span>
//                                             </div>
//                                         </div>
//                                         <div className="item-user">
//                                             <img
//                                                 src={item.user.avatar || "/placeholder.jpg"}
//                                                 alt={item.user.name}
//                                                 className="user-avatar"
//                                                 onError={(e) => {
//                                                     e.target.src = '/assets/images/default-avatar.png';
//                                                 }}
//                                             />
//                                             <span>{item.user.name}</span>
//                                         </div>
//                                     </div>
//                                 </Card>
//                             ))}
//                         </div>
//                     )}
//
//                     {filteredItems.length > 0 && (
//                         <div className="pagination">
//                             <button
//                                 className="pagination-button"
//                                 disabled={currentPage === 1}
//                                 onClick={() => setCurrentPage(currentPage - 1)}
//                             >
//                                 Précédent
//                             </button>
//                             <div className="pagination-pages">
//                                 {[...Array(totalPages)].map((_, i) => (
//                                     <button
//                                         key={i}
//                                         className={`pagination-page ${currentPage === i + 1 ? 'active' : ''}`}
//                                         onClick={() => setCurrentPage(i + 1)}
//                                     >
//                                         {i + 1}
//                                     </button>
//                                 ))}
//                             </div>
//                             <button
//                                 className="pagination-button"
//                                 disabled={currentPage === totalPages}
//                                 onClick={() => setCurrentPage(currentPage + 1)}
//                             >
//                                 Suivant
//                             </button>
//                         </div>
//                     )}
//                 </div>
//             </div>
//         </div>
//     );
// };
//
// export default SearchPage;