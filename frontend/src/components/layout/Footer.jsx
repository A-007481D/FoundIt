import React from 'react';
import { Link } from 'react-router-dom';
import { FaFacebook, FaTwitter, FaInstagram, FaLinkedin, FaEnvelope, FaPhone, FaMapMarkerAlt } from 'react-icons/fa';
import '../../styles/layout.css';

const Footer = () => {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="footer">
            <div className="footer-container">
                <div className="footer-top">
                    <div className="footer-column">
                        <div className="footer-logo">
                            <img src="/assets/images/logo.png" alt="FoundIt Logo" />
                            <span>FoundIt!</span>
                        </div>
                        <p className="footer-description">
                            La plateforme qui vous aide à retrouver vos objets perdus et à rendre ceux que vous avez trouvés.
                        </p>
                        <div className="footer-social">
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <FaFacebook />
                            </a>
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                <FaTwitter />
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                <FaInstagram />
                            </a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                <FaLinkedin />
                            </a>
                        </div>
                    </div>

                    <div className="footer-column">
                        <h3 className="footer-title">Liens rapides</h3>
                        <ul className="footer-links">
                            <li><Link to="/">Accueil</Link></li>
                            <li><Link to="/dashboard">Tableau de bord</Link></li>
                            <li><Link to="/search">Recherche</Link></li>
                            <li><Link to="/matches">Correspondances</Link></li>
                            <li><Link to="/messages">Messages</Link></li>
                        </ul>
                    </div>

                    <div className="footer-column">
                        <h3 className="footer-title">Informations</h3>
                        <ul className="footer-links">
                            <li><Link to="/about">À propos</Link></li>
                            <li><Link to="/how-it-works">Comment ça marche</Link></li>
                            <li><Link to="/faq">FAQ</Link></li>
                            <li><Link to="/terms">Conditions d'utilisation</Link></li>
                            <li><Link to="/privacy">Politique de confidentialité</Link></li>
                        </ul>
                    </div>

                    <div className="footer-column">
                        <h3 className="footer-title">Contact</h3>
                        <ul className="footer-contact">
                            <li>
                                <FaEnvelope />
                                <a href="mailto:contact@foundit.com">contact@foundit.com</a>
                            </li>
                            <li>
                                <FaPhone />
                                <a href="tel:+33123456789">+33 1 23 45 67 89</a>
                            </li>
                            <li>
                                <FaMapMarkerAlt />
                                <span>123 Rue de Paris, 75001 Paris, France</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div className="footer-bottom">
                    <p className="copyright">
                        &copy; {currentYear} FoundIt. Tous droits réservés.
                    </p>
                    <div className="footer-bottom-links">
                        <Link to="/terms">Conditions d'utilisation</Link>
                        <Link to="/privacy">Politique de confidentialité</Link>
                        <Link to="/cookies">Cookies</Link>
                    </div>
                </div>
            </div>
        </footer>
    );
};

export default Footer;