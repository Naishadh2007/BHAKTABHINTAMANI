import { useState, useEffect, useCallback } from 'react';
import { Link, useParams } from 'react-router-dom';
import axios from 'axios';
import { useLanguage } from '../context/LanguageContext';
import { useReading } from '../context/ReadingContext';
import { IconArrowLeft, IconAlert, IconChevronLeft, IconChevronRight } from '../components/Icons';
import './ChapterPage.css';

const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:8000';

function LoadingSkeleton() {
  return (
    <div className="chapter__loading" role="status" aria-label="Loading chapter…">
      <div className="chapter-skeleton__badge skeleton" />
      <div className="chapter-skeleton__title skeleton" />
      <div className="chapter-skeleton__content">
        {Array.from({ length: 8 }).map((_, i) => (
          <div key={i} className="chapter-skeleton__line skeleton" />
        ))}
      </div>
    </div>
  );
}

export default function ChapterPage() {
  const { id }                          = useParams();
  const [chapterData, setChapterData]   = useState(null);
  const [loading, setLoading]           = useState(true);
  const [error, setError]               = useState(null);
  const [progress, setProgress]         = useState(0);
  const { lang }                        = useLanguage();
  const { fontSize, setCurrentChapterId } = useReading();

  // Tell Navbar which chapter is open (for bookmark btn)
  useEffect(() => {
    setCurrentChapterId(id ? Number(id) : null);
    return () => setCurrentChapterId(null);
  }, [id, setCurrentChapterId]);

  const fetchChapter = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const { data } = await axios.get(`${API_BASE}/api/chapters/${id}`);
      if (data.chapter) {
        setChapterData(data);
      } else {
        setChapterData({ chapter: data, prev_chapter: null, next_chapter: null });
      }
    } catch (err) {
      if (err.response?.status === 404) {
        setError(lang === 'gu' ? 'પ્રકરણ મળ્યું નથી.' : 'Chapter not found.');
      } else {
        setError(lang === 'gu' ? 'પ્રકરણ લોડ થઈ શક્યું નથી.' : 'Could not load chapter. Please try again.');
      }
      console.error(err);
    } finally {
      setLoading(false);
    }
  }, [id, lang]);

  useEffect(() => {
    fetchChapter();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [fetchChapter]);

  // Reading progress bar
  useEffect(() => {
    const updateProgress = () => {
      const el = document.documentElement;
      const scrollTop  = el.scrollTop || document.body.scrollTop;
      const scrollable = el.scrollHeight - el.clientHeight;
      if (scrollable > 0) {
        setProgress(Math.min(100, (scrollTop / scrollable) * 100));
      }
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    return () => window.removeEventListener('scroll', updateProgress);
  }, []);

  const chapter     = chapterData?.chapter;
  const prevChapter = chapterData?.prev_chapter;
  const nextChapter = chapterData?.next_chapter;

  const getTitle = (ch) => {
    if (!ch) return '';
    if (lang === 'gu') return ch.title_gu || ch.title || `પ્રકરણ ${ch.order}`;
    return ch.title_en || ch.title || `Chapter ${ch.order}`;
  };

  const getContent = () => {
    if (!chapter) return '';
    if (lang === 'gu') return chapter.content_gu || chapter.content || '';
    return chapter.content_en || chapter.content || '';
  };

  const rawContent = getContent();

  return (
    <>
      {/* Reading Progress Bar */}
      <div
        className="chapter__progress"
        style={{ width: `${progress}%` }}
        role="progressbar"
        aria-valuenow={Math.round(progress)}
        aria-valuemin={0}
        aria-valuemax={100}
        aria-label="Reading progress"
      />

      <main id="main-content" className="chapter">
        <div className="page-container">
          {/* Top navigation */}
          <div className="chapter__top-nav">
            <Link to="/" id="back-to-home" className="btn btn-ghost btn-sm chapter__back-btn">
              <IconArrowLeft size={16} />
              {lang === 'gu' ? 'મુખ્ય પૃષ્ઠ' : 'Back to Chapters'}
            </Link>
          </div>

          {loading ? (
            <LoadingSkeleton />
          ) : error ? (
            <div className="chapter__error" role="alert">
              <IconAlert size={36} color="var(--accent)" />
              <h1>{lang === 'gu' ? 'પ્રકરણ મળ્યું નથી' : 'Chapter Not Found'}</h1>
              <p>{error}</p>
              <Link to="/" className="btn btn-primary" style={{ marginTop: '1rem' }}>
                {lang === 'gu' ? 'મુખ્ય પૃષ્ઠ પર પાછા જાઓ' : 'Return Home'}
              </Link>
            </div>
          ) : (
            <article className="chapter__article">
              {/* Chapter Header */}
              <header className="chapter__header">
                <span className="chapter__order-badge">
                  {lang === 'gu' ? `પ્રકરણ ${chapter.order}` : `Chapter ${chapter.order}`}
                </span>
                <h1 className="chapter__title">{getTitle(chapter)}</h1>
              </header>

              <div className="chapter__divider" aria-hidden="true" />

              {/* Reading content — font size from context */}
              <div
                className="chapter__reading-container font-rasa"
                style={{ fontSize: `${fontSize}px` }}
              >
                {rawContent.includes('<p>') || rawContent.includes('<div>') || rawContent.includes('style=') || rawContent.includes('<br>') ? (
                  <div
                    className="chapter__html-content"
                    dangerouslySetInnerHTML={{ __html: rawContent }}
                  />
                ) : (
                  <div className="chapter__text-content">
                    {rawContent.split('\n\n').map((paragraph, i) => (
                      <p key={i}>{paragraph}</p>
                    ))}
                  </div>
                )}
              </div>

              {/* ── Previous & Next — Glass buttons ── */}
              <nav className="chapter__footer-nav" aria-label="Chapter navigation">
                {prevChapter ? (
                  <Link
                    to={`/chapter/${prevChapter.id}`}
                    id={`prev-chapter-${prevChapter.id}`}
                    className="chapter__nav-glass chapter__nav-glass--prev"
                    aria-label={lang === 'gu' ? `અગાઉનું: ${getTitle(prevChapter)}` : `Previous: ${getTitle(prevChapter)}`}
                    title={getTitle(prevChapter)}
                  >
                    <IconChevronLeft size={22} color="currentColor" />
                  </Link>
                ) : (
                  <div className="chapter__nav-glass chapter__nav-glass--disabled" aria-hidden="true">
                    <IconChevronLeft size={22} color="currentColor" />
                  </div>
                )}

                <div className="chapter__nav-center">
                  <span className="chapter__nav-current">
                    {lang === 'gu' ? `પ્રકરણ ${chapter.order}` : `Chapter ${chapter.order}`}
                  </span>
                  <span className="chapter__nav-dots" aria-hidden="true">
                    {[prevChapter, chapter, nextChapter].map((ch, i) => (
                      <span
                        key={i}
                        className={`chapter__nav-dot ${ch?.id === chapter?.id ? 'chapter__nav-dot--active' : ''} ${!ch ? 'chapter__nav-dot--empty' : ''}`}
                      />
                    ))}
                  </span>
                </div>

                {nextChapter ? (
                  <Link
                    to={`/chapter/${nextChapter.id}`}
                    id={`next-chapter-${nextChapter.id}`}
                    className="chapter__nav-glass chapter__nav-glass--next"
                    aria-label={lang === 'gu' ? `આગળ: ${getTitle(nextChapter)}` : `Next: ${getTitle(nextChapter)}`}
                    title={getTitle(nextChapter)}
                  >
                    <IconChevronRight size={22} color="currentColor" />
                  </Link>
                ) : (
                  <div className="chapter__nav-glass chapter__nav-glass--disabled" aria-hidden="true">
                    <IconChevronRight size={22} color="currentColor" />
                  </div>
                )}
              </nav>
            </article>
          )}
        </div>
      </main>
    </>
  );
}
