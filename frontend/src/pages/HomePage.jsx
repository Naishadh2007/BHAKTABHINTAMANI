import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useLanguage } from '../context/LanguageContext';
import { useReading } from '../context/ReadingContext';
import { IconAlert, IconArrowRight, IconBookmarkFilled, IconBook } from '../components/Icons';
import './HomePage.css';

const API_BASE = import.meta.env.VITE_API_URL || '';

const GUJARATI_DIGITS = ['૦', '૧', '૨', '૩', '૪', '૫', '૬', '૭', '૮', '૯'];

export const toGujaratiDigits = (num) => {
  if (num === null || num === undefined) return '';
  return String(num).replace(/[0-9]/g, (digit) => GUJARATI_DIGITS[Number(digit)]);
};

export const parseApiResponse = (data) => {
  if (!data) return null;
  if (typeof data === 'string') {
    try {
      return JSON.parse(data);
    } catch (e) {
      return null;
    }
  }
  return data;
};

function SkeletonCard() {
  return (
    <div className="chapter-card-skeleton" aria-hidden="true">
      <div className="chapter-card-skeleton__header">
        <div className="chapter-card-skeleton__badge skeleton" />
      </div>
      <div className="chapter-card-skeleton__title skeleton" />
    </div>
  );
}

export default function HomePage() {
  const [chapters, setChapters] = useState([]);
  const [loading, setLoading]   = useState(true);
  const [error, setError]       = useState(null);
  const { lang }                = useLanguage();
  const { bookmark }            = useReading();

  const fetchChapters = async () => {
    try {
      setLoading(true);
      setError(null);
      let res;
      try {
        res = await axios.get(`${API_BASE}/api/chapters`, {
          headers: { Accept: 'application/json' }
        });
      } catch (e) {
        res = await axios.get(`${API_BASE}/api_chapters.php`, {
          headers: { Accept: 'application/json' }
        });
      }

      let data = parseApiResponse(res?.data);
      if (!Array.isArray(data)) {
        try {
          const fallbackRes = await axios.get(`${API_BASE}/api_chapters.php`, {
            headers: { Accept: 'application/json' }
          });
          data = parseApiResponse(fallbackRes.data);
        } catch (e) {}
      }

      if (Array.isArray(data) && data.length > 0) {
        setChapters(data);
      } else if (data && Array.isArray(data.chapters) && data.chapters.length > 0) {
        setChapters(data.chapters);
      } else {
        setError(lang === 'gu' ? 'પ્રકરણો મળ્યા નથી.' : 'No chapters found.');
      }
    } catch (err) {
      console.error('API Error:', err);
      setError(lang === 'gu' ? 'પ્રકરણો લોડ થઈ શક્યા નથી. કૃપા કરીને ફરી પ્રયાસ કરો.' : 'Could not load chapters. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchChapters();
  }, []);

  const getTitle = (chapter) => {
    if (!chapter) return '';
    if (lang === 'gu') return chapter.title_gu || chapter.title || `પ્રકરણ ${toGujaratiDigits(chapter.order)}`;
    return chapter.title_en || chapter.title || `Chapter ${chapter.order}`;
  };

  return (
    <main id="main-content" className="home">
      <div className="page-container">
        {/* Hero */}
        <section className="home__hero" aria-labelledby="home-heading">
          <div className="home__hero-badge">
            <IconBook size={16} color="var(--accent)" />
            <span>{lang === 'gu' ? 'પવિત્ર ગ્રંથ સંગ્રહ' : 'Sacred Scripture'}</span>
          </div>
          <h1 id="home-heading" className="home__hero-title">
            {lang === 'gu' ? 'ભક્ત-ચિંતામણિ' : 'Bhakta-Chintamani'}
          </h1>
          <p className="home__hero-subtitle">
            {lang === 'gu'
              ? 'પવિત્ર ગ્રંથ અને પવન કથાઓનો ભક્તિસભર સંગ્રહ'
              : 'A sacred repository of devotional chapters and spiritual wisdom.'}
          </p>
          {!loading && !error && Array.isArray(chapters) && (
            <div className="home__meta">
              <span>
                {lang === 'gu' ? `${toGujaratiDigits(chapters.length)} પ્રકરણો ઉપલબ્ધ` : `${chapters.length} Chapters Available`}
              </span>
            </div>
          )}
        </section>

        {/* Modern Content Grid */}
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
            {!Array.isArray(chapters) || chapters.length === 0 ? (
              <p className="home__empty">
                {lang === 'gu' ? 'કોઈ પ્રકરણો મળ્યા નથી.' : 'No chapters found.'}
              </p>
            ) : (
              chapters.map((chapter) => {
                if (!chapter) return null;
                const isBookmarked = bookmark && bookmark.chapterId === chapter.id;
                const orderNum = chapter.order || 1;
                const formattedNum = lang === 'gu'
                  ? toGujaratiDigits(orderNum < 10 ? `0${orderNum}` : orderNum)
                  : (orderNum < 10 ? `0${orderNum}` : orderNum);

                return (
                  <Link
                    key={chapter.id}
                    to={`/chapter/${chapter.id}`}
                    id={`chapter-card-${chapter.id}`}
                    className={`chapter-card ${isBookmarked ? 'chapter-card--bookmarked' : ''}`}
                    role="listitem"
                    aria-label={`Chapter ${orderNum}: ${getTitle(chapter)}`}
                  >
                    {/* Watermark Background Number in Gujarati Rasa / English */}
                    <span className="chapter-card__bg-num font-rasa" aria-hidden="true">
                      {formattedNum}
                    </span>

                    {/* Card Content */}
                    <div className="chapter-card__body">
                      <div className="chapter-card__top">
                        <span className="chapter-card__order-badge font-rasa">
                          {lang === 'gu' ? `પ્રકરણ ${toGujaratiDigits(orderNum)}` : `Chapter ${orderNum}`}
                        </span>

                        {isBookmarked && (
                          <span className="chapter-card__bookmark-pill" title="Bookmarked">
                            <IconBookmarkFilled size={13} color="var(--accent)" />
                          </span>
                        )}
                      </div>

                      <h2 className="chapter-card__title font-rasa">{getTitle(chapter)}</h2>
                    </div>

                    {/* Subtle Elegant Left Accent Bar */}
                    <div className="chapter-card__accent-bar" aria-hidden="true" />
                  </Link>
                );
              })
            )}
          </div>
        )}
      </div>

      {/* ── Floating Bookmark FAB ── */}
      {bookmark && (
        <Link
          to={`/chapter/${bookmark.chapterId}`}
          className="home__bookmark-fab font-rasa"
          aria-label={lang === 'gu' ? `બુકમાર્ક: પ્રકરણ ${toGujaratiDigits(bookmark.order)}` : `Bookmark: Chapter ${bookmark.order}`}
          title={bookmark.title}
        >
          <IconBookmarkFilled size={20} color="var(--bg-main)" />
          <span className="home__bookmark-fab-label">
            {lang === 'gu' ? `પ્રકરણ ${toGujaratiDigits(bookmark.order)}` : `Ch. ${bookmark.order}`}
          </span>
        </Link>
      )}
    </main>
  );
}


