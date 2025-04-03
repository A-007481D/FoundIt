import React from 'react';
import { Link } from 'react-router-dom';
import { FaSearch, FaMapMarkerAlt, FaCheckCircle, FaShieldAlt, FaUsers, FaArrowRight } from 'react-icons/fa';
import Header from '../components/common/Header';
import Button from '../components/common/Button';
import Card from '../components/common/Card';
import '../styles/pages/home.css';

const HomePage = () => {
    // Mock statistics
    const stats = [
        { id: 1, label: 'Objets retrouvés', value: '15,000+' },
        { id: 2, label: 'Utilisateurs actifs', value: '50,000+' },
        { id: 3, label: 'Villes couvertes', value: '200+' },
        { id: 4, label: 'Taux de succès', value: '85%' }
    ];

    // Mock testimonials
    const testimonials = [
        {
            id: 1,
            name: 'Marie Dubois',
            avatar: 'https://placehold.co/400',
            text: 'Grâce à FoundIt, j\'ai retrouvé mon portefeuille perdu en moins de 24 heures! Le processus était simple et l\'équipe très réactive.',
            rating: 5
        },
        {
            id: 2,
            name: 'Thomas Martin',
            avatar: '/assets/images/testimonial2.jpg',
            text: 'J\'ai trouvé un téléphone dans le parc et j\'ai pu le rendre à son propriétaire via FoundIt. Une plateforme vraiment utile!',
            rating: 5
        },
        {
            id: 3,
            name: 'Sophie Leroy',
            avatar: '/assets/images/testimonial3.jpg',
            text: 'Après avoir perdu mes clés, je les ai déclarées sur FoundIt. Quelqu\'un les avait trouvées et m\'a contactée. Service incroyable!',
            rating: 4
        }
    ];

    // Mock recent items
    const recentItems = [
        {
            id: 1,
            type: 'lost',
            title: 'Portefeuille marron',
            location: 'Parc des Buttes-Chaumont, Paris',
            date: '15 juin 2023',
            image: '/assets/images/wallet.jpg',
            category: 'Accessoires'
        },
        {
            id: 2,
            type: 'found',
            title: 'Clés avec porte-clé étoile',
            location: 'Métro Châtelet, Paris',
            date: '14 juin 2023',
            image: '/assets/images/keys.jpg',
            category: 'Clés'
        },
        {
            id: 3,
            type: 'lost',
            title: 'Lunettes de vue noires',
            location: 'Centre commercial Beaugrenelle, Paris',
            date: '13 juin 2023',
            image: '/assets/images/glasses.jpg',
            category: 'Accessoires'
        },
        {
            id: 4,
            type: 'found',
            title: 'Téléphone Samsung',
            location: 'Gare de Lyon, Paris',
            date: '12 juin 2023',
            image: '/assets/images/phone.jpg',
            category: 'Électronique'
        }
    ];

    return (
        <div className="home-page">
            {/* Hero Section */}
            <section className="hero-section">
                <div className="hero-content">
                    <h1 className="hero-title">Retrouvez vos objets perdus</h1>
                    <p className="hero-subtitle">
                        FoundIt! vous aide à retrouver vos objets perdus et à rendre ceux que vous avez trouvés.
                    </p>
                    <div className="hero-actions">
                        <Link to="/dashboard/new-item" className="hero-button lost">
                            J'ai perdu un objet
                        </Link>
                        <Link to="/dashboard/new-item" className="hero-button found">
                            J'ai trouvé un objet
                        </Link>
                    </div>
                    <div className="hero-search">
                        <form className="search-form">
                            <div className="search-input-container">
                                <FaSearch className="search-icon" />
                                <input
                                    type="text"
                                    placeholder="Rechercher un objet perdu..."
                                    className="search-input"
                                />
                            </div>
                            <div className="search-location">
                                <FaMapMarkerAlt className="location-icon" />
                                <input
                                    type="text"
                                    placeholder="Localisation"
                                    className="location-input"
                                />
                            </div>
                            <Button type="submit" className="search-button">
                                Rechercher
                            </Button>
                        </form>
                    </div>
                </div>
                <div className="hero-image">
                    <img src="https://placehold.co/400" alt="FoundIt Hero" />
                </div>
            </section>

            {/* Stats Section */}
            <section className="stats-section">
                <div className="stats-container">
                    {stats.map(stat => (
                        <div key={stat.id} className="stat-item">
                            <h3 className="stat-value">{stat.value}</h3>
                            <p className="stat-label">{stat.label}</p>
                        </div>
                    ))}
                </div>
            </section>

            {/* How It Works Section */}
            <section className="how-it-works-section">
                <Header
                    title="Comment ça marche"
                    subtitle="Un processus simple pour retrouver vos objets perdus"
                    size="md"
                />
                <div className="steps-container">
                    <div className="step">
                        <div className="step-icon">
                            <FaSearch />
                        </div>
                        <h3 className="step-title">Déclarez</h3>
                        <p className="step-description">
                            Déclarez votre objet perdu ou trouvé avec une description détaillée et des photos.
                        </p>
                    </div>
                    <div className="step">
                        <div className="step-icon">
                            <FaCheckCircle />
                        </div>
                        <h3 className="step-title">Correspondance</h3>
                        <p className="step-description">
                            Notre algorithme trouve des correspondances potentielles entre objets perdus et trouvés.
                        </p>
                    </div>
                    <div className="step">
                        <div className="step-icon">
                            <FaUsers />
                        </div>
                        <h3 className="step-title">Connectez</h3>
                        <p className="step-description">
                            Entrez en contact avec la personne qui a trouvé ou perdu l'objet via notre messagerie sécurisée.
                        </p>
                    </div>
                    <div className="step">
                        <div className="step-icon">
                            <FaShieldAlt />
                        </div>
                        <h3 className="step-title">Récupérez</h3>
                        <p className="step-description">
                            Organisez la récupération de l'objet en toute sécurité et avec confiance.
                        </p>
                    </div>
                </div>
                <div className="learn-more">
                    <Link to="/how-it-works" className="learn-more-link">
                        En savoir plus <FaArrowRight />
                    </Link>
                </div>
            </section>

            {/* Recent Items Section */}
            <section className="recent-items-section">
                <Header
                    title="Objets récents"
                    subtitle="Découvrez les derniers objets perdus et trouvés"
                    actions={
                        <Link to="/search" className="view-all-link">
                            Voir tout <FaArrowRight />
                        </Link>
                    }
                    size="md"
                />
                <div className="items-grid">
                    {recentItems.map(item => (
                        <Card
                            key={item.id}
                            className={`item-card ${item.type}`}
                            hoverable
                            onClick={() => window.location.href = `/item/${item.id}`}
                        >
                            <div className="item-image">
                                <img src={item.image || "/placeholder.jpg"} alt={item.title} />
                                <span className={`item-badge ${item.type}`}>
                  {item.type === 'lost' ? 'Perdu' : 'Trouvé'}
                </span>
                            </div>
                            <div className="item-content">
                                <h3 className="item-title">{item.title}</h3>
                                <div className="item-details">
                                    <div className="item-location">
                                        <FaMapMarkerAlt />
                                        <span>{item.location}</span>
                                    </div>
                                    <div className="item-date">
                                        <span>{item.date}</span>
                                    </div>
                                </div>
                                <div className="item-category">
                                    <span>{item.category}</span>
                                </div>
                            </div>
                        </Card>
                    ))}
                </div>
                <div className="items-actions">
                    <Link to="/dashboard/new-item" className="declare-button">
                        Déclarer un objet
                    </Link>
                </div>
            </section>

            {/* Testimonials Section */}
            <section className="testimonials-section">
                <Header
                    title="Témoignages"
                    subtitle="Ce que disent nos utilisateurs"
                    size="md"
                />
                <div className="testimonials-container">
                    {testimonials.map(testimonial => (
                        <Card key={testimonial.id} className="testimonial-card">
                            <div className="testimonial-rating">
                                {[...Array(5)].map((_, i) => (
                                    <span
                                        key={i}
                                        className={`star ${i < testimonial.rating ? 'filled' : ''}`}
                                    >
                    ★
                  </span>
                                ))}
                            </div>
                            <p className="testimonial-text">"{testimonial.text}"</p>
                            <div className="testimonial-author">
                                <img
                                    src={testimonial.avatar || "/placeholder.jpg"}
                                    alt={testimonial.name}
                                    className="author-avatar"
                                    // TODO: BUG IS HERE
                                    // onError={(e) => {
                                    //     e.target.src = '/assets/images/default-avatar.png';
                                    // }}
                                />
                                <span className="author-name">{testimonial.name}</span>
                            </div>
                        </Card>
                    ))}
                </div>
            </section>

            {/* CTA Section */}
            <section className="cta-section">
                <div className="cta-content">
                    <h2 className="cta-title">Prêt à retrouver vos objets perdus?</h2>
                    <p className="cta-text">
                        Rejoignez des milliers d'utilisateurs qui ont déjà retrouvé leurs objets grâce à FoundIt!
                    </p>
                    <div className="cta-actions">
                        <Link to="/register" className="cta-button primary">
                            Créer un compte
                        </Link>
                        <Link to="/how-it-works" className="cta-button secondary">
                            En savoir plus
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    );
};

export default HomePage;