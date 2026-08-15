import { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { useLanguage } from '../context/LanguageContext';
import { useReading } from '../context/ReadingContext';
import {
  IconBook, IconSun, IconMoon,
  IconTranslate, IconDotsThree,
  IconBookmark, IconBookmarkFilled,
} from './Icons';
import './Navbar.css';

const FONT_SIZES = [20, 22, 24, 26, 28, 30];

export default function Navbar() {
  const { theme, toggleTheme }   = useTheme();
  const { lang, toggleLanguage } = useLanguage();
  const location = useLocation();

  const { fontSize, setFontSize, bookmark, toggleBookmark, isBookmarked, currentChapterId } = useReading();

  const isChapterPage   = location.pathname.startsWith('/chapter/');
  const chapterBookmark = currentChapterId ? isBookmarked(Number(currentChapterId)) : false;

  // 3-dot popup state
  const [menuOpen, setMenuOpen] = useState(false);
  const menuRef  = useRef(null);
  const dotsBtnRef = useRef(null);

  // Close popup on outside click
  useEffect(() => {
    if (!menuOpen) return;
    const handler = (e) => {
      if (
        menuRef.current && !menuRef.current.contains(e.target) &&
        dotsBtnRef.current && !dotsBtnRef.current.contains(e.target)
      ) {
        setMenuOpen(false);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [menuOpen]);

  // Close popup on route change
  useEffect(() => { setMenuOpen(false); }, [location.pathname]);

  const fontIdx    = FONT_SIZES.indexOf(fontSize);
  const canDecrease = fontIdx > 0;
  const canIncrease = fontIdx < FONT_SIZES.length - 1;

  return (
    <header className="navbar" role="banner">
      <div className="navbar__inner">

        {/* ── Brand ── */}
        <Link to="/" className="navbar__brand" aria-label="Bhakta-Chintamani — Go to home">
          <span className="navbar__brand-icon" aria-hidden="true">
            <IconBook size={22} color="var(--accent)" />
          </span>
          <span className="navbar__brand-name">Bhakta-Chintamani</span>
        </Link>

        {/* ── Right actions ── */}
        <div className="navbar__actions">

          {/* ── Language Toggle (icon button like theme toggle) ── */}
          <button
            id="lang-toggle-btn"
            className="glass-btn glass-btn--icon glass-btn--lang"
            onClick={toggleLanguage}
            aria-label={lang === 'gu' ? 'Switch to English' : 'ગુજરાતીમાં બદલો'}
            title={lang === 'gu' ? 'Switch to English' : 'Switch to Gujarati'}
          >
            <IconTranslate size={18} color="currentColor" />
            <span className="glass-btn__lang-badge">{lang === 'gu' ? 'GU' : 'EN'}</span>
          </button>

          {/* ── Theme Toggle ── */}
          <button
            id="theme-toggle"
            className="glass-btn glass-btn--icon"
            onClick={toggleTheme}
            aria-label={`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`}
            title={theme === 'dark' ? 'Light mode' : 'Dark mode'}
          >
            {theme === 'dark'
              ? <IconSun  size={19} color="var(--accent)" />
              : <IconMoon size={19} color="currentColor" />}
          </button>

          {/* ── 3-dot Menu ── */}
          <div className="navbar__menu-wrap" style={{ position: 'relative' }}>
            <button
              id="more-menu-btn"
              ref={dotsBtnRef}
              className={`glass-btn glass-btn--icon ${menuOpen ? 'glass-btn--active' : ''}`}
              onClick={() => setMenuOpen(o => !o)}
              aria-label="More options"
              aria-expanded={menuOpen}
              aria-haspopup="true"
              title="More options"
            >
              <IconDotsThree size={18} color="currentColor" />
            </button>

            {/* ── Popup ── */}
            {menuOpen && (
              <div
                ref={menuRef}
                className="navbar__popup"
                role="dialog"
                aria-label="Reading options"
              >
                {/* Arrow */}
                <div className="navbar__popup-arrow" aria-hidden="true" />

                {/* ── Font Size Section ── */}
                <div className="popup-section">
                  <div className="popup-section__label">
                    {lang === 'gu' ? 'ટેક્સ્ટ સાઇઝ' : 'Text Size'}
                  </div>
                  <div className="popup-fontsize">
                    <button
                      id="popup-font-dec"
                      className="popup-fontsize__btn"
                      onClick={() => canDecrease && setFontSize(FONT_SIZES[fontIdx - 1])}
                      disabled={!canDecrease}
                      aria-label="Decrease font size"
                    >
                      <span className="popup-fontsize__a popup-fontsize__a--sm">A</span>
                    </button>

                    <div className="popup-fontsize__track" aria-hidden="true">
                      {FONT_SIZES.map((s, i) => (
                        <button
                          key={s}
                          className={`popup-fontsize__dot ${fontSize === s ? 'popup-fontsize__dot--active' : ''}`}
                          onClick={() => setFontSize(s)}
                          aria-label={`Font size ${s}px`}
                          title={`${s}px`}
                        />
                      ))}
                    </div>

                    <button
                      id="popup-font-inc"
                      className="popup-fontsize__btn"
                      onClick={() => canIncrease && setFontSize(FONT_SIZES[fontIdx + 1])}
                      disabled={!canIncrease}
                      aria-label="Increase font size"
                    >
                      <span className="popup-fontsize__a popup-fontsize__a--lg">A</span>
                    </button>
                  </div>
                  <div className="popup-fontsize__value" aria-live="polite">
                    {fontSize}px
                  </div>
                </div>

                {/* ── Bookmark Section (only on chapter page) ── */}
                {isChapterPage && (
                  <>
                    <div className="popup-divider" aria-hidden="true" />
                    <div className="popup-section">
                      <div className="popup-section__label">
                        {lang === 'gu' ? 'બુકમાર્ક' : 'Bookmark'}
                      </div>
                      <button
                        id="popup-bookmark-btn"
                        className={`popup-bookmark-btn ${chapterBookmark ? 'popup-bookmark-btn--active' : ''}`}
                        onClick={() => currentChapterId && toggleBookmark(Number(currentChapterId), '', Number(currentChapterId))}
                        aria-label={chapterBookmark ? 'Remove bookmark' : 'Add bookmark'}
                      >
                        {chapterBookmark
                          ? <IconBookmarkFilled size={16} color="var(--accent)" />
                          : <IconBookmark size={16} color="currentColor" />}
                        <span>
                          {chapterBookmark
                            ? (lang === 'gu' ? 'સેવ થઈ ગઈ' : 'Bookmarked')
                            : (lang === 'gu' ? 'બુકમાર્ક ઉમેરો' : 'Add Bookmark')}
                        </span>
                      </button>
                    </div>
                  </>
                )}
              </div>
            )}
          </div>

        </div>
      </div>
    </header>
  );
}
