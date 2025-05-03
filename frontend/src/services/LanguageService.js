import { ref, computed } from 'vue';
import en from '../lang/en.json';
import fr from '../lang/fr.json';

const translations = {
  'en': en,
  'fr': fr
};

export const useLanguage = () => {
  const currentLanguage = ref('en');
  
  const t = (key, params = {}) => {
    const parts = key.split('.');
    let translation = translations[currentLanguage.value];
    
    for (const part of parts) {
      if (!translation[part]) return key;
      translation = translation[part];
    }
    
    return Object.entries(params).reduce(
      (str, [key, value]) => str.replace(new RegExp(`{{${key}}}`), value),
      translation
    );
  };
  
  const setLanguage = (lang) => {
    if (translations[lang]) {
      currentLanguage.value = lang;
    }
  };
  
  const getCurrentLanguage = () => currentLanguage.value;
  
  return {
    t,
    setLanguage,
    getCurrentLanguage
  };
};
