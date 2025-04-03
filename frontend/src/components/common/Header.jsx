import React from 'react';
import '../../styles/components.css';

const Header = ({
                    title,
                    subtitle,
                    actions,
                    className = '',
                    size = 'lg'
                }) => {
    return (
        <div className={`page-header ${size} ${className}`}>
            <div className="header-content">
                <h1 className="header-title">{title}</h1>
                {subtitle && <p className="header-subtitle">{subtitle}</p>}
            </div>
            {actions && (
                <div className="header-actions">
                    {actions}
                </div>
            )}
        </div>
    );
};

export default Header;