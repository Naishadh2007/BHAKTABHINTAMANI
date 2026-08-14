import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useLanguage } from '../context/LanguageContext';
import { IconAlert, IconArrowRight } from '../components/Icons';
import './HomePage.css';

const API_BASE = import.meta.env.VITE_API_URL || '';

function SkeletonCard() {
  return (
    <div className="chapter-card-skeleton" aria-hidden="true">
      <div className="chapter-card-skeleton__order skeleton" />
      <div className="chapter-card-skeleton__title skeleton" />
    </div>
  );
}

export default function HomePage() {
  const [chapters, setChapters] = useState([]);
  const [loading, setLoading]   = useState(true);
  const [error, setError]       = useState(null);
  const { lang }                = useLanguage();

  const fetchChapters = async () => {
    try {
      setLoading(true);
      setError(null);
      const { data } = await axios.get(`${API_BASE}/api/chapters`);
      setChapters(data);
    } catch (err) {
      setError(lang === 'gu' ? 'પ્રકરણો લોડ થઈ શક્યા નથી. કૃપા કરીને બેકએન્ડ ચાલુ છે કે નહીં તે ચકાસો.' : 'Could not load chapters. Please make sure the backend is running.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchChapters();
  }, []);

  const getTitle = (chapter) => {
    if (lang === 'gu') return chapter.title_gu || chapter.title || `પ્રકરણ ${chapter.order}`;
    return chapter.title_en || chapter.title || `Chapter ${chapter.order}`;
  };

  return (
    <main id="main-content" className="home">
      <div className="page-container">
        {/* Hero */}
        <section className="home__hero" aria-labelledby="home-heading">
          <h1 id="home-heading" className="home__hero-title">
            {lang === 'gu' ? 'ભક્ત-ચિંતામણિ' : 'Bhakta-Chintamani'}
          </h1>
          <p className="home__hero-subtitle">
            {lang === 'gu'
              ? 'પવિત્ર ગ્રંથ અને પવન કથાઓનો ભક્તિસભર સંગ્રહ'
              : 'A sacred repository of devotional chapters and spiritual wisdom.'}
          </p>
          {!loading && !error && (
            <div className="home__meta">
              <span>{chapters.length} {lang === 'gu' ? 'પ્રકરણો' : 'Chapters'}</span>
            </div>
          )}
        </section>

        {/* Content Grid */}
        {loading ? (
          <div
            className="home__grid"
            role="status"
            aria-label="Loading chapters…"
          >
            {Array.from({ length: 8 }).map((_, i) => (
              <SkeletonCard key={i} />
            ))}
            <span className="sr-only">Loading chapters…</span>
          </div>
        ) : error ? (
          <div className="home__error" role="alert">
            <div className="home__error-icon">
              <IconAlert size={36} color="var(--accent)" />
            </div>
            <h2>{lang === 'gu' ? 'કંઈક ખોટું થયું' : 'Something went wrong'}</h2>
            <p>{error}</p>
            <button id="retry-btn" className="btn btn-primary" onClick={fetchChapters}>
              {lang === 'gu' ? 'ફરી પ્રયાસ કરો' : 'Try Again'}
            </button>
          </div>
        ) : (
          <div className="home__grid" role="list">
            {chapters.length === 0 ? (
              <p className="home__empty">
                {lang === 'gu' ? 'કોઈ પ્રકરણો મળ્યા નથી.' : 'No chapters found.'}
              </p>
            ) : (
              chapters.map((chapter) => (
                <Link
                  key={chapter.id}
                  to={`/chapter/${chapter.id}`}
                  id={`chapter-card-${chapter.id}`}
                  className="chapter-card"
                  role="listitem"
                  aria-label={`Chapter ${chapter.order}: ${getTitle(chapter)}`}
                >
                  <span className="chapter-card__order">
                    {lang === 'gu' ? `પ્રકરણ ${chapter.order}` : `Chapter ${chapter.order}`}
                  </span>
                  <h2 className="chapter-card__title">{getTitle(chapter)}</h2>
                  <span className="chapter-card__arrow" aria-hidden="true">
                    <IconArrowRight size={16} color="var(--accent)" />
                  </span>
                </Link>
              ))
            )}
          </div>
        )}
      </div>
    </main>
  );
}
