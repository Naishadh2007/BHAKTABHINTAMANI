import { createContext, useContext, useState, useEffect } from 'react';

const LanguageContext = createContext(null);

export function LanguageProvider({ children }) {
  const [lang, setLang] = useState(() => {
    const saved = localStorage.getItem('rv-lang');
    return saved === 'en' || saved === 'gu' ? saved : 'gu'; // Default Gujarati
  });

  useEffect(() => {
    localStorage.setItem('rv-lang', lang);
  }, [lang]);

  const toggleLanguage = () => setLang(l => (l === 'gu' ? 'en' : 'gu'));

  return (
    <LanguageContext.Provider value={{ lang, setLang, toggleLanguage }}>
      {children}
    </LanguageContext.Provider>
  );
}

export function useLanguage() {
  const ctx = useContext(LanguageContext);
  if (!ctx) throw new Error('useLanguage must be used within LanguageProvider');
  return ctx;
}
