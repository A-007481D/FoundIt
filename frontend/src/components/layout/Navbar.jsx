import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { FaSearch, FaBell, FaUser, FaSignOutAlt, FaCog, FaPlus, FaBars, FaTimes } from 'react-icons/fa';
import '../../styles/layout.css';

const Navbar = () => {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isProfileOpen, setIsProfileOpen] = useState(false);
    const [isNotificationsOpen, setIsNotificationsOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const location = useLocation();

    // Mock user data - in a real app, this would come from authentication context
    const user = {
        name: 'Jean Dupont',
        avatar: '/assets/images/default-avatar.png',
        notifications: 3
    };





    const handleSearch = (e) => {
        e.preventDefault();
        if (searchQuery.trim()) {
            window.location.href = `/search?q=${encodeURIComponent(searchQuery)}`;
        }
    };

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
        // Close other dropdowns
        setIsProfileOpen(false);
        setIsNotificationsOpen(false);
    };

    const toggleProfile = () => {
        setIsProfileOpen(!isProfileOpen);
        // Close other dropdowns
        setIsNotificationsOpen(false);
    };

    const toggleNotifications = () => {
        setIsNotificationsOpen(!isNotificationsOpen);
        // Close other dropdowns
        setIsProfileOpen(false);
    };

    // Mock notifications
    const notifications = [
        { id: 1, text: 'Votre objet a été trouvé!', time: '5 min', isNew: true },
        { id: 2, text: 'Nouveau message de Marie', time: '1 heure', isNew: true },
        { id: 3, text: 'Rappel: Mise à jour de votre profil', time: '3 heures', isNew: true },
        { id: 4, text: 'Bienvenue sur FoundIt!', time: '1 jour', isNew: false }
    ];

    return (
        <header className={`navbar ${isScrolled ? 'scrolled' : ''}`}>
            <div className="navbar-container">
                <div className="navbar-left">
                    <Link to="/" className="navbar-logo">
                        {/*<img src="/assets/images/logo.png" alt="FoundIt Logo" />*/}
                        <span className="logo-text">FoundIt!</span>
                    </Link>

                    <nav className={`navbar-nav ${isMenuOpen ? 'active' : ''}`}>
                        <ul className="nav-links">
                            <li className={location.pathname === '/' ? 'active' : ''}>
                                <Link to="/">Accueil</Link>
                            </li>
                            <li className={location.pathname === '/dashboard' ? 'active' : ''}>
                                <Link to="/dashboard">Tableau de bord</Link>
                            </li>
                            <li className={location.pathname === '/matches' ? 'active' : ''}>
                                <Link to="/matches">Correspondances</Link>
                            </li>
                            <li className={location.pathname === '/messages' ? 'active' : ''}>
                                <Link to="/messages">Messages</Link>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div className="navbar-right">
                    <form onSubmit={handleSearch} className="search-form">
                        <input
                            type="text"
                            placeholder="Rechercher..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="search-input"
                        />
                        <button type="submit" className="search-button">
                            <FaSearch />
                        </button>
                    </form>

                    <Link to="/dashboard/new-item" className="new-item-button">
                        <FaPlus />
                        <span>Déclarer</span>
                    </Link>

                    <div className="notifications-dropdown">
                        <button
                            className={`notifications-button ${isNotificationsOpen ? 'active' : ''}`}
                            onClick={toggleNotifications}
                            aria-label="Notifications"
                        >
                            <FaBell />
                            {user.notifications > 0 && (
                                <span className="notification-badge">{user.notifications}</span>
                            )}
                        </button>

                        {isNotificationsOpen && (
                            <div className="dropdown-menu notifications-menu">
                                <div className="dropdown-header">
                                    <h3>Notifications</h3>
                                    <button className="mark-all-read">Tout marquer comme lu</button>
                                </div>
                                <ul className="notifications-list">
                                    {notifications.map(notification => (
                                        <li key={notification.id} className={notification.isNew ? 'new' : ''}>
                                            <div className="notification-content">
                                                <p>{notification.text}</p>
                                                <span className="notification-time">{notification.time}</span>
                                            </div>
                                            {notification.isNew && <span className="notification-indicator" />}
                                        </li>
                                    ))}
                                </ul>
                                <div className="dropdown-footer">
                                    <Link to="/notifications">Voir toutes les notifications</Link>
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="profile-dropdown">
                        <button
                            className={`profile-button ${isProfileOpen ? 'active' : ''}`}
                            onClick={toggleProfile}
                            aria-label="Profil"
                        >
                            <img
                                src={user.avatar || '/assets/images/default-avatar.png'}
                                alt={user.name}
                                // TODO: BUG IS HERE
                                className="avatar"
                                onError={(e) => {
                                    e.target.src = 'https://placehold.co/400';
                                }}
                            />
                        </button>

                        {isProfileOpen && (
                            <div className="dropdown-menu profile-menu">
                                <div className="dropdown-header">
                                    <img
                                        src={user.avatar || '/assets/images/default-avatar.png'}
                                        alt={user.name}
                                        className="avatar-large"
                                        onError={(e) => {
                                            e.target.src = 'https://placehold.co/400';
                                        }}
                                    />
                                    <div>
                                        <h3>{user.name}</h3>
                                        <p>Membre</p>
                                    </div>
                                </div>
                                <ul className="profile-links">
                                    <li>
                                        <Link to="/profile">
                                            <FaUser />
                                            <span>Mon profil</span>
                                        </Link>
                                    </li>
                                    <li>
                                        <Link to="/settings">
                                            <FaCog />
                                            <span>Paramètres</span>
                                        </Link>
                                    </li>
                                    <li>
                                        <button onClick={() => window.location.href = '/login'}>
                                            <FaSignOutAlt />
                                            <span>Déconnexion</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        )}
                    </div>

                    <button
                        className="mobile-menu-button"
                        onClick={toggleMenu}
                        aria-label={isMenuOpen ? 'Fermer le menu' : 'Ouvrir le menu'}
                    >
                        {isMenuOpen ? <FaTimes /> : <FaBars />}
                    </button>
                </div>
            </div>
        </header>
    );
};

export default Navbar;