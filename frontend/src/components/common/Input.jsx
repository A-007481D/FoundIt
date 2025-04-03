import React from 'react';
import '../../styles/components.css';

const Input = ({
                   type = 'text',
                   placeholder,
                   value,
                   onChange,
                   label,
                   id,
                   name,
                   required = false,
                   icon,
                   error,
                   className = '',
                   onBlur,
                   min,
                   max,
                   pattern
               }) => {
    return (
        <div className={`input-container ${className}`}>
            {label && <label htmlFor={id} className="input-label">{label}</label>}
            <div className="input-field-container">
                {icon && <span className="input-icon">{icon}</span>}
                <input
                    id={id}
                    name={name}
                    type={type}
                    placeholder={placeholder}
                    value={value}
                    onChange={onChange}
                    onBlur={onBlur}
                    required={required}
                    min={min}
                    max={max}
                    pattern={pattern}
                    className={`input-field ${icon ? 'with-icon' : ''} ${error ? 'error' : ''}`}
                />
            </div>
            {error && <p className="input-error">{error}</p>}
        </div>
    );
};

export default Input;