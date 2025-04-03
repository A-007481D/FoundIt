import React from 'react';
import '../../styles/components.css';

const Card = ({
                  children,
                  className = '',
                  title,
                  subtitle,
                  footer,
                  onClick,
                  hoverable = false
              }) => {
    return (
        <div
            className={`card ${hoverable ? 'hoverable' : ''} ${className}`}
            onClick={onClick}
        >
            {(title || subtitle) && (
                <div className="card-header">
                    {title && <h3 className="card-title">{title}</h3>}
                    {subtitle && <p className="card-subtitle">{subtitle}</p>}
                </div>
            )}
            <div className="card-body">
                {children}
            </div>
            {footer && (
                <div className="card-footer">
                    {footer}
                </div>
            )}
        </div>
    );
};

export default Card;