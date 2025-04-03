import React, { useState } from 'react';
import { FaUsers, FaClipboardList, FaExclamationTriangle, FaChartLine, FaSearch, FaFilter, FaEllipsisV, FaCheck, FaTimes, FaEdit, FaTrash } from 'react-icons/fa';
import Header from '../components/common/Header';
import Card from '../components/common/Card';
import Button from '../components/common/Button';
import '../styles/pages/admin-dashboard.css';

const AdminDashboard = () => {
    const [activeTab, setActiveTab] = useState('overview');
    const [selectedItems, setSelectedItems] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');
    const [filterOpen, setFilterOpen] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);

    // Mock data
    const stats = [
        { id: 1, label: 'Utilisateurs', value: '5,432', icon: <FaUsers />, change: '+12%' },
        { id: 2, label: 'Objets déclarés', value: '15,789', icon: <FaClipboardList />, change: '+8%' },
        { id: 3, label: 'Signalements', value: '124', icon: <FaExclamationTriangle />, change: '-5%' },
        { id: 4, label: 'Taux de succès', value: '85%', icon: <FaChartLine />, change: '+3%' }
    ];

    const recentUsers = [
        { id: 1, name: 'Marie Dubois', email: 'marie.dubois@example.com', date: '15/06/2023', status: 'active' },
        { id: 2, name: 'Thomas Martin', email: 'thomas.martin@example.com', date: '14/06/2023', status: 'active' },
        { id: 3, name: 'Sophie Leroy', email: 'sophie.leroy@example.com', date: '13/06/2023', status: 'pending' },
        { id: 4, name: 'Lucas Bernard', email: 'lucas.bernard@example.com', date: '12/06/2023', status: 'active' },
        { id: 5, name: 'Emma Petit', email: 'emma.petit@example.com', date: '11/06/2023', status: 'inactive' }
    ];

    const recentItems = [
        { id: 1, title: 'Portefeuille marron', type: 'lost', user: 'Marie Dubois', date: '15/06/2023', status: 'active' },
        { id: 2, title: 'Clés avec porte-clé étoile', type: 'found', user: 'Thomas Martin', date: '14/06/2023', status: 'active' },
        { id: 3, title: 'Lunettes de vue noires', type: 'lost', user: 'Sophie Leroy', date: '13/06/2023', status: 'resolved' },
        { id: 4, title: 'Téléphone Samsung', type: 'found', user: 'Lucas Bernard', date: '12/06/2023', status: 'active' },
        { id: 5, title: 'Sac à dos bleu', type: 'lost', user: 'Emma Petit', date: '11/06/2023', status: 'expired' }
    ];

    const reports = [
        { id: 1, type: 'user', target: 'Jean Dupont', reporter: 'Marie Dubois', reason: 'Comportement inapproprié', date: '15/06/2023', status: 'pending' },
        { id: 2, type: 'item', target: 'Téléphone iPhone', reporter: 'Thomas Martin', reason: 'Information incorrecte', date: '14/06/2023', status: 'pending' },
        { id: 3, type: 'message', target: 'Conversation #12345', reporter: 'Sophie Leroy', reason: 'Spam', date: '13/06/2023', status: 'resolved' },
        { id: 4, type: 'user', target: 'Lucas Bernard', reporter: 'Emma Petit', reason: 'Harcèlement', date: '12/06/2023', status: 'pending' },
        { id: 5, type: 'item', target: 'Ordinateur portable', reporter: 'Paul Roux', reason: 'Fraude', date: '11/06/2023', status: 'resolved' }
    ];

    // Handle checkbox selection
    const handleSelectItem = (id) => {
        if (selectedItems.includes(id)) {
            setSelectedItems(selectedItems.filter(item => item !== id));
        } else {
            setSelectedItems([...selectedItems, id]);
        }
    };

    // Handle select all
    const handleSelectAll = (items) => {
        if (selectedItems.length === items.length) {
            setSelectedItems([]);
        } else {
            setSelectedItems(items.map(item => item.id));
        }
    };

    // Pagination
    const itemsPerPage = 5;
    const totalPages = 10; // Mock total pages

    const renderPagination = () => {
        return (
            <div className="pagination">
                <button
                    className="pagination-button"
                    disabled={currentPage === 1}
                    onClick={() => setCurrentPage(currentPage - 1)}
                >
                    Précédent
                </button>
                <div className="pagination-pages">
                    {[...Array(totalPages)].map((_, i) => (
                        <button
                            key={i}
                            className={`pagination-page ${currentPage === i + 1 ? 'active' : ''}`}
                            onClick={() => setCurrentPage(i + 1)}
                        >
                            {i + 1}
                        </button>
                    )).slice(Math.max(0, currentPage - 3), Math.min(totalPages, currentPage + 2))}
                </div>
                <button
                    className="pagination-button"
                    disabled={currentPage === totalPages}
                    onClick={() => setCurrentPage(currentPage + 1)}
                >
                    Suivant
                </button>
            </div>
        );
    };

    return (
        <div className="admin-dashboard">
            <Header
                title="Administration"
                subtitle="Gérez les utilisateurs, les objets et les signalements"
                actions={
                    <div className="header-actions">
                        <div className="search-container">
                            <input
                                type="text"
                                placeholder="Rechercher..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="search-input"
                            />
                            <FaSearch className="search-icon" />
                        </div>
                        <Button
                            className="filter-button"
                            onClick={() => setFilterOpen(!filterOpen)}
                            icon={<FaFilter />}
                            variant="outline"
                        >
                            Filtres
                        </Button>
                    </div>
                }
            />

            {/* Tabs */}
            <div className="admin-tabs">
                <button
                    className={`tab-button ${activeTab === 'overview' ? 'active' : ''}`}
                    onClick={() => setActiveTab('overview')}
                >
                    Vue d'ensemble
                </button>
                <button
                    className={`tab-button ${activeTab === 'users' ? 'active' : ''}`}
                    onClick={() => setActiveTab('users')}
                >
                    Utilisateurs
                </button>
                <button
                    className={`tab-button ${activeTab === 'items' ? 'active' : ''}`}
                    onClick={() => setActiveTab('items')}
                >
                    Objets
                </button>
                <button
                    className={`tab-button ${activeTab === 'reports' ? 'active' : ''}`}
                    onClick={() => setActiveTab('reports')}
                >
                    Signalements
                </button>
            </div>

            {/* Overview Tab */}
            {activeTab === 'overview' && (
                <div className="tab-content">
                    <div className="stats-grid">
                        {stats.map(stat => (
                            <Card key={stat.id} className="stat-card">
                                <div className="stat-icon">{stat.icon}</div>
                                <div className="stat-content">
                                    <h3 className="stat-value">{stat.value}</h3>
                                    <p className="stat-label">{stat.label}</p>
                                </div>
                                <div className={`stat-change ${stat.change.startsWith('+') ? 'positive' : 'negative'}`}>
                                    {stat.change}
                                </div>
                            </Card>
                        ))}
                    </div>

                    <div className="overview-grid">
                        <Card className="overview-card" title="Utilisateurs récents">
                            <div className="table-container">
                                <table className="admin-table">
                                    <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {recentUsers.slice(0, 3).map(user => (
                                        <tr key={user.id}>
                                            <td>{user.name}</td>
                                            <td>{user.email}</td>
                                            <td>{user.date}</td>
                                            <td>
                          <span className={`status-badge ${user.status}`}>
                            {user.status === 'active' ? 'Actif' :
                                user.status === 'pending' ? 'En attente' : 'Inactif'}
                          </span>
                                            </td>
                                            <td>
                                                <div className="table-actions">
                                                    <button className="action-button" aria-label="Voir">
                                                        <FaEdit />
                                                    </button>
                                                    <button className="action-button" aria-label="Supprimer">
                                                        <FaTrash />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    </tbody>
                                </table>
                            </div>
                            <div className="card-footer">
                                <button className="view-all-button" onClick={() => setActiveTab('users')}>
                                    Voir tous les utilisateurs
                                </button>
                            </div>
                        </Card>

                        <Card className="overview-card" title="Objets récents">
                            <div className="table-container">
                                <table className="admin-table">
                                    <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Utilisateur</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {recentItems.slice(0, 3).map(item => (
                                        <tr key={item.id}>
                                            <td>{item.title}</td>
                                            <td>
                          <span className={`type-badge ${item.type}`}>
                            {item.type === 'lost' ? 'Perdu' : 'Trouvé'}
                          </span>
                                            </td>
                                            <td>{item.user}</td>
                                            <td>
                          <span className={`status-badge ${item.status}`}>
                            {item.status === 'active' ? 'Actif' :
                                item.status === 'resolved' ? 'Résolu' : 'Expiré'}
                          </span>
                                            </td>
                                            <td>
                                                <div className="table-actions">
                                                    <button className="action-button" aria-label="Voir">
                                                        <FaEdit />
                                                    </button>
                                                    <button className="action-button" aria-label="Supprimer">
                                                        <FaTrash />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    </tbody>
                                </table>
                            </div>
                            <div className="card-footer">
                                <button className="view-all-button" onClick={() => setActiveTab('items')}>
                                    Voir tous les objets
                                </button>
                            </div>
                        </Card>
                    </div>

                    <Card className="overview-card" title="Signalements récents">
                        <div className="table-container">
                            <table className="admin-table">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Cible</th>
                                    <th>Raison</th>
                                    <th>Signalé par</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {reports.slice(0, 3).map(report => (
                                    <tr key={report.id}>
                                        <td>
                        <span className={`type-badge ${report.type}`}>
                          {report.type === 'user' ? 'Utilisateur' :
                              report.type === 'item' ? 'Objet' : 'Message'}
                        </span>
                                        </td>
                                        <td>{report.target}</td>
                                        <td>{report.reason}</td>
                                        <td>{report.reporter}</td>
                                        <td>{report.date}</td>
                                        <td>
                        <span className={`status-badge ${report.status}`}>
                          {report.status === 'pending' ? 'En attente' : 'Résolu'}
                        </span>
                                        </td>
                                        <td>
                                            <div className="table-actions">
                                                <button className="action-button" aria-label="Approuver">
                                                    <FaCheck />
                                                </button>
                                                <button className="action-button" aria-label="Rejeter">
                                                    <FaTimes />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                        <div className="card-footer">
                            <button className="view-all-button" onClick={() => setActiveTab('reports')}>
                                Voir tous les signalements
                            </button>
                        </div>
                    </Card>
                </div>
            )}

            {/* Users Tab */}
            {activeTab === 'users' && (
                <div className="tab-content">
                    <Card className="table-card">
                        <div className="table-header">
                            <div className="bulk-actions">
                                <input
                                    type="checkbox"
                                    checked={selectedItems.length === recentUsers.length}
                                    onChange={() => handleSelectAll(recentUsers)}
                                />
                                <span className="selected-count">
                  {selectedItems.length} sélectionné(s)
                </span>
                                {selectedItems.length > 0 && (
                                    <div className="bulk-buttons">
                                        <Button variant="outline" size="sm">Activer</Button>
                                        <Button variant="outline" size="sm">Désactiver</Button>
                                        <Button variant="outline" size="sm" className="delete">Supprimer</Button>
                                    </div>
                                )}
                            </div>
                            <div className="table-filters">
                                <select className="filter-select">
                                    <option value="all">Tous les statuts</option>
                                    <option value="active">Actif</option>
                                    <option value="pending">En attente</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                                <select className="filter-select">
                                    <option value="newest">Plus récent</option>
                                    <option value="oldest">Plus ancien</option>
                                    <option value="name">Nom</option>
                                </select>
                            </div>
                        </div>

                        <div className="table-container">
                            <table className="admin-table">
                                <thead>
                                <tr>
                                    <th>
                                        <input
                                            type="checkbox"
                                            checked={selectedItems.length === recentUsers.length}
                                            onChange={() => handleSelectAll(recentUsers)}
                                        />
                                    </th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {recentUsers.map(user => (
                                    <tr key={user.id}>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={selectedItems.includes(user.id)}
                                                onChange={() => handleSelectItem(user.id)}
                                            />
                                        </td>
                                        <td>{user.name}</td>
                                        <td>{user.email}</td>
                                        <td>{user.date}</td>
                                        <td>
                        <span className={`status-badge ${user.status}`}>
                          {user.status === 'active' ? 'Actif' :
                              user.status === 'pending' ? 'En attente' : 'Inactif'}
                        </span>
                                        </td>
                                        <td>
                                            <div className="table-actions">
                                                <button className="action-button" aria-label="Voir">
                                                    <FaEdit />
                                                </button>
                                                <button className="action-button" aria-label="Supprimer">
                                                    <FaTrash />
                                                </button>
                                                <button className="action-button" aria-label="Plus">
                                                    <FaEllipsisV />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>

                        {renderPagination()}
                    </Card>
                </div>
            )}

            {/* Items Tab */}
            {activeTab === 'items' && (
                <div className="tab-content">
                    <Card className="table-card">
                        <div className="table-header">
                            <div className="bulk-actions">
                                <input
                                    type="checkbox"
                                    checked={selectedItems.length === recentItems.length}
                                    onChange={() => handleSelectAll(recentItems)}
                                />
                                <span className="selected-count">
                  {selectedItems.length} sélectionné(s)
                </span>
                                {selectedItems.length > 0 && (
                                    <div className="bulk-buttons">
                                        <Button variant="outline" size="sm">Approuver</Button>
                                        <Button variant="outline" size="sm">Marquer comme résolu</Button>
                                        <Button variant="outline" size="sm" className="delete">Supprimer</Button>
                                    </div>
                                )}
                            </div>
                            <div className="table-filters">
                                <select className="filter-select">
                                    <option value="all">Tous les types</option>
                                    <option value="lost">Perdu</option>
                                    <option value="found">Trouvé</option>
                                </select>
                                <select className="filter-select">
                                    <option value="all">Tous les statuts</option>
                                    <option value="active">Actif</option>
                                    <option value="resolved">Résolu</option>
                                    <option value="expired">Expiré</option>
                                </select>
                                <select className="filter-select">
                                    <option value="newest">Plus récent</option>
                                    <option value="oldest">Plus ancien</option>
                                </select>
                            </div>
                        </div>

                        <div className="table-container">
                            <table className="admin-table">
                                <thead>
                                <tr>
                                    <th>
                                        <input
                                            type="checkbox"
                                            checked={selectedItems.length === recentItems.length}
                                            onChange={() => handleSelectAll(recentItems)}
                                        />
                                    </th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {recentItems.map(item => (
                                    <tr key={item.id}>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={selectedItems.includes(item.id)}
                                                onChange={() => handleSelectItem(item.id)}
                                            />
                                        </td>
                                        <td>{item.title}</td>
                                        <td>
                        <span className={`type-badge ${item.type}`}>
                          {item.type === 'lost' ? 'Perdu' : 'Trouvé'}
                        </span>
                                        </td>
                                        <td>{item.user}</td>
                                        <td>{item.date}</td>
                                        <td>
                        <span className={`status-badge ${item.status}`}>
                          {item.status === 'active' ? 'Actif' :
                              item.status === 'resolved' ? 'Résolu' : 'Expiré'}
                        </span>
                                        </td>
                                        <td>
                                            <div className="table-actions">
                                                <button className="action-button" aria-label="Voir">
                                                    <FaEdit />
                                                </button>
                                                <button className="action-button" aria-label="Supprimer">
                                                    <FaTrash />
                                                </button>
                                                <button className="action-button" aria-label="Plus">
                                                    <FaEllipsisV />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>

                        {renderPagination()}
                    </Card>
                </div>
            )}

            {/* Reports Tab */}
            {activeTab === 'reports' && (
                <div className="tab-content">
                    <Card className="table-card">
                        <div className="table-header">
                            <div className="bulk-actions">
                                <input
                                    type="checkbox"
                                    checked={selectedItems.length === reports.length}
                                    onChange={() => handleSelectAll(reports)}
                                />
                                <span className="selected-count">
                  {selectedItems.length} sélectionné(s)
                </span>
                                {selectedItems.length > 0 && (
                                    <div className="bulk-buttons">
                                        <Button variant="outline" size="sm">Approuver</Button>
                                        <Button variant="outline" size="sm">Rejeter</Button>
                                        <Button variant="outline" size="sm" className="delete">Supprimer</Button>
                                    </div>
                                )}
                            </div>
                            <div className="table-filters">
                                <select className="filter-select">
                                    <option value="all">Tous les types</option>
                                    <option value="user">Utilisateur</option>
                                    <option value="item">Objet</option>
                                    <option value="message">Message</option>
                                </select>
                                <select className="filter-select">
                                    <option value="all">Tous les statuts</option>
                                    <option value="pending">En attente</option>
                                    <option value="resolved">Résolu</option>
                                </select>
                                <select className="filter-select">
                                    <option value="newest">Plus récent</option>
                                    <option value="oldest">Plus ancien</option>
                                </select>
                            </div>
                        </div>

                        <div className="table-container">
                            <table className="admin-table">
                                <thead>
                                <tr>
                                    <th>
                                        <input
                                            type="checkbox"
                                            checked={selectedItems.length === reports.length}
                                            onChange={() => handleSelectAll(reports)}
                                        />
                                    </th>
                                    <th>Type</th>
                                    <th>Cible</th>
                                    <th>Raison</th>
                                    <th>Signalé par</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {reports.map(report => (
                                    <tr key={report.id}>
                                        <td>
                                            <input
                                                type="checkbox"
                                                checked={selectedItems.includes(report.id)}
                                                onChange={() => handleSelectItem(report.id)}
                                            />
                                        </td>
                                        <td>
                        <span className={`type-badge ${report.type}`}>
                          {report.type === 'user' ? 'Utilisateur' :
                              report.type === 'item' ? 'Objet' : 'Message'}
                        </span>
                                        </td>
                                        <td>{report.target}</td>
                                        <td>{report.reason}</td>
                                        <td>{report.reporter}</td>
                                        <td>{report.date}</td>
                                        <td>
                        <span className={`status-badge ${report.status}`}>
                          {report.status === 'pending' ? 'En attente' : 'Résolu'}
                        </span>
                                        </td>
                                        <td>
                                            <div className="table-actions">
                                                <button className="action-button" aria-label="Approuver">
                                                    <FaCheck />
                                                </button>
                                                <button className="action-button" aria-label="Rejeter">
                                                    <FaTimes />
                                                </button>
                                                <button className="action-button" aria-label="Plus">
                                                    <FaEllipsisV />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>

                        {renderPagination()}
                    </Card>
                </div>
            )}
        </div>
    );
};

export default AdminDashboard;